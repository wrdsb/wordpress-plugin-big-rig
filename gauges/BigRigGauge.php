<?php
defined( 'ABSPATH' ) or die();

class BigRigGauge {

/**
 * Unique slug for this logger
 * Will be saved in DB and used to associate each log row with its logger
 */
public $slug = __CLASS__;

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
		"name" => "BigRigGauge",
		"description" => "The built in gauge for Big Rig",
		// Capability required to view this gauge.
		"capability" => "edit_pages",
		"messages" => array(
			// No pre-defined variants
			// when adding messages __() or _x() must be used
		),
	);
	return $arr_info;
}

/**
 * Return single array entry from the array in getInfo()
 * Returns the value of the key if value exists, or null
 *
 * @return Mixed
 */
function getInfoValueByKey( $key ) {
	$arr_info = $this->getInfo();
	return isset( $arr_info[ $key ] ) ? $arr_info[ $key ] : null;
}

/**
 * Returns the capability required to read this gauge
 *
 * @return $string capability
 */
public function getCapability() {
	$arr_info = $this->getInfo();
	$capability = "manage_options";
	if ( ! empty( $arr_info["capability"] ) ) {
		$capability = $arr_info["capability"];
	}
	return $capability;
}

public function loaded() {
}

} // end class
