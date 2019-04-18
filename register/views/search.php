<?php
  global $route;
  $reglist = new Reglist( $route );
?>

<?php require('head.php'); ?>

<div class="container mt-5">

  <form method="get" action="/">
    <input type="hidden" name="p" value="<?= $route->get_page() ?>">
    <input type="hidden" name="printer" value="<?= $route->printer ?>">

    <div class="row justify-content-center align-items-end">
      <div class="col-auto form-group">
        <label for="regid">Registration ID</label>
        <input type="number" class="form-control" id="regid" name="s_regid" value="<?= isset($route->query['s_regid']) ? $route->query['s_regid'] : '' ?>">
      </div>
      <div class="col-4 form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="s_name" value="<?= isset($route->query['s_name']) ? $route->query['s_name'] : '' ?>">
      </div>
      <div class="col-auto form-group" style="padding-top: 24px;">
        <button type="submit" class="btn btn-success">Suchen</button>
      </div>
    </div>

  </form>
  <div class="mt-5">

    <h2 class="text-center"><?= $reglist->search_to_string(); ?></h2>

    <?php if ( !count($reglist->list) ): ?>
      <p class="text-center">Keine Suchergebnisse</p>
    <?php endif; ?>

    <?php if($reglist->list): ?>

      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Vorname</th>
            <th>Nachname</th>
            <th>Geburtsdatum</th>
            <?php if (MODULES['guardian']): ?>
              <th>U18</th>
              <th>Aufsichtsperson</th>
            <?php endif ?>
            <th>Registriert</th>
          </tr>
        </thead>
        <tbody>

          <?php foreach ($reglist->list as $reg): ?>

            <tr>
              <td><?= $reg->id; ?></td>
              <td><?= $reg->firstname; ?></td>
              <td><?= $reg->lastname; ?></td>
              <td><?= $reg->human_birthdate(); ?></td>
              <?php if (MODULES['guardian']): ?>
                <td>
                  <?= $reg->u18 ? '<i class="fas fa-exclamation-triangle text-danger"></i>' : '-' ?>
                </td>
                <td>
                  <?php if(!$reg->u18): ?>
                    -
                  <?php elseif($reg->has_guardian): ?>
                    <?php if($reg->guardian_arrived()): ?>
                      <i class="far fa-check-circle text-success"></i>
                    <?php else: ?>
                      <i class="far fa-times-circle text-danger"></i>
                    <?php endif; ?>
                    <?= '['.$reg->guardian_id.'] '.$reg->guardian_name ?>
                  <?php else: ?>
                    <a class="btn btn-outline-primary btn-sm" href="<?= $route->page_url( 'guardian', ['regid', 's_name'], [$reg->id, $reg->guardian_name] ); ?>" role="button">Aufsichtsperson</a>
                  <?php endif; ?>
                </td>
              <?php endif ?>
              <td>
                <?php if( MODULES['guardian'] && !$reg->has_guardian ): ?>
                  <i class="far fa-times-circle text-danger"></i>
                <?php elseif( MODULES['guardian'] && $reg->u18 && !$reg->guardian_arrived() ): ?>
                  <i class="far fa-times-circle text-danger"></i> <small>Aufsichtsperson ist noch nicht registriert</small>
                <?php elseif( !$reg->date_arrived ): ?>
                  <a class="btn btn-primary btn-sm" href="<?= $route->page_url( 'info', 'regid', $reg->id ); ?>" role="button">Registrieren</a>
                <?php else: ?>
                  <i class="fas fa-check-circle text-success"></i>
                  <a class="btn btn-outline-success btn-sm" href="<?= $route->page_url( 'info', 'regid', $reg->id ); ?>" role="button">Info</a>
                <?php endif; ?>
              </td>
            </tr>

          <?php endforeach; ?>

        </tbody>
      </table>

    <?php endif; ?>

  </div>

</div><!-- /.container -->


<?php require('foot.php'); ?>
