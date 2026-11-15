<#
  Builds an upload-ready zip for any cPanel / shared host (paid hosting).
  Run on your PC:
      powershell -ExecutionPolicy Bypass -File deploy\pack-for-cpanel.ps1
  It asks for your domain and the database details from cPanel (password is typed hidden and is only
  written into the .env inside the zip, nothing else on disk).
  Result: storage\backups\shared-hosting\cpanel\site.zip  +  database.sql
#>
param(
    [string]$AppUrl,
    [string]$DbHost = 'localhost',
    [string]$DbName,
    [string]$DbUser,
    [string]$DbPass,
    [switch]$NoHttpsRedirect,
    [string]$Package = (Join-Path (Split-Path -Parent $PSScriptRoot) 'storage\backups\shared-hosting')
)

$ErrorActionPreference = 'Stop'
$siteDir = Join-Path $Package 'site'
if (-not (Test-Path (Join-Path $siteDir 'vendor\autoload.php'))) { throw "Package not found in $siteDir. Build it first." }

if (-not $AppUrl) { $AppUrl = Read-Host 'Your site address, e.g. https://ifan.com' }
if (-not $DbName) { $DbName = Read-Host 'Database name (cPanel, e.g. cpuser_portfolio)' }
if (-not $DbUser) { $DbUser = Read-Host 'Database user (cPanel, e.g. cpuser_ifan)' }
if (-not $DbPass) {
    $sec = Read-Host 'Database password (hidden)' -AsSecureString
    $ptr = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($sec)
    try { $DbPass = [Runtime.InteropServices.Marshal]::PtrToStringBSTR($ptr) } finally { [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($ptr) }
}
$AppUrl = $AppUrl.TrimEnd('/')
if ($AppUrl -notmatch '^https?://') { $AppUrl = 'https://' + $AppUrl }
$secure = $AppUrl.StartsWith('https')

$utf8 = New-Object Text.UTF8Encoding $false
$envFile = Join-Path $siteDir '.env'
$htFile = Join-Path $siteDir '.htaccess'
$origEnv = [IO.File]::ReadAllText($envFile)
$origHt = [IO.File]::ReadAllText($htFile)
$out = Join-Path $Package 'cpanel'
New-Item -ItemType Directory -Force $out | Out-Null
$zip = Join-Path $out 'site.zip'
if (Test-Path $zip) { Remove-Item $zip -Force }

function Set-EnvLine([string]$text, [string]$key, [string]$value) {
    return [regex]::Replace($text, "(?m)^$key=.*$", [Text.RegularExpressions.MatchEvaluator] { param($m) "$key=$value" })
}

try {
    $e = $origEnv
    $e = Set-EnvLine $e 'APP_URL' $AppUrl
    $e = Set-EnvLine $e 'DB_HOST' $DbHost
    $e = Set-EnvLine $e 'DB_DATABASE' $DbName
    $e = Set-EnvLine $e 'DB_USERNAME' $DbUser
    $e = Set-EnvLine $e 'DB_PASSWORD' $DbPass
    $e = Set-EnvLine $e 'SESSION_SECURE_COOKIE' ($(if ($secure) { 'true' } else { 'false' }))
    [IO.File]::WriteAllText($envFile, $e, $utf8)

    # Optional: no forced https until the SSL certificate is active
    $h = $origHt
    if ($NoHttpsRedirect -or -not $secure) {
        $h = [regex]::Replace($h, '(?ms)\s*# Always use HTTPS\s*RewriteCond %\{HTTPS\} off\s*RewriteRule \^ https://%\{HTTP_HOST\}%\{REQUEST_URI\} \[L,R=301\]', '')
    }
    [IO.File]::WriteAllText($htFile, $h, $utf8)

    & tar -a -cf $zip -C $siteDir .
    if ($LASTEXITCODE -ne 0) { throw 'tar failed' }
}
finally {
    [IO.File]::WriteAllText($envFile, $origEnv, $utf8)   # package folder never keeps the password
    [IO.File]::WriteAllText($htFile, $origHt, $utf8)
    $DbPass = $null
}

$sql = Join-Path $Package 'database.sql'
if (Test-Path $sql) { Copy-Item $sql (Join-Path $out 'database.sql') -Force }
Get-ChildItem $out | Select-Object Name, @{n = 'MB'; e = { [math]::Round($_.Length / 1MB, 2) } }
Write-Host "`nReady: $out"
Write-Host 'Next: follow deploy\CPANEL_GUIDE.md (upload site.zip, Extract, import database.sql).'
