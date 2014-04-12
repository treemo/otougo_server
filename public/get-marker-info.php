<?php

if (empty($_GET['id'])) {
    exit('{"error": "missing parameter"}');
}

include __DIR__ . '/../lib/marker/MarkerArretTransport.class.php';
$t = new MarkerArretTransport();
echo $t->load($_GET['id'])->getData();

include __DIR__ . '/../lib/stats/Stats.class.php';
Stats::addActionGetMarkerInfo($_GET['id'], get_class($t));