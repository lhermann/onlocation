<?php
    global $route;
    $reg = new Reg( $route->regid );
?>

<?php require('head.php'); ?>


<div class="container">

    <div style="padding-top: 6em;"></div>

    <?php var_dump($reg); ?>

    <form class="form-inline" method="get" action="/">

        <input type="hidden" name="p" value="<?= $route->get_page() ?>">
        <input type="hidden" name="printer" value="<?= $route->printer ?>">
        <input type="hidden" name="regid" value="<?= $route->regid ?>">
        <input type="hidden" name="update_db" value="true">
        <input type="hidden" name="target" value="<?= $route->get_page() + 1 ?>">

        <div class="row">
            <div class="col-md-6">
                <h3>Name</h3>
                <p>
                    <input type="text" class="form-control" value="<?= $reg->id ?>" size="4" name="id" disabled>
                    <input type="text" class="form-control" value="<?= $reg->firstname ?>" name="firstname">
                    <input type="text" class="form-control" value="<?= $reg->lastname ?>" name="lastname">
                </p>

                <div style="margin-bottom: 10px">
                    <span class="text-left">Geschlecht:</span>
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-default <?= $reg->gender == 'M' ? 'active' : '' ?>">
                        <input type="radio" name="gender" value="M" <?= $reg->gender == 'M' ? 'checked' : '' ?>> männlich
                      </label>
                      <label class="btn btn-default <?= $reg->gender == 'F' ? 'active' : '' ?>">
                        <input type="radio" name="gender" value="F" <?= $reg->gender == 'F' ? 'checked' : '' ?>> weiblich
                      </label>
                    </div>
                </div>

                <div>
                    <span class="text-left">Übersetzung:</span>
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-default <?= $reg->translation ? 'active' : '' ?>">
                        <input type="radio" name="translation" value="yes" <?= $reg->translation ? 'checked' : '' ?>> Ja
                      </label>
                      <label class="btn btn-default <?= !$reg->translation ? 'active' : '' ?>">
                        <input type="radio" name="translation" value="no" <?= !$reg->translation ? 'checked' : '' ?>> Nein
                      </label>
                    </div>
                </div>

                <h3>Adresse</h3>
                <p>
                    <input type="text" class="form-control" value="<?= $reg->addr ?>" size="65" name="addr">
                </p>
                <p>
                    <input type="text" class="form-control" value="<?= $reg->zip ?>" size="4" name="zip">
                    <input type="text" class="form-control" value="<?= $reg->city ?>" size="50" name="city">
                </p>
                <p>
                    <input type="text" class="form-control" value="<?= $reg->country ?>" size="27" name="country" placeholder="Land">
                    <input type="text" class="form-control" value="<?= $reg->state ?>" size="27" name="state" placeholder="Bundesland">
                </p>
            </div>
            <div class="col-md-6">

                <h3>Bezahlung</h3>
                <p>Buchungsart: <i><?= $reg->regtype ?></i></p>
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

                <h3>Aufsichtsperson</h3>
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
                    <p class="lead text-success">
                        <span class="glyphicon glyphicon-ok"></span>&nbsp; Über 18
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center" style="padding-top: 2em">
            <a class="btn btn-default btn-lg" href="<?= $route->page_url( $route->get_page() - 1 ) ?>" role="button">Zurück</a> &mdash;
            <button type="submit" class="btn btn-success btn-lg">Speichern &amp; Weiter</button>
        </div>

    </form>

</div><!-- /.container -->



<?php require('foot.php'); ?>
