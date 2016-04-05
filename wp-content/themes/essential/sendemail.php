<?php
/**
 * 
 * Simple send mail script 
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */
?>


<?php

    require_once( '../../../wp-load.php' );
    
    $contactmail = get_post_meta( $_POST['postid'], 'contact-mail', true );     // get contact email from post meta    
    $mailto   = isset($contactmail) ? $contactmail : get_option('admin_email'); // if contact email is not set default to WP admin email 
    $name     = ucwords($_POST['name']);
    $subject  = __('Contact from ','essential') .' '. get_bloginfo('name');
    $email    = $_POST['email'];
    $message  = $_POST['message'];


	if( empty($name) ):
	   echo 'no-name';
    	
    elseif( empty($email) ):
        echo 'email_error';
	
    elseif (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", $email)): 
        echo 'email_error';
  	
	elseif( empty($message) ):
		echo 'no-message';

  	else:
        	    
      	// Compose headers
    	$headers = "From: $email \r\n";
    	$headers .= "Reply-To: $email \r\n";
    	$headers .= "X-Mailer: PHP/".phpversion();
    	$sent = wp_mail($mailto, $subject, strip_tags($message), $headers);    // Here we send actual email
	
    endif;
?>