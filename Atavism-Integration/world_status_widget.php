<?php
/*  Atavism World Status Widget  */
/*  Copyright 2018 Scott Meadows  (email : smeadows0155@yahoo.com)

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
class WorldStatusWidget extends WP_Widget
{
  /**
    * Register widget with WordPress.
    */
  function WorldStatusWidget()
  {
    $widget_ops = array('classname' => 'WorldStatusWidget', 'description' => 'Displays the status of an Atavism world server' );
    $this->WP_Widget('WorldStatusWidget', 'Atavism Server Status', $widget_ops);
  }
  /**
    * Back-end widget form.
    *
    * @see WP_Widget::form()
    *
    * @param array $instance Previously saved values from database.
    */
  /**
    * Sanitize widget form values as they are saved.
    *
    * @see WP_Widget::update()
    *
    * @param array $new_instance Values just sent to be saved.
    * @param array $old_instance Previously saved values from database.
    *
    * @return array Updated safe values to be saved.
    */
  /**
    * Front-end display of widget.
    *
    * @see WP_Widget::widget()
    *
    * @param array $args     Widget arguments.
    * @param array $instance Saved values from database.
    */
  function widget($args,$instance)
  {
    extract($args, EXTR_SKIP);
    /* User-selected values from options */
    /* Before widget (defined by theme) */
    echo $before_widget;
    /* Title of widget (before and after defined by themes). */
    $options = get_option('atavism_plugin_options');
	$cnt = 1;
	echo '<hr>';

	foreach(range(1,$options['server_count']) as $i) {
		//check Auth Status
		$worldup = false;
		$authup = false;

		$conn = new mysqli( $options['atavism_admin_db' . strval( $cnt ) .'_hostname_string'], $options['atavism_admin_db' . strval( $cnt ) .'_user_string'], $options['atavism_admin_db' . strval( $cnt ) .'_pass_string'], $options['atavism_admin_db' . strval( $cnt ) .'_schema_string'], $options['atavism_admin_db' . strval( $cnt ) .'_port_string']);
		if ( $conn->connect_error ) {
		} else {
			$sqlauth  = "SELECT status FROM server_status WHERE server = 'auth'";
			$sqlworld = "SELECT status FROM server_status WHERE server = 'world'";
			$result   = $conn->query( $sqlauth );
			$row      = $result->fetch_assoc();
			if ( $row["status"] == '1' ) {
				$authup = true;
			} else {
				$authup = false;
			}
			$result   = $conn->query( $sqlworld );
			$row      = $result->fetch_assoc();
			if ( $row["status"] == '1' ) {
				$worldup = true;
			} else {
				$worldup = false;
			}
			$conn->close();
			if (!empty($options[ 'atavism_admin_db'.strval($cnt).'_hostname_string' ])) {
				if ( $authup == true ) {
					echo $options[ 'atavism_world' . strval( $cnt ) . '_name_string' ] . ':<br> Auth <img width="32" height="32" style=vertical-align:middle title="Auth Server Online" name="online" alt="online" src="' . plugins_url( 'images/online.png', __FILE__ ) . '" border="0" />';

				} else {
					echo $options[ 'atavism_world' . strval( $cnt ) . '_name_string' ] . ':<br> Auth <img width="32" height="32" style=vertical-align:middle title="Auth Server Offine" name="offline" alt="online" src="' . plugins_url( 'images/offline.png', __FILE__ ) . '" border="0" />';
				}
			}
	    if ( $worldup == true ) {
		    echo ' World <img width="32" height="32" style=vertical-align:middle title="World Server Online" name="online" alt="online" src="' . plugins_url( 'images/online.png', __FILE__ ) . '" border="0" /><br>';
	    }
	    if ( $worldup != true ) {
		    echo ' World <img width="32" height="32" style=vertical-align:middle title="Auth Server Offine" name="offline" alt="online" src="' . plugins_url( 'images/offline.png', __FILE__ ) . '" border="0" /><br>';
	    }
	    $cnt = $cnt + 1;
		}
    }
    /* End ping code. */
    echo $after_widget;
  }
}
add_action( 'widgets_init', create_function('', 'return register_widget("WorldStatusWidget");') );
