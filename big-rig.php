<?php
/*
* Plugin Name: WRDSB BIG RIG
* Plugin URI: https://github.com/wrdsb/wordpress-plugin-rig
* Description: Business Intelligence Gathering/Runtime Intelligence Gathering for WordPress
* Author: WRDSB
* Author URI: https://github.com/wrdsb
* Version: 0.0.3
* License: GNU AGPLv3
* GitHub Plugin URI: wrdsb/wordpress-plugin-big-rig
* GitHub Branch: master
*/

add_action('network_admin_menu', 'wrdsb_big_rig_menu');

function wrdsb_big_rig_menu() {
	add_menu_page("Business/Runtime Intelligence Gathering", "BIG RIG", 'manage_options', 'wrdsb-big-rig', 'wrdsb_big_rig_page');
	add_submenu_page('wrdsb-big-rig', 'Active Widgets', 'Active Widgets', 'manage_options', 'wrdsb-big-rig-widgets', 'wrdsb_big_rig_widgets_page');
	add_submenu_page('wrdsb-big-rig', 'Users with Special Roles', 'Special Users', 'manage_options', 'wrdsb-big-rig-specials', 'wrdsb_big_rig_specials_page');
	add_submenu_page('wrdsb-big-rig', 'Provision Mailgun Lists', 'Mailgun Lists', 'manage_options', 'wrdsb-big-rig-mailgun-lists', 'wrdsb_big_rig_mailgun_page');
	add_submenu_page('wrdsb-big-rig', 'Audit User Meta', 'Audit User Meta', 'manage_options', 'wrdsb-big-rig-audit-user-meta', 'wrdsb_big_rig_audit_user_meta_page');
}

function wrdsb_big_rig_page() {
	$total_posts = 0;
	$total_pages = 0;

	$args = array(
		'network_id' => null,
		'public'     => null,
		'archived'   => null,
		'mature'     => null,
		'spam'       => null,
		'deleted'    => null,
		'number'     => 4000,
		'offset'     => 0,
	);
	$sites = get_sites( $args );

	foreach( $sites as $site ) {
		switch_to_blog( $site->blog_id );
		$count_posts = wp_count_posts();
		$total_posts += $count_posts->publish;
		$count_pages = wp_count_posts('page');
		$total_pages += $count_pages->publish;
		restore_current_blog();
	}

	echo "<p><strong>Total Posts (published):</strong> " . $total_posts . "</p>";
	echo "<p><strong>Total Pages (published):</strong> " . $total_pages . "</p>";
}

function wrdsb_big_rig_widgets_page() {
	echo "<h1>Active Widgets</h1>";
	$dee_vigets  = array();

	$args = array(
		'network_id' => null,
		'public'     => null,
		'archived'   => null,
		'mature'     => null,
		'spam'       => null,
		'deleted'    => null,
		'number'     => 4000,
		'offset'     => 0,
	);
	$sites = get_sites( $args );

	foreach( $sites as $site ) {
		switch_to_blog( $site->blog_id );
		$blog_details = get_blog_details(get_current_blog_id());
		$my_slug = str_replace('/','',$blog_details->path);
		$dee_vigets[$my_slug] = get_option('sidebars_widgets');
		restore_current_blog();
	}

	echo "<pre>";
	print_r($dee_vigets);
	echo "</pre>";
}

function wrdsb_big_rig_specials_page() {
	echo "<h1>All Users with Special Roles</h1>";
	$special_users = array();

	$args = array(
		'network_id' => null,
		'public'     => null,
		'archived'   => null,
		'mature'     => null,
		'spam'       => null,
		'deleted'    => null,
		'number'     => 4000,
		'offset'     => 0,
	);
	$sites = get_sites( $args );

	foreach ( $sites as $site ) {
		switch_to_blog( $site->blog_id );

		$special_users[$site->path]['administrator'] = array();
		$special_users[$site->path]['editor']        = array();
		$special_users[$site->path]['author']        = array();
		$special_users[$site->path]['contributor']   = array();

		$blogusers = get_users( array( 'role' => 'administrator' ) );
		foreach ( $blogusers as $user ) {
			$special_users[$site->path]['administrator'][$user->ID] = esc_html( $user->user_email );
		}
		$blogusers = get_users( array( 'role' => 'editor' ) );
		foreach ( $blogusers as $user ) {
			$special_users[$site->path]['editor'][$user->ID] = esc_html( $user->user_email );
		}
		$blogusers = get_users( array( 'role' => 'author' ) );
		foreach ( $blogusers as $user ) {
			$special_users[$site->path]['author'][$user->ID] = esc_html( $user->user_email );
		}
		$blogusers = get_users( array( 'role' => 'contributor' ) );
		foreach ( $blogusers as $user ) {
			$special_users[$site->path]['contributor'][$user->ID] = esc_html( $user->user_email );
		}
		restore_current_blog();
	}
	echo "<pre>";
	print_r($special_users);
	echo "</pre>";
}

