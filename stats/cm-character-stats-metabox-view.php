<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if ( class_exists( 'CmCharacterStatsView' )) { return; }

if (!$data) { return; }

?>

<?php foreach ( $data->fields as $key => $value ): ?>
	<?php
		$field_name  = sanitize_title($key);
		$field_label = ucfirst($key);
	?>
    <div class="field-wrapper">
		<label for="<?php echo $field_name ?>"><?php echo $field_label ?></label>
		<input type="number" id="<?php echo $field_name ?>" name="<?php echo $field_name?>" value="<?php echo
        $value?>" >
    </div>
<?php endforeach; ?>

