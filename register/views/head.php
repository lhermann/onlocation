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
    <link href="/register/assets/css/bootstrap.min.css?v=4.1.1" rel="stylesheet">

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

    <?php require('nav.php'); ?>
