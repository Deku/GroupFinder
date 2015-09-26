<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model
 *
 * Manages the information associated with users
 * 
 * @author Jose Gonzalez
 */
class User_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Checks if an account is publicly visible
     * @param   string  $user   Username
     * @return  boolean
     */
    public function is_visible($user) {
        $query = $this->db->select('hidden')
                ->where('username', $user)
                ->get('users');

        if ($query->num_rows() > 0) {
            return (bool) $query->row()->visible;
        }

        return FALSE;
    }
    
    /**
     * Gets the user profile info
     * @param   int     $user_id
     * @return  array
     */
    public function get_profile($user_id) {
        $query = $this->db->select('name, email, birthday, title, country_name, about, created, img_src')
                            ->where(array('users.user_id' => $user_id, 'size' => 297))
                            ->join('countries', 'users.country = countries.country_id')
                            ->join('users_profile_pictures', 'users_profile_pictures.user_id = users.user_id')
                            ->get('users');
        
        if ($query->num_rows() > 0)
        {
            return $query->row();
        }
        
        return NULL;
    }
    
    /**
     * Updates the account data
     * @param   int     $user_id
     * @param   array   $updates
     * @return  boolean
     */
    public function update($user_id, $updates) {
        return $this->db->where('user_id', $user_id)
                        ->update('users', $updates);
    }
    
    /**
     * Gets a list of users which name matches with the given string
     * @param   string  $words
     * @return  array
     */
    public function like($words) {
        $query = $this->db->select('user_id, name')
                        ->like('keywords', $words)
                        ->get('users');
        
        return $query->num_rows() > 0 ? $query->result_array() : array();
    }
    
    /**
     * Gets the user's bank data
     * @param type $uid
     * @return type
     */
    public function get_bank_data($uid) {
        $query = $this->db->select('bank_id, bank_acc_rut, bank_acc_number, bank_acc_type')
                          ->where('user_id', $uid)
                          ->get('users');
        
        return $query->num_rows() > 0 ? $query->row() : NULL;
    }
    
    /**
     * Inserts a new user in the database
     * @param   array   $data   New account's data
     * @return  boolean
     */
    public function create($data) {
        return $this->db->insert('users', $data);
    }

    /**
     * Creates an activation code for an account
     * @param   int     $user_id
     * @param   string  $code
     * @return  boolean
     */
    public function create_activation_code($user_id, $code) {
        return $this->db->insert('users_activation', array(
                    'user_id' => $user_id,
                    'activation_code' => $code)
        );
    }

    /**
     * Changes the state of an user from locked to unlocked
     * @param   type    $user_id
     * @return  boolean
     */
    public function activate($user_id) {
        $ua_data = array(
            'activated' => TRUE,
            'activated_time' => date("Y-m-d H:i:s")
        );
        $this->db->where('user_id', $user_id);

        if ($this->db->update('users_activation', $ua_data)) {
            // No es necesario pero nos asegura que la consulta este limpia
            $this->db->reset_query();
            $this->db->where('user_id', $user_id);

            return $this->db->update('users', array('locked' => UNLOCKED));
        }

        return FALSE;
    }

    /**
     * Updates the last time an user authenticated
     * @param   int     $user_id
     * @param   string  $ip
     */
    public function register_login($user_id, $ip) {
        $this->db->where('user_id', $user_id)
                ->update('users', array(
                    'last_login_time' => date("Y-m-d H:i:s", time()),
                    'last_login_ip' => $ip
                        )
        );
    }

    /**
     * Checks if a record exists for a given username
     * @param   string  $user   Username
     * @return  boolean
     */
    public function exists($user) {
        return $this->db->where('username', $user)
                        ->count_all_results('users') > 0;
    }

    /**
     * Checks if an account is locked
     * @param   string  $user   Username
     * @return  boolean
     */
    public function is_locked($user) {
        $query = $this->db->select('locked')
                ->where('username', $user)
                ->get('users');

        if ($query->num_rows() > 0) {
            return (bool) $query->row()->locked;
        }

        return FALSE;
    }


    /**
     * Checks if the account has a pending activation code
     * @param   int     $user_id
     * @return  boolean
     */
    public function has_activation_code($user_id) {
        return $this->db->where(array(
                            'user_id' => $user_id,
                            'activated' => FALSE
                            )
                        )
                        ->count_all_results('users_activation') > 0;
    }

    /**
     * Gets user data
     * @param   int     $user_id
     * @return  array
     */
    public function get($user_id) {
        $query = $this->db->select('user_id, username, name, email, birthday, title, country, country_name, about, last_login_time, last_login_ip')
                ->join('countries', 'users.country = countries.country_id')
                ->where('user_id', $user_id)
                ->get('users');

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        
        return NULL;
    }

    /**
     * Gets the id for an username
     * @param   string  $user   Username
     * @return  integer
     */
    public function get_id($user) {
        $query = $this->db->select('user_id')
                ->where('username', $user)
                ->get('users');

        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row->user_id;
        }
    }

    /**
     * Gets the salt for a username
     * @param   string  $user   Username
     * @return  string
     */
    public function get_salt($user) {
        $query = $this->db->select('salt')
                ->where('username', $user)
                ->get('users');

        if ($query->num_rows() > 0) {
            return $query->row()->salt;
        }
        
        return NULL;
    }

    /**
     * Tries to find a record matching the username/password combination
     * @param   string  $user   Username
     * @param   string  $password
     * @return  boolean
     */
    public function check_password($user, $password) {
        return $this->db->where(array(
                    'username' => $user,
                    'password' => $password)
                )
                ->count_all_results('users') > 0;
    }

    /**
     * Updates the password and salt
     * @param   int     $user_id
     * @param   string  $pass
     * @param   string  $salt
     * @return  boolean
     */
    public function change_password($user_id, $pass, $salt) {
        return $this->db->where('user_id', $user_id)
                        ->update('users', array(
                            'password' => $pass,
                            'salt' => $salt
        ));
    }

    /**
     * Gets the email of an account
     * @param   int     $user_id
     * @return  string
     */
    public function get_email($user_id) {
        $query = $this->db->select('email')
                ->where('user_id', $user_id)
                ->get('users');

        if ($query->num_rows() > 0) {
            return $query->row()->email;
        }
        
        return NULL;
    }

    /**
     * Gets the activation code for an account
     * @param   int     $user_id
     * @return  string
     */
    public function get_activation_code($user_id) {
        $query = $this->db->select('activation_code')
                ->where('user_id', $user_id)
                ->get('users_activation');

        if ($query->num_rows() > 0) {
            return $query->row()->activation_code;
        }
        
        return NULL;
    }

    /**
     * Update the credentials for a persistent session
     * @param   int     $user_id
     * @param   string  $id
     * @param   string  $token
     */
    public function update_persistent_session($user_id, $id, $token) {
        $this->db->where('user_id', $user_id)
                ->update('users', array(
                    'keeplogged_id' => $id,
                    'keeplogged_token' => $token
        ));
    }

    /**
     * Sets the credentials for a persistent session to NULL
     * @param   int     $user_id
     */
    public function remove_persistent_session($user_id) {
        $this->db->where('user_id', $user_id)
                ->update('users', array(
                    'keeplogged_id' => NULL,
                    'keeplogged_token' => NULL
        ));
    }

    /**
     * Gets the token of a persistent session
     * @param   int     $id     Persistent session id
     * @return  array
     */
    public function get_persistent_session($id) {
        $query = $this->db->select('user_id, keeplogged_token')
                ->where('keeplogged_id', $id)
                ->get('users');

        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return NULL;
    }
}
