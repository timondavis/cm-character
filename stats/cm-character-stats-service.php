<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if (class_exists('CmCharacterStatsService')) { return; }

class CmCharacterStatsService {

	const DB_TABLE_NAME = 'cm_character_stats';
	const DB_PRIMARY_KEY = 'id';

	public static function fetch( $post_id ) {

		return self::load_data( $post_id, new CmCharacterStatsModel );
	}

	public static function load_data( $post_id, $model ) {

		global $wpdb;
		$table_name = $wpdb->prefix . self::DB_TABLE_NAME;
		$primary_key = self::DB_PRIMARY_KEY;

		$fields = array();
		foreach( $model as $key => $value ) {
			$fields[$key] = $key;
		}

		$query = "
		SELECT " . implode( ",", $fields ) . " 
		FROM " . $table_name . " 
		WHERE " . $primary_key . " = %d";

		$query_vars = array( $post_id );

		$results = $wpdb->get_row( $wpdb->prepare( $query, $query_vars ));

		foreach( $results as $key => $value ) {
			$model->{$key} = $value;
		}

		return $model;
	}

	public static function store_data( $model ) {

		global $wpdb;

		$data_array = [];
		$data_format = [];

		foreach( $model as $key => $value ) {
			$data_array[$key] = $value;
			$data_format[] = '%d';
		}

		$where_array = array( self::DB_PRIMARY_KEY => $model->{self::DB_PRIMARY_KEY} );
		$where_format = array( '%d' );

		$wpdb->update( $wpdb->prefix . self::DB_TABLE_NAME, $data_array, $where_array, $where_format );
	}

}