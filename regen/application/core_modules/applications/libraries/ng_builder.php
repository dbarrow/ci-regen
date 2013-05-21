<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a file-level DocBlock
 * 
 * No warning will be raised.  This is the recommended usage
 * @package SomePackage
 */
/**
 * Service Builder library
 *
 * This library installs a new service.  It builds the folder structure, write controller and model files and generates db tables.
 * 
 * @category   Libraries
 * @author     Darryl Barrow
 * @link      
 *
 */
class NG_Builder 
{
    /**
     * A pointer to the CodeIgniter instance.
     *
     * @access public
     *
     * @var object
     */    
    public $CI;

    //--------------------------------------------------------------------

    /**
     * Constructor
     *
     * Loads an instance of CI.  Loads the CI dbforge class.  Loads the file helper.
     * @return void
     */
    function __construct()
    {
        $this->CI = &get_instance();  
        $this->CI->load->helper('file');  

    }//end __construct

    //--------------------------------------------------------------------
    public function initialize_app()
    {
         if (!$this->write_initial_files()) {
            return false;
        }
        return true;
    }
    /**
     * Build Service
     *
     * Build new service. 
     *
     * @access public
     *
     * @param array $fields Contains the fields and service settings for the builder
     * 
     * @return boolean
     */
    public function build($module) 
    {
        if (!$this->build_module($module)) {
            return false;
        }

        if (!$this->write_files($module)) {
            return false;
        }      

        return true;

    }//end build_service()

    //--------------------------------------------------------------------

       
    //--------------------------------------------------------------------
    // PRIVATE METHODS
    //--------------------------------------------------------------------

    /**
     *Build Module 
     *Write the files for the module to the server
     *
     * @access private
     *
     * @param string $service_name The name of the service
     * 
     * @return boolean
     */
    private function build_module($module_name)
    {                  
        //          Location            Service Name   New Directory    Perm.
        mkdir("../app/modules/" . $module_name                  , 0777);
        mkdir("../app/modules/" . $module_name . "/services"    , 0777);
        mkdir("../app/modules/" . $module_name . "/views"       , 0777);
        mkdir("../app/modules/" . $module_name . "/controllers" , 0777);     
        return true;
    }//end build_module

    //--------------------------------------------------------------------

    /**
     * Write Files
     * Write the files for the module to the server
     *
     * @access private
     *
     * @param string $service_name The name of the service
     * 
     * @return boolean
     */
    private function write_files($module)
    {
        $inflector = $this->CI->load->library('inflector');
        $this->CI->load->model('services/services_model');

        $data['lc_plural'] = $module;
        $data['uc_plural'] = ucfirst($module);        
        $data['lc_singular'] = $inflector->singularize($module);
        $data['uc_singular'] = ucfirst($data['lc_singular']);
        $data['fields'] = $this->CI->db->field_data($module);



        //set the template files for building model and controller and inject service name

        $controller = $this->CI->load->view('templates/default_controller.php', $data, true);
        $single_controller = $this->CI->load->view('templates/default_single_controller.php', $data, true);
        $service    = $this->CI->load->view('templates/default_service.php', $data, true);  
        $config     = $this->CI->load->view('templates/default_config.php', $data, true); 
        $list_view  = $this->CI->load->view('templates/default_list_view.php', $data, true); 
        $single_view  = $this->CI->load->view('templates/default_single_view.php', $data, true); 
        $new_view = $this->CI->load->view('templates/default_new_view.php', $data, true); 
    
        write_file("../app/modules/" . $module . "/controllers/" . $module  . 'ListController.js' , $controller);
        write_file("../app/modules/" . $module . "/controllers/" . $module  . 'ViewController.js' , $single_controller);
        write_file("../app/modules/" . $module . "/services/"    . $module  . 'Service.js'        , $service); 
        write_file("../app/modules/" . $module . "/config.js"                                     , $config); 
        write_file("../app/modules/" . $module . "/views/"       .  $data['lc_singular'] . ".html"   , $single_view); 
        write_file("../app/modules/" . $module . "/views/"       .  $data['lc_plural'] . ".html"   , $list_view); 
        write_file("../app/modules/" . $module . "/views/new-"       .  $data['lc_singular'] . ".html"   , $new_view); 


        return true;
    }//end write_files

    private function write_initial_files()
    {
        $data = null;
        $loginservice    = $this->CI->load->view('applications/views/templates/default_loginservice.php', $data, true);  

         write_file("../app/js/services/loginservice.js" , $loginservice);
    }

    

}//end Servicebuilder
