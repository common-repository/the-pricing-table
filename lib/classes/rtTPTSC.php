<?php
if ( ! class_exists( 'rtTPTSC' ) ):

	class rtTPTSC {
		public function __construct() {
			add_shortcode( 'the-pricing-table', array( $this, 'pricing_table_sc' ) );
		}

		function pricing_table_sc( $atts, $content = null ) {
			global $rtTPT;
			$html = null;
			$arg  = array();
			$atts = shortcode_atts( array(
				'id' => null
			), $atts, 'the-pricing-table' );
			$scID = $atts['id'];
			if ( $scID && ! is_null( get_post( $scID ) ) ) {
				$layout = get_post_meta( $scID, 'tpt_layout', true );
				$layout = ( isset( $layout ) ? $layout : 'layout_01' );
				if ( ! in_array( $layout, array_keys( $rtTPT->getLayoutList() ) ) ) {
					$layout = 'layout_01';
				}
				$settings = get_option( $rtTPT->options['settings'] );
				$currency = ! empty( $settings['currency'] ) ? $settings['currency'] : 'USD';
				$position = ! empty( $settings['currency_position'] ) ? $settings['currency_position'] : 'left';
				$thousand_sep = ! empty( $settings['price_thousand_sep'] ) ? $settings['price_thousand_sep'] : ',';
				$num_decimals = ! empty( $settings['price_num_decimals'] ) ? ( absint( $settings['price_num_decimals'] ) > 0 ? absint( $settings['price_num_decimals'] ) : 2 ) : 2;
				$cList = $rtTPT->currency_list();
				$cSymbol = $currency ? $cList[$currency]['symbol'] : null;
				$data      = get_post_meta( $scID, 'tpt_data', true );
				$col_count = count( $data );
				$html .= $rtTPT->tptStyleGenerator($scID, $data, $layout);
				$html  .= "<div id='tpt-table-{$scID}' class='tpt-table'>";
					$html .= "<div class='tpt-clearfix tpt-table-{$col_count}cols {$layout} tpt-row'>";
						if ( ! empty( $data ) && $col_count > 0 ) {
							foreach ( $data as $index => $col ) {
								$headerData = ! empty( $col['general']['style'] ) ? $col['general']['style'] : null;
								parse_str( $headerData, $hData );
								$highlightClass = ! empty( $hData['highlight'] ) ? "tpt-col-highlight" : null;

								/* header */
								$headerHtml = null;
								$headerHtml .= ! empty( $col['header']['title'] ) ? "<h3 class='tpt-title'>{$col['header']['title']}</h3>" : null;
								$headerHtml .= ! empty( $col['header']['content'] ) ? "<div class='h-content'>{$col['header']['content']}</div>" : null;

								/* Header coin */
								$amount = ! empty( $col['price']['amount'] ) ? $col['price']['amount'] : 0;
								$period = ! empty( $col['price']['period'] ) ? $col['price']['period'] : null;
								$headerBottomHtml = null;
								$headerBottomHtml .= $position == 'left' ? "<span class='currency'>{$cSymbol}</span><span class='amount'>{$amount}</span>" : "<span class='amount'>{$amount}</span><span class='currency'>{$cSymbol}</span>";
								$headerBottomHtml .= $period ? "<small class='period'>{$period}</small>" : null;
								/* body */
								$bodyItems = ! empty( $col['body']['items'] ) ? $col['body']['items'] : array();
								$itemHtml  = null;
								if ( ! empty( $bodyItems ) ) {
									foreach ( $bodyItems as $itemData ) {
										parse_str( $itemData, $iData );
										$icon     = ! empty( $iData['icon'] ) ? "<span class='tpt-body-item-icon'><i class='fa {$iData['icon']}'></i></span>" : null;
										if(!empty($iData['content'])){
											$content  = $icon . "<div class='tpt-body-item-content'>{$iData['content']}</div>";
											$itemHtml .= "<li class='tpt-body-item'>{$content}</li>";
										}
									}
									$itemHtml = ! empty( $itemHtml ) ? "<ul class='tpt-body-items'>{$itemHtml}</ul>" : null;
								}
								/* footer */
								$footerHtml    = $btnClass = null;
								$footerBtnText = ! empty( $col['footer']['text'] ) ? $col['footer']['text'] : null;
								$footerStyle = ! empty( $col['footer']['style'] ) ? $col['footer']['style'] : null;
								parse_str( $footerStyle, $fStyle );
								$btnClass .= !empty($fStyle['btn_type']) && $fStyle['btn_type'] == 'ghost' ? ' ghost-btn' : null;
								$btnTarget = !empty($fStyle['btn_link_type']) && $fStyle['btn_link_type'] == 'new' ? ' target="_blank"' : null;
								if ( $footerBtnText ) {
									$footerBtnUrl = ! empty( $col['footer']['url'] ) ? esc_url_raw($col['footer']['url']) : null;
									$footerHtml   = "<a class='tpt-footer-btn {$btnClass}' href='{$footerBtnUrl}' {$btnTarget}>{$footerBtnText}</a>";
								}
								/* Content builder */
								$html .= "<div class='tpt-col-wrap tpt-col-wrap-{$index} {$highlightClass}' data-col-index='{$index}'>";
									$html .= "<div class='tpt-col'>";
										$html .= "<div class='tpt-col-inner'>";
											ob_start();
											include $rtTPT->viewsPath . "layout/{$layout}.php";
											$html .= ob_get_contents();
											ob_end_clean();
										$html .= "</div>";
									$html .= "</div>";
								$html .= "</div>";
							}
						}
					$html .= "</div>";
				$html .= "</div>";

				return $html;
			}
		}
	}

endif;