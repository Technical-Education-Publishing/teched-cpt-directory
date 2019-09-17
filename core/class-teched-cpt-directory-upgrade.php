<?php
/**
 * Handles plugin upgrades.
 *
 * @since {{VERSION}}
 */

defined( 'ABSPATH' ) || die();

/**
 * Class TechEd_CPT_Directory_Upgrade
 *
 * Handles plugin upgrades.
 *
 * @since {{VERSION}}
 */
class TechEd_CPT_Directory_Upgrade {

	/**
	 * TechEd_CPT_Directory_Upgrade constructor.
	 *
	 * @since {{VERSION}}
	 *
	 * @return bool True if needs to upgrade, false if does not.
	 */
	function __construct() {

		add_action( 'admin_init', array( $this, 'check_upgrades' ) );

		if ( isset( $_GET['teched_cpt_directory_upgrade'] ) ) {

			add_action( 'admin_init', array( $this, 'do_upgrades' ) );
        }
        
        if ( isset( $_GET['teched_cpt_directory_upgraded'] ) ) {
            add_action( 'admin_init', array( $this, 'show_upgraded_message' ) );
        }

	}

	/**
	 * Checks for upgrades and migrations.
	 *
	 * @since {{VERSION}}
	 * @access private
	 */
	function check_upgrades() {

		$version = get_option( 'teched_cpt_directory_version', 0 );

		if ( version_compare( $version, TechEd_CPT_Directory_VER ) === - 1 ) {
			update_option( 'teched_cpt_directory_version', TechEd_CPT_Directory_VER );
		}

		$last_upgrade = get_option( 'teched_cpt_directory_last_upgrade', 0 );

		foreach ( $this->get_upgrades() as $upgrade_version => $upgrade_callback ) {

			if ( version_compare( $last_upgrade, $upgrade_version ) === - 1 ) {

				add_action( 'admin_notices', array( $this, 'show_upgrade_nag' ) );
				break;
			}
		}
	}

	/**
	 * Runs upgrades.
	 *
	 * @since {{VERSION}}
	 * @access private
	 */
	function do_upgrades() {

		$last_upgrade = get_option( 'teched_cpt_directory_last_upgrade', 0 );

		foreach ( $this->get_upgrades() as $upgrade_version => $upgrade_callback ) {

			if ( version_compare( $last_upgrade, $upgrade_version ) === - 1 ) {

				call_user_func( $upgrade_callback );
				update_option( 'teched_cpt_directory_last_upgrade', $upgrade_version );
			}
		}

		wp_safe_redirect( admin_url( 'index.php?teched_cpt_directory_upgraded=true' ) );
		exit();
	}

	/**
	 * Returns an array of all versions that require an upgrade.
	 *
	 * @since {{VERSION}}
	 * @access private
	 *
	 * @return array
	 */
	function get_upgrades() {

		return array(
			'1.0.0' => array( $this, 'upgrade_1_0_0' ),
		);
	}

	/**
	 * Displays upgrade nag.
	 *
	 * @since {{VERSION}}
	 * @access private
	 */
	function show_upgrade_nag() {
		?>
        <div class="notice notice-warning">
            <p>
				<?php printf( __( '%s needs to upgrade the database. It is strongly recommended you backup your database first.', 'teched-cpt-directory' ), TECHEDCPTDIRECTORY()->plugin_data['Name'] ); ?>
                <a href="<?php echo add_query_arg( 'teched_cpt_directory_upgrade', '1' ); ?>"
                   class="button button-primary">
					<?php _e( 'Upgrade', 'teched-cpt-directory' ); ?>
                </a>
            </p>
        </div>
		<?php
	}

	/**
	 * Displays the upgrade complete message.
	 *
	 * @since {{VERSION}}
	 * @access private
	 */
	function show_upgraded_message() {
		?>
        <div class="notice notice-success">
            <p>
				<?php printf( __( '%s has successfully upgraded!', 'teched-cpt-directory' ), TECHEDCPTDIRECTORY()->plugin_data['Name'] ); ?>
            </p>
        </div>
		<?php
	}

