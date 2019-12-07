<?php
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
/* Hooks/filters */
register_activation_hook(__FILE__, 'atavism_add_defaults');
register_deactivation_hook( __FILE__, 'atavism_remove' );
add_action('admin_menu', 'atavism_admin_add_page');
add_action('admin_init', 'atavism_admin_init');
/* Hook the admin options page */
function atavism_admin_add_page()
{
	add_options_page(
		'Atavism Integration Settings',		// Page title. (required)
		'Atavism Integration',		// Menu title. (required)
		'manage_options',				// Capability. (required)
		__FILE__,					// Menu slug. (required)
		'atavism_options_page'		// Callback function. (optional)
	);
}
function  setting_dropdown_fn() {
	$options = get_option('atavism_plugin_options');
	?>
    <select name='atavism_plugin_options[server_count]'>
        <option value=1 <?php selected( $options['server_count'], 1 ); ?>>1</option>
        <option value=2 <?php selected( $options['server_count'], 2 ); ?>>2</option>
        <option value=3 <?php selected( $options['server_count'], 3 ); ?>>3</option>
        <option value=4 <?php selected( $options['server_count'], 4 ); ?>>4</option>
        <option value=5 <?php selected( $options['server_count'], 5 ); ?>>5</option>
        <option value=6 <?php selected( $options['server_count'], 6 ); ?>>6</option>
        <option value=7 <?php selected( $options['server_count'], 7 ); ?>>7</option>
        <option value=8 <?php selected( $options['server_count'], 8 ); ?>>8</option>
        <option value=9 <?php selected( $options['server_count'], 9 ); ?>>9</option>
        <option value=10 <?php selected( $options['server_count'], 10 ); ?>>10</option>
    </select>
<?php
}
function  subscription_dropdown_fn() {
	$options = get_option('atavism_plugin_options');
	?>
    <select name='atavism_plugin_options[subscription]'>
        <option value=1 <?php selected( $options['subscription'], 1 ); ?>>N/A</option>
        <option value=2 <?php selected( $options['subscription'], 2 ); ?>>Yes</option>

    </select>
	<?php
}
/* Display the admin options page */
function atavism_options_page()
{
	?>
	<div class ="wrap">
		<div class="icon32" id="icon-plugins"><br></div>
		<h2>Atavism Online Integration Settings</h2>
		<?php settings_errors(); ?>
		<form action="options.php" method="post">
			<?php settings_fields('atavism_plugin_options'); ?>
			<?php do_settings_sections(__FILE__); ?>
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" /><p>
		</form>
	</div>
	<?php
	test_database_connectivity();
}
function test_database_connectivity()
{
	/* Test the DB connection here in the settings page so we can see quickly if the settings work */
	/**
         * Master DB
	 */
        echo '<p>Master Database Connection Status: ';
        $db_test_options = get_option('atavism_plugin_options');
        $mysqli_conn = new mysqli(
                $db_test_options['atavism_master_db_hostname_string'],  // Hostname
                $db_test_options['atavism_master_db_user_string'],      // Username
                $db_test_options['atavism_master_db_pass_string'],      // Password
                $db_test_options['atavism_master_db_schema_string'], // Database
                $db_test_options['atavism_master_db_port_string']      // Port
        );
        if($mysqli_conn->connect_errno)
        {
                echo "<font color='red'><b>Failed!</b></font><p>" . $mysqli_conn->connect_error . "</p>";
        }
        else
        {
                echo "<font color='green'><b>Success!</b></font><p>";
        }
	/**
	 * admin DB
	 */
	/* reset the count to select a new database */
    $cnt4 = "1";
	foreach(range(1,$db_test_options['server_count']) as $i) {
		if (!empty($db_test_options[ 'atavism_admin_db'.strval($cnt4).'_hostname_string' ])) {
			echo '<p>Admin Database '.strval( $cnt4 ).' Connection Status: ';
			/**
			 * admin DB
			 */
			$mysqli_conn = new mysqli(
				$db_test_options['atavism_admin_db'.strval($cnt4).'_hostname_string'],  // Hostname
				$db_test_options['atavism_admin_db'.strval($cnt4).'_user_string'],      // Username
				$db_test_options['atavism_admin_db'.strval($cnt4).'_pass_string'],      // Password
				$db_test_options['atavism_admin_db'.strval($cnt4).'_schema_string'],	// Database
				$db_test_options['atavism_admin_db'.strval($cnt4).'_port_string']	// Port
			);
			if($mysqli_conn->connect_errno)
			{
				echo "<font color='red'><b>Failed!</b></font><p>" . $mysqli_conn->connect_error . "</p>";
			}
			else
			{
				echo "<font color='green'><b>Success!</b></font><p>";
			}
		}
		/**
		 * atavism DB
		 */
		$mysqli_conn = new mysqli(
			$db_test_options['atavism_atavism_db'.strval($cnt4).'_hostname_string'],  // Hostname
			$db_test_options['atavism_atavism_db'.strval($cnt4).'_user_string'],      // Username
			$db_test_options['atavism_atavism_db'.strval($cnt4).'_pass_string'],      // Password
			$db_test_options['atavism_atavism_db'.strval($cnt4).'_schema_string'],	// Database
			$db_test_options['atavism_atavism_db'.strval($cnt4).'_port_string']	// Port
		);
		echo '</p>';
		if(!empty($db_test_options['atavism_atavism_db'.strval($cnt4).'_hostname_string'])) {
			echo '<p>Server ' . strval( $cnt4 ) . ' Atavism Database Connection Status: ';
		}
		else
		    {
		        echo '<p>Server ' . strval( $cnt4 ) . ' atavism Database not configured! ';
            }
		if($mysqli_conn->connect_errno)
		{
			if(!empty($db_test_options['atavism_atavism_db'.strval($cnt4).'_hostname_string'])) {
				echo "<font color='red'><b>Failed!</b></font><p>" . $mysqli_conn->connect_error . "</p>";
			}
		}
		else
		{
			if(!empty($db_test_options['atavism_atavism_db'.strval($cnt4).'_hostname_string'])) {
			    echo "<font color='green'><b>Success!</b></font><p>";
			}
		}
		/**
		 * World Content DB
		 */
		$mysqli_conn = new mysqli(
			$db_test_options['atavism_worldcontent_db'.strval($cnt4).'_hostname_string'],  // Hostname
			$db_test_options['atavism_worldcontent_db'.strval($cnt4).'_user_string'],      // Username
			$db_test_options['atavism_worldcontent_db'.strval($cnt4).'_pass_string'],      // Password
			$db_test_options['atavism_worldcontent_db'.strval($cnt4).'_schema_string'],	// Database
			$db_test_options['atavism_worldcontent_db'.strval($cnt4).'_port_string']	// Port
		);
		echo '</p>';
		if(!empty($db_test_options['atavism_worldcontent_db'.strval($cnt4).'_hostname_string'])) {
			echo '<p>Server ' . strval( $cnt4 ) . ' World Content Database Connection Status: ';
		}
		else
		{
			echo '<p>Server ' . strval( $cnt4 ) . ' World Content Database not configured! ';
		}if($mysqli_conn->connect_errno)
		{
			if(!empty($db_test_options['atavism_worldcontent_db'.strval($cnt4).'_hostname_string'])) {
				echo "<font color='red'><b>Failed!</b></font><p>" . $mysqli_conn->connect_error . "</p>";
			}
		}
		else
		{
			if(!empty($db_test_options['atavism_worldcontent_db'.strval($cnt4).'_hostname_string'])) {
				echo "<font color='green'><b>Success!</b></font><p>";
			}
		}
		$cnt4 = $cnt4 + 1;
	}
        $mysqli_conn->close();
        /* END DB TEST */
}
/* Add the admin settings */
function atavism_admin_init()
{
	register_setting(
		'atavism_plugin_options',		// Settings page
		'atavism_plugin_options',		// Option name
		'atavism_plugin_options_validate'	// Validation callback
	);
	add_settings_section(
		'plugin_main',			// Id
		'Main Settings',		// Title
		'plugin_section_text',		// Callback function
		 __FILE__			// Page
	);
	/*  <?php add_settings_field( $id, $title, $callback, $page, $section, $args ); ?> */
    //add_settings_field('atavism_recaptcha_apikey_pub_string', 'Recaptcha Public API Key', 'recaptcha_apikey_pub_plugin_setting_string', __FILE__, 'plugin_main');
	//add_settings_field('atavism_recaptcha_apikey_priv_string', 'Recaptcha Private API Key', 'recaptcha_apikey_priv_plugin_setting_string', __FILE__, 'plugin_main');
	add_settings_field('server_count', 'How Many World Servers do you have? (Fields will update after saving)', 'setting_dropdown_fn', __FILE__, 'plugin_main');
	add_settings_field('subscription', "if you are using smzero's Xsolla integration, would you like to restrict game login to subscribers?", 'subscription_dropdown_fn', __FILE__, 'plugin_main');
	add_settings_field('atavism_db_master_hostname_string', 'Master DB Hostname/IP Address', 'master_db_hostname_plugin_setting_string', __FILE__, 'plugin_main');
	add_settings_field('atavism_master_db_port_string', 'Master DB Port', 'master_db_port_plugin_setting_string', __FILE__, 'plugin_main');
	add_settings_field('atavism_master_db_schema_string', 'Master DB Schema', 'master_db_schema_plugin_setting_string', __FILE__, 'plugin_main');
	add_settings_field('atavism_master_db_user_string', 'Master DB Username', 'master_db_user_plugin_setting_string', __FILE__, 'plugin_main');
	add_settings_field('atavism_master_db_pass_string', 'Master DB Password', 'master_db_pass_plugin_setting_string', __FILE__, 'plugin_main');
	add_option('atavism_selected_server', '1', '', __FILE__, 'plugin_main');

	$cnt = 1;
	$options = get_option('atavism_plugin_options');
	foreach(range(1,$options['server_count']) as $i) {
		add_settings_field('atavism_world'.strval($cnt).'_name_string', 'World '.strval($cnt).' Name', 'world_name_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_admin_db'.strval($cnt).'_hostname_string('.strval($cnt).'))', 'Server '.strval($cnt).' Admin DB Hostname/IP Address', 'admin_db_hostname_plugin_setting_string', __FILE__, 'plugin_main',$cnt);
		add_settings_field('atavism_admin_db'.strval($cnt).'_port_string('.strval($cnt).')', 'Server '.strval($cnt).' Admin DB Port', 'admin_db_port_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_admin_db'.strval($cnt).'_schema_string('.strval($cnt).')', 'Server '.strval($cnt).' Admin DB Schema', 'admin_db_schema_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_admin_db'.strval($cnt).'_user_string('.strval($cnt).')', 'Server '.strval($cnt).' Admin DB Username', 'admin_db_user_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_admin_db'.strval($cnt).'_pass_string', 'Server '.strval($cnt).' Admin DB Password', 'admin_db_pass_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
	    add_settings_field('atavism_atavism_db'.strval($cnt).'_hostname_string', 'Server '.strval($cnt).' Atavism DB Hostname/IP Address', 'atavism_db_hostname_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_atavism_db'.strval($cnt).'_port_string', 'Server '.strval($cnt).' Atavism DB Port', 'atavism_db_port_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_atavism_db'.strval($cnt).'_schema_string', 'Server '.strval($cnt).' Atavism DB Schema', 'atavism_db_schema_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_atavism_db'.strval($cnt).'_user_string', 'Server '.strval($cnt).' Atavism DB Username', 'atavism_db_user_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_atavism_db'.strval($cnt).'_pass_string', 'Server '.strval($cnt).' Atavism DB Password', 'atavism_db_pass_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_worldcontent_db'.strval($cnt).'_hostname_string', 'Server '.strval($cnt).' World Content DB Hostname/IP Address', 'worldcontent_db_hostname_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_worldcontent_db'.strval($cnt).'_port_string', 'Server '.strval($cnt).' World Content DB Port', 'worldcontent_db_port_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_worldcontent_db'.strval($cnt).'_schema_string', 'Server '.strval($cnt).' World Content DB Schema', 'worldcontent_db_schema_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_worldcontent_db'.strval($cnt).'_user_string', 'Server '.strval($cnt).' World Content DB Username', 'worldcontent_db_user_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		add_settings_field('atavism_worldcontent_db'.strval($cnt).'_pass_string', 'Server '.strval($cnt).' World Content DB Password', 'worldcontent_db_pass_plugin_setting_string', __FILE__, 'plugin_main', $cnt);
		$cnt = $cnt + 1;
    }
}
function plugin_section_text()
{
	echo '<p>Integrate your Atavism Online installation by providing the information below</p>';
}
/*
 * Callback: recaptcha_apikey_pub_plugin_setting_string()
 * Handles: atavism_recaptcha_apikey_pub_string
 */
function recaptcha_apikey_pub_plugin_setting_string()
{
        $options = get_option('atavism_plugin_options');
?>
	<input id='atavism_recaptcha_apikey_pub_string' name='atavism_plugin_options[atavism_recaptcha_apikey_pub_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_recaptcha_apikey_pub_string'] ); ?>" />
<?php
}
/*
 * Callback: recaptcha_apikey_priv_plugin_setting_string()
 * Handles: atavism_recaptcha_apikey_priv_string
 */
