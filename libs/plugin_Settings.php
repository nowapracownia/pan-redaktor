<?php


class panRedaktor_Settings {

    private $pr_selectors = NULL;

    private $pr_mode = NULL;

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

        if(!empty($this->pr_selectors)){

            /*if([proper_condition]($this->pr_selectors)) {
                $this->setError('pr_selectors', __('Error message','pr-selectors'));
            }*/

        }

        if(empty($this->pr_mode)){

            $this->setError('pr_mode', __('Wybierz któryś z trybów działania','pr-mode'));

        }

        return (!$this->hasErrors());
    }

}
