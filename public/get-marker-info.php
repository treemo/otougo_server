<?php

if (empty($_GET['id'])) {
    exit('{"error": "missing parameter"}');
}

include __DIR__ . '/../lib/marker/MarkerCommon.class.php';
$t = new MarkerCommon();
$t->load($_GET['id']);
echo json_encode($t->getData());

include __DIR__ . '/../lib/stats/Stats.class.php';
Stats::addActionGetMarkerInfo($_GET['id'], get_class($t));