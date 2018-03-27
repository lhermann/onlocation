<?php
    global $route;
    $reg = new Reg( $route->regid );
    if(!$reg->id) $route->redirect('not-found');
?>

<?php require('head.php'); ?>


<div class="container">

    <div style="padding-top: 6em;"></div>

    <?php //var_dump($reg); ?>

    <form method="get" action="/">

        <input type="hidden" name="p" value="<?= $route->get_page() ?>">
        <input type="hidden" name="printer" value="<?= $route->printer ?>">
        <input type="hidden" name="regid" value="<?= $route->regid ?>">
        <input type="hidden" name="update_db" value="true">
        <input type="hidden" name="target" value="print">

        <h1><small>[<?= $reg->id ?>]</small> <?= $reg->firstname ?> <?= $reg->lastname ?></h1>
        <p>
            <?= $reg->human_birthdate() ?>
            &middot; <?= $reg->gender == 'M' ? 'Männlich' : 'Weiblich' ?>
            &middot; Übersetzung: <?= $reg->translation ? 'Ja' : 'Nein' ?>
            &middot; <?= $reg->email ?>
        </p>

        <br>

        <div class="row">
            <div class="col-md-6">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Adresse</h3>
                    </div>
                    <div class="panel-body">
                        <?= $reg->addr ?>
                        <br><?= $reg->zip ?> <?= $reg->city ?>
                        <br><?= $reg->state ?> <?= $reg->country ?>
                    </div>
                </div>

                <div class="panel <?= $reg->paid ? 'panel-success' : 'panel-danger' ?>">
                    <div class="panel-heading">
                        <h3 class="panel-title">Bezahlung</h3>
                    </div>
                    <div class="panel-body">
                        <p>Buchungsart: <i><?= $reg->registration ?></i></p>
                        <?php if($reg->paid): ?>
                            <p class="lead text-success">
                                <?= $reg->to_pay ?> EUR <span class="glyphicon glyphicon-ok"></span>&nbsp; Bezahlt
                            </p>
                        <?php else: ?>
                            <p class="lead text-danger">
                                <?= $reg->to_pay ?> EUR <span class="glyphicon glyphicon-remove"></span>&nbsp; Nicht Bezahlt
                            </p>
                        <?php endif; ?>
                        <p>
                            <textarea type="text" class="form-control" placeholder="Ohne Kommentar" cols="70" rows="2" name="comment"><?= $reg->comment ?></textarea>
                        </p>
                    </div>
                </div>

                <div class="panel <?= $reg->u18 && $reg->u18_letter ? 'panel-danger' : 'panel-success' ?>">
                    <div class="panel-heading">
                        <h3 class="panel-title">Aufsichtsperson</h3>
                    </div>
                    <div class="panel-body">
                        <?php if($reg->u18): ?>
                            <p class="lead text-info">
                                <span class="glyphicon glyphicon-warning-sign"></span>&nbsp; Unter 18
                            </p>
                            <p>
                                <span class="glyphicon glyphicon-user"></span>&nbsp; [<?= $reg->guardian_id ?>] <?= $reg->guardian_name ?>
                            </p>
                            <p class="guardian-letter" data-toggle="buttons">
                                <label class="btn btn-danger <?= $reg->u18_letter ? 'active' : '' ?>">
                                    <input type="checkbox" autocomplete="off" <?= $reg->u18_letter ? 'checked' : '' ?> name="u18_letter">&nbsp; Einverständniserklärung erhalten
                                </label>
                            </p>
                        <?php else: ?>
                            <span class="lead text-success">
                                <span class="glyphicon glyphicon-ok"></span>&nbsp; Über 18
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
            <div class="col-md-6">

                <div class="panel <?= $reg->is_helper() ? 'panel-primary' : 'panel-default' ?>">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= $reg->status ?></h3>
                    </div>
                    <div class="panel-body form-inline">
                        <?php if ($reg->status == 'Standleiter'): ?>
                            Bereich: <strong><input type="text" class="form-control" value="<?= $reg->area ?>" name="area_private"></strong>
                        <?php else: ?>
                            Bereich: <strong><?= $reg->area ?: 'keiner' ?></strong>
                        <?php endif ?>
                        <br>T-Shirt: <strong><?= $reg->t_shirt ?: 'nein' ?></strong>
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Schlafraum</h3>
                    </div>
                    <div class="panel-body">
                        <?php if ($reg->has_lodging): ?>
                            <?= $reg->print_room_options(); ?>
                        <?php else: ?>
                            <span class="lead">Kein Unterkunft gebucht</span>
                        <?php endif ?>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Essenszeit</h3>
                    </div>
                    <div class="panel-body">
                         <?php if( $reg->meal ): ?>
                             <?= $reg->print_meal_button( "EarlyEater" ); ?>
                             <?= $reg->print_meal_button( "LaterEater" ); ?>
                             <?= $reg->print_meal_button( "PrivEater" ); ?>
                         <?php else: ?>
                             <span class="lead">Kein Essen gebucht</span>
                         <?php endif; ?>
                    </div>
                </div>

                <?php //var_dump($reg); ?>

            </div>
        </div>

        <div class="text-center" style="padding-top: 2em">
            <a class="btn btn-default btn-lg" href="<?= $route->page_url('search') ?>" role="button">Zurück</a> &mdash;
            <button type="submit" class="btn btn-success btn-lg">Speichern &amp; Weiter</button>
        </div>

    </form>

</div><!-- /.container -->



<?php require('foot.php'); ?>
