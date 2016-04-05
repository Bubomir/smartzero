<?php if(version_compare($frm_version, '1.07.01', '<=')){ ?>
<div class="aweber_settings tabs-panel" style="display:none;">
<?php }

if(isset($message) and !empty($message)){ ?>
<div id="message" class="updated fade" style="padding:5px;"><?php echo $message ?></div>    
<?php 
}

if(isset($error) and !empty($error)){ ?>
<div class="error"><?php echo $error ?></div> 
<?php } ?>

    <table class="form-table">
        <tr class="form-field">
            <td width="170px"><label><?php _e('Authorization ID', 'formidable') ?></label></td>
            <td><input type="text" name="frm_awbr_oauth_id" id="frm_awbr_oauth_id" value="<?php echo $frm_awbr_settings->settings->oauth_id ?>" class="frm_long_input" /><br/>
        	</td>
        </tr>
    </table>
    
    <br/>
    <h4><?php _e('To setup AWeber:', 'formidable') ?></h4>
    <ol>
        <li><?php _e('Before you can use AWeber with your forms, you first need to authorize it to access your AWeber account.', 'formidable') ?> <a href="https://auth.aweber.com/1.0/oauth/authorize_app/17608414" target="_blank"><?php _e('Click here to get your AWeber authorization ID.', 'formidable') ?></a></li>
        <li><?php _e('After you login to AWeber, you will be given an authorization ID. Copy it and paste it below.', 'formidable') ?></li>
        <li><?php _e('Click Update. If you see a success message you are ready to setup your forms to create AWeber contacts.', 'formidable') ?></li>
    </ol>
<?php if(version_compare($frm_version, '1.07.01', '<=')){ ?>    
</div>
<?php } ?>