<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_inventory extends MY_Controller {
	function __construct(){
		 parent::__construct();
	}

	public function index(){
		$location_raw = $this->MY_Model->getRows('tbl_locations', array('where' => array('status' => 0)));
		$locations = array();

		foreach ($location_raw as $loc_info) {
			if ($loc_info['location_id'] != 1) {
				$locations[$loc_info['location_id']] = $loc_info['name'];
			}
		}

		$type_options = array(
			'0' => 'Daily',
			'1' => 'Monthly',
		);

		$data['header_right'] = array(
			'location' => array(
				'type' => 'select',
				'name' => 'location',
				'options' => $locations
			),
			'type' => array(
				'type' => 'select',
				'name' => 'type',
				'options' => $type_options,
			)
		);

		if ($this->session->type == 2)
			unset($data['header_right']);

		// Year Range
		$start_year = 2020;
		$end_year = date('Y');
		$year_difference = $end_year - $start_year;
		$year_options = array();

		for ($i=0; $i <= $year_difference; $i++) {
			$year_options[] = array(
				'year' => $start_year + $i
			);
		}

		$data['year_options'] = $year_options;
		// End Year Range

		$this->load_page('index', $data);
	}

	public function get_month_inventories() {
		$year = $this->input->post('year');
		$curr_year = Date('Y');
		$month = Date('n');
		$month_list = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

		$result_months = array();

		foreach ($month_list as $key => $month_name) {
			$result_months[] = array(
				'month' => $month_name,
				'year' => $year,
				'inprogress' => ($key + 1) == $month && $year == $curr_year ? 1 : 0,
				'number' => $key + 1
			);

			if (($key + 1) == $month && $year == $curr_year) break;
		}
		$data['result_months'] = array_reverse($result_months);

		$this->load->view('inventories', $data);
	}

	public function view_month($month, $year, $location) {
		$data['start_date'] = date('Y-m-d', strtotime('1-'.$month.'-'.$year));

		$raw_end_date = date('Y-m-d', strtotime('1-'.$month.'-'.$year));
		$data['end_date'] = date('Y-m-d', strtotime('last day of' . $raw_end_date));
		$data['location'] = $location;

		$this->load_page('monthly_inventory', $data);
	}

	public function get_month_data() {
		$start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
		$end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
		$location = $this->input->post('location');

		$location_products['select'] = '
			tbl_products.product_id,
			tbl_products.name,
			tbl_products.pass_on_cost,
		';

		$location_products['where'] = array('location' => $location);
		$location_products['join'] = array('tbl_products' => 'tbl_products.product_id = tbl_stocks.product_id');
		$location_products = $this->MY_Model->getRows('tbl_stocks', $location_products);

		foreach ($location_products as $prod_info) {
			$beginning = $prod_info['pass_on_cost'] * $this->getBeginningByProduct($start_date, $end_date, $prod_info['product_id']);
			$sold = $this->getSalesByProduct($start_date, $end_date, $prod_info['product_id'], $location)['total_sales'];
			$discounts = $this->getSalesByProduct($start_date, $end_date, $prod_info['product_id'], $location)['discounts'];
			$deliveries = $prod_info['pass_on_cost'] * $this->getAllDeliveriesByProduct($start_date, $end_date, $prod_info['product_id'], $location)['qty'];
			$transfer = $prod_info['pass_on_cost'] * $this->getAllTransfersByProduct($start_date, $end_date, $prod_info['product_id'], $location)['qty'];
			$ending = $prod_info['pass_on_cost'] * $this->getEndingByProduct($start_date, $end_date, $prod_info['product_id'])['end_qty'];
			$actual_ending = $prod_info['pass_on_cost'] * $this->getEndingByProduct($start_date, $end_date, $prod_info['product_id'])['phy_qty'];
			$cog = ($beginning + $deliveries) - $ending;

			$td[] = array(
				$prod_info['name'],
				'₱'.($beginning ? number_format($beginning, 2) : 0),
				'₱'.($sold ? number_format($sold, 2) : 0),
				'₱'.($deliveries ? number_format($deliveries, 2) : 0),
				'₱'.($transfer ? number_format($transfer, 2) : 0),
				'₱'.($ending ? number_format($ending, 2) : 0),
				'₱'.($actual_ending ? number_format($actual_ending, 2) : 0),
				'₱'.($cog ? number_format($cog, 2) : 0),
				'₱'.($discounts ? number_format($discounts, 2) : 0),
			);
		}

		$table_data = array(
			"header" => array('Product name', 'Beg', 'Sold', 'Deliveries', 'Stock Transfer', 'Ending', 'Actual Ending', 'Cost of Good Sold', 'Total Discounts'),
			"data"=> $td
		);

		$return_data = array(
			'table' => $table_data
		);

		echo json_encode($return_data);
	}

	public function getEndingByProduct($start_date, $end_date, $product_id) {
		$inventory_options['order'] = 'date';
		$inventory_options['order_op'] = 'ASC';
		$inventory_options['where'] = "date BETWEEN '$start_date' AND '$end_date'";
		$daily_inventories = $this->MY_Model->getRows('tbl_daily_inventory', $inventory_options);

		// Beg and End Inventory
		$ending_inventory = end($daily_inventories);

		// Beg and End Items
		$ending_items = json_decode($ending_inventory['verified_items']);

		$end_qty = 0;
		$phy_qty = 0;

		foreach ($ending_items as $item_info) {
			if ($item_info->item_id == $product_id) {
				$end_qty = $item_info->sys_enditem;
				$phy_qty = $item_info->phy_enditem;
			}
		}

		$return_data = array(
			'end_qty' => $end_qty,
			'phy_qty' => $phy_qty
		);

		return $return_data;
	}

  public function getAllTransfersByProduct($glob_date_from, $glob_date_to, $product_id, $location) {
		$params["select"] = '*';
		$params['where'] = "(date_received between '$glob_date_from' and '$glob_date_to') AND location_from != 1 AND location_to = " . $location;

		$res = $this->MY_Model->getRows("tbl_stocktransfer", $params);

		$total_qty = 0;

		foreach ($res as $key) {
				$items = json_decode($key["items_received"]);

				foreach ($items as $it) {
						if ($it->item_id == $product_id) {
								$total_qty = $total_qty + $it->qty;
						};
				};
		};

		$data = array(
				"qty" => $total_qty,
		);

		return $data;
	}

  public function getAllDeliveriesByProduct($glob_date_from, $glob_date_to, $product_id, $location) {
		$params["select"] = '*';
		$params['where'] = "(date_received between '$glob_date_from' and '$glob_date_to') AND location_from = 1 AND location_to = " . $location;

		$res = $this->MY_Model->getRows("tbl_stocktransfer", $params);

		$total_qty = 0;

		foreach ($res as $key) {
				$items = json_decode($key["items_received"]);

				foreach ($items as $it) {
						if ($it->item_id == $product_id) {
								$total_qty = $total_qty + $it->qty;
						};
				};
		};

		$data = array(
				"qty" => $total_qty,
		);

		return $data;
	}

	public function getSalesByProduct($start_date, $end_date, $product_id, $location) {
		$sales_options['where'] = "(date_issued BETWEEN '$start_date' AND '$end_date') AND status = 1 AND location =" . $location;
		$all_sales = $this->MY_Model->getRows('tbl_sales', $sales_options);

		$total_sales = 0;
		$total_discounts = 0;

		foreach ($all_sales as $sales) {
			foreach (json_decode($sales['items']) as $item_info) {
				if ($item_info->item_id == $product_id) {
					$total_sales = $total_sales + $item_info->total;
					$total_discounts = $total_discounts + $item_info->discount;
				}
			}
		}

		$return_data = array(
			'total_sales' => $total_sales,
			'discounts' => $total_discounts
		);

		return $return_data;
	}

	public function getBeginningByProduct($start_date, $end_date, $product_id) {
		$inventory_options['order'] = 'date';
		$inventory_options['order_op'] = 'ASC';
		$inventory_options['where'] = "date BETWEEN '$start_date' AND '$end_date'";
		$daily_inventories = $this->MY_Model->getRows('tbl_daily_inventory', $inventory_options);

		if (empty($daily_inventories)) {
			ajax_response('No record found.', 'error');
		}

		$beginning_inventory = $daily_inventories[0];
		$beginning_items = json_decode($beginning_inventory['verified_items']);

		$beg_qty =0;

		foreach ($beginning_items as $item_info) {
			if ($item_info->item_id == $product_id) {
				$beg_qty = $item_info->beg_bal;
			}
		}

		return $beg_qty;
	}

	public function recordButton(){
		$res = '';
		$type ='';
		if (strtotime(date("Y-m-d h:i:s")) < strtotime(date("Y-m-d 08:00"))) {
			$this->session->set_userdata("current_date",date('Y-m-d 08:00:00', strtotime("-1 days")));
			$date_from = $this->session->current_date;
			$date_to = new DateTime($date_from);
			$date_to->modify('+1 day');
			$another_date_to = $date_to->format('Y-m-d h:i:s');
			$res = $this->MY_Model->getRows('tbl_daily_inventory', array("where" => "is_trash = 0 AND date BETWEEN '$date_from' AND '$another_date_to'"));
			(count($res) != 0) ? $type = true : $type = false ;
		}else {
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

	public function displayDaily(){
		$td = array();
		$params['select'] = '
			recorded_items,
			date,
			end_items,
			phy_items,
			variance_item,
			end_balance,
			phy_balance,
			variance_bal,
			tbl_user_details.fname,
			tbl_user_details.lname,
		';
		$params['where'] = array( 'daily_id' => $this->input->post('id') );
		$params['join'] = array( 'tbl_user_details' => 'tbl_user_details.user_id = tbl_daily_inventory.recorded_by');

		$res1 = $this->MY_Model->getRows('tbl_daily_inventory', $params, 'row_array');
		$items = json_decode($res1['recorded_items']);

		foreach ($items as $key => $item_info) {
			$res = $this->MY_Model->getRows('tbl_products', array("select" => "name", "where" => array("product_id" => $item_info->item_id)), 'row_array')['name'];
			$td[] = array(
				$res,
				$item_info->beg_bal,
				$item_info->sys_enditem,
				$item_info->phy_enditem,
				$item_info->variance_item,
				$item_info->sys_endbal,
				$item_info->phy_endbal,
				$item_info->variance_bal
			);
		}

		$table_data = array(
			"header" => array("Item Name","BEG. Balance","END Balance","Physical Count of Item","Variance","Total Amount of Products <br> (System Count)","Total Amount of Products <br> (Physical Count)","Variance"),
			"data"=>$td
		);

		$ret = array(
			"table" => $table_data,
			"coverage" => date('F d, Y', strtotime($res1['date'])),
			"system_item" =>$res1['end_items'],
			"physical_item" =>$res1['phy_items'],
			"variance_item" =>$res1['variance_item'],
			"sytembal" =>$res1['end_balance'],
			"physicalbal"=>$res1['phy_balance'],
			"variancebal" =>$res1['variance_bal'],
			"recorded_by" => ucwords($res1['fname'] . ' ' . $res1['lname'])
		);
		echo json_encode($ret);
	}

	public function viewRecord($id){
		$data['id'] = $id;
		$this->load_page('viewDaily',$data);
	}

	public function dateTitle(){
		$date_app = $this->input->post('data_date');
		$begin = new DateTime( date("Y-m-01", strtotime($date_app)) );
		$end = new DateTime( date("Y-m-t", strtotime($date_app)) );
		$end = $end->modify( '+1 day' );
		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval ,$end);
		$days =  array(); $i = 0;
		$exportCount = array();
		foreach($daterange as $date){
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
			foreach($daterange as $date){
				$totalSold = 0;
				$weekday = date('w', strtotime($date->format("Y-m-d")));
				if ($weekday != 6 & $weekday != 0) {
					$params['select'] = 'items';
					$params_sales['where'] = array(
						"date_issued" =>  $date->format("Y-m-d"),
						"status" => 1,
						"location" => $this->session->location
					);
					$sales_all = $this->MY_Model->getRows('tbl_sales', $params_sales,$params_sales);
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

	public function dateTitle_pull(){
		//dapat dili staic ang date dapat kun when sya na record mao pud mugawas
		$date_app = $this->input->post('data_date');
		$begin = new DateTime( date("Y-m-01", strtotime($date_app)) );
		$end = new DateTime( date("Y-m-t", strtotime($date_app)) );
		$end = $end->modify( '+1 day' );
		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval ,$end);
		$days =  array(); $i = 0;
		$exportCount = array();
		foreach($daterange as $date){
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
			foreach($daterange as $date){
				$totalSold = 0;
				$weekday = date('w', strtotime($date->format("Y-m-d")));
				if ($weekday != 6 & $weekday != 0) {
					$params['select'] = 'items';
					$params_sales['where'] = array(
						"date_created" =>  $date->format("Y-m-d"),
						"location" => $this->session->location
					);
					$sales_all = $this->MY_Model->getRows('tbl_pull_out', $params_sales,$params_sales);
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


	public function viewDaily(){
		$this->load_page('recordView');
	}

    public function getalldaily(){
		$date_from = $this->session->current_date;
		$date_to = date('Y-m-d H:i:s', strtotime('+1 day', time()));
		$location = $this->session->location;
		$params['select'] = "*,(SELECT beg_balance FROM tbl_beginning_bal WHERE product_id = tbl_stocks.product_id AND  location ='$location') as beg_bal,(SELECT price FROM tbl_products WHERE product_id = tbl_stocks.product_id) as price,(SELECT name FROM tbl_products WHERE product_id = tbl_stocks.product_id) as name, (SELECT SUM(item_qty) FROM tbl_inventory_movement WHERE location = '$location' AND  item_id = tbl_stocks.product_id AND type=0 AND date_added BETWEEN '$date_from' AND  '$date_to') as total_ins, (SELECT SUM(item_qty) FROM tbl_inventory_movement WHERE location = '$location' AND item_id = tbl_stocks.product_id AND type= 1 AND date_added BETWEEN '$date_from' AND  '$date_to') as total_out";

		$params['where'] = array(
			'location' => $this->session->location,
		);
		$res = $this->MY_Model->getRows('tbl_stocks', $params);

		$td = array();

		foreach ($res as $value) {
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
					"data" => number_format($actual_ending),
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
        echo json_encode($table);
    }

	public function deleteRecord(){
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
		}else {
			ajax_response('Removed Unsuccessful!', 'error');
		}

	}

	public function recordDaily(){
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

            $is_present = $this->MY_Model->getRows('tbl_beginning_bal', $params2);

            if (count($is_present) == 0) {
                $data_upd = array(
                    "product_id" => $post['item_id'][$i],
                    "location" => $this->session->userdata('location'),
    				"beg_balance" =>  $post['qty'][$i]
    			);
    			$res = $this->MY_Model->insert('tbl_beginning_bal', $data_upd);
            }else{
                $data_upd = array(
    				"beg_balance" =>  $this->input->post('phy_count')[$i]
    			);

    			$data_where = array(
    				"product_id" => $post['item_id'][$i],
                    "location" => $post['qty'][$i]
    			);
    			$res = $this->MY_Model->update('tbl_beginning_bal', $data_upd, $data_where);
            }

			$total_var_item = $total_var_item + (int)$post['variance_item'][$i];
			$total_var_bal = $total_var_bal + (int)$post['variancebal'][$i];
			$total_pht_bal = $total_phy_bal + (int)$post['qty'][$i];
			$total_phy_bal = $total_phy_bal + (int)$post['phybal'][$i];
			$total_sys_bal = $total_sys_bal + (int)$post['endbal'][$i];
			$total_sys_item = $total_sys_item + (int)$post['actualending_sys'][$i];

			$where  = array(
				'location' => $this->session->location,
				'product_id' => $post['item_id'][$i]
			);
			$data = array('beg_balance' => $post['qty'][$i]);
			$res_upd = $this->MY_Model->update('tbl_beginning_bal', $data, $where);
		}

		$data_in = array(
			"date" => date('Y-m-d h:i:s'),
			"recorded_by" => $this->session->id,
			"variance_item"=>$total_var_item,
			"variance_bal" =>$total_var_bal,
			"phy_balance" =>$total_phy_bal,
			"end_balance" =>$total_sys_bal,
			"phy_items" =>$total_pht_bal,
			"end_items" =>$total_sys_item,
			"recorded_items" =>json_encode($holder),
			"location" =>$this->session->location,
		);
		$res_in = $this->MY_Model->insert('tbl_daily_inventory', $data_in);
		ajax_response('Recorded successfully!', 'success');
	}

	public function datatable(){
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
			'daily_id'
		);
		$join = array(
			'tbl_user_details' => 'tbl_user_details.user_id = tbl_daily_inventory.recorded_by'
		);
		$where = array('is_trash' => 0);
		$select ="
			date,
			fname as recorded_by,
			end_items,
			phy_items,
			variance_item,
			end_balance,
			phy_balance,
			daily_id
		";
		$group = array();
		$list = $this->MY_Model->get_datatables('tbl_daily_inventory',$column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);

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
		$inventory_op['where'] = array( 'verified' => 0, 'is_trash' => 0 );
		$inventory_op['join'] = array('tbl_user_details' => 'tbl_daily_inventory.recorded_by = tbl_user_details.user_id');
		$inventory = $this->MY_Model->getRows('tbl_daily_inventory', $inventory_op, 'row_array');

		if (empty($inventory))
			die('You are not allowed to visit this page');


		$data['inventory'] = $inventory;

		$this->load_page('verify', $data);
	}
}
