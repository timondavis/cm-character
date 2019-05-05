<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if (class_exists('CmCharacterPostType')) { return; }

class CmCharacterPostType {

	// Type Identifier
	const TYPE_NAME                 = 'cm-character';

	// Type Labels & Description
	const LABEL_NAME                = 'Character';
	const LABEL_SINGULAR_ITEM       = 'Character';
	const LABEL_ADD_NEW_ITEM        = 'Add New Character';
	const LABEL_EDIT_ITEM           = 'Edit Character';
	const LABEL_NEW_ITEM            = 'New Character';
	const LABEL_VIEW_ITEM           = 'View Character';
	const LABEL_VIEW_ITEMS          = 'View Characters';
	const LABEL_SEARCH_ITEMS        = 'Search Characters';
	const LABEL_NOT_FOUND           = 'No Characters';
	const LABEL_NOT_FOUND_IN_TRASH  = 'No Characters Found in Trash';
	const LABEL_ALL_ITEMS           = 'All Characters';
	const LABEL_ARCHIVE             = 'Character Archives';
	const LABEL_ATTRIBUTES          = 'Character Attributes';
	const LABEL_INSERT_INTO_ITEM    = 'Insert into Character';
	const LABEL_FILTER_ITEMS        = 'Filter Characters list';
	const LABEL_ITEM_NAVIGATION     = 'Characters list navigation';
	const LABEL_ITEMS_LIST          = 'Characters list';
	const LABEL_ITEM_PUBLISHED      = 'Character published';
	const LABEL_ITEM_PUBLISHED_PRIVATE  = 'Character published privately';
	const LABEL_ITEM_REVERTED_TO_DRAFT  = 'Character reverted to draft';
	const LABEL_ITEM_SCHEDULED          = 'Character scheduled';
	const LABEL_ITEM_UPDATED            = 'Character updated';
	const TYPE_DESCRIPTION              = 'A d20 character';

	// Type Data Exposure And Structure
	const TYPE_IS_PUBLIC                = true;
	const TYPE_IS_HIERARCHICAL          = false;
	const TYPE_IS_EXCLUDED_FROM_SEARCH  = false;
	const TYPE_IS_PUBLIC_FOR_QUERY      = true;

	// Type UI Exposure
	const TYPE_IS_SHOWN_IN_UI           = self::TYPE_IS_PUBLIC;
	const TYPE_IS_SHOWN_IN_NAV          = self::TYPE_IS_PUBLIC;
	const TYPE_IS_SHOWN_IN_MENU         = self::TYPE_IS_SHOWN_IN_UI;
	const TYPE_IS_SHOWN_IN_ADMIN_BAR    = self::TYPE_IS_SHOWN_IN_MENU;
	const TYPE_MENU_POSITION            = null; // default (at the bottom)
	//const TYPE_MENU_ICON                = 'none'; // default - may also use base64 encoded image OR icon class name
	const TYPE_MENU_ICON                = 'dashicons-heart';

	// Type REST Options
	const TYPE_IS_EXPOSED_TO_REST       = false;
	const TYPE_REST_KEY                 = self::TYPE_NAME;
	const TYPE_REST_CONTROLLER_CLASS    = 'WP_REST_Posts_Controller'; // default

	// Type Security
	const TYPE_CAPABILITY_SINGULAR      = 'post';
	const TYPE_CAPABILITY_PLURAL        = 'posts';
	const TYPE_CAPABILITIES_CUSTOM      = array(); // Override defaults with these values.
	const TYPE_USE_META_CAPABILITY_HANDLING = false;

	const PERMISSION_EDIT_POST          = 'edit_post';

	// Type WP Post Features
	const TYPE_POST_FEATURES            = array(
		'title',
		//'editor',
		//'comments',
		//'revisions',
		//'trackbacks',
		//'author',
		//'excerpt',
		//'page-attributes',
		'thumbnail',
		//'custom-fields',
		//'post-formats'
	);

	// Type Behavior & Data Customization
	const TYPE_CMB_CALLBACK             = null;
	const TYPE_TAXONOMIES               = array();
	const TYPE_IS_ARCHIVABLE            = true;
	const TYPE_IS_EXPORTABLE            = true;
	const TYPE_IS_DELETING_POSTS_WITH_USER  = null;     // Default

	// Type Rewrite Rules
	const TYPE_REWRITES                 = array(        // set to TRUE for default, FALSE to prevent
		'slug'          => self::TYPE_NAME,             // Default
		'with_front'    => true,                        // Default
		'feeds'         => self::TYPE_IS_ARCHIVABLE,    // Default
		'pages'         => true,                        // Default
		'ep_mask'       => EP_PERMALINK                 // Default (in most cases)
	);

