<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_analysis extends MY_Controller {
    public function index(){
        $locations_op['where'] = array('status' =>0);
        $data['locations'] = $this->MY_Model->getRows('tbl_locations', $locations_op);
        $this->load_page("index", $data);
    }

    public function get_data(){
        $location =  $this->input->post('location') ? $this->input->post('location') : 'all';
        $date_from = $this->input->post('date_from') ? $this->input->post('date_from') : Date('Y-m-d', strtotime('first day of this month'));
        $date_to = $this->input->post('date_to') ? $this->input->post('date_to') : Date('Y-m-d');

        $glob_date_from = Date('Y-m-d', strtotime($date_from));
        $glob_date_to = Date('Y-m-d', strtotime($date_to));

        $params['select'] ="*";
        $res = $this->MY_Model->getRows('tbl_products', $params);
        $td = array();

        foreach ($res as $key) {
            $sold = $this->getAllSoldByProduct($glob_date_from, $glob_date_to, $key['product_id'], $location)['total_qty'];
            $total = $sold * $key['price'];

            $td[] = array(
                array(
                    "class" => 'name',
                    "data" => $key['name']
                ),
                array(
                    "class" => 'sold',
                    "data" => $sold
                ),
                array(
                    "class" => 'total',
                    "data" => number_format($total, 2)
                ),
            );
        }

        $table_data = array(
            "header" => array(
                "Product Name",
                "Items Sold (pcs)",
                "Total Amount Sold (₱)",
                ),
            "data" => $td
        );

        echo json_encode($table_data);
    }

    public function getAllSoldByProduct($glob_date_from, $glob_date_to, $id, $location) {
        $params["select"] = 'items';
        $params['where'] = "(date_issued between '$glob_date_from' and '$glob_date_to')";

        if ($location != 'all') {
            $params['where'] .= " AND location = " . $location;
        }

        $res = $this->MY_Model->getRows("tbl_sales", $params);

        $total_count = 0;
        $total_amt = 0;
        $total_qty = 0;

        foreach ($res as $key) {
            $items = json_decode($key["items"]);

            foreach ($items as $it) {
                if ($it->item_id == $id) {
                    $total_amt = $total_amt + $it->total;
                    $total_count = $total_count + 1;
                    $total_qty = $total_qty + $it->qty;
                };
            };
        };

        $data = array(
            "total_amount" => $total_amt,
            "count" => $total_count,
            "total_qty" => $total_qty,
        );

        return $data;
    }
}

?>