function recaptcha_apikey_priv_plugin_setting_string()
{
        $options = get_option('atavism_plugin_options');
?>
	<input id='atavism_recaptcha_apikey_priv_string' name='atavism_plugin_options[atavism_recaptcha_apikey_priv_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_recaptcha_apikey_priv_string'] ); ?>" />
<?php
}
/*
 * Callback: master_db_hostname_plugin_setting_string()
 * Handles: master_db_hostname_plugin_setting_string
 */
function master_db_hostname_plugin_setting_string()
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='atavism_master_db_hostname_string' name='atavism_plugin_options[atavism_master_db_hostname_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_master_db_hostname_string'] ); ?>" />
	<?php
}
/*
 * Callback: master_db_port_plugin_setting_string()
 * Handles: atavism_master_db_port_string
 */
function master_db_port_plugin_setting_string()
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='atavism_master_db_port_string' name='atavism_plugin_options[atavism_master_db_port_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_master_db_port_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_master_db_schema_plugin_setting_string()
 * Handles: atavism_master_db_schema_string
 */
function master_db_schema_plugin_setting_string()
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='atavism_master_db_schema_string' name='atavism_plugin_options[atavism_master_db_schema_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_master_db_schema_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_master_db_user_plugin_setting_string()
 * Handles: atavism_master_db_user_string
 */
