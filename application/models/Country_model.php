<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Country_model
 *
 * @author José González <maangx@gmail.com>
 */
class Country_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Gets the list of countries
     * @return  array
     */
    public function get_list() {
        $query = $this->db->get('countries');

        return $query->result();
    }
    
    /**
     * Gets the name for a given country id
     * @param   int     $country_id
     * @return  string
     */
    public function get_name($country_id) {
        $query = $this->db->select('country_name')
                ->where('country_id', $country_id)
                ->get('countries');

        if ($query->num_rows() > 0) {
            return $query->row()->country_name;
        }
    }
}
