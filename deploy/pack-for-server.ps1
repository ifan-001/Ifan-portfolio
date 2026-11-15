<#
  Run this on your Windows PC (from the project folder) before the first deploy:
      powershell -ExecutionPolicy Bypass -File deploy\pack-for-server.ps1
  It creates two files in storage\backups\ that you upload to the server with scp:
    - database.sqlite        your current content (used once by portfolio:import-sqlite)
    - media.tar.gz           profile photo, CV, project screenshots and feed uploads
  The profile photo and feed uploads are NOT in git, so this step is required.
#>
$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $PSScriptRoot
Set-Location $root

$out = Join-Path $root 'storage\backups'
New-Item -ItemType Directory -Force -Path $out | Out-Null

$sqlite = Join-Path $root 'database\database.sqlite'
if (-not (Test-Path $sqlite)) { throw "database\database.sqlite not found" }

# Consistent copy of the database (merges the SQLite write-ahead log first)
& php artisan tinker --execute="DB::statement('PRAGMA wal_checkpoint(TRUNCATE)');" | Out-Null
Copy-Item $sqlite (Join-Path $out 'database.sqlite') -Force

$media = Join-Path $out 'media.tar.gz'
if (Test-Path $media) { Remove-Item $media -Force }
& tar -czf $media public/images public/uploads public/resume
if ($LASTEXITCODE -ne 0) { throw "tar failed" }

Get-ChildItem $out -Include 'database.sqlite', 'media.tar.gz' -Recurse | Select-Object Name, @{n='MB'; e={[math]::Round($_.Length / 1MB, 2)}}
Write-Host "`nDone. Files are in: $out"
Write-Host "Upload them, for example:"
Write-Host "  scp -i <your-key> `"$out\database.sqlite`" `"$out\media.tar.gz`" ubuntu@<server-ip>:~/"
