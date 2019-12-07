<?php
/*  Copyright 2012  Kotori  (email : kotori@deimos.hopto.org)

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
/* Hooks/filters */
register_activation_hook(__FILE__, 'atavism_server_add_defaults');
register_deactivation_hook( __FILE__, 'atavism_server_remove' );
add_action('admin_menu', 'atavism_admin_add_server_page');
add_action('admin_init', 'atavism_admin_server_init');
/* Hook the admin options page */
global $cnt;
$cnt = 1;
function atavism_admin_add_server_page()
{
	add_utility_page(
		'Atavism Server Control',		// Page title. (required)
		'Server Control',		// Menu title. (required)
		'manage_options',				// Capability. (required)
		__FILE__,					// Menu slug. (required)
		'atavism_server_control_page'		// Callback function. (optional)
	);
}
/* Display the admin options page */
function atavism_server_control_page()
{
	?>
	<div class ="wrap">
		<div class="icon32" id="icon-plugins"><br></div>
		<h2>Atavism Server Control</h2>
		<?php settings_errors(); ?>
		<form action="" method="post">
			<?php settings_fields('atavism_server_control'); ?>
			<?php do_settings_sections(__FILE__); ?>
            <br>
		</form>
    </div>
	<?php
	start();
}
function start() {
	global $options;
	$options = get_option( 'atavism_plugin_options' );
	if ( isset( $_POST['stop'] ) ) {
		$string      = $_POST['stop'];
		$cnt         = ltrim( $string, "Stop " );
		$options     = get_option( 'atavism_plugin_options' );
		$mysqli_conn = new mysqli(
			$options[ 'atavism_admin_db' . strval( $cnt ) . '_hostname_string' ],  // Hostname
			$options[ 'atavism_admin_db' . strval( $cnt ) . '_user_string' ],      // Username
			$options[ 'atavism_admin_db' . strval( $cnt ) . '_pass_string' ],      // Password
			$options[ 'atavism_admin_db' . strval( $cnt ) . '_schema_string' ],    // Database
			$options[ 'atavism_admin_db' . strval( $cnt ) . '_port_string' ]    // Port
		);

		$sql = "INSERT INTO server (action) 
    VALUES ('stop');";
		if ( $mysqli_conn->query( $sql ) === true ) {
			echo "Command Sent. Please allow 3 minutes for server to complete the action!";
		} else {
			echo "Error: " . $sql . "<br>" . $mysqli_conn->error;
		}
		$mysqli_conn->close();
	}
	if ( isset( $_POST['start-restart'] ) ) {
		$string      = $_POST['start-restart'];
		$cnt         = ltrim( $string, "Start/Restart " );
		$options     = get_option( 'atavism_plugin_options' );
		$mysqli_conn = new mysqli(
			$options[ 'atavism_admin_db' . strval( $cnt ) . '_hostname_string' ],  // Hostname
			$options[ 'atavism_admin_db' . strval( $cnt ) . '_user_string' ],      // Username
			$options[ 'atavism_admin_db' . strval( $cnt ) . '_pass_string' ],      // Password
			$options[ 'atavism_admin_db' . strval( $cnt ) . '_schema_string' ],    // Database
			$options[ 'atavism_admin_db' . strval( $cnt ) . '_port_string' ]    // Port
		);

		$sql = "INSERT INTO server (action) 
    VALUES ('restart');";
		if ( $mysqli_conn->query( $sql ) === true ) {
			echo "Command Sent. Please allow 3 minutes for server to complete the action!";
		} else {
			echo "Error: " . $sql . "<br>" . $mysqli_conn->error;
		}
		$mysqli_conn->close();
	}
}
	/* Add the admin settings */
	function atavism_admin_server_init() {
		register_setting(
			'atavism_server_control',        // Settings page
			'atavism_server_control'        // Option name
		);
		add_settings_section(
			'plugin_main',            // Id
			'',        // Title
			'server_plugin_section_text',        // Callback function
			__FILE__            // Page
		);

	}

	function server_plugin_section_text() {
		$options = get_option( 'atavism_plugin_options' );
		$cnt     = 1;
		echo '<hr>';
		foreach ( range( 1, $options['server_count'] ) as $i ) {
			$conn = new mysqli( $options[ 'atavism_admin_db' . strval( $cnt ) . '_hostname_string' ], $options[ 'atavism_admin_db' . strval( $cnt ) . '_user_string' ], $options[ 'atavism_admin_db' . strval( $cnt ) . '_pass_string' ], $options[ 'atavism_admin_db' . strval( $cnt ) . '_schema_string' ], $options[ 'atavism_admin_db' . strval( $cnt ) . '_port_string' ] );
			if ( $conn->connect_error ) {
				echo "Error:" . $conn->error;
			} else {
			}
			$sqlworld = "SELECT status FROM server_status WHERE server = 'world'";
			$sqlauth  = "SELECT status FROM server_status WHERE server = 'auth'";
			$result   = $conn->query( $sqlworld );
			$row      = $result->fetch_assoc();
			if ( $row["status"] == '1' ) {
				$worldup = true;
			} else {
				$worldup = false;
			}
			$result = $conn->query( $sqlauth );
			$row    = $result->fetch_assoc();
			if ( $row["status"] == '1' ) {
				$authup = true;
			} else {
				$authup = false;
			}

			$conn->close();
			if ( ! empty( $options[ 'atavism_admin_db' . strval( $cnt ) . '_hostname_string' ] ) ) {
				if ( $authup == true ) {
					echo $options[ 'atavism_world' . strval( $cnt ) . '_name_string' ] . ':<br> Auth <img width="32" height="32" style=vertical-align:middle title="Auth Server Online" name="online" alt="online" src="' . plugins_url( 'images/online.png', __FILE__ ) . '" border="0" />';

				} else {
					echo $options[ 'atavism_world' . strval( $cnt ) . '_name_string' ] . ':<br> Auth <img width="32" height="32" style=vertical-align:middle title="Auth Server Offine" name="offline" alt="online" src="' . plugins_url( 'images/offline.png', __FILE__ ) . '" border="0" />';
				}
			} else {
				echo $options[ 'atavism_world' . strval( $cnt ) . '_name_string' ] . ':<br>';
			}
			if ( $worldup == true ) {
				echo ' World <img width="32" height="32" style=vertical-align:middle title="World Server Online" name="online" alt="online" src="' . plugins_url( 'images/online.png', __FILE__ ) . '" border="0" /><br><input name="start-restart" type="submit" class="button-primary" value="Start/Restart ' . strval( $cnt ) . '"/><input name="stop" type="submit" class="button-primary" value="Stop ' . $cnt . '" /><p>';
			}
			if ( $worldup != true ) {
				echo ' World <img width="32" height="32" style=vertical-align:middle title="Auth Server Offine" name="offline" alt="online" src="' . plugins_url( 'images/offline.png', __FILE__ ) . '" border="0" /><br><input name="start-restart" type="submit" class="button-primary" value="Start/Restart ' . strval( $cnt ) . '"/><input name="stop" type="submit" class="button-primary" value="Stop ' . $cnt . '" /><p>';
			}
			$cnt = $cnt + 1;
			echo '<hr>';
		}
	}

/*
 * Callback: recaptcha_apikey_pub_plugin_setting_string()
 * Handles: atavism_recaptcha_apikey_pub_string
 */

?>
