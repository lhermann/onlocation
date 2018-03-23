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
        global $db;
        if ( isset($route->query['s_regid']) && $route->query['s_regid'] ) {
            $this->search_key = 's_regid';
            $this->search_value = $route->query['s_regid'];
            $rows[] = $db->search_regid($route->query['s_regid']);
            if( $this->list[0] == null ) $this->list = [];
        } elseif ( isset($route->query['s_name']) && $route->query['s_name'] ) {
            $this->search_key = 's_name';
            $this->search_value = $route->query['s_name'];
            $rows = $db->search_name($route->query['s_name']);
        } else {
            $rows = array();
        }

        foreach ($rows as $key => $row) {
            if( !$row ) break;
            $this->list[$key] = new Reg( $row->rg_registrationID, $row );
        }
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
