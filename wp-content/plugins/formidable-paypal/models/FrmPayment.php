<?php

class FrmPayment {
    
    function create($values){
        global $wpdb;

        $new_values = array();
        $new_values['receipt_id'] = isset($values['receipt_id']) ? $values['receipt_id'] : '';
        $new_values['item_id'] =  isset($values['item_id']) ? (int)$values['item_id'] : '';
        $new_values['amount'] = isset($values['amount']) ? $values['amount'] : '';
        $new_values['completed'] = isset($values['completed']) ? 1 : 0;
        $new_values['begin_date'] = isset($values['begin_date']) ? $values['begin_date'] : '';
        $new_values['paysys'] = isset($values['paysys']) ? $values['paysys'] : '';
        $new_values['created_at'] = current_time('mysql', 1);
        
        $query_results = $wpdb->insert( $wpdb->prefix .'frm_payments', $new_values );

        return $wpdb->insert_id;
    }
    
    function update($id, $values){
        global $wpdb;

        $new_values = array();

        $new_values['receipt_id'] = isset($values['receipt_id']) ? $values['receipt_id'] : '';
        
        if(isset($values['item_id']))
            $new_values['item_id'] =  (int)$values['item_id'];
            
        if(isset($values['paysys']))
            $new_values['paysys'] =  $values['paysys'];
            
        $new_values['amount'] = isset($values['amount']) ? $values['amount'] : '';
        $new_values['completed'] = isset($values['completed']) ? 1 : 0;
        $new_values['begin_date'] = isset($values['begin_date']) ? $values['begin_date'] : '';
        //$new_values['updated_at'] = current_time('mysql', 1);
        
        return $wpdb->update( $wpdb->prefix .'frm_payments', $new_values, compact('id') );
    }
   
    function &destroy( $id ){
        if(!current_user_can('administrator')){
            $frm_settings = FrmPaymentsHelper::get_settings();
            wp_die($frm_settings->admin_permission);
        }
            
        global $wpdb;
        $id = (int)$id;

        do_action('frm_before_destroy_payment', $id);

        $result = $wpdb->query("DELETE FROM {$wpdb->prefix}frm_payments WHERE id=". (int)$id);
        return $result;
    }

}