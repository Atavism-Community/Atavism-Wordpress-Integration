<?php
/*  Atavism Support Widget  */

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

class SupportWidget extends WP_Widget
{
  /**
    * Register widget with WordPress.
    */
  function SupportWidget()
  {
    $widget_ops = array('classname' => 'SupportWidget', 'description' => 'Allows users a link to the support ticket system' );
    $this->WP_Widget('SupportWidget', 'Support Ticket Link', $widget_ops);
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
	if(!isset($before_widget)){
		$before_widget = '';
	}
    echo $before_widget;
    /* Title of widget (before and after defined by themes). */
	  $user = wp_get_current_user();
	  if ( ! $user->exists() ) {
	       return;
	  }else{
		  global $wpdb;
		  ?>
        <div>
          <hr>
          <div class="uk-text-center"><h3>Support</h3>
          <form action="" method="post">
		  <?php
		  $table_name = $wpdb->prefix . "support";
		  if(!current_user_can('administrator')) {
              $query = $wpdb->get_results("SELECT status 
                              FROM $table_name 
                              WHERE author = '$user->ID'
                              AND close = '0'");
          }else{
              $query = $wpdb->get_results("SELECT status 
                              FROM $table_name 
                              WHERE close = '0'");
          }?>

		  <?php if(count($query) > 0): ?>
                    <?php if ( count( $query ) == 1 ) {
                        echo '<div class= "uk-text-center">You have 1 open ticket.</div>';
                    }else{
                        echo '<div class= "uk-text-center">You have '.count( $query ).' open tickets.</div>';
                    }
                    ?>
			        <div>
			            <a href="/wp-content/plugins/atavism-integration/tickets.php" class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom" uk-toggle="target: /wp-content/plugins/atavism-integration/tickets.php"><i class="fas fa-star"></i>Handle Tickets</a>
                        <a href="/wp-content/plugins/atavism-integration/tickets.php?new" class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom" uk-toggle="target: /wp-content/plugins/atavism-integration/tickets.php?new"><i class="fas fa-star"></i>New Ticket</a>
                    </div>
                    </form>
                    </div>
          <?php else :?>

            <div class= "uk-text-center">You have no open tickets.</div>
                <div>
                    <a href="/wp-content/plugins/atavism-integration/tickets.php?new" class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom" uk-toggle="target: /wp-content/plugins/atavism-integration/tickets.php?new"><i class="fas fa-star"></i>New Ticket</a>
                </div>
                </form>
                </div>
          <?php endif ?>
<?php
	  }
      if(!isset($after_widget)){
          $after_widget = '';
      }
      echo $after_widget;
  }

}
add_action( 'widgets_init', function(){
    register_widget( 'SupportWidget' );
});