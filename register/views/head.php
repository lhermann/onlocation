<?php
global $route;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>OnLocation >> <?= ucfirst($route->get_page()) ?></title>

    <!-- Bootstrap core CSS -->
    <link href="/register/assets/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .table td { vertical-align: middle !important; }
        .table .glyphicon { margin-right: 1em; }
        .navbar-text.active { color: #fff; font-weight: bold; }
        [data-toggle=buttons]>.btn input[type=checkbox] { position: relative; clip: none; }
        .guardian-letter .btn.active {
            background-color: #449d44;
            border-color: #398439
        }
        .text-left { text-align: left !important; }
        .text-left input { margin-right: .3em; }
    </style>

</head>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">On Location</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li <?= $route->is_page('search') ? 'class="active"' : '' ?>>
                        <a href="<?= $route->page_url('search') ?>">Suche</a>
                    </li>
                    <li <?= $route->is_page('info') ? 'class="active"' : '' ?>>
                        <a href="<?= $route->page_url('info') ?>">Info</a>
                    </li>
                    <li <?= $route->is_page('print') ? 'class="active"' : '' ?>>
                        <a href="<?= $route->page_url('print') ?>">Drucken</a>
                    </li>
                </ul>
                <p class="navbar-text navbar-right">IP: <?= $_SERVER['REMOTE_ADDR'] ?></p>
                <p class="navbar-text navbar-right">RegID: <?= $route->get_regid() ?></p>
                <!-- <div class="nav navbar-nav navbar-right">
                    <ul class="nav navbar-nav">
                        <li><p class="navbar-text">Drucker:</p></li>
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                    </ul>
                </div> -->
            </div><!--/.nav-collapse -->
        </div>
    </nav>
