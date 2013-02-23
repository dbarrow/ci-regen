<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends AuthController {

    protected $template = "includes/template";

    function __construct()
    {
        parent::__construct();
        $this->load->library('servicebuilder');
        $this->load->model('services_model');
    }

    public function index()
    {        
        $data['services'] = $this->services_model->get_all();    
        $data['main_content'] = $this->load->view("services/index", $data, true);
        $this->load->view($this->template, $data);
    }

    public function service($service_name)
    {
        $this->load->model('services_model');
        $this->load->model($service_name . "/" . $service_name . "_model");  
        $data['service_name'] = $service_name;    
        $data['fields_data'] = $this->db->field_data($service_name);
        $data['service'] = $this->{$service_name . "_model"} ->get_all();   
        $data['main_content'] = $this->load->view("services/service", $data, true);
        $this->load->view($this->template, $data);
    }

    public function new_service()
    {     
        if ($this->input->post('submit'))
        {  
            $this->load->model('services_model');
            $fields = $this->input->post();
            $data['name'] = $this->input->post('service_name');
            $data['enabled'] = true;
            $this->services_model->insert($data);
            $this->servicebuilder->build_service($fields);
            redirect('services');
        }
        else
        {            
            $data['main_content'] = $this->load->view("services/new_service", '', true);
            $this->load->view($this->template, $data);
        }
    }   

    public function remove_service($service_name)
    {
        $where = array('name'=>$service_name);
        $this->load->model('services/services_model');
        $this->services_model->delete_by($where);
        $this->servicebuilder->remove_service($service_name);

        redirect('services');
    }

    public function add_fields($service_name)
    {
        if ($this->input->post('submit'))
        {
           $fields = $this->input->post(); 
            $this->servicebuilder->add_fields($service_name, $fields);
            redirect("services/service/" . $service_name);
        }

        $data['main_content'] = $this->load->view("services/add_fields", '', true);
        $this->load->view($this->template, $data);
    }

    public function remove_field($service_name, $field_name)
    {     
        $this->servicebuilder->delete_field($service_name, $field_name);
        redirect("services/service/" . $service_name);
    }

}

/* End of file install.php */
/* Location: ./application/controllers/welcome.php */

