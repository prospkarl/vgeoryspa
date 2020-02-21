<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('last_query')) {
	function last_query(){
		$ci =& get_instance();
        echo $ci->db->last_query();
    }
}

if(!function_exists('raw')) {
	function raw($query,$result = 'array'){
		$ci =& get_instance();
        return $ci->MY_Model->raw($query,$result);
    }
}

if(!function_exists('getrow')) {
	function getrow($table,$options,$result = 'array'){
		$ci =& get_instance();
        return $ci->MY_Model->getRows($table,$options,$result);
	}
}

if(!function_exists('datatables')){
	function datatables($table, $column_order, $select = "*", $where = "", $join = array(), $limit, $offset, $search, $order,$group = ''){
		$ci =& get_instance();
		return $ci->MY_Model->get_datatables($table, $column_order, $select = "*", $where = "", $join = array(), $limit, $offset, $search, $order,$group = '');
    }
}

if(!function_exists('insert')) {
	function insert($table,$data){
		$ci =& get_instance();
        return $ci->MY_Model->insert($table,$data);
    }
}

if(!function_exists('batch_insert')) {
	function batch_insert($table,$data){
		global $ci;
        $ci->MY_Model->batch_insert($table,$set);
        return true;
    }
}

if(!function_exists('update')) {
	function update($table,$set,$where){
		$ci =& get_instance();
        $ci->MY_Model->update($table,$set,$where);
        return true;
    }
}

if(!function_exists('delete')) {
	function delete($table,$where){
		$ci =& get_instance();
        $ci->MY_Model->delete($table,$where);
        return true;
    }
}

if(!function_exists('json')) {
	function json($data,$isJson = true){
		if($isJson){
            echo json_encode($data);
        } else {
            echo "<pre>";
                print_r($data);
            echo "</pre>";
        }
    }
}

if(!function_exists('post')) {
	function post($key){
        $ci =& get_instance();
		return ($ci->input->post($key)) ? $ci->input->post($key) : null;
    }
}

if(!function_exists('fetch_class')) {
	function fetch_class(){
        $ci =& get_instance();
		return $ci->router->fetch_class();
    }
}

if(!function_exists('fetch_method')) {
	function fetch_method(){
        $ci =& get_instance();
		return $ci->router->fetch_method();
    }
}

if(!function_exists('assets_url')) {
	function assets_url($url = ''){
		return base_url().'assets/' . $url;
    }
}

if(!function_exists('get_module_script')) {
	function get_module_script(){
		$ci =& get_instance();
		$current_class = $ci->router->fetch_class();

		$script_url = FCPATH . "assets/js/".$current_class.".js";

		if (file_exists($script_url)){
			echo '<script src="'.assets_url('js/'.$current_class).'.js"></script>';
		}
	}
}

if(!function_exists('ajax_response')) {
	function ajax_response($msg = '', $type = 'success', $data = array()){
		$response = array(
			'message' => $msg,
			'type' => $type,
			'data' => $data
		);

		echo json_encode($response);
		exit;
	}
}

if(!function_exists('render_options')) {
	function render_options($array_values, $key, $name, $selected = 0){
		$html = '';

		foreach ($array_values as $value) {

			$selected_val = $selected == $value[$key] ? 'selected' : '';

			$html .= sprintf('<option value="%s" %s>%s</option>', $value[$key], $selected_val, $value[$name]);
		}

		echo $html;
	}
}

if(!function_exists('check_empty_fields')) {
	function check_empty_fields($required, $post){
		$errors = array();

		foreach ($post as $key => $value) {
			if (empty($value) && in_array($key,$required)) {
				$errors[$key] = 'This is field is required.';
			}
		}

		return $errors;
	}
}
