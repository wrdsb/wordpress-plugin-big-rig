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

public static function get_network_posts_published() {
	$total_posts = 0;
	$args = array(
		'network_id' => null,
		'public'     => null,
		'archived'   => null,
		'mature'     => null,
		'spam'       => null,
		'deleted'    => null,
		'limit'      => 4000,
		'offset'     => 0,
	);
	$sites = wp_get_sites( $args );
	foreach( $sites as $site ){
		switch_to_blog( $site['blog_id'] );
		$count_posts = wp_count_posts();
		if (isset($count_posts->publish)) { $total_posts += $count_posts->publish; }
		restore_current_blog();
	}
	return $total_posts;
}

public static function get_network_post_count_published() {
	$post_counts = self::get_network_post_counts();
	return $post_counts['publish'];
}

private static function get_network_post_counts() {
	$post_counts = array(
		'publish' => 0,
		'future' => 0,
		'draft' => 0,
		'pending' => 0,
		'private' => 0,
		'trash' => 0,
		'auto-draft' => 0,
		'inherit' => 0
	);
	$args = array(
		'network_id' => null,
		'public'     => null,
		'archived'   => null,
		'mature'     => null,
		'spam'       => null,
		'deleted'    => null,
		'limit'      => 4000,
		'offset'     => 0,
	);
	$sites = wp_get_sites( $args );
	foreach( $sites as $site ){
		switch_to_blog( $site['blog_id'] );
		$count_posts = wp_count_posts();
		if (isset($count_posts->publish)) { $post_counts['publish'] += $count_posts->publish; }
		restore_current_blog();
	}
	return $post_counts;
}

} // end class
