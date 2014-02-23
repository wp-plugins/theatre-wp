<?php
if ( realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME']) )
    exit('Do not access this file directly.');

class TWP_Sponsor {

    public function __construct() {

    }

    /**
     * Get a list of available sponsors.
     *
     * @access public
     * @return array
     */
    public function get_sponsors_titles() {
        $sponsors_query =  get_posts( 'post_type=sponsor&post_status=publish&orderby=title&order=ASC&numberposts=-1' );

        if ( ! $sponsors_query ) {
            return false;
        }

        foreach ( $sponsors_query as $sponsor ) {
            $sponsors[] =  array(
                'id' => $sponsor->ID,
                'title' => __($sponsor->post_title)
            );
        }

        return $sponsors;
    }
}
