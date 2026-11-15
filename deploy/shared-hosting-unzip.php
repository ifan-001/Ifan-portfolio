<?php
/*
 * One-time extractor for shared hosting without SSH.
 * deploy/upload-to-infinityfree.ps1 uploads this file (with a random token) next to site.zip,
 * then you open it once in the browser. It unpacks in small batches (hosts limit run time),
 * refreshes itself until finished, and then deletes itself and site.zip.
 */
$token = '__TOKEN__';
if (! hash_equals($token, (string) ($_GET['k'] ?? ''))) {
    http_response_code(404);
    exit('Not found');
}

@set_time_limit(60);
@ini_set('memory_limit', '256M');
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');

$root = __DIR__;
$zipPath = $root . '/site.zip';
$stateFile = $root . '/.setup-state';
$budget = 20; // seconds of work per request

if (! class_exists('ZipArchive')) {
    exit('ERROR: PHP zip extension is not available on this host.');
}
// Hosts cap single uploads (InfinityFree: about 10 MB), so site.zip arrives as site.part001, 002 ...
if (! is_file($zipPath)) {
    $parts = glob($root . '/site.part*');
    sort($parts);
    if (! $parts) {
        exit('ERROR: site.zip (or site.part001 ...) not found next to this script.');
    }
    $expected = (int) '__SIZE__';
    $sum = 0;
    foreach ($parts as $part) {
        $sum += filesize($part);
    }
    if ($expected > 0 && $sum !== $expected) {
        exit('ERROR: parts are incomplete (' . $sum . ' of ' . $expected . ' bytes). Run the upload script again.');
    }
    $out = fopen($zipPath . '.tmp', 'wb');
    foreach ($parts as $part) {
        $in = fopen($part, 'rb');
        stream_copy_to_stream($in, $out);
        fclose($in);
    }
    fclose($out);
    rename($zipPath . '.tmp', $zipPath);
    foreach ($parts as $part) {
        @unlink($part);
    }
}

$zip = new ZipArchive();
if ($zip->open($zipPath) !== true) {
    exit('ERROR: cannot open site.zip (upload may be incomplete).');
}

$total = $zip->numFiles;
$i = is_file($stateFile) ? (int) file_get_contents($stateFile) : 0;
$started = microtime(true);
$deferred = []; // root .htaccess is written last so this script stays reachable

$extract = function (int $index, string $name) use ($zip, $root): void {
    $target = $root . '/' . $name;
    if (! is_dir(dirname($target))) {
        @mkdir(dirname($target), 0755, true);
    }
    $in = $zip->getStream($zip->getNameIndex($index));
    if (! $in) {
        return;
    }
    $out = fopen($target, 'wb');
    stream_copy_to_stream($in, $out);
    fclose($out);
    fclose($in);
};

$clean = function (string $raw): string {
    $name = str_replace('\\', '/', $raw);
    return preg_replace('#^(\./)+#', '', $name);
};

for (; $i < $total; $i++) {
    if (microtime(true) - $started > $budget) {
        break;
    }
    $name = $clean($zip->getNameIndex($i));
    if ($name === '' || preg_match('#(^|/)\.\.(/|$)#', $name)) {
        continue;
    }
    if (substr($name, -1) === '/') {
        @mkdir($root . '/' . $name, 0755, true);
        continue;
    }
    if ($name === '.htaccess') {
        continue; // handled at the end
    }
    $extract($i, $name);
}

if ($i >= $total) {
    for ($j = 0; $j < $total; $j++) {
        if ($clean($zip->getNameIndex($j)) === '.htaccess') {
            $extract($j, '.htaccess');
        }
    }
    $zip->close();
    @unlink($stateFile);
    @unlink($zipPath);
    echo 'DONE ' . $total . ' entries extracted. This setup file has been removed.';
    @unlink(__FILE__);
    exit;
}

$zip->close();
file_put_contents($stateFile, (string) $i);
$url = htmlspecialchars('?k=' . urlencode($token), ENT_QUOTES);
echo '<meta http-equiv="refresh" content="1;url=' . $url . '">';
echo 'PROGRESS ' . $i . ' / ' . $total . ' ... keep this tab open';