function master_db_user_plugin_setting_string()
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='atavism_master_db_user_string' name='atavism_plugin_options[atavism_master_db_user_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_master_db_user_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_master_db_pass_plugin_setting_string()
 * Handles: atavism_master_db_pass_string
 */
function master_db_pass_plugin_setting_string()
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='atavism_master_db_pass_string' name='atavism_plugin_options[atavism_master_db_pass_string]' size='32' type='password' value="<?php esc_attr_e($options['atavism_master_db_pass_string'] ); ?>" />
	<?php
}
/*
 * Callback: world1_name_plugin_setting_string()
 * Handles: atavism_world1_name_text_string
 */
function world_name_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='world<?=$i?>_name_string' name='atavism_plugin_options[atavism_world<?=$i?>_name_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_world'.$i.'_name_string'] ); ?>" />
	<?php
}

/*
 * Callback: admin_db_hostname_plugin_setting_string()
 * Handles: atavism_admin_db_hostname_string
 */
function admin_db_hostname_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='admin_db<?=$i?>_hostname_string' name='atavism_plugin_options[atavism_admin_db<?=$i?>_hostname_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_admin_db'.$i.'_hostname_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_admin_db1_schema_plugin_setting_string()
 * Handles: atavism_admin_db1_schema_string
 */
