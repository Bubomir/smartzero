<?php

if(!is_array($list_fields))
    $list_fields = array();
    
$list_fields[] = array('name' => __('Email', 'formidable'), 'id' => 'email');
$list_fields[] = array('name' => __('Full Name', 'formidable'), 'id' => 'name');

$list_fields = array_reverse($list_fields);


foreach($list_fields as $list_field){ 
    if(is_numeric($list_field['id']))
        $list_field['id'] = $list_field['name'];
?>

<p><label class="frm_left_label"><?php echo $list_field['name']; ?></label>
        <select name="options[awbr_list][<?php echo $list_id ?>][fields][<?php echo $list_field['id'] ?>]">
            <option value="">- <?php _e('Select Field', 'formidable') ?> -</option>
            <?php foreach($form_fields as $form_field){ 
                $selected = (isset($list_options['fields'][$list_field['id'] ]) and $list_options['fields'][$list_field['id']] == $form_field->id) ? ' selected="selected"' : '';
            ?>
            <option value="<?php echo $form_field->id ?>" <?php echo $selected ?>><?php echo stripslashes($form_field->name) ?></option>
            <?php } ?>
        </select>
</p>
<?php } ?>
<div><label class="frm_logic_label" <?php if(!isset($list_options['hide_field']) or empty($list_options['hide_field'])){ echo 'style="display:none;"'; } ?>><?php _e('Conditional Logic', 'formidable') ?></label>
    <div class="frm_logic_rows tagchecklist">
        <div id="frm_logic_row_<?php echo $list_id ?>">
<?php

if(isset($list_options['hide_field']) and !empty($list_options['hide_field'])){ 
    foreach((array)$list_options['hide_field'] as $meta_name => $hide_field){
        include(FrmAwbrAppController::path() .'/views/settings/_logic_row.php');
        unset($meta_name);
        unset($hide_field);
    }
}
?>
        </div>
    </div>
    <p><a class="button" href="javascript:frmAwbrAddLogicRow('<?php echo $list_id ?>');">+ <?php _e('Add Conditional Logic', 'formidable') ?></a></p>
</div>