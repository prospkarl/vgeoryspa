<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends MY_Controller {

	public function index(){
		$session_data = array('username','password','location', 'id', 'type');
		$this->session->unset_userdata($session_data);
		redirect(base_url('login'));
	}
}
