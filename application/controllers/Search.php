<?php

/**
 * Description of Search
 *
 * @author José González <maangx@gmail.com>
 */
class Search extends GF_Global_controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function user() {
        $this->load->model('user_model');
        
        $query = $this->input->get('q');
        $result = $this->user_model->like($query);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
