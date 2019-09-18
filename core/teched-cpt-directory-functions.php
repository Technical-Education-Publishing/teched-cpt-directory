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

/**
 * Returns a list of States with the shorthand as the Key
 *
 * @since	{{VERSION}}
 * @return  array  States
 */
function teched_directory_get_state_list() {

	return array(
		'AL' => __( 'Alabama', 'teched-cpt-directory' ),
		'AK' => __( 'Alaska', 'teched-cpt-directory' ),
		'AZ' => __( 'Arizona', 'teched-cpt-directory' ),
		'AR' => __( 'Arkansas', 'teched-cpt-directory' ),
		'CA' => __( 'California', 'teched-cpt-directory' ),
		'CO' => __( 'Colorado', 'teched-cpt-directory' ),
		'CT' => __( 'Connecticut', 'teched-cpt-directory' ),
		'DE' => __( 'Delaware', 'teched-cpt-directory' ),
		'FL' => __( 'Florida', 'teched-cpt-directory' ),
		'GA' => __( 'Georgia', 'teched-cpt-directory' ),
		'HI' => __( 'Hawaii', 'teched-cpt-directory' ),
		'ID' => __( 'Idaho', 'teched-cpt-directory' ),
		'IL' => __( 'Illinois', 'teched-cpt-directory' ),
		'IN' => __( 'Indiana', 'teched-cpt-directory' ),
		'IA' => __( 'Iowa', 'teched-cpt-directory' ),
		'KS' => __( 'Kansas', 'teched-cpt-directory' ),
		'KY' => __( 'Kentucky', 'teched-cpt-directory' ),
		'LA' => __( 'Louisiana', 'teched-cpt-directory' ),
		'ME' => __( 'Maine', 'teched-cpt-directory' ),
		'MD' => __( 'Maryland', 'teched-cpt-directory' ),
		'MA' => __( 'Massachusetts', 'teched-cpt-directory' ),
		'MI' => __( 'Michigan', 'teched-cpt-directory' ),
		'MN' => __( 'Minnesota', 'teched-cpt-directory' ),
		'MS' => __( 'Mississippi', 'teched-cpt-directory' ),
		'MO' => __( 'Missouri', 'teched-cpt-directory' ),
		'MT' => __( 'Montana', 'teched-cpt-directory' ),
		'NE' => __( 'Nebraska', 'teched-cpt-directory' ),
		'NV' => __( 'Nevada', 'teched-cpt-directory' ),
		'NH' => __( 'New Hampshire', 'teched-cpt-directory' ),
		'NJ' => __( 'New Jersey', 'teched-cpt-directory' ),
		'NM' => __( 'New Mexico', 'teched-cpt-directory' ),
		'NY' => __( 'New York', 'teched-cpt-directory' ),
		'NC' => __( 'North Carolina', 'teched-cpt-directory' ),
		'ND' => __( 'North Dakota', 'teched-cpt-directory' ),
		'OH' => __( 'Ohio', 'teched-cpt-directory' ),
		'OK' => __( 'Oklahoma', 'teched-cpt-directory' ),
		'OR' => __( 'Oregon', 'teched-cpt-directory' ),
		'PA' => __( 'Pennsylvania', 'teched-cpt-directory' ),
		'RI' => __( 'Rhode Island', 'teched-cpt-directory' ),
		'SC' => __( 'South Carolina', 'teched-cpt-directory' ),
		'SD' => __( 'South Dakota', 'teched-cpt-directory' ),
		'TN' => __( 'Tennessee', 'teched-cpt-directory' ),
		'TX' => __( 'Texas', 'teched-cpt-directory' ),
		'UT' => __( 'Utah', 'teched-cpt-directory' ),
		'VT' => __( 'Vermont', 'teched-cpt-directory' ),
		'VA' => __( 'Virginia', 'teched-cpt-directory' ),
		'WA' => __( 'Washington', 'teched-cpt-directory' ),
		'DC' => __( 'Washington D.C.', 'teched-cpt-directory' ),
		'WV' => __( 'West Virginia', 'teched-cpt-directory' ),
		'WI' => __( 'Wisconsin', 'teched-cpt-directory' ),
		'WY' => __( 'Wyoming', 'teched-cpt-directory' ),
		'AA' => __( 'Armed Forces Americas', 'teched-cpt-directory' ),
		'AE' => __( 'Armed Forces Europe', 'teched-cpt-directory' ),
		'AP' => __( 'Armed Forces Pacific', 'teched-cpt-directory' ),
		'AS' => __( 'American Samoa', 'teched-cpt-directory' ),
		'VI' => __( 'Virgin Islands', 'teched-cpt-directory' ),
		'PR' => __( 'Puerto Rico', 'teched-cpt-directory' ),
		'PW' => __( 'Palau', 'teched-cpt-directory' ),
	);

}

