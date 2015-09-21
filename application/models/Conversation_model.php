<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conversations_model
 *
 * @author JosÃ© GonzÃ¡lez <maangx@gmail.com>
 */
class Conversation_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function createConversation($user_a, $user_b) {
        return $this->db->insert('conversations', array('user_a' => $user_a, 'user_b' => $user_b)) ? $this->db->insert_id() : 0;
    }
    
    public function getConversationsList($user_id) {
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
    
    public function getUnreadConversations($user_id) {
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
    
    public function addMessage($conv_id, $sender, $message) {
        return $this->db->insert('conversations_messages', array('conversation_id' => $conv_id, 'sender_id' => $sender, 'text' => $message));
    }
    
    public function getConversationId($user_one, $user_two) {
        $query = $this->db->where("(user_a = {$user_one} AND user_b = {$user_two}) OR (user_a = {$user_two} AND user_b = {$user_one})")
                        ->select('conversation_id')
                        ->get('conversations');
        
        return $query->num_rows() > 0 ? $query->row()->conversation_id : 0;
    }
    
    public function countUnreadMessages($user_id) {
       $query = $this->db->query('SELECT COUNT(M.message_id) unread '
                            . 'FROM conversations C, conversations_messages M '
                            . "WHERE (C.user_a = {$user_id} OR C.user_b = {$user_id}) "
                            . "AND C.conversation_id = M.conversation_id "
                            . "AND M.read = FALSE");
                            
        return $query->num_rows() > 0 ? $query->row()->unread : 0;
    }
    
    public function getMessages($conv_id) {
        $query = $this->db->query('SELECT M.message_id, U.user_id, U.name, I.img_src, M.text, M.sent_timestamp '
                            . 'FROM conversations_messages M, users U, users_profile_pictures I '
                            . "WHERE M.conversation_id = {$conv_id} "
                            . "AND U.user_id = M.sender_id "
                            . "AND I.user_id = U.user_id "
                            . "AND I.size = " . USER_IMAGE_SMALL);
                            
        return $query->num_rows() > 0 ? $query->result_array() : array();
    }
    
    public function getLastMessage($conv_id, $sender_id) {
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
    
    public function get($conv_id) {
        $query = $this->db->where('conversation_id', $conv_id)
                            ->get('conversations');
        
        return $query->num_rows() > 0 ? $query->row() : null;
    }
}
