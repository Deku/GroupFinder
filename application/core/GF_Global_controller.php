<?php

/**
 * Controlador base, maneja operaciones utilizadas por
 * todos los demas controladores.
 * 
 * Esta clase se carga gracias al __autoload() al final de config.php
 *
 * @author Jose Gonzalez
 */
class GF_Global_controller extends CI_Controller {
    
    public $data;
    public $months = array(
                        1 => 'Enero',
                        2 => 'Febrero',
                        3 => 'Marzo',
                        4 => 'Abril',
                        5 => 'Mayo',
                        6 => 'Junio',
                        7 => 'Julio',
                        8 => 'Agosto',
                        9 => 'Septiembre',
                        10 => 'Octubre',
                        11 => 'Noviembre',
                        12 => 'Diciembre'
                    );
    
    public function __construct() {
        parent::__construct();
        
        $unread_messages = 0;
        $unread_notifications = 0;
        $html_notifications = array();
        $html_conversations = array();
        
        $this->checkPersistentSession();
        
        if ($this->session->loggedIn)
        {
            $this->load->model('conversation_model');
            $this->load->model('conversation_message_model');
            //$this->load->model('notification_model');
            $this->load->library('parser');
            
            $unread_messages = intval($this->conversation_message_model->count_unread($this->session->user_id));
            $conversations = $this->conversation_model->get_unread_list($this->session->user_id);
            
            foreach($conversations as $c) {
                $c['img_src'] = $this->format_img_src($c['img_src']);
                $c['msg_url'] = site_url('conversations/messages/'.$c['conversation_id']);
                $c['sent_timestamp'] = date('d-m-y H:i', strtotime($c['sent_timestamp']));
                array_push($html_conversations, $this->parser->parse('conversations/fragments/navbar_conversation', $c, true));
            }
        }
        
        // Verificar si el usuario esta logeado
        $this->data = array(
            'loggedIn' => (isset($this->session->loggedIn)  ? true : false),
            'count_unread_messages' => $unread_messages,
            'count_unread_notifications' => $unread_notifications,
            'unread_conversations' => $html_conversations
        );
    }
    
    public function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function return_ajax_success($msg = '', $extra = array()) {
        header('Content-Type: application/json');
        echo json_encode(array('success_message' => $msg, 'extra' => $extra));
    }

