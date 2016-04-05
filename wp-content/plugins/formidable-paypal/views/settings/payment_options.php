    <table class="form-table">
        <tr>
            <td><label for="paypal_opt"><input type="checkbox" name="options[paypal]" id="paypal_opt" value="1" <?php checked($values['paypal'], 1); ?> onclick="frm_show_div('hide_paypal',this.checked,1,'.')"/> <?php _e('Send users to PayPal after submitting this form', 'frmpp') ?></label></td>
        </tr>
         
        <tr class="hide_paypal" <?php echo $hide_paypal ?>>
            <td>
            <p><label class="frm_left_label"><?php _e('Item Name', 'frmpp') ?></label>
            <input type="text" name="options[paypal_item_name]" id="paypal_item_name" value="<?php echo esc_attr(stripslashes($values['paypal_item_name'])); ?>" class="frm_not_email_subject frm_with_left_label" /></p>
            
            <div class="clear"></div>
            <p class="frm_pp_toggle_new"><label class="frm_left_label"><?php _e('Amount', 'frmpp') ?></label>
            <select name="options[paypal_amount_field]" class="frm_cancelnew" <?php echo $show_amount ? 'style="display:none;"' : ''; ?>>
                <option value=""><?php _e( '&mdash; Select &mdash;' ) ?></option>
                <?php
                $selected = false;
                if(isset($form_fields) and is_array($form_fields)){
                    foreach($form_fields as $field){ 
                        if($field->type == 'checkbox')
                            continue;
                        
                        if ( $values['paypal_amount_field'] == $field->id ) {
                            $selected = true;
                        }
                    ?>
                    <option value="<?php echo $field->id ?>" <?php selected($values['paypal_amount_field'], $field->id) ?>><?php echo substr(esc_attr(stripslashes($field->name)), 0, 50);
                    unset($field); 
                    ?></option>
                    <?php 
                    }
                }
                ?>
            </select>
            <input type="text" value="<?php echo $values['paypal_amount'] ?>" name="options[paypal_amount]" class="frm_enternew" <?php echo $show_amount ? '' : 'style="display:none;"'; ?> />
            <span class="clear"></span>
            <label class="frm_left_label">&nbsp;</label>
            <a class="hide-if-no-js frm_toggle_pp_opts">
                <span class="frm_enternew" <?php echo $show_amount ? 'style="display:none;"' : ''; ?>><?php _e('Set Amount', 'frmpp'); ?></span>
                <span class="frm_cancelnew" <?php echo $show_amount ? '' : 'style="display:none;"'; ?>><?php _e('Select Field', 'frmpp'); ?></span>
            </a>
            </p>
            <div class="clear"></div>
            <p>
                <label class="frm_left_label"><?php _e('Donations', 'frmpp' ) ?></label>
                <label for="paypal_type"><input type="checkbox" value="_donations" name="options[paypal_type]" <?php checked($values['paypal_type'], '_donations') ?> id="paypal_type" />
                    <?php _e('Payments made in this form are donations.', 'frmpp') ?>
                </label>
            </p>
            <div class="clear"></div>
            <p>
                <label class="frm_left_label"><?php _e('Notifications', 'frmpp' ) ?></label>
                <label for="paypal_stop_email"><input type="checkbox" value="1" name="options[paypal_stop_email]" <?php checked($values['paypal_stop_email'], 1) ?> id="paypal_stop_email" />
                    <?php _e('Hold email notifications until payment is received.', 'frmpp') ?>
                    <span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php _e('Stop all emails set up with this form, including the registration email if applicable. Send them when the successful payment notification is received from PayPal.', 'frmpp') ?>" ></span>
                </label>
            </p>
            <div class="clear"></div>

        <div class="frm_add_remove">
        <p class="frm_add_logic_link" id="logic_link_paypal">
            <a class="frm_add_paypal_logic" data-emailkey="paypal" <?php echo (!isset($values['paypal_list']['hide_field']) || empty($values['paypal_list']['hide_field'])) ? '' : 'style="display:none"'; ?>><?php _e('Use Conditional Logic', 'frmpp') ?></a></p>
        <div id="frm_pay_fields" class="frm_logic_rows" <?php echo (isset($values['paypal_list']['hide_field']) && !empty($values['paypal_list']['hide_field'])) ? '' : ' style="display:none"'; ?>>
            <h4><?php _e('Conditional Logic', 'frmpp') ?></h4>
            <div class="frm_pay_logic_rows">
                <div id="frm_pay_logic_row">
        <?php
        
        if ( isset($values['paypal_list']['hide_field']) && !empty($values['paypal_list']['hide_field']) ) {
            foreach ( (array) $values['paypal_list']['hide_field'] as $meta_name => $hide_field ) {
                FrmPaymentSettingsController::include_logic_row($meta_name, $values['id'], $values['paypal_list']);
                unset($meta_name, $hide_field);
            }
        }
        ?>
                </div>
            </div>
        </div>
        </div>
        </td>
        </tr>
    </table>

<script type="text/javascript">
jQuery(document).ready(function($){
$('#paypal_settings, .paypal_settings').on('click', '.frm_toggle_pp_opts', frm_toggle_pp_opts);
$('#paypal_settings, .paypal_settings').on('click', '.frm_add_paypal_logic', frmPayAddLogicRow);
});

function frmPayAddLogicRow(){
if(jQuery('#frm_pay_logic_row .frm_logic_row_paypal').length)
    var len=1+parseInt(jQuery('#frm_pay_logic_row .frm_logic_row_paypal:last').attr('id').replace('frm_logic_paypal_', ''));
else var len=0;
jQuery.ajax({
    type:"POST",url:ajaxurl,
    data:"action=frm_pay_add_logic_row&form_id=<?php echo $values['id'] ?>&meta_name="+len,
    success:function(html){
        jQuery('#logic_link_paypal .frm_add_paypal_logic').hide();
        jQuery('#frm_pay_fields').show();
        jQuery('#frm_pay_logic_row').append(html);
    }
});
}

function frm_toggle_pp_opts(){
	jQuery(this).closest('.frm_pp_toggle_new').find('.frm_enternew, .frm_cancelnew').toggle();
	jQuery(this).closest('.frm_pp_toggle_new').find('input.frm_enternew, select.frm_cancelnew').val('');
	return false;
}
</script>