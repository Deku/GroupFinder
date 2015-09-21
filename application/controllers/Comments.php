<?php

class Comments extends GF_Global_controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get() {
        if ($this->is_ajax())
        {
            $id = $this->input->post('id');
            $type = $this->input->post('type');
            
            $this->load->model('comment_model');
            $this->load->library('parser');
            
            $comments = $this->comment_model->getComments($id, $type);
            $response = array();
            foreach ($comments as $comment)
            {
                $comment->img_src = $this->format_img_src($comment->img_src);
                $html = $this->parser->parse('global/comment', $comment, true);
                array_push($response, $html);
            }
            
            echo !empty($response) ? json_encode($response) : json_encode(array('result' => 'false'));
        }
    }
    
    public function post() {
        if ($this->is_ajax())
        {
            $this->load->library('form_validation');
            
            $rules = array(
                array(
                    'field' => 'ref',
                    'label' => 'Referencia',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'origin',
                    'label' => 'Origen',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'message',
                    'label' => 'Mensaje',
                    'rules' => 'trim|required'
                )
            );
            $this->form_validation->set_rules($rules);
            
            if ($this->form_validation->run() == FALSE)
            {
                echo json_encode( array(
                    'result' => false,
                    'error' => 'No se pudo enviar tu comentario. '. $this->form_validation->error_string()
                ));
                return;
            }
            
            $user_id = $this->session->user_id;
            $ref = $this->input->post('ref');
            $origin = $this->input->post('origin');
            $message = $this->input->post('message');
            
            $this->load->model('comment_model');
            
            $result = $this->comment_model->postComment($user_id, $ref, $origin, $message);
            
            if ($result)
            {
                $this->load->library('parser');
                $last_comment = $this->comment_model->getLatestComment($ref, $origin);
                $last_comment[0]['img_src'] = $this->format_img_src($last_comment[0]['img_src']);
                $html = $html = $this->parser->parse('global/comment', $last_comment[0], true);
                echo json_encode($html);
            }
            
        }
    }
}
