<?php


class panRedaktor_Settings {

    /* Plugin options corresponding to WordPress options */
    private $pr_selectors = NULL;
    private $pr_mode = NULL;

    /* Validation options */
    private $validate_settings = array(
        'emails' => array(),
        'not-empty' => array(),
        'not-script' => array(),
        'css-basic' => array(),
        'urls' => array()
    );

    private $errors = array();
    private $exists = FALSE;


    function __construct($id = NULL) {
        $this->load();
    }

    private function load(){
        $model = new panRedaktor_Model();
        $settings = $model->getSettings();

        if(isset($settings)){
            $this->setFields($settings);
            $this->exists = TRUE;
        }
    }

    function setFields($fields){
        foreach($fields as $key => $val){
            $this->{$key} = $val;
        }
    }

    public function exists(){
        return $this->exists;
    }


    function getField($field){
        if(isset($this->{$field})){
            return $this->{$field};
        }

        return NULL;
    }

    function setError($field, $error){
        $this->errors[$field] = $error;
    }

    function getError($field){
        if(isset($this->errors[$field])){
            return $this->errors[$field];
        }

        return NULL;
    }

    function hasError($field){
        return isset($this->errors[$field]);
    }

    function hasErrors(){
        return (count($this->errors) > 0);
    }


    function validate(){

        foreach($this->validate_settings['emails'] as $s) {
            if(!empty($this->$s)) {
                if(!is_email($this->$s)){
                    $this->setError($s, __('Ten adres e-mail jest niepoprawny','pan-redaktor'));
                }
            }
        }
        foreach($this->validate_settings['not-empty'] as $s) {
            if(empty($this->$s)){
                $this->setError($s, __('To opcja nie może być nieustawiona lub pusta','pan-redaktor'));
            }
        }
        foreach($this->validate_settings['not-script'] as $s) {
            if(stripos($this->$s,'<script>') !== FALSE){
                $this->setError($s, __('Ten obszar tekstowy nie może zawierać JavaScriptu','pan-redaktor'));
            }
        }
        foreach($this->validate_settings['css-basic'] as $s) {
            if(!empty($this->$s)) {
                if(!preg_match("#^[a-zA-Z0-9%]+$#", $this->$s)){
                    $this->setError($s, __('To pole może zawierać tylko cyfry, litery oraz znak %, np. 10px, 10%','pan-redaktor'));
                }
            }
        }
        foreach($this->validate_settings['urls'] as $s) {
            if(!empty($this->$s)) {
                if(filter_var($this->$s, FILTER_VALIDATE_URL) === FALSE){
                    $this->setError($s, __('Ten adres URL jest niepoprawny','pan-redaktor'));
                }
            }
        }

        return (!$this->hasErrors());
    }

}
