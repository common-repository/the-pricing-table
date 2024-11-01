<div class='body-row'>
	<div class='row-toolbar'>
        <span class='tpt-edit-row'><span class='dashicons dashicons-edit'></span></span>
        <span class='tpt-move-row'><span class='dashicons dashicons-move'></span></span>
        <span class='tpt-delete-row'><span class='dashicons dashicons-trash'></span></span>
	</div>
    <div class="body-item">
        <?php
        parse_str( $body_item, $data );
        $label = !empty($data['content']) ? $data['content'] : null;
        ?>
        <label class="content"><?php echo $label; ?></label>
        <input type='hidden' name='tpt_data[<?php echo $tpt_id; ?>][body][items][]' value='<?php echo esc_html( $body_item ); ?>' />
    </div>
</div>