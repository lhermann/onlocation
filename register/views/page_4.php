<?php
    global $route;
    $reg = new Reg( $route->regid );
?>

<?php require('head.php'); ?>


<div class="container">

    <div style="padding-top: 6em;"></div>

    <?php //var_dump($reg, $reg->print_room_options()); ?>

    <form class="" method="get" action="/">

        <input type="hidden" name="p" value="<?= $route->get_page() ?>">
        <input type="hidden" name="printer" value="<?= $route->printer ?>">
        <input type="hidden" name="regid" value="<?= $route->regid ?>">
        <input type="hidden" name="update_db" value="true">
        <input type="hidden" name="target" value="<?= $route->get_page() + 1 ?>">


        <div class="row">

            <div class="col-md-6 col-md-offset-3 text-center">
                <h2>Essen</h2>
                <p>[<?= $reg->id ?>] <?= $reg->name() ?></p>
                <?php if( $reg->meal ): ?>
                    <div class="btn-group btn-group-justified" data-toggle="buttons">
                        <?= $reg->print_meal_button( "EarlyEater" ); ?>
                        <?= $reg->print_meal_button( "LaterEater" ); ?>
                        <?= $reg->print_meal_button( "PrivEater" ); ?>
                    </div>
                <?php else: ?>
                    <p class="lead">Kein Essen gebucht</p>
                <?php endif; ?>
            </div>

            <div class="col-md-6 col-md-offset-3 text-center">
                <h2>Schlafraum</h2>
                <select name="room" class="form-control">
                    <?= $reg->print_room_options(); ?>
                </select>
            </div>

        </div>


        <div class="text-center" style="padding-top: 2em">
            <a class="btn btn-default btn-lg" href="<?= $route->page_url( $route->get_page() - 1 ) ?>" role="button">Zurück</a> &mdash;
            <button type="submit" class="btn btn-success btn-lg">Speichern &amp; Weiter</button>
        </div>

    </form>

</div><!-- /.container -->



<?php require('foot.php'); ?>
