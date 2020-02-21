<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_analysis extends MY_Controller {
    public function index(){
        $this->load_page("index");
    }

    public function checkAvg(){
        $array_hold = array();
        $params_check['select'] = "product_id,date_added,name";
        $items = $this->MY_Model->getRows('tbl_products', $params_check);
        // =================== diri kuwaon niya ang mga dates nga na sold
        foreach ($items as $key) {
            $temp = array();
            array_push($temp, $key['date_added']);
            $sales_date = $this->item_exist($key['product_id']);
            for ($i=0; $i < count($sales_date); $i++) {
                if ($i != count($sales_date)-1) {
                    if ($sales_date[$i] != $sales_date[$i+1]) {
                        array_push($temp, $sales_date[$i]);
                    }
                }else {array_push($temp, $sales_date[$i]);}
            }
            $array_hold[$key['product_id']] = array("name" => $key['name'], "dates" => $temp);
        }
        // =================== kani ari kay iya ge kuha ang mga average
        $margin_day = 0; $all_sales= 0; $speeed_margin=0; $all_dates_items = array();
        foreach ($array_hold as $key) {
            $days_average = 0; $total_day = 0;
            if (empty($key['dates']) != 1) {
                for ($i=0; $i < count($key['dates']); $i++) {
                    if ($i != count($key['dates'])-1) {
                        $days = abs(strtotime($key['dates'][$i + 1]) - strtotime($key['dates'][$i]))/86400;
                        $total_day = $total_day + $days;
                    }elseif (count($key['dates']) == 1) { $total_day = $total_day + 0; }
                }

                $all_dates_items[$key['name']] = array(
                    "name" => $key['name'],
                    "average" => (int)$days_average = $total_day/count($key['dates'])
                );
            }
            $margin_day = $margin_day + (int)$days_average;
            $all_sales = $all_sales + 1;
        }

        // =================== this part kay iya ge segregate ang FAST ug SLOW
        $speeed_margin =  $margin_day/$all_sales;
        $fast_moving = array(); $slow_moving= array();
        foreach ($all_dates_items as $key) {
            if ($key['average'] > $speeed_margin && $key['average'] != 0) {
                $slow_moving[] = array(
                    "average" => $key['average'],
                    "name" => $key['name'],
                );
            }elseif($key['average'] <= $speeed_margin  && $key['average'] != 0) {
                $fast_moving[] = array(
                    "average" => $key['average'],
                    "name" => $key['name'],
                );
            }elseif ($key['average'] == 0) {
                $slow_moving[] = array(
                    "average" => $key['average'],
                    "name" => $key['name'],
                );
            }
        }
        // =================== diri kay pag himo sa display ===================
        $fast_str = ""; $slow_Str = "";
        asort($slow_moving); asort($fast_moving);
        foreach ($slow_moving as $key) {
            $slow_Str .= "<tr>";
            $slow_Str .= "<td>".$key['name']."</td>";
            $slow_Str .= "<td>".$key['average']."</td>";
            $slow_Str .= "</tr>";
        }

        foreach ($fast_moving as $key) {
            $fast_str .= "<tr>";
            $fast_str .= "<td>".$key['name']."</td>";
            $fast_str .= "<td>".$key['average']."</td>";
            $fast_str .= "</tr>";
        }

        $result = array(
            "slow" => $slow_Str,
            "fast" => $fast_str
        );

        echo json_encode($result);
    }

    public function item_exist($id){
        $today = date('m');
        $dates_array = array();
        $params_sold['where'] = "MONTH(date_issued) = $today";
        $items_sold = $this->MY_Model->getRows('tbl_sales', $params_sold);
        foreach ($items_sold as $items) {
            $items_array = json_decode($items['items']);
            foreach ($items_array as $key) {
                if ($id == $key->item_id) {
                    array_push($dates_array, $items['date_issued']);
                }
            }
        }
        return $dates_array;
    }


}

?>
