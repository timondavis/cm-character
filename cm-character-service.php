<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if (class_exists('CmCharacterService')) { return; }

class CmCharacterService {

	public static function fetch( $post_id ) {

		$model = new CmCharacterModel();

		$model->post  = get_post( $post_id );
		$model->meta = get_post_meta( $post_id );
		$model->stats = CmCharacterStatsService::fetch( $post_id );

		return $model;
	}

	public static function load_data( $post_id, CmCharacterModel $model ) {

		$model->post = get_post( $post_id );
		$model->meta = get_post_meta( $post_id );
		$model->stats = CmCharacterStatsService::fetch( $post_id );

		return $model;
	}

	public static function store_data( CmCharacterModel $model ) {

		$post_id = $model->post->ID;
		wp_update_post( $model->post );

		$old_post_meta = get_post_meta( $post_id );

		foreach( $model->meta as $key => $new_value ) {

			if (array_key_exists($key, $old_post_meta)) {
				update_post_meta( $post_id, $key, $new_value, $old_post_meta[$key] );
			} else {
				add_post_meta( $post_id, $key, $new_value );
			}
		}

		CmCharacterStatsService::store_data( $model->stats );
	}

	/**
	 * WP Query filter, referenced by CmCharacterPostType class.  Adds core metadata to the post.
	 *
	 * @param $clauses
	 *
	 * @see apply_filters( 'posts_clauses' )
	 * @return mixed
	 */
	public static function attach_data_to_loop( $clauses ) {

		$clauses['fields'] .= ',' . CmCharacterStatsService::get_loop_clause_fields();
		$clauses['join']   .= " " . CmCharacterStatsService::get_loop_clause_join();

		return $clauses;
	}
}

