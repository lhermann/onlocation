<?php
    global $route;
    $reg = new Reg( $route->regid );
    if(!$reg->id) $route->redirect('not-found');
    $reg->add_label_to_print_queue();
?>

<?php require('head.php'); ?>


<div class="container">

    <div style="padding-top: 6em;"></div>

    <?php //var_dump($reg); ?>

    <div class="row">

        <div class="col-md-12 text-center">

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h2 class="panel-title">Anmeldung abgeschlossen</h2>
                </div>
                <div class="panel-body">
                    <span class="lead">
                        <span class="glyphicon glyphicon-ok" style="color: green;"></span>
                        [<?= $reg->id ?>] <?= $reg->name() ?> wurde angemeldet.
                    </span>
                </div>
            </div>
        </div>

    </div>


    <div class="text-center" style="padding-top: 2em">
        <a class="btn btn-default btn-lg" href="<?= $route->page_url('print') ?>" role="button">Zurück</a>
        &mdash;
        <a class="btn btn-success btn-lg" href="<?= $route->page_url('search', 'regid', '') ?>">Zurück zur Hauptseite</a>
    </div>

</div><!-- /.container -->



<?php require('foot.php'); ?>
