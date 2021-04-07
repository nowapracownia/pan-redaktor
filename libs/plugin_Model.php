<?php


class panRedaktor_Model {

    private $wpdb;


    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    function getSettings(){
        $settings = array();
        $settings['pr_selectors'] = get_option('pr_selectors');
        $settings['pr_mode'] = get_option('pr_mode');

        return ($settings);
    }

    function saveSettings(panRedaktor_Settings $settings){

        $toSave = array(
            'pr_selectors' => $settings->getField('pr_selectors'),
            'pr_mode' => $settings->getField('pr_mode'),
        );

        // set save status
        $status = TRUE;

        if(get_option('pr_selectors') != $toSave['pr_selectors']) $save = update_option('pr_selectors',$toSave['pr_selectors']);
        if($save === FALSE) $status = FALSE;
        // status updated, if save failed

        if(get_option('pr_mode') != $toSave['pr_mode']) $save = update_option('pr_mode',$toSave['pr_mode']);
        if($save === FALSE) $status = FALSE;

        // status updated, if save failed
        // ------------------------------

        if($status !== FALSE) return TRUE;
        else return FALSE;

    }

}
