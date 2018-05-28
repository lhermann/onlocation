<?php

require_once('reg.php');

/**
 * Model: Reglist
 */
class Reglist
{
    public
        $search_key,
        $search_value,
        $list;

    function __construct($route)
    {
        if ( isset($route->query['s']) && $route->query['s'] ) {

            $rows = $this->by_name($route->query['s']);
            if(!$rows) {
                $rows = $this->by_regid($route->query['s']);
            }

        } elseif ( isset($route->query['s_regid']) && $route->query['s_regid'] ) {

            $rows = $this->by_regid($route->query['s_regid']);

        } elseif ( isset($route->query['s_name']) && $route->query['s_name'] ) {

            $rows = $this->by_name($route->query['s_name']);

        } elseif ( isset($route->query['regid']) && $route->query['regid'] ) {

            $rows = $this->by_regid($route->query['regid']);

        } else {

            $rows = array();

        }

        foreach ($rows as $key => $row) {
            if( !$row ) break;
            $this->list[$key] = new Reg( $row->rg_registrationID, $row );
        }
    }

    private function by_regid($value) {
        global $db;
        $this->search_key = 's_regid';
        $this->search_value = $value;
        return [$db->search_regid($value)] ?: [];
    }

    private function by_name($value) {
        global $db;
        $this->search_key = 's_name';
        $this->search_value = $value;
        return $db->search_name($value) ?: [];
    }

    public function search_to_string() {
        switch ($this->search_key) {
            case 's_regid':
                return "Suche nach Registration ID: $this->search_value";
                break;
            case 's_name':
                return "Suche nach Name: $this->search_value";
                break;
            default:
                return "Noch keine Suche gestartet";
                break;
        }
    }

    // public function update( $a_in ) {
    //     global $db;

    //     $a_out = array();

    //     // Page 20
    //     if( isset($a_in['guardian_name']) ) $a_out['rg_customfield17'] = $a_in['guardian_name'];
    //     if( isset($a_in['guardian_id']) ) $a_out['rg_customfield18'] = $a_in['guardian_id'];

    //     return $db->update_row( $db->main, $this->search_value, $a_out );
    // }
}
