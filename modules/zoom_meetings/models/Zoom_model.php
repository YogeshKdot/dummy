<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Zoom_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

   
    public function update_meeting_settings($data){

        
        $this->db->where('id', 1);
        $this->db->update(db_prefix() . 'zoom', $data);
        return true;

    }     public function update_client_meeting_settings($data){                $id=$data['client_id'];        $this->db->where('client_id', $id);        $this->db->update(db_prefix() . 'zoom_client', $data);        return true;    }	    public function insert_meeting($data){                $this->db->insert(db_prefix() . 'zoom_meetings', $data);        return true;    }		    public function insert_client_meeting($data){                $this->db->insert(db_prefix() . 'zoom_client_meetings', $data);        return true;    }		  public function insert_notificatons($data){                $this->db->insert(db_prefix() . 'notifications', $data);        return true;    }		public function delete_meeting($id){								$this->db->where('meeting_id =',$id);		$this->db->delete(db_prefix() . 'zoom_meetings');					}			public function delete_client_meeting($id){								$this->db->where('meeting_id =',$id);		$this->db->delete(db_prefix() . 'zoom_client_meetings');					}		public function check_meeting_exist($id){		
        $this->db->where('meeting_id =',$id);		$array = $this->db->get(db_prefix() . 'zoom_meetings')->result_array();		return $array ;    }		public function check_client_meeting_exist($id){		        $this->db->where('meeting_id =',$id);		$array = $this->db->get(db_prefix() . 'zoom_client_meetings')->result_array();		return $array ;    }	
    public function get_api_settings(){

        $this->db->where('id =', 1);
		$array = $this->db->get(db_prefix() . 'zoom')->result_array();
		return $array ;
    }		public function get_client_api_settings($id){        $this->db->where('id =', $id);		$array = $this->db->get(db_prefix() . 'zoom_client')->result_array();		return $array ;    }		public function get_client_meetings($id){				$this->db->where('client_id =', $id);		$array = $this->db->get(db_prefix() . 'zoom_client_meetings')->result_array();		return $array ;				    } 
	
}
