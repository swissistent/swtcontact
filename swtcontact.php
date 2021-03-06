<?php
    /*
     Plugin Name: Swissistent Tasks Contact Form
     Plugin URI: http://www.swissistent.ch
     Description: Contact Form that sends requests automatically to Swissistent Tasks
     Version: 0.2
     Author: Swissistent GmbH
     Author URI: http://www.swissistent.ch
     Min WP Version: 1.5
     */
    
    require_once "server.php";
    
    class SwissistentTasksContactForm {
        
        static $error;
        static $json;
        static $plugin_dir;
        static $server;
        
        function init(){

            load_plugin_textdomain('swtcontact', false, dirname(plugin_basename(__FILE__)));
            self::$server = new Server("http://88.198.191.154",get_option("username"), get_option("passwordHash"));
            
            self::$plugin_dir = get_option('siteurl').'/'.PLUGINDIR.'/swtcontact/';
            if(function_exists('current_user_can') && current_user_can('manage_options')){
                add_action('admin_menu', array(__CLASS__, 'add_settings_page'));
                add_filter('plugin_action_links', array(__CLASS__, 'register_actions'), 10, 2);
            }
            
            if(get_option("username"))
            {
                $returncode = self::$server->get_group_list();
                self::populate_list($returncode, 'grouplist', 'group_selection');
                
                $returncode = self::$server->get_project_list();
                self::populate_list($returncode, 'projectlist', 'project_selection');

                $returncode = self::$server->get_category_list();
                self::populate_list($returncode, 'categorylist', 'category_selection');                
            }
            else
            {
                add_action('admin_notices', array(__CLASS__, 'admin_notice'));
            }
        }
        
        function populate_list($map, $json_key, $wp_key)
        {
            if (count($map) >= 1 && $map[0]->{"result"}) //kein error, wenn ein result geliefert wird
            {
                $list = $map[0]->{$json_key};
                update_option($wp_key,$list);
                
            }
            else
            {
                self::$error=$map[0]->{"errorinfo"};
            }
        }
        
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
            register_setting('swtcontact', 'passwordHash');
            register_setting('swtcontact', 'ignorePattern');
            register_setting('swtcontact', 'group_selection');
            register_setting('swtcontact', 'group');
            register_setting('swtcontact', 'project_selection');
            register_setting('swtcontact', 'project');
            register_setting('swtcontact', 'category_selection');
            register_setting('swtcontact', 'category');

            
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
                echo '<div id="setting-error-settings_error" class="error settings-error"><p><strong>'.self::$error.'</strong></p></div>';
            }

            $plugin_dir = self::$plugin_dir;
            $in_swtcontact = true;
            require_once "swtcontact.admin.php";
        }
    }
    
    add_action('init', array('SwissistentTasksContactForm', 'init'));

    //---
    
    
    if( ! function_exists('wp_mail') ) {
        function wp_mail( $to, $subject, $message, $headers = '' )
        {
            $message = str_replace("<br />", "", $message);
            $message = str_replace("<br/>", "\r\n", $message);
            $message = str_replace("<strong>Your Message: </strong>", "", $message);

            try
            {
                if (SwissistentTasksContactForm::$server!=null)
                {
                    $ignorePattern=explode("\r\n",get_option('ignorePattern'));
                    
                    
                    if (in_array($subject,$ignorePattern)) //Zum Beispiel Bestaetigungs-E-Mails noch immer per E-Mail senden
                    {
                        return wp_mail_original( $to, $subject, $message, $headers = '' );
                    }
                    else
                    {
                        $returncode = SwissistentTasksContactForm::$server->create_task($subject,get_option('project'),get_option('group'),get_option('category'),$message);

                        if (count($returncode) == 1 && $returncode[0]->{"result"}) //kein error, wenn ein result geliefert wird
                        {
                            return true;
                        }
                        else
                        {
                            return wp_mail_error($to,$subject,$message,json_encode($returncode),$headers);
                        }
                    }
                }
            }
            catch (Exception $e)
            {
                return wp_mail_error($to,$subject,$message,$e,$headers);
            }
            
            return wp_mail_error($to,$subject,$message,'SwissistentTasksContactForm::$server is null',$headers);

        }
    }
    
    function wp_mail_original( $to, $subject, $message, $headers = '' )
    {
        if( $headers == '' )
        {
            $headers = "MIME-Version: 1.0\n" .
            "From: " . get_settings('admin_email') . "\n" .
            "Content-Type: text/plain; charset=\"" . get_settings('blog_charset') . "\"\n";
        }
        return @mail($to, $subject, $message, $headers);
    }
    
    function wp_mail_error( $to, $subject, $message, $error, $headers = '' )
    {
        $errormessage='';
        if ($error instanceof Exception)
            $errormessage=$error->getMessage();
        
        $returncode = wp_mail_original(get_settings('admin_email'),'ERROR in wp_mail_script: '.$errormessage,$error,$headers);
        return $returncode && wp_mail_original($to,$subject,$message,$headers);
    }
    
?>