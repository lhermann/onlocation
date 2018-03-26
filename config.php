<?php

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
define('PRINTER1',  'Brother_QL-570');
define('PRINTER2',  'Brother_QL-570');
define('PRINTER3',  'Brother_QL-570');
define('PRINTER4',  'Brother_QL-570');


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
 * Custom Fields
 */
define('FIELDS', array(
    ''                  => 'rg_customfield1',
    ''                  => 'rg_customfield2',
    'registration'      => 'rg_customfield3',
    'external-housing'  => 'rg_customfield4',
    'food-time'         => 'rg_customfield5',
    ''                  => 'rg_customfield6',
    'translation'       => 'rg_customfield7',
    ''                  => 'rg_customfield8',
    ''                  => 'rg_customfield8',
    ''                  => 'rg_customfield9',
    'setup-teardown'    => 'rg_customfield10',
    ''                  => 'rg_customfield11',
    ''                  => 'rg_customfield12',
    'volunteer'         => 'rg_customfield13',
    'area-public'       => 'rg_customfield14',
    't-shirt'           => 'rg_customfield15',
    ''                  => 'rg_customfield16',
    'guardian_name'     => 'rg_customfield17',
    'guardian_id'       => 'rg_customfield18',
    'room_name'         => 'rg_customfield19',
    ''                  => 'rg_customfield20',
    ''                  => 'rg_customfield21',
    ''                  => 'rg_customfield22',
    ''                  => 'rg_customfield23',
    'area-private'      => 'rg_customfield24',
    'label'             => 'rg_customfield25',
    'food-priv'         => 'rg_customfield26',
    ''                  => 'rg_customfield27',
    ''                  => 'rg_customfield28',
    ''                  => 'rg_customfield29',
    ''                  => 'rg_customfield30',
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

