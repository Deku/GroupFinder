<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Comment_model
 *
 * Manages the messages posted in a profile thread
 * 
 * @author Jose Gonzalez
 */
class Comment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Gets the comments for a given profile
     * @param   int     $ref    Id of the referenced profile
     * @param   int     $origin Type of profile (user or project)
     * @return  array
     */
    public function get_list($ref, $origin) {
        $this->db->select('comment_id, comments.user_id, name, img_src, post_time, text, hidden')
                ->from('comments')
                ->join('users', 'comments.user_id = users.user_id')
                ->join('users_profile_pictures', 'comments.user_id = users_profile_pictures.user_id')
                ->where(array('ref_id' => $ref, 'origin' => $origin, 'size' => 85))
                ->order_by('post_time');
        $query = $this->db->get();

        return $query->num_rows() > 0 ? $query->result() : array();
    }

    /**
     * Inserts a new comment in the database
     * @param   int     $user_id
     * @param   int     $ref
     * @param   int     $origin
     * @param   string  $message
     * @return  boolean
     */
    public function insert($user_id, $ref, $origin, $message) {
        return (boolean) $this->db->insert('comments', array(
                    'user_id' => $user_id,
                    'ref_id' => $ref,
                    'origin' => $origin,
                    'text' => $message
                        )
        );
    }

    /**
     * Gets the last comment on a profile
     * @param   int     $ref    Id of the profile
     * @param   int     $origin Type of profile
     * @return  array
     */
    public function get_last($ref, $origin) {
        $query = $this->db->select('comment_id, comments.user_id, name, img_src, post_time, text, votes, hidden')
                ->from('comments')
                ->join('users', 'comments.user_id = users.user_id')
                ->join('users_profile_pictures', 'comments.user_id = users_profile_pictures.user_id')
                ->where(array('ref_id' => $ref, 'origin' => $origin, 'size' => 85))
                ->order_by('comment_id', 'DESC')
                ->limit(1)
                ->get();

        return $query->num_rows() > 0 ? $query->result_array() : array();
    }

}
