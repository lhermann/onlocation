<?php

$root = dirname(__DIR__);
require_once($root."/config.php");
require_once($root."/lib/mysql.php");
global $db;

// Route
require('router.php');
global $route;

// Require Models
require_once("models/reglist.php");
require_once("models/reg.php");

// Update the Database
if( isset($route->query['update_db']) ) {
    $reg = new Reg( $route->regid );
    $reg->update($route->query);
}

// If route has a target, do a redirect
if( $route->target !== null ) {
    // var_dump($route); die();
    header('Location: /'.$route->page_url(
        $route->target,
        isset($route->query['s_regid']) ? 's_regid' : null,
        isset($route->query['s_regid']) ? $route->query['s_regid'] : null
    ));
    exit;
}

// Require View
require($route->get_view_file());
