<?php
defined( 'ABSPATH' ) or die();

class BigRigEventsGauge extends BigRigGauge {

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
		"name" => "BigRigEventsGauge",
		"description" => "Current status data for Events",
		// Capability required to view this gauge.
		"capability" => "edit_pages",
		"messages" => array(
			// No pre-defined variants
			// when adding messages __() or _x() must be used
		),
	);
	return $arr_info;
}

public static function get_network_event_count_published() {
        $event_counts = self::get_network_event_counts();
        return $event_counts['publish'];
}

public static function get_network_event_counts() {
        $event_counts = array(
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
                $count_events = wp_count_posts('ailec_event');
                if (isset($count_events->publish))    { $event_counts['publish']    += $count_events->publish; }
                if (isset($count_events->future))     { $event_counts['future']     += $count_events->future; }
                if (isset($count_events->draft))      { $event_counts['draft']      += $count_events->draft; }
                if (isset($count_events->pending))    { $event_counts['pending']    += $count_events->pending; }
                if (isset($count_events->private))    { $event_counts['private']    += $count_events->private; }
                if (isset($count_events->trash))      { $event_counts['trash']      += $count_events->trash; }
                restore_current_blog();
        }
        return $event_counts;
}

} // end class
