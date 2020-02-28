<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Discrepancy extends MY_Controller {
    public function __construct() {
        $this->load->model('DiscrepancyModel');
    }

    public function index() {
        $this->load_page('index');
    }

    public function getDiscrepancies() {
        $this->DiscrepancyModel->set_variables();
        $discrepancies = $this->DiscrepancyModel->getDiscrepancies();

        foreach ($discrepancies as $key => $disc_info) {
            $td[] = array(
                array(
                    'class' => 'test',
                    'data' => $disc_info['id']
                ),
                array(
                    'class' => 'test',
                    'data' => $disc_info['type']
                ),
                array(
                    'class' => 'test',
                    'data' => $disc_info['location']
                ),
                array(
                    'class' => 'test',
                    'data' => $disc_info['received_date']
                ),
                array(
                    'class' => 'test',
                    'data' => $disc_info['received_by']
                ),
                array(
                    'class' => 'test',
                    'data' => '<a href="javascript:;" class="btn btn-sm btn-info view-discrepancy" data-discrepancy_id="'.$disc_info['id'].'" data-toggle="modal" data-target="#discrepancy_modal">View Details</a>'
                ),
            );
        }

        $table_data = array(
            "header"    => array("ID", "Type", "Location", "Date Received", "Received By", "Actions"),
            "data"      => $td
        );


        echo json_encode($table_data);
    }

    public function getDiscrepancyItems() {
        $raw_id = explode('-', $this->input->post('disc_id'));

        $discrepancy_type = $raw_id[0];
        $discrepancy_id = $raw_id[1];

        $items = $this->DiscrepancyModel->getDiscrepancyItems($discrepancy_type, $discrepancy_id);

        echo json_encode($items);
    }
}
