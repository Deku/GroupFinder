<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home Controller
 *
 * Handles the home page and user's dashboard
 */
class Portal extends GF_Global_controller {

    /**
     * Home landing page
     */
    public function home()
    {
        $this->load->model('project_model');
        $this->load->model('category_model');
        
        $cat_results = $this->category_model->get_list();
        $categories = array();
        foreach ($cat_results as $cat) {
            $categories[$cat->category_id] = $cat->name;
        }
        $this->data['categories'] = $categories;
        
        $featured = $this->project_model->get_random_list(3);
        foreach ($featured as $feat) {
            $feat->picture = $this->format_img_src($feat->picture);
            $feat->limit_date = date('d/M/Y', strtotime($feat->limit_date));
        }
        $this->data['featured'] = $featured;

        $this->load_view('Inicio', 'portal/home');
    }
    
}
