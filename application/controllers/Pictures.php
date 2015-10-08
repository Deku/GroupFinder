<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pictures Controller
 * 
 * Handles the profile pictures across the site
 *
 * @author Jose Gonzalez
 */
class Pictures extends GF_Global_controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Uploads a file and sets as the user avatar
     */
    public function upload() {
        $this->requires_login();
        
        if ($this->input->post('source'))
        {
            /*
             * STEP 1 => Upload the picture to the user's assets folder
             */

            $RELATIVE_PATH = 'assets/images/';
            $TYPE_PATH = '';
            // Upload helper config
            $config = array(
                'upload_path' => FCPATH . $RELATIVE_PATH,
                'allowed_types' => 'gif|jpg|png',
                'max_size' => '2048'
            );

            // Is this a user or project profile?
            switch ($this->input->post('source'))
            {
                case sha1('profile-edit'):
                    $TYPE_PATH = 'users/' . sha1($this->session->user_id . $this->session->username) . '/';
                    break;
                case sha1('project-edit'):
                    $TYPE_PATH = 'users/' . sha1($this->input->post('projectID') . $this->session->username) . '/';
                    break;
            }

            $config['upload_path'] .= $TYPE_PATH;

            // Prepare folder for upload
            $this->prepare_folder($config['upload_path']);

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image-file'))
            {
                $this->return_ajax_error($this->upload->display_errors());
            }
            /*
             * STEP 2 => Process the image with ZebraImage and generate the different views
             */

            $this->load->library('zebra_image');

            $this->zebra_image->source_path = $this->upload->data('full_path');
            // File references to store in db
            $refs = array();
            $errors = array();

            // small nav image
            $this->zebra_image->target_path = $this->upload->data('file_path') . 'small.jpg';

            if ($this->zebra_image->resize(USER_IMAGE_SMALL, USER_IMAGE_SMALL, ZEBRA_IMAGE_CROP_CENTER))
            {
                array_push($refs, array(
                    'user_id' => $this->session->user_id,
                    'img_src' => $RELATIVE_PATH . $TYPE_PATH . 'small.jpg',
                    'size' => USER_IMAGE_SMALL
                ));
            } else {
                array_push($errors, $this->zebra_image->error);
            }

            // medium comments image
            $this->zebra_image->target_path = $this->upload->data('file_path') . 'medium.jpg';

            if ($this->zebra_image->resize(USER_IMAGE_MEDIUM, USER_IMAGE_MEDIUM, ZEBRA_IMAGE_CROP_CENTER))
            {
                array_push($refs, array(
                    'user_id' => $this->session->user_id,
                    'img_src' => $RELATIVE_PATH . $TYPE_PATH . 'medium.jpg',
                    'size' => USER_IMAGE_MEDIUM
                ));
            } else {
                array_push($errors, $this->zebra_image->error);
            }

            // large nav image
            $this->zebra_image->target_path = $this->upload->data('file_path') . 'large.jpg';

            if ($this->zebra_image->resize(USER_IMAGE_LARGE, USER_IMAGE_LARGE, ZEBRA_IMAGE_CROP_CENTER))
            {
                array_push($refs, array(
                    'user_id' => $this->session->user_id,
                    'img_src' => $RELATIVE_PATH . $TYPE_PATH . 'large.jpg',
                    'size' => USER_IMAGE_LARGE
                ));
            } else {
                array_push($errors, $this->zebra_image->error);
            }

            // Error check
            if (count($errors) > 0)
            {
                log_message('error', print_r($errors));

                $this->return_ajax_error();
            }

            // Save the references to db
            $this->load->model('picture_model');
            $result = $this->picture_model->save_user_picture($refs);

            // If the save is successful, clean the folder and update session
            if ($result)
            {
                $this->delete_old_pictures($this->upload->data('file_path'));
                $this->update_session();

                echo $this->format_img_src($refs[2]['img_src']);
            } else {
                $this->rollback_changes($this->upload->data('file_path'));

                $this->return_ajax_error();
            }
        }
    }
    
    /**
     * Uses the picture provided by Gravatar.com
     */
    public function use_gravatar() {
        if ($this->input->is_ajax_request())
        {
            $img_small = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->session->user_email))) . '?d=mm&s=40';
            $img_medium = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->session->user_email))) . '?d=mm&s=85';
            $img_large = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->session->user_email))) . '?d=mm&s=297';
            
            $refs = array(
                [
                    'user_id' => $this->session->user_id,
                    'img_src' => $img_small,
                    'size' => USER_IMAGE_SMALL
                ],
                [
                    'user_id' => $this->session->user_id,
                    'img_src' => $img_medium,
                    'size' => USER_IMAGE_MEDIUM
                ],
                [
                    'user_id' => $this->session->user_id,
                    'img_src' => $img_large,
                    'size' => USER_IMAGE_LARGE
                ]
            );
            
            $this->load->model('picture_model');
            $result = $this->picture_model->save_user_picture($refs);
            
            if ($result)
            {
                $this->update_session();
            }
            
            return $result ? $img_large : false;
        }
    }
    
    /**
     * Checks a folder and renames all the content inside it
     * @param string $dir Path of the folder to be prepared
     */
    public function prepare_folder($dir) {
        if (!file_exists($dir))
        {
            mkdir($dir);
        } else {
            if (count(glob($dir.'*.jpg')) > 0)
            {
                if ($handle = opendir($dir)) {
                    while (false !== ($fileName = readdir($handle))) {
                        if ($fileName != "." && $fileName != "..")
                        {
                            $newName = '_' . $fileName;
                            rename($dir . $fileName, $dir . $newName);
                        }
                    }
                    closedir($handle);
                }
            }
        }
        
    }

    /**
     * Deletes the files starting by "_" (renamed by prepare_folder($dir))
     * @param string $dir Path of the folder to be cleaned
     */
    private function delete_old_pictures($dir) {
        if (count(glob($dir.'*.jpg')) > 0)
        {
            if ($handle = opendir($dir)) {
                while (false !== ($fileName = readdir($handle))) {
                    if ($fileName != "." && $fileName != "..")
                    {
                        if (preg_match("/^_\w+/", $fileName) == 1)
                        {
                            unlink($dir . $fileName);
                        }
                    }
                }
                closedir($handle);
            }
        }
    }

    /**
     * Rollbacks the changes made to files in the folder (renamed by prepare_folder())
     * @param string $dir Path of the folder
     */
    private function rollback_changes($dir) {
        if (count(glob($dir.'*.jpg')) > 0)
        {
            if ($handle = opendir($dir)) {
                while (false !== ($fileName = readdir($handle))) {
                    if ($fileName != "." && $fileName != "..")
                    {
                        if (preg_match("/^_\w+/", $fileName) == 1)
                        {
                            $newName = str_replace('_', '', $fileName);
                            rename($dir . $fileName, $dir . $newName);
                        } else {
                            unlink($dir . $fileName);
                        }
                    }
                }
                closedir($handle);
            }
        }
    }
}
