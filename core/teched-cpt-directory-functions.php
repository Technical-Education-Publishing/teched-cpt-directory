<?php
/**
 * Provides helper functions.
 *
 * @since	  {{VERSION}}
 *
 * @package	TechEd_CPT_Directory
 * @subpackage TechEd_CPT_Directory/core
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Returns the main plugin object
 *
 * @since		{{VERSION}}
 *
 * @return		TechEd_CPT_Directory
 */
function TECHEDCPTDIRECTORY() {
	return TechEd_CPT_Directory::instance();
}