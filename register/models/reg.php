<?php

/**
 * A single registration
 */
class Reg
{

    public $id, $firstname, $lastname, $gender, $addr, $zip, $city, $state, $country, $paid;

    function __construct( $regid = 0, $row = false )
    {
        if( !$row ) {
            global $db;
            $row = $db->get_single_row($db->main, 'rg_registrationID', $regid);
        }
        if(!$row) return;

        // Default values
        $this->id           = $row->rg_registrationID;
        $this->date_arrived = $row->rg_date_arrived;
        $this->firstname    = $row->rg_firstname;
        $this->lastname     = $row->rg_lastname;
        $this->email        = $row->rg_email;
        $this->gender       = $row->rg_gender;
        $this->birthdate    = strtotime(
            $row->rg_birthday.'-'.$row->rg_birthmonth.'-'.$row->rg_birthyear
        );
        $this->year         = $row->rg_birthyear;
        $this->addr         = $row->rg_addr;
        $this->zip          = $row->rg_zip;
        $this->city         = $row->rg_city;
        $this->state        = $row->rg_state;
        $this->country      = $row->rg_country;
        $this->to_pay       = $row->rg_amount_to_pay;
        $this->paid         =
            $row->rg_amount_to_pay === '0.00'
            || $row->rg_amount_received !== null;
        $this->comment      = $row->rg_payment_comments;


        // custom fields
        for ($i = 1; $i <= 30; $i++) {
            $this->{'customfield' . $i} = $row->{'rg_customfield' . $i};
            if(CUSTOMFIELDS[$i]) {
                $this->{CUSTOMFIELDS[$i]} = $row->{'rg_customfield' . $i};
            }
        }


        // guardian
        if( MODULES['guardian'] ) {
            // check that these fileds exist
            if(!property_exists($this, 'guardian_id'))
                throw new Exception("customfield for 'guardian_id' missing");
            if(!property_exists($this, 'guardian_name'))
                throw new Exception("customfield for 'guardian_name' missing");

            $this->u18           = $this->is_under18();
            $this->has_guardian  = $this->has_guardian_set();
            $this->u18_letter    = $row->rg_parental_letter_received !== null;
        }


        if (MODULES['yim']) {
            // check that these fileds exist
            if(!property_exists($this, 'translation'))
                throw new Exception("customfield for 'translation' missing");
            if(!property_exists($this, 'tshirt'))
                throw new Exception("customfield for 'tshirt' missing");
            if(!property_exists($this, 'volunteer_area'))
                throw new Exception("customfield for 'volunteer_area' missing");
            if(!property_exists($this, 'area'))
                throw new Exception("customfield for 'area' missing");
            if(!property_exists($this, 'label'))
                throw new Exception("customfield for 'label' missing");
            if(!property_exists($this, 'food_priv'))
                throw new Exception("customfield for 'food_priv' missing");
            if(!property_exists($this, 'food_time'))
                throw new Exception("customfield for 'food_time' missing");
            if(!property_exists($this, 'room_id'))
                throw new Exception("customfield for 'room_id' missing");

            /*
             * volunteer/helper info
             */
            //label
            if(!$this->label) {
                if($this->volunteer_area) {
                    $this->label = 'Volunteer';
                } elseif($this->registration === 'day-ticket') {
                    $this->label = 'Tagesgast';
                } elseif($this->label == 'Freiperson') {
                    $this->label = 'Teilnehmer';
                } else {
                    $this->label = 'Teilnehmer';
                }
            }

            // area
            if(!$this->area) {
                if($this->volunteer_area) {
                    if(isset(AREA[$this->volunteer_area])){
                        $this->area = AREA[$this->volunteer_area];
                    } else {
                        $this->area = ucfirst($this->volunteer_area);
                    }
                } elseif(in_array($this->label, ['Teilnehmer', 'TTBW', 'Medical Team'])) {
                    $this->area = '';
                } elseif($this->label === 'Tagesgast') {
                    $days = explode(',', $this->day_ticket);
                    $full_days = [];
                    foreach ($days as $day) {
                        if(strpos($day, 'thu') === 0) $full_days[] = "Do";
                        if(strpos($day, 'fri') === 0) $full_days[] = "Fr";
                        if(strpos($day, 'sat') === 0) $full_days[] = "Sa";
                        if(strpos($day, 'sun') === 0) $full_days[] = "So";
                    }
                    $this->area = implode(', ', $full_days);
                }
            }

            // $this->area =
            // $this->area = $this->volunteer_area ? AREA[$this->volunteer_area] : $this->area;
            // // status
            // if (strpos($this->registration, 'day')) $this->status = 'Tagesgast';
            // // remove area of some statii
            // if(in_array($this->label, ['Teilnehmer', 'TTBW', 'Medical Team']))
            //     $this->area = '';

            /*
             * lodging
             */
            $this->has_lodging = in_array($this->registration, ['attendee', 'reduced', 'child']);
            $this->external_lodging = $this->external_housing == 'ExternalHousing';

            // if(!$this->room_id && $this->has_lodging)
            //     $this->room_id = 0;

            if($this->room_id && isset($db))
                $this->room = $db->get_single_row($db->rooms, 'id', $this->room_id);

            // if( $this->room_id === null && ( $this->external_lodging || !$this->has_lodging ) ) {
            //     $this->room_id = 0;
            // }

            /*
             * meals
             */
            // if they booked food then:
            $this->has_meal = in_array($this->registration, ['attendee', 'reduced', 'child']);
            $this->has_food_priv = $this->is_helper() ?: (bool) $this->food_priv;
            $this->meal = $this->food_time;
            if( $this->has_meal && $this->has_food_priv ) {
                $this->meal = 'PrivEater';
            }
        }

    }

