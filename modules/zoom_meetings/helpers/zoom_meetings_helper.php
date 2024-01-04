<?php defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('zoom_get_customers')) {
     /**
      * Fetches from database all staff assigned customers
      * If admin fetches all customers
      * @return array
      */
     function zoom_get_customers()
     {
          $CI = &get_instance();

          $staffCanViewAllClients = staff_can('view', 'customers');

          $CI->db->select('firstname, lastname, ' . db_prefix() . 'contacts.id as contact_id, ' . get_sql_select_client_company());
          $CI->db->where(db_prefix() . 'clients.active', '1');
          $CI->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid=' . db_prefix() . 'contacts.userid', 'left');
          $CI->db->select(db_prefix() . 'clients.userid as client_id');

          if (!$staffCanViewAllClients) {
               $CI->db->where('(' . db_prefix() . 'clients.userid IN (SELECT customer_id FROM ' . db_prefix() . 'customer_admins WHERE staff_id=' . get_staff_user_id() . '))');
          }

          $result = $CI->db->get(db_prefix() . 'contacts')->result_array();

          if ($CI->db->affected_rows() !== 0) {
               return $result;
          } else {
               return [];
          }
     }
}


/**
 * Function helper to get user details
 *
 * @param string $id
 * @return object
 */
function zoom_get_user_limited_details($id, $table)
{
     return ($table === 'leads')
          ? get_instance()->db->select("name, SUBSTRING_INDEX(SUBSTRING_INDEX(name, ' ', 1), ' ', -1) AS firstname, TRIM( SUBSTR(name, LOCATE(' ', name)) ) AS lastname, email,userid")->get_where(db_prefix() . $table, ['id' => $id])->row()
          : get_instance()->db->select('firstname, lastname, email,userid')->get_where(db_prefix() . $table, [($table === 'staff' ? 'staffid' : 'id') => $id])->row();
}

/**
 * Function helper to get staff details
 *
 * @param string $id
 * @return object
 */
function zoom_get_staff_limited_details($id, $table)
{
     return ($table === 'leads')
          ? get_instance()->db->select("name, SUBSTRING_INDEX(SUBSTRING_INDEX(name, ' ', 1), ' ', -1) AS firstname, TRIM( SUBSTR(name, LOCATE(' ', name)) ) AS lastname, email")->get_where(db_prefix() . $table, ['id' => $id])->row()
          : get_instance()->db->select('firstname, lastname, email,staffid')->get_where(db_prefix() . $table, [($table === 'staff' ? 'staffid' : 'id') => $id])->row();
}
/**
 * Get meeting type name
 *
 * @param inrenger $type
 * @return void
 */
function zoom_meeting_type($type)
{
     switch ($type) {
          case 1:
               $type = _l('zoom_instant_label');
               break;
          case 2:
               $type = _l('zoom_scheduled_label');
               break;
          case 3:
               $type = _l('zoom_recurring1_label');
               break;
          case 4:
               $type =  _l('zoom_recurring2_label');
               break;
          default:
               $type = _l('zoom_instant_label');
     }
     return $type;
}


if (!function_exists('zoom_redirect_after_event')) {
     /**
      * Helper redirect function with alert message
      *
      * @param string $type 'success' | 'danger'
      * @param string $message
      *
      * @return void
      */
     function zoom_redirect_after_event($type, $message, $path = null)
     {
          $CI = &get_instance();

          $CI->session->set_flashdata('message-' . $type . '', $message);

          if ($path) {
               redirect(admin_url('zoom_meeting_manager/index/') . $path);
          } else {
               redirect(admin_url('zoom_meeting_manager/index'));
          }
     }
}

/**
 * Holds all meeting hours
 *
 * @return array of hours
 */
function getZoomMinutes()
{
     return [
          ['value' => '0', 'name' => '0'],
          ['value' => '15', 'name' => '15'],
          ['value' => '30', 'name' => '30'],
          ['value' => '45', 'name' => '45']
     ];
}
/**
 * Holds all meeting hours
 *
 * @return array of hours
 */
function zoomGetHours()
{
     return [
          ['value' => '0', 'name' => '0'],
          ['value' => '60', 'name' => '1'],
          ['value' => '120', 'name' => '2'],
          ['value' => '180', 'name' => '3'],
          ['value' => '240', 'name' => '4'],
          ['value' => '300', 'name' => '5'],
          ['value' => '360', 'name' => '6'],
          ['value' => '420', 'name' => '7'],
          ['value' => '480', 'name' => '8'],
          ['value' => '540', 'name' => '9'],
          ['value' => '600', 'name' => '10'],
          ['value' => '660', 'name' => '11'],
          ['value' => '720', 'name' => '12'],
          ['value' => '780', 'name' => '13'],
          ['value' => '840', 'name' => '14'],
          ['value' => '900', 'name' => '15'],
          ['value' => '960', 'name' => '16'],
          ['value' => '1020', 'name' => '17'],
          ['value' => '1080', 'name' => '18'],
          ['value' => '1140', 'name' => '19'],
          ['value' => '1200', 'name' => '20'],
          ['value' => '1260', 'name' => '21'],
          ['value' => '1320', 'name' => '22'],
          ['value' => '1380', 'name' => '23'],
          ['value' => '1440', 'name' => '24']
     ];
}

function convertToHoursMinsZoom($time, $format = '')
{
     if ($time < 1) {
          return;
     }
     $lang['zoom_hours_and'] = 'Hours and';
     $lang['zoom_hours'] = 'Hours';
     $format = '%2d ' . _l('zoom_hours_and') . ' %02d ' . _l('zoom_minutes') . '';

     $hours = floor($time / 60);
     $minutes = ($time % 60);

     if ($hours == 1 && $minutes == '00') {
          $format = '%2d ' . ucfirst(_l('zoom_hour')) . '';
     } elseif ($hours > 1 && $minutes != '00') {
          $format = '%2d ' . _l('zoom_hours_and') . ' %02d ' . _l('zoom_minutes') . '';
     } elseif ($hours > 1 && $minutes == '00') {
          $format = '%2d ' . _l('zoom_hours') . '';
     }

     return ltrim(sprintf($format, $hours, $minutes));
}
