<?php

if ( ! class_exists( 'rtTPTInit' ) ):
	class rtTPTInit {

		public function __construct() {
			add_action( 'init', array( $this, 'init' ), 1 );
			add_action( 'admin_menu', array( $this, 'tgt_menu_register' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'settings_admin_enqueue_scripts' ) );
			add_action( 'plugins_loaded', array( $this, 'tpt_load_text_domain' ) );
			add_filter( 'plugin_action_links_' . RT_TPT_PLUGIN_ACTIVE_FILE_NAME, array($this, 'tpt_marketing') );
		}
		function tpt_marketing($links){
			$links[] = '<a target="_blank" href="'. esc_url( 'http://demo.radiustheme.com/wordpress/plugins/pricing-table/' ) .'">'.__( 'Demo', "the-pricing-table" ).'</a>';
			$links[] = '<a target="_blank" href="'. esc_url( 'https://www.radiustheme.com/setup-configure-pricing-table-wordpress/' ) .'">'.__( 'Documentation', "the-pricing-table" ).'</a>';
			return $links;
		}

		function init() {
			// Create the post grid post type
			$labels = array(
				'name'               => __( 'The Pricing Table', 'the-post-grid-pro' ),
				'singular_name'      => __( 'The Pricing Table', 'the-post-grid-pro' ),
				'add_new'            => __( 'Add New Pricing Table', 'the-post-grid-pro' ),
				'all_items'          => __( 'All Pricing Table', 'the-post-grid-pro' ),
				'add_new_item'       => __( 'Add New Pricing Table', 'the-post-grid-pro' ),
				'edit_item'          => __( 'Edit Pricing Table', 'the-post-grid-pro' ),
				'new_item'           => __( 'New Pricing Table', 'the-post-grid-pro' ),
				'view_item'          => __( 'View Pricing Table', 'the-post-grid-pro' ),
				'search_items'       => __( 'Search Pricing Tables', 'the-post-grid-pro' ),
				'not_found'          => __( 'No Pricing Tables found', 'the-post-grid-pro' ),
				'not_found_in_trash' => __( 'No Pricing Tables found in Trash', 'the-post-grid-pro' ),
			);

			global $rtTPT;

			register_post_type( $rtTPT->post_type, array(
				'labels'          => $labels,
				'public'          => false,
				'show_ui'         => true,
				'_builtin'        => false,
				'capability_type' => 'page',
				'hierarchical'    => true,
				'menu_icon'       => $rtTPT->assetsUrl . 'admin/images/icon_16x16.png',
				'rewrite'         => false,
				'query_var'       => $rtTPT->post_type,
				'supports'        => array( 'title' ),
				'show_in_menu'    => true
			) );
			$scripts = $styles = array();


			$scripts['rt-tpt']         = array(
				'src'    => $rtTPT->assetsUrl . "js/the-pricing-table.js",
				'deps'   => array( 'jquery' ),
				'footer' => true
			);
			$styles['rt-font-awesome'] = $rtTPT->assetsUrl . 'vendor/font-awesome/css/font-awesome.min.css';
			$styles['rt-tpt']          = $rtTPT->assetsUrl . 'css/the-pricing-table.css';
			if ( is_admin() ) {
				$scripts['ace_code_highlighter_js'] = array(
					'src'    => $rtTPT->assetsUrl . "vendor/ace/ace.js",
					'deps'   => null,
					'footer' => true
				);
				$scripts['ace_mode_js']             = array(
					'src'    => $rtTPT->assetsUrl . "vendor/ace/mode-css.js",
					'deps'   => array( 'ace_code_highlighter_js' ),
					'footer' => true
				);

				$scripts['rt-select2'] = array(
					'src'    => $rtTPT->assetsUrl . "vendor/select2/select2.min.js",
					'deps'   => array( 'jquery' ),
					'footer' => false
				);

				$scripts['rt-tpt-admin'] = array(
					'src'    => $rtTPT->assetsUrl . "js/admin.js",
					'deps'   => array( 'jquery' ),
					'footer' => true
				);
				$styles['rt-select2']    = $rtTPT->assetsUrl . 'vendor/select2/select2.min.css';
				$styles['rt-tpt-admin']  = $rtTPT->assetsUrl . 'css/admin.css';
			}


			foreach ( $scripts as $key => $script ) {
				wp_register_script( $key, $script['src'], $script['deps'], time(), $script['footer'] );
			} //$rtTPG->options['version']


			foreach ( $styles as $k => $v ) {
				wp_register_style( $k, $v, false, rand( 1, 233 ) );
			}

		}

		function tgt_menu_register() {
			global $rtTPT;
			add_submenu_page(
				'edit.php?post_type=' . $rtTPT->post_type, __( 'Settings' ),
				__( 'Settings', "the-pricing-table" ),
				'administrator',
				'rttpt_settings',
				array(
					$this,
					'rttpt_settings'
				) );
		}

		function rttpt_settings() {
			global $rtTPT;
			$rtTPT->render( 'settings' );
		}

		function settings_admin_enqueue_scripts() {
			global $pagenow, $typenow, $rtTPT;

			// validate page
			if ( ! in_array( $pagenow, array( 'edit.php' ) ) ) {
				//return;
			}
			if ( $typenow != $rtTPT->post_type ) {
				return;
			}
			wp_enqueue_media();
			wp_enqueue_script( array(
				'jquery',
				'wp-color-picker',
				'jquery-ui-sortable',
				'ace_code_highlighter_js',
				'ace_mode_js',
				'rt-select2',
				'rt-tpt-admin'
			) );

			// styles
			wp_enqueue_style( array(
				'wp-color-picker',
				'rt-font-awesome',
				'rt-select2',
				'rt-tpt-admin'
			) );

			wp_localize_script( 'rt-tpt-admin', 'rttpt',
				array(
					'nonceID' => $rtTPT->nonceId(),
					'nonce'   => wp_create_nonce( $rtTPT->nonceText() ),
					'ajaxurl' => admin_url( 'admin-ajax.php' )
				) );
		}

		function tpt_load_text_domain(){
			load_plugin_textdomain( 'the-pricing-table', false, RT_TPT_LANGUAGE_PATH );
		}

	}
endif;