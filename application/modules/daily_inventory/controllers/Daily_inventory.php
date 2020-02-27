<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Daily_inventory extends MY_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
    }

    public function index() {
        $need_verification_op['where'] = array('verified' => 0, 'is_trash' => 0, 'location' => $this->session->location);
        $need_verification = $this->MY_Model->getRows('tbl_daily_inventory', $need_verification_op, 'count');
        $data['need_verification'] = $need_verification ? true : false;
        $this->load_page('index', $data);
    }

    public function recordButton() {
        $res = '';
        $type ='';
        if (strtotime(date("Y-m-d h:i:s")) < strtotime(date("Y-m-d 08:00"))) {
            $this->session->set_userdata("current_date", date('Y-m-d 08:00:00', strtotime("-1 days")));
            $date_from = $this->session->current_date;
            $date_to = new DateTime($date_from);
            $date_to->modify('+1 day');
            $another_date_to = $date_to->format('Y-m-d h:i:s');
            $res = $this->MY_Model->getRows('tbl_daily_inventory', array("where" => "is_trash = 0 AND date BETWEEN '$date_from' AND '$another_date_to'"));
            (count($res) != 0) ? $type = true : $type = false ;
        } else {
            $this->session->set_userdata("current_date", date('Y-m-d 08:00:00'));
            $date_from = $this->session->userdata['current_date'];
            $date_to = new DateTime($date_from);
            $date_to->modify('+1 day');
            $another_date_to = $date_to->format('Y-m-d h:i:s');
            $res = $this->MY_Model->getRows('tbl_daily_inventory', array("where" => "is_trash = 0 AND date BETWEEN '$date_from' AND '$another_date_to'"));
            (count($res) != 0) ? $type = true : $type = false ;
        }
        $result = array(
            "has_been_recorded" => $type,
            "date" =>  $this->session->current_date
        );
        echo json_encode($result);
    }

    public function displayDaily() {
        $td = array();
        $params['select'] = '
			recorded_items,
			date_from,
			date,
			end_items,
			phy_items,
			variance_item,
			end_balance,
			phy_balance,
			variance_bal,
			verified,
			verified_items,
			tbl_user_details.fname,
			tbl_user_details.lname,
			(SELECT fname from tbl_user_details where user_id = `tbl_daily_inventory`.`verified_by`) as verified_by
		';
        $params['where'] = array( 'daily_id' => $this->input->post('id') );
        $params['join'] = array( 'tbl_user_details' => 'tbl_user_details.user_id = tbl_daily_inventory.recorded_by');

        $res1 = $this->MY_Model->getRows('tbl_daily_inventory', $params, 'row_array');
        $items = json_decode($res1['recorded_items']);

        if ($res1['verified']) {
            $items = json_decode($res1['verified_items']);
        }

        foreach ($items as $key => $item_info) {
            $res = $this->MY_Model->getRows('tbl_products', array("select" => "name", "where" => array("product_id" => $item_info->item_id)), 'row_array')['name'];
            $td[] = array(
                $res,
                ($item_info->beg_bal != ' ') ? $item_info->beg_bal : '0' ,
                ($item_info->sys_enditem != ' ') ? $item_info->sys_enditem : '0' ,
                ($item_info->phy_enditem != ' ') ? $item_info->phy_enditem : '0' ,
                ($item_info->variance_item != ' ') ? $item_info->variance_item : '0' ,
                ($item_info->sys_endbal != ' ') ? $item_info->sys_endbal :  '0' ,
                ($item_info->phy_endbal != '') ? $item_info->phy_endbal : '0' ,
                ($item_info->variance_bal != '') ? $item_info->variance_bal : '0'
            );
        }

        $table_data = array(
            "header" => array("Item Name","BEG. Balance","END Balance","Physical Count of Item","Variance","Total Amount of Products <br> (System Count)","Total Amount of Products <br> (Physical Count)","Variance"),
            "data"=>$td
        );



        $ret = array(
            "table" => $table_data,
            "coverage" => date('F d, Y (h:iA)', strtotime($res1['date_from'])) . ' - ' . date('F d, Y (h:iA)', strtotime($res1['date'])),
            "system_item" =>$res1['end_items'],
            "physical_item" =>$res1['phy_items'],
            "variance_item" =>$res1['variance_item'],
            "sytembal" =>$res1['end_balance'],
            "physicalbal"=>$res1['phy_balance'],
            "variancebal" =>$res1['variance_bal'],
            "verified" => $res1['verified'] ? '<span class="label label-md label-success label-rounded">VERIFIED BY: '.strtoupper($res1['verified_by']).'</span>' : '<span class="label label-md label-danger label-rounded">UNVERIFIED</span>',
            "recorded_by" => ucwords($res1['fname'] . ' ' . $res1['lname'])
        );
        echo json_encode($ret);
    }

    public function viewRecord($id) {
        $data['id'] = $id;
        $this->load_page('viewDaily', $data);
    }

    public function dateTitle() {
        $inventory_id = $this->input->post('id');
        $inventory_info_op['select'] = 'date_from, date';
        $inventory_info_op['where'] = array('daily_id' => $inventory_id);
        $inventory_info = $this->MY_Model->getRows('tbl_daily_inventory', $inventory_info_op, 'row_array');
        $begin = new DateTime(date("Y-m-01", strtotime($inventory_info['date_from'])));
        $end = new DateTime(date("Y-m-t", strtotime($inventory_info['date'])));
        $end = $end->modify('+1 day');
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval, $end);
        $days =  array();
        $i = 0;
        $exportCount = array();
        foreach ($daterange as $date) {
            $weekday = date('w', strtotime($date->format("Y-m-d")));
            if ($weekday != 6 & $weekday != 0) {
                array_push($days, $date->format("d"));
            }
        }
        $params['select'] = "product_id, (SELECT name FROM tbl_products WHERE product_id = tbl_stocks.product_id) as name";
        $params['where'] = array("location" => $this->session->location);
        $res = $this->MY_Model->getRows("tbl_stocks", $params);

        foreach ($res as $key) {
            $eachdata = array();
            $exportCount[$key['product_id']] = array(
                "name" => $key['name']
            );
            foreach ($daterange as $date) {
                $totalSold = 0;
                $weekday = date('w', strtotime($date->format("Y-m-d")));
                if ($weekday != 6 & $weekday != 0) {
                    $params['select'] = 'items';
                    $params_sales['where'] = array(
                        "date_issued" =>  $date->format("Y-m-d"),
                        "status" => 1,
                        "location" => $this->session->location
                    );
                    $sales_all = $this->MY_Model->getRows('tbl_sales', $params_sales, $params_sales);
                    foreach ($sales_all as $sale_key) {
                        $allItems = json_decode($sale_key['items']);
                        foreach ($allItems as $counted) {
                            if ($key['product_id'] == $counted->item_id) {
                                $totalSold = $totalSold + $counted->qty;
                            }
                        }
                    }
                    array_push($eachdata, $totalSold);
                }
            }
            array_push($exportCount[$key['product_id']], $eachdata);
        }

        $data_trans = array(
            "insideParasite" => $exportCount,
            "days" => $days,
        );
        echo json_encode($data_trans);
    }

    public function dateTitle_pull() {
        //dapat dili staic ang date dapat kun when sya na record mao pud mugawas
        $inventory_id = $this->input->post('id');
        $inventory_info_op['select'] = 'date_from, date';
        $inventory_info_op['where'] = array('daily_id' => $inventory_id);
        $inventory_info = $this->MY_Model->getRows('tbl_daily_inventory', $inventory_info_op, 'row_array');
        $begin = new DateTime(date("Y-m-01", strtotime($inventory_info['date_from'])));
        $end = new DateTime(date("Y-m-t", strtotime($inventory_info['date'])));
        $end = $end->modify('+1 day');
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval, $end);
        $days =  array();
        $i = 0;
        $exportCount = array();
        foreach ($daterange as $date) {
            $weekday = date('w', strtotime($date->format("Y-m-d")));
            if ($weekday != 6 & $weekday != 0) {
                array_push($days, $date->format("d"));
            }
        }
        $params['select'] = "product_id,(SELECT name FROM tbl_products WHERE product_id = tbl_stocks.product_id) as name";
        $params['where'] = array("location" => $this->session->location);
        $res = $this->MY_Model->getRows("tbl_stocks", $params);

        foreach ($res as $key) {
            $eachdata = array();
            $exportCount[$key['product_id']] = array(
                "name" => $key['name']
            );
            foreach ($daterange as $date) {
                $totalSold = 0;
                $weekday = date('w', strtotime($date->format("Y-m-d")));
                if ($weekday != 6 & $weekday != 0) {
                    $params['select'] = 'items';
                    $params_sales['where'] = array(
                        "date_created" =>  $date->format("Y-m-d"),
                        "location" => $this->session->location
                    );
                    $sales_all = $this->MY_Model->getRows('tbl_pull_out', $params_sales, $params_sales);
                    foreach ($sales_all as $sale_key) {
                        $allItems = json_decode($sale_key['items']);
                        foreach ($allItems as $counted) {
                            if ($key['product_id'] == $counted->item_id) {
                                $totalSold = $totalSold + $counted->qty;
                            }
                        }
                    }
                    array_push($eachdata, $totalSold);
                }
            }
            array_push($exportCount[$key['product_id']], $eachdata);
        }
        $data_trans = array(
            "insideParasite" => $exportCount,
            "days" => $days,
        );
        echo json_encode($data_trans);
    }


    public function viewDaily() {
        $this->load_page('recordView');
    }

    public function getalldaily() {
        $action = $this->input->post('action');

        $date_from = date('Y-m-d H:i:s', strtotime('-1 day', time()));

        $has_previous_inv_op['where'] = array('location' => $this->session->location, 'is_trash' => 0, 'verified' => 1);
        $has_previous_inv_op['order'] = 'date';
        $has_previous_inv_op['order_op'] = 'desc';
        $has_previous_inv = $this->MY_Model->getRows('tbl_daily_inventory', $has_previous_inv_op, 'row_array');

        if (!empty($has_previous_inv)) {
            $date_from = $has_previous_inv['date'];
        }

        $date_to = date('Y-m-d H:i:s');
        $location = $this->session->location;
        $params['select'] = "*,
            (SELECT beg_balance FROM tbl_beginning_bal WHERE product_id = tbl_stocks.product_id AND  location ='$location') as beg_bal,
            (SELECT price FROM tbl_products WHERE product_id = tbl_stocks.product_id) as price,
            (SELECT name FROM tbl_products WHERE product_id = tbl_stocks.product_id) as name,
            (SELECT SUM(item_qty) FROM tbl_inventory_movement WHERE location = '$location' AND item_id = tbl_stocks.product_id AND type = 0 AND date_added BETWEEN '$date_from' AND  '$date_to') as total_ins,
            (SELECT SUM(item_qty) FROM tbl_inventory_movement WHERE location = '$location' AND item_id = tbl_stocks.product_id AND type= 1 AND date_added BETWEEN '$date_from' AND  '$date_to') as total_out
        ";

        $params['where'] = array(
            'location' => $this->session->location,
        );
        $res = $this->MY_Model->getRows('tbl_stocks', $params);

        $td = array();
        
        if ($action == 'ending') {
            foreach ($res as $key => $value) {
                $t_ins =($value['total_ins'] != '') ? $value['total_ins'] : 0;
                $actual_ending = ($value['total_ins'] + $value['beg_bal']) - $value['total_out'];
                $td[] = array(
                    array(
                        "class" => '',
                        "data" => $value['name']
                    ),
                    array(
                        "class" => '',
                        "data" =>number_format($value['beg_bal']),
                    ),
                    array(
                        "class" => '',
                        "data" => $t_ins,
                    ),
                    array(
                        "class" => 'test',
                        "data" =>number_format($value['total_out']),
                    ),
                    array(
                        "class" => 'endind_sys_item',
                        "data" => $actual_ending,
                    ),
                    array(
                        "class" => 'test',
                        "data" => array(
                            array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "",
                                "name" => "item_id[]",
                                "value" => $value['product_id'],
                            ),
                            array(
                                "kind" => 'input',
                                "type" => 'number',
                                "class" => "phy_qty",
                                "name" => "qty[]",
                                "value" => '0',
                            ),
                            array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "begbal",
                                "name" => "begbal[]",
                                "value" => $value['beg_bal'],
                            ),
                            array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "",
                                "name" => "actualending_sys[]",
                                "value" => $actual_ending,
                                ),
                                array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "varianceitem_in",
                                "name" => "variance_item[]",
                                "value" => '0',
                                ),
                                array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "",
                                "name" => "endbal[]",
                                "value" => $value['price'] * $actual_ending,
                                ),
                                array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "physicalbal_in",
                                "name" => "phybal[]",
                                "value" => '',
                                ),
                                array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "varianceBal",
                                "name" => "variancebal[]",
                                "value" => '',
                                ),
                                array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "price",
                                "name" => "",
                                "value" => $value['price'],
                                ),
                                ),
                                ),
                                array(
                                "class" => 'variance_item',
                                "data" => '0',
                                ),
                                array(
                                "class" => 'ending_sys format-money',
                                "data" => $value['price'] * $actual_ending,
                                ),
                                array(
                                "class" => 'actual_ending_bal',
                                "data" => '0',
                                ),
                                array(
                                "class" => 'variance_bal',
                                "data" => '0',
                                ),
                                );
            };
            $table = array(
                                "header" => array("Item Name", "Today's Beg. Balance", "Total IN", "Total OUT","End Item Count", "Physical Count","Variance","End Balance","Physical Amount", "Variance"),
                                "data" => $td
                            );
        } else {
            // VERIFY!!
            $inventory_op['where'] = array('daily_id' => $this->input->post('inventory_id'));
            $inventory = $this->MY_Model->getRows('tbl_daily_inventory', $inventory_op, 'row_array');

            $recorded_items = json_decode($inventory['recorded_items']);
            $actual_items_qty = array();

            foreach ($recorded_items as $item_info) {
                $actual_items_qty[$item_info->item_id] = $item_info->phy_enditem;
                $beg_balance[$item_info->item_id] = $item_info->beg_bal;
            }


            foreach ($res as $value) {
                $t_ins =($value['total_ins'] != '') ? $value['total_ins'] : 0;
                $actual_ending = ($value['total_ins'] + $value['beg_bal']) - $value['total_out'];
                $td[] = array(
                    array(
                        "class" => '',
                        "data" => $value['name']
                    ),
                    array(
                        "class" => 'endind_sys_item',
                        "data" => isset($beg_balance[$value['product_id']]) ? $beg_balance[$value['product_id']] : '0',
                    ),
                    array(
                        "class" => 'physical_count',
                        "data" => isset($actual_items_qty[$value['product_id']]) ? $actual_items_qty[$value['product_id']] : '0',
                    ),
                    array(
                        "class" => 'test',
                        "data" => array(
                            array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "",
                                "name" => "item_id[]",
                                "value" => $value['product_id'],
                            ),
                            array(
                                "kind" => 'input',
                                "type" => 'number',
                                "class" => "phy_qty",
                                "name" => "qty[]",
                                "value" => '0',
                            ),
                            array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "begbal",
                                "name" => "begbal[]",
                                "value" => $value['beg_bal'],
                            ),
                            array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "",
                                "name" => "actualending_sys[]",
                                "value" => $actual_ending,
                                ),
                                array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "varianceitem_in",
                                "name" => "variance_item[]",
                                "value" => '0',
                                ),
                                array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "",
                                "name" => "endbal[]",
                                "value" => $value['price'] * $actual_ending,
                                ),
                                array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "physicalbal_in",
                                "name" => "phybal[]",
                                "value" => '',
                                ),
                                array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "varianceBal",
                                "name" => "variancebal[]",
                                "value" => '',
                                ),
                                array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "price",
                                "name" => "",
                                "value" => $value['price'],
                                ),
                                ),
                                ),
                                array(
                                "class" => 'variance_item',
                                "data" => '0',
                                ),
                                array(
                                "class" => 'ending_sys format-money',
                                "data" => $value['price'] * $actual_ending,
                                ),
                                array(
                                "class" => 'actual_ending_bal',
                                "data" => '0',
                                ),
                                array(
                                "class" => 'variance_bal',
                                "data" => '0',
                                ),
                                );
            };
            $table = array(
                "header" => array("Item Name", "End Item Count", "Physical Count", "Verify Count","Variance","End Balance","Physical Amount", "Variance"),
                "data" => $td
            );
        }

        $data['table'] = $table;
        $data['coverage'] = date('Y-m-d (h:iA)', strtotime($date_from)) . ' - ' . date('Y-m-d (h:iA)', strtotime($date_to));
        $data['date_from'] = $date_from;
        echo json_encode($data);
    }

    public function deleteRecord() {
        $post = $this->input->post();

        $params_get['select'] = "recorded_items";
        $params_get['where'] = array('daily_id' => $post['id']);
        $recorded_items = json_decode($this->MY_Model->getRows('tbl_daily_inventory', $params_get)[0]["recorded_items"]);
        $res;
        foreach ($recorded_items as $key) {
            $params_set = array("beg_balance" => $key->beg_bal);
            $params_where = array(
                "product_id" => $key->item_id,
                "location" => $this->session->location,
            );
            $res = $this->MY_Model->update('tbl_beginning_bal', $params_set, $params_where);
        }

        $upd_daily = array("is_trash" => 1);
        $upd_where = array('daily_id' => $post['id']);
        $res2 = $this->MY_Model->update('tbl_daily_inventory', $upd_daily, $upd_where);
        if ($res2) {
            ajax_response('Removed successfully!', 'success');
        } else {
            ajax_response('Removed Unsuccessful!', 'error');
        }
    }

    public function recordDaily() {
        $check_options['where'] = array('is_trash' => 0, 'verified' => 0, 'location' => $this->session->location);
        $check_options = $this->MY_Model->getRows('tbl_daily_inventory', $check_options, 'count');

        if ($check_options) {
            ajax_response('Please verify previous inventory.', 'error');
        }

        $post = $this->input->post();
        $holder = array();
        $total_var_item = 0;
        $total_var_bal = 0;
        $total_pht_bal = 0;
        $total_phy_bal = 0;
        $total_sys_item = 0;
        $total_sys_bal = 0;

        for ($i=0; $i < count($post['item_id']); $i++) {
            $holder[] = array(
                "item_id" =>  $post['item_id'][$i],
                "beg_bal" =>  $post['begbal'][$i],
                "sys_enditem" => $post['actualending_sys'][$i],
                "phy_enditem" => $post['qty'][$i],
                "variance_item" => $post['variance_item'][$i],
                "sys_endbal" => $post['endbal'][$i],
                "phy_endbal" => $post['phybal'][$i],
                "variance_bal"=> $post['variancebal'][$i],
            );

            $params2['where'] = array(
                "product_id" => $post['item_id'][$i],
                "location" => $this->session->userdata('location')
            );
            $total_var_item = $total_var_item + (int)$post['variance_item'][$i];
            $total_var_bal = $total_var_bal + (int)$post['variancebal'][$i];
            $total_pht_bal = $total_pht_bal + (int)$post['qty'][$i];
            $total_phy_bal = $total_phy_bal + (int)$post['phybal'][$i];
            $total_sys_bal = $total_sys_bal + (int)$post['endbal'][$i];
            $total_sys_item = $total_sys_item + (int)$post['actualending_sys'][$i];
        }


        $data_in = array(
            "date_from" => date('Y-m-d H:i', strtotime($post['date_from'])),
            "date" => date('Y-m-d H:i'),
            "recorded_by" => $this->session->id,
            "variance_item"=> $total_var_item,
            "variance_bal" => $total_var_bal,
            "phy_balance" => $total_phy_bal,
            "end_balance" => $total_sys_bal,
            "phy_items" => $total_pht_bal,
            "end_items" => $total_sys_item,
            "recorded_items" => json_encode($holder),
            "location" => $this->session->location,
        );

        $res_in = $this->MY_Model->insert('tbl_daily_inventory', $data_in);
        ajax_response('Recorded successfully!', 'success');
    }

    public function datatable() {
        $limit = $this->input->post('length');
        $offset = $this->input->post('start');
        $search = $this->input->post('search');
        $order = $this->input->post('order');
        $draw = $this->input->post('draw');
        $column_order = array(
            'date',
            'recorded_by',
            'end_items',
            'phy_items',
            'variance_item',
            'end_balance',
            'phy_balance',
            'verified',
            'daily_id'
        );
        $join = array(
            'tbl_user_details' => 'tbl_user_details.user_id = tbl_daily_inventory.recorded_by'
        );
        $where = array('is_trash' => 0, 'location' => $this->session->location);

        if (!empty($this->input->post('location_id'))) {
            $where['location'] = $this->input->post('location_id');
        }

        $select ="
			DATE_FORMAT(date, '%b %e, %Y - %l:%i%p') as date,
			fname as recorded_by,
			end_items,
			phy_items,
			variance_item,
			end_balance,
			phy_balance,
			verified,
			daily_id
		";
        $group = array();
        $list = $this->MY_Model->get_datatables('tbl_daily_inventory', $column_order, $select, $where, $join, $limit, $offset, $search, $order, $group);

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

    public function verify() {
        $data = array();

        $inventory_op['select'] = 'daily_id, date, tbl_user_details.fname, tbl_user_details.lname';
        $inventory_op['where'] = array( 'verified' => 0, 'is_trash' => 0, 'location' => $this->session->location );
        $inventory_op['join'] = array('tbl_user_details' => 'tbl_daily_inventory.recorded_by = tbl_user_details.user_id');
        $inventory = $this->MY_Model->getRows('tbl_daily_inventory', $inventory_op, 'row_array');

        if (empty($inventory)) {
            die('You are not allowed to visit this page');
        }

        $data['inventory'] = $inventory;

        $this->load_page('verify', $data);
    }

    public function verify_submit() {
        // $_POST
        $post = $this->input->post();
        $item_parsed = array();

		$inventory_op['where'] = array('daily_id' => $this->input->post('inventory_id'));
		$inventory = $this->MY_Model->getRows('tbl_daily_inventory', $inventory_op, 'row_array');
		$recorded_items = json_decode($inventory['recorded_items']);

		foreach ($recorded_items as $item_info) {
			$actual_items_qty[$item_info->item_id] = $item_info->phy_enditem;
		}

        $total_var_item = 0;
        $total_var_bal = 0;
        $total_pht_bal = 0;
        $total_phy_bal = 0;
        $total_sys_bal = 0;
        $total_sys_item = 0;

        foreach ($post['item_id'] as $key => $value) {
            $item_parsed[] = array(
                "item_id" =>  $post['item_id'][$key],
                "beg_bal" =>  $post['begbal'][$key],
                "sys_enditem" => $post['actualending_sys'][$key],
                "phy_enditem" => $post['qty'][$key],
                "variance_item" => $post['variance_item'][$key],
                "sys_endbal" => $post['endbal'][$key],
                "phy_endbal" => $post['phybal'][$key],
                "variance_bal"=> $post['variancebal'][$key]
            );

            $where = array('product_id' => $post['item_id'][$key], 'location' => $this->session->location);
            $tbl_stocks_set = array('qty' => $post['qty'][$key]);

            $this->MY_Model->update('tbl_stocks', $tbl_stocks_set, $where);


            $is_present = $this->MY_Model->getRows('tbl_beginning_bal', $where);

            if (count($is_present) == 0) {
                $data_upd = array(
                    "product_id" => $post['item_id'][$key],
                    "location" => $this->session->location,
                    "beg_balance" =>  $post['qty'][$key]
                );
                $res = $this->MY_Model->insert('tbl_beginning_bal', $data_upd);
            } else {
                $tbl_beginning_bal_set = array('beg_balance' => $post['qty'][$key]);
    			$this->MY_Model->update('tbl_beginning_bal', $tbl_beginning_bal_set, $where);
            }

            $total_var_item = $total_var_item + (int)$post['variance_item'][$key];
            $total_var_bal = $total_var_bal + (int)$post['variancebal'][$key];
            $total_pht_bal = $total_pht_bal + (int)$post['qty'][$key];
            $total_phy_bal = $total_phy_bal + (int)$post['phybal'][$key];
            $total_sys_bal = $total_sys_bal + (int)$post['endbal'][$key];
            $total_sys_item = $total_sys_item + (int)$post['actualending_sys'][$key];
        }

		$set_inventory = array(
			'verified_items' => json_encode($item_parsed),
			'verified_by' => $this->session->id,
			'verified' => 1,
            "variance_item"=> $total_var_item,
            "variance_bal" => $total_var_bal,
            "phy_balance" => $total_phy_bal,
            "end_balance" => $total_sys_bal,
            "phy_items" => $total_pht_bal,
            "end_items" => $total_sys_item
		);

		$update = $this->MY_Model->update('tbl_daily_inventory', $set_inventory, $inventory_op['where']);

		if ($update) {
			ajax_response('Inventory Verified!', 'success');
		}else {
			ajax_response('Something went wrong', 'error');
		}
    }

    public function get_inventory_items() {
        $inventory_options['where'] = array('daily_id' => $this->input->post('daily_id'));
        $inventory_info = $this->MY_Model->getRows('tbl_daily_inventory', $inventory_options, 'row_array');

        $items = json_decode($inventory_info['recorded_items']);


        foreach ($items as $key => $item_info) {
            $search_item_op['select'] = 'name';
            $search_item_op['where'] = array( 'product_id' => $item_info->item_id );
            $search_item = $this->MY_Model->getRows('tbl_products', $search_item_op, 'row_array');
            $items[$key]->item_name = $search_item['name'];
        }

        echo json_encode($items);
    }
}
