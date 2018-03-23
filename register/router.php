<?php

$route = new Route($_GET);
global $route;

/**
* Route class
*/
class Route
{
    private
        $page = 1;
    public
        $printer = 1,
        $target,
        $model,
        $regid,
        $query;

    function __construct($get)
    {
        if ( isset($get['p']) )      $this->page = (int) $get['p'];
        if ( isset($get['regid']) )  $this->regid = (int) $get['regid'];
        if ( isset($get['target']) ) $this->target = (int) $get['target'];

        $this->query = $_GET;

        switch ($this->page) {
            case 1:
            case 90:
                $this->model = 'reglist';
                break;
            default:
                $this->model = 'reg';
                break;
        }
    }

    public function get_page() {
        return $this->page;
    }

    public function this_page_url() {
        $this->page_url($this->page);
    }

    public function page_url($page, $key = null, $value = null) {
        $values = array(
            'p'         => $page,
            'printer'   => $this->printer
        );

        if( $this->regid ) $values['regid'] = $this->regid;

        if( is_array($key) && is_array($key) ) {
            foreach ($key as $i => $thiskey) {
                $values[$thiskey] = $value[$i];
            }
        } elseif( $key !== null && $value !== null ) {
            $values[$key] = $value;
        }
        return '?'.http_build_query($values);
    }
}
