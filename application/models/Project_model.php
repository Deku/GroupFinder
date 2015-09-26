<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Project_model
 *
 * Manages the project information
 * 
 * @author Jose Gonzalez <maangx@gmail.com>
 */
class Project_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Creates a new project
     * @param   array   $data   Data taken from the posted form
     * @return  int             Id of the new project
     */
    public function create($data) {
        return $this->db->insert('projects', $data) ? $this->db->insert_id() : NULL;
    }

    /**
     * Gets the list of projects owned by an user
     * @param   int     $user_id
     * @return  array
     */
    public function get_by_owner($user_id) {
        $query = $this->db->select('projects.project_id, title, status_name, limit_date, img_src picture')
                        ->join('projects_picture', 'projects.project_id = projects_picture.project_id')
                        ->join('projects_status', 'projects.status = projects_status.status_id')
                        ->where(array(
                            'owner_id' => $user_id,
                            'size' => PROJECT_IMAGE_SMALL)
                        )
                        ->get('projects');

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return NULL;
    }

    /**
     * Gets the information of a project
     * @param   int     $project_id
     * @return  array
     */
    public function get($project_id) {
        $query = $this->db->select('a.*, g.status_name, b.category_id, b.name category,'
                                . ' e.img_src picture, c.name owner_name, c.title owner_title,'
                                . ' d.country_name owner_country, c.created owner_registered,'
                                . ' f.img_src owner_picture')
                        ->from('projects a')
                        ->join('projects_categories b', 'a.category = b.category_id')
                        ->join('users c', 'a.owner_id = c.user_id')
                        ->join('countries d', 'c.country = d.country_id')
                        ->join('projects_picture e', 'a.project_id = e.project_id')
                        ->join('projects_status g', 'a.status = g.status_id')
                        ->join('users_profile_pictures f', 'a.owner_id = f.user_id')
                        ->where(array(
                            'a.project_id' => $project_id,
                            'e.size' => PROJECT_IMAGE_LARGE,
                            'f.size' => USER_IMAGE_MEDIUM)
                        )
                        ->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return NULL;
    }

    /**
     * Gets the tile of a project
     * @param   int     $project_id
     * @return  string
     */
    public function get_title($project_id) {
        $query = $this->db->select('title')
                        ->where('project_id', $project_id)
                        ->get('projects');

        return $query->num_rows() > 0 ? $query->row()->title : '';
    }

    /**
     * Checks if the user is the project's owner
     * @param   int     $user_id
     * @param   int     $project_id
     * @return  boolean
     */
    public function is_owner($user_id, $project_id) {
        $query = $this->db->select('project_id')
                ->where(array('owner_id' => $user_id, 'project_id' => $project_id))
                ->get('projects');

        return $query->num_rows() > 0 ? TRUE : FALSE;
    }

    /**
     * Updates the project's data
     * @param   int     $project_id
     * @param   array   $data
     * @return  boolean
     */
    public function update($project_id, $data) {
        $this->db->where('project_id', $project_id)
                ->update('projects', $data);

        return $this->db->affected_rows() > 0;
    }

    /**
     * Gets a limited list of random featured projects
     * @param   int     $limit
     * @return  array
     */
    public function get_random_list($limit) {
        $query = $this->db->select('projects.project_id, title, status_name, summary, limit_date, img_src picture')
                        ->where(array('featured' => '1', 'size' => PROJECT_IMAGE_SMALL, 'projects.status' => PROJECT_STATUS_GROWING))
                        ->join('projects_picture', 'projects.project_id = projects_picture.project_id')
                        ->join('projects_status', 'projects.status = projects_status.status_id')
                        ->order_by('projects.project_id', 'RANDOM')
                        ->limit($limit)
                        ->get('projects');

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return array();
    }
}
