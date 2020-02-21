<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Acquisition extends MY_Controller {
    public function index() {
        $this->load_page('index');
    }

    public function submitAcquisition() {
        for ($i=0; $i < count($this->input->post('id')); $i++) {
            $data = array(
                "acquisition" => $this->input->post('ac_cost')[$i],
                "acquisition_percentage" =>  $this->input->post('cost')[$i],
                "pass_on_cost" => $this->input->post('ps_cost')[$i],
                "pass_on_cost_percentage" =>  $this->input->post('passOn')[$i],
            );
            $where = array(
                'product_id' =>$this->input->post('id')[$i]
            );
            $res = $this->MY_Model->update('tbl_products', $data, $where);
        }

        ajax_response('Acquisition Settings Saved', 'success');
    }

    public function setCost() {
        $td = array();
        $params['select'] = '*';
        // $params['where'] = "product_id = 1 OR product_id = 15 OR product_id = 16";
        $res = $this->MY_Model->getRows('tbl_products', $params);
        foreach ($res as $key) {
            $td[] = array(
                array(
                    "class" => 'test',
                    "data" => array(
                        $key['name']
                    )
                ),

                array(
                    "class" => 'supplier',
                    "data" => array(
                        $key['supplier_price'],
                        array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "priced_static",
                                "name" => "",
                                "value" => $key['supplier_price'],
                        ),
                    )
                ),
                array(
                    "class" => 'price',
                    "data" => array(
                        "&#x20B1; " . $key['price']
                    )
                ),
                array(
                    "class" => 'cost_css',
                    "data" =>  array(
                        array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "",
                                "name" => "id[]",
                                "value" => $key['product_id'],
                            ),
                        array(
                                "kind" => 'input',
                                "type" => 'number',
                                "class" => "per_cost form-control form-control-sm",
                                "name" => "cost[]",
                                "value" => ($key['acquisition_percentage'] != 0) ? $key['acquisition_percentage'] : "0",
                            ),
                        array(
                                "kind" => 'input',
                                "type" => 'hidden',
                                "class" => "ac_cost",
                                "name" => "ac_cost[]",
                                "value" => ($key['acquisition'] != 0) ? $key['acquisition'] : "0",
                            ),
                    )
                ),
                array(
                    "class" => 'ac_cost_html',
                    "data" =>  ($key['acquisition'] != 0) ? '₱ ' . number_format($key['acquisition'], 2) : "0",
                ),
                array(
                    "class" => 'cost_css',
                    "data" =>  array(
                        array(
                                "kind" => 'input',
                                "type" => 'number',
                                "class" => "passOn form-control form-control-sm",
                                "name" => "passOn[]",
                                "value" => ($key['pass_on_cost_percentage'] != 0) ? $key['pass_on_cost_percentage'] : "0",
                            ),
                            array(
                                    "kind" => 'input',
                                    "type" => 'hidden',
                                    "class" => "passOn_html",
                                    "name" => "ps_cost[]",
                                    "value" => ($key['pass_on_cost'] != 0) ? $key['pass_on_cost'] : "0",
                                ),

                    )
                ),
                array(
                    "class" => 'pass_cost',
                    "data" =>  ($key['pass_on_cost'] != 0) ? '₱ ' . number_format($key['pass_on_cost'], 2) : "0",
                ),
            );
        }

        $data_array = array(
            "header" => array("Product Name", "Supplier's Price", "SRP", "Acquisition %<button data-id='ac' class='set_all btn btn-xs btn-info' style='float:right'>Set All</button>","Acquisition Cost","Passed on Cost %<button data-id='pa' class='set_all btn btn-xs btn-info' style='float:right'>Set All</button>","Passed on Cost"),
            "data" => $td
        );

        echo json_encode($data_array);
    }
}
