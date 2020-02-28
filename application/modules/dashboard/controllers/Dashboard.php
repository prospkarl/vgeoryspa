<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	function __construct(){
		parent::__construct();
		if ($this->session->type == 2) {
			redirect('sales');
		}
	}

	public function index(){
		$this->render_admin_dashboard();
	}

	public function render_admin_dashboard() {
		$locations = $this->MY_Model->getRows('tbl_locations', array('where' => array('status' => 0)));

		$options = array();

		foreach ($locations as $loc) {
			$options[$loc['location_id']] = $loc['name'];
		}

		$data['header_right'] = array(
			array(
				'type' => 'select',
				'name' => 'location',
				'options' => array_merge([0 => 'All'], $options)
			),
			array(
				'type' => 'select',
				'name' => 'timeframe',
				'options' => array(
					'week' => 'Week',
					'month' => 'Month',
					'year' => 'Year'
				)
			),
		);

		$data['categories'] = $this->MY_Model->getRows('tbl_categories');

		$count_options['select'] = "(SELECT SUM(tbl_stocks.qty) FROM tbl_stocks WHERE tbl_stocks.location=1) as warehouse_qty";

		$data['product_count'] = $this->MY_Model->getRows('tbl_stocks', $count_options, 'row_array');

		# Warehouse Stock on Hand
		$warehouse_options['select'] = '(SELECT `acquisition` FROM tbl_products WHERE tbl_products.product_id = tbl_stocks.product_id) * tbl_stocks.qty as sum';

		$warehouse_options['where'] = array(
			'location' => 1
		);

		$warehouse_stocks = $this->MY_Model->getRows('tbl_stocks', $warehouse_options);

		$stock_on_hand = 0;

		foreach ($warehouse_stocks as $stock) {
			$stock_on_hand = $stock_on_hand + $stock['sum'];
		}

		$data['stock_on_hand'] = $stock_on_hand;

		# Low Stock
		$locations = $this->MY_Model->getRows('tbl_locations');

		$colors = ['success', 'primary', 'danger', 'info', 'violet', 'warning'];

		$low_stock = array();

		foreach ($locations as $key => $loc) {
			$loc_options['select'] = 'qty, name, sku';

			$loc_options['where'] = array(
				'location' => $loc['location_id'],
			);

			$loc_options['join'] = array(
				'tbl_products' => 'tbl_stocks.product_id = tbl_products.product_id'
			);

			$loc_options['where_sub'] = '`tbl_stocks`.`qty` <= (SELECT tbl_products.min_stock_kiosk from tbl_products WHERE tbl_products.product_id LIKE tbl_stocks.product_id) ';

			if ($loc['location_id'] != 1) {
				$low_stock[$loc['name']] = array(
					'items' => $this->MY_Model->getRows('tbl_stocks', $loc_options),
					'color' => $colors[$key],
					'id' => $loc['location_id']
				);
			}
		}

		$data['locations'] = $low_stock;

		# Count Low stock
		$low_stock_options['where'] = array(
			'location' => 1,
		);

		$low_stock_options['join'] = array(
			'tbl_products' => 'tbl_stocks.product_id = tbl_products.product_id'
		);

		$low_stock_options['where_sub'] = '`tbl_stocks`.`qty` <= (SELECT tbl_products.min_stock_warehouse from tbl_products WHERE tbl_products.product_id LIKE tbl_stocks.product_id) ';

		$low_stock_count = $this->MY_Model->getRows('tbl_stocks', $low_stock_options, 'count');

		$data['low_stock_count'] = $low_stock_count - 1;

		# For delivery
		$delivery_options['where'] = array('status' => 0, 'location_from' => 1);
		$delivery = $this->MY_Model->getRows('tbl_stocktransfer', $delivery_options, 'count');

		$data['for_delivery_count'] = $delivery;


		$this->load_page('admin', $data);
	}

	// Charts
	public function get_items_sold() {
		$location = $this->input->post('location');
		$time_frame = $this->input->post('timeframe');

		$location_options['select'] = 'location_id, name';
		$location_options['where'] = array(
			'status' => 0
		);

		if ($location == 0) {
			$location_options['where'] = array( 'status' => 0 );
		}else {
			$location_options['where']['location_id'] = $location;
		}

		$locations = $this->MY_Model->getRows('tbl_locations', $location_options);
		$items_sold = array();
		$labels = array();
		$data = $this->get_dates($time_frame);

		foreach ($locations as $loc) {
			$sales_options['select'] = 'date_issued, total_items';

			$sales_options['where'] = array(
				'location' => $loc['location_id']
			);
			$items_sold[$loc['name']] = array();


			//Time Frame
			if ($time_frame == 'week') {
				$labels = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

				foreach ($labels as $label) {
					$items_sold[$loc['name']][$label] = 0;
				}

				$sales_options['where_sub'] = "date_issued between '".$data['current']['start_date']."' and '".$data['current']['end_date']."'";
				$sales = $this->MY_Model->getRows('tbl_sales', $sales_options);

				foreach ($sales as $sale_info) {
					if (isset($items_sold[$loc['name']][date('D', strtotime($sale_info['date_issued']))])) {
						$items_sold[$loc['name']][date('D', strtotime($sale_info['date_issued']))] = $items_sold[$loc['name']][date('D', strtotime($sale_info['date_issued']))] + $sale_info['total_items'];
					}else {
						$items_sold[$loc['name']][date('D', strtotime($sale_info['date_issued']))] = $sale_info['total_items'];
					}
				}
			}elseif ($time_frame == 'month') {
				$labels = array( 'Week 1', 'Week 2', 'Week 3', 'Week 4', );

				$items_sold[$loc['name']] = array();

				$start_date = 1;
				$end_date = date('d', strtotime('last day of' . date('M Y')));

				foreach ($labels as $label) {
					$items_sold[$loc['name']][$label] = 0;
				}

				for ($i = $start_date; $i < $end_date; $i++) {
					$sales_options['select'] = "SUM(total_items) as total_items";
					$sales_options['where_sub'] = "date_issued = '".date('Y-m-d', strtotime(date('Y-m-'.$i)))."'";

					if ($i < 7) {
						$items_sold[$loc['name']]['Week 1'] = $items_sold[$loc['name']]['Week 1'] + $this->MY_Model->getRows('tbl_sales', $sales_options, 'row_array')['total_items'];
					}elseif ($i <= 14) {
						$items_sold[$loc['name']]['Week 2'] = $items_sold[$loc['name']]['Week 2'] + $this->MY_Model->getRows('tbl_sales', $sales_options, 'row_array')['total_items'];
					}elseif ($i <= 21) {
						$items_sold[$loc['name']]['Week 3'] = $items_sold[$loc['name']]['Week 3'] + $this->MY_Model->getRows('tbl_sales', $sales_options, 'row_array')['total_items'];
					}else {
						$items_sold[$loc['name']]['Week 4'] = $items_sold[$loc['name']]['Week 4'] + $this->MY_Model->getRows('tbl_sales', $sales_options, 'row_array')['total_items'];
					}
				}
			}elseif ($time_frame == 'year') {
				$labels = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );

				foreach ($labels as $label) {
					$items_sold[$loc['name']][$label] = 0;

					$start_date = date('Y-m-d', strtotime('first day of ' . $label . date('Y')));
					$end_date = date('Y-m-d', strtotime('last day of ' . $label . date('Y')));

					$sales_additional_options['select'] = 'SUM(total_items) as total_items';

					$sales_options['where_sub'] = "date_issued between '".$start_date."' and '".$end_date."'";
					$sales = $this->MY_Model->getRows('tbl_sales', array_merge($sales_options, $sales_additional_options), 'row_array');

					foreach ($sales as $sales_info) {
						$items_sold[$loc['name']][$label] = !empty($sales['total_items']) ? $sales['total_items'] : 0;
					}
				}
			}
		}

		//Parsing of data
		$final_results = array(
			'labels' => $labels,
			'datasets' => array()
		);

		foreach ($items_sold as $location_name => $data) {
			$data_set = [];

			if (count($data)) {
				foreach ($data as $key => $value) {
					$data_set[] = $value;
				}

				switch ($location_name) {
					case 'Ayala Mall Cebu':
						$color = '#2FB7B6';
						break;
					case 'Robinsons Cybergate':
						$color = '#FF7D47';
						break;
					case 'SM City Cebu':
						$color = '#5587E7';
						break;
					case 'CSO':
						$color = '#55CE63';
						break;
					default:
						$color = '#2FB7B6';
						break;
				}

				$final_results['datasets'][] = array(
					'label' => $location_name,
					'fill' => false,
					'data' => $data_set,
					'borderColor' => $color,
					'lineTension' => 0.1,
				);
			}
		}

		echo json_encode($final_results);
	}


	public function get_sales_chart() {
		$location = $this->input->post('location');
		$time_frame = $this->input->post('timeframe');

		$location_options['select'] = 'location_id, name';
		$location_options['where'] = array(
			'status' => 0
		);

		if ($location == 0) {
			$location_options['where'] = array( 'status' => 0 );
		}else {
			$location_options['where']['location_id'] = $location;
		}

		$locations = $this->MY_Model->getRows('tbl_locations', $location_options);
		$items_sold = array();
		$labels = array();
		$data = $this->get_dates($time_frame);

		foreach ($locations as $loc) {
			$sales_options['select'] = 'date_issued, total_amount';

			$sales_options['where'] = array(
				'location' => $loc['location_id'],
				'status' => 1
			);
			$items_sold[$loc['name']] = array();


			//Time Frame
			if ($time_frame == 'week') {
				$labels = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

				foreach ($labels as $label) {
					$items_sold[$loc['name']][$label] = 0;
				}

				$sales_options['where_sub'] = "date_issued between '".$data['current']['start_date']."' and '".$data['current']['end_date']."'";
				$sales = $this->MY_Model->getRows('tbl_sales', $sales_options);

				foreach ($sales as $sale_info) {
					if (isset($items_sold[$loc['name']][date('D', strtotime($sale_info['date_issued']))])) {
						$items_sold[$loc['name']][date('D', strtotime($sale_info['date_issued']))] = $items_sold[$loc['name']][date('D', strtotime($sale_info['date_issued']))] + $sale_info['total_amount'];
					}else {
						$items_sold[$loc['name']][date('D', strtotime($sale_info['date_issued']))] = $sale_info['total_amount'];
					}
				}
			}elseif ($time_frame == 'month') {
				$labels = array( 'Week 1', 'Week 2', 'Week 3', 'Week 4', );

				$items_sold[$loc['name']] = array();

				$start_date = 1;
				$end_date = date('d', strtotime('last day of' . date('M Y')));

				foreach ($labels as $label) {
					$items_sold[$loc['name']][$label] = 0;
				}

				for ($i = $start_date; $i < $end_date; $i++) {
					$sales_options['select'] = "SUM(total_amount) as total_amount";
					$sales_options['where_sub'] = "date_issued = '".date('Y-m-d', strtotime(date('Y-m-'.$i)))."'";

					if ($i < 7) {
						$items_sold[$loc['name']]['Week 1'] = $items_sold[$loc['name']]['Week 1'] + $this->MY_Model->getRows('tbl_sales', $sales_options, 'row_array')['total_amount'];
					}elseif ($i <= 14) {
						$items_sold[$loc['name']]['Week 2'] = $items_sold[$loc['name']]['Week 2'] + $this->MY_Model->getRows('tbl_sales', $sales_options, 'row_array')['total_amount'];
					}elseif ($i <= 21) {
						$items_sold[$loc['name']]['Week 3'] = $items_sold[$loc['name']]['Week 3'] + $this->MY_Model->getRows('tbl_sales', $sales_options, 'row_array')['total_amount'];
					}else {
						$items_sold[$loc['name']]['Week 4'] = $items_sold[$loc['name']]['Week 4'] + $this->MY_Model->getRows('tbl_sales', $sales_options, 'row_array')['total_amount'];
					}

				}

			}elseif ($time_frame == 'year') {
				$labels = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );

				foreach ($labels as $label) {
					$items_sold[$loc['name']][$label] = 0;

					$start_date = date('Y-m-d', strtotime('first day of ' . $label . date('Y')));
					$end_date = date('Y-m-d', strtotime('last day of ' . $label . date('Y')));

					$sales_additional_options['select'] = 'SUM(total_amount) as total_amount';

					$sales_options['where_sub'] = "date_issued between '".$start_date."' and '".$end_date."'";
					$sales = $this->MY_Model->getRows('tbl_sales', array_merge($sales_options, $sales_additional_options), 'row_array');

					foreach ($sales as $sales_info) {
						$items_sold[$loc['name']][$label] = !empty($sales['total_amount']) ? $sales['total_amount'] : 0;
					}
				}
			}
		}

		//Parsing of data
		$final_results = array(
			'xAxis' => $labels,
			'series' => array()
		);

		foreach ($items_sold as $location_name => $data) {
			$data_set = [];

			if (count($data)) {
				foreach ($data as $key => $value) {
					$data_set[] = $value;
				}

				$final_results['legend'][] = $location_name;

				$final_results['series'][] = array(
					'name' => $location_name,
					'type' => 'bar',
					'data' => $data_set,
				);
			}
		}

		$target_sales = $this->get_option('target_sales_week');

		foreach ($data_set as $nothing) {
			switch ($time_frame) {
				case 'week':
				$target_array[] = $target_sales / 7;
				break;
				case 'month':
				$target_array[] = $target_sales / 4;
				break;
				case 'year':
				$target_array[] = $target_sales / 12;
				break;
			}
		}


		$final_results['series'][] = array(
			'name' => 'Target',
			'symbol' => 'none',
			'type' => 'line',
			'data' => $target_array,
			'lineStyle' => array(
				'type' => 'dashed',
				'color' => 'red',
				'opacity' => 0
			),
		);

		echo json_encode($final_results);
	}

	public function top_selling_chart() {
		$categories_options['select'] = 'category_id, name';

		$categories_options['where'] = array(
			'status' => 0
		);

		$categories = $this->MY_Model->getRows('tbl_categories', $categories_options);

		$response = array();

		foreach ($categories as $cat) {
			$sales_options['select'] = 'SUM(qty) as overall';

			$sales_options['where'] = array(
				'category_id' => $cat['category_id'],
				'tbl_sales.status' => 1
			);

			$first_day = date('Y-m-d', strtotime('first day of january this year'));

			$sales_options['where_sub'] = "date_issued between '$first_day' and '".date('Y-m-d')."'";

			$sales_options['join'] = array(
				'tbl_sales' => 'tbl_purchased_items.purchase_id = tbl_sales.sales_id'
			);

			$sales = $this->MY_Model->getRows('tbl_purchased_items', $sales_options, 'row_array');

			if (!empty($sales['overall'])) {
				$response[] = array(
					'y' => $cat['name'],
					'b' => $sales['overall']
				);
			}
		}

		echo json_encode($response);
	}

	public function ajax_dashboard() {
		$location = $this->input->post('location');
		$time_frame = $this->input->post('timeframe');

		$sales = $this->get_sales($location, $time_frame);
		$soldItems = $this->get_sold_items($location, $time_frame);
		$top_sellers = $this->get_top_sellers($location, $time_frame);
		$target_sales = $this->get_target_sales($time_frame);
		$target_sales_value = $this->get_target_sales($time_frame, true);

		$results = array(
			'sales' => $sales,
			'sold' => $soldItems,
			'top_sellers' => $top_sellers,
			'target_sales' => $target_sales,
			'target_sales_value' => $target_sales_value,
			'display_time_frame' => ucwords($time_frame)
		);

		echo json_encode($results);
	}

	public function get_target_sales($time_frame, $raw = false) {
		$target_sales_option['where'] = array(
			'option_name' => 'target_sales_week'
		);

		$target_sales = $this->MY_Model->getRows('tbl_options', $target_sales_option, 'row_array')['option_value'];

		switch ($time_frame) {
			case 'month':
				$target_sales = $target_sales * 4;
				break;
			case 'year':
				$target_sales = $target_sales * 48;
				break;
		}

		if ($raw) {
			return $target_sales;
		}else {
			return '₱ ' . number_format($target_sales);
		}
	}

	public function get_dates($time_frame) {
		switch ($time_frame) {
			case 'week':
				$start_date = date("Y-m-d", strtotime('monday this week'));
				$end_date = date("Y-m-d");
				$previous_start_date = date("Y-m-d", strtotime('monday last week'));
				$previous_end_date = date("Y-m-d", strtotime('sunday last week'));
				break;
			case 'month':
				$start_date = date("Y-m-d", strtotime('first day of this month'));
				$end_date = date("Y-m-d");
				$previous_start_date = date("Y-m-d", strtotime('first day of last month'));
				$previous_end_date = date("Y-m-d", strtotime('last day of last month'));

				break;
			case 'year':
				$start_date = date("Y-m-d", strtotime('first day of january this year'));
				$end_date = date("Y-m-d");
				$previous_start_date = date("Y-m-d", strtotime('first day of january last year'));
				$previous_end_date = date("Y-m-d", strtotime('last day of december last year'));

				break;
		}

		$result = array(
			'current' => array(
				'start_date' => $start_date,
				'end_date' => $end_date,
			),
			'previous' => array(
				'start_date' => $previous_start_date,
				'end_date' => $previous_end_date,
			)
		);

		return $result;
	}

	public function get_top_sellers($location, $time_frame) {
		$dates = $this->get_dates($time_frame);
		$options['select'] = "
			*,
			(SELECT SUM(total_amount) FROM tbl_sales WHERE issued_by = tbl_user_details.user_id AND MONTH(`date_issued`) = '".date('m')."' AND YEAR(`date_issued`) = '".date('Y')."') as total_sales,
			(SELECT SUM(total_items) FROM tbl_sales WHERE issued_by = tbl_user_details.user_id AND MONTH(`date_issued`) = '".date('m')."' AND YEAR(`date_issued`) = '".date('Y')."' ) as total_items
		";

		$options['where'] = array(
			'position' => 'Sales'
		);

		$options['order'] = 'total_sales DESC';

		$get_sellers = $this->MY_Model->getRows('tbl_user_details', $options);


		$top_sellers = '';

		if (!empty($get_sellers)) {
			foreach ($get_sellers as $seller) {
				if (!empty($seller['total_sales'])) {
					$top_sellers .= '<tr style="cursor:pointer" onclick="window.location=`'.base_url('top_sellers').'`">';
					$top_sellers .= '<td style="height:70px" class="text-center"><i class="fa fa-user"></i></td>';
					$top_sellers .= '<td>'.ucwords($seller['fname'] . ' ' . $seller['lname']).'</td>';
					$top_sellers .= '<td class="text-right">₱ '.$seller['total_sales'].'</td>';
					$top_sellers .= '</tr>';
				}
			}
		}

		if (empty($top_sellers)) {
			$top_sellers .= '<tr>';
			$top_sellers .= '<td colspan="100%">';
			$top_sellers .= 'No sales this Month';
			$top_sellers .= '</td>';
			$top_sellers .= '</tr>';
		}

		return $top_sellers;
	}

	public function get_sold_items($location, $time_frame) {
		$sold_options['select'] = 'SUM(qty) as total_qty';

		if ($location != 0) {
			$sold_options['where'] = array(
				'tbl_sales.location' => $location,
				'tbl_sales.status' => 1
			);
		}

		$dates = $this->get_dates($time_frame);

		$sold_options['where_sub'] = "date_issued between '".$dates['current']['start_date']."' and '".$dates['current']['end_date']."'";
		$sold_options['join'] = array( 'tbl_sales' => 'tbl_purchased_items.purchase_id = tbl_sales.sales_id' );
		$sold = $this->MY_Model->getRows('tbl_purchased_items', $sold_options, 'row_array');

		$sold_options['where_sub'] = "date_issued between '".$dates['previous']['start_date']."' and '".$dates['previous']['end_date']."'";
		$sold_previous = $this->MY_Model->getRows('tbl_purchased_items', $sold_options, 'row_array');


		$difference = $sold['total_qty'] - $sold_previous['total_qty'];

		$total = '0';
		$final_difference = '';

		if (!empty($sold['total_qty'])) {
			$total = $sold['total_qty'];
			$final_difference = ltrim($difference , '-') . ' <i class="fas fa-long-arrow-alt-'. ( $difference > 0 ? 'up' : 'down') .' text-sm"></i>';
		}

		$results = array(
			'total' => $total,
			'difference' => $final_difference,
			'class' => $difference < 0 ?  'text-danger' : 'text-info',
			'gain' => $difference < 0 ? false : true
		);

		return $results;
	}

	public function get_sales($location, $time_frame) {
		$dates = $this->get_dates($time_frame);

		$sales_options['select'] = 'SUM(total_amount) as total_amount';
		$sales_previous_options['select'] = 'SUM(total_amount) as total_amount';

		$sales_options['where']['status'] = 1;
		$sales_previous_options['where']['status'] = 1;

		if ($location != 0) {
			$sales_options['where']['location'] = $location;
		}

		$sales_previous_options['where_sub'] = "date_issued between '".$dates['previous']['start_date']."' and '".$dates['previous']['end_date']."'";
		$sales_previous = $this->MY_Model->getRows('tbl_sales', $sales_previous_options, 'row_array');

		$sales_options['where_sub'] = "date_issued between '".$dates['current']['start_date']."' and '".$dates['current']['end_date']."'";
		$sales = $this->MY_Model->getRows('tbl_sales', $sales_options, 'row_array');

		$difference = $sales['total_amount'] - $sales_previous['total_amount'];

		$total = '0';
		$final_difference = '';

		if (!empty($sales['total_amount'])) {
			$total = '₱ ' . number_format($sales['total_amount']);
			$final_difference = '₱ ' . ltrim(number_format($difference) , '-') . ' <i class="fas fa-long-arrow-alt-'. ( $difference > 0 ? 'up' : 'down') .' text-sm"></i>';
		}

		$results = array(
			'total' => $total,
			'difference' => $final_difference,
			'class' => $difference < 0 ?  'text-danger' : 'text-info',
			'gain' => $difference < 0 ? false : true
		);

		return $results;
	}

	public function updateSalesTarget($time_frame) {
		$new_target = $this->input->post('new_target');

		switch ($time_frame) {
			case 'month':
				$new_target = $new_target / 4;
				break;

			case 'year':
				$new_target = $new_target / 48;
				break;
		}

		$update = $this->update_option('target_sales_week', $new_target);

		if ($update) {
			ajax_response('Updated Successfully', 'success');
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}
}