    private function is_under18() {
        return strtotime('-18 year') < $this->birthdate ? true : false;
    }

    private function has_guardian_set() {
        return $this->guardian_id && $this->guardian_name || !$this->u18 ? true : false;
    }

    public function status() {
        return $this->registration === "day-ticket" ? "Tagesgast" : "Teilnehmer";
    }

    public function has_lodging() {
        return $this->has_lodging && !$this->external_housing;
    }

    public function has_meal() {
        return in_array($this->registration, ['attendee', 'reduced', 'child']);
    }

    public function is_attendee() {
        return $this->status === 'Teilnehmer';
    }

    public function is_helper() {
        return !in_array($this->label, ['Teilnehmer', 'Standleiter']);
    }

    public function update( $a_in ) {
        global $db;

        $a_out = array();
        //var_dump($a_in);die();

        unset($a_in['p']);
        unset($a_in['printer']);
        unset($a_in['regid']);
        unset($a_in['s_regid']);
        unset($a_in['update_db']);
        unset($a_in['target']);

        foreach ($a_in as $key => $value) {
            if($key === 'comment') {
                $a_out['rg_payment_comments'] = $value;
            } elseif($i = array_search($key, CUSTOMFIELDS)) {
                $a_out['rg_customfield'.$i] = $value;
            } else {
                $a_out['rg_'.$key] = $value;
            }
        }

        // Page 2
        if( isset($a_in['firstname']) )     $a_out['rg_firstname'] = $a_in['firstname'];
        if( isset($a_in['lastname']) )      $a_out['rg_lastname'] = $a_in['lastname'];
        if( isset($a_in['gender']) )        $a_out['rg_gender'] = $a_in['gender'];
        if( isset($a_in['translation']) )   $a_out['rg_customfield7'] = ( $a_in['translation'] == 'yes' ? 'NeedEngTranslation' : NULL );
        if( isset($a_in['addr']) )          $a_out['rg_addr'] = $a_in['addr'];
        if( isset($a_in['zip']) )           $a_out['rg_zip'] = $a_in['zip'];
        if( isset($a_in['city']) )          $a_out['rg_city'] = $a_in['city'];
        if( isset($a_in['state']) )         $a_out['rg_state'] = $a_in['state'];
        if( isset($a_in['country']) )       $a_out['rg_country'] = $a_in['country'];
        if( isset($a_in['comment']) )       $a_out['rg_payment_comments'] = $a_in['comment'];
        if( $this->u18 && isset($a_in['u18_letter']) )
            $a_out['rg_parental_letter_received'] = date('Y-m-d');


        // arrival
        if( isset($a_in['date_arrived']) ) $a_out['rg_date_arrived'] = $a_in['date_arrived'];


        // var_dump($a_out, $this->id); die();

        return $db->update_row( $db->main, $this->id, $a_out );
    }

    public function print_meal_button( $meal ) {
        // Labels
        switch ($meal) {
            case 'PrivEater': $label = "*** Privilegiert ***"; break;
            case 'EarlyEater': $label = "Frühesser [" . $this->meal_count('EarlyEater') . "]"; break;
            default:
            case 'LaterEater': $label = "Spätesser [" . $this->meal_count('LaterEater') . "]"; break;
        }
        // Disabled?
        $disabled = false;
        if( $meal == 'PrivEater' && !$this->has_food_priv ) {
            $disabled = true;
        } elseif( $meal !== 'PrivEater' && $this->has_food_priv ) {
            $disabled = true;
        } elseif( $meal == 'LaterEater' && !$this->meal ) {

        }

        // Checked?
        $checked = false;
        if( $this->meal == $meal || ($meal == 'LaterEater' && !$this->meal) ) {
            $checked = true;
        }

        return sprintf('<div class="radio %s"><label>
                            <input type="radio" name="food_time" value="%s" %s %s> %s
                        </label></div>',
                $disabled ? 'disabled text-muted' : '',
                $meal,
                $checked ? 'checked' : '',
                $disabled ? 'disabled="disabled"' : '',
                $label
        );
    }

