<h3 class="popup-title"><span class="dashicons dashicons-media-spreadsheet"></span>Body Style</h3>
<?php
parse_str( $value, $data );
$font_size = ! empty( $data['font_size'] ) ? $data['font_size'] : null;

echo $rtTPT->rtFieldGenerator( array(
	'font_size' => array(
		'label'   => __( 'Font Size', 'the-pricing-table' ),
		'type'    => 'select',
		'blank'   => 'Select one',
		'class'   => 'rt-select2',
		'options' => $rtTPT->getFontList(),
		'value'   => $font_size
	)
) );