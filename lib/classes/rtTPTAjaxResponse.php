<?php

if ( ! class_exists( 'rtTPTAjaxResponse' ) ):

	class rtTPTAjaxResponse {
		public function __construct() {
			add_action( 'wp_ajax_tpt_add_row_action', array( $this, 'tpt_add_row_action' ) );
			add_action( 'wp_ajax_tpt_edit_row_action', array( $this, 'tpt_edit_row_action' ) );
			add_action( 'wp_ajax_tpt_style_action', array( $this, 'tpt_style_action' ) );
			add_action( 'wp_ajax_tpt_add_new_col_action', array( $this, 'tpt_add_new_col_action' ) );
			add_action( 'wp_ajax_tptSettingsAction', array( $this, 'tpt_Settings_Update' ) );
			add_action( 'wp_ajax_rtTPTShortCodeList', array($this, 'shortCodeList'));
		}

		function tpt_edit_row_action() {
			global $rtTPT;
			$error = true;
			$html  = null;
			if ( $rtTPT->verifyNonce() ) {
				$error = false;
				$value = ! empty( $_REQUEST['value'] ) ? $_REQUEST['value'] : null;
				$html  .= "<div class='popup-field-wrap'>";
				ob_start();
				include $rtTPT->viewsPath . 'admin/body-item-options.php';
				$html .= ob_get_contents();
				$html .= "</div>";
				ob_end_clean();
			}
			$response = array(
				'error' => $error,
				'html'  => $html,
			);
			wp_send_json( $response );
			die();
		}

		function tpt_add_row_action() {
			global $rtTPT;
			$error = true;
			$html  = null;
			if ( $rtTPT->verifyNonce() ) {
				$tpt_id = ! empty( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : 0;
				$error  = false;
				ob_start();
				include $rtTPT->viewsPath . 'admin/body-item.php';
				$html .= ob_get_contents();
				ob_end_clean();
			}
			$response = array(
				'error' => $error,
				'html'  => $html,
			);
			wp_send_json( $response );
			die();
		}

		function tpt_style_action() {
			global $rtTPT;
			$error = true;
			$html  = null;
			$type  = ! empty( $_REQUEST['type'] ) ? sanitize_text_field( $_REQUEST['type'] ) : null;
			if ( $rtTPT->verifyNonce() && $type ) {
				$error = false;
				$value = ! empty( $_REQUEST['value'] ) ? $_REQUEST['value'] : null;
				ob_start();
				echo "<div class='popup-field-wrap'>";
				switch ( $type ) {
					case 'general':
						include $rtTPT->viewsPath . 'admin/general-style.php';
						break;
					case 'header':
						include $rtTPT->viewsPath . 'admin/header-style.php';
						break;
					case 'price':
						include $rtTPT->viewsPath . 'admin/price-style.php';
						break;
					case 'body':
						include $rtTPT->viewsPath . 'admin/body-style.php';
						break;
					case 'footer':
						include $rtTPT->viewsPath . 'admin/footer-style.php';
						break;
					default:
						break;
				}
				echo "</div>";
				$html .= ob_get_contents();
				ob_end_clean();
			}
			$response = array(
				'error' => $error,
				'html'  => $html,
			);
			wp_send_json( $response );
			die();
		}

		function tpt_add_new_col_action() {
			global $rtTPT;
			$error = true;
			$data  = false;
			if ( $rtTPT->verifyNonce() ) {
				$tpt_id = ! empty( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : 0;
				$error  = false;
				ob_start();
				include $rtTPT->viewsPath . 'admin/price-table.php';
				$data = ob_get_contents();
				ob_end_clean();
			}

			$response = array(
				'error' => $error,
				'data'  => $data,
			);
			wp_send_json( $response );
			die();
		}

		function tpt_Settings_Update() {
			global $rtTPT;
			$error = true;
			if ( $rtTPT->verifyNonce() ) {
				$data = $newData = array();
				parse_str( $_REQUEST['data'], $data );
				$allSettingsField = array_merge(
					$rtTPT->rtTPTGeneralSettingsFields(),
					$rtTPT->rtTPTSettingsCustomCssFields()
				);
				foreach ($allSettingsField as $metaKey => $field){
					$newData[$metaKey]  = $rtTPT->sanitize( $field, $data[$metaKey] );
				}
				update_option( $rtTPT->options['settings'], $newData );
				$error = false;
				$msg   = __( 'Settings successfully updated', 'the-pricing-table' );
			} else {
				$msg = __( 'Security Error !!', 'the-pricing-table' );
			}
			$response = array(
				'error' => $error,
				'msg'   => $msg
			);
			wp_send_json( $response );
			die();
		}

		function shortCodeList(){
			global $rtTPT;
			$html = null;
			$scQ = new WP_Query( array('post_type' => $rtTPT->post_type, 'order_by' => 'title', 'order' => 'ASC', 'post_status' => 'publish', 'posts_per_page' => -1) );
			if ( $scQ->have_posts() ) {

				$html .= "<div class='mce-container mce-form'>";
				$html .= "<div class='mce-container-body'>";
				$html .= '<label class="mce-widget mce-label" style="padding: 20px;font-weight: bold;" for="scid">'.__('Select Short code',  'the-pricing-table').'</label>';
				$html .= "<select name='id' id='scid' style='width: 150px;margin: 15px;'>";
				$html .= "<option value=''>".__('Default',  'the-pricing-table')."</option>";
				while ( $scQ->have_posts() ) {
					$scQ->the_post();
					$html .="<option value='".get_the_ID()."'>".get_the_title()."</option>";
				}
				$html .= "</select>";
				$html .= "</div>";
				$html .= "</div>";
			}else{
				$html .= "<div>".__('No shortCode found.', 'the-pricing-table')."</div>";
			}
			echo $html;
			die();
		}
	}

endif;