	function __construct() {
		add_action( 'init', array( &$this, 'register_super_page_post_type' ));
		add_action( 'do_meta_boxes', array( &$this, 'remove_unwanted_meta_boxes' ));
		add_filter( 'posts_clauses', array( 'CmCharacterService', 'attach_data_to_loop' ));
	}

	function register_super_page_post_type() {

		register_post_type( sanitize_key( self::TYPE_NAME ), array(
			'labels' => array(
				'name'                  => self::LABEL_NAME,
				'singular_name'         => self::LABEL_SINGULAR_ITEM,
				'add_new_item'          => self::LABEL_ADD_NEW_ITEM,
				'edit_item'             => self::LABEL_EDIT_ITEM,
				'new_item'              => self::LABEL_NEW_ITEM,
				'view_item'             => self::LABEL_VIEW_ITEM,
				'view_items'            => self::LABEL_VIEW_ITEMS,
				'search_items'          => self::LABEL_SEARCH_ITEMS,
				'not_found'             => self::LABEL_NOT_FOUND,
				'not_found_in_trash'    => self::LABEL_NOT_FOUND_IN_TRASH,
				//'parent_item_colon'     => self::LABEL_PARENT_ITEM,
				'all_items'             => self::LABEL_ALL_ITEMS,
				'archives'              => self::LABEL_ARCHIVE,
				'attributes'            => self::LABEL_ATTRIBUTES,
				'insert_into_item'      => self::LABEL_INSERT_INTO_ITEM,
				'filter_items_list'     => self::LABEL_FILTER_ITEMS,
				'items_list_navigation' => self::LABEL_ITEM_NAVIGATION,
				'items_list'            => self::LABEL_ITEMS_LIST,
				'item_published'        => self::LABEL_ITEM_PUBLISHED,
				'item_published_privately'  => self::LABEL_ITEM_PUBLISHED_PRIVATE,
				'item_reverted_to_draft'    => self::LABEL_ITEM_REVERTED_TO_DRAFT,
				'item_scheduled'        => self::LABEL_ITEM_SCHEDULED,
				'item_updated'          => self::LABEL_ITEM_UPDATED,
			),
			'description'               => self::TYPE_DESCRIPTION,
			'public'                    => self::TYPE_IS_PUBLIC,
			'hierarchical'              => self::TYPE_IS_HIERARCHICAL,
			'excluded_from_search'      => self::TYPE_IS_EXCLUDED_FROM_SEARCH,
			'publicly_querable'         => self::TYPE_IS_PUBLIC_FOR_QUERY,
			'show_ui'                   => self::TYPE_IS_SHOWN_IN_UI,
			'show_in_menu'              => self::TYPE_IS_SHOWN_IN_MENU,
			'show_in_nav_menus'         => self::TYPE_IS_SHOWN_IN_NAV,
			'show_in_admin_bar'         => self::TYPE_IS_SHOWN_IN_ADMIN_BAR,
			'show_in_rest'              => self::TYPE_IS_EXPOSED_TO_REST,
			'rest_base'                 => esc_url_raw( self::TYPE_REST_KEY ),
			'rest_controller_class'     => self::TYPE_REST_CONTROLLER_CLASS,
			'menu_position'             => self::TYPE_MENU_POSITION,
			'menu_icon'                 => self::TYPE_MENU_ICON,
			// 'capability_type'          => array( self::TYPE_CAPABILITY_SINGULAR, self::TYPE_CAPABILITY_PLURAL ),
			// 'capabilities'              => self::TYPE_CAPABILITIES_CUSTOM, // Uncomment to override defaults.
			// 'map_meta_cap'              => self::TYPE_USE_META_CAPABILITY_HANDLING,
			'supports'                  => self::TYPE_POST_FEATURES,
			// 'register_meta_box_cb'      => self::TYPE_CMB_CALLBACK,  // Uncomment to override defaults
			'taxonomies'                => self::TYPE_TAXONOMIES,
			'has_archive'               => self::TYPE_IS_ARCHIVABLE,
			'rewrite'                   => self::TYPE_REWRITES,
			'query_var'                 => esc_url_raw( self::TYPE_NAME ),
			'can_export'                => self::TYPE_IS_EXPORTABLE,
			'delete_with_user'          => self::TYPE_IS_DELETING_POSTS_WITH_USER
		));
	}

	public function remove_unwanted_meta_boxes() {
		remove_meta_box( 'commentstatusdiv', self::TYPE_NAME, 'normal' );
		remove_meta_box( 'commentsdiv', self::TYPE_NAME, 'normal' );
	}
}