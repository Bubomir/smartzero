<tr class="hide_aweber awbr_list awbr_list_<?php echo $list_id ?>" <?php echo $hide_aweber; ?>>
    <td>
        <a class="frm_awbr_remove alignright frm_email_actions feature-filter" id="remove_list_<?php echo $list_id ?>" href="javascript:void(0)"><img src="<?php echo method_exists('FrmAppHelper', 'plugin_url') ? FrmAppHelper::plugin_url() : FRM_URL; ?>/images/trash.png" alt="<?php _e('Remove', 'formidable') ?>" title="<?php _e('Remove', 'formidable') ?>" /></a>  
        <p>
        <?php if($lists){ ?>
        <select name="awbr_list[]" id="select_list_<?php echo $list_id ?>">
            <option value="">- <?php _e('Select List', 'formidable') ?> -</option>
            <?php foreach($lists as $list){ ?>
            <option value="<?php echo $list->id ?>" <?php selected($list_id, $list->id) ?>><?php echo $list->name ?></option>
            <?php } ?>
        </select>
        <?php }else{
            _e('No AWeber mailing lists found', 'formidable');
        } ?>
        </p>
<div class="frm_awbr_fields frm_indent_opt">
<?php
if(isset($list_fields))
    include(FrmAwbrAppController::path() .'/views/settings/_match_fields.php');
?>
</div>
    </td>
</tr>