<?php
ob_start(); //http://www.lessthanweb.com/blog/wordpress-and-wp_redirect-function-problem
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/*
     * Plugin Name: Pan Redaktor
     * Plugin URI: http://presspro.dev/pan-redaktor
     * Description: Pan Redaktor: plugin, który zadba o to, aby w Twoim tekście nie pozostawały wiszące spójniki.
     * Author: presspro::dev
     * Version: 0.5
     * Author URI: http://presspro.dev/
     */

require_once 'libs/plugin_Model.php';
require_once 'libs/plugin_Settings.php';
require_once 'libs/plugin_Request.php';
require_once 'libs/functions.php';


class panRedaktor{

    private static $plugin_id = 'pan-redaktor';
    private static $plugin_options = array('pr_selectors','pr_mode');
    // check plugin_Settings.php for options key names

    private $plugin_version = '1.0';
    private $user_capability = 'manage_options';
    private $model;
    private $action_token = 'pan-redaktor-action';

    public $data = array();

    function __construct() {
        $this->model = new panRedaktor_Model();

        //uruchamianie podczas aktywacji
        register_activation_hook(__FILE__, array($this, 'onActivate'));

        //uruchamianie podczas deinstalacji
        register_uninstall_hook(__FILE__, array('panRedaktor', 'onUninstall'));

        //rejestracja przycisku w menu
        add_action('admin_menu', array($this, 'createAdminMenu'));

        //rejestracja skryptów panelu admina
        add_action('admin_enqueue_scripts', array($this, 'addAdminPageScripts'));

    }

    function panRedaktorInit() {
        if(!is_admin()) {
            $Settings = new panRedaktor_Settings();
            $selectors = $Settings->getField('pr_selectors');
            $mode = $Settings->getField('pr_mode');
            if($mode == 'script') {
                add_action('wp_footer', function() use ( $selectors ) {
                    do_action('pan_redaktor_before_script');
                    $this->render('script', array(
                            'selectors' => $selectors
                        ), TRUE);
                    do_action('pan_redaktor_after_script');
                });
            }
        }
    }

    function addAdminPageScripts(){

        if(get_current_screen()->id == 'toplevel_page_'.static::$plugin_id){

            // load only if admin page is plugin's page
            wp_enqueue_style( 'pan-redaktor-style', plugin_dir_url(__FILE__) . '/css/pan-redaktor.css', [], time() );

        }

    }

    static function onUninstall(){
        $ver_opt = static::$plugin_id.'-version';
        delete_option($ver_opt);
        $opts = static::$plugin_options;
        foreach($opts as $o) {
            delete_option(esc_attr($o));
        }
    }


    function onActivate(){
        $ver_opt = static::$plugin_id.'-version';
        $installed_version = get_option($ver_opt, NULL);

        if($installed_version == NULL){

            update_option($ver_opt, $this->plugin_version);

        }else{

            switch (version_compare($installed_version, $this->plugin_version)) {
                case 0:
                    //zainstalowana wersja jest identyczna z tą
                    break;

                case 1:
                    //zainstalowana wersja jest nowsza niż ta
                    break;

                case -1:
                    //zainstalowana wersja jest starsza niż ta
                    break;
            }

        }
    }

    function createAdminMenu(){

        add_menu_page(
            'Pan Redaktor',
            'Pan Redaktor',
            $this->user_capability,
            static::$plugin_id,
            array($this, 'printAdminPage'),
            'dashicons-editor-spellcheck'
        );

    }

    function printAdminPage(){

        $request = panRedaktor_Request::instance();

        $view = $request->getQuerySingleParam('view', 'index');
        $action = $request->getQuerySingleParam('action');
        $Settings = new panRedaktor_Settings();

        switch($view){

            case 'index':

                $this->render('form', array(
                    'Settings' => $Settings
                ));
                break;

            case 'form':

                if($action == 'save' && $request->isMethod('POST') && isset($_POST['settings'])){

                    if(check_admin_referer($this->action_token)){

                        $Settings->setFields($_POST['settings']);

                        if($Settings->validate()){

                            $save = $this->model->saveSettings($Settings);

                            if($save !== FALSE){
                                $this->setFlashMsg('Poprawnie zmodyfikowano opcję.');
                                $this->redirect($this->getAdminPageUrl(array('view' => 'index')));

                            }else{
                                $this->setFlashMsg('Nie udało się zmodyfikować opcji', 'error');
                            }
                        }else{
                            $this->setFlashMsg('Popraw błędy formularza', 'error');
                        }

                    }else{
                        $this->setFlashMsg('Błędny token formularza!', 'error');

                    }

                }

                $this->render('form', array(
                    'Settings' => $Settings
                ));
                break;

            default:
                $this->render('404');
                break;

        }
    }


    private function render($view, array $args = array(), $mode = FALSE){

        extract($args);

        $tmpl_dir = plugin_dir_path(__FILE__).'templates/';

        $view = $tmpl_dir.$view.'.php';

        if($mode) require_once $tmpl_dir.'frontend.php';
        else require_once $tmpl_dir.'layout.php';

    }

    public function getAdminPageUrl(array $params = array()){
        $admin_url = admin_url('admin.php?page='.static::$plugin_id);
        $admin_url = add_query_arg($params, $admin_url);

        return $admin_url;
    }


    public function setFlashMsg($message, $status = 'updated'){
        $_SESSION[__CLASS__]['message'] = $message;
        $_SESSION[__CLASS__]['status'] = $status;
    }

    public function getFlashMsg(){
        if(isset($_SESSION[__CLASS__]['message'])){
            $msg = $_SESSION[__CLASS__]['message'];
            unset($_SESSION[__CLASS__]);
            return $msg;
        }

        return NULL;
    }

    public function getFlashMsgStatus(){
        if(isset($_SESSION[__CLASS__]['status'])){
            return $_SESSION[__CLASS__]['status'];
        }

        return NULL;
    }

    public function hasFlashMsg(){
        return isset($_SESSION[__CLASS__]['message']);
    }


    public function redirect($location){
        wp_safe_redirect($location);
        exit;
    }

}

if(is_admin()) $panRedaktor = new panRedaktor();
ob_flush();
?>
