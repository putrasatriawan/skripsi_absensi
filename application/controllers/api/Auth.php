<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

 
class Auth extends REST_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('user_model');
        $this->load->model("ion_auth_model");   

    }

    public function login_post()
    { 
        $email   = $this->post('email');
        // $username   = $this->post('phone');
        $password   = $this->post('password');
        $fcm_token  = $this->post('fcm_token');
        $udid       = $this->post('udid');
        $info_hp    = $this->post('info_hp');
        $isAuthenticated = $this->ion_auth_model->login($email, $password);
        $data_users = $this->ion_auth->user()->row(); 
        $data = array();
        $token = substr(_hash($email . time()), 0, config_item('rest_key_length'));
        if (!empty($data_users)) {
            if ($data_users->is_deleted == 0) { 

                $isHasToken = $this->ion_auth_model->checkToken($data_users->id);
                if (!$isHasToken) {
                    $dataToken = array(
                        "key" => $token,
                        "user_id" => $data_users->id
                    );
                    $insertToken = $this->ion_auth_model->addToken($dataToken);
                } else {
                    $dataToken = array(
                        "key" => $token
                    );
                    $insertToken = $this->ion_auth_model->updateToken($dataToken, array("user_id" => $data_users->id));
                }
                if ($data_users->last_login==NULL) {
                  $update_last_login=$this->ion_auth_model->update_last_login($data_users->id);
                }
                $data_users->token = $token;
                $update_data_users = array(
                    'fcm_token' => $fcm_token,
                    'info_hp' => $info_hp
                );
                $user_groups = $this->ion_auth->get_users_groups($data_users->id)->row();
                
                $users = new stdClass();
                $users->name = $data_users->first_name; 
                $users->last_name = $data_users->last_name; 
                $users->address = $data_users->address; 
                $users->email = $data_users->email; 
                $users->phone = $data_users->phone; 
                $users->photo = $data_users->photo; 
                $users->role_id = $user_groups->id;
                $users->role_name = $user_groups->name; 

                $data_login['data_users'] = $users;
                if ($data_users->udid == null || !empty($udid)) { 
                    $update_data_users['udid'] = $udid; 
                    $this->user_model->update($update_data_users, array("id" => $data_users->id)); 
                    $data_login['data_users']->udid = $update_data_users['udid'];
                    $data_login['data_users']->fcm_token = $update_data_users['fcm_token'];
                    $data_login['data_users']->info_hp = $update_data_users['info_hp'];
                    $this->_response = [
                        'status' => TRUE,
                        'data' => $data_login,
                        'message' => "Login Berhasil"
                    ];
                    $update_last_login=$this->ion_auth_model->update_last_login($data_users->id);   
                }else{
                    if($data_users->udid != $udid) { 
                        $this->_response = [
                            'status' => false,
                            'data' => array(),
                            'message' => "User Sedang digunakan"
                        ];
                    }else{
                        $data_login['data_users']->fcm_token = $update_data_users['fcm_token'];
                        $data_login['data_users']->info_hp = $update_data_users['info_hp'];
                        $data_login = array_merge($update_data_users,$data_login);
                        $this->_response = [
                            'status' => TRUE,
                            'data' => $data_login,
                            'message' => "Login Berhasil"
                        ];
                        $update_last_login=$this->ion_auth_model->update_last_login($data_users->id);
                    } 
                }   
            } else {
                $this->_response = [
                    'status' => false,
                    'data' => array(),
                    'message' => "User Tidak Aktif"
                ];
            }
        } else {
            $this->_response = [
                'status' => false,
                'data' => array(),
                'message' => "User Tidak Ditemukan"
            ];
        }

        $this->set_response($this->_response, REST_Controller::HTTP_OK);
        
    }

    public function forgot_password_post(){
        $this->form_validation->set_rules('email','Email', 'required');
        if(!empty($this->input->post('email'))){
            if($this->user_model->getOneUserBy(['users.email'=>$this->input->post('email')])){
                $detail = $this->user_model->getOneUserBy(['users.email'=>$this->input->post('email')]);
                $time = time()+$this->config->item('forgot_password_time');
                $this->user_model->update(['users.forgotten_password_code'=>md5($this->config->item('snc_loginkey_enc').$time),'users.forgotten_password_time'=>$time],['users.email'=>$this->input->post('email')]);
                $updateddetail = $this->user_model->getOneUserBy(['users.email'=>$this->input->post('email')]);
                $link = 'login/reset_password/'.$updateddetail->forgotten_password_code;
                if($this->send_mail($detail->email,$detail->first_name.' '.$detail->last_name,$link,$updateddetail->forgotten_password_code)){
                    $this->_response['message'] ='Berhasil Mengirim Email';
                    $this->_response['status'] = true;
                    return $this->set_response($this->_response, REST_Controller::HTTP_OK);
                }else{
                    $this->_response['message'] ='Gagal Mengirim Email';
                    $this->_response['status'] = true;
                    return $this->set_response($this->_response, REST_Controller::HTTP_OK);
                }
            }else{
                $this->_response['message'] ='Email Tidak Ditemukan';
                $this->_response['status'] = true;
                return $this->set_response($this->_response, REST_Controller::HTTP_OK);
            }
        }else{
            $this->_response['message'] ='Email harus diisi';
            $this->_response['status'] = true;
            return $this->set_response($this->_response, REST_Controller::HTTP_OK);
        }
    }
    public function notif_post()
    {    
        $send = sendNotification("/topics/all","Push Notification","Lorem Epsum","1","pmku");
        if($send){
              $this->_response['status'] = TRUE;
              $this->_response['message'] = "";
              $this->_response['data'] = $send;
        }
        $this->set_response($this->_response, REST_Controller::HTTP_OK);
    }  
    private function send_mail($email,$name,$link,$code){
        date_default_timezone_set('Asia/Jakarta');
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = $this->config->item('smtp_server');
        $mail->Port = $this->config->item('smtp_port');
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Username = $this->config->item('smtp_username');
        $mail->Password = base64_decode($this->config->item('smtp_password'));
        $mail->setFrom($this->config->item('mail_noreply'), $this->config->item('mail_signature'));
        //$mail->addReplyTo('replyto@example.com', 'First Last');
        $mail->addAddress($email, $name);
        $mail->Subject = 'Reset password akun Jabar Quick Response';
        $this->data['nama'] = $name;
        $this->data['email'] = $email;
        $this->data['kode'] = $code;
        $this->data['linkreset'] = base_url($link);
        $mail->msgHTML($this->load->view('user/mail/forgot_password',$this->data,TRUE));
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }
    public function check_token_post(){
        $token = $this->input->post('token');
        $validasi = true;
         if(empty($this->input->post('token'))){
            $validasi = false;
            $data_validasi['token'] = 'token harus diisi';
        }
        if($validasi == false){
            $this->_response['message'] = $data_validasi;
            $this->_response['status'] = true;
            return $this->set_response($this->_response, REST_Controller::HTTP_OK);
        }
        $checkToken = $this->api_keys_model->getOneBy(['key'=>$token]);
        if($checkToken){
            $user = $this->user_model->getOneBy(['users.id'=>$checkToken->user_id]);
            $this->_response['message'] = 'Token ditemukan';
            $this->_response['status'] = true;
            $this->_response['data'] = $user;
            return $this->set_response($this->_response, REST_Controller::HTTP_OK);
        }else{
            $this->_response['message'] = 'Token is missmatch';
            $this->_response['status'] = true;
            $this->_response['data'] = [];
            return $this->set_response($this->_response, REST_Controller::HTTP_OK);
        }
    }
}
