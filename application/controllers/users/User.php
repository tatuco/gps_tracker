<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user/user_model');
        $this->load->helper('my_table_helper');
        $this->data['photo'] = 'web\pics\users\\'.$this->session->userdata('email').'.jpg';
    }
    
    public function register()
    {   
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('phone_number', 'Phone number', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[16]|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password', 'Confirm password', 'trim|required');
            
            if ($this->form_validation->run())
            {
                if ($this->user_model->save_user_form_on_first_login() == false)
                {
                    $this->data['message'] = 'Form has not been saved.';
                }
                else
                {
                    $this->save_photo();
                    if ($this->session->userdata('phone') == 1)
                    {
                        redirect('main/phone');
                    }
                    else 
                    {
                        redirect('main');
                    }
                }
            }
        }
        $this->data['header_title'] = 'Register';
        $this->load->view('templates/main/header', $this->data);
        $this->load->view('user/register', $this->data);
        $this->load->view('templates/main/footer');
    }

    private function save_photo()
    {
        $config['upload_path']   = FCPATH.'web\pics\users\\';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = '300';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload() == false)
        {
            $this->data['message'] .= 'Ignore the error if you did not want to upload photo.'
                                    . $this->upload->display_errors();
            return false;
        }
        else
        {
            $upload_data = $this->upload->data();
            $file = $upload_data['full_path'];
            rename($file, FCPATH.$this->data['photo']);
            $this->data['message'] .= 'Photo has been successfully uploaded. ';
            return true;
        }
    }
    
    public function change_user_settings()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('first_name', 'First name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last name', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('phone_number', 'Phone number', 'required');
            
            if ($this->form_validation->run())
            {
                if ($this->user_model->save_user_settings_form())
                {
                    $this->data['message'] .= 'Form has been succesfully saved. ';
                    $this->save_photo();
                }
                else
                {
                    $this->data['message'] .= 'Something went wrong. '
                                           . 'The username or email may address already exists. '
                                           . 'Please try another username and/or email address. ';
                }
            }
            $this->send_messages();
            return;
        }
        $this->data['records'] = $this->user_model->get_user($this->session->userdata('id'));
        $this->load->view('user/user_settings', $this->data);
    }
}