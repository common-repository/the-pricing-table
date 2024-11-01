<h3 class="popup-title"><span class="dashicons dashicons-tickets-alt"></span>Price Style</h3>
<?php
parse_str( $value, $data );
$font_size = ! empty( $data['font_size'] ) ? absint( $data['font_size'] ) : null;
$color     = ! empty( $data['color'] ) ? "#".$data['color'] : null;
$bg_color     = ! empty( $data['bg_color'] ) ? "#".$data['bg_color'] : null;

echo $rtTPT->rtFieldGenerator( array(
	'font_size' => array(
		'label'   => __( 'Font Size', 'the-pricing-table' ),
		'type'    => 'select',
		'class'   => 'rt-select2',
		'blank'   => 'Select one',
		'options' => $rtTPT->getFontList(),
		'value'   => $font_size
	),
	'color'     => array(
		'label' => __( 'Color', 'the-pricing-table' ),
		'type'  => 'text',
		'class' => 'rt-color',
		'value' => $color
	),
	'bg_color'  => array(
		'label' => __( 'Background Color', 'the-pricing-table' ),
		'type'  => 'text',
		'class' => 'rt-color',
		'value' => $bg_color
	)
) );