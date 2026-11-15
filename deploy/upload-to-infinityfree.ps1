<#
  Uploads the prepared package to InfinityFree / any FTP shared host without SSH.
  It sends a zip in small parts plus a one-time extractor (about 7 uploads instead of 6,500).

  Run on your PC:
      powershell -ExecutionPolicy Bypass -File deploy\upload-to-infinityfree.ps1

  It asks for your hosting account password (typed hidden, never saved to disk).
  Afterwards it prints a one-time URL; open it in the browser and wait until it says DONE.
#>
param(
    [string]$FtpHost = 'ftpupload.net',
    [string]$FtpUser = 'if0_43446922',
    [string]$Site    = 'http://ifan-portfolio.gt.tc',
    [string]$Package = (Join-Path (Split-Path -Parent $PSScriptRoot) 'storage\backups\shared-hosting'),
    [int]$PartMB     = 4,
    [switch]$DryRun
)

$ErrorActionPreference = 'Stop'
$siteDir = Join-Path $Package 'site'
$envFile = Join-Path $siteDir '.env'
if (-not (Test-Path $envFile)) { throw "Package not found: $siteDir" }
$extractorSrc = Join-Path $PSScriptRoot 'shared-hosting-unzip.php'
$tmp = Join-Path $env:TEMP ('ifan-upload-' + [guid]::NewGuid().ToString('N').Substring(0, 8))
New-Item -ItemType Directory -Force $tmp | Out-Null
$zipPath = Join-Path $tmp 'site.zip'
$originalEnv = [IO.File]::ReadAllText($envFile)
$utf8 = New-Object Text.UTF8Encoding $false
$password = $null
$script:mode = 'tls'   # 'tls' first, falls back to 'plain' only if the host refuses encryption

