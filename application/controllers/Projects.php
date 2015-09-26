<?php

/**
 * Description of Projects
 *
 * @author Jose Gonzalez
 */
class Projects extends GF_Global_controller {

    public $delivery_types = array(
        REWARD_DELIVERY_NONE => 'No requiere env&iacute;o',
        REWARD_DELIVERY_RETIRE_ADDRESS => 'Retiro en direcci&oacute;n espec&iacute;fica',
        REWARD_DELIVERY_SENT_BY_MAIL => 'Despacho a domicilio'
    );
    
    public function __construct() {
        parent::__construct();
        $this->load->model('project_model');
    }
    
    /**
     * Funcion Async
     * @param string $action Accion a ejecutar
     */
    public function async($action) {
        if (!$this->is_ajax() || !isset($action)) {
            $this->return_ajax_error('Accion invalida');
        }
        
        $pid = $this->input->post('pid');
        
        if (!isset($pid) || $pid < 1) {
            $this->return_ajax_error('Proyecto invalido');
        }
        
        switch($action) {
            case 'saveFundingMode':
                $mode = $this->input->post('mode');
                
                if (!isset($mode) || $mode < FUNDING_MODE_PRIVATE || $mode > FUNDING_MODE_COMMUNITY) {
                    $this->return_ajax_error('Modo seleccionado no es valido');
                }
                
                if ($this->project_model->update($pid, array('funding_mode' => $mode))) {
                    $this->return_ajax_success();
                } else {
                    $this->return_ajax_error();
                }
                break;
            case 'saveBankData':
                $data = array(
                    'bank_id' => $this->input->post('bank-id'),
                    'bank_acc_type' => $this->input->post('bank-acc-type'),
                    'bank_acc_rut' => $this->input->post('bank-acc-rut'),
                    'bank_acc_name' => $this->input->post('bank-acc-name'),
                    'bank_acc_email' => $this->input->post('bank-acc-email'),
                    'bank_acc_number' => $this->input->post('bank-acc-number')
                );
                
                if ($this->project_model->update($pid, $data)) {
                    $this->return_ajax_success();
                } else {
                    $this->return_ajax_error();
                }
                break;
            case 'saveRewardsActivate':
                $activate = filter_var($this->input->post('activate'), FILTER_VALIDATE_BOOLEAN);
                
                if (!isset($activate)) {
                    $this->return_ajax_error('Seleccion invalida');
                }
                
                if ($this->project_model->update($pid, array('rewards_activated' => $activate))) {
                    $this->return_ajax_success();
                } else {
                    $this->return_ajax_error();
                }
                
                break;
            case 'addReward':
                $this->load->model('reward_model');
                
                $data = array(
                    'project_id' => $pid,
                    'description' => $this->input->post('add-rewards-description'),
                    'min_amount' => $this->input->post('add-rewards-min'),
                    'delivery_type' => $this->input->post('add-rewards-delivery'),
                    'limit' => $this->input->post('add-rewards-limit')
                );
                
                if ($data['delivery_type'] == REWARD_DELIVERY_NONE) {
                    $delivery = array(
                        'delivery_date' => null,
                        'delivery_notes' => null
                    );
                } else {
                    $delivery = array(
                        'delivery_date' => $this->input->post('add-rewards-date').'-01',
                        'delivery_notes' => $this->input->post('add-rewards-notes')
                    );
                }
                $data = array_merge($data, $delivery);
                
                $rid = $this->reward_model->add($data);
                
                if ($rid) {
                    $this->load->library('parser');
                    $data['reward_id'] = $rid;
                    log_message('debug', $data['delivery_date']);
                    if ($data['delivery_type'] == REWARD_DELIVERY_NONE) {
                        $data['delivery_date'] = '';
                    } else {
                        $data['delivery_date'] = strftime('%B/%Y', strtotime($data['delivery_date']));
                    }
                    
                    $data['delivery_type_text'] = $this->delivery_types[$data['delivery_type']];
                    $html = $this->parser->parse('projects/fragments/rewards_row', $data, true);
                    
                    $this->return_ajax_success('Recompensa agregada!', array('tr' => $html));
                } else {
                    $this->return_ajax_error();
                }
                
                break;
            default:
                $this->return_ajax_error('Accion invalida');
        }
    }

