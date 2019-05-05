<?php

/**
 * @package Creative Mutagens: Character
 * @version 0.0.3
 */
/*
Plugin Name: Creative Mutagens: Character
Plugin URI: http://wordpress.org/plugins/super-page/
Description: A bunch of experimental proofs for wp practice
Author: Timon Davis
Version: 0.0.3
Author URI: http://www.creativemutagens.com
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
const CM_CHARACTER_VERSION = '0.0.3';

require_once( plugin_dir_path( __FILE__ ) . 'cm-character-install.php');
require_once( plugin_dir_path( __FILE__ ) . 'cm-character-post-type.php' );
require_once( plugin_dir_path( __FILE__ ) . 'cm-character-class-post-type.php' );

require_once( plugin_dir_path( __FILE__ ) . 'stats/cm-character-stats-metabox-controller.php' );
require_once( plugin_dir_path( __FILE__ ) . 'stats/cm-character-stats-model.php' );
require_once( plugin_dir_path( __FILE__ ) . 'stats/cm-character-stats-service.php' );

require_once( plugin_dir_path( __FILE__ ) . 'cm-character-model.php' );
require_once( plugin_dir_path( __FILE__ ) . 'cm-character-service.php' );

$install = new CmCharacterInstall();

new CmCharacterPostType();
new CmCharacterClassPostType();

new CmCharacterStatsController();


register_activation_hook( __FILE__, array( &$install, 'cm_character_install' ));
register_activation_hook( __FILE__, array( &$install, 'cm_character_install_data' ));
register_uninstall_hook( __FILE__, array( &$install, 'cm_character_uninstall' ));



