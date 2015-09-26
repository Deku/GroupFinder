<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Conversation_model
 *
 * Manages the conversations between users
 * 
 * @author Jose Gonzalez
 */
class Conversation_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Creates a new conversation
     * @param   int     $user_a     Id of the first user
     * @param   int     $user_b     Id of the second user
     * @return  int                 The id of the new conversation or 0 if failed
     */
    public function create($user_a, $user_b) {
        return $this->db->insert('conversations', array(
                    'user_a' => $user_a,
                    'user_b' => $user_b
                        )
                ) ? $this->db->insert_id() : 0;
    }

    /**
     * Gets the list of conversations associated with an user
     * @param   int     $user_id
     * @return  array
     */
    public function get_list($user_id) {
        $query = $this->db->query('SELECT C.conversation_id, U.user_id, U.name, I.img_src, '
                                . '(SELECT text FROM conversations_messages CC WHERE CC.conversation_id = C.conversation_id ORDER BY sent_timestamp DESC LIMIT 1) last_message, '
                                . '(SELECT sent_timestamp FROM conversations_messages CC WHERE CC.conversation_id = C.conversation_id ORDER BY sent_timestamp DESC LIMIT 1) sent_timestamp '
                                . 'FROM conversations C, users U, users_profile_pictures I '
                                . "WHERE (user_a = {$user_id} OR user_b = {$user_id}) "
                                . "AND CASE "
                                . "WHEN C.user_a = {$user_id} THEN U.user_id = C.user_b "
                                . "WHEN C.user_b = {$user_id} THEN U.user_id = C.user_a "
                                . "END "
                                . "AND I.user_id = U.user_id "
                                . "AND I.size = " . USER_IMAGE_MEDIUM);

        return $query->num_rows() > 0 ? $query->result_array() : array();
    }

    /**
     * Gets the list of unread conversations associated with an user
     * @param   int     $user_id
     * @return  array
     */
    public function get_unread_list($user_id) {
        $query = $this->db->query('SELECT C.conversation_id, U.user_id, U.name, I.img_src, '
                                . '(SELECT text FROM conversations_messages CC WHERE CC.conversation_id = C.conversation_id AND CC.read = FALSE ORDER BY sent_timestamp DESC LIMIT 1) text, '
                                . '(SELECT sent_timestamp FROM conversations_messages CC WHERE CC.conversation_id = C.conversation_id AND CC.read = FALSE ORDER BY sent_timestamp DESC LIMIT 1) sent_timestamp '
                                . 'FROM conversations C, users U, users_profile_pictures I '
                                . "WHERE (user_a = {$user_id} OR user_b = {$user_id}) "
                                . "AND CASE "
                                . "WHEN C.user_a = {$user_id} THEN U.user_id = C.user_b "
                                . "WHEN C.user_b = {$user_id} THEN U.user_id = C.user_a "
                                . "END "
                                . "AND I.user_id = U.user_id "
                                . "AND I.size = " . USER_IMAGE_SMALL);

        return $query->num_rows() > 0 ? $query->result_array() : array();
    }

    /**
     * Returns the id of the conversation related to two users
     * @param   int     $user_one   Id of the first user
     * @param   int     $user_two   Id of the second user
     * @return  int
     */
    public function get_id($user_one, $user_two) {
        $query = $this->db->select('conversation_id')
                        ->where("(user_a = {$user_one} AND user_b = {$user_two}) OR (user_a = {$user_two} AND user_b = {$user_one})")
                        ->get('conversations');

        return $query->num_rows() > 0 ? $query->row()->conversation_id : 0;
    }

    /**
     * Gets a conversation
     * @param   int     $conv_id
     * @return  array
     */
    public function get($conv_id) {
        $query = $this->db->where('conversation_id', $conv_id)
                        ->get('conversations');

        return $query->num_rows() > 0 ? $query->row() : null;
    }

}
