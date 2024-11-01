<?php global $rtTPT; ?>

<div class="wrap">
    <div id="upf-icon-edit-pages" class="icon32 icon32-posts-page"><br/></div>
    <h2><?php _e( 'The Price Table Settings', 'the-pricing-table' ); ?></h2>
    <h3><?php _e( 'General settings', 'the-pricing-table' ); ?>
        <a style="margin-left: 15px; font-size: 15px;"
           href="http://demo.radiustheme.com/wordpress/plugins/the-post-grid/"
           target="_blank"><?php _e( 'Documentation', 'the-pricing-table' ) ?></a>
    </h3>

    <div class="rt-setting-wrapper">
        <div class="rt-response"></div>
        <form id="rt-tpt-settings-form">
			<?php
			$html = null;
			$html .= '<div id="settings-tabs" class="rt-tabs rt-tab-container">';
			$html .= '<ul class="tab-nav rt-tab-nav">
								<li><a href="#general-settings">' . __( 'General Settings', 'the-pricing-table' ) . '</a></li>
								<li><a href="#custom-css">' . __( 'Custom Css', 'the-pricing-table' ) . '</a></li>
							  </ul>';

			$html .= '<div id="general-settings" class="rt-tab-content">';
			$html .= $rtTPT->rtFieldGenerator( $rtTPT->rtTPTGeneralSettingsFields() );
			$html .= '</div>';

			$html .= '<div id="custom-css" class="rt-tab-content">';
			$html .= $rtTPT->rtFieldGenerator( $rtTPT->rtTPTSettingsCustomCssFields() );
			$html .= '</div>';


			$html .= '</div>';

			echo $html;
			?>
            <p class="submit-wrap"><input type="submit" name="submit" class="button button-primary rtSaveButton"
                                          value="Save Changes"></p>

			<?php wp_nonce_field( $rtTPT->nonceText(), $rtTPT->nonceId() ); ?>
        </form>
        <div id="rt-response" class="updated"></div>

        <p class="tlp-help-link">
            <a class="button-primary" href="http://demo.radiustheme.com/wordpress/plugins/pricing-table/" target="_blank">
                <?php _e( 'Demo', "the-pricing-table" ); ?>
            </a>
            <a class="button-primary" href="https://www.radiustheme.com/setup-configure-pricing-table-wordpress/" target="_blank">
                <?php _e( 'Documentation', "the-pricing-table" ); ?>
            </a>
        </p>
    </div>
</div>
