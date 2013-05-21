<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Applications extends AuthController {

    protected $template = "includes/template";

    function __construct()
    {
        parent::__construct();
        $this->load->library('ng_builder');
        $this->load->model('services/services_model');
    }

    public function index()
    {        
        $data = null;
        $data['services'] = $this->services_model->get_all();    
        $data['main_content'] = $this->load->view("index", $data, true);        
        $this->load->view($this->template, $data);
    }

    public function build_ng_module($module)
    {     
        $out = array();
        $this->ng_builder->build($module);
        exec('ls', $out);
        echo $out;
    }   

  

}

/* End of file install.php */
/* Location: ./application/controllers/welcome.php */

