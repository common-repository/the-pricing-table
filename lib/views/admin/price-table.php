<div class='tpt-table-col'>
    <div class="tpt-item-general tpt-item-section">
        <div class="section-title rt-clear">
            <div class="section-title-wrap">
                <span class="dashicons dashicons-admin-generic"></span>
                <h3>General</h3>
            </div>
            <div class="col-toolbar">
                <span class="tpt-move-col"><span class="dashicons dashicons-move"></span></span></span>
                <span class="tpt-delete-col"><span class="dashicons dashicons-trash"></span></span>
            </div>
        </div>
        <div class="section-content">
            <div class="tpt-style-wrapper">
                <input type="hidden" name="tpt_data[<?php echo $tpt_id; ?>][general][style]"
                       value="<?php echo esc_attr( $tpt_general_style ); ?>"/>
                <span class="rt-style" data-type="general"><span class="dashicons dashicons-edit"></span> Options</span>
            </div>
        </div>
    </div>
    <div class='tpt-item-header tpt-item-section'>
        <div class="section-title rt-clear">
            <div class="section-title-wrap">
                <span class="dashicons dashicons-welcome-learn-more"></span>
                <h3>Header</h3>
            </div>
            <div class="tpt-style-wrapper">
                <input type="hidden" name="tpt_data[<?php echo $tpt_id; ?>][header][style]"
                       value="<?php echo esc_attr( $tpt_header_style ); ?>"/>
                <span class="rt-style" data-type="header"><span class="dashicons dashicons-edit"></span></span>
            </div>
        </div>
        <div class="section-content">
            <div class='tpth-item'>
                <label>Title</label>
                <input type="text" name="tpt_data[<?php echo $tpt_id; ?>][header][title]"
                       value="<?php echo esc_html( $tpt_header_title ); ?>"/>
            </div>
            <div class="tpth-item">
                <label>Content</label>
                <input type="text" name="tpt_data[<?php echo $tpt_id; ?>][header][content]"
                       value="<?php echo esc_html( $tpt_header_content ); ?>"/>
            </div>
        </div>
    </div>
    <div class="tpt-item-price tpt-item-section">
        <div class="section-title rt-clear">
            <div class="section-title-wrap">
                <span class="dashicons dashicons-tickets-alt"></span>
                <h3>Price</h3>
            </div>
        </div>
        <div class="section-content">
            <div class="tptp-item">
                <label>Price</label>
                <input type="text" name="tpt_data[<?php echo $tpt_id; ?>][price][amount]"
                       value="<?php echo $tpt_price; ?>"/>
            </div>
            <div class="tptp-item">
                <label>Period</label>
                <input type="text" name="tpt_data[<?php echo $tpt_id; ?>][price][period]"
                       value="<?php echo esc_html( $tpt_price_period ); ?>"/>
            </div>
        </div>
    </div>
    <div class='tpt-item-body tpt-item-section'>
        <div class="section-title rt-clear">
            <div class="section-title-wrap">
                <span class="dashicons dashicons-media-spreadsheet"></span>
                <h3>Body</h3>
            </div>
        </div>
        <div class="section-content">
            <div class="row-add-toolbar">
                <span class="tpt-add-row button button-primary"><span
                            class="dashicons dashicons-plus-alt"></span>Add Row</span>
            </div>
            <div class="tpt-body-content">
				<?php
				if ( ! empty( $tpt_body_items ) ) {
					foreach ( $tpt_body_items as $body_item ) {
						include $rtTPT->viewsPath . 'admin/body-item.php';
					}
				}
				?>
            </div>
        </div>
    </div>
    <div class='tpt-item-footer tpt-item-section'>
        <div class="section-title rt-clear">
            <div class="section-title-wrap">
                <span class="dashicons dashicons-marker"></span>
                <h3>Footer</h3>
            </div>
            <div class="tpt-style-wrapper">
                <input type="hidden" name="tpt_data[<?php echo $tpt_id; ?>][footer][style]"
                       value="<?php echo esc_attr( $tpt_footer_style ); ?>"/>
                <span class="rt-style" data-type="footer"><span class="dashicons dashicons-edit"></span></span>
            </div>
        </div>
        <div class="section-content">
            <div class="tptf-item">
                <label>Button text</label>
                <input type="text" name="tpt_data[<?php echo $tpt_id; ?>][footer][text]"
                       value="<?php echo esc_html( $tpt_footer_btn_text ); ?>"/>
            </div>
            <div class="tptf-item">
                <label>Button URL</label>
                <input type="text" name="tpt_data[<?php echo $tpt_id; ?>][footer][url]"
                       value="<?php echo esc_url( $tpt_footer_btn_url ); ?>"/>
            </div>
        </div>
    </div>
</div>