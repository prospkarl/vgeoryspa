<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_movement extends MY_Controller {
    public function index() {
        $data['date_from'] = Date('F d, Y', strtotime('first day of this month'));
        $data['date_to'] = Date('F d, Y');
        $this->load_page('index', $data);
    }

    public function get_data(){
        $glob_date_from = Date('Y-m-d', strtotime('first day of this month'));;
        $glob_date_to= Date('Y-m-d');
        $params['select'] ="*";
        $res = $this->MY_Model->getRows('tbl_products', $params);
        $td = array();

        foreach ($res as $key) {
            $sold = $this->getAllSoldByProduct($glob_date_from, $glob_date_to, $key['product_id'], 1)['total_qty'];
            $deliveries = $this->getAllDeliveriesByProduct($glob_date_from, $glob_date_to, $key['product_id'], 1)['qty'];
            $purchases = (int)$this->getAllPurchaseByProduct($glob_date_from, $glob_date_to, $key['product_id'])['qty'];
            $pull_outs = $this->getAllPulloutByProduct($glob_date_from, $glob_date_to, $key['product_id'])['qty'];
            $current_stock = $this->getCurrentStock($key['product_id']);
            $system_ending = ($key['beg_bal'] - $sold - $deliveries - $pull_outs ) + $purchases;

            $is_not_balance = false;

            if ($system_ending != $current_stock) {
                $is_not_balance = true;
            }

            $td[] = array(
                array(
                    "class" => 'name',
                    "data" => $key['name']
                ),
                array(
                    "class" => 'beg_bal',
                    "data" => $key['beg_bal']
                ),
                array(
                    "class" => 'sold text_red',
                    "data" => $sold ? '-'.$sold : 0
                ),
                array(
                    "class" => 'deliveries text_red',
                    "data" => $deliveries ? '-'.$deliveries : 0
                ),
                array(
                    "class" => 'purchases text_good',
                    "data" => $purchases ? '+'.$purchases : 0
                ),
                array(
                    "class" => 'pull_out text_red',
                    "data" => $pull_outs ? '-'.$pull_outs : 0
                ),
                array(
                    "class" => 'system_ending ' . ($is_not_balance ? 'bg-warning' : ''),
                    "data" => $system_ending
                ),
                array(
                    "class" => 'ending ' . ($is_not_balance ? 'bg-warning' : ''),
                    "data" => $current_stock
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
                "System Ending",
                "Ending",
                ),
            "data" => $td
        );

        echo json_encode($table_data);
    }

    public function getAllSoldByProduct($glob_date_from, $glob_date_to, $id, $location) {
        $params["select"] = 'items';
        $params['where'] = "(date_issued between '$glob_date_from' and '$glob_date_to') AND location = " . $location;

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

    public function getAllDeliveriesByProduct($glob_date_from, $glob_date_to, $id, $location = 1) {
        $params["select"] = '*';
        $params['where'] = "(date_added between '$glob_date_from' and '$glob_date_to') AND location_from = " . $location;

        $res = $this->MY_Model->getRows("tbl_stocktransfer", $params);

        $total_count = 0;
        $total_qty = 0;
        foreach ($res as $key) {
            $items = !empty($key["items_received"]) ? $key["items_received"] : $key['items'];

            $items = json_decode($items);

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

    public function getCurrentStock($product_id) {
        $items_params['where'] = array('product_id' => $product_id, 'location' => 1);
        $qty = $this->MY_Model->getRows('tbl_stocks', $items_params, 'row_array')['qty'];
        return $qty;
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
}
