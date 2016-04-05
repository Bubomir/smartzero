<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Payments', 'frmpp') ?></h2>
    
    <?php include(FrmAppHelper::plugin_path() .'/classes/views/shared/errors.php'); ?>
    
    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <div class="inner-sidebar">
        <div id="submitdiv" class="postbox ">
            <h3 class="hndle"><span><?php _e('Entry Actions', 'frmpp') ?></span></h3>
            <div class="inside">
                <div class="submitbox">
            	<div id="major-publishing-actions">
            	    <div id="delete-action">                	    
            	        <a class="submitdelete deletion" href="<?php echo add_query_arg('frm_action', 'destroy') ?>" onclick="return confirm('<?php _e('Are you sure you want to delete that payment?', 'frmpp') ?>');" title="<?php _e('Delete') ?>"><?php _e('Delete') ?></a>
            	    </div>
            	    
            	    <div id="publishing-action">
            	        <a href="<?php echo add_query_arg('frm_action', 'edit') ?>" class="button-primary"><?php _e('Edit') ?></a>
                    </div>
                    <div class="clear"></div>
                </div>
                </div>
            </div>
        </div>
        </div>
        
        <div id="post-body">
        <div id="post-body-content">

            <div class="postbox">
                <div class="handlediv"><br/></div><h3 class="hndle"><span><?php _e('Entry', 'frmpp') ?></span></h3>
                <div class="inside">
                    <table class="form-table"><tbody>
                        <tr valign="top">
                            <th scope="row"><?php _e('Completed', 'frmpp') ?>:</th>
                            <td><?php echo ($payment->completed) ? __('Yes', 'frmpp') : __('No', 'frmpp') ?></td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php _e('User', 'frmpp') ?>:</th>
                            <td><?php echo FrmProFieldsHelper::get_display_name($payment->user_id, 'display_name', array('link' => true)) ?></td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php _e('Entry', 'frmpp') ?>:</th>
                            <td><a href="?page=formidable-entries&amp;action=show&amp;frm_action=show&amp;id=<?php echo $payment->item_id ?>"><?php echo $payment->item_id ?></a></td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php _e('Receipt', 'frmpp') ?>:</th>
                            <td><?php echo $payment->receipt_id ?></td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php _e('Amount', 'frmpp') ?>:</th>
                            <td><?php echo FrmPaymentsHelper::formatted_amount($payment->amount) ?></td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php _e('Date', 'frmpp') ?>:</th>
                            <td><?php echo $payment->begin_date ?></td>
                        </tr>
                        
                        
                        
                        <?php if($payment->meta_value){ 
                            $payment->meta_value = maybe_unserialize($payment->meta_value); 
                        ?>
                        <tr valign="top">
                            <th scope="row"><?php _e('Payment Status Updates', 'frmpp') ?>:</th>
                            <td>
                            
                            <?php foreach($payment->meta_value as $metas){ ?>
                                <table class="widefat" style="border:none;">
                                <?php foreach($metas as $key => $meta){ ?>
                                <tr>
                                    <th><?php echo $key ?></th>
                                    <td><?php echo $meta ?></td>
                                </tr>
                                <?php } ?>
                                </table><br/>
                            <?php } ?>
                            
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody></table>
                </div>
            </div>
        </div>
        </div>
        
    </div>
</div>