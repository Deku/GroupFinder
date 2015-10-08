<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Friend_model
 * 
 * Manages relationships between users 
 *
 * @author Jose Gonzelez <maangx@gmail.com>
 */
class Friend_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Adds a new relationship in pending status
     * @param   int     $user_id    Id of the user that sends the request
     * @param   int     $target_id  Id of the user that wants to be befriended
     * @return  boolean
     */
    public function add($user_id, $target_id) {
        return $this->db->insert('users_friends', array('user_a' => $user_id, 'user_b' => $target_id));
    }
    
    /**
     * Accepts a pending relationship (friend request)
     * @param   int     $request_id
     * @return  boolean
     */
    public function accept($request_id) {
        return $this->db->where('relation_id', $request_id)
                        ->update('users_friends', array('relation_status' => 1, 'confirmation_time' => date("Y-m-d H:i:s")));
    }
    
    /**
     * Rejects a pending relationship (friend request)
     * @param   int     $request_id
     * @return  boolean
     */
    public function reject($request_id) {
        return $this->db->where('relation_id', $request_id)
                        ->update('users_friends', array('relation_status' => 2));
    }
    
    /**
     * Blocks the interaction with a friend (doesn't remove the relationship)
     * @param   int     $request_id
     * @return  boolean
     */
    public function block($request_id) {
        return $this->db->where('relation_id', $request_id)
                        ->update('users_friends', array('relation_status' => 3));
    }
    
    /**
     * Gets the list of friends of a user
     * @param   int     $user_id
     * @return  array
     */
    public function get_list($user_id) {
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
    
    /**
     * Gets the list of relationships in pending status (friend requests)
     * @param   int     $user_id
     * @return  array
     */
    public function get_pending($user_id) {
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
    
    /**
     * Deletes the relationship between two users
     * @param   int     $relation_id
     * @return  boolean
     */
    public function remove($relation_id) {
        return $this->db->delete('users_friends', array('relation_id' => $relation_id));
    }
}
