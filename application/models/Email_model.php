<?php

class Email_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getTemplate($id) {
        $this->load->database();
        
        $this->db->where('mail_id', $id);
        $query = $this->db->get('mail_templates');
        
        if ($query->num_rows() > 0)
        {
            return $query->row();
        }
    }
}
