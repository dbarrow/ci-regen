<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class install extends MX_Controller {

    protected $template = "includes/template";  //set the template for this controller

    function __construct()
    {
        parent::__construct();
        $this->load->library('installer');
        $this->load->library('applications/ng_builder');

        $this->load->helper('file');
        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;
    }

    public function index()
    {       
        if(read_file('application/core_modules/install/installed.txt') != NULL)  //if file exists, already installed.
        {
          redirect('services');
        }
        else
        {
            $data['main_content'] = $this->load->view("install/install", '', true);
            $this->load->view($this->template, $data);
        }
    }

    public function step1()
    {        
        $this->form_validation->set_rules('hostname', 'Hostname', 'trim|required');
        $this->form_validation->set_rules('port', 'Port', 'trim|required');
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim');
        $this->form_validation->set_rules('database', 'Database name', 'trim|requiered');

        $this->form_validation->set_rules('user_user', 'User', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|email');
        $this->form_validation->set_rules('user_password', 'Password', 'trim|required|matches[password_conf]');
        $this->form_validation->set_rules('password_conf', 'Password Confirmation', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            $data['main_content'] = $this->load->view("install", '', true);
            $this->load->view($this->template, $data);
        }

        else
        {
            $data['host']             = $this->input->post('hostname');
            $data['port']             = $this->input->post('port');
            $data['username']         = $this->input->post('username');
            $data['password']         = $this->input->post('password');
            $data['database']         = $this->input->post('database');
            $data['email']            = $this->input->post('email');
            $data['user_user']        = $this->input->post('user_user');
            $data['user_password']    = $this->input->post('user_password');
            $data['driver']           = $this->input->post('driver');
            
            //Run Installer Library functions
            $data['db_installed']     = $this->installer->build_db_config($data);        //build database config file 
            $data['tables_installed'] = $this->installer->install_tables($data);    //install tables  

            $vars = null;
            $loginservice    = $this->load->view('templates/default_loginservice.php', $vars, true);  
            write_file("../app/js/services/loginservice.js" , $loginservice);

            if($data['db_installed'] && $data['tables_installed'])  //if installation is successful, write install.txt to install module
            {
                write_file('application/core_modules/install/installed.txt',"installed");   
            }
            
            $data['main_content'] = $this->load->view("install/complete", $data, true);
            $this->load->view($this->template, $data);
        }                 
    }  
}//end 

