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
define('MODULES', [
    'guardian'      => false,
    'volunteer'     => false,
    'yim'           => false,
    'josua'         => true
]);
define('PRINTER1',  'Brother_QL-570');

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
    1   => 'registration',
    2   => 'lodging',
    3   => 'allergies',
    4   => 'terms',
    5   => 'tagesgast',
    6   => 'label',
    7   => 'gruppe',
    8   => '',
    9   => 'essenszeit',
    10  => 'kuechendienst',
    11  => '',
    12  => '',
    13  => '',
    14  => '',
    15  => '',
    16  => '',
    17  => '',
    18  => '',
    19  => '',
    20  => '',
    21  => '',
    22  => '',
    23  => '',
    24  => '',
    25  => '',
    26  => '',
    27  => '',
    28  => '',
    29  => '',
    30  => '',
));

/**
 * Positions
 */
define('VOLUNTEER', array(
    'maintenance'       => 'Sauberkeit',
    'wsaudio'           => 'Workshop Audio',
    'surroundings'      => 'Außenbereich',
    'ushers'            => 'Saalordnung',
    'registration'      => 'Registrierung',
    'security'          => 'Security',
    'kitchen'           => 'Küche'
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
    ''                  => ''
));

// dump sql from yimteam
// psql -d yimteam -c "Copy (Select * From helpers_helper) To STDOUT With CSV HEADER DELIMITER ',';" > yimteam.csv

