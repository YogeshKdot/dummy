<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Reports_model extends CI_Model
{
    public function chart_orders_of_the_week()
    {
        $qry='SELECT DAYNAME(order_date),
                SUM(qty) AS total_sales, product_name
                FROM '.db_prefix().'order_master
                join '.db_prefix().'order_items on '.db_prefix().'order_items.order_id = '.db_prefix().'order_master.id 
                join '.db_prefix().'product_master on '.db_prefix().'order_items.product_id = '.db_prefix().'product_master.id 
                where `order_date` 
                BETWEEN (select date_sub(CURDATE(),INTERVAL 1 WEEK)) 
                AND CURDATE()
                GROUP BY order_date, product_id';
        $query      = $this->db->query($qry);
        $array      = $query->result_array();
        $chart_data = [];
        $days       = [];
        $final      = [];
        foreach ($array as $w => $n) {
            $chart_data[$array[$w]['product_name']][$array[$w]['DAYNAME(order_date)']] = (int) $array[$w]['total_sales'];
        }
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $i    = 0;
        foreach ($chart_data as $product => $value) {
            $final[$i]['name'] = $product;
            foreach ($days as $day) {
                $final[$i]['data'][] = $value[$day] ?? 0;
            }
            ++$i;
        }
        $week_chart['days']                     =$days;
        (!empty($final)) ? $week_chart['series']=$final : $week_chart['series']=null;
        $week_chart['series']                   =$final;

        return $week_chart;
    }

    public function chart_orders_per_month()
    {
        $qry='SELECT CAST(MONTHNAME(order_date) AS CHAR(3)),
                SUM(qty) AS total_sales, product_name
                FROM '.db_prefix().'order_master
                join '.db_prefix().'order_items on '.db_prefix().'order_items.order_id = '.db_prefix().'order_master.id 
                join '.db_prefix().'product_master on '.db_prefix().'order_items.product_id = '.db_prefix().'product_master.id 
                where `order_date` 
                BETWEEN (select date_sub(CURDATE(),INTERVAL 1 MONTH)) 
                AND CURDATE()
                GROUP BY order_date, product_id';
        $query      = $this->db->query($qry);
        $array      = $query->result_array();
        $chart_data = [];
        $months     = [];
        foreach ($array as $w => $n) {
            $chart_data[$array[$w]['product_name']][$array[$w]['CAST(MONTHNAME(order_date) AS CHAR(3))']] = (int) $array[$w]['total_sales'];
        }
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $i      = 0;
        foreach ($chart_data as $product => $value) {
            $final[$i]['name'] = $product;
            foreach ($months as $day) {
                $final[$i]['data'][] = $value[$day] ?? 0;
            }
            ++$i;
        }
        $month_chart['months']                   =$months;
        (!empty($final)) ? $month_chart['series']=$final : $month_chart['series']=null;

        return $month_chart;
    }

    public function chart_orders_per_year()
    {
        $qry='SELECT YEAR(order_date),
                SUM(qty) AS total_sales, product_name
                FROM '.db_prefix().'order_master
                join '.db_prefix().'order_items on '.db_prefix().'order_items.order_id = '.db_prefix().'order_master.id 
                join '.db_prefix().'product_master on '.db_prefix().'order_items.product_id = '.db_prefix().'product_master.id 
                where `order_date` 
                BETWEEN (select date_sub(CURDATE(),INTERVAL 1 YEAR)) 
                AND CURDATE()
                GROUP BY order_date, product_id';
        $query      = $this->db->query($qry);
        $array      = $query->result_array();
        $chart_data = [];
        $years      = [];
        foreach ($array as $w => $n) {
            $chart_data[$array[$w]['product_name']][$array[$w]['YEAR(order_date)']] = (int) $array[$w]['total_sales'];
        }
        $years = range(date('Y'), 2019);
        $i     = 0;
        foreach ($chart_data as $product => $value) {
            $final[$i]['name'] = $product;
            foreach ($years as $day) {
                $final[$i]['data'][] = $value[$day] ?? 0;
            }
            ++$i;
        }
        $year_chart['years']                    =$years;
        (!empty($final)) ? $year_chart['series']=$final : $year_chart['series']=null;

        return $year_chart;
    }

    public function chart_custom_date_range($selected_products, $from, $to)
    {
        $qry='SELECT order_date,
                SUM(qty) AS total_sales, product_name
                FROM '.db_prefix().'order_master
                join '.db_prefix().'order_items on '.db_prefix().'order_items.order_id = '.db_prefix().'order_master.id 
                join '.db_prefix().'product_master on '.db_prefix().'order_items.product_id = '.db_prefix().'product_master.id 
                where (
                (`order_date` BETWEEN "'.$from.'" AND "'.$to.'")
                AND
                product_name IN ("'.$selected_products.'")
                )
                GROUP BY order_date, product_id
                ORDER BY order_date';
        $query           = $this->db->query($qry);
        $array           = $query->result_array();
        $chart_data      = [];
        $date_range      = [];
        foreach ($array as $w => $n) {
            if (!in_array($array[$w]['order_date'], $date_range)) {
                $date_range[] = $array[$w]['order_date'];
            }
            $chart_data[$array[$w]['product_name']][$array[$w]['order_date']] = (int) $array[$w]['total_sales'];
        }
        $i = 0;
        foreach ($chart_data as $product => $value) {
            $final[$i]['name'] = $product;
            foreach ($date_range as $day) {
                $final[$i]['data'][] = $value[$day] ?? 0;
            }
            ++$i;
        }
        $year_chart['date_range']               =$date_range;
        (!empty($final)) ? $year_chart['series']=$final : $year_chart['series']=null;

        return $year_chart;
    }
}
