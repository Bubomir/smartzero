<?php if(version_compare($frm_version, '1.07.01', '<=')){ ?>
<div id="awbr_settings" class="aweber_settings tabs-panel" style="display:none;">
<?php }

if(isset($error) and !empty($error)){ ?>
<div class="error"><?php echo $error ?></div> 
<?php } ?>
    <table class="form-table">
        <tbody>
        <tr>
            <td><label for="aweber"><input type="checkbox" name="options[aweber]" id="aweber" value="1" <?php checked($values['aweber'], 1); ?> /> <?php _e('Add users who submit this form to an AWeber mailing list', 'formidable') ?></label></td>
        </tr>
<?php
        
        if($values['aweber'] and !empty($values['awbr_list'])){
            $hide_aweber = ($values['aweber']) ? '' : 'style="display:none;"';
            foreach((array)$values['awbr_list'] as $list_id => $list_options){
                if(!is_array($list_options))
                    continue;
                
                try{
                    $list = $account->loadFromUrl("/accounts/{$account->id}/lists/{$list_id}");
                    $list_fields = $list->custom_fields->data['entries'];
                } catch (FrmAWeberException $e) {
                    $error = __('Your AWeber account info is not correct.', 'formidable');
                    $error .= ' <a href="'. admin_url('admin.php?page=formidable-settings') .'">'. __('Update it now.', 'formidable') .'</a>';
                    echo '<tr><td><p>'. $error .'</p></td></tr>';
                }
                
                include(FrmAwbrAppController::path() .'/views/settings/_list_options.php');
                unset($list_fields);
                unset($list_id);
                unset($list_options);
            }
        }
?>
    </tbody>
</table>

<p id="awbr_add_button" class="hide_aweber" style="margin-left:10px;<?php echo $values['aweber'] ? '' : 'display:none;'; ?>">
    <a href="javascript:void(0)" class="button-secondary frm_awbr_add_list">+ <?php _e('Add List', 'formidable') ?></a></td>
</p>

<?php if(version_compare($frm_version, '1.07.01', '<=')){ ?>
</div>
<?php 
    wp_localize_script('formidable', 'frm_js', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'images_url' => FRM_URL .'/images',
        'loading' => __('Loading&hellip;')
    ));
} ?>

<style type="text/css">
.themeRoller .aweber_settings{color:#333;display:block !important;}
<?php if(version_compare($frm_version, '1.07.01', '<=')){ ?>.frm_left_label{clear:both;float:left;width:170px;}<?php } ?>
.awbr_list > td{border-top:1px solid #DFDFDF;}
table .awbr_list:nth-child(2) > td{border:none;}
</style>

<script type="text/javascript">
jQuery(document).ready(function($){
$('#aweber_settings').on('change', 'select[name="awbr_list[]"]', frmAwbrFields);
$('input#aweber').click(function(){
    frm_show_div('hide_aweber',this.checked,1,'.');
    if(this.checked) frmAwbrAddList();
    else $('.frm_awbr_remove').click();
});
$('#aweber_settings').on('click', '.frm_awbr_remove', frmAwbrRemoveList);
$('.frm_awbr_add_list').click(frmAwbrAddList);
$('#aweber_settings').on('click', '.frm_awbr_remove_tag', function(){
    if(jQuery(this).closest('.frm_logic_rows').find('.frm_awbr_logic_row').length==1){
        var c=',.frm_logic_label';
    }else{
        var c='';
    }
    $('#'+$(this).closest('.frm_awbr_logic_row').attr('id')+c).fadeOut(1000, function(){
        $(this).closest('.frm_awbr_logic_row').replaceWith('');
    });
});
});

function frmAwbrFields(id,htmlid){
    var id=jQuery(this).val();
    var htmlid=jQuery(this).attr('id').replace('select_list_', '');
    var div=jQuery(this).closest('.awbr_list').find('.frm_awbr_fields');
    div.empty().append('<img class="frm_awbr_loading_field" src="'+ frm_js.images_url +'/wpspin_light.gif" alt="'+ frm_js.loading +'" style="display:none;"/>');
    jQuery('.frm_awbr_loading_field').fadeIn('slow');
    jQuery.ajax({
        type:"POST",url:ajaxurl,
        data:"action=frm_awbr_match_fields&form_id=<?php echo $values['id'] ?>&list_id="+id,
        success:function(html){jQuery('.frm_awbr_loading_field').replaceWith(html).fadeIn('slow');}
    });
}

function frmAwbrAddList(){
    var len=jQuery('.awbr_list').length+1;
    jQuery('#aweber_settings .form-table tbody').append('<tr class="frm_awbr_loading_list"><td><img src="'+ frm_js.images_url +'/wpspin_light.gif" alt="'+ frm_js.loading +'" style="display:none;"/></td></tr>');
    jQuery('.frm_awbr_loading_list img').fadeIn('slow');
    jQuery.ajax({
        type:"POST",url:ajaxurl,
        data:"action=frm_awbr_add_list&list_id="+len,
        success:function(html){jQuery('.frm_awbr_loading_list').replaceWith(html);jQuery('.aweber_settings').fadeIn('slow');}
    });
}

function frmAwbrRemoveList(){
    var id=jQuery(this).attr('id').replace('remove_list_', '');
    jQuery('.awbr_list_'+id+',#frm_awbr_fields_'+id+',.frm_awbr_fields_'+id).fadeOut(1000, function(){
        jQuery('.awbr_list_'+id+',#frm_awbr_fields_'+id+',.frm_awbr_fields_'+id).replaceWith('');
    });
}

function frmAwbrAddLogicRow(id){
if(jQuery('#frm_logic_row_'+id+' .frm_awbr_logic_row').length)
	var len=1+parseInt(jQuery('#frm_logic_row_'+id+' .frm_awbr_logic_row:last').attr('id').replace('frm_logic_'+id+'_', ''));
else var len=0;
jQuery.ajax({
    type:"POST",url:ajaxurl,
    data:"action=frm_awbr_add_logic_row&form_id=<?php echo $values['id'] ?>&list_id="+id+"&meta_name="+len,
    success:function(html){jQuery('.frm_logic_label').show();jQuery('#frm_logic_row_'+id).append(html);}
});
}

function frmAwbrGetFieldValues(field_id,list_id,row){ 
    if(field_id){
    jQuery.ajax({
        type:"POST",url:ajaxurl,
        data:"action=frm_awbr_get_field_values&form_id=<?php echo $values['id'] ?>&list_id="+list_id+"&field_id="+field_id,
        success:function(msg){jQuery("#frm_show_selected_values_"+list_id+'_'+row).html(msg);} 
    });
    }
}
</script>