<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Country_model
 * 
 * Manages the countries for user location
 *
 * @author José González <maangx@gmail.com>
 */
class Country_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
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