    /*
     * Vista Explorar Categorias
     */

    public function explore() {
        $this->load->model('category_model');
        
        $categories = $this->category_model->get_list();
        $this->data['categories'] = $categories;

        $this->data['view_title'] = 'Explorar categorias';
        $this->load->view('global/header', $this->data);
        $this->load->view('projects/explore');
        $this->load->view('global/footer');
    }

    /*
     * Vista Categoria
     */

    public function category($category_id = 0) {
        $this->load->model('category_model');
        
        $cat_results = $this->category_model->get_list();
        $categories = array();
        
        foreach ($cat_results as $cat) {
            $categories[$cat->category_id] = $cat->name;
        }
        $this->data['categories'] = $categories;

        if (isset($category_id) && $category_id > 0) {
            $projects = $this->category_model->get_projects($category_id);
            if (isset($projects) && !empty($projects)) {
                foreach ($projects as $project) {
                    $project->picture = $this->format_img_src($project->picture);
                }
                $this->data['projects'] = $projects;
            }

            $category_name = $this->category_model->get_name($category_id);
            $this->data['category_name'] = $category_name;
        }

        $this->data['view_title'] = 'Categoria: ' . $category_name;
        $this->load->view('global/header', $this->data);
        $this->load->view('projects/category');
        $this->load->view('global/footer');
    }

    /*
     * Vista Proyectos propios
     */

    public function mine() {
        $this->requires_login();

        $projects = $this->project_model->get_by_owner($this->session->user_id);

        if (isset($projects) && !empty($projects)) {
            foreach ($projects as $p) {
                $p->picture = $this->format_img_src($p->picture);
            }

            $this->data['projects'] = $projects;
        }

        $this->data['view_title'] = 'Mis proyectos';
        $this->load->view('global/header', $this->data);
        $this->load->view('projects/mine');
        $this->load->view('global/footer');
    }

    /*
     * Vista Crear
     */

    public function create() {
        $this->requires_login();

        $categories = $this->category_model->get_list();
        $this->data['categories'] = $categories;

        $this->data['view_title'] = 'Crear un proyecto';
        $this->load->view('global/header', $this->data);
        $this->load->view('projects/create');
        $this->load->view('global/footer');
    }

