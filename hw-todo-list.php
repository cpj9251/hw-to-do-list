<?php
/**
 * Plugin Name:       Hello World To Do List
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Paul Jarvis
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hw-todo-list
 *
 * @package           hw-todo-list
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function hw_todo_list_hw_todo_list_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'hw_todo_list_hw_todo_list_block_init' );



function set_up_hw_scripts($hook){

	wp_enqueue_style( 'hw_todo-style', plugins_url('/hw_todo.css', __FILE__));

	wp_enqueue_script('hw_todo_js', plugins_url('/hw_todo.js', __FILE__),array('jquery'));

	$hw_todo_nonce = wp_create_nonce( 'hw-todo-nonce' );

	$localizeYesOrNo = wp_localize_script(
	'hw_todo_js',
	'hw_ajax_obj',
	array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => $hw_todo_nonce,
		'site_url' => plugins_url()
	)
	);


 
}


add_action('wp_enqueue_scripts','set_up_hw_scripts');


register_activation_hook(__FILE__,'doDBStuffFirstTime');

function doDBStuffFirstTime(){
	
	
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	
	
	
	global $wpdb;
	
	$tablename = 'wp_hw_todo_list'; 
	
	$main_sql_create = 'CREATE TABLE ' . $tablename . '(
						id int NOT NULL AUTO_INCREMENT,
						name varchar(255) NOT NULL,
						list_order int NULL,
						PRIMARY KEY (id)
						);';  


	maybe_create_table($tablename,$main_sql_create);
	

}//end fx

add_action('wp_ajax_hw_todo', 'hw_todo_list_handler');

function hw_todo_list_handler(){

	global $wpdb;

	?>
	<ul class="hw-todo-ul-box">
<?php 

	$sql = "select * from wp_hw_todo_list order by id";

	$myRes = $wpdb->get_results($sql);

	foreach($myRes as $todo_item){

		echo '<li class="todo-item-li">'.$todo_item->name.'</li>';

	}//end foreach
	/**/
	?>
	<li class="todo-item-input-li"><input type="text" id="todo-input" class="todo-item-input" placeholder="Enter To Do Item" value=""><button id="hw_todo_submit_btn">Add New</button></li>
</ul>

<?php
	
wp_die();

}

add_action('wp_ajax_hw_todo_add', 'hw_todo_add_handler');

function hw_todo_add_handler(){

	global $wpdb;

	$wpdb->insert('wp_hw_todo_list',
			array('name'=>sanitize_text_field($_POST['todo_item'])),
			array('%s')
);

hw_todo_list_handler();

wp_die();

}
