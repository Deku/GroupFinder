<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Team_application_model
 *
 * Manages the applications to join a team
 * 
 * @author Jose Gonzalez <maangx@gmail.com>
 */
class Team_application_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Gets the list of applications to join a team
     * @param   int     $project_id
     * @return  array
     */
    public function get_list($project_id) {

        $query = $this->db->select('a.application_id, a.user_id, role_name, name, title, img_src, a.message, a.application_time')
                ->from('projects_applications a')
                ->join('projects_roles', 'a.role_id = projects_roles.role_id')
                ->join('users', 'a.user_id = users.user_id')
                ->join('users_profile_pictures', 'a.user_id = users_profile_pictures.user_id')
                ->where(array('a.project_id' => $project_id, 'size' => USER_IMAGE_MEDIUM, 'a.status' => TEAM_APPLICATION_WAITING))
                ->order_by('application_time', 'ASC')
                ->get();

        return $query->num_rows() > 0 ? $query->result_array() : array();
    }
    
    /**
     * Gets an application
     * @param   int     $app_id
     * @return  array
     */
    public function get($app_id) {
        $query = $this->db->select('project_id, role_id, user_id AS member_id')
                ->where('application_id', $app_id)
                ->get('projects_applications');

        return $query->num_rows() > 0 ? $query->row_array() : array();
    }

    /**
     * Sets the status of an application as accepted
     * @param   int     $project_id
     * @param   int     $app_id
     * @return  boolean
     */
    public function accept($project_id, $app_id) {
        $this->db->where(array('application_id' => $app_id, 'project_id' => $project_id))
                ->update('projects_applications', array('status' => TEAM_APPLICATION_ACCEPTED));

        return $this->db->affected_rows() > 0;
    }

    /**
     * Sets the status of an application as rejected
     * @param   int     $project_id
     * @param   int     $app_id
     * @return  boolean
     */
    public function reject($project_id, $app_id) {
        $this->db->where(array('application_id' => $app_id, 'project_id' => $project_id))
                ->update('projects_applications', array('status' => TEAM_APPLICATION_REJECTED));

        return $this->db->affected_rows() > 0;
    }

    /**
     * Creates a new application
     * @param   array   $data
     * @return  boolean
     */
    public function add($data) {
        return $this->db->insert('projects_applications', $data);
    }
}