    public function print_room_options() {
        global $db;

        $rooms = $db->get_rooms_with_count(
            $this->label,
            $this->gender
        );

        // print dropdown
        print('<select name="room_id" class="form-control">');
        foreach ($rooms as $room) {
            printf('<option value="%d" %s>[%s] %s %s %s</option>',
                   $room->id,
                   $room->id == $this->room_id ? "selected" : "",
                   $room->count,
                   $room->name,
                   $room->status ? '- '.$room->status : '',
                   $room->early_or_late == 'early' ? "Frühschläfer" : ''
            );
        }
        printf('<option value="0" %s>Schläft extern</option>',
               $this->room_id === 0 ? "selected" : "",
               'Schläft extern'
        );
        print('</select>');
    }

    public function human_birthdate() {
        return date('d.m.Y', $this->birthdate);
    }

    public function name() {
        $name = $this->firstname.' '.$this->lastname;
        if( $this->country != 'DE' ) {
            $name .= ' ['.$this->country.']';
        }
        return $name;
    }

    public function guardian_arrived() {
        return (bool) (new Reg( $this->guardian_id ))->date_arrived;
    }

    public function meal_count($meal) {
        global $db;
        return $db->count($db->main, 'rg_customfield5', $meal);
    }

    public function generate_print_pattern() {
        global $db;
        $labeldir = dirname(dirname(__DIR__)) . '/labels/';

        // Get file
        $file = file_get_contents( $labeldir . 'pattern.html' );

        // Replace content
        $patterns = array();
        $replacements = array();

        $patterns[0] = '/%%FIRSTNAME%%/';
        $replacements[0] = $this->firstname;

        $patterns[11] = '/%%LASTNAME%%/';
        $replacements[11] = $this->lastname;

        $patterns[1] = '/%%LABEL%%/';
        $replacements[1] = $this->label ?: $this->status();

        $patterns[12] = '/%%AREA%%/';
        $replacements[12] = $this->area;


        $room = $db->get_single_row($db->rooms, 'id', $this->room_id);
        $patterns[2] = '/%%ROOM%%/';
        //var_dump($this, $room);die();
        $replacements[2] = $this->room_id ? $room->name : 'Extern';

        // $patterns[2] = '/%%ROOM%%/';
        // switch ($this->lodging) {
        //     case 'external':
        //     case null:
        //         $replacements[2] = 'Extern/Keine'; break;
        //     case 'camping':
        //         $replacements[2] = 'Campingplatz'; break;
        //     default:
        //         $replacements[2] = 'Intern'; break;
        // }

        $patterns[3] = '/%%FOOD-CLASS%%/';
        $patterns[4] = '/%%FOOD%%/';
        switch ($this->meal) {
            case 'PrivEater':
                $replacements[3] = 'food--priv';
                $replacements[4] = 'Essen';
                break;
            case 'EarlyEater':
                $replacements[3] = 'food--early';
                $replacements[4] = 'Frühesser';
                break;
            case 'LaterEater':
                $replacements[3] = 'food--late';
                $replacements[4] = 'Spätesser';
                break;
            default:
                $replacements[3] = 'food--none';
                $replacements[4] = 'Kein Essen';
                break;
        }
        if(!$this->has_meal) {
            $replacements[3] = 'food--none';
            $replacements[4] = 'Kein Essen';
        }

        // $patterns[3] = '/%%FOOD-CLASS%%/';
        // $patterns[4] = '/%%FOOD%%/';
        // $replacements[4] = $this->food_time ?: 'Kein Essen';

        // switch ($this->registration) {
        //     case 'attendee-2meals':
        //         $replacements[3] = 'lunch dinner'; break;
        //     case 'attendee-3meals':
        //         $replacements[3] = 'breakfast lunch dinner'; break;
        //     default:
        //         $replacements[3] = ''; break;
        // }
        // if(!$this->has_meal) {
        //     $replacements[3] = 'food--none';
        //     $replacements[4] = 'Kein Essen';
        // }

        $patterns[5] = '/%%COMMENT%%/';
        $replacements[5] = "";
        if (MODULES['yim']) {
            if( $this->translation ) {
                $replacements[5] .= "Englische Übersetung";
            }
            if( $this->u18 ) {
                $guardian = new Reg($this->guardian_id);
                $guardian_room = $db->get_single_row($db->rooms, 'id', $guardian->room_id);
                $replacements[5] .= sprintf( "U18 / Aufsichtsperson:<br>[%s] %s (%s)",
                    $guardian->id,
                    $guardian->name(),
                    $guardian->room_id ? $guardian_room->name : 'Extern'
                );
            }
        }
        if (MODULES['josua']) {
            $replacements[5] = "Kleingruppe: " . $this->gruppe;
        }

        ksort($patterns); ksort($replacements);
        $file = preg_replace($patterns, $replacements, $file);

        // write file
        $this->labelfile = $labeldir . 'src/' . $this->id . '.html';
        $this->labelurl = "/labels/src/" . $this->id . ".html";
        file_put_contents( $this->labelfile, $file );
        return null;
    }

