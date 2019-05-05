<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if (class_exists('CmCharacterModel')) { return; }

class CmCharacterModel {

	protected $post;
	protected $meta;
	protected $stats;

	public function __get( $key ) {

		if ( !$this->post ) { return $this->{$key}; }

		$post_id = $this->post->ID;

		if ($this->{$key} === null) {

			switch( $key ){
				case( 'meta' ): {
					$this->meta = get_post_meta( $post_id );
					break;
				}
				case( 'stats' ): {
					$this->stats = CmCharacterStatsService::fetch( $post_id );
					break;
				}
				default: break;
			}
		}

		return $this->{$key};
	}

	public function __set( $key, $value ) {
		$this->{$key} = $value;
	}
}