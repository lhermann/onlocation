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

for ($i=0; $i < 60; $i++) {

    // Get next row from db where printed = 0
    $row = $db->get_single_row($db->queue, 'printed', 0);

    // Exit if there is no row
    if( $row ) {

        // set the value op printet = 1
        $db->update_value($db->queue, $row->id, 'printed', 1);

        // run print.sh
        $execstr = sprintf(
            "cd $root/print && %s %s ./print.sh %s >> %s 2>&1",
            "BROTHER_QL_PRINTER=".BROTHER_QL_PRINTER, // env var
            "BROTHER_QL_MODEL=".BROTHER_QL_MODEL, // env var
            $row->file, // file to print
            "$root/triggerprint.log" // logfile
        );
        file_put_contents ( $root."/triggerprint.log", shell_exec( $execstr ), FILE_APPEND );

    } else {

        sleep(1);

    }

}
