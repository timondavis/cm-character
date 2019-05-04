<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if (class_exists('CmCharacterStatsData')) { return; }

class CmCharacterStatsController {

	const META_BOX_ID = 'cm-character-stats';
	const META_BOX_TITLE = 'Character Stats';
	const META_BOX_APPEARS_IN = CmCharacterPostType::TYPE_NAME;
	const META_BOX_CONTEXT = 'side';
	const META_BOX_PRIORITY = 'default';
	const META_BOX_ARGS = array();
	const META_BOX_NONCE_NAME = 'cm-character-stats-nonce';
	const META_BOX_TEMPLATE = '/cm-character-stats-metabox-view.php';
	const META_BOX_STYLE = 'cm-character-stats-metabox-style.css';

	public function __construct() {
		add_action( 'add_meta_boxes_' . CmCharacterPostType::TYPE_NAME,
			array ( &$this, 'register' ));

		add_action( 'save_post_' . CmCharacterPostType::TYPE_NAME, array( &$this, 'on_save' ));
	}

	public function register() {
		add_meta_box(
			sanitize_key( self::META_BOX_ID ),
			__( self::META_BOX_TITLE ),
			array( &$this, 'render' ),
			self::META_BOX_APPEARS_IN,
			self::META_BOX_CONTEXT,
			self::META_BOX_PRIORITY,
			self::META_BOX_ARGS
		);
	}

	public function render() {

		global $post;

		// Controller data passed into view
		$data = new stdClass();

		// Load model and apply needed information to $data
		$model = CmCharacterStatsService::fetch($post->ID);

		$data->fields = array();
		foreach( $model as $key => $value ) {
			if ($key === CmCharacterStatsService::DB_PRIMARY_KEY) { continue; }
			$data->fields[$key] = $value;
		}

		// Load Resources
		wp_enqueue_style( self::META_BOX_ID, plugin_dir_url( __FILE__ ) . self::META_BOX_STYLE, array(),
			CM_CHARACTER_VERSION );

		// Register nonce field and load template.
		wp_nonce_field( self::META_BOX_NONCE_NAME, self::META_BOX_NONCE_NAME );
		require plugin_dir_path( __FILE__ ) . self::META_BOX_TEMPLATE;
	}

	public function on_save( $post_id ) {

		// Validate safety and intention
		if( !isset( $_POST[self::META_BOX_NONCE_NAME] ) ||
		     !wp_verify_nonce( $_POST[self::META_BOX_NONCE_NAME], self::META_BOX_NONCE_NAME )) { return; }
		if( !current_user_can( CmCharacterPostType::PERMISSION_EDIT_POST )) { return; }

		// Load model and override with form values.
		$model = CmCharacterStatsService::fetch( $post_id );
		foreach( new CmCharacterStatsModel as $key => $field_value ) {
			if( isset( $_POST[$key] )) {
				$model->{$key} = (int) sanitize_text_field( $_POST[$key] );
			}
		}

		// Store character stat data
		CmCharacterStatsService::store_data( $model );
	}
};

