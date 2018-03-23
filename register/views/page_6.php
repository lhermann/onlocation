<?php
    global $route;
    $reg = new Reg( $route->regid );
?>

<?php require('head.php'); ?>


<div class="container">

    <div style="padding-top: 6em;"></div>

    <?php //var_dump($reg); ?>

    <form class="" method="get" action="/">

        <input type="hidden" name="p" value="<?= $route->get_page() ?>">
        <input type="hidden" name="printer" value="<?= $route->printer ?>">
        <input type="hidden" name="regid" value="<?= $route->regid ?>">
        <input type="hidden" name="target" value="1">


        <div class="row">

            <div class="col-md-12 text-center">
                <h2>Anmeldung abgeschlossen</h2>
                <p><span class="glyphicon glyphicon-ok"></span> [<?= $reg->id ?>] <?= $reg->name() ?> wurde angemeldet.</p>
            </div>

        </div>


        <div class="text-center" style="padding-top: 2em">
            <button type="submit" class="btn btn-success btn-lg">Zurück zur Hauptseite</button>
        </div>

    </form>

</div><!-- /.container -->



<?php require('foot.php'); ?>
