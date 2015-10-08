<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Project_resource_model
 * 
 * Manages the resources needed by the project
 *
 * @author Jose Gonzalez <maangx@gmail.com>
 */
class Project_resource_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Gets the list of resources used by a project
     * @param   int     $project_id
     * @return  array
     */
    public function get_list($project_id) {
        $query = $this->db->select('resource_id, name, cost, amount, detail, required')
                ->where('project_id', $project_id)
                ->order_by('required DESC, cost ASC')
                ->get('projects_costs');

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return array();
    }
    
    /**
     * Creates a new resource
     * @param   array   $data
     * @return  boolean
     */
    public function addResource($data) {
        $this->db->insert('projects_costs', $data);

        return $this->db->affected_rows() > 0;
    }
}
