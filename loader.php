<?php
/*
Plugin Name: BuddyPress XProfile Validate with RegEx
Description: BuddyPress - Validate XProfile data with PCRE regular expressions.
Version: 0.1.0
Author: Tomasz "Tometzky" Ostrowski
License: AGPLv3 or later
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function buddypress_xprofile_validate_with_regex()
{
	if ( ! bp_is_active( 'xprofile' ) ) return;

	$field_ids = explode( ',', $_POST['signup_profile_field_ids'] );
	foreach( $field_ids as $field_id ) {
		if ( !ctype_digit($field_id) ) {
			trigger_error('Invalid field_id in signup_profile_field_ids', E_USER_ERROR);
		}
		$value_regex=bp_xprofile_get_meta($field_id, 'field', 'validate_with_regex');
		if ( $value_regex==='' or !is_string($value_regex) ) {
			continue;
		}
		if ( !isset($_POST["field_$field_id"]) ) {
			continue;
		}
		if ( $_POST["field_$field_id"]==='' ) {
			# Don't validate empty fields
			continue;
		}

		$value = stripslashes($_POST["field_$field_id"]);
		if ( ! preg_match($value_regex, $value) ) {
			$error_message = bp_xprofile_get_meta($field_id, 'field', 'validate_with_regex_error_message');
			if ( $error_message==='' or !is_string($value_regex) ) {
				$error_message = __('Error');
			}
			global $bp;
			$bp->signup->errors["field_$field_id"] = $error_message;
		}
	}
}
add_filter('bp_signup_validate','buddypress_xprofile_validate_with_regex');

if (is_admin()) {
	require(dirname(__FILE__).'/options.php');
}

?>
