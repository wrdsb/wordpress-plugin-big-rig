<?php
defined( 'ABSPATH' ) or die();

/**
 * Main class for Big Rig
 */
class BigRig {

const NAME = "Big Rig";

/**
 * For singleton
 */
private static $instance;

/**
 * Array with all instantiated gauges
 */
private $instantiatedGauges;

function __construct() {
	$this->init();
} // construct

public function init() {
	$this->setup_variables();
	$this->load_gauges();

	if ( is_admin() ) {
		$this->add_admin_actions();
	}
} // init

/**
 * Setup variables and things
 */
public function setup_variables() {
}

private function add_admin_actions() {
        add_action('network_admin_menu', array($this, 'big_rig_gauges_menu'));
}

public function load_gauges() {
	$gaugesDir = BIG_RIG_PATH . "gauges/";

	$gaugesFiles = array(
		$gaugesDir . "BigRigSitesGauge.php",
		$gaugesDir . "BigRigPostsGauge.php",
		$gaugesDir . "BigRigPagesGauge.php",
		$gaugesDir . "BigRigEventsGauge.php"
	);

	include_once $gaugesDir . "BigRigGauge.php";

	// Array with slug of gauges to instantiate
	// Slug of gauge must also be the name of the gauge class
	$arrGaugesToInstantiate = array();

	foreach ( $gaugesFiles as $oneGaugeFile ) {
		$basename_no_suffix = basename( $oneGaugeFile, ".php" );
		include_once $oneGaugeFile;
		$arrGaugesToInstantiate[] = $basename_no_suffix;
	}

	// Instantiate each logger
	foreach ( $arrGaugesToInstantiate as $oneGaugeName ) {
		if ( ! class_exists( $oneGaugeName ) ) {
			continue;
		}
		$gaugeInstance = new $oneGaugeName( $this );
		if ( ! is_subclass_of( $gaugeInstance, "BigRigGauge" ) && ! is_a( $gaugeInstance, "BigRigGauge" ) ) {
			continue;
		}
		$gaugeInstance->loaded();

		$gaugeInfo = $gaugeInstance->getInfo();

		// Add gauge to array of gauges
		$this->instantiatedGauges[$gaugeInstance->slug] = array(
			"name" => $gaugeInfo["name"],
			"instance" => $gaugeInstance,
		);
	}
}

/**
 * Get singleton intance
 * @return BigRig instance
 */
public static function get_instance() {
	if ( ! isset( self::$instance ) ) {
		self::$instance = new BigRig();
	}
	return self::$instance;
}

public function big_rig_gauges_menu() {
        add_menu_page('Business/Runtime Intelligence Gathering','BIG RIG','manage_options','big-rig',array($this, 'big_rig_gauges_page'));
}

public function big_rig_gauges_page() {
	$network_post_counts = BigRigPostsGauge::get_network_post_counts();
	$network_page_counts = BigRigPagesGauge::get_network_page_counts();
	$network_event_counts = BigRigEventsGauge::get_network_event_counts();
        echo '<h1>Big Rig Gauges</h1>';
	echo '<h2>Posts</h2>';
	echo '<p><strong>Total Published:</strong> ' . $network_post_counts['publish'] . '</p>';
	echo '<p><strong>Total Future:</strong> ' . $network_post_counts['future'] . '</p>';
	echo '<p><strong>Total Draft:</strong> ' . $network_post_counts['draft'] . '</p>';
	echo '<p><strong>Total Pending:</strong> ' . $network_post_counts['pending'] . '</p>';
	echo '<p><strong>Total Private:</strong> ' . $network_post_counts['private'] . '</p>';
	echo '<p><strong>Total Trash:</strong> ' . $network_post_counts['trash'] . '</p>';
	echo '<h2>Pages</h2>';
	echo '<p><strong>Total Published:</strong> ' . $network_page_counts['publish'] . '</p>';
	echo '<p><strong>Total Future:</strong> ' . $network_page_counts['future'] . '</p>';
	echo '<p><strong>Total Draft:</strong> ' . $network_page_counts['draft'] . '</p>';
	echo '<p><strong>Total Pending:</strong> ' . $network_page_counts['pending'] . '</p>';
	echo '<p><strong>Total Private:</strong> ' . $network_page_counts['private'] . '</p>';
	echo '<p><strong>Total Trash:</strong> ' . $network_page_counts['trash'] . '</p>';
	echo '<h2>Events</h2>';
	echo '<p><strong>Total Published:</strong> ' . $network_event_counts['publish'] . '</p>';
	echo '<p><strong>Total Future:</strong> ' . $network_event_counts['future'] . '</p>';
	echo '<p><strong>Total Draft:</strong> ' . $network_event_counts['draft'] . '</p>';
	echo '<p><strong>Total Pending:</strong> ' . $network_event_counts['pending'] . '</p>';
	echo '<p><strong>Total Private:</strong> ' . $network_event_counts['private'] . '</p>';
	echo '<p><strong>Total Trash:</strong> ' . $network_event_counts['trash'] . '</p>';
}

} // end class

