<?php
    /*
     Plugin Name: Swissistent Tasks Contact Form
     Plugin URI: http://www.swissistent.ch
     Description: Contact Form that sends requests automatically to Swissistent Tasks
     Version: 1.0
     Author: Swissistent GmbH
     Author URI: http://www.swissistent.ch
     Update Server: http://www.swissistent.ch/wp-content/download/wp_swissistenttasks_contact
     Min WP Version: 1.5
     Max WP Version: 2.8
     */
    
    include "swthelper.php";
    include "server.php";

    
    class SwissistentTasksContactForm {
        

        
        
        static $error;
        static $json;
        static $plugin_dir;
        static $server;
        
        function init(){

            load_plugin_textdomain('swtcontact', false, dirname(plugin_basename(__FILE__)));
            self::$server = new Server("http://88.198.191.154",get_option("username"), get_option("password"));
            self::$plugin_dir = get_option('siteurl').'/'.PLUGINDIR.'/swtcontact/';
            if(function_exists('current_user_can') && current_user_can('manage_options')){
                add_action('admin_menu', array(__CLASS__, 'add_settings_page'));
                add_filter('plugin_action_links', array(__CLASS__, 'register_actions'), 10, 2);
            }
            
            if(get_option("username"))
            {
                $returncode = self::$server->get_group_list();

                if (count($returncode) == 1 && $returncode[0]->{"result"}) //kein error, wenn ein result geliefert wird
                {
                    $grouplist = $returncode[0]->{"grouplist"};

                    update_option("group_selection",$grouplist);

                }
                else
                {
                    self::$error=$decoded[0]->{"errorInfo"};
                }
                add_action('wp_footer', array(__CLASS__, 'insert_code'));
            }
            else {
                add_action('admin_notices', array(__CLASS__, 'admin_notice'));
            }
        }
        
    //    function insert_code(){
            /* Insert Userlike javascript code into the page */
    /*        if(!self::$is_enabled) return false;
            $secret = get_option("userlike_secret");
            echo "<script src=\"https://s3-eu-west-1.amazonaws.com/userlike-cdn-widgets/".$secret.".js\"></script>";
        }*/
        
        function admin_notice(){
                echo '<div class="error"><p><strong>'.sprintf(__('Die Swissistent Tasks Contact Form Integration ist noch nicht abgeschlossen. Bitte erg&auml;ntzen Sie auf der <a href="%s">Plug-In Seite</a> Tasks Benutzername und Passwort.' ), admin_url('options-general.php?page=swtcontact')).'</strong></p></div>';
        }
        
        function add_settings_page(){
            add_action('admin_init', array(__CLASS__, 'register_settings'));
            add_submenu_page('options-general.php', 'Swissistent Tasks Contact From', 'Swissistent Tasks Contact From', 'manage_options', 'swtcontact', array(__CLASS__, 'settings_page'));
        }
        
        /* Helper Functions */
        
        function register_settings(){
            register_setting('swtcontact', 'username');
            register_setting('swtcontact', 'password');
            register_setting('swtcontact', 'group_selection');
            register_setting('swtcontact', 'group');

            add_settings_section('swtcontact', 'Swissistent Tasks Contact Form', '', 'swtcontact');
        }
        
        function register_actions($links, $file){
            $this_plugin = plugin_basename(__FILE__);
            if($file == $this_plugin && function_exists('admin_url')){
                $settings_link = '<a href="'.admin_url('options-general.php?page=swtcontact').'">'.__('Settings', 'swtcontact').'</a>';
                array_unshift($links, $settings_link);
            }
            return($links);
        }
        
        function settings_page()
        {
            if (self::$error)
            {
                echo_admin_error(self::$error);
            }
            else
            {
            }
            $plugin_dir = self::$plugin_dir;
            $in_swtcontact = true;
            require_once "swtcontact.admin.php";
        }
    }
    
    add_action('init', array('SwissistentTasksContactForm', 'init'));
?>