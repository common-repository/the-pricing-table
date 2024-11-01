<?php

if ( ! class_exists( 'rtTPTMeta' ) ):

	class rtTPTMeta {

		public function __construct() {
			add_action( 'admin_head', array( $this, 'admin_head' ) );
			add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
			add_action( 'edit_form_after_title', array( $this, 'tpt_sc_after_title' ) );
			add_filter( 'manage_edit-rt_price_table_columns', array( $this, 'arrange_rttpt_columns' ) );
			add_action( 'manage_rt_price_table_posts_custom_column', array( $this, 'manage_rttpt_columns' ), 10, 2 );
		}

		function save_post( $post_id, $post ) {
			global $rtTPT;
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			if ( ! $rtTPT->verifyNonce() ) {
				return $post_id;
			}
			if ( $rtTPT->post_type != $post->post_type ) {
				return $post_id;
			}

			$tpt_layout = ! empty( $_REQUEST['tpt_layout'] ) ? sanitize_text_field($_REQUEST['tpt_layout']) : 'layout_01';
			$data       = ! empty( $_REQUEST['tpt_data'] ) ? $rtTPT->sanitize_tpt_data($_REQUEST['tpt_data']) : null;
			update_post_meta( $post->ID, 'tpt_layout', $tpt_layout );
			update_post_meta( $post->ID, 'tpt_data', $data );

		}

		function admin_head() {

			global $rtTPT;
			add_meta_box(
				'rttpt_meta',
				__( 'Pricing Table Generator', 'the-pricing-table' ),
				array( $this, 'rttpt_meta_settings_selection' ),
				$rtTPT->post_type,
				'normal',
				'high' );

		}

		function rttpt_meta_settings_selection( $post ) {
			global $rtTPT;
			wp_nonce_field( $rtTPT->nonceText(), $rtTPT->nonceId() );
			$tpt_layout = get_post_meta( $post->ID, 'tpt_layout', true );
			$tpt_layout = $tpt_layout ? $tpt_layout : 'layout_01';
			$data       = get_post_meta( $post->ID, 'tpt_data', true );
			$html       = null;
			$html       .= "<div class='tpt-wrapper'>";
			$html       .= "<div class='tpt-field-wrap'>";
			$html       .= $rtTPT->rtFieldGenerator( array(
				'tpt_layout' => array(
					'label'   => __( 'Table Layout', 'the-pricing-table' ),
					'type'    => 'select',
					'class'   => 'rt-select2 selected_img_preview',
					'attr'    => "data-src='{$rtTPT->assetsUrl}admin/images/layout/'",
					'options' => $rtTPT->getLayoutList(),
					'value'   => $tpt_layout
				),
			) );
			$html       .= "</div>";
			$html       .= "<div class='top-toolbar'><span id='tpt-add-new-col' class='button button-primary'><span class='dashicons dashicons-plus-alt'></span>Add New Column</span></div>";
			$html       .= "<div id='tpt-table-wrapper' class='rt-clear'>";
			if ( ! empty( $data ) ) {
				foreach ( $data as $tpt_id => $pt ) {
					$tpt_general_style   = ! empty( $pt['general']['style'] ) ? $pt['general']['style'] : null;
					$selected_layout     = ! empty( $pt['general']['layout'] ) ? $pt['general']['layout'] : null;
					$tpt_header_title    = ! empty( $pt['header']['title'] ) ? $pt['header']['title'] : null;
					$tpt_header_content  = ! empty( $pt['header']['content'] ) ? $pt['header']['content'] : null;
					$tpt_header_style    = ! empty( $pt['header']['style'] ) ? $pt['header']['style'] : null;
					$tpt_price           = ! empty( $pt['price']['amount'] ) ? $pt['price']['amount'] : null;
					$tpt_price_period    = ! empty( $pt['price']['period'] ) ? $pt['price']['period'] : null;
					$tpt_price_style     = ! empty( $pt['price']['style'] ) ? $pt['price']['style'] : null;
					$tpt_footer_btn_text = ! empty( $pt['footer']['text'] ) ? $pt['footer']['text'] : null;
					$tpt_footer_btn_url  = ! empty( $pt['footer']['url'] ) ? $pt['footer']['url'] : null;
					$tpt_footer_style    = ! empty( $pt['footer']['style'] ) ? $pt['footer']['style'] : null;

					$tpt_body_items = ! empty( $pt['body']['items'] ) ? $pt['body']['items'] : array();
					$tpt_body_style = ! empty( $pt['body']['style'] ) ? $pt['body']['style'] : null;

					ob_start();
					include $rtTPT->viewsPath . 'admin/price-table.php';
					$html .= ob_get_contents();
					ob_end_clean();
				}
			}
			$html .= "</div>";
			$html .= "</div>";
			echo $html;
		}


		function tpt_sc_after_title( $post ) {
			global $rtTPT;
			if ( $rtTPT->post_type !== $post->post_type ) {
				return;
			}
			$html = null;
			$html .= '<div class="postbox rt-after-title" style="margin-bottom: 0;"><div class="inside">';
			$html .= '<p><input type="text" onfocus="this.select();" readonly="readonly" value="[the-pricing-table id=&quot;' . $post->ID . '&quot; title=&quot;' . $post->post_title . '&quot;]" class="large-text code rt-code-sc">
            <input type="text" onfocus="this.select();" readonly="readonly" value="&#60;&#63;php echo do_shortcode( &#39;[the-pricing-table id=&quot;' . $post->ID . '&quot; title=&quot;' . $post->post_title . '&quot;]&#39; ); &#63;&#62;" class="large-text code rt-code-sc">
            </p>';
			$html .= '</div></div>';

			echo $html;
		}

		public function manage_rttpt_columns( $column ) {
			switch ( $column ) {
				case 'shortcode':
					echo '<input type="text" onfocus="this.select();" readonly="readonly" value="[the-pricing-table id=&quot;' . get_the_ID() . '&quot; title=&quot;' . get_the_title() . '&quot;]" class="large-text code rt-code-sc">';
					break;
				default:
					break;
			}
		}

		function arrange_rttpt_columns( $columns ) {
			$shortcode = array( 'shortcode' => __( 'Shortcode', 'the-pricing-table' ) );

			return array_slice( $columns, 0, 2, true ) + $shortcode + array_slice( $columns, 1, null, true );
		}

	}

endif;