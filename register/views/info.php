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
                <?= $reg->to_pay ?> EUR <i class="fas fa-check-circle text-success"></i>&nbsp; Bezahlt
              </span>
            <?php else: ?>
              <span class="lead text-danger">
                <?= $reg->to_pay ?> EUR <i class="fas fa-times-circle text-danger"></i>&nbsp; Nicht Bezahlt
              </span>
            <?php endif; ?>
          </div>
        </div>

        <?php if (MODULES['josua']): ?>
          <div class="card mb-3 border-primary">
            <h4 class="card-header">Kleingruppen</h3>
            <div class="card-body">
              <select name="gruppe" class="form-control">
                <?php
                  $options = range(1, 18);
                  $options = array_map(function($el){return "Gruppe ".$el;}, $options);
                  $options[] = "Keine";
                  $limits = array_merge(
                      array_fill_keys(range(0, 10), 20),
                      array_fill_keys(range(11, 17), 15),
                      [0]
                  );
                  $fields = $reg->distribute_equally(7, $options, $limits, "Keine");
                  foreach ($fields as $field) {
                    printf('<option value="%s" %s>[%s%s] %s</option>',
                           $field->slug,
                           $field->selected ? "selected" : "",
                           $field->count,
                           $field->limit ? " &middot; limit ".$field->limit : "",
                           $field->slug
                    );
                  }
                ?>
              </select>
            </div>
          </div>
        <?php endif ?>

        <?php if (MODULES['guardian']): ?>
          <div class="card mb-3 <?= $reg->u18 && !$reg->u18_letter ? 'border-danger' : 'border-success' ?>">
            <h4 class="card-header">Aufsichtsperson</h3>
            <div class="card-body">
              <?php if($reg->u18): ?>
                <p class="lead text-info">
                  <i class="far fa-times-circle text-danger"></i>&nbsp; Unter 18
                </p>
                <p>
                  <i class="fas fa-user"></i>&nbsp; [<?= $reg->guardian_id ?>] <?= $reg->guardian_name ?>
                </p>
                <p class="guardian-letter" data-toggle="buttons">
                  <label class="btn btn-danger <?= $reg->u18_letter ? 'active' : '' ?>">
                    <input type="checkbox" autocomplete="off" <?= $reg->u18_letter ? 'checked' : '' ?> name="u18_letter">&nbsp; Einverständniserklärung erhalten
                  </label>
                </p>
              <?php else: ?>
                <span class="lead text-success">
                  <i class="fas fa-check-circle text-success"></i>&nbsp; Über 18
                </span>
              <?php endif; ?>
            </div>
          </div>
        <?php endif ?>

      </div>
      <div class="col-md-6">

        <?php if (MODULES['yim']): ?>
          <div class="card mb-3 border-primary">
            <h4 class="card-header"><?= $reg->label ?></h3>
            <div class="card-body">
                <p>
                  <?php if ($reg->label == 'Standleiter'): ?>
                    Bereich: <strong><input type="text" class="form-control" value="<?= $reg->area ?>" name="area"></strong>
                  <?php else: ?>
                    Bereich: <strong><?= $reg->area ?: 'keiner' ?></strong>
                  <?php endif ?>
                </p>
              <?php if (isset($reg->tshirt)): ?>
                <p>
                  T-Shirt: <strong><?= $reg->tshirt ?: 'nein' ?></strong>
                </p>
              <?php endif ?>
            </div>
          </div>
        <?php endif ?>

        <?php if (MODULES['josua']): ?>
          <div class="card mb-3 border-primary">
            <h4 class="card-header"><?= $reg->status() ?></h3>
            <?php if ($reg->status() === "Teilnehmer"): ?>
              <div class="card-body">
                <select name="label" class="form-control">
                  <?php
                    $options = ["Teilnehmer", "Team", "Standleiter", "Sprecher", "Tagesgast"];
                    $fields = $reg->distribute_fill_first(6, $options, null, "Teilnehmer");
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
              </div>
            <?php endif ?>
          </div>
        <?php endif ?>


        <div class="card mb-3 <?= $reg->has_lodging() ? 'border-primary' : '' ?>">
          <h4 class="card-header">Unterkunft</h3>
          <div class="card-body">
            <?php if ($reg->has_lodging()): ?>
              <?= $reg->print_room_options(); ?>
            <?php else: ?>
              <span class="lead">Kein Unterkunft gebucht</span>
            <?php endif ?>
          </div>
        </div>

        <div class="card mb-3 <?= $reg->has_meal() ? 'border-primary' : '' ?>">
          <h4 class="card-header">Essenszeit</h3>
          <div class="card-body">
            <?php if( $reg->has_meal ): ?>
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


    <div class="card mb-3 <?= $reg->comment ? 'border-primary' : '' ?>">
      <h4 class="card-header">Kommentar</h3>
      <div class="card-body" style="padding: .5em">
         <textarea type="text" class="form-control" placeholder="Ohne Kommentar" cols="70" rows="2" name="comment"><?= $reg->comment ?></textarea>
      </div>
    </div>

    <div class="text-center" style="padding-top: 2em">
      <a class="btn btn-outline-secondary btn-lg" href="<?= $route->page_url('search') ?>" role="button">Zurück</a> &mdash;
      <button type="submit" class="btn btn-success btn-lg">Speichern &amp; Weiter</button>
    </div>

  </form>

</div><!-- /.container -->



<?php require('foot.php'); ?>
