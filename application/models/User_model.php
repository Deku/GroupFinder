<?php

/**
 * User_model
 *
 * Administra la informacion asociada a los perfiles de usuario.
 * 
 * @author Jose Gonzalez
 */
class User_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function isVisible($user_id) {
        $this->db->select('hidden');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('users');
        
        if ($query->num_rows() > 0)
        {
            return $query->row()->hidden;
        }
    }
    
    public function getProfile($user_id) {
        $query = $this->db->select('name, email, birthday, title, country_name, about, created, img_src')
                            ->where(array('users.user_id' => $user_id, 'size' => 297))
                            ->join('countries', 'users.country = countries.country_id')
                            ->join('users_profile_pictures', 'users_profile_pictures.user_id = users.user_id')
                            ->get('users');
        
        if ($query->num_rows() > 0)
        {
            return $query->row();
        }
        
        return null;
    }
    
    public function updateProfile($user_id, $updates) {
        $this->db;
        return $this->db->where('user_id', $user_id)
                        ->update('users', $updates);
    }
    
    public function addFriendsRequest($user_id, $target_id) {
        return $this->db->insert('users_friends', array('user_a' => $user_id, 'user_b' => $target_id));
    }
    
    public function acceptFriendRequest($request_id) {
        return $this->db->where('relation_id', $request_id)
                        ->update('users_friends', array('relation_status' => 1, 'confirmation_time' => date("Y-m-d H:i:s")));
    }
    
    public function rejectFriendRequest($request_id) {
        return $this->db->where('relation_id', $request_id)
                        ->update('users_friends', array('relation_status' => 2));
    }
    
    public function blockFriend($request_id) {
        return $this->db->where('relation_id', $request_id)
                        ->update('users_friends', array('relation_status' => 3));
    }
    
    public function getFriendsList($user_id) {
        $query = $this->db->query('SELECT R.relation_id, U.user_id, U.name, U.title, I.img_src, C.country_name, R.confirmation_time '
                . 'FROM users U, users_profile_pictures I, countries C, users_friends R '
                . 'WHERE CASE '
                . "WHEN R.user_a = {$user_id} THEN R.user_b = U.user_id "
                . "WHEN R.user_b = {$user_id} THEN R.user_a = U.user_id "
                . 'END '
                . 'AND R.relation_status = 1 '
                . 'AND I.user_id = U.user_id '
                . 'AND I.size = '.USER_IMAGE_MEDIUM.' '
                . 'AND C.country_id = U.country');
                
        return $query->num_rows() > 0 ? $query->result_array() : array();
    }
    
    public function getPendingRequests($user_id) {
        $query = $this->db->query('SELECT R.relation_id, U.user_id, U.name, U.title, I.img_src, C.country_name '
                . 'FROM users U, users_profile_pictures I, countries C, users_friends R '
                . 'WHERE R.user_b = '.$user_id.' '
                . 'AND relation_status = 0 '
                . 'AND U.user_id = R.user_a '
                . 'AND I.user_id = U.user_id '
                . 'AND I.size = '.USER_IMAGE_MEDIUM.' '
                . 'AND C.country_id = U.country');
                
        return $query->num_rows() > 0 ? $query->result_array() : array();
    }
    
    public function removeFriend($relation_id) {
        return $this->db->delete('users_friends', array('relation_id' => $relation_id));
    }
    
    public function search($query) {
        $query = $this->db->select('user_id, name')
                        ->like('keywords', $query)
                        ->get('users');
        
        return $query->num_rows() > 0 ? $query->result_array() : array();
    }
    
    public function getUserBank($uid) {
        $query = $this->db->select('bank_id, bank_acc_rut, bank_acc_number, bank_acc_type')
                          ->where('user_id', $uid)
                          ->get('users');
        
        return $query->num_rows() > 0 ? $query->row() : null;
    }
}
