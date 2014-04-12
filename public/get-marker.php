<?php

if (empty($_GET['la']) || empty($_GET['lo']) || empty($_GET['d'])) {
    exit('{"error": "missing parameter"}');
}

if (empty($_GET['t'])) {
    $_GET['t'] = 0;
}
// TODO => ajouter le fuseau oraire du client

if (empty($_GET['f'])) {
    $_GET['f'] = '';
}

include __DIR__ . '/../lib/marker/MarkerManager.class.php';
echo json_encode( MarkerManager::getZone($_GET['la'], $_GET['lo'], $_GET['d'], $_GET['t'], $_GET['f']));

include __DIR__ . '/../lib/stats/Stats.class.php';
Stats::addActionGetMarker($_GET['la'], $_GET['lo'], $_GET['d'], $_GET['t'], $_GET['f']);