function admin_db_schema_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='admin_db<?=$i?>_schema_string' name='atavism_plugin_options[atavism_admin_db<?=$i?>_schema_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_admin_db'.$i.'_schema_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_admin_db_user_plugin_setting_string()
 * Handles: atavism_admin_db_user_text_string
 */
function admin_db_user_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='admin_db<?=$i?>_user_string' name='atavism_plugin_options[atavism_admin_db<?=$i?>_user_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_admin_db'.$i.'_user_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_admin_db_pass_plugin_setting_string()
 * Handles: atavism_admin_db_pass_text_string
 */
function admin_db_pass_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='admin_db<?=$i?>_pass_string' name='atavism_plugin_options[atavism_admin_db<?=$i?>_pass_string]' size='32' type='password' value="<?php esc_attr_e($options['atavism_admin_db'.$i.'_pass_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_admin_db_port_plugin_setting_string()
 * Handles: atavism_admin_db_port_string
 */
function admin_db_port_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='admin_db<?=$i?>_port_string' name='atavism_plugin_options[atavism_admin_db<?=$i?>_port_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_admin_db'.$i.'_port_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_db1_hostname_plugin_setting_string()
 * Handles: atavism_atavism_db1_hostname_text_string
 */
function atavism_db_hostname_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='atavism_db<?=$i?>_hostname_string' name='atavism_plugin_options[atavism_atavism_db<?=$i?>_hostname_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_atavism_db'.$i.'_hostname_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_atavism_db1_schema_plugin_setting_string()
 * Handles: atavism_atavism_db1_schema_string
 */
