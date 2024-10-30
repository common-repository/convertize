<?php
/*
Plugin Name: Convertize
Description: Install Convertize on your WordPress website in less then 10 seconds. Integrate unique tracking code of Convertize into every page of your website in one click.
Author: Convertize
Version: 1.0.6
Author URI: https://convertize.io
License: GPLv2
Plugin URI: https://convertize.io/#

*/

/**
 * Prevent Direct Access
 */
defined('ABSPATH') or die("Restricted access!");

define('CONVERTIZE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CONVERTIZE_PLUGIN_DIR', str_replace('\\','/',dirname(__FILE__)));

if (!class_exists('HeaderAndFooterScripts')) {
    class ConvertizeHeaderPixelCode
    {
        public function __construct()
        {
            add_action('init', array(&$this, 'init'));
            add_action('admin_init', array(&$this, 'init_settings'));
            add_action('admin_menu', array(&$this, 'init_menu'));
            add_action('wp_head', array(&$this, 'print_pixel'));
        }
    
        public function init()
        {
            load_plugin_textdomain('convertize', false, dirname(plugin_basename(__FILE__)).'/lang');
        }

        public function init_settings()
        {
            register_setting(
                'insert-convertize-pixel',
                'convp_insert_header',
                array(&$this, 'convertize_pixel_sanitize')
            );
        }

        public function init_menu()
        {
            add_menu_page(
                'Convertize',
                'Convertize',
                'manage_options',
                'convertize',
                array(&$this, 'convertize_options_panel'),
                CONVERTIZE_PLUGIN_URL . 'icon.png'
            );
        }

        public function print_pixel()
        {
            $meta = get_option('convp_insert_header', '');
        
            if (!empty($meta)) {
                echo $meta, "\n";
            }
        }

        public function convertize_pixel_sanitize($data)
        {
            $data = trim($data);

            if (!$data) {
                return '';
            }
        
            if (!$this->is_valid_pixel($data)) {
                add_settings_error(
                    'insert-convertize-pixel',
                    esc_attr('settings_updated'),
                    'This field does not a contain valid Convertize pixel',
                    'error'
                );
                return '';
            }
        
            return $data;
        }
        
        public function convertize_options_panel()
        {
            require_once(CONVERTIZE_PLUGIN_DIR . '/inc/options.php');
        }

        private function is_valid_xml($content)
        {
            $content = trim($content);
            if (empty($content)) {
                return false;
            }

            libxml_use_internal_errors(true);
            simplexml_load_string($content);
            $errors = libxml_get_errors();          
            libxml_clear_errors();  

            return empty($errors);
        }

        private function is_valid_pixel($content)
        {
            if(!preg_match('/^<script src="\/\/pixel\.convertize\.io\/[0-9]+\.js\"(|.*?)><\/script>$/', $content)) {
                return false;
            }
            return true;
        }
    }

    $convertize_header_pixel_code = new ConvertizeHeaderPixelCode();
}