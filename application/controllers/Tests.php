<?php

class Tests extends GF_Global_controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function test() {
        $this->data['view_title'] = 'Tests';
        $this->load->view('global/header', $this->data);
        $this->load->view('tests/khipu');
        $this->load->view('global/footer');
    }
    
    public function khipuCheckCobrador() {
        $receiver_id = KHIPU_ID;
        $secret = KHIPU_KEY;
        $concatenated = "receiver_id=$receiver_id";
        $hash = hash_hmac('sha256', $concatenated , $secret);

        $url = 'https://khipu.com/api/1.3/receiverStatus';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);

        $params = array('receiver_id' => $receiver_id, 'hash' => $hash);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $this->data['result'] = $output;

        $this->data['view_title'] = 'Tests';
        $this->load->view('global/header', $this->data);
        $this->load->view('tests/khipu');
        $this->load->view('global/footer');
    }
    
    public function khipuListaBancos() {
        $receiver_id = KHIPU_ID;
        $secret = KHIPU_KEY;

        $khipu_url = 'https://khipu.com/api/1.3/receiverBanks';

        $concatenated = "receiver_id=$receiver_id";

        $hash = hash_hmac('sha256', $concatenated , $secret);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $khipu_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);

        $data = array(
            'receiver_id' => $receiver_id,
            'hash' => $hash
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        
        $this->data['result'] = $output;

        $this->data['view_title'] = 'Tests';
        $this->load->view('global/header', $this->data);
        $this->load->view('tests/khipu');
        $this->load->view('global/footer');
    }
    
    public function khipuBotonPago() {
        // Llenamos los parametros
        $receiver_id = KHIPU_ID;
        $subject = 'Impresora Laser';
        $body = 'Una impresora laser con multiples bandejas de entrada';
        $amount = '100';
        $notify_url = 'http://misitio.com/notificacion';
        $return_url = 'http://misitio.com/exito';
        $cancel_url = '';
        $transaction_id = 'T-1000';
        $expires_date = time() + 30*24*60*60; //treinta dias a partir de ahora
        $payer_email = 'mi.cliente@misitio.com';
        $bank_id='';
        $picture_url = '';
        $secret = KHIPU_KEY;
        $custom = 'el modelo en color rojo';

        $khipu_url = 'https://khipu.com/api/1.3/createPaymentPage';

        // creamos el hash
        $concatenated = "receiver_id=$receiver_id&subject=$subject&body=$body&amount=$amount&payer_email=$payer_email&bank_id=$bank_id&expires_date=$expires_date&transaction_id=$transaction_id&custom=$custom&notify_url=$notify_url&return_url=$return_url&cancel_url=$cancel_url&picture_url=$picture_url";

        $hash = hash_hmac('sha256', $concatenated , $secret);
        
        $output = '<form action="'.$khipu_url.'" method="post">
        <input type="hidden" name="receiver_id" value="'. $receiver_id .'">
        <input type="hidden" name="subject" value="'. $subject .'"/>
        <input type="hidden" name="body" value="'. $body .'">
        <input type="hidden" name="amount" value="'. $amount .'">
        <input type="hidden" name="notify_url" value="'. $notify_url .'"/>
        <input type="hidden" name="return_url" value="'. $return_url .'"/>
        <input type="hidden" name="cancel_url" value="'. $cancel_url .'"/>
        <input type="hidden" name="custom" value="'. $custom .'">
        <input type="hidden" name="transaction_id" value="'. $transaction_id .'">
        <input type="hidden" name="payer_email" value="'. $payer_email .'">
        <input type="hidden" name="expires_date" value="'. $expires_date .'">
        <input type="hidden" name="bank_id" value="'. $bank_id .'">
        <input type="hidden" name="picture_url" value="'. $picture_url .'">
        <input type="hidden" name="hash" value="'. $hash .'">
        <input type="image" name="submit" src="https://s3.amazonaws.com/static.khipu.com/buttons/200x50.png">
        </form>';
        
        $this->data['result'] = $output;

        $this->data['view_title'] = 'Tests';
        $this->load->view('global/header', $this->data);
        $this->load->view('tests/khipu');
        $this->load->view('global/footer');
    }

    public function async($action) {
        echo "<h1>It works!</h1>You requested {$action}";
    }
}
