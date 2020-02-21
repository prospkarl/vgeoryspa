<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brands extends MY_Controller {

	public function index(){
		$data['header_right'] = array(
			array(
				'type' => 'button',
				'name' => 'Create New',
				'modal' => 'addBrand',
				'icon' => 'fa fa-plus-circle'
			)
		);

		$this->load_page('index', $data);
	}

	public function brand(){
		if (!empty($this->input->post('brand_id'))) {
			$this->updateBrand();
		}else {
			$this->addBrand();
		}
	}

	public function updateBrand(){
		$brand_id = $this->input->post('brand_id');
		$brand_name = $this->input->post('brand_name');

		$set = array(
			'name' => $brand_name,
		);

		$where = array(
			'brand_id' => $brand_id
		);

		$check_name = $this->MY_Model->getRows('tbl_brands', array('where'=> array('name'=>$brand_name)), 'count');

		if ($check_name) {
			ajax_response('"'.$brand_name.'" is already been taken', 'error');
		}

		$update = $this->MY_Model->update('tbl_brands', $set, $where);

		if ($update) {
			ajax_response('Updated Successfully', 'success', array('type' => 'update'));
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function addBrand(){
		$brand_name = $this->input->post('brand_name');

		$data = array(
			'name' => $brand_name,
			'date_added' => date('Y-m-d')
		);


		$check_name = $this->MY_Model->getRows('tbl_brands', array('where'=> array('name'=>$brand_name)), 'count');

		if ($check_name) {
			ajax_response('"'.$brand_name.'" is already been taken', 'error');
		}

		$insert = $this->MY_Model->insert('tbl_brands', $data);

		if ($insert) {
			ajax_response('Added '.$brand_name.' Successfully', 'success');
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function deleteBrand(){
		$brand_id = $this->input->post('brand_id');

		$where = array( 'brand_id' => $brand_id );
		$set = array( 'status' => 1 );

		$update = $this->MY_Model->update('tbl_brands', $set, $where);

		if ($update) {
			ajax_response('Deleted Successfully', 'success');
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function brandDataTable() {
        $limit = $this->input->post('length');
        $offset = $this->input->post('start');
        $search = $this->input->post('search');
        $order = $this->input->post('order');
        $draw = $this->input->post('draw');

		$select ="*";
        $join = array();
        $where = array('status' => 0);
        $group = array();
		$column_order = array('brand_id','name','date_added');

        $list = $this->MY_Model->get_datatables('tbl_brands', $column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);
        $output = array(
               "draw" => $draw,
               "recordsTotal" => $list['count_all'],
               "recordsFiltered" => $list['count'],
               "data" => $list['data'],
               "limit" => $limit,
               "offset" => $offset
        );

        echo json_encode($output);
    }
}
