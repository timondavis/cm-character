<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if (class_exists('CmCharacterInstall')) { return; }

class CmCharacterInstall {

	const CM_CHARACTER_VERSION_OPTION = 'cm_character_db_version';
	const STATS_TABLE_NAME = 'cm_character_stats';
	const CHARACTER_TABLE_NAME = 'cm_character';
	const CLASS_TABLE_NAME = 'cm_class';

	public function cm_character_install() {

		if (!self::cm_character_should_update()) {
			return;
		}

		global $wpdb;

		$stats_table_name = $wpdb->prefix . self::STATS_TABLE_NAME;
		$class_table_name = $wpdb->prefix . self::CLASS_TABLE_NAME;

		$charset_collate = $wpdb->get_charset_collate();

		$stats_sql =
		"CREATE TABLE " . $stats_table_name . " (
			post_id int NOT NULL,
			strength tinyint NOT NULL,
			dexterity tinyint NOT NULL,
			constitution tinyint NOT NULL,
			wisdom tinyint NOT NULL,
			intelligence tinyint NOT NULL,
			charisma tinyint NOT NULL,
			PRIMARY KEY (post_id) ) " . $charset_collate . ";";

		$class_sql =
		"CREATE TABLE " . $class_table_name . "(
			post_id int NOT NULL,
			classname varchar(32) NOT NULL,
			description varchar(2048) NOT NULL,
		PRIMARY KEY (post_id) ) " . $charset_collate . ";";

		$sql = $stats_sql . $class_sql;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option(
			self::CM_CHARACTER_VERSION_OPTION,
			CM_CHARACTER_VERSION
		);
	}

	public function cm_character_uninstall() {

		global $wpdb;

		$stats_table_name = $wpdb->prefix . self::STATS_TABLE_NAME;
		$class_table_name = $wpdb->prefix . self::CLASS_TABLE_NAME;

		$stats_sql =
		"DROP TABLE " . $stats_table_name . ";";

		$class_sql =
		"DROP TABLE" . $class_table_name . ";";

		$sql = $stats_sql . $class_sql;

		dbDelta($sql);
	}

	public function cm_character_install_data() {

		if (!self::cm_character_should_update()) {
			return;
		}

		$this->cm_character_insert_default_content();
	}

	private function cm_character_insert_default_content() {

	}

	private static function cm_character_should_update() {
		$registered_version = get_option( self::CM_CHARACTER_VERSION_OPTION );

		if ($registered_version &&
		    $registered_version >= CM_CHARACTER_VERSION) {

			return false;
		}

		return true;
	}
}