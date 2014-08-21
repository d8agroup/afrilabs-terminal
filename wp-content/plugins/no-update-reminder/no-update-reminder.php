<?php
/*
Plugin Name: No Update Reminder
Plugin URI: http://wordpress.org/plugins/no-update-reminder/
Description: Simply Hide all Update Reminders in WP-Admin
Author: Farzad Setoode
Version: 1.0
License: GPLv2
*/

add_action('admin_menu','no_reminder');
function no_reminder() {
remove_action( 'admin_notices', 'update_nag', 3 );
}
?>