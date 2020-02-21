<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends MX_Controller {
	public function __construct(){
		date_default_timezone_set('Asia/Manila');
		setlocale(LC_MONETARY, 'en_PH');

		$this->load->model('MY_Model');

		$route = $this->router->fetch_class();
		$restrictions = $this->restrictions();

		if($route != 'login'){
			if(!$this->session->userdata('username')){
				   redirect(base_url('login'));
			}
		}

		if($route == 'login'){
			if($this->session->has_userdata('username')){
				redirect(base_url());
			}
		} else {
			if(!$this->session->has_userdata('username')){
				redirect(base_url('login'));
			}
			if (in_array($this->router->fetch_class(), $restrictions)) {
				die('You are not allowed to visit this page');
			}
		}
	}

	public function restrictions(){
		// Put controller name here
		if ($this->session->userdata('type') == 0) {
			$restriction = array("salesreport", 'receive', 'sales');
		}else if ($this->session->userdata('type') == 1) {
			$restriction = array("user", "generate_reports", "transferstock");
		}else if ($this->session->userdata('type') == 2) { //Sales
			$restriction = array("user","reports",'salesreport', 'purchaseorder', 'stock_transfer', 'void_requests');
		}else if ($this->session->userdata('type') == 3) {
			$restriction = array("user","reports","transferstock",'salesreport');
		}

		return $restriction;
	}

	public function getNotifications() {
		$notifications = array();

		if ($this->session->type != 2) { //FOR ADMIN/WAREHOUSE
			# Monthly Inventory
			$date_now = date("Y-m", strtotime("+1 months"));
			$last_months_first_monday = date("Y-m-d", strtotime("first monday " . $date_now));
			$now = time(); // or your date as well
			$your_date = strtotime($last_months_first_monday);
			$datediff =  $your_date -  $now;
			$number = round($datediff / (60 * 60 * 24));
			$result =  "<strong style='font-weight:900; color:indianred'>". $number . " Days</strong> Left Until Next Inventory";

			if ($number <= 5) {
				$notifications[] = array(
					'messageheader' => 'Monthly Inventory',
					'message' => $result,
					'icon' => 'fas fa-clipboard-list',
					'iconClass' => 'info',
					'link' => base_url('monthly_inventory')
				);
			}

			# REQUESTS
			$req_options['where'] = array('status' => 0);
			$requests = $this->MY_Model->getRows('tbl_request', $req_options, 'count');

			if ($requests) {
				$notifications[] = array(
					'messageheader' => 'Requests',
					'message' => 'You have <strong style="font-weight:900">'.$requests.'</strong> Pending Request(s)',
					'icon' => 'mdi mdi-inbox-arrow-up',
					'iconClass' => 'warning',
					'link' => base_url('requests')
				);
			}

			# TO RECEIVE
			$receive_options['where'] = array('status' => 0);
			$receive = $this->MY_Model->getRows('tbl_purchase_order', $receive_options, 'count');

			if ($receive) {
				$notifications[] = array(
					'messageheader' => 'To Receive',
					'message' => 'You have <strong style="font-weight:900">'.$receive.'</strong> Pending Orders(s)',
					'icon' => 'fas fa-boxes',
					'iconClass' => 'success',
					'link' => base_url('purchaseorder')
				);
			}

			//Void Requests
			$request_op['where'] = array('status' => 0);
			$requests = $this->MY_Model->getRows('tbl_sales', $request_op, 'count');

			if (!empty($requests)) {
				$notifications[] = array(
					'messageheader' => 'Void Request',
					'message' => $requests . ' pending requests.',
					'icon' => 'fa fa-exclamation-circle',
					'iconClass' => 'danger',
					'link' => base_url('void_requests')
				);
			}

		}else { // FOR SALES
			$receive_options['where'] = array(
			'status' => 0,
			'location_to' => $this->session->location,
			);
			$receive = $this->MY_Model->getRows('tbl_stocktransfer', $receive_options, 'count');

			if ($receive) {
				$notifications[] = array(
				'messageheader' => 'To Receive',
				'message' => 'You have <strong style="font-weight:900">'.$receive.'</strong> Pending Orders(s)',
				'icon' => 'fas fa-boxes',
				'iconClass' => 'success',
				'link' => base_url('receive')
				);
			}
		}

		# Low Stock
		$locations = $this->MY_Model->getRows('tbl_locations');

		$low_stock = array();

		foreach ($locations as $loc) {
			$loc_options['where'] = array(
			'location' => $loc['location_id'],
			);

			if ($loc['location_id'] == 1) {
				$loc_options['where_sub'] = '`tbl_stocks`.`qty` <= (SELECT tbl_products.min_stock_warehouse from tbl_products WHERE tbl_products.product_id LIKE tbl_stocks.product_id) ';
			}else {
				$loc_options['where_sub'] = '`tbl_stocks`.`qty` <= (SELECT tbl_products.min_stock_kiosk from tbl_products WHERE tbl_products.product_id LIKE tbl_stocks.product_id) ';
			}


			if ($this->session->location == 1) {
				$low_stock[$loc['name']] = array(
				'low_items' => $this->MY_Model->getRows('tbl_stocks', $loc_options, 'count'),
				'loc_id' => $loc['location_id']
				);
			}else {
				if ($loc['location_id'] == $this->session->location) {
					$low_stock[$loc['name']] = array(
					'low_items' => $this->MY_Model->getRows('tbl_stocks', $loc_options, 'count'),
					'loc_id' => $loc['location_id']
					);
				}
			}

		}

		foreach ($low_stock as $location => $low) {
			if ($low['low_items']) {
				$notifications[] = array(
					'messageheader' => 'Low stock',
					'message' => $location . ' - <strong style="font-weight:900">' . $low['low_items'] . '</strong>',
					'icon' => 'fas fa-exclamation-triangle',
					'iconClass' => 'inverse',
					'link' => base_url('products?loc=' . $low['loc_id'])
				);
			}
		}

		return $notifications;
	}

	public function load_page($page, $data = array()){
		if ($this->session->type == 2) {
			$need_verification_op['where'] = array('verified' => 0, 'is_trash' => 0, 'location' => $this->session->location);
	        $need_verification = $this->MY_Model->getRows('tbl_daily_inventory', $need_verification_op, 'count');
	        $data['need_verification'] = $need_verification ? true : false;
		}


		$params['select'] = "position, fname, lname";
		$params['where'] = array("user_id" => $this->session->userdata('id'));

		$data['userdata'] = $this->MY_Model->getrows('tbl_user_details',$params,'row_array');
		$data['navigation'] = $this->get_navigation();

		$loc_options['where'] = array(
			'location_id' => $this->session->location
		);

		if ($this->session->location) {
			$data['current_location'] = $this->MY_Model->getRows('tbl_locations', $loc_options, 'row')->name;
		}


		$data['notifications'] = $this->getNotifications();

      	$this->load->view('includes/head', $data);
      	$this->load->view('includes/nav', $data);
      	$this->load->view($page,$data);
      	$this->load->view('includes/footer', $data);
     }

	 public function login_view($page, $data = array()){
	   $this->load->view('includes/login_head', $data);
	   $this->load->view($page, $data);
 	   $this->load->view('includes/login_footer', $data);
	 }

	public function get_navigation(){

		$nav['dashboard'] = array(
			'title' => 'Dashboard',
			'link' => base_url(),
			'icon' => 'ti-menu-alt'
		);

		$nav['user'] = array(
			'title' => 'Users',
			'link' => base_url() . 'user',
			'icon' => 'mdi mdi-account-multiple'
		);

		$nav['sales_order'] = array(
			'title' => 'Sales Order',
			'link' => base_url() . 'sales_order',
			'icon' => 'fas fa-shopping-bag'
		);

		$nav['purchaseorder'] = array(
			'title' => 'Purchase Order',
			'link' => base_url() . 'purchaseorder',
			'icon' => 'fas fa-boxes'
		);

		// $nav['topsellers'] = array(
		// 	'title' => 'Top Sellers',
		// 	'link' => base_url() . 'topsellers',
		// 	'icon' => 'fas fa-user'
		// );

		$nav['inventory'] = array(
			'title' => 'Inventory',
			'link' => 'inventory',
			'icon' => 'fas fa-clipboard-list',
			'sub-nav' => array(
				'products' => array(
					'title' => 'Products',
					'link' => 'products',
				),
				'pull_out' => array(
					'title' => 'Pull Out',
					'link' => 'pull_out',
				),
				'categories' => array(
					'title' => 'Categories',
					'link' => 'categories',
				),
				// 'brands' =>  array(
				// 	'title' => 'Brands',
				// 	'link' => 'brands',
				// ),
				'locations' => array(
					'title' => 'Locations',
					'link' => 'locations',
				),
			)
		);

		$nav['stocks'] = array(
			'title' => 'Manage Stocks',
			'link' => 'inventory',
			'icon' => 'mdi mdi-inbox-arrow-up',
			'sub-nav' => array(
				'transferstock' => array(
					'title' => 'Delivery',
					'link' => 'delivery',
				),
				'delivery' => array(
					'title' => 'Transfer Stocks',
					'link' => 'stock_transfer',
				),
				'requests' => array(
					'title' => 'Requests',
					'link' => 'requests',
				),

			)
		);

		$nav['generate_reports'] = array(
			'title' => 'Generate Reports',
			'link' => 'reports',
			'icon' => 'mdi mdi-chart-areaspline',
			'sub-nav' => array(
				'topsellers' => array(
					'title' => 'Top Sellers',
					'link' => 'top_sellers',
				),
				'inventoryanalysis' => array(
					'title' => 'Inventory Analysis',
					'link' => 'inventory_analysis',
				),
				'sales' => array(
					'title' => 'Sales',
					'link' => '',
					'sub_link' => array(
						'salesreport' => array(
							'title' => 'Sales Report',
							'link' => 'reports',
						),
						'salesinventory' => array(
							'title' => 'Inventory Report',
							'link' => 'sales_inventory',
						),
					)
				),
				'transaction' => array(
					'title' => 'Transaction Report',
					'link' => 'transaction',
					'sub_link' => array(
						'impact' => array(
							'title' => 'Cashless Transaction',
							'link' => 'transaction',
						),
						'graph' => array(
							'title' => 'Transaction Graph',
							'link' => 'transaction/graph',
						),
					)
				),
				'monthlyinventory' => array(
					'title' => 'Monthly Inventory',
					'link' => 'monthly_inventory',
				)
			)
		);

		$nav['salesreport'] = array(
			'title' => 'Sales Report',
			'link' => 'reports',
			'icon' => 'mdi mdi-account-multiple'
		);

		$restriction = $this->restrictions();

		foreach ($restriction as $key) {
			unset($nav[$key]);
		}

		return $nav;
	}

	public function create_log($table, $log_info = array()) {
		// Data: Table, referrer_id, log, logged_by

		$insert = array(
			'referrer_id' => $log_info['referrer_id'],
			'table_name' => $table,
			'content' => $log_info['log'],
			'logged_by' => $log_info['logged_by'],
			'date' => date('Y-m-d H:i:s')
		);
		$insert = $this->MY_Model->insert('tbl_logs', $insert);

		if ($insert) {
			return true;
		}else {
			return false;
		}
	}

	public function update_option($option_name, $option_value) {
		$where = array('tbl_options.option_name' => $option_name);
		$set = array( 'option_value' => $option_value );
		$update = $this->MY_Model->update('tbl_options', $set, $where);
		return true;
	}

	public function get_option($option_name) {
		$options['where'] = array('tbl_options.option_name' => $option_name);
		$result = $this->MY_Model->getRows('tbl_options', $options, 'row_array');
		return $result['option_value'];
	}

}
