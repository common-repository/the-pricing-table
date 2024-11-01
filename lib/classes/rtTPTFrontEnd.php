<?php
if ( ! class_exists( 'rtTPTFrontEnd' ) ):

	class rtTPTFrontEnd {
		public function __construct(){
			add_action( 'wp_enqueue_scripts', array($this, 'wp_enqueue_scripts') );
		}

		function wp_enqueue_scripts(){
			wp_enqueue_style(array(
				'rt-font-awesome',
				'rt-tpt'
			));
			global $rtTPT;
			$settings = get_option($rtTPT->options['settings']);
			$css = isset($settings['custom_css']) ? trim($settings['custom_css']) : null;
			if($css) {
				wp_add_inline_style( 'rt-tpt', $css );
			}
			wp_enqueue_script(array(
				'rt-tpt'
			));
		}
	}
	
endif;