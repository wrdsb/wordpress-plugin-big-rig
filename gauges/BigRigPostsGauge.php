<?php
defined( 'ABSPATH' ) or die();

class BigRigPostsGauge extends BigRigGauge {

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
		"name" => "BigRigPostsGauge",
		"description" => "Current status data for Posts",
		// Capability required to view this gauge.
		"capability" => "edit_pages",
		"messages" => array(
			// No pre-defined variants
			// when adding messages __() or _x() must be used
		),
	);
	return $arr_info;
}

public static function get_network_post_count_published() {
	$post_counts = self::get_network_post_counts();
	return $post_counts['publish'];
}

public static function get_network_post_counts() {
	$post_counts = array(
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
		$count_posts = wp_count_posts();
		if (isset($count_posts->publish))    { $post_counts['publish']    += $count_posts->publish; }
		if (isset($count_posts->future))     { $post_counts['future']     += $count_posts->future; }
		if (isset($count_posts->draft))      { $post_counts['draft']      += $count_posts->draft; }
		if (isset($count_posts->pending))    { $post_counts['pending']    += $count_posts->pending; }
		if (isset($count_posts->private))    { $post_counts['private']    += $count_posts->private; }
		if (isset($count_posts->trash))      { $post_counts['trash']      += $count_posts->trash; }
		restore_current_blog();
	}
	return $post_counts;
}

} // end class
