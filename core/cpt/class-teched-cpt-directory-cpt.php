<?php
/**
 * Class CPT_TechEd_CPT_Directory
 *
 * Creates the post type.
 *
 * @since {{VERSION}}
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_TechEd_CPT_Directory extends RBM_CPT {

	public $post_type = 'teched-directory';
	public $label_singular = null;
	public $label_plural = null;
	public $labels = array();
	public $icon = 'book-alt';
	public $post_args = array(
		'hierarchical' => false,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail' ),
		'has_archive' => true,
		'rewrite' => array(
			'slug' => 'directory',
			'with_front' => false,
			'feeds' => false,
			'pages' => true
		),
		'menu_position' => 11,
		//'capability_type' => 'directory',
	);

	/**
	 * CPT_TechEd_CPT_Directory constructor.
	 *
	 * @since {{VERSION}}
	 */
	function __construct() {

		// This allows us to Localize the Labels
		$this->label_singular = __( 'Directory Item', 'teched-cpt-directory' );
		$this->label_plural = __( 'Directory Items', 'teched-cpt-directory' );

		$this->labels = array(
			'menu_name' => __( 'Directory', 'teched-cpt-directory' ),
			'all_items' => __( 'All Directory Items', 'teched-cpt-directory' ),
		);

		parent::__construct();

        add_action( 'init', array( $this, 'register_taxonomy' ) );
        
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		
	}

	/**
	 * Registers our Directory Item Categories Taxonomy
	 *
	 * @access	public
	 * @since	{{VERSION}}
	 * @return  void
	 */
	public function register_taxonomy() {

		$args = array(
            'hierarchical'          => true,
            'labels'                => $this->get_taxonomy_labels( __( 'State', 'teched-cpt-directory' ), __( 'States', 'teched-cpt-directory' ) ),
            'show_in_menu'          => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'directory-state' ),
        );
    
        register_taxonomy( 'teched-directory-state', 'teched-directory', $args );

        $args = array(
            'hierarchical'          => true,
            'labels'                => $this->get_taxonomy_labels( __( 'Category', 'teched-cpt-directory' ), __( 'Categories', 'teched-cpt-directory' ) ),
            'show_in_menu'          => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'directory-category' ),
        );
    
        register_taxonomy( 'teched-directory-category', 'teched-directory', $args );

        $args = array(
            'hierarchical'          => false,
            'labels'                => $this->get_taxonomy_labels( __( 'Tag', 'teched-cpt-directory' ), __( 'Tags', 'teched-cpt-directory' ) ),
            'show_in_menu'          => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'directory-tag' ),
        );
    
        register_taxonomy( 'teched-directory-tag', 'teched-directory', $args );

	}

	/**
     * DRYs up the code above a little
     *
     * @param   [string]  $singular   Singular Label
     * @param   [string]  $plural     Plural Label
     * @param   [string]  $menu_name  Menu Label. Defaults to Plural Label
     *
     * @since   {{VERSION}}
     * @return  [array]               Taxonomy Labels
     */
    private function get_taxonomy_labels( $singular, $plural, $menu_name = false ) {

        if ( ! $menu_name ) {
            $menu_name = $plural;
        }

        $labels = array(
            'name'                       => $menu_name,
            'singular_name'              => $singular,
            'search_items'               => sprintf( __( 'Search %', 'teched-cpt-directory' ), $plural ),
            'popular_items'              => sprintf( __( 'Popular %s', 'teched-cpt-directory' ), $plural ),
            'all_items'                  => sprintf( __( 'All %', 'teched-cpt-directory' ), $plural ),
            'parent_item'                => sprintf( __( 'Parent %s', 'teched-cpt-directory' ), $singular ),
            'parent_item_colon'          => sprintf( __( 'Parent %s:', 'teched-cpt-directory' ), $singular ),
            'edit_item'                  => sprintf( __( 'Edit %s', 'teched-cpt-directory' ), $singular ),
            'update_item'                => sprintf( __( 'Update %s', 'teched-cpt-directory' ), $singular ),
            'add_new_item'               => sprintf( __( 'Add New %s', 'teched-cpt-directory' ), $singular ),
            'new_item_name'              => sprintf( __( 'New %s Name', 'teched-cpt-directory' ), $singular ),
            'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'teched-cpt-directory' ), $plural ),
            'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'teched-cpt-directory' ), $plural ),
            'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'teched-cpt-directory' ), $plural ),
            'not_found'                  => sprintf( __( 'No %s found.', 'teched-cpt-directory' ), $plural ),
            'menu_name'                  => $menu_name,
        );

        return $labels;

    }

    /**
	 * Enqueues the necessary JS/CSS on the Directory Screen
	 *
	 * @access	public
	 * @since	{{VERSION}}
	 * @return  void
	 */
	public function admin_enqueue_scripts() {

		$current_screen = get_current_screen();
		global $pagenow;
		
		if ( $current_screen->post_type == 'teched-directory' && 
			( in_array( $pagenow, array( 'post-new.php', 'post.php' ) ) ) ) {

            wp_enqueue_style( 'teched-cpt-directory-admin' );
            
            add_filter( 'rbm_fieldhelpers_load_select2', '__return_true' );

		}

	}

	/**
	 * Registers our Meta Boxes
	 *
	 * @access	public
	 * @since	{{VERSION}}
	 * @return  void
	 */
	public function add_meta_boxes() {

		add_meta_box(
			'directory-meta',
			__( 'Directory Item Meta', 'teched-cpt-directory' ),
			array( $this, 'directory_metabox_content' ),
			$this->post_type,
			'normal'
		);

	}

	/**
	 * Adds Metabox Content for our Directory Item Occurrences Meta Box
	 *
	 * @access	public
	 * @since	{{VERSION}}
	 * @return  void
	 */
	public function directory_metabox_content() {

		teched_directory_do_field_text( array(
            'name' => 'name',
            'group' => 'directory_meta',
            'label' => '<strong>' . __( 'Name', 'teched-cpt-directory' ) . '</strong>',
            'wrapper_classes' => array(
                'fieldhelpers-col',
                'fieldhelpers-col-1',
            ),
        ) );
        
        teched_directory_do_field_text( array(
            'name' => 'title',
            'group' => 'directory_meta',
            'label' => '<strong>' . __( 'Title', 'teched-cpt-directory' ) . '</strong>',
            'wrapper_classes' => array(
                'fieldhelpers-col',
                'fieldhelpers-col-1',
            ),
        ) );
        
        echo '<h3>' . __( 'Address', 'teched-cpt-directory' ) . '</h3>';

        teched_directory_do_field_text( array(
            'name' => 'street_address_1',
            'group' => 'directory_meta',
            'label' => '<strong>' . __( 'Street Address Line 1', 'teched-cpt-directory' ) . '</strong>',
            'input_class' => '',
            'input_atts' => array(
                'style' => 'width: 100%;',
            ),
            'wrapper_classes' => array(
                'fieldhelpers-col',
                'fieldhelpers-col-1',
            ),
        ) );

        teched_directory_do_field_text( array(
            'name' => 'street_address_2',
            'group' => 'directory_meta',
            'label' => '<strong>' . __( 'Street Address Line 2', 'teched-cpt-directory' ) . '</strong>',
            'input_class' => '',
            'input_atts' => array(
                'style' => 'width: 100%;',
            ),
            'wrapper_classes' => array(
                'fieldhelpers-col',
                'fieldhelpers-col-1',
            ),
        ) );

        teched_directory_do_field_text( array(
            'name' => 'city',
            'group' => 'directory_meta',
            'label' => '<strong>' . __( 'City', 'teched-cpt-directory' ) . '</strong>',
            'wrapper_classes' => array(
                'fieldhelpers-col',
                'fieldhelpers-col-3',
            ),
        ) );

        teched_directory_do_field_select( array(
            'name' => 'state',
            'group' => 'directory_meta',
            'label' => '<strong>' . __( 'State', 'teched-cpt-directory' ) . '</strong>',
            'options' => array( '' => __( 'Select a State', 'teched-cpt-directory' ) ) + teched_directory_get_state_list(),
            'placeholder' => __( 'Select a State', 'teched-cpt-directory' ),
            'wrapper_classes' => array(
                'fieldhelpers-col',
                'fieldhelpers-col-3',
            ),
        ) );

        teched_directory_do_field_text( array(
            'name' => 'zip',
            'group' => 'directory_meta',
            'label' => '<strong>' . __( 'ZIP Code', 'teched-cpt-directory' ) . '</strong>',
            'wrapper_classes' => array(
                'fieldhelpers-col',
                'fieldhelpers-col-3',
            ),
        ) );

        teched_directory_do_field_text( array(
            'name' => 'business_email',
            'group' => 'directory_meta',
            'label' => '<strong>' . __( 'Business Contact Email', 'teched-cpt-directory' ) . '</strong>',
            'wrapper_classes' => array(
                'fieldhelpers-col',
                'fieldhelpers-col-1',
            ),
        ) );

        teched_directory_do_field_text( array(
            'name' => 'phone',
            'group' => 'directory_meta',
            'label' => '<strong>' . __( 'Phone Number', 'teched-cpt-directory' ) . '</strong>',
            'wrapper_classes' => array(
                'fieldhelpers-col',
                'fieldhelpers-col-1',
            ),
        ) );

        teched_directory_do_field_text( array(
            'name' => 'fax',
            'group' => 'directory_meta',
            'label' => '<strong>' . __( 'Fax Number', 'teched-cpt-directory' ) . '</strong>',
            'wrapper_classes' => array(
                'fieldhelpers-col',
                'fieldhelpers-col-1',
            ),
        ) );

        teched_directory_do_field_text( array(
            'name' => 'website_url',
            'group' => 'directory_meta',
            'label' => '<strong>' . __( 'Business Website Address', 'teched-cpt-directory' ) . '</strong>',
            'wrapper_classes' => array(
                'fieldhelpers-col',
                'fieldhelpers-col-2',
            ),
        ) );

        teched_directory_do_field_text( array(
            'name' => 'website_text',
            'group' => 'directory_meta',
            'label' => '<strong>' . __( 'Business Website Text (Optional)', 'teched-cpt-directory' ) . '</strong>',
            'wrapper_classes' => array(
                'fieldhelpers-col',
                'fieldhelpers-col-2',
            ),
        ) );

		teched_directory_init_field_group( 'directory_meta' );

	}
	
}