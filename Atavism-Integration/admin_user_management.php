<?php
$cnt = get_option('atavism_selected_server');
/*  Copyright 2019  Scott Meadows  (email : smeadows0155@yahoo.com)

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
register_activation_hook(__FILE__, 'atavism_user_add_defaults');
register_deactivation_hook( __FILE__, 'atavism_user_remove' );
add_action('admin_menu', 'atavism_admin_add_user_page');
add_action('admin_init', 'atavism_admin_user_init');
/* Hook the admin options page */
function atavism_admin_add_user_page()
{
	$options = get_option('atavism_plugin_options');
	add_users_page(
		'Atavism User Management',        // Page title. (required)
        'Atavism User Management',        // Menu title. (required)
        'manage_options',                // Capability. (required)
        __FILE__,                    // Menu slug. (required)
        'atavism_user_management_page' // Callback function. (optional)
    );
}
/* Display the admin options page */
function atavism_user_management_page()
{
?>
<div class ="wrap">
    <div class="icon32" id="icon-plugins"><br></div>
    <h2>Atavism User Management</h2>
		<?php do_settings_sections(__FILE__); ?>
        <br>
</div>
	<?php
	}
/* Add the admin settings */
function atavism_admin_user_init()
{
	register_setting(
		'atavism_user_management',		// Settings page
		'atavism_user_management'		// Option name
	);
	add_settings_section(
		'plugin_main',			// Id
		'',		// Title
		'user_plugin_section_text',		// Callback function
		 __FILE__			// Page
	);
}
function set_atavism_role($role, $id, $cnt)
{
        if($role == '10'){
            $role = '0';
        }
		foreach ( $_REQUEST['users'] as $i ) {
			$sql = "UPDATE account SET status=$role WHERE id=$id";
			conn($cnt)->query( $sql );
			connmaster()->query( $sql );
			//todo this line results in user being able to login if last action was to unban on a world, even if there are other entries.
		}
	    connmaster()->close();
		conn($cnt)->close();
        //todo ban globally via master.
}
function user_plugin_section_text() {
	global $cnt;
	$postnames = array_keys($_POST);
	if($postnames[4] == "s"){
		$postnames = null;
	}
	if (isset($_REQUEST[ 'changeit2' ] ) ) {
	    if ( isset( $_REQUEST[ 'server' ] ) ) {
			update_option('atavism_selected_server', $_REQUEST[ 'server' ]);
		    $redirect = 'users.php?page=Atavism-Integration%2Fadmin_user_management.php';
		    $update   = '';
		    wp_redirect( add_query_arg( 'update', $update, $redirect ) );
		}
	}
	function conn( $cnt ) {
		$options     = get_option( 'atavism_plugin_options' );
		foreach ( range( 1, $options['server_count'] ) as $i ) {
			$connarray[] = array(
				'host'   => $options[ 'atavism_admin_db' . $i . '_hostname_string' ],
				'user'   => $options[ 'atavism_admin_db' . $i . '_user_string' ],
				'pass'   => $options[ 'atavism_admin_db' . $i . '_pass_string' ],
				'schema' => $options[ 'atavism_admin_db' . $i . '_schema_string' ],
				'port'   => $options[ 'atavism_admin_db' . $i . '_port_string' ]
			);
		}
		return new mysqli(
			$connarray[ $cnt - 1 ]['host'],
			$connarray[ $cnt - 1 ]['user'],
			$connarray[ $cnt - 1 ]['pass'],
			$connarray[ $cnt - 1 ]['schema'],
			$connarray[ $cnt - 1 ]['port']
		);
	}
	function connmaster() {
		$options     = get_option( 'atavism_plugin_options' );
		return new mysqli(
			$options[ 'atavism_master_db_hostname_string' ],
			$options[ 'atavism_master_db_user_string' ],
			$options[ 'atavism_master_db_pass_string' ],
			$options[ 'atavism_master_db_schema_string' ],
			$options[ 'atavism_master_db_port_string' ]
		);
	}
	if ( empty( $limit ) ) {
		$limit = 7;
	}
	function resultall($cnt) {
		$sqlall    = "SELECT status, username, id, created FROM account";
		$resultall = conn( $cnt )->query( $sqlall );
		return $resultall;
	}
	function result($cnt) {
		$postnames = array_keys($_POST);
		if($postnames[1] == "s"){
			$postnames = null;
		}
		$limit = 7;
		if ( ! empty( $_REQUEST['limit'] ) ) {
			$limit = $_POST['limit'];
		}
		$page  = 1;
		if(empty($offset)) {
			$offset = ( $page - 1 ) * $limit;
		}
		$sql    = "SELECT status, username, id, created FROM account LIMIT $limit";
		$total     = mysqli_num_rows( resultall($cnt) );
		$pages     = ceil( $total / $limit );
		$if = false;
		if ( empty( $_REQUEST['paginate1'] ) && empty( $_REQUEST['s'] ) ) {
			$sql    = "SELECT status, username, id, created FROM account LIMIT $limit";
			$if = true;
		}
		if ( isset( $_REQUEST['paginate1'] ) ) {
			$if = true;
			if ( $_REQUEST['paginate1'] == '«' ) {
				$page   = 1;
				$sql    = "SELECT status, username, id, created FROM account LIMIT $limit";

			}
			if ( $_REQUEST['paginate1'] == '»' ) {
				$page   = $pages;
				$offset = ( $page - 1 ) * $limit;
				$sql    = "SELECT status, username, id, created FROM account LIMIT $offset, $limit";
			}
		}
		if ( isset( $_REQUEST['paginatenext'] ) ) {
			$if = true;
			$page   = $_POST['paginatenext'];
			$offset = ( $page - 1 ) * $limit;
			//$offset = ceil( $page / $limit ) + ceil( $page / $limit );
			$sql    = "SELECT status, username, id, created FROM account LIMIT $offset, $limit";
		}
		if ( isset( $_REQUEST['paginateprevious'] ) ) {
			$if = true;
			$page = $_POST['paginateprevious'];
			$offset   = ( $page - 1 ) * $limit - 1;
			if ($offset < 1){
			    $offset = 0;
            }
			$sql    = "SELECT status, username, id, created FROM account LIMIT $offset, $limit";
		}
		if ( ! $_REQUEST['s'] == '' ) {
			$s      = $_POST['s'];
			//var_dump($s);exit();
			$sql    = "SELECT status, username, id, created FROM account WHERE username LIKE '%$s%'";
			$if = true;
		}
		if ( ! is_null( $postnames[1] ) ) {
			$sortby = (int) $postnames[1];
			$if = true;
			if ( $postnames[1] == 'All' ) {
				$sql    = "SELECT status, username, id, created FROM account";
			} else if ( $postnames[1] == '10' ) {
				$sql    = "SELECT status, username, id, created FROM account WHERE status = '0'";
			} else {
				$sql    = "SELECT status, username, id, created FROM account WHERE status = '$sortby'";
			}
		}
		if($if == false){
		    $sql      = "SELECT status, username, id, created FROM account LIMIT $limit";
	    }
	    $result   = conn( $cnt )->query( $sql );
		$totalresult  = resultall( $cnt );
		$total  = mysqli_num_rows( $totalresult );
		$end      = min( ( $offset + $limit ), $total );
		$topuser  = $offset + $page + $limit;
		$topuser2 = $end;
		if ( $topuser2 > $total ) {
			$topuser2 = $total;
		}
		return array( $topuser, $topuser2, $limit, $page, $total, $result, $offset, $end, $pages);
	}
	$options = get_option( 'atavism_plugin_options' );
	$search = 's'; //works
	$page = result($cnt)[3];
    $topuser2 = result($cnt)[1];
    $total = result($cnt)[4];
	$offset = result($cnt)[6];
    $pages = result($cnt)[8];

	if (isset($_REQUEST[ 'changeit' ] ) ) {
		if ( isset( $_REQUEST[ 'new_role' ] ) ) {
			if ( ! current_user_can( 'promote_users' ) ) {
				wp_die( __( 'Sorry, you are not allowed to edit this user.' ), 403 );
			}
			if ( isset( $_REQUEST[ 'users' ] ) ) {
			    $userids = $_REQUEST['users'];
				$role    = $_REQUEST['new_role'];

			}
			//var_dump($userids);exit();
			if ( isset( $role ) ) {
				foreach ( $userids as $id ) {

					set_atavism_role( $role, $id, $cnt );
				}
			}
			$update   = '';
			wp_redirect( add_query_arg( array(
			        'update' => $update,
                    'pageno' => $page,
                    'topuser2' => $topuser2,
                    'total' => $total,
                    'offset' => $offset,
                    'pages' => $pages,
                    'result' => result($cnt)
                )));
		}
	}
	$start2 = $offset + 1;
	$all      = 0;
	$admin    = 0;
	$standard = 0;
	$mod      = 0;
	$banned   = 0;
	$searchresult = mysqli_num_rows(result($cnt)[5]);
	foreach ( resultall($cnt) as $data ) {
		$all = $all + 1;
		if ( empty( $data['status'] ) ) {
			$role   = 'Banned';
			$banned = $banned + 1;
		}
		if ( $data['status'] == 1 ) {
			$role     = 'Standard User';
			$standard = $standard + 1;
		}
		if ( $data['status'] == 3 ) {
			$role = 'Mod';
			$mod  = $mod + 1;
		}
		if ( $data['status'] == 5 ) {
			$role  = 'Administrator';
			$admin = $admin + 1;
		}
	}
    ?>
        <form action="" method="post">
            <div class="wrap">
	            <?php if($options['server_count'] > 1):?>
                    <select name="server" id="server">
			            <?php foreach ( range( 1, $options['server_count'] ) as $i ) {?>
                            <option value="<?= $i ?>"><?php echo $options[ "atavism_world" . strval( $i ) . "_name_string" ]?></option>
			            <?php } ?>
                    </select>
                    <input type="submit" name="changeit2" id="changeit2" class="button" value="Change">
	            <?php endif ?>
                <hr class="wp-header-end">
                <hr class="wp-header-end">
                <h4 class="screen-reader-text">Filter users list</h4>
                <ul class="subsubsub">
                    <input type="submit" class="button" value="All (<?php echo '' . $all; ?>)" Name="All"></span>
                    <input type="submit" class="button" value="Admin (<?php echo '' . $admin; ?>)" Name="5"></span>
                    <input type="submit" class="button" value="Standard (<?php echo '' . $standard; ?>)" Name="1"></span>
                    <input type="submit" class="button" value="Moderator (<?php echo '' . $mod; ?>)" Name="3"></span>
                    <input type="submit" class="button" value="Banned (<?php echo '' . $banned; ?>)" Name="10"></span>
                </ul>
                <br>
                <p class="search-box">
                    <label class="screen-reader-text" for="user-search-input">Search Users:</label>
                    <input type="search" id="user-search-input" name="s" value="">
                    <input type="submit" id="search-submit" class="button" value="Search Users"></p>
                <div class="tablenav top">
                    <div class="alignleft actions">
                        <label class="screen-reader-text" for="new_role">Change role to…</label>
                        <select name="new_role" id="new_role">
                            <option value="">Change role to…</option>
                            <option value="10">Banned</option>
                            <option value="1">Standard Player</option>
                            <option value="3">Mod</option>
                            <option value="5">Administrator</option>
                        </select>
                        <input type="submit" name="changeit" id="changeit" class="button" value="Change"></div>
		<?php if ( $_REQUEST[$search] == '' ): ?>
                    <span class="tablenav-pages one-page"><span class="displaying-num">Displaying <?php echo $start2 . ' to ' . $topuser2 . ' of ' . $total . ' users' ?></span>
                    <input type="submit" id="pagination-first" name="paginate1" class="button" value="«">
                    <input type="submit" name="paginateprevious" id="pagination" class="button"
                           value="<?php if ( $page == 1 ) {
                                   echo( $page );
                               } else {
                                   echo( $page - 1 );
                               }?>">
                    <span class="tablenav-paging-text">Page <?php echo $page ?> of <span class="total-pages"><?php echo $pages ?></span>
                        <input type="submit" id="pagination" class="button" name="paginatenext" value="<?php if ( $page == $pages ) {
                                echo $page;
                            } else {
	                            echo( $page + 1 );
                            }
                            echo '">
                            <input type="submit" name="paginate1" id="pagination-last" class="button" value="»"></span>';?>
        <?php endif; ?>

            <br class=" clear">
                </div>

                <h2><?php echo $options[ "atavism_world" . strval( $cnt ) . "_name_string" ]; ?></h2>
                <h2 class="screen-reader-text">Users list</h2>
                <table class="wp-list-table widefat fixed striped users">
                    <thead>
                    <tr>
                        <td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                            <input id="cb-select-all-1" type="checkbox"></td>
                        <th scope="col" id="username" class="manage-column column-username column-primary">Username</th>
                        <th scope="col" id="name" class="manage-column column-name">Name</th>
                        <th scope="col" id="email" class="manage-column column-email">Email</th>
                        <th scope="col" id="role" class="manage-column column-role">Role</th>
                        <th scope="col" id="memstart" class="manage-column column-memstart">Created</th>
                        <th scope="col" id="posts" class="manage-column column-posts num">Posts</th>
                    </tr>
                    </thead>
                    <tbody id="the-list" data-wp-lists="list:user">
		<?php foreach ( result($cnt)[5] as $data ) {
			$all = $all + 1;
			if ( $data['status'] == 1 ) {
			    $role = 'Standard User';
			}
			if ( $data['status'] == 5 ) {
			    $role = 'Administrator';
			}
			if ( $data['status'] == 0 ) {
			    $role = 'Banned';
			}
			if ( $data['status'] == 3 ) {
			    $role = 'Mod';
			}
			$user     = get_user_by( 'login', $data['username'] );
			$memstart = $data['created'];
			$posts    = count_user_posts( $user->ID );
			$avatar32 = get_avatar( $user->user_email, 32 );
			echo( "<tr id=''user_'.$data[id].''><th scope='row' class='check-column'><label class='screen-reader-text' for='user_$user->ID'>Select '.$data[username].'</label><input type='checkbox' name='users[]'' id='user_$user->ID' class='$role' value='$data[id]'></th>
                <td class='username column-username has-row-actions column-primary' data-colname='$data[username].'><img alt='' src=$avatar32 <strong><a href='/wp-admin/user-edit.php?user_id=2&amp;wp_http_referer=%2Fwp-admin%2Fusers.php'>$data[username]</a></strong><br><div class='row-actions'><span class='edit'><a href='/wp-admin/user-edit.php?user_id=$user->ID&amp;wp_http_referer=%2Fwp-admin%2Fusers.php'>Edit</a> </span> | </span><span class='view'><a href='/author/$data[username]/' aria-label='View posts by $data[username]'>View</a></span></div><button type='button' class='toggle-row'><span class='screen-reader-text'>Show more details</span></button></td>
                <td class='name column-name' data-colname='Name'>$user->first_name $user->last_name</td>
                <td class='email column-email' data-colname='Email'><a href='mailto: $user->user_email'>$user->user_email</a></td>
                <td class='role column-role' data-colname='Role'>$role</td>
                <td class='memstart column-memstart' data-colname='memstart'>$memstart</td>
                <td class='posts column-posts num' data-colname='Posts'>$posts</td></tr>	
                </tbody>" );
		}?>
                </table>
                <div class="tablenav bottom">
                    <div class="alignleft actions">
                        <?php if ( $_REQUEST[$search] == '' ) :?>
                            <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo 'Displaying ' . $start2 . ' to ' . $topuser2 . ' of ' . $total . ' users' ?></span>
                            </div>
                        <?php else :?>
                            <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo $searchresult . ' users match the term: "'.$_REQUEST[$search].'"' ?></span>
                            </div>
                        <?php endif ?>
                    </div>
                    <br class="clear">
                </div>
                <br class="clear">
                </form>
        </div>
	<?php
    conn( $cnt )->close();
}




