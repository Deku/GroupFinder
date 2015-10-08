<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Bank_model
 * 
 * Manages the banks available for transactions
 *
 * @author Jose Gonzalez <maangx@gmail.com>
 */
class Bank_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Gets the list of banks available
     * @return array
     */
    public function get_list() {
        $query = $this->db->get('banks');

        return $query->result();
    }
}
