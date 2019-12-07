<?php
/*
Plugin Name: Atavism Integration
Plugin URI: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9T6NJ747QR8PU&source=url
Description: Atavism Online Server integration plugin for Wordpress
Version: 1.1.2
Author: Scott Meadows
Author URI: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9T6NJ747QR8PU&source=url
License: GPLv2 or later
*/
/*
    Copyright 2012  Scott Meadows  (email: smeadows0155@yahoo.com)

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
require( ABSPATH . '/wp-load.php' );
require_once( plugin_dir_path( __FILE__ ) . '/admin_page_settings.php' );
require_once( plugin_dir_path( __FILE__ ) . '/admin_server_control.php' );
require_once( plugin_dir_path( __FILE__ ) . '/admin_user_management.php' );
require_once( plugin_dir_path( __FILE__ ) . '/world_status_widget.php' );
require_once( plugin_dir_path( __FILE__ ) . '/character_widget.php' );
require_once( plugin_dir_path( __FILE__ ) . '/support_model.php' );
require_once( plugin_dir_path( __FILE__ ) . '/support_widget.php' );
/* This is a hack to get around symlink resolving issues, see
 *  http://wordpress.stackexchange.com/questions/15202/plugins-in-symlinked-directories
 *  Hopefully a better solution will be found in future versions of WordPress.
 */
if ( isset( $plugin ) )
	define( 'ATAVISMMANAGEMENT_DIRECTORY', plugin_dir_url( $plugin ) );
else define( 'ATAVISMMANAGEMENT_DIRECTORY', plugin_dir_url( __FILE__ ) );
/********************************************
	Begin Shortcode Registration
********************************************/

/********************************************
	End Shortcode Registration
 ********************************************/

/**
 * Add jQuery Validation script on posts. ?>
 */
add_action( 'plugins_loaded', 'on_activate_support' );
function on_activate_support() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . "support";
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
              `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
              `title` varchar(45),
              `date` bigint(10),
              `author` varchar(45),
              `status` bigint(11),
              `category` bigint(11),
              `description` varchar(500),
              `priority` bigint(11),
              `close` bigint(1),
              PRIMARY KEY  (id)
            ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    $table_name = $wpdb->prefix . "support_category";
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(45),
                PRIMARY KEY  (id)
              ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        $wpdb->insert($table_name,
        array(
            'title' => 'Website',
        ));
        $wpdb->insert($table_name,
        array(
            'title' => 'Quests',
        ));
        $wpdb->insert($table_name,
        array(
            'title' => 'Items',
        ));
        $wpdb->insert($table_name,
        array(
            'title' => 'Classes',
        ));
        $wpdb->insert($table_name,
        array(
            'title' => 'Creatures',
        ));
        $wpdb->insert($table_name,
        array(
            'title' => 'Exploits/Usebugs',
        ));
        $wpdb->insert($table_name,
        array(
            'title' => 'Instances',
        ));
        $wpdb->insert($table_name,
        array(
            'title' => 'Guilds',
        ));
        $wpdb->insert($table_name,
        array(
            'title' => 'Friends',
        ));
        $wpdb->insert($table_name,
        array(
            'title' => 'Skills',
        ));
        $wpdb->insert($table_name,
        array(
            'title' => 'Other',
        ));
    }
    $table_name = $wpdb->prefix . "support_priority";
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(45),
                PRIMARY KEY  (id)
              ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        $wpdb->insert($table_name,
        array(
            'title' => 'High',
        ));
        $wpdb->insert($table_name,
        array(
            'title' => 'Medium',
        ));
        $wpdb->insert($table_name,
        array(
            'title' => 'Low',
        ));
    }
    $table_name = $wpdb->prefix . "support_status";
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(45),
                PRIMARY KEY  (id)
              ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        $wpdb->insert($table_name,
            array(
                'title' => 'New Ticket',
            ));
        $wpdb->insert($table_name,
            array(
                'title' => 'Waiting for more information',
            ));
        $wpdb->insert($table_name,
            array(
                'title' => 'Ticket Confirmed',
            ));
        $wpdb->insert($table_name,
            array(
                'title' => 'In Progress',
            ));
        $wpdb->insert($table_name,
            array(
                'title' => 'Fix needs testing',
            ));
        $wpdb->insert($table_name,
            array(
                'title' => 'Fix needs review',
            ));
        $wpdb->insert($table_name,
            array(
                'title' => 'Invalid Ticket',
            ));
        $wpdb->insert($table_name,
            array(
                'title' => 'Ticket Resolved',
            ));
    }
}