	/**
	 * 1.0.0 upgrade script.
	 *
	 * @since {{VERSION}}
	 * @access private
	 */
	function upgrade_1_0_0() {

		$old_category = ( defined( 'WPBDP_CATEGORY_TAX' ) ) ? WPBDP_CATEGORY_TAX : 'wpbdp_category';
		$old_tag = ( defined( 'WPBDP_TAGS_TAX' ) ) ? WPBDP_TAGS_TAX : 'wpbdp_tag';

		$categories = get_terms( array(
			'taxonomy' => $old_category,
			'hide_empty' => false,
		) );

		$tags = get_terms( array(
			'taxonomy' => $old_tag,
			'hide_empty' => false,
		) );

		// Holds Term IDs that we're going to re-assign
		$new_categories = array();
		$states_categories = array();
		$new_tags = array();

		$all_states = teched_directory_get_state_list();

		foreach ( $categories as $term ) {

			if ( array_key_exists( $term->name, $all_states ) ) {
				$states_categories[] = $term->term_id;
			}
			else {
				$new_categories[] = $term->term_id;
			}

		}

		foreach ( $tags as $term ) {

			if ( array_key_exists( strtoupper( $term->name ), $all_states ) || 
				array_search( $term->name, $all_states ) ) {
				continue;
			}

			$new_tags[] = $term->term_id;

		}

		// Thanks, Scribu for the quick way to convert these!
		$_POST['new_tax'] = 'teched-directory-state';
		Term_Management_Tools::handle_change_tax( $states_categories, $old_category );

		$_POST['new_tax'] = 'teched-directory-category';
		Term_Management_Tools::handle_change_tax( $new_categories, $old_category );

		$_POST['new_tax'] = 'teched-directory-tag';
		Term_Management_Tools::handle_change_tax( $new_tags, $old_tag );

        $directory = new WP_Query( array(
            'post_type' => ( defined( 'WPBDP_POST_TYPE' ) ) ? WPBDP_POST_TYPE : 'wpbdp_listing',
            'posts_per_page' => -1,
		) );

        if ( $directory->have_posts() ) :

			while ( $directory->have_posts() ) : $directory->the_post();

                // Change Post Type
                $error = wp_insert_post( array(
                    'ID' => get_the_ID(),
                    'post_type' => 'teched-directory',
                    'post_content' => get_the_content(),
					'post_title' => get_the_title(),
					'post_status' => 'publish',
					'post_author' => get_the_author_meta( 'ID' ),
					'post_date' => date( 'Y-m-d H:i:s', get_post_time() ),
				), true );
				
				update_post_meta( get_the_ID(), 'directory_name', trim( get_post_meta( get_the_ID(), '_wpbdp[fields][12]', true ) ) );
				update_post_meta( get_the_ID(), 'directory_title', trim( get_post_meta( get_the_ID(), '_wpbdp[fields][13]', true ) ) );

				// Address gets a little more complicated
				$address = get_post_meta( get_the_ID(), '_wpbdp[fields][10]', true );

				// Split into an Array
				$address_array = preg_split( '/\r\n|\r|\n/', $address );

				// Remove empty lines
				$address_array = array_filter( $address_array );

				foreach ( $address_array as $index => $line ) {

					if ( $index > 1 ) {

						$words = explode( ' ', trim( $line ) );

						if ( count( $words ) == 1 ) {

							if ( preg_match( '/[a-z]/i', $line ) ) {
								unset ( $address_array[ $index ] ); // This is just some single word, like putting the State on a separate line. Remove
							}

						}

					}

				}

				// Re-index
				$address_array = array_values( $address_array );

				// This is gross, but I want to be able to easily move the ZIP up if I have to
				foreach ( $address_array as $index => $line ) {

					if ( $index > 1 ) {

						$words = explode( ' ', trim( $line ) );

						if ( count( $words ) == 1 ) {

							if ( ! preg_match( '/[a-z]/i', $line ) ) {
								$address_array[ $index - 1 ] .= ' ' . $line; // Bump the ZIP up one line
								unset( $address_array[ $index ] );
							}

						}

					}

				}

				// Re-index
				$address_array = array_values( $address_array );

				// Save line 1
				update_post_meta( get_the_ID(), 'directory_street_address_1', ( isset( $address_array[0] ) && $address_array[0] ) ? trim( $address_array[0] ) : '' ); 

				// Assume it is on index 1
				$city_state_zip_index = 1;

				// There's a second Street Address Line
				if ( count( $address_array ) > 2 ) {

					update_post_meta( get_the_ID(), 'directory_street_address_2', trim( $address_array[1] ) );

					$city_state_zip_index = 2;

				}

				// Grab the City/State/ZIP line
				$city_state_zip = ( isset( $address_array[ $city_state_zip_index ] ) && $address_array[ $city_state_zip_index ] ) ? $address_array[ $city_state_zip_index ] : '';

				// Store here for later
				$zip = preg_replace( '/[^\d|-]/', '', $city_state_zip );

				// Remove ZIP since we already have that data and will be setting it later
				$city_state_zip = preg_replace( '/\s?[\d|-]*$/', '', $city_state_zip );

				$city_state_array = preg_split( '/\s?,\s?/', $city_state_zip );

				update_post_meta( get_the_ID(), 'directory_city', ( isset( $city_state_array[0] ) && $city_state_array[0] ) ? trim( $city_state_array[0] ) : '' );

				$state = ( isset( $city_state_array[1] ) && $city_state_array[1] ) ? $city_state_array[1] : '';
				$state = trim( $state );

				if ( $state ) {

					// Check if we have the Key or the Value
					if ( array_key_exists( strtoupper( $state ), $all_states ) ) {
						update_post_meta( get_the_ID(), 'directory_state', strtoupper( $state ) );
					}
					elseif ( $key = array_search( $state, $all_states ) ) {

						update_post_meta( get_the_ID(), 'directory_state', $key );

					}

				}

				// The old implementation had a separate field for ZIP for some reason?
				$separate_zip = get_post_meta( get_the_ID(), '_wpbdp[fields][11]', true );
				update_post_meta( get_the_ID(), 'directory_zip', ( $separate_zip ) ? trim( $separate_zip ) : trim( $zip ) );

				update_post_meta( get_the_ID(), 'directory_business_email', trim( get_post_meta( get_the_ID(), '_wpbdp[fields][8]', true ) ) );
				update_post_meta( get_the_ID(), 'directory_phone', trim( get_post_meta( get_the_ID(), '_wpbdp[fields][6]', true ) ) );
				update_post_meta( get_the_ID(), 'directory_fax', trim( get_post_meta( get_the_ID(), '_wpbdp[fields][7]', true ) ) );

				// For some reason, they actually saved this data as serialized
				$website_url = get_post_meta( get_the_ID(), '_wpbdp[fields][5]', true );

				update_post_meta( get_the_ID(), 'directory_website_url', ( is_array( $website_url ) && isset( $website_url[0] ) && $website_url[0] ) ? trim( $website_url[0] ) : '' );
				update_post_meta( get_the_ID(), 'directory_website_text', ( is_array( $website_url ) && isset( $website_url[1] ) && $website_url[1] ) ? trim( $website_url[1] ) : '' );

            endwhile;

            wp_reset_postdata();

        endif;
		
	}
	
}