<?php

class Comment_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getComments($ref, $origin) {
        $this->load->database();
        
        $this->db->select('comment_id, comments.user_id, name, img_src, post_time, text, hidden')
                 ->from('comments')
                 ->join('users', 'comments.user_id = users.user_id')
                 ->join('users_profile_pictures', 'comments.user_id = users_profile_pictures.user_id')
                 ->where(array('ref_id' => $ref, 'origin' => $origin, 'size' => 85));
        $query = $this->db->get();
        
        return $query->num_rows() > 0 ? $query->result() : array();
    }
    
    public function postComment($user_id, $ref, $origin, $message) {
        $this->load->database();
        
        $this->db->insert('comments', array('user_id' => $user_id, 'ref_id' => $ref, 'origin' => $origin, 'text' => $message));
        
        return $this->db->affected_rows() > 0;
    }
    
    public function getLatestComment($ref, $origin) {
        $this->load->database();
        
        $this->db->select('comment_id, comments.user_id, name, img_src, post_time, text, votes, hidden')
                 ->from('comments')
                 ->join('users', 'comments.user_id = users.user_id')
                 ->join('users_profile_pictures', 'comments.user_id = users_profile_pictures.user_id')
                 ->where(array('ref_id' => $ref, 'origin' => $origin, 'size' => 85))
                 ->order_by('comment_id', 'DESC')
                 ->limit(1);
        $query = $this->db->get();
        
        return $query->num_rows() > 0 ? $query->result_array() : array();
    }
}