function atavism_db_schema_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='atavism_db<?=$i?>_schema_string' name='atavism_plugin_options[atavism_atavism_db<?=$i?>_schema_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_atavism_db'.$i.'_schema_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_atavism_db1_user_plugin_setting_string()
 * Handles: atavism_atavism_db1_user_text_string
 */
function atavism_db_user_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='atavism_db<?=$i?>_user_string' name='atavism_plugin_options[atavism_atavism_db<?=$i?>_user_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_atavism_db'.$i.'_user_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_atavism_db1_pass_plugin_setting_string()
 * Handles: atavism_atavism_db1_pass_text_string
 */
function atavism_db_pass_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='atavism_db<?=$i?>_pass_string' name='atavism_plugin_options[atavism_atavism_db<?=$i?>_pass_string]' size='32' type='password' value="<?php esc_attr_e($options['atavism_atavism_db'.$i.'_pass_string'] ); ?>" />
	<?php
}/*
 * Callback: atavism_atavism_db1_port_plugin_setting_string()
 * Handles: atavism_atavism_db1_port_string
 */
function atavism_db_port_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='atavism_db<?=$i?>_port_string' name='atavism_plugin_options[atavism_atavism_db<?=$i?>_port_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_atavism_db'.$i.'_port_string'] ); ?>" />
	<?php
}
/*
 * Callback: worldcontent_db1_hostname_plugin_setting_string()
 * Handles: atavism_worldcontent_db1_hostname_text_string
 */
function worldcontent_db_hostname_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='worldcontent_db<?=$i?>_hostname_string' name='atavism_plugin_options[atavism_worldcontent_db<?=$i?>_hostname_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_worldcontent_db'.$i.'_hostname_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_worldcontent_db1_schema_plugin_setting_string()
 * Handles: atavism_worldcontent_db1_schema_string
 */
function worldcontent_db_schema_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='worldcontent_db<?=$i?>_schema_string' name='atavism_plugin_options[atavism_worldcontent_db<?=$i?>_schema_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_worldcontent_db'.$i.'_schema_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_worldcontent_db1_user_plugin_setting_string()
 * Handles: atavism_worldcontent_db1_user_text_string
 */
function worldcontent_db_user_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='worldcontent_db<?=$i?>_user_string' name='atavism_plugin_options[atavism_worldcontent_db<?=$i?>_user_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_worldcontent_db'.$i.'_user_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_worldcontent_db1_pass_plugin_setting_string()
 * Handles: atavism_worldcontent_db1_pass_text_string
 */
function worldcontent_db_pass_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='worldcontent_db<?=$i?>_pass_string' name='atavism_plugin_options[atavism_worldcontent_db<?=$i?>_pass_string]' size='32' type='password' value="<?php esc_attr_e($options['atavism_worldcontent_db'.$i.'_pass_string'] ); ?>" />
	<?php
}
/*
 * Callback: atavism_worldcontent_db1_port_plugin_setting_string()
 * Handles: atavism_worldcontent_db1_port_string
 */
