<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends MY_Controller {
    public $buttons = array(
        'makepurchase' => array(
            'title' => 'Make a purchase',
            'subtitle' => 'Create Sales Order',
            'link' => 'sales_order/makeapurchase',
            'icon' => 'fas fa-shopping-bag',
            'color' => 'success'
        ),
        'reports' => array(
            'title' => 'Today\'s Sales',
            'subtitle' => 'Sales Report for today',
            'link' => 'salestoday',
            'icon' => 'fas fa-chart-line',
            'color' => 'danger'
        ),
        'inventory' => array(
            'title' => 'Inventory',
            'subtitle' => 'Check kiosk items',
            'link' => 'products',
            'icon' => 'fas fa-clipboard-list',
            'color' => 'primary'
        ),
        'requestitems' => array(
            'title' => 'Replenishments',
            'subtitle' => 'Request Items to CSO',
            'link' => 'request_items',
            'icon' => 'fas fa-box-open',
            'color' => 'info'
        ),
        'recieveitems' => array(
            'title' => 'Receive Items',
            'subtitle' => 'Receive Items from CSO',
            'link' => 'receive',
            'icon' => 'mdi mdi-inbox-arrow-down',
            'color' => 'violet'
        ),
    );


	function __construct(){
		 parent::__construct();
		 $this->load->helper(array('form', 'url'));
		 $this->load->library('form_validation');
	}

    public function index(){
        $data['hide_breadcrumbs'] = true;
        $data['ci'] = $this;

        $options['where'] = array('location_id <>' => '1');
        $data['locations'] = $this->MY_Model->getRows('tbl_locations', $options);

        $sales_target_op['where'] = array('option_name' => 'target_sales_week');

        $sales_target = $this->MY_Model->getRows('tbl_options', $sales_target_op, 'row_array')['option_value'];

        $data['sales_target'] = '₱' . number_format($sales_target);

    	$this->load_page('index', $data);
    }

    public function setlocation() {
        if ($this->input->post('location') != '') {
            $this->session->set_userdata(array('location' => $this->input->post('location')));

            $user_options['select'] = 'fname, lname';
            $user_options['where'] = array('user_id' => $this->session->id);
            $user_info = $this->MY_Model->getRows('tbl_user_details', $user_options, 'row_array');

            ajax_response('Welcome ' . ucwords($user_info['fname']) . ' ' . ucwords($user_info['lname']) . '!', 'success');
        }else {
            ajax_response('Please select a location', 'error');
        }

    }

    public function render_buttons(){
        $html = '';

        foreach ($this->buttons as $key => $buttoninfo) {
            $html .= '<div class="button-container">';
            $html .= '    <div class="sales-btn card" onclick="window.location.href=`'.$buttoninfo['link'].'`">';
            $html .= '        <div class="progress">';
            $html .= '            <div class="progress-bar bg-'.$buttoninfo['color'].'" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:100%; height:7px;"> <span class="sr-only">50% Complete</span></div>';
            $html .= '        </div>';
            $html .= '        <div class="card-body d-flex justify-content-sm-center align-items-sm-center inner-btn">';
            $html .= '            <div class="text-center faa-parent animated-hover">';
            $html .= '                <i class="sales-btn-icon fa faa-tada '.$buttoninfo['icon'].' m-b-30 text-'.$buttoninfo['color'].'"></i>';
            $html .= '                <h3 class="btn-title">'.$buttoninfo['title'].'</h3>';
            $html .= '                <p>'.$buttoninfo['subtitle'].'</p>';
            $html .= '            </div>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</div>';
        }

        echo "$html";
    }

    public function get_sales_this_week($location) {
        $start_date = date("Y-m-d", strtotime('monday this week'));
        $end_date = date("Y-m-d");

        $sales_op['select'] = "SUM(total_amount) as total_sales";
        $sales_op['where_sub'] = "date_issued between '".$start_date."' and '".$end_date."'";
        $sales_op['where'] = array(
            'location' => $location,
            'status' => 1
        );

        $sales = $this->MY_Model->getRows('tbl_sales', $sales_op, 'row_array');

        echo '₱' . number_format($sales['total_sales']);
    }
}
