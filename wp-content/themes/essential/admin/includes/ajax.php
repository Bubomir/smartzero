<?php

class prospekt_ajax
{
    /**
     * Search posts (in the Placement Rules area)
     * @param array $options 
     * @return string A JSON results object
     */
    public function search_posts($options)
    {
        global $wpdb;

        $sql = $wpdb->prepare("
        SELECT ID, post_type, post_title
        FROM $wpdb->posts
        WHERE
            post_status IN ('publish', 'private') AND
            post_type NOT IN ('cfs', 'attachment', 'revision', 'nav_menu_item') AND
            post_title LIKE '%s'
        ORDER BY post_type, post_title
        LIMIT 10",
        '%'.$options['q'].'%' );

        $results = $wpdb->get_results( $sql );

        $output = array();
        foreach ($results as $result)
        {
            $output[] = array(
                'id' => $result->ID,
                'text' => "($result->post_type) $result->post_title"
            );
        }
        return json_encode($output);
    }




    
}
