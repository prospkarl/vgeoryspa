<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DiscrepancyModel extends CI_Model {
    private $from_date;
    private $to_date;
    private $type_of_discrepancy = 'all';

    public function set_variables() {
        $from_date = $this->input->post('from_date') ? $this->input->post('from_date') : 'first day of this month';
        $to_date = $this->input->post('to_date') ? $this->input->post('to_date') : Date('Y-m-d');

        $this->from_date = Date('Y-m-d', strtotime($from_date));
        $this->to_date = Date('Y-m-d', strtotime($to_date));
        $this->type_of_discrepancy = $this->input->post('status');
    }

    public function getDiscrepancies() {
        $final_query = '';

        $query_purchase_order = "
            SELECT
            CONCAT
                ('PO', '-', poid) as id,
                'PURCHASE ORDER' as type,
                'CSO' as location,
                received_date,
                    (
                        SELECT
                            CONCAT
                                (fname, ' ', lname)
                        FROM
                            tbl_user_details
                        WHERE
                            user_id = tbl_purchase_order.received_by
                    ) as received_by
            FROM
                tbl_purchase_order
            WHERE
                with_discrepancy = 1 ";

        $query_stock_transfer  = "
            SELECT
            CONCAT
                ('TRANS', '-', transfer_id) as id,
                'STOCK TRANSFER' as type,
                    (
                        SELECT
                            name
                        FROM
                            tbl_locations
                        WHERE
                            location_id = tbl_stocktransfer.location_to
                    ) as location,
                date_received as received_date,
                (
                    SELECT
                        CONCAT
                            (fname, ' ', lname)
                    FROM
                        tbl_user_details
                    WHERE
                        user_id = tbl_stocktransfer.received_by
                ) as received_by
            FROM
                tbl_stocktransfer
            WHERE
                with_discrepancy = 1
        ";

        $query_purchase_order .= "AND received_date BETWEEN '$this->from_date' AND '$this->to_date'";
        $query_stock_transfer .= "AND date_received BETWEEN '$this->from_date' AND '$this->to_date'";

        switch ($this->type_of_discrepancy) {
            case 'all':
                $final_query = $query_purchase_order . "UNION" . $query_stock_transfer;
                break;
            case 'po':
                $final_query = $query_purchase_order;
                break;
            case 'transfer':
                $final_query = $query_stock_transfer;
                break;
        }

        $query = $this->db->query($final_query);

        return $query->result_array();
    }

    public function getDiscrepancyItems($type, $discrepancy_id){
        $options = array();

        switch ($type) {
            case 'PO':
                $options['field'] = 'poid';
                $options['table'] = 'tbl_purchase_order';
                $options['items_field'] = 'quantity_received';
                $options['req_items_field'] = 'items';
                break;
            case 'TRANS':
                $options['field'] = 'transfer_id';
                $options['table'] = 'tbl_stocktransfer';
                $options['items_field'] = 'items_received';
                $options['req_items_field'] = 'items';
                break;
            default:
                return false;
                break;
        }

		$params['where'] = array($options['field'] => $discrepancy_id);
		$res = $this->MY_Model->getRows($options['table'], $params, 'row_array');

		$items = json_decode($res[$options['items_field']]);
		$req_items = json_decode($res[$options['req_items_field']]);
		$with_discrepancies = array();

		foreach ($items as $key => $value) {
			$received_item = $this->get_array_info($req_items, array('item_id' => $value->item_id));
            if (is_object($received_item)) {
                if ($received_item->qty != $value->qty) {
                    $value->prod_name = $this->getName($value->item_id);
                    $value->requested_qty = $received_item->qty;
                    $with_discrepancies[] = $value;
                }
            }else {
                if ($received_item['qty'] != $value->qty) {
                    $value->prod_name = $this->getName($value->item_id);
                    $value->requested_qty = $received_item['qty'];
                    $with_discrepancies[] = $value;
                }
            }
		}

		$data = array(
			"discrepancy_items" => $with_discrepancies,
			"reason" => $res['reason_for_disc'] ? $res['reason_for_disc'] : 'None'
		);

		return $data;
	}

    public function get_array_info($array, $find) {
        foreach ($array as $key => $info) {
            foreach ($find as $key => $value) {
                if ($info->{$key} == $value) {
                    return $info;
                }
            }
        }
    }

    public function getName($prod_id){
        $params['where'] = array("product_id"=> $prod_id);
        $res = $this->MY_Model->getRows("tbl_products", $params);
        return json_encode($res[0]['name']);
    }
}
