<?php

$route = new Route($_GET);
global $route;

/**
* Route class
*/
class Route
{
    private
        $page = 'search';
    public
        $printer = 1,
        $target,
        $regid,
        $query;

    function __construct($get)
    {
        if ( isset($get['p']) )      $this->page = $get['p'];
        if ( isset($get['regid']) )  $this->regid = (int) $get['regid'];
        if ( isset($get['target']) ) $this->target = $get['target'];

        $this->query = $_GET;
    }

    public function get_page() {
        return $this->page;
    }

    public function get_view() {
        return $this->page;
    }

    public function get_view_file() {
        $file = __DIR__.'/views/'.$this->get_view().'.php';
        if ( file_exists($file) ) {
            return $file;
        } else {
            return "views/404.php";
        }
    }

    public function is_page($page) {
        return $this->page == $page;
    }

    public function this_page_url() {
        $this->page_url($this->page);
    }

    public function get_regid() {
        return $this->regid ?: '-';
    }

    public function page_url($page = null, $key = null, $value = null) {
        $values = array(
            'p'         => $page ?: $this->page,
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

    public function redirect($page = null, $key = null, $value = null) {
        header('Location: /' . $this->page_url($page, $key, $value));
        exit;
    }
}
