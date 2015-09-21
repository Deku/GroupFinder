<?php

class Payments extends GF_Global_controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('payments_model');
    }
    
    public function checkout() {
        $this->requires_login();
        
        $rid = $this->input->post('reward-id');
        $this->load->model('project_model');
        $reward = $this->project_model->getReward($rid);
        
        $pid = $this->input->post('project-id');
        $project_title = $this->project_model->getProjectTitle($pid);
        
        $pledge_amount = $this->input->post('pledge-amount');
        $backer_name = $this->input->post('backer-name');
        $backer_email = $this->input->post('backer-email');
        $payment_method = $this->input->post('payment-method');
        
        if ($payment_method == 'khipu') {
            // Llenamos los parametros
            $receiver_id = KHIPU_ID;
            $subject = 'Aporte a proyecto: '.$project_title;
            $body = isset($reward) ? $reward->description : 'Aporte voluntario sin recompensa';
            $amount = $pledge_amount;
            $notify_url = 'http://misitio.com/notificacion';
            $return_url = 'http://misitio.com/exito';
            $cancel_url = '';
            $transaction_id = 'T-1000';
            $payer_email = $backer_email;
            $bank_id = 'Bawdf';
            $expires_date = time() + 30*24*60*60; //treinta dias a partir de ahora
            $picture_url = '';
            $secret = KHIPU_KEY;
            $custom = ' ';

            $khipu_url = 'https://khipu.com/api/1.3/createPaymentURL';

            $concatenated = "receiver_id=$receiver_id&subject=$subject&body=$body&amount=$amount&payer_email=$payer_email&bank_id=$bank_id&expires_date=$expires_date&transaction_id=$transaction_id&custom=$custom&notify_url=$notify_url&return_url=$return_url&cancel_url=$cancel_url&picture_url=$picture_url";

            $hash = hash_hmac('sha256', $concatenated , $secret);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $khipu_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, true);

            $data = array(
                'receiver_id' => $receiver_id,
                'subject' => $subject,
                'body' => $body,
                'amount' => $amount,
                'notify_url' => $notify_url,
                'return_url' => $return_url,
                'cancel_url' => $cancel_url,
                'transaction_id' => $transaction_id,
                'payer_email' => $payer_email,
                'expires_date' => $expires_date,
                'bank_id' => $bank_id,
                'picture_url' => $picture_url,
                'custom' => $custom,
                'hash' => $hash
            );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);

            $output = json_decode($output);
            log_message('debug', print_r($output, true));
            redirect($output->url);
        } else {
            
        }
    }
}