    public function add_label_to_print_queue($printer = 1) {
        global $db;
        // var_dump($_SERVER); die;
        $cols = ['ip', 'printerid', 'file'];
        $values = [
            $_SERVER['REMOTE_ADDR'],
            $printer,
            $this->id
        ];
        $db->insert_row($db->queue, $cols, $values);
    }

    /**
     * $filed_id  => rg_customfield ID
     * $options   => array of slugs
     * $limits    => array of limits, key matching $options
     * $excludes  => array of slugs to exclude, keys don't matter
     */
    public function distribute_equally($field_id, $options = [], $limits = [], $excludes = []) {
        global $db;

        // prepare vars
        if(is_null($options)) $options = [];
        if(is_null($limits)) $limits = [];
        if(is_null($excludes)) $excludes = [];
        if(!is_array($options)) $options = [$options];
        if(!is_array($limits)) $limits = [$limits];
        if(!is_array($excludes)) $excludes = [$excludes];

        // get cols
        $col = $db->get_col_with_count("rg_customfield".$field_id);

        // make sure that $options are among the result set
        foreach ($col as $option) {
            $option->selected = false;
            $key = array_search($option->slug, $options);
            if($key !== null) {
                unset($options[$key]);
                $option->limit = isset($limits[$key]) ? $limits[$key] : 0;
            }
        }

        // add options if not of the result set
        foreach ($options as $key => $option) {
            $col[] = (object)[
                "slug" => $option,
                "count" => 0,
                "selected" => false,
                "limit" => isset($limits[$key]) ? $limits[$key] : 0
            ];
        }

        // choose which col to select
        $thisfield = $this->{'customfield'.$field_id};
        if ($thisfield === null) {
            for ($i = count($col)-1; $i > 0;) {
                if (in_array($col[$i]->slug, $excludes)) { // exlude
                    $i--;
                } elseif( // keep limit
                    $col[$i]->limit > 0 &&
                    $col[$i]->count >= $col[$i]->limit
                ) {
                    $i--;
                } else {
                    break;
                }
            }
            $col[$i]->selected = true;
        } else {
            foreach ($col as $option) {
                if($option->slug === $thisfield) $option->selected = true;
            }
        }

        return $col;
    }

    public function distribute_fill_first($field_id, $options = [], $limit = 0, $default = null) {
        global $db;

        $col = $db->get_col_with_count("rg_customfield".$field_id);
        foreach ($col as $option) {
            $option->selected = false;
            $key = array_search($option->slug, $options);
            if($key !== null) unset($options[$key]);
        }
        var_dump($col, $options);
        foreach ($options as $option) {
            $col[] = (object)["slug" => $option, "count" => 0, "selected" => false];
        }

        $thisfield = $this->{'customfield'.$field_id} ?: $default;
        if ($thisfield === null) {
            foreach ($col as $option) {
                if($limit > 0 && $col->count < $limit) {
                    $option->selected = true;
                    break;
                }
            }
        } else {
            foreach ($col as $option) {
                if($option->slug === $thisfield) $option->selected = true;
            }
        }

        return $col;
    }

    public function print_button_with_count( $slug, $field_id ) {
        global $db;

        $col = $db->get_col_with_count("rg_customfield".$field_id);

        foreach ($col as $option) {
            if($option->slug === $slug) break;
            $option = null;
        }
        if(!$col || !$option) $option = (object)["slug" => $slug, "count" => 0];

        printf('<input class="form-check-input" type="radio" name="%1$s" id="%2$s" value="%2$s" %4$s><label class="form-check-label mr-4" for="%2$s">[%3$s] %2$s</label>',
               'customfield'.$field_id,
               $slug,
               $option->count,
               $this->{'customfield'.$field_id} === $slug ? 'checked' : ''
        );
    }

}
