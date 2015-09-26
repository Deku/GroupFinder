<?php

/**
 * Description of Conversations
 *
 * @author José González <maangx@gmail.com>
 */
class Conversations extends GF_Global_controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('conversation_model');
        $this->load->model('conversation_message_model');
    }
    
    /*
     * Vista Todas las connversaciones
     */
    public function all() {
        $this->requires_login();
        
        $user_id = $this->session->user_id;
        $conversations = $this->conversation_model->get_list($user_id);
        $html_conv = array();
        $this->load->library('parser');
        
        foreach ($conversations as $conv) {
            $conv['img_src'] = $this->format_img_src($conv['img_src']);
            $conv['profile_url'] = site_url('users/u/'.$conv['user_id']);
            $conv['conversation_url'] = site_url('conversations/messages/'.$conv['conversation_id']);
            array_push($html_conv, $this->parser->parse('conversations/fragments/conversation_item', $conv, true));
        }
        
        $this->data['conversations'] = $html_conv;
        
        $this->data['view_title'] = 'Mis conversaciones';
        $this->load->view('global/header', $this->data);
        $this->load->view('conversations/all_conversations');
        $this->load->view('global/footer');
    }

    /*
     * Vista Mensajes de la conversación
     */
    public function messages($conv_id = 0) {
        $this->requires_login();
        
        if ($conv_id > 0) {
            // Todas las conversaciones (barra lateral izquierda)
            $user_id = $this->session->user_id;
            $conversations = $this->conversation_model->get_list($user_id);
            $html_conv = array();
            $this->load->library('parser');

            foreach ($conversations as $conv) {
                $conv['img_src'] = $this->format_img_src($conv['img_src']);
                $conv['conv_url'] = site_url('conversations/messages/'.$conv['conversation_id']);
                $conv['profile_url'] = site_url('users/u/'.$conv['user_id']);
                array_push($html_conv, $this->parser->parse('conversations/fragments/sidebar_conversation', $conv, true));
            }

            $this->data['conversations'] = $html_conv;
            
            
            // Mensajes de la conversacion
            $messages = $this->conversation_message_model->get_list($conv_id);
            $html_msg = array();

            foreach ($messages as $msg) {
                $msg['img_src'] = $this->format_img_src($msg['img_src']);
                array_push($html_msg, $this->parser->parse('conversations/fragments/message', $msg, true));
            }

            $this->data['messages'] = $html_msg;
            $this->data['conversation_id'] = $conv_id;
            
            $this->data['view_title'] = 'Mensajes';
            $this->load->view('global/header', $this->data);
            $this->load->view('conversations/conversation');
            $this->load->view('global/footer');
        }
    }
    
    public function sendMessage() {
        if ($this->is_ajax())
        {
            $ref = $this->input->post('ref');
            $message = $this->input->post('message');
            $conv_id = $this->conversation_model->get_id($ref, $this->session->user_id);
            
            if ($conv_id == 0)
            {
                $conv_id = $this->conversation_model->create($this->session->user_id, $ref);
            }
            
            if ($this->conversation_message_model->insert($conv_id, $ref, $message))
            {
                $this->return_ajax_success('Mensaje enviado');
            } else {
                $this->return_ajax_error('No se pudo enviar el mensaje');
            }
        }
    }
    
    public function sendChatMessage() {
        if ($this->is_ajax())
        {
            $sender = $this->session->user_id;
            $conv_id = $this->input->post('ref');
            $conv_id = intval(explode('-', $conv_id)[1]);
            $conversation = $this->conversation_model->get($conv_id);
            $message = $this->input->post('message');
            
            if ($conversation->user_a == $sender) {
                $receiver = $conversation->user_b;
            } else {
                $receiver = $conversation->user_a;
            }
            
            if ($this->conversation_message_model->insert($conv_id, $sender, $message))
            {
                $msg = $this->conversation_message_model->get_last($conv_id, $sender);
                $msg['img_src'] = $this->format_img_src($msg['img_src']);
                $html = $this->parser->parse('conversations/fragments/message', $msg, true);
                
                $this->return_ajax_success('OK', array('html' => $html));
            } else {
                $this->return_ajax_error('No se pudo enviar el mensaje');
            }
        }
    }
}
