<?php
global $route, $db;
// get all rows from yimteam
$regs = $db->get_rows($db->main, 1, 1);
$helpers = $db->get_rows($db->yimteam, 1, 1);



// iterate rows and find corresponding registration
// foreach ($yimteam as $key => $helper) {
//     $reg = null;
//     if( $helper['reg_id'] )
//         $reg = $db->get_single_row($db->main, 'rg_registrationID', $helper['reg_id']);

//     if( !$reg )
//         $reg = $db->get_single_row($db->main, 'rg_email', $helper['email']);

//     if( !$reg ) continue;

//     // var_dump($reg);
//     if($helper['last_name']) {
//         if( $helper['first_name'] !== $reg->rg_firstname || $helper['last_name'] !== $reg->rg_lastname )
//         var_dump($helper['email']."\t\t".$helper['first_name'].' '.$helper['last_name'].' - '.$reg->rg_firstname.' '.$reg->rg_lastname);

//     }

// }
// var_dump($yimteam);











?>

<?php require('head.php'); ?>

<div class="container">

    <div style="padding-top: 6em;"></div>

    <h1>Dirty Update</h1>

<?php
$counter = 0;
// iterate over registrations
foreach ($regs as $reg) {

    /***********
     * Search
     ***********/

    $finds = null;
    // try to find regid
    foreach ($helpers as $helper) {
        if($helper['reg_id'] == $reg['rg_registrationID']) {
            $finds = null;
            $finds[] = $helper;
            break;
        }
        if($helper['email'] == $reg['rg_email'] && $helper['reg_id'] === '') {
            $finds[] = $helper;
        }
    }

    /***********
     * Filter
     ***********/

    if(!$finds) continue;

    // if(count($finds) === 1
    //    && $finds[0]['first_name'] == $reg['rg_firstname']
    //    && $finds[0]['last_name'] == $reg['rg_lastname'])
    //     continue;

    // only ambigous ones
    // if(count($finds) === 1) continue;

    // only where regID fits but names differ
    // if(!($finds[0]['reg_id'] == $reg['rg_registrationID']
    //    && $finds[0]['last_name'] !== $reg['rg_lastname']))
    //     continue;

    // only where tshirt sizes differ
    // if($finds[0]['t_shirt_size'] == $reg[FIELDS['t-shirt']]) continue;

    /***********
     * Update Operation
     ***********/

    // label
    // area
    // priv food
    // firstname
    // lastname
    // tShirt only if entry is missing, not when both are missing
    $update = [];
    if($finds[0]['label'])          $update[FIELDS['label']] = $finds[0]['label'];
    if($finds[0]['area'])           $update[FIELDS['area-private']] =  $finds[0]['area'];
    if($finds[0]['food_privilege']) $update[FIELDS['food-priv']] = $finds[0]['food_privilege'] === 't' ? 1 : 0;
    if($finds[0]['first_name'])     $update['rg_firstname'] =  $finds[0]['first_name'];
    if($finds[0]['last_name'])      $update['rg_lastname'] =  $finds[0]['last_name'];
    if($finds[0]['t_shirt_size']
       && $finds[0]['t_shirt_size'] !== '-'
       && $reg[FIELDS['t-shirt']] === '-')
                                    $update[FIELDS['t-shirt']] =  $finds[0]['t_shirt_size'];

    $db->update_row($db->main, $reg['rg_registrationID'], $update );


    print('<div>');
    print('<h4>['.$reg['rg_registrationID'].'] '.$reg['rg_firstname'].' '.$reg['rg_lastname'].' - '.$reg['rg_email'].'</h4>');
    var_dump($update);
    print('</div><hr>');
    $counter++;
}

print('<h3>Rows Updated: '.$counter.'</h3>');

?>

    <hr>

    <?php var_dump($route); ?>

</div><!-- /.container -->


<?php require('foot.php'); ?>
