<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * FAQ_model
 * 
 * Manages the Frequently Asked Questions of a project
 *
 * @author Jose Gonzalez <maangx@gmail.com>
 */
class Faq_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Adds a new FAQ to a project
     * @param   int     $project_id
     * @param   string  $question
     * @param   string  $answer
     * @return  boolean
     */
    public function add($project_id, $question, $answer) {
        return $this->db->insert('projects_faq', array(
                    'project_id' => $project_id,
                    'question' => $question,
                    'answer' => $answer
                        )
        );
    }

    /**
     * Gets the list of questions for a project
     * @param   int     $project_id
     * @return  array
     */
    public function get_list($project_id) {
        $query = $this->db->select('list_number, question, answer')
                ->where('project_id', $project_id)
                ->order_by('list_number', 'ASC')
                ->get('projects_faq');

        return $query->num_rows() > 0 ? $query->result_array() : null;
    }

}
