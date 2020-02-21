<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Void_requests extends MY_Controller{
    public $pending = array();

    function __construct() {
        parent::__construct();
    }

    public function index()  {
        $pending_op['where'] = array(
            'status' => 0
        );

        $pending = $this->MY_Model->getRows('tbl_sales', $pending_op);

        $data['render_row'] = $this->render_row($pending);

        $this->load_page('index', $data);
    }

    public function approve($sales_id) {
        $options['select'] = '
            tbl_sales.sales_id,
            tbl_sales.display_id,
            tbl_sales.customer_name,
            tbl_sales.customer_contact,
            tbl_sales.customer_email,
            tbl_sales.items,
            tbl_sales.date_issued,
            tbl_sales.payment_method,
            tbl_sales.total_items,
            tbl_sales.total_discount,
            tbl_sales.total_amount,
            tbl_sales.remarks,
            tbl_sales.status,
            tbl_sales.void_to,
            tbl_user_details.fname,
            tbl_user_details.lname,
            tbl_locations.name as location
        ';

        $options['where'] = array(
            'sales_id' => $sales_id
        );

        $options['join'] = array(
            'tbl_user_details' => 'tbl_sales.issued_by = tbl_user_details.user_id',
            'tbl_locations' => 'tbl_sales.location = tbl_locations.location_id',
        );
        $invoice = $this->MY_Model->getRows('tbl_sales', $options, 'row_array');

        $items_parsed = json_decode($invoice['items']);

        foreach ($items_parsed as $key => $item) {
            $item_options['select'] = 'name';

            $item_options['where'] = array(
                'product_id' => $item->item_id
            );

            $item_info = $this->MY_Model->getRows('tbl_products', $item_options, 'row_array');

            $items_parsed[$key]->item_name = $item_info['name'];
        }

        $invoice['items_parsed'] = $items_parsed;

        switch ($invoice['status']) {
            case 0:
                $invoice['status'] = 'VOID PENDING';
                $invoice['status_color'] = 'warning';
                break;
            case 1:
                $invoice['status'] = 'COMPLETED';
                $invoice['status_color'] = 'info';
                break;
            case 2:
                $invoice['status'] = 'VOID';
                $invoice['status_color'] = 'danger';
                break;
        }

        $data['invoice'] = $invoice;

        //New
        $new_options['select'] = '
            tbl_sales.sales_id,
            tbl_sales.display_id,
            tbl_sales.customer_name,
            tbl_sales.customer_contact,
            tbl_sales.customer_email,
            tbl_sales.items,
            tbl_sales.date_issued,
            tbl_sales.payment_method,
            tbl_sales.total_items,
            tbl_sales.total_discount,
            tbl_sales.total_amount,
            tbl_sales.remarks,
            tbl_sales.status,
            tbl_user_details.fname,
            tbl_user_details.lname,
            tbl_locations.name as location
        ';

        $new_options['where'] = array('sales_id' => $invoice['void_to']);

        $new_options['join'] = array('tbl_user_details' => 'tbl_sales.issued_by = tbl_user_details.user_id', 'tbl_locations' => 'tbl_sales.location = tbl_locations.location_id');
        $data['new_invoice'] = $this->MY_Model->getRows('tbl_sales', $new_options, 'row_array');

        $new_items_parsed = json_decode($data['new_invoice']['items']);

        foreach ($new_items_parsed as $key => $item) {
            $item_options['select'] = 'name';

            $item_options['where'] = array(
                'product_id' => $item->item_id
            );

            $item_info = $this->MY_Model->getRows('tbl_products', $item_options, 'row_array');

            $new_items_parsed[$key]->item_name = $item_info['name'];
        }

        $data['new_invoice']['new_items_parsed'] = $new_items_parsed;

        if (empty($data['invoice'])) {
            die('Invoice does not exist');
        }

        $data['hide_breadcrumbs'] = true;
        $this->load_page('approve', $data);
    }

    public function render_row($pending){
        $html = '';

        foreach ($pending as $sale_info) {
            $html .= '<tr>';
            $html .= '<td>' . $sale_info['display_id'] . '</td>';
            $html .= '<td>' . $sale_info['issued_by'] . '</td>';
            $html .= '<td>' . $sale_info['customer_name'] . '</td>';
            $html .= '<td>' . $sale_info['date_issued'] . '</td>';
            $html .= '<td>' . $sale_info['total_items'] . '</td>';
            $html .= '<td>' . $sale_info['total_amount'] . '</td>';
            $html .= '<td>' . $this->getStatus($sale_info['status']) . '</td>';
            $html .= '<td><a class="btn btn-sm btn-rounded btn-outline-success" href="' . base_url('void_requests/approve/' . $sale_info['sales_id']) . '"><i class="fas fa-eye"></i> View</a></td>';
            $html .= '</tr>';
        }

        return $html;
    }

    public function getStatus($status){
        $final_status = '';
        $status_color = '';

        switch ($status) {
            case 0:
                $final_status .= 'Pending';
                $status_color .= 'warning';
                break;
            case 1:
                $final_status .= 'Completed';
                $status_color .= 'info';
                break;
            case 2:
                $final_status .= 'Void';
                $status_color .= 'danger';
                break;
        }

        $html = '<span class="label label-' . $status_color . ' label-rounded"> ' . $final_status . ' </span>';

        return $html;
    }

    public function submit(){
        $action = $this->input->post('action');
        $sales_id = $this->input->post('sales_id');

        // Get sales items
        $general_op['where'] = array( 'sales_id' => $sales_id );

        if ($action == 'approve') {
            $sales_info = $this->MY_Model->getRows('tbl_sales', $general_op, 'row_array');
            $location = $sales_info['location'];
            $items = json_decode($sales_info['items']);

            foreach ($items as $item_info) {
                $where = array( 'product_id' => $item_info->item_id, 'location' => $location );
                $current_qty = $this->MY_Model->getRows('tbl_stocks', array('where' => $where),'row_array');

                $set = array(
                    'qty' => (int)$current_qty['qty'] + $item_info->qty
                );

                $this->MY_Model->update('tbl_stocks', $set, $where);

                $inventory_movement_set = array( 'item_qty' => 0 );
                $inventory_movement_where = array(
                    'reference_id' => $sales_id,
                    'item_id' => $item_info->item_id
                );
                $this->MY_Model->update('tbl_inventory_movement', $inventory_movement_set, $inventory_movement_where);
            }

            $set = array('status' => 2, 'voided_by' => $this->session->id);
            $this->MY_Model->update('tbl_sales', $set, $general_op['where']);

			$this->MY_Model->delete('tbl_purchased_items', array('purchase_id' => $sales_id));

            ajax_response('Successfully updated!', 'success');
        }else {
            $set = array('status' => 1);
            $this->MY_Model->update('tbl_sales', $set, $general_op['where']);

            ajax_response('Successfully updated!', 'success');
        }
    }
}
