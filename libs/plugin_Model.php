<?php


class panRedaktor_Model {

    private $wpdb;

    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    function getSettings(){
        $settings = array();
        $pr = new panRedaktor();
        $options = $pr->getOptions();
        unset($pr);
        foreach($options as $o) {
            /* this method populates the array with all the plugin options. IMPORTANT: the names of the input fields has to correspond: name = class variable = option name */
            $settings[$o] = get_option($o);
        }
        return ($settings);
    }

    function saveSettings(panRedaktor_Settings $settings){

        // set options to update and values to save
        $pr = new panRedaktor();
        $options = $pr->getOptions();
        unset($pr);
        $opts_and_values = array();
        foreach($options as $o) {
            $opts_and_values[$o] = $settings->getField($o);
        }
        $status = $this->saveSettings_updateOptions($opts_and_values);
        if($status !== FALSE) return TRUE;
        else return FALSE;

    }

    private function saveSettings_updateOptions($opts_and_values = array()) {
        $status = TRUE;
        foreach($opts_and_values as $o=>$v) {
            if(get_option($o) != $v) $save = update_option($o,$v);
            if($save === FALSE) $status = FALSE;
        }
        return $status;
    }

}