    public function processCreate() {

        $this->load->library('form_validation');

        $rules = array(
            array(
                'field' => 'q-title',
                'label' => 'T&iacute;tulo del proyecto',
                'rules' => 'trim|required|max_length[250]'
            ),
            array(
                'field' => 'q-category',
                'label' => 'Categor&iacute;a',
                'rules' => 'required|integer'
            ),
            array(
                'field' => 'q-problem',
                'label' => 'Problema',
                'rules' => 'required|trim'
            ),
            array(
                'field' => 'q-solution',
                'label' => 'Solucion',
                'rules' => 'required|trim'
            ),
            array(
                'field' => 'q-target',
                'label' => 'Grupo objetivo',
                'rules' => 'required|trim'
            ),
            array(
                'field' => 'q-due-date',
                'label' => 'Fecha de cierre',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('global/header', $this->data);
            $this->load->view('projects/create', $this->data);
            $this->load->view('global/footer');
        }

        $project = array(
            'owner_id' => intval($this->session->user_id),
            'title' => $this->input->post('q-title'),
            'problem' => $this->input->post('q-problem'),
            'solution' => $this->input->post('q-solution'),
            'target_group' => $this->input->post('q-target'),
            'limit_date' => $this->input->post('q-due-date'),
            'category' => intval($this->input->post('q-category'))
        );

        $project_id = $this->project_model->create($project);

        if (isset($project_id)) {
            // Primero las imagenes por defecto
            $refs = array(
                [
                    'project_id' => $project_id,
                    'img_src' => PROJECT_IMAGE_DEFAULT_URL . 'small.jpg',
                    'size' => PROJECT_IMAGE_SMALL
                ],
                [
                    'project_id' => $project_id,
                    'img_src' => PROJECT_IMAGE_DEFAULT_URL . 'medium.jpg',
                    'size' => PROJECT_IMAGE_MEDIUM
                ],
                [
                    'project_id' => $project_id,
                    'img_src' => PROJECT_IMAGE_DEFAULT_URL . 'large.jpg',
                    'size' => PROJECT_IMAGE_LARGE
                ]
            );

            $this->load->model('picture_model');
            $this->picture_model->save_project_picture($refs);
            
            redirect(site_url('projects/edit/' . $project_id));
        } else {
            $this->data['error'] = "Ha ocurrido un error y no se ha podido crear el proyecto, por favor intenta mÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡s tarde.";
            return $this->create();
        }
    }

    /*
     * Vista Editar
     */

    public function edit() {
        $this->requires_login();

        $project_id = $this->uri->segment(3);

        if (isset($project_id) && $project_id > 0) {
            $this->requires_owner($project_id);

            $this->load->library('parser');
            $this->load->library('table');
            $this->load->model('category_model');
            $this->load->model('reward_model');
            $this->load->model('bank_model');
            $this->load->model('faq_model');
            $this->load->model('team_model');
            $this->load->model('team_application_model');
            $this->load->model('project_resource_model');
            
            $ttemplate = array(
                'table_open' => '<table class="table table-hover">'
            );
            $this->table->set_template($ttemplate);

            // Info del proyecto
            $project = $this->project_model->get($project_id);
            $project->picture = $this->format_img_src($project->picture);
            $project->owner_picture = $this->format_img_src($project->owner_picture);
            $project->owner_registered = date('d M Y', strtotime($project->owner_registered));
            $project->limit_date = date('d M Y', strtotime($project->limit_date));
            $this->data['project'] = $project;

            // Miembros del equipo
            $members = $this->team_model->get_list($project_id);
            $members_html = array();

            foreach ($members as $member) {
                $member['img_src'] = $this->format_img_src($member['img_src']);
                $member['profile_url'] = site_url('users/u/' . $member['member_id']);
                $member['leader_icon'] = $member['leader'] ? '<i class="fa fa-star-o"></i>' : '';
                $member['remove_button'] = !$member['leader'] ? '<a class="remove_member_js" href="#" data-id="' . $member['member_id'] . '" data-u="member_u_' . $member['member_id'] . '"><i class="fa fa-angle-right"></i> Desvincular</a>' : '';
                $html = $this->parser->parse('projects/fragments/member', $member, true);
                array_push($members_html, $html);
            }

            $this->data['team'] = $members_html;


            // Vacantes
            $this->table->set_heading('Rol', 'Cupos', 'Cupos utilizados', 'Descripci&oacute;n', '');
            $vacants = $this->team_model->get_roles($project_id);

            foreach ($vacants as $vac) {
                $vac[] = '<button class="no-button" type="button" data-toggle="modal" data-target="#modal-role" data-r="' . $vac['role_name']
                        . '" data-a="' . $vac['vacants_amount']
                        . '" data-d="' . $vac['role_description']
                        . '" data-rid="' . $vac['role_id']
                        . '"><i class="fa fa-pencil-square-o"></i></button>';
                $this->table->add_row(array_slice($vac, 1));
            }

            $this->data['vacants_table'] = $this->table->generate();
            $this->table->clear();

            // Postulaciones
            $applications = $this->team_application_model->get_list($project_id);
            $apps_html = array();

            foreach ($applications as $app) {
                $app['img_src'] = $this->format_img_src($app['img_src']);
                $app['profile_url'] = site_url('users/u/' . $app['user_id']);
                $html = $this->parser->parse('projects/fragments/team_application', $app, true);
                array_push($apps_html, $html);
            }

            $this->data['applications'] = $apps_html;

            // Categorias
            $cat_results = $this->category_model->get_list();
            $categories = array();
            foreach ($cat_results as $cat) {
                $categories[$cat->category_id] = $cat->name;
            }
            $this->data['categories'] = $categories;

            // Recursos
            $this->table->set_heading('Recurso', 'Costo', 'Cantidad', 'Utilizaci&oacute;n', 'Importante', '');
            $resources = $this->project_resource_model->get_list($project_id);

            foreach ($resources as $r) {
                $r['required'] = $r['required'] ? 'Si' : 'No';
                $r[] = '<button class="no-button" type="button" data-toggle="modal" data-target="#modal-costs" data-r="' . $r['name']
                        . '" data-c="' . $r['cost']
                        . '" data-a="' . $r['amount']
                        . '" data-d="' . $r['detail']
                        . '" data-i="' . $r['required']
                        . '" data-rid="' . $r['resource_id']
                        . '"><i class="fa fa-pencil-square-o"></i></button>';
                $this->table->add_row(array_slice($r, 1));
            }

            $this->data['resources_table'] = $this->table->generate();
            $this->table->clear();

            // Preguntas frecuentes
            $faq = $this->faq_model->get_list($project_id);
            $this->data['faq'] = $faq;
            
            // Tipos de financiamiento
            $funding_modes = array(
                FUNDING_MODE_PRIVATE => 'Privado',
                FUNDING_MODE_GOV => 'Con ayuda del Estado',
                FUNDING_MODE_COMMUNITY => 'Comunitario'
            );
            $this->data['funding_modes'] = $funding_modes;
            
            // Bancos
            $banks = $this->bank_model->get_list();
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
            
            // Recompensas
            $rewards = $this->reward_model->get_list($project_id);
            $rewards_html = array();
            
            foreach ($rewards as $r) {
                $r['delivery_type_text'] = $this->delivery_types[$r['delivery_type']];
                
                if (isset($r['delivery_date'])){
                    $r['delivery_date'] = strftime('%B/%Y', strtotime($r['delivery_date']));
                }
                
                $html = $this->parser->parse('projects/fragments/rewards_row', $r, true);
                
                array_push($rewards_html, $html);
            }
            $this->data['rewards'] = $rewards_html;
        }

        $this->data['view_title'] = 'Editar proyecto';
        $this->load->view('global/header', $this->data);
        $this->load->view('projects/edit');
        $this->load->view('global/footer');
    }
    
    /**
     * Vista Perfil proyecto
     * @param int $project_id Id del proyecto
     * @param string $hash (opcional) Hash para la vista previa
     */
    public function view($project_id = 0, $hash = null) {

        if (!isset($project_id) || $project_id == 0) {
            show_404(null, false);
        }
        
        $project = $this->project_model->get($project_id);
        
        if (!isset($project)) {
            show_404(null, false);
        }
        
        if ($project->status <= PROJECT_STATUS_EDITING) {
            if ($project->owner_id != $this->session->user_id && ($hash == null || $hash != $project->preview_hash)) {
                show_404(null, false);
            }
        }
        
        $this->load->library('parser');
        $this->load->library('table');
        $this->load->model('reward_model');
        $this->load->model('faq_model');
        $this->load->model('project_resource_model');
        $this->load->model('team_model');
        
        $ttemplate = array(
            'table_open' => '<table class="table table-hover">'
        );
        $this->table->set_template($ttemplate);
        
        $project->picture = $this->format_img_src($project->picture);
        $project->owner_picture = $this->format_img_src($project->owner_picture);
        $project->owner_registered = date('d/m/Y', strtotime($project->owner_registered));
        $project->limit_date = date('d/m/Y', strtotime($project->limit_date));
        $this->data['project'] = $project;

        // Vacantes
        $vacants = $this->team_model->get_available_roles($project_id);
        $vacs_html = array();

        foreach ($vacants as $vac) {
            $vac['modal_id'] = '#modal-role';
            $html = $this->parser->parse('projects/fragments/vacant_view', $vac, true);
            array_push($vacs_html, $html);
        }

        $this->data['vacants'] = $vacs_html;

        // Miembros del equipo
        $membs = $this->team_model->get_list($project_id);
        $members = array();

        $leader = $this->team_model->get_leader($project_id);
        $leader['img_src'] = $this->format_img_src($leader['img_src']);
        $leader['profile_url'] = site_url('users/u/' . $leader['member_id']);
        $leader['role_name'] = 'L&iacute;der del proyecto';
        $leader['leader_icon'] = '<i class="fa fa-shield"></i>';
        array_push($members, $leader);

        foreach ($membs as $member) {
            $member['img_src'] = $this->format_img_src($member['img_src']);
            $member['profile_url'] = site_url('users/u/' . $member['member_id']);
            array_push($members, $member);
        }

        $this->data['team'] = $members;


        // Recursos
        $this->table->set_heading('Recurso', 'Costo', 'Cantidad', 'Utilizaci&oacute;n', 'Importante', '');
        $resources = $this->project_resource_model->get_list($project_id);
        foreach ($resources as $r) {
            $r['required'] = $r['required'] ? 'Si' : 'No';
            $this->table->add_row(array_slice($r, 1));
        }
        $this->data['resources_table'] = $this->table->generate();
        $this->table->clear();

        // Preguntas frecuentes
        $faq = $this->faq_model->get_list($project_id);
        $this->data['faq'] = $faq;
        
        // Recompensas
        $rewards = $this->reward_model->get_list($project_id);
        $rewards_html = array();
        
        $no_reward = array(
            'reward_id' => 0,
            'project_id' => $project_id,
            'description' => 'Realizar un aporte voluntario sin esperar una recompensa.',
            'delivery_type' => REWARD_DELIVERY_NONE,
            'delivery_type_text' => $this->delivery_types[REWARD_DELIVERY_NONE],
            'min_amount' => 1,
            'delivery_date' => null,
            'delivery_notes' => null,
            'limit' => 'Ilimitado',
            'backers_amount' => 0
        );
        $no_html = $this->parser->parse('projects/fragments/no_reward_block', $no_reward, true);
        array_push($rewards_html, $no_html);
        
        foreach ($rewards as $reward) {
            $reward['delivery_type_text'] = $this->delivery_types[$reward['delivery_type']];
            $reward['backers_amount'] = $this->reward_model->count_backers($reward['reward_id']);
            $reward['project_id'] = $project_id;
            $reward['limit'] = $reward['limit'] == 0 ? 'Ilimitado' : $reward['limit'];
            
            if (isset($reward['delivery_date'])){
                $reward['delivery_date'] = strftime('%B/%Y', strtotime($reward['delivery_date']));
            }
            
            $html = $this->parser->parse('projects/fragments/reward_block', $reward, true);
            
            array_push($rewards_html, $html);
        }
        $this->data['rewards'] = $rewards_html;

        $this->data['view_title'] = $project->title;

        $this->load->view('global/header', $this->data);
        $this->load->view('projects/view');
        $this->load->view('global/footer');
    }

    /*
     * Operaciones con las vacantes del proyecto
     */

    public function vacants($project_id) {
        if (!$this->is_ajax() || (!isset($project_id) || $project_id <= 0)) {
            $this->return_ajax_error('Solicitud invalida.');
        }

        $action = $this->input->post('action');

        if ($this->isOwner($project_id) && isset($action)) {
            switch ($action) {
                case 'add':
                    $this->load->model('role_model');
                    
                    $data = array(
                        'project_id' => $project_id,
                        'role_name' => $this->input->post('role'),
                        'vacants_amount' => $this->input->post('amount'),
                        'role_description' => $this->input->post('description')
                    );
                    $result = $this->role_model->add($data);

                    if ($result) {
                        $this->return_ajax_success($result);
                    } else {
                        $this->return_ajax_error('Error al intentar crear la vacante');
                    }
                    break;
                case 'edit':
                    $this->load->model('role_model');
                    
                    $role_id = $this->input->post('role_id');
                    $data = array(
                        'role_name' => $this->input->post('role'),
                        'vacants_amount' => $this->input->post('amount'),
                        'role_description' => $this->input->post('description')
                    );

                    if ($this->role_model->edit($role_id, $data)) {
                        $this->return_ajax_success();
                    } else {
                        $this->return_ajax_error('Error al intentar editar la vacante');
                    }
                    break;
                case 'remove':
                    $this->load->model('role_model');
                    $this->load->model('team_model');
                    
                    $role_id = $this->input->post('role');

                    if (!isset($role_id)) {
                        $this->return_ajax_error();
                    }

                    if ($this->team_model->count_in_role($project_id, $role_id) > 0) {
                        $this->return_ajax_error('Existen miembros ocupando este rol. Por favor desvinculalos del equipo primero, si realmente quieres eliminar el rol.');
                    }

                    if ($this->role_model->remove($project_id, $role_id)) {
                        $this->return_ajax_success();
                    } else {
                        $this->return_ajax_error('Error al intentar eliminar la vacante');
                    }
                    break;
                default:
                    $this->return_ajax_error();
            }
        }
    }

    /*
     * Operaciones con los miembros del equipo
     */

    public function members() {
        if ($this->is_ajax()) {
            $project_id = $this->uri->segment(3);

            if (isset($project_id) && $project_id > 0) {
                $this->isOwner($project_id);

                $action = $this->input->post('action');

                if ($this->isOwner($project_id) && isset($action)) {
                    switch ($action) {
                        case 'remove':
                            $this->load->model('role_model');
                            $this->load->model('team_model');
                            
                            $user_id = $this->input->post('uID');

                            // No se puede eliminar al lider del equipo
                            if (isset($user_id) && $user_id > 0 && !$this->team_model->is_leader($project_id, $user_id)) {
                                $role = $this->team_model->user_role($project_id, $user_id);

                                if ($this->team_model->remove_member($project_id, $user_id)) {
                                    $this->role_model->update_occupied($project_id, $role, '-1');

                                    $this->return_ajax_success();
                                } else {
                                    $this->return_ajax_error('No se pudo desvincular al miembro del equipo.');
                                }
                            } else {
                                $this->return_ajax_error('Usuario invalido.');
                            }
                            break;
                        default:
                            $this->return_ajax_error();
                    }
                }
            }
        }
    }

    /*
     * Operaciones con las postulaciones a un proyecto
     */

    public function applications() {
        $this->requires_login();

        if ($this->is_ajax()) {
            $project_id = $this->uri->segment(3);

            if (isset($project_id) && $project_id > 0) {
                $action = $this->input->post('action');

                if (isset($action)) {
                    switch ($action) {
                        case 'add':
                            $this->load->model('team_application_model');
                            
                            $app = array(
                                'project_id' => $project_id,
                                'role_id' => $this->input->post('role'),
                                'user_id' => $this->session->user_id,
                                'message' => $this->input->post('msg')
                            );

                            if (isset($role) && $role > 0 && !$this->isOwner($project_id)) {
                                // $project_id no es necesario, pero se utiliza por seguridad
                                if ($this->team_application_model->add($app)) {
                                    $this->return_ajax_success();
                                } else {
                                    $this->return_ajax_error('Ocurrio un error al procesar la solicitud.');
                                }
                            } else {
                                $this->return_ajax_error('Solicitud invalida.');
                            }
                            break;
                        case 'accept':
                            $this->load->model('role_model');
                            $this->load->model('team_model');
                            $this->load->model('team_application_model');
                            
                            $app_id = $this->input->post('appID');

                            if (isset($app_id) && $app_id > 0 && $this->isOwner($project_id)) {
                                // $project_id no es necesario, pero se utiliza por seguridad
                                if ($this->team_application_model->accept($project_id, $app_id)) {
                                    $data = $this->team_application_model->get($app_id);

                                    if ($this->team_model->add_member($data)) {
                                        $this->load->library('parser');
                                        $member = $this->team_model->get_member($project_id, $data['member_id']);
                                        $member['img_src'] = $this->format_img_src($member['img_src']);
                                        $member['profile_url'] = site_url('users/u/' . $member['member_id']);
                                        $member['leader_icon'] = '';
                                        $member['remove_button'] = '<button type="button" class="btn btn-primary remove_member_js" data-id="' . $member['member_id'] . '" data-u="member_u_' . $member['member_id'] . '">Desvincular</button>';
                                        $html = $this->parser->parse('projects/fragments/member', $member, true);

                                        $this->role_model->update_occupied($project_id, $data['role_id'], '+1');

                                        $this->return_ajax_success('Agregado al equipo.', array('el' => $html));
                                    } else {
                                        $this->return_ajax_error('Ocurrio un error al procesar la solicitud.');
                                    }
                                } else {
                                    $this->return_ajax_error('Ocurrio un error al procesar la solicitud.');
                                }
                            } else {
                                $this->return_ajax_error('Solicitud invalida.');
                            }
                            break;
                        case 'reject':
                            $this->load->model('team_application_model');
                            
                            $app_id = $this->input->post('appID');

                            if (isset($app_id) && $app_id > 0 && $this->isOwner($project_id)) {
                                
                                if ($this->team_application_model->reject($project_id, $app_id)) {
                                    $this->return_ajax_success('Solicitud rechazada.');
                                } else {
                                    $this->return_ajax_error('Ocurrio un error al procesar la solicitud.');
                                }
                            } else {
                                $this->return_ajax_error('Solicitud invalida.');
                            }
                            break;
                        default:
                            $this->return_ajax_error();
                    }
                }
            }
        }
    }

    /*
     * Operaciones con los recursos requeridos
     */

    public function resources() {
        if ($this->is_ajax()) {
            $project_id = $this->uri->segment(3);

            if (isset($project_id) && $project_id > 0) {
                $action = $this->input->post('action');

                if ($this->isOwner($project_id) && isset($action)) {
                    switch ($action) {
                        case 'add':
                            $this->load->model('project_resource_model');
                            
                            $data = array(
                                'project_id' => $project_id,
                                'name' => $this->input->post('name'),
                                'cost' => $this->input->post('cost'),
                                'amount' => $this->input->post('amount'),
                                'detail' => $this->input->post('description'),
                                'required' => $this->input->post('required') == 'on' ? true : false
                            );
                            $result = $this->project_resource_model->add($data);

                            if ($result != NULL) {
                                $this->return_ajax_success($result);
                            } else {
                                $this->return_ajax_error('Error al intentar agregar el recurso');
                            }
                            break;
                        case 'edit':
                            $role_id = $this->input->post('role_id');
                            $role = $this->input->post('role');
                            $amount = $this->input->post('amount');
                            $description = $this->input->post('description');

                            if ($this->project_model->editRole($role_id, $role, $amount, $description)) {
                                $this->return_ajax_success();
                            } else {
                                $this->return_ajax_error('Error al intentar editar la vacante');
                            }
                            break;
                        case 'remove':
                            $role = $this->input->post('role');

                            if ($this->project_model->removeVacant($project_id, $role)) {
                                $this->return_ajax_success();
                            } else {
                                $this->return_ajax_error('Error al intentar eliminar la vacante');
                            }
                            break;
                        default:
                            $this->return_ajax_error();
                    }
                }
            }
        }
    }

    /*
     * Operaciones de actualizacion de informacion del proyecto
     */

    public function save() {
        if ($this->is_ajax()) {
            $project_id = $this->uri->segment(3);

            if (isset($project_id) && $project_id > 0) {
                $segment = $this->input->post('segment');

                if ($this->isOwner($project_id) && isset($segment)) {
                    $this->load->library('form_validation');

                    switch ($segment) {
                        case 'general':
                            $data = array(
                                'title' => $this->input->post('title'),
                                'category' => $this->input->post('category'),
                                'limit_date' => $this->input->post('ddate'),
                                'summary' => $this->input->post('summary'),
                                'problem' => $this->input->post('problem'),
                                'solution' => $this->input->post('solution'),
                                'target_group' => $this->input->post('target')
                            );

                            if ($this->project_model->update($project_id, $data)) {
                                $this->return_ajax_success();
                            } else {
                                $this->return_ajax_error();
                            }
                            break;
                        case 'extrainfo':
                            $info = $this->input->post('extra');
                            if (isset($info) && !empty($info)) {
                                $data = array(
                                    'extra_info' => base64_encode($this->input->post('extra'))
                                );

                                if ($this->project_model->update($project_id, $data)) {
                                    $this->return_ajax_success();
                                } else {
                                    log_message('debug', 'Error al intentar guardar los cambios. ' . $info);
                                    $this->return_ajax_error('Error al intentar guardar los cambios.');
                                }
                            } else {
                                log_message('debug', 'Texto vacio. ' . $info);
                                $this->return_ajax_error('Texto vacio.');
                            }
                            break;
                        default:
                            $this->return_ajax_error();
                    }
                } else {
                    $this->return_ajax_error('Accion invalida.');
                }
            }
        }
    }

    /*
     * Operaciones con las preguntas frecuentes
     */

    public function faqcreate($project_id) {
        if (!$this->is_ajax() || !isset($project_id)) {
            $this->return_ajax_error();
        }
        
        $this->load->model('faq_model');

        $quest = $this->input->post('question');
        $answ = $this->input->post('answer');

        if (!isset($quest) || !isset($answ)) {
            $this->return_ajax_error();
        }

        if ($this->faq_model->add($project_id, $quest, $answ)) {
            $this->return_ajax_success();
        } else {
            $this->return_ajax_error();
        }
    }
    
    /**
     * Vista detalle de la donacion
     * @param int $project_id Id del proyecto
     */
    public function donate() {
        $this->requires_login();
        $this->load->model('reward_model');
        
        $project_id = $this->input->post('project-id');
        $backing = $this->input->post('backing');
        $reward = $this->reward_model->get($backing['reward_id']);
        
        if (isset($reward)) {
            $reward->delivery_type_text = $this->delivery_types[$reward->delivery_type];
            $reward_backers_amount = $this->reward_model->count_backers($reward->reward_id);

            $available = $reward->limit == 0 || $reward->limit > $reward_backers_amount;
        } else {
            $available = true;
        }
        
        $this->data['project_id'] = $project_id;
        $this->data['reward_available'] = $available;
        $this->data['reward'] = $reward;
        $this->data['pledge_amount'] = $backing['amount'];
        $this->data['view_title'] = 'Donar';
        
        $this->load->view('global/header', $this->data);
        $this->load->view('projects/donate');
        $this->load->view('global/footer');
    }

    protected function requires_owner($project_id) {
        if (!$this->isOwner($project_id)) {
            redirect('portal/home');
        }
    }

    protected function isOwner($project_id) {
        return $this->project_model->is_owner($this->session->user_id, $project_id);
    }

}
