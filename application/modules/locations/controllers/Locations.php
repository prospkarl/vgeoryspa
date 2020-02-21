<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Locations extends MY_Controller {

	public function index(){
		$data['header_right'] = array(
			array(
				'type' => 'button',
				'name' => 'Create New',
				'modal' => 'addLocation',
				'icon' => 'fa fa-plus-circle'
			)
		);

		$this->load_page('index', $data);
	}

	public function location(){
		if (!empty($this->input->post('location_id'))) {
			$this->updateLocation();
		}else {
			$this->addLocation();
		}
	}

	public function updateLocation(){
		$loc_id = $this->input->post('location_id');
		$loc_name = $this->input->post('location_name');

		$set = array(
			'name' => $loc_name,
		);

		$where = array(
			'location_id' => $loc_id
		);

		$check_name = $this->MY_Model->getRows('tbl_locations', array('where'=> array('name'=>$loc_name)), 'count');

		if ($check_name) {
			ajax_response('"'.$loc_name.'" is already been taken', 'error');
		}

		$update = $this->MY_Model->update('tbl_locations', $set, $where);

		if ($update) {
			ajax_response('Updated Successfully', 'success', array('type' => 'update'));
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function addLocation(){
		$loc_name = $this->input->post('location_name');

		$data = array(
			'name' => $loc_name,
			'date_added' => date('Y-m-d')
		);


		$check_name = $this->MY_Model->getRows('tbl_locations', array('where'=> array('name'=>$loc_name)), 'count');

		if ($check_name) {
			ajax_response('"'.$loc_name.'" is already been taken', 'error');
		}

		$insert = $this->MY_Model->insert('tbl_locations', $data);

		if ($insert) {
			ajax_response('Added '.$loc_name.' Successfully', 'success');
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function deletelocation(){
		$loc_id = $this->input->post('location_id');

		$where = array( 'location_id' => $loc_id );
		$set = array( 'status' => 1 );

		$update = $this->MY_Model->update('tbl_locations', $set, $where);

		if ($update) {
			ajax_response('Deleted Successfully', 'success');
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function locationDataTable() {
        $limit = $this->input->post('length');
        $offset = $this->input->post('start');
        $search = $this->input->post('search');
        $order = $this->input->post('order');
        $draw = $this->input->post('draw');

		$select ="*";
        $join = array();
        $where = array('status' => 0);
        $group = array();
		$column_order = array('location_id','name','date_added');

        $list = $this->MY_Model->get_datatables('tbl_locations', $column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);
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
