<?php
  global $route;
  $reg = new Reg( $route->regid );
  if(!$reg->id) $route->redirect('not-found');
?>

<?php require('head.php'); ?>


<div class="container">

  <div style="padding-top: 4em;"></div>

  <?php //var_dump($reg); ?>

  <form method="get" action="/">

    <input type="hidden" name="p" value="<?= $route->get_page() ?>">
    <input type="hidden" name="printer" value="<?= $route->printer ?>">
    <input type="hidden" name="regid" value="<?= $route->regid ?>">
    <input type="hidden" name="update_db" value="true">
    <input type="hidden" name="target" value="print">

    <h1 class="form-inline">
      <small>[<?= $reg->id ?>]</small>
      <input type="text" class="form-control input-lg mx-2" value="<?= $reg->firstname ?>" name="firstname">
      <input type="text" class="form-control input-lg" value="<?= $reg->lastname ?>" name="lastname">
    </h1>

    <br>

    <div class="row">
      <div class="col-md-6">

        <div class="card mb-3 ">
          <h4 class="card-header">Personendaten</h3>
          <div class="card-body">
            <?= $reg->human_birthdate() ?>
            &middot; <?= $reg->gender == 'M' ? 'Männlich' : 'Weiblich' ?>
            &middot; <?= $reg->email ?>
            <br><?= $reg->addr ?>, <?= $reg->zip ?> <?= $reg->city ?>, <?= $reg->state ?>, <?= $reg->country ?>
            <?php if (MODULES['yim']): ?>
              <br>Übersetzung: <?= $reg->translation ? 'Ja' : 'Nein' ?>
            <?php endif ?>
          </div>
        </div>

        <div class="card mb-3 <?= $reg->paid ? 'border-success' : 'border-danger' ?>">
          <h4 class="card-header">Bezahlung</h3>
          <div class="card-body">
            <p>Buchungsart: <i><?= $reg->registration ?></i></p>
            <?php if($reg->paid): ?>
              <span class="lead text-success">
                <?= $reg->to_pay ?> EUR <span class="glyphicon glyphicon-ok"></span>&nbsp; Bezahlt
              </span>
            <?php else: ?>
              <span class="lead text-danger">
                <?= $reg->to_pay ?> EUR <span class="glyphicon glyphicon-remove"></span>&nbsp; Nicht Bezahlt
              </span>
            <?php endif; ?>
          </div>
        </div>

        <?php if (MODULES['josua']): ?>
          <div class="card mb-3 border-primary">
            <h4 class="card-header">Gruppe</h3>
            <div class="card-body">
            </div>
          </div>
        <?php endif ?>

        <?php if (MODULES['guardian']): ?>
          <div class="card mb-3 <?= $reg->u18 && !$reg->u18_letter ? 'border-danger' : 'border-success' ?>">
            <h4 class="card-header">Aufsichtsperson</h3>
            <div class="card-body">
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
        <?php endif ?>

      </div>
      <div class="col-md-6">

        <div class="card mb-3 border-primary">
          <h4 class="card-header"><?= $reg->status ?></h3>
          <div class="card-body form-inline">
            <?php if (MODULES['yim']): ?>
              <?php if ($reg->status == 'Standleiter'): ?>
                Bereich: <strong><input type="text" class="form-control" value="<?= $reg->area ?>" name="area_private"></strong>
              <?php else: ?>
                Bereich: <strong><?= $reg->area ?: 'keiner' ?></strong>
              <?php endif ?>
            <?php else: ?>
              TEILNEHMERART!!!!
            <?php endif ?>
            <?php if (isset($reg->t_shirt)): ?>
              <br>T-Shirt: <strong><?= $reg->t_shirt ?: 'nein' ?></strong>
            <?php endif ?>
          </div>
        </div>

        <div class="card mb-3 <?= $reg->has_lodging() ? 'border-primary' : '' ?>">
          <h4 class="card-header">Unterkunft</h3>
          <div class="card-body">
            <?php if ($reg->has_lodging()): ?>
              <select name="lodging" class="form-control">
                <?php
                  $options = ["internal", "camping", "external"];
                  $fields = $reg->distribute_equally(2, $options);
                  foreach ($fields as $field) {
                    printf('<option value="%s" %s>[%s] %s</option>',
                           $field->slug,
                           $field->selected ? "selected" : "",
                           $field->count,
                           $field->slug
                    );
                  }
                ?>
              </select>
            <?php else: ?>
              <span class="lead">Kein Unterkunft gebucht</span>
            <?php endif ?>
          </div>
        </div>

        <div class="card mb-3 <?= $reg->has_meal() ? 'border-primary' : '' ?>">
          <h4 class="card-header">Essen</h3>
          <div class="card-body">
             <?php if( $reg->has_meal() ): ?>
                <h5>Essen</h5>
                <div class="form-check form-check-inline">
                  <?= $reg->print_button_with_count('attendee-2meals', 1) ?>
                  <?= $reg->print_button_with_count('attendee-3meals', 1) ?>
                </div>
                <h5 class="mt-3">Essenszeit</h5>
                <select name="essenszeit" class="form-control">
                  <?php
                    $options = ["Frühesser", "Spätesser"];
                    $fields = $reg->distribute_equally(9, $options);
                    foreach ($fields as $field) {
                      printf('<option value="%s" %s>[%s] %s</option>',
                             $field->slug,
                             $field->selected ? "selected" : "",
                             $field->count,
                             $field->slug
                      );
                    }
                  ?>
                </select>
             <?php else: ?>
               <span class="lead">Kein Essen gebucht</span>
             <?php endif; ?>
          </div>
        </div>

        <?php //var_dump($reg); ?>

      </div>
    </div>


    <div class="card mb-3 <?= $reg->comment ? 'border-primary' : '' ?>">
      <h4 class="card-header">Kommentar</h3>
      <div class="card-body" style="padding: .5em">
         <textarea type="text" class="form-control" placeholder="Ohne Kommentar" cols="70" rows="2" name="comment"><?= $reg->comment ?></textarea>
      </div>
    </div>

    <div class="text-center" style="padding-top: 2em">
      <a class="btn btn-default btn-lg" href="<?= $route->page_url('search') ?>" role="button">Zurück</a> &mdash;
      <button type="submit" class="btn btn-success btn-lg">Speichern &amp; Weiter</button>
    </div>

  </form>

</div><!-- /.container -->



<?php require('foot.php'); ?>
