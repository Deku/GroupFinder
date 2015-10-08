<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Email_model
 * 
 * Manages the email templates
 *
 * @author José González <maangx@gmail.com>
 */
class Email_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Gets a template from db
     * @param   int     $id
     * @return  array
     */
    public function get($id) {
        $this->db->where('mail_id', $id);
        $query = $this->db->get('mail_templates');
        
        if ($query->num_rows() > 0)
        {
            return $query->row();
        }
    }
}
