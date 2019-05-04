<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if (class_exists('CmCharacterStatsModel')) { return; }

class CmCharacterStatsModel {

	public $id;
	public $strength;
	public $dexterity;
	public $constitution;
	public $wisdom;
	public $intelligence;
	public $charisma;
}