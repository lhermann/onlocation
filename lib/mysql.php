<?php
if( !defined('DB_HOST') ) require_once("../config.php");

/**
 *  Establish MySQL connection
 */
// global $mysqli;
// $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
// if ( $mysqli->connect_errno ) {
//     echo "Failed to connect to MySQL: " . $mysqli->connect_error;
// }
// $mysqli->set_charset( DB_CHARSET );

global $db;
$db = new DB( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_CHARSET );


/**
* Class to connect to DB
*/
class DB
{
    private $mysqli;
    public $main, $queue, $rooms;

    function __construct( $host, $user, $pw, $name, $charset )
    {
        $this->mysqli = new mysqli( $host, $user, $pw, $name );
        if ( $this->mysqli->connect_errno ) {
           echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        }
        $this->mysqli->set_charset( $charset );

        $this->main = "main_registrations";
        $this->yimteam = "yimteam";
        $this->queue = "printqueue";
        $this->rooms = "rooms";

        // check if tables 'printqueue' and 'rooms' exist, create them if needed
        // if( !$this->mysqli->query("SELECT 1 FROM $this->rooms LIMIT 1;") ) {
        //     $this->mysqli->query( file_get_contents(__DIR__.'/table.rooms.sql') );
        //     $this->mysqli->query( file_get_contents(__DIR__.'/table.printqueue.sql') );
        // }
    }

    public function insert_row($table, $cols, $values) {
        $cols = implode(",", $cols);
        $values = "'".implode("','", $values)."'";
        $string = "
            INSERT INTO $table ($cols) VALUES($values);
        ";
        return $this->mysqli->query( $string );
    }

    public function update_value($table, $id, $col, $value) {
        $id_key = $table == "main_registrations" ? "rg_registrationID" : "id";
        $string = "
            UPDATE $table
            SET $col = $value, modified = CURRENT_TIMESTAMP
            WHERE $id_key = $id
            LIMIT 1;
        ";
        return $this->mysqli->query( $string );
    }

    /**
     * $array as column/value pairs
     */
    public function update_row($table, $id, $array) {
        $set = "";
        foreach ($array as $column => $value) {
            $set .= ", $column = ".( $value === null ? "NULL" : "'$value'");
        }
        $set = substr($set, 2);
        $id_key = $table == "main_registrations" ? "rg_registrationID" : "id";
        $modified = $table == "printqueue" ? ", modified = CURRENT_TIMESTAMP" : "";
        $string = "
            UPDATE $table
            SET $set $modified
            WHERE $id_key = $id
            LIMIT 1;
        ";
        // var_dump($array, $string); exit;
        return $this->mysqli->query( $string );
    }

    public function get_rows($table, $col, $value) {
        $where = "";
        if( is_array($col) ) {
            foreach ($col as $key => $c) {
                $where .= "AND $c = ".( $value[$key] === null ? "NULL" : "'".$value[$key]."'");
            }
            $where = substr($where, 4);
        } else {
            $where = "$col = '$value'";
        }
        $string = "
            SELECT *
            FROM $table
            WHERE $where;
        ";
        $res = $this->mysqli->query( $string );
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function get_single_row($table, $col, $value, $limit = 1) {
        $string = "
            SELECT *
            FROM $table
            WHERE $col = '$value'
            LIMIT $limit;
        ";
        $res = $this->mysqli->query( $string );
        return $res->fetch_object();
    }

    public function search_regid($reg_id) {
        $string = "
            SELECT *
            FROM $this->main
            WHERE rg_registrationID = $reg_id
            AND rg_date_cancelled IS NULL
            LIMIT 1;
        ";
        $res = $this->mysqli->query( $string );
        return $res->fetch_object();
    }

    public function search_name($name) {
        $name = explode(' ', $name);
        $firstname = reset($name);
        $lastname = next($name);

        if (count($name) <= 1) {
            $string = "
                SELECT *
                FROM $this->main
                WHERE (rg_firstname LIKE '%$firstname%'
                OR rg_lastname LIKE '%$firstname%')
                AND rg_date_cancelled IS NULL;
            ";
        } else {
            $string = "
                SELECT *
                FROM $this->main
                WHERE rg_firstname LIKE '%$firstname%'
                AND rg_lastname LIKE '%$lastname%'
                AND rg_date_cancelled IS NULL;
            ";
        }

        $res = $this->mysqli->query( $string );
        $return = array();
        while( $field = $res->fetch_object() ) {
            $return[] = $field;
        }
        return $return;
    }

    /*
     * Priorities:
     *  1: Familienzimmer
     *  2: Teilnehmer
     *  3: Mitarbeiter
     */
    public function get_rooms_with_count($status = 'Teilnehmer', $gender = null) {

        $priority = 1;
        switch ($status) {
            case 'Teilnehmer':
            case 'Volunteer':
            case 'Standleiter':
                $priority = 2;
                break;
            case 'Mitarbeiter':
            default:
                $priority = 3;
        }

        // gender
        $gender_clause = "gender IS NULL";
        if($gender) $gender_clause .= " OR gender = '$gender'";

        // Building query
        $string = "
            SELECT
                rooms.*,
                (SELECT COUNT(*) FROM main_registrations AS reg WHERE reg.rg_assigned_roomID = rooms.id) AS count
            FROM rooms
            WHERE priority <= $priority
            AND $gender_clause
            ORDER BY priority desc, count asc;
        ";

        $res = $this->mysqli->query( $string );
        $return = array();
        while( $field = $res->fetch_object() ) {
            $return[] = $field;
        }
        return $return;
    }

    public function count($table, $col, $value) {
        $string = "
            SELECT COUNT(*)
            FROM $table
            WHERE $col = '$value';
        ";
        $res = $this->mysqli->query( $string );
        return $res->fetch_assoc()['COUNT(*)'];
    }
}


// function insert_row() {

// }

// function update_row($id, $col, $value) {
//     global $mysqli;
//     $string = "
//         UPDATE printqueue
//         SET $col = $value, modified = CURRENT_TIMESTAMP
//         WHERE id = $id
//         LIMIT 1;
//     ";
//     return $mysqli->query( $string );
// }

// function get_single_row($col, $value) {
//     global $mysqli;
//     $string = "
//         SELECT *
//         FROM printqueue
//         WHERE $col = $value
//         LIMIT 1;
//     ";
//     $res = $mysqli->query( $string );
//     return $res->fetch_object();
// }
