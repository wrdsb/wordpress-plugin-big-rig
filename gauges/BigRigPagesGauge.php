<?php
defined( 'ABSPATH' ) or die();

class BigRigPagesGauge extends BigRigGauge {

/**
 * Unique slug for this logger
 * Will be saved in DB and used to associate each log row with its logger
 */
public $slug = __CLASS__;

public function loaded() {
}

/**
 * Get array with information about this gauge
 *
 * @return array
 */
function getInfo() {
	$arr_info = array(
		// The gauge slug. Defaults to the class name.
		"slug" => __CLASS__,
		// Use these fields to tell the world what your gauge is used for.
		"name" => "BigRigPagesGauge",
		"description" => "Current status data for Pages",
		// Capability required to view this gauge.
		"capability" => "edit_pages",
		"messages" => array(
			// No pre-defined variants
			// when adding messages __() or _x() must be used
		),
	);
	return $arr_info;
}

public static function get_network_page_count_published() {
	$page_counts = self::get_network_page_counts();
	return $page_counts['publish'];
}

public static function get_network_page_counts() {
        $page_counts = array(
                'publish' => 0,
                'future' => 0,
                'draft' => 0,
                'pending' => 0,
                'private' => 0,
                'trash' => 0,
        );
        $sites = BigRigSitesGauge::get_network_sites();
        foreach( $sites as $site ){
                switch_to_blog( $site['blog_id'] );
                $count_pages = wp_count_posts('page');
                if (isset($count_pages->publish))    { $page_counts['publish']    += $count_pages->publish; }
                if (isset($count_pages->future))     { $page_counts['future']     += $count_pages->future; }
                if (isset($count_pages->draft))      { $page_counts['draft']      += $count_pages->draft; }
                if (isset($count_pages->pending))    { $page_counts['pending']    += $count_pages->pending; }
                if (isset($count_pages->private))    { $page_counts['private']    += $count_pages->private; }
                if (isset($count_pages->trash))      { $page_counts['trash']      += $count_pages->trash; }
                restore_current_blog();
        }
        return $page_counts;
}


} // end class
