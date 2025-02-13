<?php

if(!class_exists('rtTPTSCButton')):

    class rtTPTSCButton{

        public $sc_tag = 'rt_tpt_scg';

        function __construct() {
            if ( is_admin() ){
                add_action('admin_head', array( $this, 'admin_head') );
            }
        }
        /**
         * admin_head
         * calls your functions into the correct filters
         * @return void
         */
        function admin_head() {
            // check user permissions
            if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
                return;
            }
            // check if WYSIWYG is enabled
            if ( 'true' == get_user_option( 'rich_editing' ) ) {
                add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
                add_filter( 'mce_buttons', array($this, 'mce_buttons' ) );
                global $rtTPT;
                echo "<style>";
                echo "i.mce-i-rt_tpt_scg{";
                echo "background: url('".$rtTPT->assetsUrl ."img/rt-tpt-sc.png');";
                echo "}";
                echo "</style>";
            }
        }
        /**
         * mce_external_plugins
         * Adds our tinymce plugin
         * @param  array $plugin_array
         * @return array
         */
        function mce_external_plugins( $plugin_array ) {
            global $rtTPT;
            $plugin_array[$this->sc_tag] = $rtTPT->assetsUrl .'js/mce-button.js';
            return $plugin_array;
        }

        /**
         * mce_buttons
         * Adds our tinymce button
         * @param  array $buttons
         * @return array
         */
        function mce_buttons( $buttons ) {
            array_push( $buttons, $this->sc_tag );
            return $buttons;
        }

    }

endif;