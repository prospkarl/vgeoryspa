<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monthly_inventory extends MY_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
    }

    public function index() {
        $data['header_right'] = array(
            array(
                'type' => 'button',
                'name' => 'Record Monthly Inventory',
                'modal' => 'recMonthly',
                'icon' => 'fa fa-plus-circle'
            ),
        );
        $this->load_page('index', $data);
    }

    public function daysLeft() {
        $last_months = date("Y-m-t");
        $add_one_day = date("Y-m-d");
        $last_months_final = $last_months;
        if (date('N', strtotime($last_months)) == 7) {
            $last_months_final = date('Y-m-d', strtotime("+1 days", strtotime($last_months)));
        } elseif (date('N', strtotime($last_months)) == 6) {
            $last_months_final = date('Y-m-d', strtotime("+2 days", strtotime($last_months)));
        }

        $endTimeStamp =  strtotime($last_months_final);
        $startTimeStamp = strtotime($add_one_day);
        $timeDiff = abs($endTimeStamp - $startTimeStamp);
        $daysleft = $timeDiff/86400;
        $daysleft = intval($daysleft);

        $date_to = date("Y-m-d");
        $try['where'] = "date_to = '$date_to' OR date_from= '$date_to'";
        $isRecorded = $this->MY_Model->getRows('tbl_monthly_inventory', $try);
        $isGood ='';
        if (count($isRecorded) > 0) {
            $isGood = 'success';
        }
        $try = $this->session->userdata('recorded');
        $data = array(
            "date_due" =>$add_one_day,
            "daysleft" => $daysleft,
            "istoday_record" => $isGood
        );
        echo json_encode($data);
    }

    public function viewRecord($id_rec) {
        $data['id'] = $id_rec;
        $this->load_page('view_monthly', $data);
    }

    public function tables() {
        $params['select'] ="*,(SELECT fname FROM tbl_user_details WHERE user_id = tbl_monthly_inventory.recorded_by) as fname, (SELECT lname FROM tbl_user_details WHERE user_id = tbl_monthly_inventory.recorded_by) as lname";

        $params['where'] = array(
            "monthly_id" => $this->input->post('id')
        );

        $res = $this->MY_Model->getRows('tbl_monthly_inventory', $params, 'row_array');
        $glob_date_from = $res['date_from'];
        $glob_date_to = $res['date_to'];
        $items = json_decode($res['recorded_items']);

        $params_total_purc['select'] ='SUM(total_cost) as total_purchase';
        $params_total_purc['where'] = "received_date between '$glob_date_from' and '$glob_date_to'";
        $total_purchase = $this->MY_Model->getRows('tbl_purchase_order', $params_total_purc)[0]['total_purchase'];

        $params_total_sales['select'] ='SUM(total_amount) as total_sales';
        $params_total_sales['where'] = "date_issued between '$glob_date_from' and '$glob_date_to' AND status = 1";
        $total_sales = $this->MY_Model->getRows('tbl_sales', $params_total_sales)[0]['total_sales'];

        $data = array(
            "display_item" => $this->displayAllItems($items),
            "display_pull" => $this->displayAllPullOut($glob_date_from, $glob_date_to),
            "display_pur" => $this->displayAllPurchase($glob_date_from, $glob_date_to),
            "display_goods" =>$this->displayGoodsSold($glob_date_from, $glob_date_to),
            "display_summary" =>$this->displaySummary($glob_date_from, $glob_date_to),
            "system_count" => "&#x20B1; " . number_format($res['end_balance'], 2),
            "physical_count" => "&#x20B1; " . number_format($res['phy_balance'], 2),
            "variance_amt" => "&#x20B1; " . number_format($res['variance_amount'], 2),
            "system_count_items" => number_format($res['end_items']),
            "physical_count_items" => number_format($res['phy_items']),
            "total_sales"=>"&#x20B1; " . number_format($total_sales),
            "total_purchase" => "&#x20B1; " . number_format($total_purchase),
            "profit" => "&#x20B1; " . number_format($total_sales - $total_purchase),
            "variance_items" => number_format($res['variance_item']),
            "coverage" =>  $res['date_from'] . " To " . $res['date_to'],
            "month"=> $res['month_of_record'],
            "recorded" => $res['fname'] ." ". $res['lname']
        );

        echo json_encode($data);
    }

    public function displayGoodsSold($glob_date_from, $glob_date_to) {
        $params['select'] ="*";
        $res = $this->MY_Model->getRows('tbl_products', $params);
        $td = array();

        foreach ($res as $key) {
            $beg_acq = $key['acquisition'] * (int)$this->getAllPhysicalByProduct($glob_date_from, $glob_date_to, $key['product_id'])['beg_bal'];

            $purchases = $key['acquisition'] * (int)$this->getAllPurchaseByProduct($glob_date_from, $glob_date_to, $key['product_id'])['qty'];

            $actual_ending = $key['acquisition'] * (int)$this->getAllPhysicalByProduct($glob_date_from, $glob_date_to, $key['product_id'])['qty'];

            $td[] = array(
                array(
                    "class" => 'test',
                    "data" => $key['name']
                ),
                array(
                    "class" => 'test',
                    "data" => number_format($beg_acq),
                ),
                array(
                    "class" => 'test',
                    "data" =>number_format($purchases),
                ),
                array(
                    "class" => 'test',
                    "data" => number_format($actual_ending),
                ),
                array(
                    "class" => 'test',
                    "data" => number_format(($beg_acq + $purchases) - $actual_ending),
                ),

            );
        }

        $table_data = array(
            "header" => array(
                "Product Name",
                "Beg. Balance<br><span class='red_italic'>Acquisition Cost</span>",
                "Purchases<br><span class='red_italic'>Acquisition Cost</span>",
                "Actual Ending<br><span class='red_italic'>Acquisition Cost</span>",
                "Cost of Good Sold",
                ),
            "data"=>$td
        );

        return $table_data;
    }

    // SUMMARY TAB
    public function displaySummary($glob_date_from, $glob_date_to) {
        $params['select'] ="*";
        $res = $this->MY_Model->getRows('tbl_products', $params);
        $td = array();
        foreach ($res as $key) {
            $beginning = (int)$key['acquisition'] * $this->getAllPhysicalByProduct($glob_date_from, $glob_date_to, $key['product_id'])['beg_bal'];
            $sold = $this->getAllSoldByProduct($glob_date_from, $glob_date_to, $key['product_id'], 1)['total_amount'];
            $deliveries = (int)$key['pass_on_cost'] * $this->getAllDeliveriesByProduct($glob_date_from, $glob_date_to, $key['product_id'], 1)['qty'];
            $purchases = $key['acquisition'] * (int)$this->getAllPurchaseByProduct($glob_date_from, $glob_date_to, $key['product_id'])['qty'];
            $pull_outs = (int)$key['acquisition'] * $this->getAllPulloutByProduct($glob_date_from, $glob_date_to, $key['product_id'])['qty'];
            $end_bal = (int)$key['acquisition'] * $this->getAllPhysicalByProduct($glob_date_from, $glob_date_to, $key['product_id'])['end_bal'];
            $actual_end_bal = (int)$key['acquisition'] * $this->getAllPhysicalByProduct($glob_date_from, $glob_date_to, $key['product_id'])['phy_count'];
            $cost_of_good_sold = ($beginning + $purchases) - $actual_end_bal;
            $discounts =  $this->getAllDiscountsByProduct($glob_date_from, $glob_date_to, $key['product_id'], 1);

            $td[] = array(
                array(
                    "class" => 'name',
                    "data" => $key['name']
                ),
                array(
                    "class" => 'beg',
                    "data" => '₱'.number_format($beginning)
                ),
                array(
                    "class" => '',
                    "data" => '₱'. ($sold ? number_format($sold) : 0)
                ),
                array(
                    "class" => 'deliveries',
                    "data" => '₱'. ($deliveries ? number_format($deliveries) : 0)
                ),
                array(
                    "class" => 'purchases',
                    "data" => '₱'. ($purchases ? number_format($purchases) : 0)
                ),
                array(
                    "class" => 'pull_outs',
                    "data" => '₱'. ($pull_outs ? number_format($pull_outs) : 0)
                ),
                array(
                    "class" => 'end_bal',
                    "data" => '₱'. ($end_bal ? number_format($end_bal) : 0)
                ),
                array(
                    "class" => 'actual_end_bal',
                    "data" => '₱'. ($actual_end_bal ? number_format($actual_end_bal) : 0)
                ),
                array(
                    "class" => 'cost_of_good_sold',
                    "data" => '₱'. ($cost_of_good_sold ? number_format($cost_of_good_sold) : 0)
                ),
                array(
                    "class" => 'discounts',
                    "data" => '₱'. ($discounts ? number_format($discounts) : 0)
                ),

            );
        }

        $table_data = array(
            "header" => array(
                "Product Name",
                "Beg",
                "Sold",
                "Deliveries",
                "Purchases",
                "Pull Out",
                "Ending",
                "Actual Ending",
                "Cost of Good Sold",
                "Total Discounts",
                ),
            "data" => $td
        );

        return $table_data;
    }

    public function displayAllPullOut($glob_date_from, $glob_date_to) {
        $td = array();
        $params_pull['select'] ="*, (SELECT name FROM tbl_locations WHERE location_id = tbl_pull_out.location) as location_name, (SELECT fname FROM tbl_user_details WHERE user_id = tbl_pull_out.created_by) as fname, (SELECT lname FROM tbl_user_details WHERE user_id = tbl_pull_out.created_by) as lname";
        $params_pull['where'] = "date_created between '$glob_date_from' and '$glob_date_to' and location='".$this->session->location."'";
        $params_pur['order'] = "date_created";
        $res_pull = $this->MY_Model->getRows('tbl_pull_out', $params_pull);
        foreach ($res_pull as $key) {
            $par['select']="items";
            $par['where'] = array('pull_out_id' => $key['pull_out_id']);
            $pull_items =json_decode($this->MY_Model->getRows('tbl_pull_out', $par, "row_array")['items']);
            $total_qty = 0;
            $total_amt_pull=0;
            foreach ($pull_items as $key_qty) {
                $par_price['select'] = 'price';
                $par_price['where'] = array('product_id' => $key_qty->item_id);
                $price = $this->MY_Model->getRows('tbl_products', $par_price)[0]['price'];
                $total_qty = $total_qty + $key_qty->qty;
                $total_amt_pull = $total_amt_pull + ($key_qty->qty * $price);
            };
            $td[] = array($key['date_created'], ucfirst($key['fname']) ." ". ucfirst($key['lname']), $key['location_name'], $total_qty, "&#x20B1;".number_format($total_amt_pull), $key['remarks']);
        }
        $table_data = array(
                "header" => array("Date Pulled Out", "Pull Out By", "Location", "Quantity", "Amount", "Remarks"),
                "data"=>$td
            );
        return $table_data;
    }

    public function displayAllItems($items = array()) {
        $td = array();
        foreach ($items as $key) {
            $params['select'] = "name, beg_bal";
            $params['where'] = array("product_id" => $key->item_id);
            $rest = $this->MY_Model->getRows('tbl_products', $params)[0];
            $td[] = array(
                $rest['name'],
                $key->beg_bal,
                $key->end_bal_items,
                $key->phy_count_items,
                $key->variance_item,
                $key->end_bal_amt,
                $key->phy_amt,
                $key->variance_amt
            );
        }
        $table_data = array(
            "header" => array(
                "Item Name",
                "BEG. Balance",
                "END Balance",
                "Physical Count of Item",
                "Variance",
                "Total Amount Of Products <br> System Count",
                "Total Amount Of Products <br> Physical Count",
                "Variance"
            ),
            "data"=> $td
        );
        return $table_data;
    }

    public function displayAllPurchase($glob_date_from, $glob_date_to) {
        $params_pur['select'] ="*, (SELECT fname FROM tbl_user_details WHERE user_id = tbl_purchase_order.created_by) as fname, (SELECT lname FROM tbl_user_details WHERE user_id = tbl_purchase_order.created_by) as lname";
        $params_pur['where'] = "received_date between '$glob_date_from' and '$glob_date_to'";
        $params_pur['order'] = "received_date";
        $res_pur = $this->MY_Model->getRows('tbl_purchase_order', $params_pur);
        $td = array();

        foreach ($res_pur as $key) {
            $par['select']="quantity_received";
            $par['where'] = array('poid' => $key['poid']);
            $purchased_items =json_decode($this->MY_Model->getRows('tbl_purchase_order', $par, "row_array")['quantity_received']);
            $total_qty = 0;
            $total_amt_pull=0;
            foreach ($purchased_items as $keyval) {
                $total_qty = $total_qty + $keyval->qty;
            };
            $td[] = array($key['received_date'], ucfirst($key['supplier']), ucfirst($key['lname']),$total_qty,"&#x20B1;".number_format($key['total_cost']));
        }
        $table_data = array(
                "header" => array("Date Of Purchase", "Supplier", "Purchased By", "Total Quantity", "Total Cost"),
                "data"=> $td
            );
        return $table_data;
    }

    public function recordInventory() {
        $recorded_items = array();
        $date_now = date("Y-m", strtotime("-1 months"));
        $firstDay= date("Y-m-01");
        $lastDay = date("Y-m-t");
        $current_month = date("F", strtotime(date('Y-m-d'))) ." ". date("Y");
        for ($i=0; $i < count($this->input->post('item_id')) ; $i++) {
            $recorded_items[] = array(
                "item_id" =>$this->input->post('item_id')[$i],
                "beg_bal" => $this->input->post('beg_balance')[$i],
                "end_bal_items"=>$this->input->post('end_bal')[$i],
                "phy_count_items" =>$this->input->post('phy_count')[$i],
                "variance_item" =>$this->input->post('variance_item')[$i],
                "end_bal_amt" => $this->input->post('end_bal_amt')[$i],
                "phy_amt" => $this->input->post('phy_bal')[$i],
                "variance_amt"=>$this->input->post('variance_amt')[$i]
            );

            $data_upd = array(
                "beg_bal" =>  $this->input->post('phy_count')[$i]
            );

            $data_where = array(
                "product_id" => $this->input->post('item_id')[$i]
            );
            $res = $this->MY_Model->update('tbl_products', $data_upd, $data_where);

            $data_upd_stock = array(
                "qty" =>  $this->input->post('phy_count')[$i]
            );

            $data_where_stock = array(
                "product_id" => $this->input->post('item_id')[$i],
                "location" => 1
            );
            $res = $this->MY_Model->update('tbl_stocks', $data_upd_stock, $data_where_stock);
        };

        $sum_end_items = array_sum($this->input->post('end_bal'));
        $sum_physical = array_sum($this->input->post('phy_count'));
        $sum_var_item = array_sum($this->input->post('variance_item'));
        $sum_end_amt = array_sum($this->input->post('end_bal_amt'));
        $sum_phy_amt = array_sum($this->input->post('phy_bal'));
        $sum_var_amt = array_sum($this->input->post('variance_amt'));

        $data = array(
            "date_from" =>$firstDay,
            "date_to" => $lastDay,
            "month_of_record" =>$current_month,
            "recorded_by" =>$this->session->userdata('id'),
            "variance_item" =>$sum_var_item,
            "variance_amount" =>$sum_var_amt,
            "phy_balance" =>$sum_phy_amt,
            "end_balance" =>$sum_end_amt,
            "phy_items" =>$sum_physical,
            "end_items" =>$sum_end_items,
            "recorded_items" =>json_encode($recorded_items),
        );

        $res = $this->MY_Model->insert('tbl_monthly_inventory', $data);
        $sessioned = array(
            "recorded" => "success"
        );
        $this->session->set_userdata($sessioned);
        $para_prev['where'] = array(
            "monthly_id" => $res - 1
        );
        $prev_phy_balance = $this->MY_Model->getRows('tbl_monthly_inventory', $para_prev);
        ajax_response('Added Successfully', 'Success');
    }

    public function checkDateWithinRange($start_date, $user_date, $end_date) {
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $user_ts = strtotime($user_date);
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }

    public function recordView() {
        $this->load_page('record_inventory');
    }

    public function recordContent() {
        $date_now = date("Y-m", strtotime("-1 months"));
        $firstDay= date("Y-m-01");
        $lastDay = date("Y-m-t");
        $current_month = date("F", strtotime(date('Y-m-d'))) ." ". date("Y");

        $params['select'] = "*, (SELECT SUM(item_qty) FROM tbl_inventory_movement WHERE location = 1 AND  item_id = tbl_products.product_id AND type=0 AND date_added between '$firstDay' and '$lastDay') as total_in, (SELECT SUM(item_qty) FROM tbl_inventory_movement WHERE location = 1 AND item_id = tbl_products.product_id AND type= 1 AND date_added between '$firstDay' and '$lastDay') as total_out";

        $res = $this->MY_Model->getRows('tbl_products', $params);

        $i=1;
        $str='';
        foreach ($res as $val) {
            $end_bal = (intval($val['total_in']) + $val['beg_bal'])  - intval($val['total_out']);

            $td[] = array(
                array(
                    "class" => 'test',
                    "data" => array(
                        array(
                            "kind" => 'input',
                            "type" => 'hidden',
                            "class" => "",
                            "name" => "item_id[]",
                            "value" => $val['product_id'],
                        ),
                        $val['name'],
                    )
                ),
                array(
                    "class" => 'test',
                    "data" => $val['beg_bal'],
                ),
                array(
                    "class" => 'test',
                    "data" => number_format($val['total_in']),
                ),
                array(
                    "class" => 'test',
                    "data" => number_format($val['total_out']),
                ),
                array(
                    "class" => '',
                    "data" => array(
                        array(
                            "kind" => 'input',
                            "type" => 'hidden',
                            "class" => "var_item",
                            "name" => "variance_item[]",
                            "value" => '0',
                        ),
                        array(
                            "kind" => 'input',
                            "type" => 'hidden',
                            "class" => "",
                            "name" => "beg_balance[]",
                            "value" => $val['beg_bal'],
                        ),
                        array(
                            "kind" => 'input',
                            "type" => 'hidden',
                            "class" => "physical_bal",
                            "name" => "phy_bal[]",
                            "value" => '0',
                        ),
                        array(
                            "kind" => 'input',
                            "type" => 'hidden',
                            "class" => "variance_amount_total",
                            "name" => "variance_amt[]",
                            "value" => '0',
                        ),
                        array(
                            "kind" => 'input',
                            "type" => 'hidden',
                            "class" => "ending_balance",
                            "name" => "end_bal[]",
                            "value" => $end_bal,
                        ),
                        array(
                            "kind" => 'input',
                            "type" => 'hidden',
                            "class" => "price",
                            "name" => "",
                            "value" => $val['price'],
                        ),
                        array(
                            "kind" => 'input',
                            "type" => 'hidden',
                            "class" => "ending_amt",
                            "name" => "end_bal_amt[]",
                            "value" => $end_bal * $val['price'],
                        ),
                        number_format($end_bal),
                    ),
                ),
                array(
                    "class" => 'test',
                    "data" =>   array(
                        array(
                            "kind" => 'input',
                            "type" => 'hidden',
                            "class" => "",
                            "name" => "",
                            "value" => $end_bal,
                        ),
                        array(
                            "kind" => 'input',
                            "type" => 'number',
                            "class" => "count_qty_mon",
                            "name" => "phy_count[]",
                            "value" => "0",
                        ),
                    ),
                ),
                array(
                    "class" => 'variance',
                    "data" =>  "0",
                ),
                array(
                    "class" => '',
                    "data" => "&#x20B1; " . number_format($end_bal * $val['price']),
                ),
                array(
                    "class" => 'end_bal_res',
                    "data" =>  "&#x20B1; 0",
                ),
                array(
                    "class" => 'variance_amt',
                    "data" =>  "&#x20B1; 0",
                ),
            );
        }

        $table = array(
            "header" => array("Item Name", "Beg.Balance", "Total IN", "Total OUT","End Item Count", "Physical Count","Variance","End Balance","Physical Amount", "Variance"),
            "data" => $td
        );

        $data_array = array(
            "table" => $table,
            "coverage" => $firstDay." To ".$lastDay
        );
        echo json_encode($data_array);
    }


    public function recordDatatable() {
        $limit = $this->input->post('length');
        $offset = $this->input->post('start');
        $search = $this->input->post('search');
        $order = $this->input->post('order');
        $draw = $this->input->post('draw');
        $column_order = array('monthly_id');
        $join = array();
        $where = array("location" => 0);
        $select ="*,(SELECT fname FROM tbl_user_details WHERE user_id = tbl_monthly_inventory.recorded_by) as fname,(SELECT lname FROM tbl_user_details WHERE user_id = tbl_monthly_inventory.recorded_by) as lname";
        $group = array();
        $list = $this->MY_Model->get_datatables('tbl_monthly_inventory', $column_order, $select, $where, $join, $limit, $offset, $search, $order, $group);
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

    public function getAllDeliveriesByProduct($glob_date_from, $glob_date_to, $id, $location = 1) {
        $params["select"] = '*';
        $params['where'] = "(date_received between '$glob_date_from' and '$glob_date_to') AND location_from = " . $location;

        $res = $this->MY_Model->getRows("tbl_stocktransfer", $params);

        $total_count = 0;
        $total_qty = 0;
        foreach ($res as $key) {
            $items = json_decode($key["items_received"]);

            foreach ($items as $it) {
                if ($it->item_id == $id) {
                    $total_qty = $total_qty + $it->qty;
                    $total_count = $total_count + 1;
                };
            };
        };

        $data = array(
            "qty" => $total_qty,
            "count" => $total_count
        );

        return $data;
    }

    public function getAllPulloutByProduct($glob_date_from, $glob_date_to, $id, $location = 1) {
        $params["select"] = '*';
        $params['where'] = "(date_created between '$glob_date_from' and '$glob_date_to') AND location = " . $location;

        $res = $this->MY_Model->getRows("tbl_pull_out", $params);

        $total_count = 0;
        $total_qty = 0;
        foreach ($res as $key) {
            $items = json_decode($key["items"]);

            foreach ($items as $it) {
                if ($it->item_id == $id) {
                    $total_qty = $total_qty + $it->qty;
                    $total_count = $total_count + 1;
                };
            };
        };

        $data = array(
            "qty" => $total_qty,
            "count" => $total_count
        );

        return $data;
    }

    public function getAllPurchaseByProduct($glob_date_from, $glob_date_to, $id) {
        $params["select"] = '*';
        $params['where'] = "received_date between '$glob_date_from' and '$glob_date_to'";

        $res = $this->MY_Model->getRows("tbl_purchase_order", $params);
        $total_count = 0;
        $total_qty = 0;
        foreach ($res as $key) {
            $items = json_decode($key["quantity_received"]);

            foreach ($items as $it) {
                if ($it->item_id == $id) {
                    $total_qty = $total_qty + $it->qty;
                    $total_count = $total_count + 1;
                };
            };
        };

        $data = array(
            "qty" => $total_qty,
            "count" => $total_count
        );
        return $data;
    }

    public function getAllSoldByProduct($glob_date_from, $glob_date_to, $id, $location) {
        $params["select"] = 'items';
        $params['where'] = "(date_issued between '$glob_date_from' and '$glob_date_to') AND location = " . $location;

        $res = $this->MY_Model->getRows("tbl_sales", $params);

        $total_count = 0;
        $total_amt = 0;

        foreach ($res as $key) {
            $items = json_decode($key["items"]);

            foreach ($items as $it) {
                if ($it->item_id == $id) {
                    $total_amt = $total_amt + $it->total;
                    $total_count = $total_count + 1;
                };
            };
        };

        $data = array(
            "total_amount" => $total_amt,
            "count" => $total_count
        );

        return $data;
    }

    public function getAllDiscountsByProduct($glob_date_from, $glob_date_to, $id, $location) {
        $params["select"] = 'items';
        $params['where'] = "(date_issued between '$glob_date_from' and '$glob_date_to') AND location = " . $location;

        $res = $this->MY_Model->getRows("tbl_sales", $params);

        $total_discounts = 0;

        foreach ($res as $key) {
            $items = json_decode($key["items"]);

            foreach ($items as $it) {
                if ($it->item_id == $id) {
                    $total_discounts = $total_discounts + $it->discount;
                };
            };
        };

        return $total_discounts;
    }

    public function getAllPhysicalByProduct($glob_date_from, $glob_date_to, $id) {
        $params["select"] = '*';
        $params['where'] = array(
            "date_from" => $glob_date_from,
            "date_to" => $glob_date_to,
            "location" => 0
        );

        $res = $this->MY_Model->getRows("tbl_monthly_inventory", $params);

        $total_count = 0;
        $total_qty = 0;
        $end_bal = '';
        $beg_Bal = '';
        $phy_count = '';

        foreach ($res as $key) {
            $items = json_decode($key["recorded_items"]);

            foreach ($items as $it) {
                if ($it->item_id == $id) {
                    $beg_Bal = $it->beg_bal;
                    $end_bal = $it->end_bal_items;
                    $phy_count = $it->phy_count_items;
                    $total_qty = $total_qty + (int)$it->phy_count_items;
                    $total_count = $total_count + 1;
                };
            };
        };

        $data = array(
            "end_bal" => $end_bal,
            "beg_bal" => $beg_Bal,
            "qty" => $total_qty,
            "count" => $total_count,
            "phy_count" => $phy_count
        );
        return $data;
    }
}
