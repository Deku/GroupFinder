<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Team_model
 * 
 * Manages the members of a project's team. Think of it as: "team" = group of people with a common objective
 *
 * @author Jose Gonzalez <maangx@gmail.com>
 */
class Team_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Returns the number of members occupying a role
     * @param int $project_id
     * @param int $role_id
     * @return int
     */
    public function count_in_role($project_id, $role_id) {
        return $this->db->where(array('project_id' => $project_id, 'role_id' => $role_id))
                ->count_all_results('projects_members');
    }
    
    /**
     * Gets the list of members that belong to the team
     * @param   int     $project_id
     * @return  array
     */
    public function get_list($project_id) {

        $query = $this->db->select('member_id, role_name, name, title, img_src, join_timestamp, leader')
                ->from('projects_members a')
                ->join('projects_roles', 'projects_roles.role_id = a.role_id')
                ->join('users', 'users.user_id = member_id')
                ->join('users_profile_pictures', 'users_profile_pictures.user_id = member_id')
                ->where(array('a.project_id' => $project_id, 'size' => USER_IMAGE_MEDIUM))
                ->order_by('join_timestamp', 'ASC')
                ->get();

        return $query->num_rows() > 0 ? $query->result_array() : array();
    }

    /**
     * Gets the leader of the team
     * @param type $project_id
     * @return type
     */
    public function get_leader($project_id) {

        $query = $this->db->select('owner_id member_id, name, users.title, img_src')
                ->from('projects p')
                ->join('users', 'users.user_id = p.owner_id')
                ->join('users_profile_pictures', 'users_profile_pictures.user_id = owner_id')
                ->where(array('p.project_id' => $project_id, 'size' => USER_IMAGE_MEDIUM))
                ->get();

        return $query->num_rows() > 0 ? $query->row_array() : array();
    }

    /**
     * Gets a team member. Returns basic information of the user and their relation with the project
     * @param   int     $project_id
     * @param   int     $user_id
     * @return  array
     */
    public function get_member($project_id, $user_id) {

        $query = $this->db->select('member_id, role_name, name, title, img_src, join_timestamp, leader')
                ->from('projects_members a')
                ->join('projects_roles', 'projects_roles.role_id = a.role_id')
                ->join('users', 'users.user_id = member_id')
                ->join('users_profile_pictures', 'users_profile_pictures.user_id = member_id')
                ->where(array('a.project_id' => $project_id, 'size' => USER_IMAGE_MEDIUM, 'member_id' => $user_id))
                ->get();

        return $query->num_rows() > 0 ? $query->row_array() : array();
    }

    /**
     * Adds a user to the team
     * @param array $data
     * @return boolean
     */
    public function add_member($data) {
        return $this->db->insert('projects_members', $data);
    }

    /**
     * Kicks a member from the team
     * @param   int     $project_id
     * @param   int     $user_id
     * @return  boolean
     */
    public function remove_member($project_id, $user_id) {
        $this->db->where(array('project_id' => $project_id, 'member_id' => $user_id))
                ->delete('projects_members');

        return $this->db->affected_rows() > 0;
    }

    /**
     * Checks if the user is the leader of the team
     * @param   int     $project_id
     * @param   int     $user_id
     * @return  boolean
     */
    public function is_leader($project_id, $user_id) {
        $query = $this->db->where(array('project_id' => $project_id, 'member_id' => $user_id, 'leader' => true))
                ->get('projects_members');

        return $query->num_rows() > 0 ? true : false;
    }
    
    /**
     * Returns the role of the user inside the team
     * @param   int     $project_id
     * @param   int     $user_id
     * @return  int     Id of the role
     */
    public function user_role($project_id, $user_id) {
        $query = $this->db->where(array('project_id' => $project_id, 'member_id' => $user_id))
                ->select('role_id')
                ->get('projects_members');

        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row->role_id;
        }
    }
    
    /**
     * Gets the list of roles of a project
     * @param   int     $project_id
     * @return  array
     */
    public function get_roles($project_id) {
        $query = $this->db->select('role_id, role_name, vacants_amount, vacants_used, role_description')
                ->where(array('project_id' => $project_id, 'NOT role_name LIKE "Líder del proyecto"'))
                ->get('projects_roles');

        return $query->num_rows() > 0 ? $query->result_array() : array();
    }
    
    /**
     * Gets the list of roles with available vacants
     * @param   int     $project_id
     * @return  array
     */
    public function get_available_roles($project_id) {
        $query = $this->db->query('SELECT *,'
                . ' (SELECT COUNT(application_id) FROM projects_applications a WHERE r.role_id = a.role_id AND a.status = 0) postulantes'
                . ' FROM projects_roles r'
                . " WHERE project_id = {$project_id} AND vacants_used < vacants_amount");

        return $query->num_rows() > 0 ? $query->result_array() : array();
    }
}
