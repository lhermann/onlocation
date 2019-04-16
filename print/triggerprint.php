<?php
/**
 * This script runs 20 times in 60 seconds
 */

/*
 * Cron Script:
 * * * * * * www-data /usr/bin/php /var/www/onlocation/print/triggerprint.php
 */

/*
 * Test commands:
 * sudo -u www-data /usr/bin/php /var/www/onlocation/print/triggerprint.php
 */

$root = dirname(__DIR__);
require_once($root."/config.php");
require_once($root."/lib/mysql.php");
global $db;

define('BROTHER_QL_PRINTER', 'file:///dev/usb/lp0');
define('BROTHER_QL_MODEL', 'QL-570');

for ($i=0; $i < 20; $i++) {

    // Get next row from db where printed = 0
    $row = $db->get_single_row($db->queue, 'printed', 0);

    // Exit if there is no row
    if( $row ) {

        // set the value op printet = 1
        $db->update_value($db->queue, $row->id, 'printed', 1);

        // run print.sh
        $printer = BROTHER_QL_PRINTER;
        $model = BROTHER_QL_MODEL;
        $execstr = "cd $root/print && BROTHER_QL_PRINTER=$printer BROTHER_QL_MODEL=$model ./print.sh $row->file 2>&1";

        putenv("BROTHER_QL_PRINTER=file:///dev/usb/lp0");
        putenv("BROTHER_QL_MODEL=QL-570");
        file_put_contents ( $root."/triggerprint.log", shell_exec( $execstr ), FILE_APPEND );

    } else {

        sleep(3);

    }

}
