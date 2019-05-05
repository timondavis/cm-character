<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if (class_exists('CmCharacterStatsModel')) { return; }

class CmCharacterStatsModel {

	public $post_id;
	public $strength;
	public $dexterity;
	public $constitution;
	public $wisdom;
	public $intelligence;
	public $charisma;

	public function get_keys( $ignore_fields = array( CmCharacterStatsService::DB_PRIMARY_KEY )) {

		$keys = array();

		foreach( new CmCharacterStatsModel() as $key => $value ) {

			if( in_array( $key, $ignore_fields )) { continue; }

			$keys[] = $key;
		}

		return $keys;
	}
}