/**
 * Quick access to plugin field helpers.
 *
 * @since {{VERSION}}
 *
 * @return RBM_FieldHelpers
 */
function teched_directory_fieldhelpers() {
	return TECHEDCPTDIRECTORY()->field_helpers;
}

/**
 * Initializes a field group for automatic saving.
 *
 * @since {{VERSION}}
 *
 * @param $group
 */
function teched_directory_init_field_group( $group ) {
	teched_directory_fieldhelpers()->fields->save->initialize_fields( $group );
}

/**
 * Gets a meta field helpers field.
 *
 * @since {{VERSION}}
 *
 * @param string $name Field name.
 * @param string|int $post_ID Optional post ID.
 * @param mixed $default Default value if none is retrieved.
 * @param array $args
 *
 * @return mixed Field value
 */
function teched_directory_get_field( $name, $post_ID = false, $default = '', $args = array() ) {
    $value = teched_directory_fieldhelpers()->fields->get_meta_field( $name, $post_ID, $args );
    return $value !== false ? $value : $default;
}

/**
 * Gets a option field helpers field.
 *
 * @since {{VERSION}}
 *
 * @param string $name Field name.
 * @param mixed $default Default value if none is retrieved.
 * @param array $args
 *
 * @return mixed Field value
 */
function teched_directory_get_option_field( $name, $default = '', $args = array() ) {
	$value = teched_directory_fieldhelpers()->fields->get_option_field( $name, $args );
	return $value !== false ? $value : $default;
}

/**
 * Outputs a text field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_text( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_text( $args['name'], $args );
}

/**
 * Outputs a password field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_password( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_password( $args['name'], $args );
}

/**
 * Outputs a textarea field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_textarea( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_textarea( $args['name'], $args );
}

/**
 * Outputs a checkbox field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_checkbox( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_checkbox( $args['name'], $args );
}

/**
 * Outputs a toggle field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_toggle( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_toggle( $args['name'], $args );
}

/**
 * Outputs a radio field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_radio( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_radio( $args['name'], $args );
}

/**
 * Outputs a select field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_select( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_select( $args['name'], $args );
}

/**
 * Outputs a number field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_number( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_number( $args['name'], $args );
}

/**
 * Outputs an image field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_media( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_media( $args['name'], $args );
}

/**
 * Outputs a datepicker field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_datepicker( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_datepicker( $args['name'], $args );
}

/**
 * Outputs a timepicker field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_timepicker( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_timepicker( $args['name'], $args );
}

/**
 * Outputs a datetimepicker field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_datetimepicker( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_datetimepicker( $args['name'], $args );
}

/**
 * Outputs a colorpicker field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_colorpicker( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_colorpicker( $args['name'], $args );
}

/**
 * Outputs a list field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_list( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_list( $args['name'], $args );
}

/**
 * Outputs a hidden field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_hidden( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_hidden( $args['name'], $args );
}

/**
 * Outputs a table field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_table( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_table( $args['name'], $args );
}

/**
 * Outputs a HTML field.
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_do_field_html( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_html( $args['name'], $args );
}

/**
 * Outputs a repeater field.
 *
 * @since {{VERSION}}
 *
 * @param mixed $values
 */
function teched_directory_do_field_repeater( $args = array() ) {
	teched_directory_fieldhelpers()->fields->do_field_repeater( $args['name'], $args );
}

/**
 * Outputs a String if a Callback Function does not exist for an Options Page Field
 *
 * @since {{VERSION}}
 *
 * @param array $args
 */
function teched_directory_missing_callback( $args ) {
	
	printf( 
		_x( 'A callback function called "teched_directory_do_field_%s" does not exist.', '%s is the Field Type', 'teched-cpt-directory' ),
		$args['type']
	);
		
}