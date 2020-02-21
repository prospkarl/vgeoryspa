<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends MY_Controller {

	function __construct(){
		 parent::__construct();
		 $this->load->helper(array('form', 'url'));
		 $this->load->library('form_validation');
	}

	public function index(){
		$this->login_view('login');
	}

   public function validate(){
		$data = array(); // init array result sender
		$this->form_validation->set_rules('username', 'Username', 'required',  array('required' => 'You must provide a %s.'));
		$this->form_validation->set_rules('password', 'Password', 'required',  array('required' => 'You must provide a %s.'));

		if ($this->form_validation->run() == FALSE){
			array_push($data, array("success" => false));
			array_push($data, array("msg" => validation_errors()));
		}else{
			$params['join'] = array(
				'tbl_user_details' => 'tbl_user_details.user_id = tbl_users.user_id'
			);

			$params['where'] = array(
				"tbl_users.username" => $this->input->post('username')
			);

			$result = $this->MY_Model->getRows('tbl_users',$params, 'row_array');

			if (is_array($result)){
				if ($this->input->post('password') == $result['password']) {
					if ($result['user_status'] == 1) {
						$response = array('success' => true);

						$accounts = $this->session->userdata('accounts');
						if ($result['user_type'] == '2') {
							$accounts[$result['username']] = ucwords($result['fname']);
						}

						$userCredentials = array(
							"username" => $result['username'],
							"id" => $result['user_id'],
							"type" => $result['user_type'],
							"accounts" => $accounts
						);

						if ($result['user_type'] == 2) {
							$response['is_sales'] = true;
						}else {
							$userCredentials['location'] = 1;
						}

						$this->session->set_userdata($userCredentials);
					}else{
						$response = array(
							'success' => false,
							'msg' => 'Account Disabled'
						);
					}
				}else {
					$response = array(
						'success' => false,
						'msg' => 'Password Incorrect'
					);
				}
			}else{
				$response = array(
					'success' => false,
					'msg' => 'Your login credentials are incorrect please try again'
				);
			}
	   }

       echo json_encode($response);
   }

}
