<?php

if ( ! class_exists( 'rtTPTHelper' ) ):

	class rtTPTHelper {
		function verifyNonce() {
			$nonce = isset( $_REQUEST[ $this->nonceId() ] ) ? $_REQUEST[ $this->nonceId() ] : null;
			if ( ! wp_verify_nonce( $nonce, $this->nonceText() ) ) {
				return false;
			}

			return true;
		}

		function nonceText() {
			return "rttpt_nonce_secret";
		}

		function nonceId() {
			return "rttpt_nonce";
		}

		function meta_exist( $post_id = null, $meta_key, $type = "post" ) {
			if ( ! $post_id ) {
				return false;
			}

			return metadata_exists( $type, $post_id, $meta_key );
		}

		function sanitize_tpt_data( $rawDara ) {
			$data = array();
			if ( ! empty( $rawDara ) && count( $rawDara ) > 0 ) {
				foreach ( $rawDara as $index => $col ) {
					$data[ $index ]['general']['style']  = sanitize_text_field( $col['general']['style'] );
					$data[ $index ]['header']['title']   = sanitize_text_field( $col['header']['title'] );
					$data[ $index ]['header']['content'] = sanitize_text_field( $col['header']['content'] );
					$data[ $index ]['header']['style']   = sanitize_text_field( $col['header']['style'] );
					$mPrice                              = number_format( $col['price']['amount'], 2, '.', ',' );
					$priceA                              = explode( ".", $mPrice );
					$amount                              = $priceA[1] == 0 ? $priceA[0] : $mPrice;
					$data[ $index ]['price']['amount']   = $amount;
					$data[ $index ]['price']['period']   = sanitize_text_field( $col['price']['period'] );
					$bodyItems                           = array();
					if ( ! empty( $col['body']['items'] ) ) {
						foreach ( $col['body']['items'] as $item ) {
							$bodyItems[] = sanitize_text_field( $item );
						}
					}
					$data[ $index ]['body']['items']   = $bodyItems;
					$data[ $index ]['footer']['text']  = sanitize_text_field( $col['footer']['text'] );
					$data[ $index ]['footer']['url']   = esc_url_raw( $col['footer']['url'] );
					$data[ $index ]['footer']['style'] = sanitize_text_field( $col['footer']['style'] );
				}
			}

			return $data;
		}

		function rtFieldGenerator( $fields = array() ) {
			$html = null;
			if ( is_array( $fields ) && ! empty( $fields ) ) {
				$fmField = new rtTPTField();
				foreach ( $fields as $fieldKey => $field ) {
					$html .= $fmField->Field( $fieldKey, $field );
				}
			}

			return $html;
		}

		function tptAllSettingsFields() {
			global $rtTPT;

			return array_merge( $rtTPT->rtTPTGeneralSettingsFields(), $rtTPT->rtTPTSettingsCustomCssFields() );
		}

		function getCurrencyList() {
			$currencyList = array();
			global $rtTPT;
			foreach ( $rtTPT->currency_list() as $id => $currency ) {
				$currencyList[ $id ] = $currency['name'] . " (" . $currency['symbol'] . ")";
			}

			return $currencyList;
		}

		function sanitize( $field = array(), $value = null ) {
			$newValue = null;
			if ( is_array( $field ) ) {
				$type = ( ! empty( $field['type'] ) ? $field['type'] : 'text' );
				if ( empty( $field['multiple'] ) ) {
					if ( $type == 'text' || $type == 'number' || $type == 'select' || $type == 'checkbox' || $type == 'radio' ) {
						$newValue = sanitize_text_field( $value );
					}else if ( $type == 'url' ) {
						$newValue = esc_url( $value );
					} else if ( $type == 'slug' ) {
						$newValue = sanitize_title_with_dashes( $value );
					} else if ( $type == 'textarea' ) {
						$newValue = wp_kses_post( $value );
					} else if ( $type == 'custom_css' ) {
						$newValue = esc_attr( $value );
					} else if ( $type == 'colorpicker' ) {
						$newValue = $this->sanitize_hex_color( $value );
					} else if ( $type == 'image_size' ) {
						$newValue = array();
						foreach ( $value as $k => $v ) {
							$newValue[ $k ] = esc_attr( $v );
						}
					} else if ( $type == 'style' ) {
						$newValue = array();
						foreach ( $value as $k => $v ) {
							if ( $k == 'color' ) {
								$newValue[ $k ] = $this->sanitize_hex_color( $v );
							} else {
								$newValue[ $k ] = $this->sanitize( array( 'type' => 'text' ), $v );
							}
						}
					} else {
						$newValue = sanitize_text_field( $value );
					}

				} else {
					$newValue = array();
					if ( ! empty( $value ) ) {
						if ( is_array( $value ) ) {
							foreach ( $value as $key => $val ) {
								if ( $type == 'style' && $key == 0 ) {
									if ( function_exists( 'sanitize_hex_color' ) ) {
										$newValue = sanitize_hex_color( $val );
									} else {
										$newValue[] = $this->sanitize_hex_color( $val );
									}
								} else {
									$newValue[] = sanitize_text_field( $val );
								}
							}
						} else {
							$newValue[] = sanitize_text_field( $value );
						}
					}
				}
			}

			return $newValue;
		}

		function sanitize_hex_color( $color ) {
			if ( function_exists( 'sanitize_hex_color' ) ) {
				return sanitize_hex_color( $color );
			} else {
				if ( '' === $color ) {
					return '';
				}

				// 3 or 6 hex digits, or the empty string.
				if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
					return $color;
				}
			}
		}

		function hex2rgb( $hex, $less = null ) {
			$hex = str_replace( "#", "", $hex );

			if ( strlen( $hex ) == 3 ) {
				$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
				$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
				$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
			} else {
				$r = hexdec( substr( $hex, 0, 2 ) );
				$g = hexdec( substr( $hex, 2, 2 ) );
				$b = hexdec( substr( $hex, 4, 2 ) );
			}
			if ( $less ) {
				$lR = $r - $less;
				$lG = $g - $less;
				$lB = $b - $less;
				$r  = $lR < 0 ? 0 : ( $lR > 255 ? 255 : $lR );
				$g  = $lG < 0 ? 0 : ( $lG > 255 ? 255 : $lG );
				$b  = $lB < 0 ? 0 : ( $lB > 255 ? 255 : $lB );
			}
			$rgb    = array( $r, $g, $b );
			$output = 'rgb(' . implode( ",", $rgb ) . ')';

			//return implode(",", $rgb); // returns the rgb values separated by commas
			return $output; // returns an array with the rgb values
		}

		function tptStyleGenerator( $scID, $data, $layout ) {
			global $rtTPT;
			$css = null;
			$css .= "<style type='text/css' media='all'>";
			if($layout === "layout_06"){
				$settings = get_option( $rtTPT->options['settings'] );
				$cBgColor = ( ! empty( $settings['container_bg_color'] ) ? $settings['container_bg_color'] : null );
				if($cBgColor){
					$css         .= "#tpt-table-{$scID} .layout_06{";
					$css         .= "background : {$cBgColor}";
					$css         .= "}";
				}
			}
			if ( ! empty( $data ) ) {
				foreach ( $data as $index => $colData ) {
					$generalData = ! empty( $colData['general']['style'] ) ? $colData['general']['style'] : null;
					parse_str( $generalData, $gData );
					$mainColor = ! empty( $gData['main_color'] ) ? $gData['main_color'] : null;
					if ( $mainColor ) {
						$mainRgb = $rtTPT->hex2rgb( $mainColor );
						if ( $layout == 'layout_03' ) {
							$css         .= "#tpt-table-{$scID} .layout_03 .tpt-col-wrap-{$index} .tpt-col .tpt-header .tpt-header-top, 
								#tpt-table-{$scID} .layout_03 .tpt-col-wrap-{$index} .tpt-col .tpt-footer .tpt-footer-btn{";
							$css         .= "background : {$mainRgb}";
							$css         .= "}";
							$css         .= "#tpt-table-{$scID} .layout_03 .tpt-col-wrap-{$index} .tpt-col .tpt-header .tpt-header-bottom {";
							$lessMainRgb = $rtTPT->hex2rgb( $mainColor, 35 );
							$css         .= "background : {$lessMainRgb}";
							$css         .= "}";
						} else if ( $layout == 'layout_06' ) {
							$lessMainRgb = $rtTPT->hex2rgb( $mainColor, 35 );
							$css         .= "#tpt-table-{$scID} .layout_06 .tpt-col-wrap-{$index}.tpt-col-wrap:hover .tpt-col,
									#tpt-table-{$scID} .layout_06 .tpt-col-wrap-{$index} .tpt-footer{";
							$css         .= "background : {$mainRgb};";
							$css         .= "background : -webkit-linear-gradient(left top, {$mainRgb}, {$lessMainRgb});";
							$css         .= "background : -o-linear-gradient(top right, {$mainRgb}, {$lessMainRgb});";
							$css         .= "background : -moz-linear-gradient(top right, {$mainRgb}, {$lessMainRgb});";
							$css         .= "background : linear-gradient(to top right, {$mainRgb}, {$lessMainRgb});";
							$css         .= "}";
							$css         .= "#tpt-table-{$scID} .layout_06 .tpt-col-wrap:hover .tpt-footer{";
							$css         .= "background : #fff;";
							$css         .= "}";
							$css         .= "#tpt-table-{$scID} .layout_06 .tpt-col-wrap-{$index}:hover .tpt-footer .tpt-footer-btn:hover{";
							$css         .= "color : {$mainRgb};";
							$css         .= "}";

						} else if ( $layout == 'layout_07' || $layout == 'layout_09' ) {
							if ( $layout == 'layout_07' ) {
								$mMainRgb = $rtTPT->hex2rgb( $mainColor, - 40 );
								$css      .= "#tpt-table-{$scID} .{$layout} .tpt-col-wrap-{$index} .tpt-col-inner{";
								$css      .= "border-color : {$mMainRgb};";
								$css      .= "}";
							}
							$lessMainRgb  = $rtTPT->hex2rgb( $mainColor, 35 );
							$css          .= "#tpt-table-{$scID} .{$layout} .tpt-col-wrap-{$index} .tpt-col-inner {";
							$css          .= "background : {$mainRgb};";
							$css          .= "background : -webkit-linear-gradient(left top, {$mainRgb}, {$lessMainRgb});";
							$css          .= "background : -o-linear-gradient(top right, {$mainRgb}, {$lessMainRgb});";
							$css          .= "background : -moz-linear-gradient(top right, {$mainRgb}, {$lessMainRgb});";
							$css          .= "background : linear-gradient(to top right, {$mainRgb}, {$lessMainRgb});";
							$css          .= "}";
							$hMainRgb     = $rtTPT->hex2rgb( $mainColor, - 25 );
							$MoreHMainRgb = $rtTPT->hex2rgb( $mainColor, - 45 );
							$css          .= "#tpt-table-{$scID} .{$layout} .tpt-col-wrap-{$index} .tpt-col-inner:hover {";
							$css          .= "background : {$hMainRgb};";
							$css          .= "background : -webkit-linear-gradient(left top, {$hMainRgb}, {$MoreHMainRgb});";
							$css          .= "background : -o-linear-gradient(top right, {$hMainRgb}, {$MoreHMainRgb});";
							$css          .= "background : -moz-linear-gradient(top right, {$hMainRgb}, {$MoreHMainRgb});";
							$css          .= "background : linear-gradient(to top right, {$hMainRgb}, {$MoreHMainRgb});";
							$css          .= "}";
							if ( $layout == 'layout_09' ) {
								$css .= "#tpt-table-{$scID} .{$layout} .tpt-col-wrap-{$index} .tpt-header .tpt-header-top .tpt-title:before,
								        #tpt-table-{$scID} .{$layout} .tpt-col-wrap-{$index} .tpt-header .tpt-header-top .tpt-title:after{";
								$css .= "background-color : {$mainColor};";
								$css .= "}";
							}
						}
					}
				}
			}
			$css .= "</style>";

			return $css;
		}
	}
endif;