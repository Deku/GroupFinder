<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Category_model
 * 
 * Manages the categories were projects are grouped
 *
 * @author Jose Gonzalez <maangx@gmail.com>
 */
class Category_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Gets the list of categories and the number of projects in them
     * @return  array
     */
    public function get_list() {
        $query = $this->db->select('a.category_id, a.name, a.description, (SELECT COUNT(project_id) FROM projects b WHERE a.category_id = b.category AND status > ' . PROJECT_STATUS_GROWING . ') count')
                ->get('projects_categories a');
        
        return $query->result();
    }

    /**
     * Gets the name of a category
     * @param   int     $category_id
     * @return  string
     */
    public function get_name($category_id) {
        $query = $this->db->select('name')
                ->where('category_id', $category_id)
                ->get('projects_categories');

        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row->name;
        }
        
        return '';
    }

    /**
     * Gets the list of projects in the category
     * @param   int     $category_id
     * @return  array
     */
    public function get_projects($category_id) {
        $query = $this->db->select('projects.project_id, title, status_name, summary, limit_date, img_src picture')
                ->where(array('category' => $category_id, 'size' => PROJECT_IMAGE_SMALL))
                ->where_in('projects.status', array(PROJECT_STATUS_GROWING, PROJECT_STATUS_STARTED, PROJECT_STATUS_FINISHED))
                ->join('projects_picture', 'projects.project_id = projects_picture.project_id')
                ->join('projects_status', 'projects.status = projects_status.status_id')
                ->get('projects');

        return $query->num_rows() > 0 ? $query->result() : array();
    }
}
