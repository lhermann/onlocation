<?php
    global $route;
    $reg = new Reg( $route->regid );
?>

<?php require('head.php'); ?>


<div class="container">

    <div style="padding-top: 4em;"></div>

    <?php //var_dump($reg); ?>

    <form class="form-inline" method="get" action="/">

        <input type="hidden" name="p" value="<?= $route->get_page() ?>">
        <input type="hidden" name="printer" value="<?= $route->printer ?>">
        <input type="hidden" name="regid" value="<?= $route->regid ?>">
        <input type="hidden" name="update_db" value="true">
        <input type="hidden" name="target" value="<?= $route->get_page() + 1 ?>">

        <div class="row" data-toggle="buttons">

            <div class="col-md-12 text-center">
                <h1>Position</h1>
                <p>[<?= $reg->id ?>] <?= $reg->name() ?></p>
            </div>

            <div class="col-md-6">
                <h2>Volunteer</h2>
                <div class="btn-group-vertical btn-block">
                    <?= $reg->print_position_button('Maintenance') ?>
                    <?= $reg->print_position_button('Logistics') ?>
                    <?= $reg->print_position_button('Surroundings') ?>
                    <?= $reg->print_position_button('Ushers') ?>
                    <?= $reg->print_position_button('SongService') ?>
                    <?= $reg->print_position_button('VideoProject') ?>
                    <?= $reg->print_position_button('Workshops') ?>
                    <?= $reg->print_position_button('Outreach') ?>
                    <?= $reg->print_position_button('KitchenAndServing') ?>
                    <?= $reg->print_position_button('Registration') ?>
                    <?= $reg->print_position_button('Security') ?>
                    <?= $reg->print_position_button('Technical') ?>
                    <?= $reg->print_position_button('SpecialVolunteer') ?>
                </div>
            </div>
            <div class="col-md-6">
                <h2>Keine</h2>
                <div class="btn-group-vertical btn-block">
                    <?= $reg->print_position_button('') ?>
                </div>

                <h2>Special</h2>
                <div class="btn-group-vertical btn-block">
                    <?= $reg->print_position_button('Mitarbeiter') ?>
                    <?= $reg->print_position_button('Arbeitskreis') ?>
                    <?= $reg->print_position_button('Standbetreuer') ?>
                    <?= $reg->print_position_button('Sprecher') ?>
                </div>

                <h2>Andere</h2>
                <div class="input-group">
                    <?= $reg->print_position_button('other') ?>
                </div><!-- /input-group -->
            </div>

        </div>

        <div class="text-center" style="padding-top: 2em">
            <a class="btn btn-default btn-lg" href="<?= $route->page_url( $route->get_page() - 1 ) ?>" role="button">Zurück</a> &mdash;
            <button type="submit" class="btn btn-success btn-lg">Speichern &amp; Weiter</button>
        </div>

    </form>

</div><!-- /.container -->



<?php require('foot.php'); ?>
