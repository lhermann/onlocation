<?php
    global $route;
    $reg = new Reg( $route->regid );
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
        <input type="hidden" name="target" value="<?= $route->get_page() + 1 ?>">


        <div class="row">

            <div class="col-md-12 text-center">
                <h2>Druckvorschau</h2>
                <p>[<?= $reg->id ?>] <?= $reg->name() ?></p>
                <iframe style="width: 59mm; overflow: hidden; border: 2px solid green; border-radius: 5px;" src="<?= $reg->labelurl . '?' . rand() ?>"></iframe>
            </div>

        </div>


        <div class="text-center" style="padding-top: 2em">
            <p>
                <button type="submit" class="btn btn-success btn-lg">
                    <span class="glyphicon glyphicon-ok"></span> Registrierung abschlie&szlig;en &amp; Drucken
                </button>
            </p>
            <p>
                <a class="btn btn-default btn-lg" href="<?= $route->page_url( $route->get_page() - 1 ) ?>" role="button">Zurück</a>
            </p>
        </div>

    </form>

</div><!-- /.container -->



<?php require('foot.php'); ?>
