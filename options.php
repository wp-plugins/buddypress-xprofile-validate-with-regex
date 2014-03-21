<?php

// Exit if accessed directly   
if ( !defined( 'ABSPATH' ) ) exit;

$meta_keys_descriptions = array(
	'validate_with_regex' => 'Validate with regular expression (PCRE):',
	'validate_with_regex_error_message' => 'Error message to show when validation fails:'
);

function buddypress_xprofile_validate_with_regex_options($field)
{
	global $meta_keys_descriptions;
	echo "<p>\n";
	foreach( $meta_keys_descriptions as $meta_key => $meta_description ) {
		$meta_value = bp_xprofile_get_meta( $field->id, 'field', $meta_key );
		printf('
			%1$s <input type="text"
				id="buddypress_xprofile_%2$s"
				name="buddypress_xprofile_%2$s"
				value="%3$s"><br />
			', $meta_description, $meta_key, esc_attr($meta_value)
		);
	}
	echo "</p>\n";
}
add_action('xprofile_field_additional_options','buddypress_xprofile_validate_with_regex_options');

function buddypress_xprofile_validate_with_regex_save($field)
{
	global $meta_keys_descriptions;
	foreach( array_keys($meta_keys_descriptions) as $meta_key ) {
		if ( isset($_POST["buddypress_xprofile_$meta_key"]) )
		{
			bp_xprofile_update_meta(
				$field->id,
				'field',
				$meta_key,
				$_POST["buddypress_xprofile_$meta_key"]
			);
		}
	}
}
add_action('xprofile_field_after_save','buddypress_xprofile_validate_with_regex_save');

?>
