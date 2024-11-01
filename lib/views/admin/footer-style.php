<h3 class="popup-title"><span class="dashicons dashicons-marker"></span>Footer Style</h3>
<?php
parse_str( $value, $data );
$btn_type             = ! empty( $data['btn_type'] ) ? $data['btn_type'] : 'normal';
$btn_link_type        = ! empty( $data['btn_link_type'] ) ? $data['btn_link_type'] : 'same';

echo $rtTPT->rtFieldGenerator( array(
	'btn_type'             => array(
		'label'   => __( 'Button type', 'the-pricing-table' ),
		'type'    => 'select',
		'class'   => 'rt-select2',
		'options' => array( 'normal' => 'Normal', 'ghost' => 'Ghost Button' ),
		'value'   => $btn_type
	),
	'btn_link_type'        => array(
		'label'   => __( 'Button link type', 'the-pricing-table' ),
		'type'    => 'select',
		'class'   => 'rt-select2',
		'options' => array( 'same' => 'Same Window', 'new' => 'New Window' ),
		'value'   => $btn_link_type
	)
) );