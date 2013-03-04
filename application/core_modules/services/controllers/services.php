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
            $fields = $this->input->post();                     
            $this->servicebuilder->build_service($fields);

            $data['name'] = $this->input->post('service_name');
            $data['enabled'] = true;   
            $this->services_model->insert($data);
            redirect('services');
        }
        else
        {            
            $data['main_content'] = $this->load->view("services/new_service", '', true);
            $this->load->view($this->template, $data);
        }
    }   

    public function from_database()
    {     
        if ($this->input->post('submit'))
        {  
            $table = $this->input->post('table');              
            $this->servicebuilder->build_from_db($table);

            $data['name'] = $this->input->post('table');
            $data['enabled'] = true;   
            $this->services_model->insert($data);

            redirect('services');
        }
        else
        {        
            $services = $this->services_model->select_name()->get_all();
            $tables = $this->servicebuilder->get_non_service_tables($services);  
           
            $options = array();    
            foreach ($tables as $table)
            {
                $options[$table] = $table;  
            }

            $data['tables'] = $options;
            $data['main_content'] = $this->load->view("services/from_database", $data, true);
            $this->load->view($this->template, $data);
        }
    }   

    public function remove_service($service_name)
    {
        $this->servicebuilder->remove_service($service_name);
        $where = array('name'=>$service_name);
        $this->services_model->delete_by($where);

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

