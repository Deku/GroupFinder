<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Picture_model
 * 
 * Manages the profile pictures
 *
 * @author José González <maangx@gmail.com>
 */
class Picture_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Saves a user's profile picture
     * @param   array   $refs
     * @return  boolean
     */
    public function save_user_picture($refs) {
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
    
    /**
     * Get the user's profile picture in all sizes
     * @param   int     $user_id
     * @return  array
     */
    public function get_by_user($user_id) {
        $this->load->database();
        
        $this->db->select('img_src')
                 ->where('user_id', $user_id)
                 ->order_by('size', 'ASC');
        $query = $this->db->get('users_profile_pictures');
        
        return $query->result_array();
    }
    
    /**
     * Saves a project's profile picture
     * @param   array   $refs
     * @return  boolean
     */
    public function save_project_picture($refs) {
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