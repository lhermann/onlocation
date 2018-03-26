<?php
    global $route;
    $reglist = new Reglist( $route );
?>

<?php require('head.php'); ?>

<div class="container">

    <div style="padding-top: 6em;"></div>

    <?php //var_dump($route, $reglist); ?>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <form method="get" action="/">

                <input type="hidden" name="p" value="<?= $route->get_page() ?>">
                <input type="hidden" name="printer" value="<?= $route->printer ?>">

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="regid">Registration ID</label>
                        <input type="number" class="form-control" id="regid" name="s_regid" value="<?= isset($route->query['s_regid']) ? $route->query['s_regid'] : '' ?>">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="s_name" value="<?= isset($route->query['s_name']) ? $route->query['s_name'] : '' ?>">
                    </div>
                    <div class="col-md-2 form-group" style="padding-top: 24px;">
                        <button type="submit" class="btn btn-success">Suchen</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-md-12">

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
                            <th>U18</th>
                            <th>Aufsichtsperson</th>
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
                                <td>
                                    <?= $reg->u18 ? '<span class="glyphicon glyphicon-warning-sign text-danger"></span>' : '-' ?>
                                </td>
                                <td>
                                    <?php if(!$reg->u18): ?>
                                        -
                                    <?php elseif($reg->has_guardian): ?>
                                        <?php if($reg->guardian_arrived()): ?>
                                            <span class="glyphicon glyphicon-ok text-success"></span>
                                        <?php else: ?>
                                            <span class="glyphicon glyphicon-remove text-danger"></span>
                                        <?php endif; ?>
                                        <?= '['.$reg->guardian_id.'] '.$reg->guardian_name ?>
                                    <?php else: ?>
                                        <a class="btn btn-success btn-sm" href="<?= $route->page_url( 'guardian', ['regid', 's_name'], [$reg->id, $reg->guardian_name] ); ?>" role="button">Aufsichtsperson</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if( !$reg->has_guardian ): ?>
                                        <span class="glyphicon glyphicon-remove text-danger"></span>
                                    <?php elseif( $reg->u18 && !$reg->guardian_arrived() ): ?>
                                        <span class="glyphicon glyphicon-remove text-danger"></span> <small>Aufsichtsperson ist noch nicht registriert</small>
                                    <?php elseif( !$reg->date_arrived ): ?>
                                        <span class="glyphicon glyphicon-remove text-danger"></span>
                                        <a class="btn btn-default btn-sm" href="<?= $route->page_url( 'info', 'regid', $reg->id ); ?>" role="button">Registrieren</a>
                                    <?php else: ?>
                                        <span class="glyphicon glyphicon-ok text-success"></span>
                                        <a class="btn btn-default btn-sm" href="<?= $route->page_url( 'info', 'regid', $reg->id ); ?>" role="button">Info</a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>

            <?php endif; ?>

        </div>
    </div>


</div><!-- /.container -->


<?php require('foot.php'); ?>
