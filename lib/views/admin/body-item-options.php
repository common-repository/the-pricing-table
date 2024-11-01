<h3 class="popup-title"><span class="dashicons dashicons-media-spreadsheet"></span>Body Item Options</h3>
<?php
parse_str( $value, $data );
$content       = ! empty( $data['content'] ) ? esc_html($data['content']) : null;

echo $rtTPT->rtFieldGenerator( array(
	'content'    => array(
		'label' => __( 'Content', 'the-pricing-table' ),
		'type'  => 'text',
		'value' => $content
	)
) );

