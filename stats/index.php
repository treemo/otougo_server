<?php

include __DIR__ . '/../lib/stats/Stats.class.php';

$statTab = array(
    'index'    => array(
        'pageName'      => 'Navigateur internet',
        'include'       => 'statsNavigateur',
    ),
);

$page = empty($_GET['p']) || empty($statTab[$_GET['p']]) ? 'index' : $_GET['p'];
$statsData = $statTab[$page];

include __DIR__ . '/template/layout_top.php';
include __DIR__ . '/template/'.$statsData['include'].'.php';
include __DIR__ . '/template/layout_bottom.php';