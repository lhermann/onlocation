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

        $this->id           = $row->rg_registrationID;
        $this->date_arrived = $row->rg_date_arrived;
        $this->firstname    = $row->rg_firstname;
        $this->lastname     = $row->rg_lastname;
        $this->email        = $row->rg_email;
        $this->gender       = $row->rg_gender;
        $this->translation  = (bool) $row->{FIELDS['translation']};
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
        $this->registration = $row->{FIELDS['registration']};
        $this->t_shirt      = $row->{FIELDS['t-shirt']};

        $this->u18           = $this->is_under18();
        $this->guardian_id   = $row->{FIELDS['guardian_id']};
        $this->guardian_name = $row->{FIELDS['guardian_name']};
        $this->has_guardian  = $this->has_guardian_set();
        $this->u18_letter    = $row->rg_parental_letter_received !== null;

        /*
         * volunteer/helper info
         */
        // area
        $this->area = $row->{FIELDS['area-private']} ?: AREA[$row->{FIELDS['area-public']}];
        // status
        $this->status = $row->{FIELDS['label']} ?: 'Volunteer';
        if (!$this->area || $this->status == 'Freiperson') $this->status = 'Teilnehmer';
        if (strpos($this->registration, 'day')) $this->status = 'Tagesgast';
        // remove area of some stati
        if(in_array($this->status, ['Teilnehmer', 'TTBW', 'Medical Team']))
            $this->area = '';

        /*
         * lodging
         */
        $this->has_lodging = in_array($this->registration, ['attendee', 'reduced']);
        $this->external_lodging = $row->{FIELDS['external-housing']} == 'ExternalHousing';
        $this->room_id = $row->rg_assigned_roomID;

        if( $this->room_id === null && ( $this->external_lodging || !$this->has_lodging ) ) {
            $this->room_id = 0;
        }

        /*
         * meals
         */
        // if they booked food then:
        $this->has_meal = in_array($this->registration, ['attendee', 'reduced']);
        $this->has_food_priv = $this->is_helper() || (bool) $row->{FIELDS['food-priv']};
        $this->meal = $row->{FIELDS['food-time']};
        if( $this->has_meal && $this->has_food_priv ) {
            $this->meal = 'PrivEater';
        }

    }

    private function is_under18() {
        return strtotime('-18 year') < $this->birthdate ? true : false;
    }

    private function has_guardian_set() {
        return $this->guardian_id && $this->guardian_name || !$this->u18 ? true : false;
    }

    public function is_attendee() {
        return $this->status === 'Teilnehmer';
    }

    public function is_helper() {
        return !in_array($this->status, ['Teilnehmer', 'Standleiter']);
    }

    public function update( $a_in ) {
        global $db;

        $a_out = array();
        // var_dump($a_in); die();

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
        if( $this->u18 )
            $a_out['rg_parental_letter_received'] = isset($a_in['u18_letter']) ? date('Y-m-d') : null;

        // guardian
        if( isset($a_in['guardian_name']) ) $a_out['rg_customfield17'] = $a_in['guardian_name'];
        if( isset($a_in['guardian_id']) ) $a_out['rg_customfield18'] = $a_in['guardian_id'];

        // volunteer/helper info
        if( isset($a_in['label']) )        $a_out[FIELDS['label']] = $a_in['label'];
        if( isset($a_in['area_private']) ) $a_out[FIELDS['area-private']] = $a_in['area_private'];

        // lodging
        if( isset($a_in['room_id']) )      $a_out['rg_assigned_roomID'] = $a_in['room_id'];

        // meals
        if( isset($a_in['food_time']) )    $a_out[FIELDS['food-time']] = $a_in['food_time'];


        // arrival
        if( isset($a_in['date_arrived']) ) $a_out['rg_date_arrived'] = $a_in['date_arrived'];


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
            $this->status,
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

        $patterns[1] = '/%%STATUS%%/';
        $replacements[1] = $this->status;

        $patterns[12] = '/%%AREA%%/';
        $replacements[12] = $this->status !== 'Teilnehmer' ? $this->area : '';

        $room = $db->get_single_row($db->rooms, 'id', $this->room_id);
        $patterns[2] = '/%%ROOM%%/';
        $replacements[2] = $this->room_id ? $room->name : 'Extern';

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

        $patterns[5] = '/%%COMMENT%%/';
        $replacements[5] = "";
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

}
