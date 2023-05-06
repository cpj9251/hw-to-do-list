<?php
#Uninstgall script 

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}


// drop a custom database table
global $wpdb;

$tablename = 'wp_hw_todo_list'; 

$wpdb->query( "DROP TABLE IF EXISTS ".$tablename );