function worldcontent_db_port_plugin_setting_string($i)
{
	$options = get_option('atavism_plugin_options');
	?>
    <input id='worldcontent_db<?=$i?>_port_string' name='atavism_plugin_options[atavism_worldcontent_db<?=$i?>_port_string]' size='32' type='text' value="<?php esc_attr_e($options['atavism_worldcontent_db'.$i.'_port_string'] ); ?>" />
	<?php
}
/* Options validation function */
function atavism_plugin_options_validate($input)
{
	$cnt5 = 1;
	$options = get_option('atavism_plugin_options');
	// Check our textbox option fields contain no HTML tags - if so strip them out
	$input['atavism_recaptcha_apikey_pub_string'] = wp_filter_nohtml_kses($input['atavism_recaptcha_apikey_pub_string']);
	$input['atavism_recaptcha_apikey_priv_string'] = wp_filter_nohtml_kses($input['atavism_recaptcha_apikey_priv_string']);
	$input['atavism_db_master_hostname_string'] = wp_filter_nohtml_kses($input['atavism_db_master_hostname_string']);
	$input['atavism_db_master_user_string'] = wp_filter_nohtml_kses($input['atavism_db_master_user_string']);
	$input['atavism_db_master_pass_string'] = wp_filter_nohtml_kses($input['atavism_db_master_pass_string']);
	$input['atavism_db_master_schema_string'] = wp_filter_nohtml_kses($input['atavism_db_master_schema_string']);
	$input['atavism_db_master_port_string'] = wp_filter_nohtml_kses($input['atavism_db_master_port_string']);
	$input['server_count'] = wp_filter_nohtml_kses($input['server_count']);
	$input['subscription'] = wp_filter_nohtml_kses($input['subscription']);
    foreach(range(1,$options['server_count']) as $i) {
	    $input['atavism_admin_db_hostname_string('.strval($cnt5).')'] = wp_filter_nohtml_kses($input['atavism_admin_db_hostname_string('.strval($cnt5).')']);
	    $input['atavism_admin_db_port_string('.strval($cnt5).')'] = wp_filter_nohtml_kses($input['atavism_admin_db_port_string('.strval($cnt5).')']);
	    $input['atavism_admin_db_schema_string('.strval($cnt5).')'] = wp_filter_nohtml_kses($input['atavism_admin_db_schema_string('.strval($cnt5).')']);
	    $input['atavism_admin_db_user_string('.strval($cnt5).')'] = wp_filter_nohtml_kses($input['atavism_admin_db_user_string('.strval($cnt5).')']);
	    $input['atavism_admin_db_pass_string('.strval($cnt5).')'] = wp_filter_nohtml_kses($input['atavism_admin_db_pass_string('.strval($cnt5).')']);
	    $input['atavism_atavism_db'.strval($cnt5).'_hostname_string'] = wp_filter_nohtml_kses($input['atavism_atavism_db'.strval($cnt5).'_hostname_string']);
	    $input['atavism_atavism_db'.strval($cnt5).'_port_string'] = wp_filter_nohtml_kses($input['atavism_atavism_db'.strval($cnt5).'_port_string']);
	    $input['atavism_atavism_db'.strval($cnt5).'_schema_string'] = wp_filter_nohtml_kses($input['atavism_atavism_db'.strval($cnt5).'_schema_string']);
	    $input['atavism_atavism_db'.strval($cnt5).'_user_string'] = wp_filter_nohtml_kses($input['atavism_atavism_db'.strval($cnt5).'_user_string']);
	    $input['atavism_atavism_db'.strval($cnt5).'_pass_string'] = wp_filter_nohtml_kses($input['atavism_atavism_db'.strval($cnt5).'_pass_string']);
	    $input['atavism_worldcontent_db'.strval($cnt5).'_hostname_string'] = wp_filter_nohtml_kses($input['atavism_worldcontent_db'.strval($cnt5).'_hostname_string']);
	    $input['atavism_worldcontent_db'.strval($cnt5).'_port_string'] = wp_filter_nohtml_kses($input['atavism_worldcontent_db'.strval($cnt5).'_port_string']);
	    $input['atavism_worldcontent_db'.strval($cnt5).'_schema_string'] = wp_filter_nohtml_kses($input['atavism_worldcontent_db'.strval($cnt5).'_schema_string']);
	    $input['atavism_worldcontent_db'.strval($cnt5).'_user_string'] = wp_filter_nohtml_kses($input['atavism_worldcontent_db'.strval($cnt5).'_user_string']);
	    $input['atavism_worldcontent_db'.strval($cnt5).'_pass_string'] = wp_filter_nohtml_kses($input['atavism_worldcontent_db'.strval($cnt5).'_pass_string']);

	    $cnt5 = $cnt5 + 1;
    }
	
	// Return validated input
	return $input;
}
/* When this plugin is deactivated, remove the options as well */
function atavism_remove()
{
	$tmp = get_option('atavism_plugin_options');
    delete_option('atavism_recaptcha_apikey_pub_string');
    delete_option('atavism_recaptcha_apikey_priv_string');
	delete_option('atavism_db_master_hostname_string');
	delete_option('atavism_db_master_port_string');
	delete_option('atavism_db_master_schema_string');
	delete_option('atavism_db_master_user_string');
	delete_option('atavism_db_master_pass_string');
	$cnt3 = 1;
	$options = get_option('atavism_plugin_options');
	foreach(range(1,$options['server_count']) as $i) {
		delete_option('atavism_world'. strval( $cnt3 ) .'_name_string');
		delete_option('atavism_admin_db'.strval($cnt3).'_hostname_string');
		delete_option('atavism_admin_db'.strval($cnt3).'_port_string');
		delete_option('atavism_admin_db'.strval($cnt3).'_schema_string');
		delete_option('atavism_admin_db'.strval($cnt3).'_user_string');
		delete_option('atavism_admin_db'.strval($cnt3).'_pass_string');
		delete_option('atavism_atavism_db'.strval($cnt3).'_hostname_string');
		delete_option('atavism_atavism_db'.strval($cnt3).'_port_string');
		delete_option('atavism_atavism_db'.strval($cnt3).'_schema_string');
		delete_option('atavism_atavism_db'.strval($cnt3).'_user_string');
		delete_option('atavism_atavism_db'.strval($cnt3).'_pass_string');
		delete_option('atavism_worldcontent_db'.strval($cnt3).'_hostname_string');
		delete_option('atavism_worldcontent_db'.strval($cnt3).'_port_string');
		delete_option('atavism_worldcontent_db'.strval($cnt3).'_schema_string');
		delete_option('atavism_worldcontent_db'.strval($cnt3).'_user_string');
		delete_option('atavism_worldcontent_db'.strval($cnt3).'_pass_string');
		$cnt3 = $cnt3 + 1;
	}
	delete_option('server_count');
	delete_option('subscription');
}
/* Define default option settings */
function atavism_add_defaults()
{
	global $options_array;
	$options = get_option('atavism_plugin_options');
	$cnt = 1;
    $options_array = array(
	    "atavism_recaptcha_apikey_priv_string"        => "YOUR_RECAPTHA_PRIVATE_KEY",
	    "atavism_recaptcha_apikey_pub_string"         => "YOUR_RECAPTCHA_PUBLIC_KEY",
	    "server_count"         => "1",
	    "Subscription"         => "1",
        "atavism_db_master_hostname_string"           => "localhost",
	    "atavism_db_master_port_string"               => "3306",
	    "atavism_db_master_schema_string"             => "master",
	    "atavism_db_master_user_string"               => "atavism",
	    "atavism_db_master_pass_string"               => "atavism",
    );
    foreach(range(1,$options['server_count']) as $i) {
        $array2 = array(
                "atavism_world". strval( $cnt ) ."_name_string" => "Local",
                "atavism_admin_db". strval( $cnt ) ."_hostname_string" => "localhost",
        		"atavism_admin_db". strval( $cnt ) ."_port_string"               => "3306",
        		"atavism_admin_db". strval( $cnt ) ."_schema_string"        => "admin",
	        	"atavism_admin_db". strval( $cnt ) ."_user_string"               => "atavism",
	        	"atavism_admin_db". strval( $cnt ) ."_pass_string"               => "atavism",
	        	"atavism_atavism_db". strval( $cnt ) ."_hostname_string"            => "localhost",
	        	"atavism_atavism_db". strval( $cnt ) ."_port_string"                => "3306",
	           	"atavism_atavism_db". strval( $cnt ) ."_schema_string"         => "atavism",
	        	"atavism_atavism_db". strval( $cnt ) ."_user_string"                => "atavism",
	        	"atavism_atavism_db". strval( $cnt ) ."_pass_string"                => "atavism",
	        	"atavism_worldcontent_db". strval( $cnt ) ."_hostname_string"    => "localhost",
	        	"atavism_worldcontent_db". strval( $cnt ) ."_port_string"        => "3306",
	        	"atavism_worldcontent_db". strval( $cnt ) ."_schema_string" => "world_content",
		        "atavism_worldcontent_db". strval( $cnt ) ."_user_string"        => "atavism",
                "atavism_worldcontent_db". strval( $cnt ) ."_pass_string"        => "atavism",
        );
	    $options_array = array_merge($options_array, $array2);
    $cnt = $cnt + 1;
	}
	foreach( $options_array as $k => $v )
	{
		update_options($k, $v);
	}
	return;
	update_option('atavism_plugin_options', $options_array);
}
?>
