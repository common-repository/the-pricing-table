<h3 class="popup-title"><span class="dashicons dashicons-welcome-learn-more"></span>Header Style</h3>
<?php
parse_str( $value, $data );
$text_color = ! empty( $data['text_color'] ) ? "#".$data['text_color'] : null;

echo $rtTPT->rtFieldGenerator( array(
	'text_color' => array(
		'label' => __( 'Text color', 'the-pricing-table' ),
		'type'  => 'text',
		'class' => 'rt-color',
		'value' => $text_color
	)
) );
