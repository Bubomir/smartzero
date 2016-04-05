<div class="frm_paypal_settings">
    <table class="form-table">
        <tr><td>
            <p><label class="frm_left_label"><?php _e('Item Name', 'frmpp') ?></label>
            <input type="text" name="<?php echo $this->get_field_name('paypal_item_name') ?>" id="paypal_item_name" value="<?php echo esc_attr(stripslashes($form_action->post_content['paypal_item_name'])); ?>" class="frm_not_email_subject frm_with_left_label" /></p>
            
            <div class="clear"></div>
            <p class="frm_pp_toggle_new"><label class="frm_left_label"><?php _e('Amount', 'frmpp') ?></label>
            <select name="<?php echo $this->get_field_name('paypal_amount_field') ?>" class="frm_cancelnew" <?php echo $show_amount ? 'style="display:none;"' : ''; ?>>
                <option value=""><?php _e( '&mdash; Select &mdash;' ) ?></option>
                <?php
                $selected = false;
				foreach ( $form_fields as $field ) {
                    if ( $form_action->post_content['paypal_amount_field'] == $field->id ) {
                        $selected = true;
                    }
                    ?>
                    <option value="<?php echo $field->id ?>" <?php selected($form_action->post_content['paypal_amount_field'], $field->id) ?>><?php echo substr(esc_attr(stripslashes($field->name)), 0, 50);
                    unset($field); 
                    ?></option>
                    <?php
                }
                ?>
            </select>
            <input type="text" value="<?php echo $form_action->post_content['paypal_amount'] ?>" name="<?php echo $this->get_field_name('paypal_amount') ?>" class="frm_enternew" <?php echo $show_amount ? '' : 'style="display:none;"'; ?> />
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
                <label for="paypal_type"><input type="checkbox" value="_donations" name="<?php echo $this->get_field_name('paypal_type') ?>" <?php checked($form_action->post_content['paypal_type'], '_donations') ?> id="paypal_type" />
                    <?php _e('Payments made in this form are donations.', 'frmpp') ?>
                </label>
                <?php
                /*
                <label class="frm_left_label"><?php _e('Payment Type', 'frmpp' ) ?></label>
                <select name="<?php echo $this->get_field_name('paypal_type') ?>" id="paypal_type" />
                    <option value="_xclick" <?php selected($form_action->post_content['paypal_type'], '_xclick') ?>><?php _e('Regular Payment', 'frmpp') ?></option>
                    <option value="_donations" <?php selected($form_action->post_content['paypal_type'], '_donations') ?>><?php _e('Donation', 'frmpp') ?></option>
                    <option value="_xclick-subscriptions" <?php selected($form_action->post_content['paypal_type'], '_xclick-subscriptions') ?>><?php _e('Subsciption', 'frmpp') ?></option>
                </select>
                */
                ?>
            </p>
            <div class="clear"></div>
            <p>
                <label class="frm_left_label"><?php _e('Notifications', 'frmpp' ) ?></label>
                <label for="paypal_stop_email"><input type="checkbox" value="1" name="<?php echo $this->get_field_name('paypal_stop_email') ?>" <?php checked($form_action->post_content['paypal_stop_email'], 1) ?> id="paypal_stop_email" />
                    <?php _e('Hold email notifications until payment is received.', 'frmpp') ?>
                    <span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php _e('Stop all emails set up with this form, including the registration email if applicable. Send them when the successful payment notification is received from PayPal.', 'frmpp') ?>" ></span>
                </label>
            </p>
            <div class="clear"></div>

        </td></tr>
    </table>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
$('.frm_single_paypal_settings').on('click', '.frm_toggle_pp_opts', frm_toggle_pp_opts);
});

function frm_toggle_pp_opts(){
    var $link = jQuery(this).closest('.frm_pp_toggle_new');
	$link.find('.frm_enternew, .frm_cancelnew').toggle();
	$link.find('input.frm_enternew, select.frm_cancelnew').val('');
	return false;
}
</script>