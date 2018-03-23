<?php

/**
 * A single registration
 */
class Reg
{

    public $id, $firstname, $lastname, $gender, $addr, $zip, $city, $state, $country, $paid;

    function __construct( $regid, $row = false )
    {
        if( !$row ) {
            global $db;
            $row = $db->get_single_row($db->main, 'rg_registrationID', $regid);
        }

        $this->id           = $row->rg_registrationID;
        $this->date_arrived = $row->rg_date_arrived;

        // Page 2
        $this->firstname    = $row->rg_firstname;
        $this->lastname     = $row->rg_lastname;
        $this->gender       = $row->rg_gender;
        $this->translation  = (bool) $row->rg_customfield7;
        $this->birthdate    = strtotime( $row->rg_birthday.'-'.$row->rg_birthmonth.'-'.$row->rg_birthyear);
        $this->year         = $row->rg_birthyear;
        $this->addr         = $row->rg_addr;
        $this->zip          = $row->rg_zip;
        $this->city         = $row->rg_city;
        $this->state        = $row->rg_state;
        $this->country      = $row->rg_country;
        $this->to_pay       = $row->rg_amount_to_pay;
        $this->paid         = $row->rg_amount_to_pay === '0.00' || $row->rg_amount_received !== null;
        $this->comment      = $row->rg_payment_comments;
        $this->regtype      = $row->rg_customfield3;

        $this->u18           = $this->is_under18();
        $this->guardian_id   = $row->{FIELDS['guardian_id']};
        $this->guardian_name = $row->{FIELDS['guardian_name']};
        $this->has_guardian  = $this->has_guardian_set();
        $this->u18_letter    = $row->rg_parental_letter_received !== null;

        // Page 3
        $this->position     = $row->rg_customfield19 ?: $row->rg_customfield16 ?: $row->rg_customfield14;

        // Page 4
        if ( substr( $row->rg_customfield3, 0, 12 ) === "AllInclusive" ) {
            $this->meal = $row->rg_customfield5;
            if( $this->position ) $this->meal = "PrivEater";
        } else {
            $this->meal = false;
        }

        // if( $this->position ) {
        //     $this->meal = "PrivEater";
        // } elseif( substr( $row->rg_customfield3, 0, 12 ) === "AllInclusive" ) {
        //     $this->meal = $row->rg_customfield5;
        // } else {
        //     $this->meal = false;
        // }

        if( $row->rg_assigned_roomID ) {
            $this->room  = $row->rg_assigned_roomID;
        } else if( substr( $this->regtype, 0, 13 ) === "NoFoodLodging" || $row->rg_customfield4 == "ExternalHousing" ) {
            $this->room  = "extern";
        }

    }

    private function is_under18() {
        return strtotime('-18 year') < $this->birthdate ? true : false;
    }

    private function has_guardian_set() {
        return $this->guardian_id && $this->guardian_name || !$this->u18 ? true : false;
    }

    public function update( $a_in ) {
        global $db;

        $a_out = array();

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
        if( isset($a_in['firstname']) ) {
            $a_out['rg_parental_letter_received'] = isset($a_in['u18_letter']) ? date('Y-m-d') : NULL;
        }

        // Page 3
        if( isset($a_in['position']) ) {
            $a_out['rg_customfield19'] = $a_in['position'] == 'other' ? $a_in['position-text'] : $a_in['position'];
        }

        // Page 4
        if( isset($a_in['meal']) ) $a_out['rg_customfield5'] = $a_in['meal'];
        if( isset($a_in['room']) ) $a_out['rg_assigned_roomID'] = $a_in['room'];

        // Page 5
        if( isset($a_in['date_arrived']) ) $a_out['rg_date_arrived'] = $a_in['date_arrived'];

        // Page 90
        if( isset($a_in['guardian_name']) ) $a_out['rg_customfield17'] = $a_in['guardian_name'];
        if( isset($a_in['guardian_id']) ) $a_out['rg_customfield18'] = $a_in['guardian_id'];

        return $db->update_row( $db->main, $this->id, $a_out );
    }

