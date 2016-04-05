<?php
if(!$new_field)
    return;
    
if ($new_field->type == 'data'){

    if (isset($new_field->field_options['form_select']) && is_numeric($new_field->field_options['form_select'])){
        $frm_entry_meta = new FrmEntryMeta();
        $new_entries = $frm_entry_meta->getAll("it.field_id=". $new_field->field_options['form_select']);
        unset($frm_entry_meta);
    }
        
    $new_field->options = array();
    if (isset($new_entries) && !empty($new_entries)){
        foreach ($new_entries as $ent)
            $new_field->options[$ent->item_id] = $ent->meta_value;
    }
}else if(isset($new_field->field_options['post_field']) and $new_field->field_options['post_field'] == 'post_status'){
    $new_field->options = FrmProFieldsHelper::get_status_options($new_field);
}else{
    $new_field->options = stripslashes_deep(maybe_unserialize($new_field->options));
}
    

if(isset($new_field->field_options['post_field']) and $new_field->field_options['post_field'] == 'post_category'){
    $new_field = (array)$new_field;
    $new_field['value'] = (isset($field) and isset($list_options['hide_opt'][$meta_name])) ? $list_options['hide_opt'][$meta_name] : '';
    $new_field['exclude_cat'] = (isset($new_field->field_options['exclude_cat'])) ? $new_field->field_options['exclude_cat'] : '';
    echo FrmFieldsHelper::dropdown_categories(array('name' => "options[awbr_list][{$list_id}][hide_opt][]", 'id' => "options[awbr_list][{$list_id}][hide_opt]", 'field' => $new_field) );
}else{ ?>
<select name="options[awbr_list][<?php echo $list_id ?>][hide_opt][]">
    <option value=""><?php echo ($new_field->type == 'data') ? 'Anything' : 'Select'; ?></option>
    <?php 
    if($new_field->options){ 
        $temp_field = (array)$new_field;
        foreach($new_field->field_options as $k => $o){
            $temp_field[$k] = $o;
            unset($k);
            unset($o);
        }
        
    foreach ($new_field->options as $opt_key => $opt){  
        $field_val = apply_filters('frm_field_value_saved', $opt, $opt_key, $temp_field); //use VALUE instead of LABEL
        $opt = apply_filters('frm_field_label_seen', $opt, $opt_key, $temp_field);
        
    $selected = (isset($list_options) && (($new_field->type == 'data' && $list_options['hide_opt'][$meta_name] == $opt_key) || $list_options['hide_opt'][$meta_name] == $field_val)) ? ' selected="selected"' : ''; ?>
    <option value="<?php echo ($new_field->type == 'data')? $opt_key : stripslashes(esc_html($field_val)); ?>"<?php echo $selected; ?>><?php echo FrmAppHelper::truncate($opt, 25); ?></option>
    <?php } 
    } ?>
</select>
<?php 
} ?>