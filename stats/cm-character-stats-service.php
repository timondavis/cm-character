<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if (class_exists('CmCharacterStatsService')) { return; }

class CmCharacterStatsService {

	const DB_TABLE_NAME = 'cm_character_stats';
	const DB_PRIMARY_KEY = 'post_id';

	public static function fetch( $post_id ) {

		return self::load_data( $post_id, new CmCharacterStatsModel );
	}

	/**
	 * Get a model instance by extracting from post data (this assumes that post data contains
	 * requisite values.  See CmCharacterService::attach_data_to_loop as an example on how to do this.
	 * By Default, this plugin captures this data and attaches it to the post when queyring for cm-character
	 * instances.
	 *
	 * @param $post
	 * @param null $model
	 */
	public static function get_from_post_data( $post, $model = null ) {

		$model = ( $model ) ? $model : new CmCharacterStatsModel();

		foreach( $model as $key => $value ) {

			if ( $key === self::DB_PRIMARY_KEY ) {
				$model->{self::DB_PRIMARY_KEY} = $post->ID;
			} else {
				$model->{$key} = $post->{$key};
			}
		}

		return $model;
	}

	public static function load_data( $post_id, CmCharacterStatsModel $model ) {

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

		if ( !$results ) {
			$model = new CmCharacterStatsModel();
			$model->post_id = $post_id;
			return $model;
		}

		foreach( $results as $key => $value ) {
			$model->{$key} = $value;
		}

		return $model;
	}

	/**
	 * Insert / Update record reflecting the provided model
	 * @param $model CmCharacterStatsModel
	 */
	public static function store_data( CmCharacterStatsModel $model ) {

		global $wpdb;

		$data_array = [];
		$data_format = [];

		foreach( $model as $key => $value ) {
			$data_array[$key] = $value;
			$data_format[] = '%d';
		}

		$wpdb->replace( $wpdb->prefix . self::DB_TABLE_NAME, $data_array);
	}

	/**
	 * Returns the list of fields needed for getting the stats associated with the given character from the loop.
	 * Requires use of JOIN clause to reference the table
	 *
	 * @see add_filter( 'posts_clauses' ), add_filter( 'posts_fields' )
	 * @see CmCharacterStatsService::get_loop_clause_join()
	 * @return string
	 */
	public static function get_loop_clause_fields() {

		$stats_fields_array = [];

		foreach( new CmCharacterStatsModel as $key => $value ) {
			if ($key == CmCharacterStatsService::DB_PRIMARY_KEY ) { continue; }
			$stats_fields_array[] = $key;
		}

		return implode( ",", $stats_fields_array );
	}

	/**
	 * Returns the join clause necessary to integrate character stats in the WP Query loop
	 *
	 * @see add_filter( 'posts_clauses' ), add_filter( 'posts_join_paged' )
	 * @return string
	 */
	public static function get_loop_clause_join() {

		global $wpdb;

		return "
		LEFT JOIN " . $wpdb->prefix . CMCharacterStatsService::DB_TABLE_NAME . "
		ON " . $wpdb->prefix . 'posts.ID = ' . CmCharacterStatsService::DB_PRIMARY_KEY;
	}
}