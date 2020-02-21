<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends MY_Controller {

	public function index(){
		$data['header_right'] = array(
			array(
				'type' => 'button',
				'name' => 'Create New',
				'modal' => 'addCategory',
				'icon' => 'fa fa-plus-circle'
			)
		);

		$data['categories'] = $this->MY_Model->getRows('tbl_categories');


		$this->load_page('index', $data);
	}

	public function category(){
		if (!empty($this->input->post('category_id'))) {
			$this->updateCategory();
		}else {
			$this->addCategory();
		}
	}

	public function updateCategory(){
		$cat_id = $this->input->post('category_id');
		$cat_name = $this->input->post('category_name');

		$set = array(
			'name' => $cat_name,
		);

		$where = array(
			'category_id' => $cat_id
		);

		$check_name = $this->MY_Model->getRows('tbl_categories', array('where'=> array('name'=>$cat_name)), 'count');

		if ($check_name) {
			ajax_response('"'.$cat_name.'" is already been taken', 'error');
		}

		$update = $this->MY_Model->update('tbl_categories', $set, $where);

		if ($update) {
			ajax_response('Updated Successfully', 'success', array('type' => 'update'));
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function addCategory(){
		$cat_name = $this->input->post('category_name');

		$data = array(
			'name' => $cat_name,
			'date_added' => date('Y-m-d')
		);


		$check_name = $this->MY_Model->getRows('tbl_categories', array('where'=> array('name'=>$cat_name)), 'count');

		if ($check_name) {
			ajax_response('"'.$cat_name.'" is already been taken', 'error');
		}

		$insert = $this->MY_Model->insert('tbl_categories', $data);

		if ($insert) {
			ajax_response('Added '.$cat_name.' Successfully', 'success');
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function deletecategory(){
		$cat_id = $this->input->post('category_id');

		$where = array( 'category_id' => $cat_id );
		$set = array( 'status' => 1 );

		$update = $this->MY_Model->update('tbl_categories', $set, $where);

		if ($update) {
			ajax_response('Deleted Successfully', 'success');
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function categoryDataTable() {
        $limit = $this->input->post('length');
        $offset = $this->input->post('start');
        $search = $this->input->post('search');
        $order = $this->input->post('order');
        $draw = $this->input->post('draw');

		$select ="*";
        $join = array();
        $where = array('status' => 0);
        $group = array();
		$column_order = array('category_id','name','date_added');

        $list = $this->MY_Model->get_datatables('tbl_categories', $column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);
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
