<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends MY_Controller {
	function __construct(){
		 parent::__construct();
	}

    public function autocomplete() {
        $inputted = str_replace('\'', '', $this->input->post('input'));
        $this->db->distinct();
        $this->db->limit(5);
        $params['select'] = '*';

		if (strpos($inputted, ' ')) {
			$keywords = explode(' ', $inputted);

			foreach ($keywords as $key) {
				if (!empty($key)) {
					$params['locate'][] = array(
						'tbl_products.name' => $key
					);
				}
			}
		}else {
			$params['or_like'] = array(
				'tbl_products.barcode' => $inputted,
				'tbl_products.sku' => $inputted,
				'tbl_products.name' => $inputted,
			);
		}

		$params['where'] = array('status' => 1);

        $res = $this->MY_Model->getRows('tbl_products', $params);
        echo json_encode($res);
    }

	public function getiteminfo($product_id = '') {
		$product_info_options['select'] = 'name, price';

		$product_info_options['where'] = array(
			'product_id' => $product_id
		);

		$product_info = $this->MY_Model->getRows('tbl_products', $product_info_options, 'row_array');

		$return['product_name'] = $product_info['name'];
		$return['price'] = $product_info['price'];


		if ($this->input->post('location')) {
			$search_location = $this->input->post('location');
		}else {
			$search_location = $this->session->location;
		}

		$stock_info_options['where'] = array(
			'location' => $search_location,
			'product_id' => $product_id
		);

		$stock_info = $this->MY_Model->getRows('tbl_stocks', $stock_info_options, 'row_array');

		$return['current_qty'] = $stock_info['qty'] ? $stock_info['qty'] : '0';

		echo json_encode($return);
	}

	public function changepassword() {
		$post = $this->input->post();

		if ($post['password'] != $post['con_password']) {
			ajax_response('Please confirm your password', 'warning');
		}

		$required = array('password', 'con_password');
		$errors = check_empty_fields($required, $post);

		if (!empty($errors)) {
			ajax_response('Please input your password', 'warning');
		}

		$set = array(
			'password' => $post['password']
		);

		$where = array(
			'user_id' => $this->session->id
		);

		$update = $this->MY_Model->update('tbl_users', $set, $where);

		if ($update) {
			ajax_response('Successfully changed password!', 'success');
		}else {
			ajax_response('Something went wrong!', 'danger');
		}
	}


	public function switch_account() {
		$post = $this->input->post();

		$check_user_options['where'] = array(
			'username' => $post['username']
		);
		$check_user = $this->MY_Model->getRows('tbl_users', $check_user_options, 'row_array');

		if ($check_user['password'] == $post['password']) {
			$set_session = array(
				'username' => $check_user['username'],
				'id' => $check_user['user_id'],
				'type' => $check_user['user_type'],
			);

			$this->session->set_userdata($set_session);

			ajax_response('success', 'success');
		}else {
			ajax_response('Incorrect password!', 'error');
		}
	}

	public function getlogs() {
		$options['select'] = '
			tbl_logs.content,
			tbl_logs.date as date,
			CONCAT(tbl_user_details.fname , " " , tbl_user_details.lname) as logged_by,
		';

		$options['where'] = array(
			'referrer_id' => $this->input->post('referrer_id'),
			'table_name' => $this->input->post('tablename'),
		);
		$options['join'] = array(
			'tbl_user_details' => 'tbl_logs.logged_by = tbl_user_details.user_id'
		);

		$logs = $this->MY_Model->getRows('tbl_logs', $options);

		echo json_encode($logs);
	}
}
