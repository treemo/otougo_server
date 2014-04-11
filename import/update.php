<?php

include __DIR__ . '/../lib/marker/MarkerManager.class.php';
include __DIR__ . '/../lib/functions.php';

$dirList = array(
    'bicycle' => array(
        'jcdecaux',
    ),
);

$totalScript = 0;
foreach ($dirList as $scriptList) {
    $totalScript += count($scriptList);
}

set_time_limit(0);
ignore_user_abort(true);

ob_implicit_flush(true);
header('Content-type: text/plain');
echo $totalScript, "\n";

$nbScript = 0;
foreach ($dirList as $dir => $scriptList) {
    foreach ($scriptList as $script) {
        echo round(100 * $nbScript++ / $totalScript), "\n";
        include __DIR__ . "/$dir/$script.php";
    }
}

echo 100, "\n";