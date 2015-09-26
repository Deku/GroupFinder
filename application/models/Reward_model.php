<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reward_model
 * 
 * Manages the rewards offered by a project
 *
 * @author Jose Gonzalez <maangx@gmail.com>
 */
class Reward_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Creates a new reward
     * @param   array   $data
     * @return  int
     */
    public function add($data) {
        return $this->db->insert('projects_rewards', $data) ? $this->db->insert_id() : 0;
    }

    /**
     * Counts the amount of people has chosen this reward
     * @param   int     $id
     * @return  int
     */
    public function count_backers($id) {
        return $this->db->where('reward_id', $id)
                        ->count_all_results('projects_transactions');
    }

    /**
     * Gets a reward by id
     * @param   int     $rid
     * @return  array
     */
    public function get($rid) {
        $query = $this->db->where('reward_id', $rid)
                ->get('projects_rewards');

        return $query->num_rows() > 0 ? $query->row() : null;
    }
    
    /**
     * Gets the list of rewards that belong to a project
     * @param   int     $project_id
     * @return  array
     */
    public function get_list($project_id) {
        $query = $this->db->where('project_id', $project_id)
                ->order_by('min_amount', 'ASC')
                ->get('projects_rewards');

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return array();
    }
}
