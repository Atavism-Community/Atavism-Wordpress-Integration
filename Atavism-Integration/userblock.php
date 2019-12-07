
<?php

class user_block_ajax_call_back {
	public function __construct() {
		add_action('wp_ajax_nopriv_dwul_action_callback', array($this, 'user_login_callback'));
		add_action( 'wp_login',   array( $this, 'dwul_disable_user_call_back'), 10, 2 );
		add_filter( 'login_message',array( $this, 'dwul_disable_user_login_message'));
	}
	public function user_login_callback() {

		global $wpdb;
		global $disableemail;
		$exitingarray = array();
		$disableemail = $_REQUEST['useremail'];
		$table_name = $wpdb->prefix . dwul_disable_user_email;
		$exitingusertbl =  $wpdb->prefix .users;
		$exitinguserquery = "SELECT user_email FROM $exitingusertbl";
		$getexiting = $wpdb->get_col($exitinguserquery);

		$user = get_user_by( 'email', $disableemail );


		if($user->roles[0] == 'administrator'){

			$successresponse = "11";

		}else{



			foreach ($getexiting as $exitinguser){

				$exitingarray[] = $exitinguser;

			}
			if(!in_array($disableemail, $exitingarray)){

				$successresponse = "12";

			}else{


				$insertdata = $wpdb->insert($table_name, array('useremail' => $disableemail), array('%s'));
				if($insertdata){

					$successresponse =  "1";

				}else{

					$successresponse =  "15";
				}
			}
		}
		echo $successresponse;
		die();
	}
}

