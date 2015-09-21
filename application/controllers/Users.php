<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends GF_Global_controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }
    
    public function u() {
        $user_id = $this->uri->segment(3);

        if (isset($user_id) && $user_id > 0)
        {
            // Obtenemos el perfil del usuario
            $profile = $this->user_model->getProfile($user_id);
            $this->data['error'] = false;
            
            if (isset($profile))
            {
                $profile->img_src = $this->format_img_src($profile->img_src);
                $this->data['profile'] = $profile;
            } else {
                $this->data['error'] = true;
            }
        } else {
            $this->data['error'] = true;
        }
        
        $this->data['view_title'] = $profile->name;
        $this->load->view('global/header', $this->data);
        $this->load->view('users/profile', $this->data);
        $this->load->view('global/footer');
    }
    
    public function settings() {
        $this->requires_login();
        
        $this->load->model('account_model');
        $this->load->model('user_model');
        $this->load->model('country_model');
        $countries[] = array();
        
        foreach ($this->country_model->get_list() as $row)
        {
            $countries[$row->country_id] = $row->country_name;
        }
        
        $this->load->model('project_model');
        // Bancos
        $banks = $this->project_model->getBanks();
        $bank_array = array();

        foreach ($banks as $b) {
            $bank_array[$b->bank_id] = $b->name;
        }
        $this->data['banks'] = $bank_array;

        // Tipos de cuenta bancaria
        $bank_acc_types = array(
            BANK_ACC_CTA_CORRIENTE => 'Cuenta Corriente',
            BANK_ACC_CTA_AHORRO => 'Cuenta Ahorro',
            BANK_ACC_CTA_VISTA => 'Cuenta Vista'
        );
        $this->data['bank_acc_types'] = $bank_acc_types;
        
        $user_bank = $this->user_model->getUserBank($this->session->user_id);

        $this->data['user_bank'] = $user_bank;
        $this->data['countries'] = $countries;
        $this->data['view_title'] = 'Configuración de la cuenta';
        $this->load->view('global/header', $this->data);
        $this->load->view('users/settings', $this->data);
        $this->load->view('global/footer');
    }
    
    public function editProfile() {
        if ($this->is_ajax())
        {
            $this->load->library('form_validation');
            
            $rules = array(
                array(
                    'field' => 'editName',
                    'label' => 'Nombre',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'editTitle',
                    'label' => 'Titulo profesional',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'editBirthday',
                    'label' => 'Fecha de nacimiento',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'editCountry',
                    'label' => 'País',
                    'rules' => 'trim|required|numeric'
                ),
                array(
                    'field' => 'editAbout',
                    'label' => 'Acerca de mi',
                    'rules' => 'trim'
                )
            );
            $this->form_validation->set_rules($rules);
            
            if ($this->form_validation->run() == FALSE)
            {
                echo json_encode( array(
                    'result' => false,
                    'error' => 'No se pudo guardar. '. $this->form_validation->error_string()
                ));
                return;
            }
            
            // Array de los cambios a realizar
            $updates = array();
            
            if ($this->input->post('editName') !== $this->session->user_realname)
            {
                $updates['name'] = $this->input->post('editName');
            }
            
            if ($this->input->post('editTitle') !== $this->session->user_title)
            {
                $updates['title'] = $this->input->post('editTitle');
            }
            
            if ($this->input->post('editBirthday') !== $this->session->user_birthday)
            {
                $updates['birthday'] = $this->input->post('editBirthday');
            }
            
            if ($this->input->post('editCountry') !== $this->session->user_realname)
            {
                $updates['country'] = $this->input->post('editCountry');
            }
            
            if ($this->input->post('editAbout') !== $this->session->user_realname)
            {
                $updates['about'] = $this->input->post('editAbout');
            }
            
            $this->load->model('user_model');
            
            $result = $this->user_model->updateProfile($this->session->user_id, $updates);
            
            if ($result)
            {
               $this->update_session();
            }
            
            echo $result;
        }
    }
    
    public function friends() {
        $this->requires_login();
        
        $this->load->library('parser');
        $friends = $this->user_model->getFriendsList($this->session->user_id);
        $html_friends = array();
       
        foreach($friends as $f) {
            $f['img_src'] = $this->format_img_src($f['img_src']);
            $f['confirmation_time'] = date('d M Y', strtotime($f['confirmation_time']));
            $f['profile_url'] = site_url('users/u/'.$f['user_id']);
            array_push($html_friends, $this->parser->parse('users/fragments/users_friend', $f, true));
        }
        
        $requests = $this->user_model->getPendingRequests($this->session->user_id);
        $html_requests = array();

        foreach($requests as $r) {
            $r['img_src'] = $this->format_img_src($r['img_src']);
            $r['profile_url'] = site_url('users/u/'.$r['user_id']);
            array_push($html_requests, $this->parser->parse('users/fragments/users_friend_request', $r, true));
        }
        
        $this->data['requests'] = $html_requests;
        
        $this->data['friends'] = $html_friends;
        $this->data['view_title'] = 'Mis amigos';
        $this->load->view('global/header', $this->data);
        $this->load->view('users/friends');
        $this->load->view('global/footer');
    }
    
    public function pending() {
        $this->requires_login();
        
        $this->load->library('parser');
        $requests = $this->user_model->getPendingRequests($this->session->user_id);
        $html_requests = array();

        foreach($requests as $r) {
            $r['img_src'] = $this->format_img_src($r['img_src']);
            $r['profile_url'] = site_url('users/u/'.$r['user_id']);
            array_push($html_requests, $this->parser->parse('users/fragments/users_friend_request', $r, true));
        }
        
        $this->data['requests'] = $html_requests;
        $this->data['view_title'] = 'Solicitudes de amistad pendiente';
        $this->load->view('global/header', $this->data);
        $this->load->view('users/pending');
        $this->load->view('global/footer');
    }
    
    public function sendFriendsRequest($target_id) {
        if ($this->is_ajax() && $this->session->loggedIn)
        {
            if (isset($target_id) && $target_id > 0)
            {
                $sender_id = $this->session->user_id;
                
                if ($this->user_model->addFriendsRequest($sender_id, $target_id))
                {
                    $this->return_ajax_success("Solicitud enviada");
                } else {
                    $this->return_ajax_error("No se pudo enviar la solicitud");
                }
            }
        }
    }
    
    public function processRequest() {
        if ($this->is_ajax())
        {
            $action = $this->input->post('action');
            $request_id = $this->input->post('rq');
            
            switch($action)
            {
                case 'accept':
                    if ($this->user_model->acceptFriendRequest($request_id))
                    {
                        $this->return_ajax_success();
                    } else {
                        $this->return_ajax_error();
                    }
                    break;
                case 'reject':
                    if ($this->user_model->rejectFriendRequest($request_id))
                    {
                        $this->return_ajax_success();
                    } else {
                        $this->return_ajax_error();
                    }
                    break;
                default:
                    $this->return_ajax_error();
            }
        }
    }
    
    public function removeFriend() {
        if ($this->is_ajax() && $this->session->loggedIn)
        {
            $request_id = $this->input->post('ref');
            
            if (isset($request_id) && $request_id > 0)
            {   
                if ($this->user_model->removeFriend($request_id))
                {
                    $this->return_ajax_success();
                } else {
                    $this->return_ajax_error("Ocurrio un error con tu solicitud");
                }
            }
        }
    }
}