function Get-PlainPassword {
    $secure = Read-Host 'InfinityFree account password (hidden)' -AsSecureString
    $ptr = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($secure)
    try { return [Runtime.InteropServices.Marshal]::PtrToStringBSTR($ptr) }
    finally { [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($ptr) }
}

# Runs curl.exe; the login is passed through stdin so the password never appears in a process list.
function Invoke-Curl([string[]]$extra, [switch]$ShowProgress) {
    $user = ($FtpUser + ':' + $script:password).Replace('\', '\\').Replace('"', '\"')
    $args = @('-K', '-', '--connect-timeout', '30', '--max-time', '1200', '--ftp-skip-pasv-ip')
    if ($script:mode -eq 'tls') { $args += '--ssl-reqd' }
    if ($ShowProgress) { $args += @('-#', '-S') } else { $args += @('-s', '-S') }
    $prev = $ErrorActionPreference
    $ErrorActionPreference = 'Continue'   # curl writes progress/errors to stderr; do not treat that as a PowerShell error
    try {
        $out = ('user = "' + $user + '"') | & curl.exe @args @extra 2>&1
        $code = $LASTEXITCODE
    } finally { $ErrorActionPreference = $prev }
    return @{ Code = $code; Out = (($out | ForEach-Object { "$_" }) -join "`n") }
}

function Test-Login {
    foreach ($m in 'tls', 'plain') {
        $script:mode = $m
        $r = Invoke-Curl @('--list-only', "ftp://$FtpHost/htdocs/", '-o', 'NUL')
        if ($r.Code -eq 0) {
            if ($m -eq 'tls') { Write-Host 'Logged in (encrypted FTP).' } else { Write-Host 'Logged in (plain FTP: this host did not accept encryption).' }
            return
        }
        if ($r.Code -eq 67 -or $r.Out -match 'Login denied|530') { throw 'FTP login failed: wrong password? Run the script again.' }
    }
    throw 'Cannot reach the FTP server. Check your internet connection and try again.'
}

function Remove-Remote([string]$name) {
    [void](Invoke-Curl @('-Q', "-DELE $name", '--list-only', "ftp://$FtpHost/htdocs/", '-o', 'NUL'))
}

function Send-File([string]$local, [string]$name) {
    $r = Invoke-Curl @('-T', $local, "ftp://$FtpHost/htdocs/$name") -ShowProgress
    # Windows' TLS stack often reports an error while closing the data connection AFTER every byte was sent.
    # So an error here is not final: the caller verifies the size on the server.
    if ($r.Code -ne 0) { Write-Host "  (curl exit $($r.Code) at the end of the transfer, checking the size on the server)" }
}

function Get-RemoteSize([string]$name) {
    $r = Invoke-Curl @('-I', "ftp://$FtpHost/htdocs/$name")
    if ($r.Code -ne 0) { return -1 }
    if ($r.Out -match 'Content-Length:\s*(\d+)') { return [int64]$Matches[1] }
    return -1
}

try {
    if (-not $DryRun) { $password = Get-PlainPassword } else { $password = 'dry-run-password' }
    if ([string]::IsNullOrWhiteSpace($password)) { throw 'No password entered.' }

    # DB password goes only into the zip, the package folder is restored right after
    $patched = [regex]::Replace($originalEnv, '(?m)^DB_PASSWORD=.*$', [Text.RegularExpressions.MatchEvaluator] { param($m) 'DB_PASSWORD=' + $password })
    [IO.File]::WriteAllText($envFile, $patched, $utf8)
    Write-Host 'Building site.zip ...'
    & tar -a -cf $zipPath -C $siteDir .
    if ($LASTEXITCODE -ne 0) { throw 'tar failed' }
    [IO.File]::WriteAllText($envFile, $originalEnv, $utf8)
    $zipBytes = (Get-Item $zipPath).Length
    $zipMb = [math]::Round($zipBytes / 1MB, 1)

    $bytes = New-Object byte[] 16
    $rng = [Security.Cryptography.RandomNumberGenerator]::Create(); $rng.GetBytes($bytes); $rng.Dispose()
    $token = -join ($bytes | ForEach-Object { $_.ToString('x2') })
    $extractor = Join-Path $tmp "setup-$token.php"
    [IO.File]::WriteAllText($extractor, ([IO.File]::ReadAllText($extractorSrc)).Replace('__TOKEN__', $token).Replace('__SIZE__', [string]$zipBytes), $utf8)
    $url = "$Site/setup-$token.php?k=$token"

    if ($DryRun) {
        Write-Host "DRY RUN ok. zip=$zipMb MB, extractor=$(Split-Path $extractor -Leaf), url=$url"
        return
    }

    Test-Login

    Write-Host 'Cleaning old upload leftovers ...'
    $listing = Invoke-Curl @('--list-only', "ftp://$FtpHost/htdocs/")
    $leftovers = @($listing.Out -split "`r?`n" | ForEach-Object { $_.Trim() } | Where-Object { $_ -match '^site\.(zip|part\d+)$' })
    foreach ($left in $leftovers) { Remove-Remote $left }

    $partSize = $PartMB * 1MB
    $zipStream = [IO.File]::OpenRead($zipPath)
    $count = 0
    try {
        $buffer = New-Object byte[] $partSize
        while (($read = $zipStream.Read($buffer, 0, $partSize)) -gt 0) {
            $count++
            $name = 'site.part{0:000}' -f $count
            $partPath = Join-Path $tmp $name
            $fs = [IO.File]::Create($partPath); $fs.Write($buffer, 0, $read); $fs.Close()
            for ($try = 1; $try -le 4; $try++) {
                try {
                    Send-File $partPath $name
                    $remote = Get-RemoteSize $name
                    if ($remote -eq $read) { break }
                    throw "size mismatch ($remote of $read bytes)"
                } catch {
                    if ($try -eq 4) { throw "Upload of $name failed: $($_.Exception.Message)" }
                    Write-Host "Retrying $name ($try/4): $($_.Exception.Message)"
                    Remove-Remote $name
                }
            }
            Remove-Item $partPath -Force
            Write-Host ('  {0} done ({1:N1} MB)' -f $name, ($read / 1MB))
        }
    } finally { $zipStream.Close() }
    Write-Host "All $count parts uploaded ($zipMb MB)."

    Send-File $extractor (Split-Path $extractor -Leaf)
    Write-Host 'Extractor uploaded.'

    Set-Content -Path (Join-Path $Package 'setup-url.txt') -Value $url -Encoding ascii -NoNewline
    Write-Host ''
    Write-Host 'DONE uploading. Now open this address in your browser and wait until it says DONE:' -ForegroundColor Green
    Write-Host $url -ForegroundColor Cyan
}
finally {
    [IO.File]::WriteAllText($envFile, $originalEnv, $utf8)
    Remove-Item $tmp -Recurse -Force -ErrorAction SilentlyContinue
    $password = $null
}
