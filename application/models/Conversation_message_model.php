<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Conversation_message_model
 * 
 * Manages the messages sent in a conversation by users
 *
 * @author Jose Gonzelez <maangx@gmail.com>
 */
class Conversation_message_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Inserts a new message in a conversation
     * @param   int     $conv_id    Id of the conversation
     * @param   int     $sender     Id of the user that sent the message
     * @param   string  $message
     * @return  boolean
     */
    public function insert($conv_id, $sender, $message) {
        return $this->db->insert('conversations_messages', array(
                    'conversation_id' => $conv_id,
                    'sender_id' => $sender,
                    'text' => $message
                        )
        );
    }

    /**
     * Counts the number of a user's unread messages
     * @param   int     $user_id
     * @return  int
     */
    public function count_unread($user_id) {
        $query = $this->db->query('SELECT COUNT(M.message_id) unread '
                                . 'FROM conversations C, conversations_messages M '
                                . "WHERE (C.user_a = {$user_id} OR C.user_b = {$user_id}) "
                                . "AND C.conversation_id = M.conversation_id "
                                . "AND M.read = FALSE");

        return $query->num_rows() > 0 ? $query->row()->unread : 0;
    }

    /**
     * Gets the messages of a conversation
     * @param   int     $conv_id
     * @return  array
     */
    public function get_list($conv_id) {
        $query = $this->db->query('SELECT M.message_id, U.user_id, U.name, I.img_src, M.text, M.sent_timestamp '
                                . 'FROM conversations_messages M, users U, users_profile_pictures I '
                                . "WHERE M.conversation_id = {$conv_id} "
                                . "AND U.user_id = M.sender_id "
                                . "AND I.user_id = U.user_id "
                                . "AND I.size = " . USER_IMAGE_SMALL);

        return $query->num_rows() > 0 ? $query->result_array() : array();
    }

    /**
     * Gets the last message of a conversation
     * @param   int     $conv_id
     * @param   int     $sender_id
     * @return  array
     */
    public function get_last($conv_id, $sender_id) {
        $query = $this->db->query('SELECT M.message_id, U.user_id, U.name, I.img_src, M.text, M.sent_timestamp '
                . 'FROM conversations_messages M, users U, users_profile_pictures I '
                . "WHERE M.conversation_id = {$conv_id} "
                . "AND M.sender_id = {$sender_id} "
                . "AND U.user_id = M.sender_id "
                . "AND I.user_id = U.user_id "
                . "AND I.size = " . USER_IMAGE_SMALL
                . " ORDER BY sent_timestamp DESC "
                . "LIMIT 1");

        return $query->num_rows() > 0 ? $query->row_array() : array();
    }

}
