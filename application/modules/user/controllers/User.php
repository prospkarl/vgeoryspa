<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller{
   function __construct() {
      parent::__construct();
      $this->load->helper(array('form', 'url'));
      $this->load->library('form_validation');
   }

   public function index()  {
      $data['header_right'] = array(
          array(
              'type' => 'button',
              'name' => 'Create New',
              'modal' => 'addUser',
              'icon' => 'fa fa-plus-circle',
              'class' => 'btn btn-info create-new'
          ),
      );

      $data['location'] = $this->MY_Model->getRows('tbl_locations');

      $this->load_page('user', $data);
   }

   public function getUser(){
       $params['where'] = array(
             "tbl_user_details.user_id" => $this->input->post('id'),
        );
        $params['join']=array(
            "tbl_users" => "tbl_users.user_id = tbl_user_details.user_id"
        );

        $result = $this->MY_Model->getRows('tbl_user_details',$params);
        echo json_encode($result[0]);
   }

   public function addUser()   {
      $pos = $this->input->post('position');
      $data1 = array(
         "username" => $this->input->post('username'),
         "password" => $this->input->post('password'),
         "user_type" => ($pos == "Sales") ? 2 : 0,
         "user_status" => 1,
      );

      $user_id = $this->MY_Model->insert('tbl_users', $data1);

      $data = array(
         "user_id" => $user_id,
         "fname" => $this->input->post('fname'),
         "lname" => $this->input->post('lname'),
         "gender" => $this->input->post('gender'),
         "birthdate" => $this->input->post('bday'),
         "position" => $pos,
         "date_added" => date("Y-m-d")
      );

      $result1 = $this->MY_Model->insert('tbl_user_details', $data);

      ajax_response('Added Successfully', 'success');
   }

   public function removeUsers(){
       $where = array(
           "user_id" => $this->input->post('id')
       );
       $data = array(
           "trashed" => 1
       );
       $update = $this->MY_Model->update('tbl_users', $data, $where);

       ajax_response('Successfully Deleted!', 'success');
   }

   public function updUser() {
       $data = array(
          "fname" => $this->input->post('fname'),
          "lname" => $this->input->post('lname'),
          "gender" => $this->input->post('gender'),
          "birthdate" => $this->input->post('bday'),
          "position" => $this->input->post('position'),
       );

       $where = array(
           "user_id" => $this->input->post('user_id')
       );

       $data_credentials = array(
           "username" => $this->input->post('username'),
           "password" => $this->input->post('password'),
           "user_type" => ($this->input->post('position') == 'Sales') ? 2 : 0
       );
       $update1 = $this->MY_Model->update('tbl_user_details', $data, $where);
       $update2 = $this->MY_Model->update('tbl_users', $data_credentials, $where);

       ajax_response('Updated Successfully', 'success');
   }

   public function userDataTable(){
       $limit = $this->input->post('length');
       $offset = $this->input->post('start');
       $search = $this->input->post('search');
       $order = $this->input->post('order');
       $draw = $this->input->post('draw');
       $column_order = array('tbl_user_details.user_id','fname','tbl_user_details.date_added','gender');
       $join = array(
             "tbl_user_details"  => "tbl_user_details.user_id = tbl_users.user_id",

       );
       $where = array('tbl_users.trashed' => 0);
       $select ="*, tbl_user_details.date_added";
       $group = array();
       $list = $this->MY_Model->get_datatables('tbl_users',$column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);
       $output = array(
              "draw" => $draw,
              "recordsTotal" => $list['count_all'],
              "recordsFiltered" => $list['count'],
              "data" => $list['data'],
              "limit" => $limit,
              "offset" => $offset,
       );
       echo json_encode($output);
   }

   public function toggleUserStatus() {
       $set = array(
           'user_status' => $this->input->post('toStatus')
       );

       $where = array(
           'user_id' => $this->input->post('id')
       );

       $update = $this->MY_Model->update('tbl_users', $set, $where);

       if ($update) {
           ajax_response('Successfully Updated','success');
       }else {
           ajax_response('Something went wrong!','error');
       }
   }
}
