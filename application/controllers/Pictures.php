<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pictures extends GF_Global_controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function upload() {
        $this->requires_login();
        
        if ($this->input->post('source'))
        {
            /*
             * PASO 1 => Cargar la foto al directorio del usuario
             */

            // En la db despues lo almacenaremos para ocuparlo en los <img>
            $RELATIVE_PATH = 'assets/images/';
            // Foto para un usuario o para un proyecto?
            $TYPE_PATH = '';
            // Configuracion para guardar los archivos en disco
            $config = array(
                'upload_path' => FCPATH . $RELATIVE_PATH,
                'allowed_types' => 'gif|jpg|png',
                'max_size' => '2048'
            );

            switch ($this->input->post('source'))
            {
                case sha1('profile-edit'):
                    $TYPE_PATH = 'users/' . sha1($this->session->user_id . $this->session->username) . '/';
                    break;
                case sha1('project-edit'):
                    $TYPE_PATH = 'users/' . sha1($this->input->post('projectID') . $this->session->username) . '/';
                    break;
            }

            // Establecemos el directorio final donde guardaremos las imagenes
            $config['upload_path'] .= $TYPE_PATH;

            // Verificamos si la carpeta tiene archivos y los renombramos
            // Asi en caso de error no reemplazamos lo que ya estaba
            $this->prepare_folder_for_upload($config['upload_path']);

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image-file'))
            {
                $this->return_ajax_error($this->upload->display_errors());
            }
            /*
             * PASO 2 => Procesar las fotos con Zebra y generar los distintos tamaños
             */

            $this->load->library('zebra_image');
            // Imagen original subida al servidor
            $this->zebra_image->source_path = $this->upload->data('full_path');
            // Referencias a insertar en la db
            $refs = array();
            // En caso de que existan errores
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

            // Si no hubieron errores en cargar las miniaturas
            if (count($errors) > 0)
            {
                log_message('error', print_r($errors));

                echo false;
                return;
            }

            // Actualizamos las referencias en la base de datos
            $this->load->model('picture_model');
            $result = $this->picture_model->save_user_picture($refs);
            
            if ($result)
            {
                // Si todo fue ok, podemos borrar las fotos viejas y actualizamos la sesion
                $this->delete_old_pictures($this->upload->data('file_path'));
                $this->update_session();
                echo $this->format_img_src($refs[2]['img_src']);
            } else {
                // O eliminamos las fotos cargadas y retornamos false para indicar un error
                $this->rollback_changes($this->upload->data('file_path'));
                echo false;
            }
        }
    }
    
    public function useGravatar() {
        if ($this->is_ajax())
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
    
    public function prepare_folder_for_upload($dir) {
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
