<?php

/**
 * Clase Controlador Auth
 * 
 * Maneja las operaciones de las cuentas de usuario
 *
 * @author Jose Gonzalez
 */
class Auth extends GF_Global_controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('account_model');
    }
    
    /*
     * Vista Registrar cuenta
     */
    public function register() {
        $this->load->country_model;
        $countries[] = array();

        foreach ($this->country_model->get_list() as $row)
        {
            $countries[$row->country_id] = $row->country_name;
        }

        $this->data['countries'] = $countries;
        
        $this->data['view_title'] = 'Registrarse';
        $this->load->view('global/header', $this->data);
        $this->load->view('accounts/register');
        $this->load->view('global/footer');
    }
    
    /*
     * Vista Iniciar sesión
     */
    public function login() {
        
        $this->data['view_title'] = 'Iniciar sesión';
        $this->load->view('global/header', $this->data);
        $this->load->view('accounts/login');
        $this->load->view('global/footer');
    }
    
    public function processRegister() {
        $this->load->library('form_validation');

        $rules = array(
            array(
                'field' => 'username',
                'label' => 'Nombre de usuario',
                'rules' => 'trim|required|max_length[20]|xss_clean'
            ),
            array(
                'field' => 'password',
                'label' => 'Contraseña',
                'rules' => 'required|min_length[8]|max_length[10]|matches[v-password]'
            ),
            array(
                'field' => 'v-password',
                'label' => 'Confirmación de contraseña',
                'rules' => 'required|min_length[8]|max_length[10]'
            ),
            array(
                'field' => 'name',
                'label' => 'Nombre de usuario',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'email',
                'label' => 'Correo electrónico',
                'rules' => 'trim|required|valid_email|xss_clean'
            ),
            array(
                'field' => 'title',
                'label' => 'Título profesional',
                'rules' => 'trim|required|xss_clean'
            )
        );

        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('global/header', $this->data);
            $this->load->view('accounts/register', $this->data);
            $this->load->view('global/footer');
        }

        $this->load->model('country_model');
        $this->load->helper('string');
        
        $password = $this->input->post('password');
        $salt = random_string('alnum', 8); // String random de largo 8 alfanumerico
        $password = sha1(sha1($password) . sha1($salt));
        
        $name = $this->input->post('name');
        $title = $this->input->post('title');
        $title = isset($title) ? $title : '';
        $reference = $this->normalizeChars(trim(strtolower($name + rand(1,99))));
        $countryID = intval($this->input->post('country'));
        $countryName = $this->country_model->get_name($countryID);

        $account = array(
            'username' => $this->input->post('username'),
            'password' => $password,
            'salt' => $salt,
            'name' => $name,
            'email' => $this->input->post('email'),
            'birthday' => $this->input->post('birthday'),
            'title' => $title,
            'country' => $countryID,
            'reference' => $reference,
            'keywords' => $this->normalizeChars(strtolower($name . ' ' . $title . ' ' . $countryName))
        );
        
        if ($this->account_model->create($account))
        {
            $user_id = $this->account_model->get_id($account['username']);
            
            $img_small = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($account['email']))) . '?d=mm&s=40';
            $img_medium = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($account['email']))) . '?d=mm&s=85';
            $img_large = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($account['email']))) . '?d=mm&s=297';
            
            $refs = array(
                [
                    'user_id' => $user_id,
                    'img_src' => $img_small,
                    'size' => USER_IMAGE_SMALL
                ],
                [
                    'user_id' => $user_id,
                    'img_src' => $img_medium,
                    'size' => USER_IMAGE_MEDIUM
                ],
                [
                    'user_id' => $user_id,
                    'img_src' => $img_large,
                    'size' => USER_IMAGE_LARGE
                ]
            );
            
            $this->load->model('picture_model');
            $result = $this->picture_model->saveProfileImageRef($refs);
            
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $account['username'];
            $_SESSION['user_email'] = $account['email'];
            
            redirect('auth/activate');
        }
        else
        {
            $this->session->set_userdata('errorMsg', 'Lo sentimos, no ha podido crearse la cuenta.');
            redirect('auth/register');
        }

    }
    
    public function registerSuccess() {
        $this->load->view('global/header', $this->data);
        $this->load->view('accounts/registerSuccess');
        $this->load->view('global/footer');
    }
    
    public function processLogin() {
        $this->load->library('form_validation');
        
        $rules = array (
            array (
                'field' => 'username',
                'label' => 'Usuario',
                'rules' => 'trim|required'
            ),
            array (
                'field' => 'password',
                'label' => 'Contraseña',
                'rules' => 'required'
            )
        );
        
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() == FALSE)
        {   
            $this->load->view('global/header', $this->data);
            $this->load->view('accounts/login');
            $this->load->view('global/footer');
        }
        
        $user = $this->input->post('username');
        $pass = $this->input->post('password');
        
        if ($this->account_model->exists($user) && ($this->auth($user, $pass) == TRUE))
        {
            $user_id = $this->account_model->get_id($user);
            
            if ($this->account_model->is_locked($user))
            {   
                if (!$this->account_model->has_activation_code($user_id))
                {
                    $_SESSION['username'] = $user;
                    $_SESSION['user_id'] = $user_id;
                    $this->activate();
                }
                else
                {
                    $this->data['errorMsg'] = 'Este usuario se encuentra bloqueado.';
                    $this->login();
                }
            }
            else
            {
                $this->set_session($user_id);
                
                $keepLogged = $this->input->post('keepLogged');
                
                if ($keepLogged === 'on') {
                    $this->load->helper('string');
                    $this->load->helper('cookie');
                    
                    $keepLogged_id = random_string('alnum', 64);
                    $keepLogged_token = random_string('alnum', 10);
                    
                    $this->account_model->update_persistent_session(
                                $user_id,
                                $keepLogged_id,
                                sha1($keepLogged_token)
                            );
                    
                    set_cookie(
                            'gf_keeplogged',
                            $keepLogged_id.'___'.$keepLogged_token,
                            strtotime('+1 Week')
                    );
                }
                
                redirect('portal/home');
            }
        }        
        else
        {
            $this->data['errorMsg'] =  "Usuario o contraseña inválidos";
            $this->login();
        }
    }
    
    public function activate() {
        $user_id = $this->account_model->get_id($this->session->username);
        $_SESSION['user_email'] = $this->account_model->get_email($user_id);
        
        if ($this->account_model->has_activation_code($user_id))
        {
            $_SESSION['emailSent'] = true;
            $_SESSION['pending'] = $this->account_model->userActivationPending($user_id);
        } 
        else
        {
            // Creamos el codigo de activacion
            $this->load->helper('string');
            $code = random_string('alnum', 10);
            $this->account_model->create_activation_code($user_id, $code);
            
            // Lo enviamos por correo
            $this->load->library('email');
            $this->load->model('email_model');
            
            $vars = array(
                'lfg.username' => $this->session->username,
                'lfg.code' => $code
            );

            $_SESSION['emailSent'] = $this->send_email('REGISTER_ACTIVATION_PROMPT', $this->session->user_email, $vars);
            $_SESSION['pending'] = true;
        }
        
        $this->load->view('global/header', $this->data);
        $this->load->view('accounts/activate');
        $this->load->view('global/footer');
    }
    
    public function processActivation() {
        $this->load->library('form_validation');
        
        $rules = array (
            array (
                'field' => 'activationCode',
                'label' => 'Código de activación',
                'rules' => 'trim|required'
            )
        );
        
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() == FALSE)
        {   
            $this->load->view('global/header', $this->data);
            $this->load->view('accounts/activate');
            $this->load->view('global/footer');
        }
        
        $user_id = $this->session->user_id;
        $code = $this->input->post('activationCode');
        $storedCode = $this->account_model->get_activation_code($user_id);
        
        if ($storedCode && $code == $storedCode)
        {
            if ($this->account_model->activate($user_id))
            {
                $this->registerSuccess();
            } else {
                $this->data['errorMsg'] = 'El código es válido pero ocurrió un problema al intentar activar tu cuenta :(';
                $this->load->view('global/header', $this->data);
                $this->load->view('accounts/activate');
                $this->load->view('global/footer');
            }
        } else {
            $this->data['errorMsg'] = 'El código ingresado no es válido.';
            $this->load->view('global/header', $this->data);
            $this->load->view('accounts/activate');
            $this->load->view('global/footer');
        }
    }
    
    public function resend() {
        $user_email = $this->session->user_email;
        $user_id = $this->session->user_id;
        $vars = array(
            'lfg.username' => $this->session->username,
            'lfg.code' => $this->account_model->get_activation_code($user_id)
        );
        
        $this->send_email('REGISTER_ACTIVATION_PROMPT', $user_email, $vars);
        
        redirect('auth/activate');
    }
    
    public function logout() {
        $this->account_model->remove_persistent_session($this->session->user_id);
        session_destroy();
        redirect('portal/home');
    }
    
    private function auth($user, $pass) {
        $salt = $this->account_model->get_salt($user);
        $hashed_pass = sha1(sha1($pass) . sha1($salt));
        
        return $this->account_model->check_password($user, $hashed_pass);
    }
    
    public function changePassword() {
        if ($this->is_ajax())
        {
            $this->load->library('form_validation');
            
            $rules = array(
                array(
                    'field' => 'oldPass',
                    'label' => 'Contraseña antigua',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'newPass',
                    'label' => 'Nueva contraseña',
                    'rules' => 'required|matches[verifNewPass]'
                ),
                array(
                    'field' => 'verifNewPass',
                    'label' => 'Verificación de nueva contraseña',
                    'rules' => 'required'
                )
            );
            $this->form_validation->set_rules($rules);
            
            if ($this->form_validation->run() == FALSE)
            {
                $this->return_ajax_error('No se pudo cambiar la contraseña. '. $this->form_validation->error_string());
            }
            
            if ($this->auth($this->session->username, $this->input->post('oldPass')))
            {   
                $this->load->helper('string');
                
                $password = $this->input->post('newPass');
                $salt = random_string('alnum', 8); // String alfanumerico aleatorio
                $password = sha1(sha1($password) . sha1($salt));
                
                $result = $this->account_model->change_password($this->session->user_id, $password, $salt);
                
                $this->return_ajax_success();
            } else {
                $this->return_ajax_error('Contraseña actual no válida.');
            }
        }
    }
}
