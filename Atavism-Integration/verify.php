<?php
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/wp-load.php');
$userLogin = $_POST['user'];
//$userLogin = $_REQUEST['user'];
$password = $_POST['password'];
//$password = $_REQUEST['password'];
checkCustomer($userLogin, $password);
function checkCustomer($userLogin, $password) {
	error_log("checking customer: " .$userLogin);
	$options = get_option('atavism_plugin_options');
	$mysqli_conn = new mysqli(
		$options[ 'atavism_master_db_hostname_string' ],
		$options[ 'atavism_master_db_user_string' ],
		$options[ 'atavism_master_db_pass_string' ],
		$options[ 'atavism_master_db_schema_string' ],
		$options[ 'atavism_master_db_port_string' ]
	);

	if (username_exists($userLogin)) {
		$integration_options = get_option('atavism_plugin_options');
		$user = get_user_by( 'login', $userLogin );
		$id = strval($user->ID);
		//echo '$id is'.$id;exit();
		if ( $user && wp_check_password( $password, $user->data->user_pass, $id) ) {
			if($integration_options['subscription'] == '2'){
				global $wpdb;
				$table_name = $wpdb->prefix . "xsolla_user_subscription_status";
				$query = $wpdb->get_results( "SELECT status 
                              FROM $table_name 
                              WHERE wpid = '$id'
                              AND status = 'Subscribed'" );
				if ( count( $query ) > 0 ) {
					$sql    = "SELECT status FROM account WHERE id = '$id'";
					$result = $mysqli_conn->query( $sql );
					foreach ( $result as $data ) {
						if ( empty( $data['status'] ) ) {
							echo "-1, user banned ";
						} else {
							echo $user->ID;
						}
					}
				} else {
					echo "-1, no active subscription";
				}
			}else{
				$sql    = "SELECT status FROM account WHERE id = '$id'";
				$result = $mysqli_conn->query( $sql );
				foreach ( $result as $data ) {

					if ( empty( $data['status'] ) ) {
						echo "-1, user banned ";
					} else {
						echo $user->ID;
					}
				}
			}
		} else {
			echo "-1, wrong pass ";
		}
	}else{
		echo "-1, user not found ";
	}
}
