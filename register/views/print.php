<?php
    global $route;
    $reg = new Reg( $route->regid );
    if(!$reg->id) $route->redirect('not-found');
    $reg->generate_print_pattern();
?>

<?php require('head.php'); ?>


<div class="container">

    <div style="padding-top: 6em;"></div>

    <?php //var_dump($reg, $reg->generate_print_pattern()); ?>

    <form class="" method="get" action="/">

        <input type="hidden" name="p" value="<?= $route->get_page() ?>">
        <input type="hidden" name="printer" value="<?= $route->printer ?>">
        <input type="hidden" name="regid" value="<?= $route->regid ?>">
        <input type="hidden" name="update_db" value="true">
        <input type="hidden" name="date_arrived" value="<?= date("Y-m-d H:i:s") ?>">
        <input type="hidden" name="target" value="success">


        <div class="row">

            <div class="col-md-12 text-center">
                <h2>Druckvorschau</h2>
                <style>
                    .label-iframe {
                        width: 59mm;
                        height: 40.5mm;
                        overflow: hidden;
                        border: 2px solid green;
                        border-radius: 5px;
                        transform: scale(2);
                        background-color: white;
                        margin: 24mm 0;
                    }
                </style>
                <iframe
                    class="label-iframe"
                    src="<?= $reg->labelurl . '?' . rand() ?>"></iframe>
            </div>

        </div>


        <div class="text-center" style="padding-top: 2em">
            <a class="btn btn-default btn-lg" href="<?= $route->page_url('info') ?>" role="button">Zurück</a>
            &mdash;
            <button type="submit" class="btn btn-success btn-lg">
                <span class="glyphicon glyphicon-ok"></span> Registrierung abschlie&szlig;en &amp; Drucken
            </button>
        </div>

    </form>

</div><!-- /.container -->



<?php require('foot.php'); ?>