    public function print_position_button( $position ) {
        if( $position === 'other' ) {
            return sprintf('<label class="btn btn-default input-group-addon"><input type="radio" name="position" value="%s" %s></label><input type="text" class="form-control" name="position-text" value="%s">',
                $position,
                !array_key_exists ( $this->position , POSITION ) ? 'checked' : '',
                !array_key_exists ( $this->position , POSITION ) ? $this->position : ''
            );
        } else {
            return sprintf('<label class="btn btn-default text-left %s"><input type="radio" name="position" value="%s" %s> %s</label>',
                $this->position == $position ? 'active' : '',
                $position,
                $this->position == $position ? 'checked' : '',
                POSITION[$position]
            );
        }
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
        if( $this->meal != 'PrivEater' && $meal == 'PrivEater' ) {
            $disabled = true;
        }
        return sprintf('<label class="btn btn-default %s" %s>
                            <input type="radio" name="meal" value="%s" %s %s> %s
                        </label>',
                $this->meal == $meal ? 'active' : '',
                $disabled ? 'disabled="disabled"' : '',
                $meal,
                $this->meal == $meal ? 'checked' : '',
                $disabled ? 'disabled' : '',
                $label
        );
    }

    public function print_room_options() {

        // No lodging
        // if( $this->room == "extern" ) {
        //     return '<option value="extern" selected>Schläft extern</option>';
        // }

        // With Lodging
        global $db;
        $rooms = $db->get_rooms_with_count($this->gender, in_array($this->position, ["Mitarbeiter", "Arbeitskreis"]) ? "Mitarbeiter" : "Teilnehmer");

        // var_dump($rooms); exit;

        // Shift Familienzimmer to end
        $rooms[] = array_shift($rooms);

        // Find room with fewest people
        if( !$this->room ) {
            $min = [0, 2000];
            foreach ($rooms as $key => $room) {
                $min = $room->count < $min[1] ? [$key, $room->count] : $min;
            }
            $this->room = $rooms[$min[0]]->id;
        }


        // Return options
        $return = "";
        $in_list = $this->room == "extern" ? true : false;
        foreach ($rooms as $room) {
            if( $room->id == $this->room ) $in_list = true;
            $return .= sprintf('<option value="%s" %s>[%s] %s (%s %s %s)</option>',
                $room->id,
                $room->id == $this->room ? "selected" : "",
                $room->count,
                $room->id,
                $room->label,
                $room->gender,
                $room->time
            );
        }
        $return .= '<option value="extern" '.($this->room == "extern" ? "selected" : "").'>Schläft extern</option>';
        if( $this->room && !$in_list ) {
            $return .= "<option value=\"$this->room\" selected>$this->room</option>";
        }
        return $return;
    }

    public function human_birthdate() {
        return date('d.m.Y', $this->birthdate);
    }

    public function name() {
        $name = $this->firstname.' '.$this->lastname;
        if( $this->country != 'DE' ) {
            $name .= ' ['.$this->country.($this->translation ? '*' : '').']';
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
        $labeldir = dirname(dirname(__DIR__)) . '/labels/';

        // Get file
        $file = file_get_contents( $labeldir . 'pattern.html' );

        // Replace content
        $patterns = array();
        $replacements = array();

        $patterns[0] = '/%%NAME%%/';
        $replacements[0] = $this->name();

        $patterns[1] = '/%%POSITION%%/';
        $replacements[1] = POSITION[$this->position] ?: $this->position;
        if( $this->state == 'BW' && $this->year >= 1990 ) $replacements[1] .= ' &middot;';

        $patterns[2] = '/%%ROOM%%/';
        $replacements[2] = $this->room;

        $patterns[3] = '/%%FOOD-CLASS%%/';
        $patterns[4] = '/%%FOOD%%/';
        switch ($this->meal) {
            case 'PrivEater':
                $replacements[3] = 'food--priv';
                $replacements[4] = '***';
                break;
            case 'EarlyEater':
                $replacements[3] = 'food--early';
                $replacements[4] = 'Früh';
                break;
            case 'LaterEater':
                $replacements[3] = 'food--late';
                $replacements[4] = 'Spät';
                break;
            default:
                $replacements[3] = 'food--none';
                $replacements[4] = '';
                break;
        }

        $patterns[5] = '/%%COMMENT%%/';
        $replacements[5] = "";
        if( $this->u18 ) {
            $guardian = new Reg($this->guardian_id);
            $replacements[5] = sprintf( "U18 / Aufsichtsperson:<br>[%s] %s (%s)",
                $guardian->id,
                $guardian->name(),
                $guardian->room
            );
        }

        $file = preg_replace($patterns, $replacements, $file);

        // write file
        $this->labelfile = $labeldir . 'src/' . $this->id . '.html';
        $this->labelurl = "/labels/src/" . $this->id . ".html";
        file_put_contents( $this->labelfile, $file );
        return null;
    }

    public function add_label_to_print_queue($id, $printer) {
        global $db;
        $cols = array('ip', 'printerid', 'file');
        $values = array( (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : "") , $printer, $id);
        // var_dump($cols, $values); exit;
        $db->insert_row($db->queue, $cols, $values);
    }

}
