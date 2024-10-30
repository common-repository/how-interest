<?php


/**
 * @package how-interest
 * @version 1
 */




/*	
	Plugin Name: How-Interest
	Plugin URI: http://www.howlin.ie
	Description: A simple plugin to let your site's visitors register their interest in your services
	Author: P&aacute;draig Howlin
	Version: 1
	Author URI: http://www.howlin.ie
	
	
	
	
	Copyright 2011  howlin.ie  (email : hello@howlin.ie)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
*/
 




/**
 * action, activation and deactivation bits
 */
register_activation_hook(__FILE__, 'on_activation');
register_deactivation_hook(__FILE__, 'on_deactivation');
add_action('admin_menu', 'how_interest_add_menu');


//---------------------------------------------------------------






/**
 * on_activation
 * 
 */
function on_activation(){
	
	global $wpdb;
	$table_name = $wpdb->prefix . "how_interested";

	$sql = "
		CREATE TABLE `".$table_name."` (
			`id`		INT NOT NULL AUTO_INCREMENT,
			`name`		VARCHAR( 128 ),
			`email`		VARCHAR( 128 ),
			`phone`		VARCHAR( 128 ),
			`date`		INT(11),
			PRIMARY KEY (  `id` ),
			UNIQUE (`id`)
		)
	";
	
	$wpdb->query($wpdb->prepare($sql));
}


//---------------------------------------------------------------






/**
 * on_deactivation
 * 
 */
function on_deactivation(){
	
	global $wpdb;
	$table_name = $wpdb->prefix . "how_interested";
	
	$sql = "DROP TABLE `".$table_name."`";
	
	$wpdb->query($wpdb->prepare($sql));
}


//---------------------------------------------------------------






/**
 * add_menu
 * 
 */
function how_interest_add_menu(){
	add_options_page('How-Interest', 'How-Interest', 'level_10', 'how_interest', 'how_interest_admin_view');
}


//---------------------------------------------------------------








/**
 * how_interest_admin_view
 * 	import the plugins admin view
 */
function how_interest_admin_view(){
	include 'admin_page.php';
}


//---------------------------------------------------------------







/**
 * load widget
 * 
 */
//include 'sample-widget.php';
include 'widget.php';


//---------------------------------------------------------------




/* End of file how-interest.php */