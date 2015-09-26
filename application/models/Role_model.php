<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Role_model
 * 
 * Manages the roles of a project's team
 *
 * @author Jose Gonzalez <maangx@gmail.com>
 */
class Role_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Removes a role
     * @param   int     $project_id
     * @param   int     $role_id
     * @return  boolean
     */
    public function remove($project_id, $role_id) {
        $this->db->where(array('project_id' => $project_id, 'role_id' => $role_id))
                ->delete('projects_roles');

        return $this->db->affected_rows() > 0;
    }
    
    /**
     * Edits the data of a role
     * @param   int     $role_id
     * @param   array   $data
     * @return  boolean
     */
    public function edit($role_id, $data) {
        $this->db->where('role_id', $role_id)
                ->update('projects_roles', $data);

        return $this->db->affected_rows() > 0;
    }
    
    /**
     * Creates a new role
     * @param   array   $data
     * @return  int
     */
    public function add($data) {
        return $this->db->insert('projects_roles', $data) ? $this->db->insert_id() : 0;
    }
    
    /**
     * Updates the amount of members occuping a role
     * @param   int     $project_id
     * @param   int     $role_id
     * @param   int     $variation
     * @return  boolean
     */
    public function update_occupied($project_id, $role_id, $variation) {
        $this->db->where(array('project_id' => $project_id, 'role_id' => $role_id))
                ->set('vacants_used', 'vacants_used' . $variation, false)
                ->update('projects_roles');

        return $this->db->affected_rows() > 0;
    }
}
