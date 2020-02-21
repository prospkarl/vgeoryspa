<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends MY_Controller {
	public function index(){
		$data['location'] = $this->MY_Model->getRows('tbl_locations');
		$months = $this->months_total();
		$data['string'] = $this->getTransactionByType($months);
		$paymenMethod = array('card','gcash','cash','cheque');
		for ($i=0; $i < count($paymenMethod); $i++) {
			$data[$paymenMethod[$i]] = $this->getCards($paymenMethod[$i],$months,'',0);
		}
		$data['location_from'] = $this->MY_Model->getRows('tbl_locations');

		// $this->lineLocation();
		$this->load_page("index", $data);
	}

	public function graph(){
		$data['location_from'] = $this->MY_Model->getRows('tbl_locations');

		$this->load_page("graph", $data);
	}
	// sya tawgon sa json man padung sa view onchange ni sya
	public function central_main(){
		$year = $this->input->post('date');
		$months = $this->months_total($year, $this->input->post('loc'));
		$data['string'] = $this->getTransactionByType($months, $year, $this->input->post('loc'));
		$paymenMethod = array('card','gcash','cash','cheque');
		for ($i=0; $i < count($paymenMethod); $i++) {
			$data[$paymenMethod[$i]] = $this->getCards($paymenMethod[$i],$months, $year, $this->input->post('loc'));
		}
		echo json_encode($data);
	}

	public function lineLocation(){
		$params['select'] = "location_id, name";
		$params['where'] ="location_id != 1";
		$location = $this->MY_Model->getRows('tbl_locations', $params);
		// $paymenMethod = array('card','gcash','cash','cheque');
		$year; ($this->input->post('data') != '') ? $year = $this->input->post('data') : $year = date('Y');
		for ($j=0; $j < count($location); $j++) {
			$data_in = array();
			for ($i=0; $i < 12; $i++) {
				$mon = $i+1;
				$params['select'] = "COUNT(sales_id) as counted_sale,SUM(total_amount) as run_bal, (SUM(total_amount) / COUNT(sales_id)) as average_check";
				$params['where'] = array(
					"YEAR(`date_issued`)" => $year,
					"MONTH(`date_issued`)" => $mon,
					"location" => $location[$j]['location_id'],
					"status" => 1
				);
				$res = $this->MY_Model->getRows('tbl_sales', $params);
				$var = 0;
				if ($res[0]['counted_sale'] !='') {
					$var = $res[0]['counted_sale'];
				}
				array_push($data_in, $var);
			}
			$data[$location[$j]['name']] = array("data" => $data_in, "method" => ucfirst($location[$j]['name']));
		}
		echo json_encode($data);
	}

	public function linegraphData(){
		$year; ($this->input->post('data') != '') ? $year = $this->input->post('data') : $year = date('Y');
			$data_in = array();
			for ($i=0; $i < 12; $i++) {
				$mon = $i+1;
				$params['select'] = "COUNT(sales_id) as counted_sale,SUM(total_amount) as run_bal, (SUM(total_amount) / COUNT(sales_id)) as average_check";
				$params['where'] = array(
					"YEAR(`date_issued`)" => $year,
					"MONTH(`date_issued`)" => $mon,
					"status" => 1
				);
				$res = $this->MY_Model->getRows('tbl_sales', $params);
				$var = 0;
				$date = $year . "-" . $mon;
				if ($res[0]['counted_sale'] != '') {
					$var = (double)number_format($res[0]['counted_sale'] / (int)date("t", strtotime($date)),2);
				}
				array_push($data_in, $var);
			}
			$data['all'] = array("data" => $data_in);
		echo json_encode($data);
	}

	public function avgCheck(){
		$year; ($this->input->post('data') != '') ? $year = $this->input->post('data') : $year = date('Y');
			$data_in = array();
			for ($i=0; $i < 12; $i++) {
				$mon = $i+1;
				$params['select'] = "COUNT(sales_id) as counted_sale,SUM(total_amount) as run_bal, (SUM(total_amount) / COUNT(sales_id)) as average_check";
				$params['where'] = array(
					"YEAR(`date_issued`)" => $year,
					"MONTH(`date_issued`)" => $mon,
					"status" => 1
				);
				$res = $this->MY_Model->getRows('tbl_sales', $params);
				$var = 0;
				$date = $year . "-" . $mon;
				if ($res[0]['run_bal'] != '') {
					$var = $res[0]['run_bal'] / (int)date("t", strtotime($date));
				}
				array_push($data_in, $var);
			}

			$data['all'] = array("data" => $data_in);
		echo json_encode($data);
	}

	public function getCards($cards=array(), $month, $param_year='', $loc){
		$year; ($param_year != '') ? $year = $param_year : $year = date('Y');
		$titles = array('Percentage','Total Transactions', 'Average Check');
		$str = '';
		for ($j=0; $j < count($titles); $j++) {
			$str .= "<tr>";
			$str .= "<td>".$titles[$j]."</td>";
			for ($i=0; $i < 12; $i++) {
				$running_total = 0;
				$mon = $i + 1;
				$params['select'] = "COUNT(sales_id) as counted_sale,SUM(total_amount) as run_bal, (SUM(total_amount) / COUNT(sales_id)) as average_check";
				if ($loc == 0) {
					$params['where'] = array(
						"YEAR(`date_issued`)" => $year,
						"MONTH(`date_issued`)" => $mon,
						"payment_method" => $cards,
						"status" => 1
					);
				}else {
					$params['where'] = array(
						"YEAR(`date_issued`)" => $year,
						"MONTH(`date_issued`)" => $mon,
						"payment_method" => $cards,
						'location' => $loc,
						"status" => 1
					);
				}
				$res = $this->MY_Model->getRows('tbl_sales', $params);

				$percentage = '';
				if ($j == 0) {
					if ($month[$i] != 0) {
						$str .= "<td>".number_format(($res[0]['run_bal'] * 100) / $month[$i], 2) ."%</td>";
					}else {
						$str .= "<td>0.00%</td>";
					}
				}elseif($j == 1) {
					$running_total = $running_total + $res[0]['run_bal'];
					$str .= "<td>".$res[0]['counted_sale']  ."</td>";
				}elseif($j == 2) {
					if ($res[0]['average_check'] != '') {
						$running_total = $running_total + $res[0]['run_bal'];
						$str .= "<td>".number_format($res[0]['average_check'], 2)  ."</td>";
					}else {
						$str .= "<td>0</td>";
					}
				}
			};
			$str .= "</tr>";
		}
		return $str;
	}

    public function getTransactionByType($months_total, $param_year='', $location=0){
		$year; ($param_year != '') ? $year = $param_year : $year = date('Y');
		$str = '';
        $paymenMethod = array('cash', 'card', 'gcash', 'cheque' ,'Total');
		$overall = 0;
        for ($j=0; $j < count($paymenMethod); $j++) {
            $running_total = 0;
            $str .= "<tr>";
            $str .= "<th>".ucfirst($paymenMethod[$j])."</th>";
            if ($j < 4) {
                for ($i=0; $i < 12; $i++) {
                    $mon = $i+1;
                    $params['select'] = "COUNT(sales_id) as counted_sale,SUM(total_amount) as run_bal";
					if ($this->input->post('loc') == 0) {
						$params['where'] = array(
							"YEAR(`date_issued`)" => $year,
							"MONTH(`date_issued`)" => $mon,
							"payment_method" => $paymenMethod[$j],
							"status" => 1
						);
					}else {
						$params['where'] = array(
							"YEAR(`date_issued`)" => $year,
							"MONTH(`date_issued`)" => $mon,
							"payment_method" => $paymenMethod[$j],
							"location" => $location,
							"status" => 1
						);
					}

                    $res = $this->MY_Model->getRows('tbl_sales', $params);
                    $running_total = $running_total + $res[0]['run_bal'];
					if ($res[0]['counted_sale'] != 0) {
						$str .= "<td>".number_format($res[0]['run_bal'],2)  ."</td>";
					}else {
						$str .= "<td>".$res[0]['counted_sale'] ."</td>";
					}
                };
				$overall = $overall + $running_total;
                $str .= "<td>&#8369; ".$running_total ."</td>";
            }else {
				for ($i=0; $i < count($months_total) ; $i++) {
					$str .= "<td>".number_format($months_total[$i], 2)."</td>";
					$running_total = $running_total + $res[0]['run_bal'];
				}
                $str .= "<td>&#8369; ".number_format($overall, 2) ."</td>";
            }
            $str .= "</tr>";
        }
		return $str;
    }

	public function months_total($param_year='', $loc = ''){
		$year; ($param_year != '') ? $year = $param_year : $year = date('Y');
		$months_total_sales = array();
		for ($i=0; $i < 12; $i++) {
			$mon = $i+1;
			$params['select'] = "COUNT(sales_id) as counted_sale, SUM(total_amount) as run_bal";
			if ($loc == 0) {
				$params['where'] = array(
					"YEAR(`date_issued`)" => $year,
					"MONTH(`date_issued`)" => $mon,
					"status" => 1
				);
			}else{
				$params['where'] = array(
					"YEAR(`date_issued`)" => $year,
					"MONTH(`date_issued`)" => $mon,
					"location" => $loc,
					"status" => 1
				);
			}
			$res = $this->MY_Model->getRows('tbl_sales', $params);
			if ($res[0]['run_bal'] != '') {
				$months_total_sales[] = $res[0]['run_bal'];
			}else {
				$months_total_sales[] = 0;
			}
		};
		return $months_total_sales;
	}
}
