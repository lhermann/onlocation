<?php

define('DEBUG', true);

/**
 * MySQL Credentials
 */
define('DB_NAME',       'local');
define('DB_USER',       'root');
define('DB_PASSWORD',   'root');
define('DB_HOST',       'localhost');
define('DB_CHARSET',    'utf8');
define('DB_COLLATE',    '');


/**
 * Settings
 */
define('BROTHER_QL_PRINTER', 'file:///dev/usb/lp0');
define('BROTHER_QL_MODEL', 'QL-570');

define('MODULES', [
    'guardian'      => true,
    'volunteer'     => true,
    'yim'           => true,
    'josua'         => false
]);


/**
 * Custom Fields
 */
define('CUSTOMFIELDS', array(
    1   => '',
    2   => '',
    3   => 'registration',
    4   => 'external_housing',
    5   => 'food_time',
    6   => '',
    7   => 'translation',
    8   => '',
    9   => '',
    10  => 'setup_teardown',
    11  => 'day_ticket',
    12  => '',
    13  => '',
    14  => 'volunteer_area',
    15  => 'tshirt',
    16  => '',
    17  => 'guardian_name',
    18  => 'guardian_id',
    19  => 'room_id',
    20  => '',
    21  => '',
    22  => '',
    23  => '',
    24  => 'area',
    25  => 'label',
    26  => 'food_priv',
    27  => '',
    28  => '',
    29  => '',
    30  => '',
));


/**
 * Areas
 *
 * Format: slug => Title
 */
define('AREA', array(
    'maintenance'       => 'Sauberkeit',
    'wsaudio'           => 'Workshop Audio',
    'surroundings'      => 'Außenbereich',
    'ushers'            => 'Saalordnung',
    'registration'      => 'Registrierung',
    'security'          => 'Security',
    'kitchen'           => 'Küche',
    'where-needed'      => 'Wo Bedarf',
    ''                  => ''
));


// dump sql from yimteam
// psql -d yimteam -c "Copy (Select * From helpers_helper) To STDOUT With CSV HEADER DELIMITER ',';" > yimteam-$(date --iso-8601=minutes).csv

