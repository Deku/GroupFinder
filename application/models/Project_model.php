<?php

/**
 * Description of Project_model
 *
 * @author José González <maangx@gmail.com>
 */
class Project_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function createProject($data) {
        return $this->db->insert('projects', $data) ? $this->db->insert_id() : null;
    }

    public function createVacants($dataArray) {
        $res = true;

        foreach ($dataArray as $data) {
            $res = $res && $this->db->insert('projects_roles', $data);
        }

        return $res;
    }

    public function getCategories() {
        $query = $this->db->select('category_id, name')
                ->get('projects_categories');
        return $query->result();
    }

    public function getDetailedCategories() {
        $query = $this->db->select('a.category_id, a.name, a.description, (SELECT COUNT(project_id) FROM projects b WHERE a.category_id = b.category AND status IN ("' . PROJECT_STATUS_GROWING . '","' . PROJECT_STATUS_STARTED . '","' . PROJECT_STATUS_FINISHED . '")) count')
                ->get('projects_categories a');
        return $query->result();
    }

    public function getCategoryName($category_id) {
        $query = $this->db->select('name')
                ->where('category_id', $category_id)
                ->get('projects_categories');

        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row->name;
        }
    }

    public function getCategoryProjects($category_id) {
        $query = $this->db->select('projects.project_id, title, status_name, summary, limit_date, img_src picture')
                ->where(array('category' => $category_id, 'size' => PROJECT_IMAGE_SMALL))
                ->where_in('projects.status', array(PROJECT_STATUS_GROWING, PROJECT_STATUS_STARTED, PROJECT_STATUS_FINISHED))
                ->join('projects_picture', 'projects.project_id = projects_picture.project_id')
                ->join('projects_status', 'projects.status = projects_status.status_id')
                ->get('projects');

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return null;
    }

    public function getUserOverview($user_id) {
        $query = $this->db->select('projects.project_id, title, status_name, limit_date, img_src picture')
                ->join('projects_picture', 'projects.project_id = projects_picture.project_id')
                ->join('projects_status', 'projects.status = projects_status.status_id')
                ->where(array(
                    'owner_id' => $user_id,
                    'size' => PROJECT_IMAGE_SMALL)
                )
                ->get('projects');

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return null;
    }

    public function getProjectProfile($project_id) {
        $query = $this->db->select('a.*, g.status_name, b.category_id, b.name category,'
                        . ' e.img_src picture, c.name owner_name, c.title owner_title,'
                        . ' d.country_name owner_country, c.created owner_registered,'
                        . ' f.img_src owner_picture')
                ->from('projects a')
                ->join('projects_categories b', 'a.category = b.category_id')
                ->join('users c', 'a.owner_id = c.user_id')
                ->join('countries d', 'c.country = d.country_id')
                ->join('projects_picture e', 'a.project_id = e.project_id')
                ->join('projects_status g', 'a.status = g.status_id')
                ->join('users_profile_pictures f', 'a.owner_id = f.user_id')
                ->where(array(
                    'a.project_id' => $project_id,
                    'e.size' => PROJECT_IMAGE_LARGE,
                    'f.size' => USER_IMAGE_MEDIUM)
                )
                ->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return null;
    }
    
    public function getProjectTitle($project_id) {
        $query = $this->db->select('title')
                          ->where('project_id', $project_id)
                          ->get('projects');
        
        return $query->num_rows() > 0 ? $query->row()->title : '';
    }

    public function getAvailableVacants($project_id) {
        /* $query = $this->db->where('project_id =' . $project_id . ' AND vacants_used < vacants_amount')
          ->get('projects_roles'); */
        $query = $this->db->query('SELECT *,'
                . ' (SELECT COUNT(application_id) FROM projects_applications a WHERE r.role_id = a.role_id AND a.status = 0) postulantes'
                . ' FROM projects_roles r'
                . " WHERE project_id = {$project_id} AND vacants_used < vacants_amount");

        return $query->num_rows() > 0 ? $query->result_array() : array();
    }

    public function getVacants($project_id) {
        $query = $this->db->select('role_id, role_name, vacants_amount, vacants_used, role_description')
                ->where(array('project_id' => $project_id, 'NOT role_name LIKE "Líder del proyecto"'))
                ->get('projects_roles');

        return $query->num_rows() > 0 ? $query->result_array() : array();
    }

    public function getResources($project_id) {
        $query = $this->db->select('resource_id, name, cost, amount, detail, required')
                ->where('project_id', $project_id)
                ->order_by('required DESC, cost ASC')
                ->get('projects_costs');

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return array();
    }

    public function getRewards($project_id) {
        $query = $this->db->where('project_id', $project_id)
                            ->order_by('min_amount', 'ASC')
                            ->get('projects_rewards');

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return array();
    }

    public function checkOwner($user_id, $project_id) {
        $query = $this->db->select('project_id')
                ->where(array('owner_id' => $user_id, 'project_id' => $project_id))
                ->get('projects');

        return $query->num_rows() > 0 ? true : false;
    }

    public function addRole($project_id, $role, $amount, $description) {
        return $this->db->insert(
                        'projects_roles', array(
                    'project_id' => $project_id,
                    'role_name' => $role,
                    'vacants_amount' => $amount,
                    'role_description' => $description
                        )
                ) ? $this->db->insert_id() : null;
    }

    public function editRole($role_id, $role, $amount, $description) {
        $this->db->where('role_id', $role_id);

        $this->db->update(
                'projects_roles', array(
            'role_name' => $role,
            'vacants_amount' => $amount,
            'role_description' => $description
                )
        );

        return $this->db->affected_rows() > 0;
    }

    public function getApplications($project_id) {

        $query = $this->db->select('a.application_id, a.user_id, role_name, name, title, img_src, a.message, a.application_time')
                ->from('projects_applications a')
                ->join('projects_roles', 'a.role_id = projects_roles.role_id')
                ->join('users', 'a.user_id = users.user_id')
                ->join('users_profile_pictures', 'a.user_id = users_profile_pictures.user_id')
                ->where(array('a.project_id' => $project_id, 'size' => USER_IMAGE_MEDIUM, 'a.status' => TEAM_APPLICATION_WAITING))
                ->order_by('application_time', 'ASC')
                ->get();

        return $query->num_rows() > 0 ? $query->result_array() : array();
    }

    public function getMembers($project_id) {

        $query = $this->db->select('member_id, role_name, name, title, img_src, join_timestamp, leader')
                ->from('projects_members a')
                ->join('projects_roles', 'projects_roles.role_id = a.role_id')
                ->join('users', 'users.user_id = member_id')
                ->join('users_profile_pictures', 'users_profile_pictures.user_id = member_id')
                ->where(array('a.project_id' => $project_id, 'size' => USER_IMAGE_MEDIUM))
                ->order_by('join_timestamp', 'ASC')
                ->get();

        return $query->num_rows() > 0 ? $query->result_array() : array();
    }

    public function getTeamLeader($project_id) {

        $query = $this->db->select('owner_id member_id, name, users.title, img_src')
                ->from('projects p')
                ->join('users', 'users.user_id = p.owner_id')
                ->join('users_profile_pictures', 'users_profile_pictures.user_id = owner_id')
                ->where(array('p.project_id' => $project_id, 'size' => USER_IMAGE_MEDIUM))
                ->get();

        return $query->num_rows() > 0 ? $query->row_array() : array();
    }

    public function getTeamMember($project_id, $user_id) {

        $query = $this->db->select('member_id, role_name, name, title, img_src, join_timestamp, leader')
                ->from('projects_members a')
                ->join('projects_roles', 'projects_roles.role_id = a.role_id')
                ->join('users', 'users.user_id = member_id')
                ->join('users_profile_pictures', 'users_profile_pictures.user_id = member_id')
                ->where(array('a.project_id' => $project_id, 'size' => USER_IMAGE_MEDIUM, 'member_id' => $user_id))
                ->get();

        return $query->num_rows() > 0 ? $query->row_array() : array();
    }

    public function addMember($data) {
        $this->db->insert('projects_members', $data);

        return $this->db->affected_rows() > 0;
    }

    public function removeMember($project_id, $user_id) {
        $this->db->where(array('project_id' => $project_id, 'member_id' => $user_id))
                ->delete('projects_members');

        return $this->db->affected_rows() > 0;
    }

    public function userIsLeader($project_id, $user_id) {
        $query = $this->db->where(array('project_id' => $project_id, 'member_id' => $user_id, 'leader' => true))
                ->get('projects_members');

        return $query->num_rows() > 0 ? true : false;
    }

    public function getApplication($app_id) {
        $query = $this->db->select('project_id, role_id, user_id AS member_id')
                ->where('application_id', $app_id)
                ->get('projects_applications');

        return $query->num_rows() > 0 ? $query->row_array() : array();
    }

    public function acceptApplication($project_id, $app_id) {
        $this->db->where(array('application_id' => $app_id, 'project_id' => $project_id))
                ->update('projects_applications', array('status' => TEAM_APPLICATION_ACCEPTED));

        return $this->db->affected_rows() > 0;
    }

    public function rejectApplication($project_id, $app_id) {
        $this->db->where(array('application_id' => $app_id, 'project_id' => $project_id))
                ->update('projects_applications', array('status' => TEAM_APPLICATION_REJECTED));

        return $this->db->affected_rows() > 0;
    }

    public function addApplication($project_id, $role_id, $user_id, $message) {
        $this->db->insert('projects_applications', array(
            'project_id' => $project_id,
            'role_id' => $role_id,
            'user_id' => $user_id,
            'message' => $message
        ));

        return $this->db->affected_rows() > 0;
    }

    public function updateInfo($project_id, $data) {
        $this->db->where('project_id', $project_id)
                ->update('projects', $data);

        return $this->db->affected_rows() > 0;
    }

    public function updateVacantsUsed($project_id, $role_id, $variation) {
        $this->db->where(array('project_id' => $project_id, 'role_id' => $role_id))
                ->set('vacants_used', 'vacants_used' . $variation, false)
                ->update('projects_roles');

        return $this->db->affected_rows() > 0;
    }

    public function getUserRole($project_id, $user_id) {
        $query = $this->db->where(array('project_id' => $project_id, 'member_id' => $user_id))
                ->select('role_id')
                ->get('projects_members');

        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row->role_id;
        }
    }

    public function addResource($data) {
        $this->db->insert('projects_costs', $data);

        return $this->db->affected_rows() > 0;
    }

    public function getRandomFeatured($number) {
        $query = $this->db->select('projects.project_id, title, status_name, summary, limit_date, img_src picture')
                ->where(array('featured' => '1', 'size' => PROJECT_IMAGE_SMALL, 'projects.status' => PROJECT_STATUS_GROWING))
                ->join('projects_picture', 'projects.project_id = projects_picture.project_id')
                ->join('projects_status', 'projects.status = projects_status.status_id')
                ->order_by('projects.project_id', 'RANDOM')
                ->limit($number)
                ->get('projects');

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return array();
    }

    public function addFAQ($project_id, $question, $answer) {
        $this->db->insert('projects_faq', array('project_id' => $project_id, 'question' => $question, 'answer' => $answer));

        return $this->db->affected_rows() > 0;
    }

    public function getFAQ($project_id) {
        $query = $this->db->select('list_number, question, answer')
                ->where('project_id', $project_id)
                ->order_by('list_number', 'ASC')
                ->get('projects_faq');

        return $query->num_rows() > 0 ? $query->result_array() : null;
    }

    public function countMembersInRole($project_id, $role_id) {
        $count = $this->db->where(array('project_id' => $project_id, 'role_id' => $role_id))
                ->count_all_results('projects_members');

        return $count;
    }

    public function removeRole($project_id, $role_id) {
        $this->db->where(array('project_id' => $project_id, 'role_id' => $role_id))
                ->delete('projects_roles');

        return $this->db->affected_rows() > 0;
    }
    
    public function getBanks(){
        $query = $this->db->get('banks');
        
        return $query->result();
    }

    public function updateFundingMode($pid, $mode) {
        $this->db->where('project_id', $pid)
                 ->update('projects', array(
                    'funding_mode' => $mode
                 ));
        
        return $this->db->affected_rows() > 0;
    }
    
    public function updateBankData($pid, $data) {
        return $this->db->where('project_id', $pid)
                    ->update('projects', $data);
    }
    
    public function updateRewardsActivated($pid, $bool) {
        $this->db->where('project_id', $pid)
                    ->update('projects', array(
                    'rewards_activated' => $bool
                ));
                log_message('debug', $this->db->last_query());
        return true;
    }
    
    public function addReward($data) {
        return $this->db->insert('projects_rewards', $data) ? $this->db->insert_id() : null;
    }
    
    public function countRewardBackers($id) {
        return $this->db->where('reward_id', $id)
                        ->count_all_results('projects_transactions');
    }
    
    public function getReward($rid) {
        $query = $this->db->where('reward_id', $rid)
                          ->get('projects_rewards');
        
        return $query->num_rows() > 0 ? $query->row() : null;
    }
}
