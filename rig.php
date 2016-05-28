<?php
/*
* Plugin Name: WRDSB RIG
* Plugin URI: https://github.com/wrdsb/wordpress-plugin-rig
* Description: Runtime Intelligence Gathering for WordPress
* Author: WRDSB
* Author URI: https://github.com/wrdsb
* Version: 0.0.1
* License: GNU AGPLv3
* GitHub Plugin URI: wrdsb/wordpress-plugin-rig
* GitHub Branch: master
*/

add_action('network_admin_menu', 'wrdsb_rig_menu');

function wrdsb_rig_menu() {
	add_menu_page("Runtime Intelligence Gathering","RIG",'capability','wrdsb-rig','wrdsb_rig_page');
}

function wrdsb_rig_page() {
	$total_posts = 0;
	$total_pages = 0; 

	$args = array(
		'network_id' => null,
		'public'     => null,
		'archived'   => null,
		'mature'     => null,
		'spam'       => null,
		'deleted'    => null,
		'limit'      => 100,
		'offset'     => 0,
	);
	$sites = wp_get_sites( $args );

	foreach( $sites as $site ){
		switch_to_blog( $site['blog_id'] );
		$count_posts = wp_count_posts();
		$total_posts += $count_posts->publish;
		$count_pages = wp_count_posts('page');
		$total_pages += $count_pages->publish;
		restore_current_blog();
	}

	echo "<p><strong>Total Posts (published):</strong> " . $total_posts . "</p>";
	echo "<p><strong>Total Pages (published):</strong> " . $total_pages . "</p>";
}
