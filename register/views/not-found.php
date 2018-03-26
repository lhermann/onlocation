<?php
    global $route;
?>

<?php require('head.php'); ?>

<div class="container">

    <div style="padding-top: 6em;"></div>

    <h1>Keine Anmeldung gefunden</h1>

    <a class="btn btn-success btn-lg" href="<?= $route->page_url('search', 'regid', '') ?>">Zurück zur Hauptseite</a>

</div><!-- /.container -->


<?php require('foot.php'); ?>
