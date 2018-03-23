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

// Instantiate Model
// $classname = ucfirst($route->model);
// ${$route->model} = new $classname( $route );
// global ${$route->model};

if( isset($route->query['update_db']) ) {
    $reg = new Reg( $route->regid );
    $reg->update($route->query);

    if( isset($route->query['date_arrived']) ) {
        $reg->add_label_to_print_queue( $route->regid, $route->printer );
    }
}


if( $route->target !== null ) {
    header('Location: /'.$route->page_url(
        $route->target,
        isset($route->query['s_regid']) ? 's_regid' : '',
        isset($route->query['s_regid']) ? $route->query['s_regid'] : ''
    ));
    exit;
}

// View
require("views/page_".$route->get_page().".php");
