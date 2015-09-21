<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Picture_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function saveProfileImageRef($refs) {
        $this->load->database();
        
        if (count($refs) > 0)
        {
            foreach ($refs as $ref)
            {
                $this->db->replace('users_profile_pictures', $ref);
            }
            return true;
        }
        
        return false;
    }
    
    public function getProfilePics($user_id) {
        $this->load->database();
        
        $this->db->select('img_src')
                 ->where('user_id', $user_id)
                 ->order_by('size', 'ASC');
        $query = $this->db->get('users_profile_pictures');
        
        return $query->result_array();
    }
    
    public function saveProjectImage($refs) {
        if (count($refs) > 0)
        {
            foreach ($refs as $ref)
            {
                $this->db->replace('projects_picture', $ref);
            }
            return true;
        }
        
        return false;
    }
}