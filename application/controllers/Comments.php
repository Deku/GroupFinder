<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Comments Controller
 * 
 * Handles the comments posted in a profile. The profiles can be of two types:
 * (a) User profile
 * (b) Project profile
 *
 * @author Jose Gonzalez
 */
class Comments extends GF_Global_controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * (Async) Gets the list of comments on a profile
     */
    public function comments() {
        if ($this->input->is_ajax_request())
        {
            $id = $this->session->prof_id;
            $type = $this->session->prof_type;
            
            $this->get_list($id, $type);
        }
    }
    
    /**
     * (Async) Adds a new comment to a profile
     */
    public function post() {
        if ($this->input->is_ajax_request())
        {
            $user_id = $this->session->user_id;
            $prof_id = $this->session->prof_id;
            $type = $this->session->prof_type;
            $message = $this->input->post('message');
            
            $this->load->model('comment_model');

            if ($this->comment_model->insert($user_id, $prof_id, $type, $message))
            {
                $this->get_list($prof_id, $type);
            }
            
        }
    }

    private function get_list($id, $type) {
        $this->load->model('comment_model');
        $this->load->library('parser');

        $comments = $this->comment_model->get_list($id, $type);

        foreach ($comments as $comment)
        {
            $comment->img_src = $this->format_img_src($comment->img_src);
        }

        header('Content-Type: application/json');
        header('Cache-Control: no-cache');
        echo json_encode($comments, JSON_PRETTY_PRINT);
    }
}