    public function return_ajax_error($msg = 'Error al procesar la solicitud', $extra = array()) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array('error_message' => $msg, 'extra' => $extra)));
    }
    
    // Incluye el base_url en caso de que la direccion apunte a un directorio local
    public function format_img_src($src) {
        return (preg_match('/^(http:)/', $src) ? '' : base_url()) . $src;
    }
    
    public function send_email($mail_id, $receiver, $vars = array()) {
        $this->load->model('email_model');
        $this->load->library('email');
        $this->load->library('parser');
        
        $mail_template = $this->email_model->get($mail_id);
        
        if (!empty($mail_template))
        {
            $this->email->from('no-reply@groupfinder.com', 'Group Finder');
            $this->email->to($receiver);
            $this->email->subject($mail_template->subject);
            
            $body = $this->parser->parse_string($mail_template->body, $vars, true);
            
        } else {
            return false;
        }
        
        $this->email->message($body);
        
        $_SESSION['emailSent'] = $this->email->send();
    }
    
    // Funcion para obtener la direccion ip del cliente. Retorna en formato IPv4 o IPv6
    function get_client_ip() {
        $ipaddress = '';
        
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if(getenv('HTTP_X_FORWARDED_FOR')){
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if(getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if(getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if(getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if(getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = null;
        }
        
        return $ipaddress;
    }
    
    public function requires_login() {
        if (!$this->session->loggedIn)
        {
            redirect('auth/login');
        }
    }
    
    public function requires_guest() {
        if ($this->session->loggedIn)
        {
            redirect('portal/home');
        }
    }
    
    public function update_session() {
        // Obtenemos los datos del usuario para almacenarlos en la sesion
        $this->load->model('user_model');
        $userdata = $this->user_model->get($this->session->user_id);
        
        // Obtenemos la foto del usuario en sus distintos tamaños
        $this->load->model('picture_model');
        $profile_pics = $this->picture_model->get_by_user($this->session->user_id);

        $_SESSION['user_email'] = $userdata->email;
        $_SESSION['user_realname'] = $userdata->name;
        $_SESSION['user_birthday'] = $userdata->birthday;
        $_SESSION['user_title'] = $userdata->title;
        $_SESSION['user_country_id'] = $userdata->country;
        $_SESSION['user_country'] = $userdata->country_name;
        $_SESSION['user_about'] = $userdata->about;
        $_SESSION['user_last_login_ip'] = $userdata->last_login_ip;
        $_SESSION['user_last_login_time'] = $userdata->last_login_time;
        $_SESSION['img_small'] = $this->format_img_src($profile_pics[0]['img_src']);
        $_SESSION['img_medium'] = $this->format_img_src($profile_pics[1]['img_src']);
        $_SESSION['img_large'] = $this->format_img_src($profile_pics[2]['img_src']);
        session_write_close();
    }
    
    /**
    * Replace language-specific characters by ASCII-equivalents.
    * @param string $s
    * @return string
    */
   public function normalizeChars($s) {
       $replace = array(
           'ъ'=>'-', 'Ь'=>'-', 'Ъ'=>'-', 'ь'=>'-',
           'Ă'=>'A', 'Ą'=>'A', 'À'=>'A', 'Ã'=>'A', 'Á'=>'A', 'Æ'=>'A', 'Â'=>'A', 'Å'=>'A', 'Ä'=>'Ae',
           'Þ'=>'B',
           'Ć'=>'C', 'ץ'=>'C', 'Ç'=>'C',
           'È'=>'E', 'Ę'=>'E', 'É'=>'E', 'Ë'=>'E', 'Ê'=>'E',
           'Ğ'=>'G',
           'İ'=>'I', 'Ï'=>'I', 'Î'=>'I', 'Í'=>'I', 'Ì'=>'I',
           'Ł'=>'L',
           'Ñ'=>'N', 'Ń'=>'N',
           'Ø'=>'O', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe',
           'Ş'=>'S', 'Ś'=>'S', 'Ș'=>'S', 'Š'=>'S',
           'Ț'=>'T',
           'Ù'=>'U', 'Û'=>'U', 'Ú'=>'U', 'Ü'=>'Ue',
           'Ý'=>'Y',
           'Ź'=>'Z', 'Ž'=>'Z', 'Ż'=>'Z',
           'â'=>'a', 'ǎ'=>'a', 'ą'=>'a', 'á'=>'a', 'ă'=>'a', 'ã'=>'a', 'Ǎ'=>'a', 'а'=>'a', 'А'=>'a', 'å'=>'a', 'à'=>'a', 'א'=>'a', 'Ǻ'=>'a', 'Ā'=>'a', 'ǻ'=>'a', 'ā'=>'a', 'ä'=>'ae', 'æ'=>'ae', 'Ǽ'=>'ae', 'ǽ'=>'ae',
           'б'=>'b', 'ב'=>'b', 'Б'=>'b', 'þ'=>'b',
           'ĉ'=>'c', 'Ĉ'=>'c', 'Ċ'=>'c', 'ć'=>'c', 'ç'=>'c', 'ц'=>'c', 'צ'=>'c', 'ċ'=>'c', 'Ц'=>'c', 'Č'=>'c', 'č'=>'c', 'Ч'=>'ch', 'ч'=>'ch',
           'ד'=>'d', 'ď'=>'d', 'Đ'=>'d', 'Ď'=>'d', 'đ'=>'d', 'д'=>'d', 'Д'=>'d', 'ð'=>'d',
           'є'=>'e', 'ע'=>'e', 'е'=>'e', 'Е'=>'e', 'Ə'=>'e', 'ę'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'Ē'=>'e', 'Ė'=>'e', 'ė'=>'e', 'ě'=>'e', 'Ě'=>'e', 'Є'=>'e', 'Ĕ'=>'e', 'ê'=>'e', 'ə'=>'e', 'è'=>'e', 'ë'=>'e', 'é'=>'e',
           'ф'=>'f', 'ƒ'=>'f', 'Ф'=>'f',
           'ġ'=>'g', 'Ģ'=>'g', 'Ġ'=>'g', 'Ĝ'=>'g', 'Г'=>'g', 'г'=>'g', 'ĝ'=>'g', 'ğ'=>'g', 'ג'=>'g', 'Ґ'=>'g', 'ґ'=>'g', 'ģ'=>'g',
           'ח'=>'h', 'ħ'=>'h', 'Х'=>'h', 'Ħ'=>'h', 'Ĥ'=>'h', 'ĥ'=>'h', 'х'=>'h', 'ה'=>'h',
           'î'=>'i', 'ï'=>'i', 'í'=>'i', 'ì'=>'i', 'į'=>'i', 'ĭ'=>'i', 'ı'=>'i', 'Ĭ'=>'i', 'И'=>'i', 'ĩ'=>'i', 'ǐ'=>'i', 'Ĩ'=>'i', 'Ǐ'=>'i', 'и'=>'i', 'Į'=>'i', 'י'=>'i', 'Ї'=>'i', 'Ī'=>'i', 'І'=>'i', 'ї'=>'i', 'і'=>'i', 'ī'=>'i', 'ĳ'=>'ij', 'Ĳ'=>'ij',
           'й'=>'j', 'Й'=>'j', 'Ĵ'=>'j', 'ĵ'=>'j', 'я'=>'ja', 'Я'=>'ja', 'Э'=>'je', 'э'=>'je', 'ё'=>'jo', 'Ё'=>'jo', 'ю'=>'ju', 'Ю'=>'ju',
           'ĸ'=>'k', 'כ'=>'k', 'Ķ'=>'k', 'К'=>'k', 'к'=>'k', 'ķ'=>'k', 'ך'=>'k',
           'Ŀ'=>'l', 'ŀ'=>'l', 'Л'=>'l', 'ł'=>'l', 'ļ'=>'l', 'ĺ'=>'l', 'Ĺ'=>'l', 'Ļ'=>'l', 'л'=>'l', 'Ľ'=>'l', 'ľ'=>'l', 'ל'=>'l',
           'מ'=>'m', 'М'=>'m', 'ם'=>'m', 'м'=>'m',
           'ñ'=>'n', 'н'=>'n', 'Ņ'=>'n', 'ן'=>'n', 'ŋ'=>'n', 'נ'=>'n', 'Н'=>'n', 'ń'=>'n', 'Ŋ'=>'n', 'ņ'=>'n', 'ŉ'=>'n', 'Ň'=>'n', 'ň'=>'n',
           'о'=>'o', 'О'=>'o', 'ő'=>'o', 'õ'=>'o', 'ô'=>'o', 'Ő'=>'o', 'ŏ'=>'o', 'Ŏ'=>'o', 'Ō'=>'o', 'ō'=>'o', 'ø'=>'o', 'ǿ'=>'o', 'ǒ'=>'o', 'ò'=>'o', 'Ǿ'=>'o', 'Ǒ'=>'o', 'ơ'=>'o', 'ó'=>'o', 'Ơ'=>'o', 'œ'=>'oe', 'Œ'=>'oe', 'ö'=>'oe',
           'פ'=>'p', 'ף'=>'p', 'п'=>'p', 'П'=>'p',
           'ק'=>'q',
           'ŕ'=>'r', 'ř'=>'r', 'Ř'=>'r', 'ŗ'=>'r', 'Ŗ'=>'r', 'ר'=>'r', 'Ŕ'=>'r', 'Р'=>'r', 'р'=>'r',
           'ș'=>'s', 'с'=>'s', 'Ŝ'=>'s', 'š'=>'s', 'ś'=>'s', 'ס'=>'s', 'ş'=>'s', 'С'=>'s', 'ŝ'=>'s', 'Щ'=>'sch', 'щ'=>'sch', 'ш'=>'sh', 'Ш'=>'sh', 'ß'=>'ss',
           'т'=>'t', 'ט'=>'t', 'ŧ'=>'t', 'ת'=>'t', 'ť'=>'t', 'ţ'=>'t', 'Ţ'=>'t', 'Т'=>'t', 'ț'=>'t', 'Ŧ'=>'t', 'Ť'=>'t', '™'=>'tm',
           'ū'=>'u', 'у'=>'u', 'Ũ'=>'u', 'ũ'=>'u', 'Ư'=>'u', 'ư'=>'u', 'Ū'=>'u', 'Ǔ'=>'u', 'ų'=>'u', 'Ų'=>'u', 'ŭ'=>'u', 'Ŭ'=>'u', 'Ů'=>'u', 'ů'=>'u', 'ű'=>'u', 'Ű'=>'u', 'Ǖ'=>'u', 'ǔ'=>'u', 'Ǜ'=>'u', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'У'=>'u', 'ǚ'=>'u', 'ǜ'=>'u', 'Ǚ'=>'u', 'Ǘ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ü'=>'ue',
           'в'=>'v', 'ו'=>'v', 'В'=>'v',
           'ש'=>'w', 'ŵ'=>'w', 'Ŵ'=>'w',
           'ы'=>'y', 'ŷ'=>'y', 'ý'=>'y', 'ÿ'=>'y', 'Ÿ'=>'y', 'Ŷ'=>'y',
           'Ы'=>'y', 'ž'=>'z', 'З'=>'z', 'з'=>'z', 'ź'=>'z', 'ז'=>'z', 'ż'=>'z', 'ſ'=>'z', 'Ж'=>'zh', 'ж'=>'zh'
       );
       return strtr($s, $replace);
   }
   
   public function set_session($user_id) {
       // Primero registramos el login, asi aparece reflejado al obtener los datos del usuario despues
        $this->user_model->register_login($user_id, $this->get_client_ip());

        // Obtenemos los datos del usuario para almacenarlos en la sesion
        $userdata = $this->user_model->get($user_id);
        $this->load->model('picture_model');
        // Obtenemos la foto del usuario en sus distintos tamaños
        $profile_pics = $this->picture_model->get_by_user($user_id);

        $_SESSION['user_id'] = $userdata->user_id;
        $_SESSION['username'] = $userdata->username;
        $_SESSION['user_email'] = $userdata->email;
        $_SESSION['user_realname'] = $userdata->name;
        $_SESSION['user_birthday'] = $userdata->birthday;
        $_SESSION['user_title'] = $userdata->title;
        $_SESSION['user_country_id'] = $userdata->country;
        $_SESSION['user_country'] = $userdata->country_name;
        $_SESSION['user_about'] = $userdata->about;
        $_SESSION['user_last_login_ip'] = $userdata->last_login_ip;
        $_SESSION['user_last_login_time'] = $userdata->last_login_time;
        $_SESSION['img_small'] = $this->format_img_src($profile_pics[0]['img_src']);
        $_SESSION['img_medium'] = $this->format_img_src($profile_pics[1]['img_src']);
        $_SESSION['img_large'] = $this->format_img_src($profile_pics[2]['img_src']);
        $_SESSION['loggedIn'] = true;
        session_write_close();
   }
   
   private function checkPersistentSession() {
       $this->load->helper('cookie');
       
       if (get_cookie('gf_keeplogged') && !$this->session->loggedIn) {
           $data = get_cookie('gf_keeplogged');
           $credentials = explode('___', $data);
           
           if (empty(trim($data)) || count($credentials) !== 2) {
               redirect('portal/home');
           } else {
               $identifier = $credentials[0];
               $token = sha1($credentials[1]);
               
               $this->load->model('account_model');
               
               $user = $this->user_model->get_persistent_session($identifier);
               
               if ($user) {
                   if ($user->keeplogged_token == $token) {
                       $this->set_session($user->user_id);
                   } else {
                       $this->user_model->remove_persistent_session($user->user_id);
                   }
               }
           }
       }
   }
}