function wrdsb_big_rig_audit_user_meta_page() {
	echo "<h1>Audit User Meta</h1>";

	$super_admins = array(
		"manninn",
		"twardud",
		"pentick",
		"cartercs",
		"willstr",
		"christd",
		"greenh",
		"barrate",
		"meiklel",
		"schumajr",
		"admin",
		"wrdsbsuperstar",
		"wpengine"
	);

	$missing_id_numbers = array();
	$key = 'wrdsb_id_number';
	$single = true;

	$args = array(
		'network_id' => null,
		'public'     => null,
		'archived'   => null,
		'mature'     => null,
		'spam'       => null,
		'deleted'    => null,
		'number'     => 4000,
		'offset'     => 0,
	);
	$sites = get_sites( $args );

	foreach ( $sites as $site ) {
		switch_to_blog( $site->blog_id );
		echo "<h2>Switched to Blog #". $site->blog_id ."</h2>";
		echo "<pre>";
		$all_users = get_users();
		foreach ( $all_users as $user ) {
			$user_wrdsb_id_number = get_user_meta($user->ID, $key, $single);
			if ($user_wrdsb_id_number == "") {
				echo $user->ID .", ";
				echo $user->user_login .", ";
				echo $user->user_email ." ";
				if (!in_array($user->user_login, $super_admins)) {
					//wp_delete_user($user->ID, 1);
					echo "deleted.";
					$missing_id_numbers[] = $user->ID;
				}
				echo "\n";
			}
		}
	}
	$missing_id_numbers = array_unique( $missing_id_numbers );
	echo "<h2>". count($missing_id_numbers) ." users to delete from network.</h2>";
	foreach ($missing_id_numbers as $userid) {
		//wpmu_delete_user($userid);
		echo "deleted ". $userid ."\n";
	}
	echo "</pre>";
}

function wrdsb_big_rig_mailgun_page() {
	echo "<h1>Provision Mailgun Lists for Network</h1>";
	echo "<ul>";
	$list_name = "";

	$args = array(
		'network_id' => null,
		'public'     => null,
		'archived'   => null,
		'mature'     => null,
		'spam'       => null,
		'deleted'    => null,
		'number'     => 4000,
		'offset'     => 0,
	);
	$sites = get_sites( $args );

	foreach ( $sites as $site ) {
		$list_name = get_list_name($site);
		echo "<li>". $list_name ."</li>";
	}
	echo "</ul>";
}

function get_list_name($site) {
	$blog_details = get_blog_details($site->blog_id);
	$my_domain = $blog_details->domain;
	echo $my_domain;
	$my_slug = str_replace('/','',$blog_details->path);
	switch ($my_domain) {
		case "www.wrdsb.ca":
			if (empty($my_slug)) {
				return "www@hedwig.wrdsb.ca";
			} else {
				return "www-".$my_slug."@hedwig.wrdsb.ca";
			}
		case "staff.wrdsb.ca":
			if (empty($my_slug)) {
				return "staff@hedwig.wrdsb.ca";
			} else {
				return "staff-".$my_slug."@hedwig.wrdsb.ca";
			}
		case "schools.wrdsb.ca":
			if (empty($my_slug)) {
				return "schools@hedwig.wrdsb.ca";
			} else {
				return $my_slug."@hedwig.wrdsb.ca";
			}
		case "teachers.wrdsb.ca":
			if (empty($my_slug)) {
				return "teachers@hedwig.wrdsb.ca";
			} else {
				return "teachers-".$my_slug."@hedwig.wrdsb.ca";
			}
		case "wcssaa.ca":
			return "wcssaa@hedwig.wrdsb.ca";
		case "wplabs.wrdsb.ca":
			if (empty($my_slug)) {
				return "wplabs@hedwig.wrdsb.ca";
			} else {
				return "wplabs-".$my_slug."@hedwig.wrdsb.ca";
			}
		case "www.stswr.ca":
			return "www@bigbus.stswr.ca";
		default:
			return "no-list@hedwig.wrdsb.ca";
	}
}
