<h3 class="popup-title"><span class="dashicons dashicons-admin-generic"></span>General Style</h3>
<?php
parse_str( $value, $data );
$main_color  = ! empty( $data['main_color'] ) ? "#".$data['main_color'] : null;
//$highlight   = ! empty( $data['highlight'] ) ? 1 : 0;

echo $rtTPT->rtFieldGenerator( array(
	'main_color'  => array(
		'label' => __( 'Main color', 'the-pricing-table' ),
		'type'  => 'text',
		'class' => 'rt-color',
		'value' => $main_color
	),
//	'highlight'   => array(
//		'label'  => __( 'Highlight Column?', 'the-pricing-table' ),
//		'type'   => 'checkbox',
//		'option' => 'Enable',
//		'value'  => $highlight
//	)
) );