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
class Servicebuilder 
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
        $this->CI->load->dbforge();   
        $this->CI->load->helper('file');      
   
    }//end __construct

    //--------------------------------------------------------------------

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
    public function build_service($fields) 
    {
        //The name of the service        
        $service_name = $fields['service_name'];  

        $primary_key = array(
                'name' => $fields['primary_name'],
                'key' => $fields['primary_key'],
                'auto_inc' => $fields['primary_auto_inc'],
                'length' => $fields['primary_length'],
                'default' => $fields['primary_default'],
                'null' => $fields['primary_null'],
                'type' => $fields['primary_type'],
            );       

        if (!$this->build_module($service_name)) {
            return false;
        }

        if (!$this->write_files($service_name, $primary_key)) {
            return false;
        }

        if (!$this->write_db($service_name, $fields, $primary_key)) {
            return false;
        }

        return true;

    }//end build_service()

    //--------------------------------------------------------------------

    /**
     * Build from db
     *
     * Build new service from existing database table. 
     *
     * @access public
     *
     * @param array $service_name name of the service to be built.
     * 
     * @return boolean
     */
    public function build_from_db($service_name)
    {
        //get table info from db
        $fields_data = $this->CI->db->field_data($service_name);

        //find the primary key
        foreach ($fields_data as $field) 
        {
            if($field->primary_key==1)
            {
                $primary_key = $field;
            }
        }

        //build the folder structure for model
        if (!$this->build_module($service_name)) {
            return false;
        }

        //write the generic files
        if (!$this->write_files($service_name, (array)$primary_key)) {
            return false;
        }
    }

    //--------------------------------------------------------------------

    /**
     * Remove Service
     *
     * Removes a service from the system.  Includes removing database table, MVC module and 
     *
     * @access public
     *
     * @param string $service_name Contains the fields and service settings for the builder
     * 
     * @return boolean
     */
    public function remove_service($service_name)
    {
        delete_files('application/modules/' . $service_name, TRUE);
        rmdir('application/modules/' . $service_name);
        $this->CI->dbforge->drop_table($service_name);
    }//end remove_service
 
    //--------------------------------------------------------------------

    public function add_fields($service_name, $fields)
    {
        $data = $this->convert_fields_array($fields);        
        $this->CI->dbforge->add_column($service_name, $data['fields_arr']);
    }

    public function delete_field($service_name, $field)
    {
        $this->CI->dbforge->drop_column($service_name, $field);
    }

    public function get_non_service_tables($services)
    {
        //get all tables from database
        $tables = $this->CI->db->list_tables();

        //remove application tables
        $tables = array_diff($tables, array('ci_sessions', 'login_attempts', 'roles', 'services', 'user_autologin', 'user_profiles', 'users'));

        //remove tables that already have a service built
        $results = array();
        foreach($services as $service)
        {
            array_push($results,$service->name);
        }
        return array_diff($tables, $results);
    }

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
    private function build_module($service_name)
    {                  
        //          Location            Service Name   New Directory    Perm.
        mkdir("application/modules/" . $service_name                  , 0777);
        mkdir("application/modules/" . $service_name . "/models"      , 0777);
        mkdir("application/modules/" . $service_name . "/views"       , 0777);
        mkdir("application/modules/" . $service_name . "/controllers" , 0777);  
        mkdir("application/modules/" . $service_name . "/config"      , 0777);  

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
    private function write_files($service_name, $primary_key)
    {
        $data['service_name'] = $service_name;
        $data['primary_key'] = $primary_key;


        //set the template files for building model and controller and inject service name
        $controller = $this->CI->load->view('templates/default_controller.php', $data, true);
        $model      = $this->CI->load->view('templates/default_model.php', $data, true);  
        $config     = $this->CI->load->view('templates/default_config.php', $data, true); 
    
        write_file("application/modules/" . $service_name . "/controllers/" . $service_name . '.php'       , $controller);
        write_file("application/modules/" . $service_name . "/models/"      . $service_name . '_model.php' , $model); 
        write_file("application/modules/" . $service_name . "/config/config.php", $config); 


        return true;
    }//end write_files

    //--------------------------------------------------------------------

    /**
     * Write DB
     * Write the database tables
     *
     * @access private
     *
     * @param string $service_name The name of the table to be written to the database
     * @param string $fields the fields to be written to the table
     *
     * 
     * @return boolean
     */
    private function write_db($service_name, $fields, $primary_key)
    {          
        //convert fields array to acceptable input for add_field function
        $data = $this->convert_fields_array($fields);

        //add primary key to fields
        $this->CI->dbforge->add_field($primary_key['name'] . " int(" . $primary_key['length'] . ") NOT NULL AUTO_INCREMENT");

        //add remainder of fields
        $this->CI->dbforge->add_field($data['fields_arr']);

        //add Foreign keys
        foreach($data['key_arr'] as $key)
        {
            $this->CI->dbforge->add_key($key);
        }

        //add primary key
        $this->CI->dbforge->add_key($primary_key['name'], TRUE);
        $this->CI->dbforge->create_table($service_name);             
       
        return true;
    }//end write_db


    //helper function to prepare an array for ci dbforge input
    private function convert_fields_array($fields)
    {
        //get fields from post array. They must be associated and restructured below.

            $field_names    = $fields['name'];
            $field_lengths  = $fields['length'];
            $field_types    = $fields['type'];
            $field_nulls    = $fields['null'];
            $field_keys     = $fields['key'];
            $field_defaults = $fields['default'];
            $field_auto_inc = $fields['auto_inc'];

            //Associate and restructure fields array for the add field function.    

            $fields_arr = array();  //storage for rebuilt array    
            $key_arr = array();     //storage for key fields  

            $count = count($field_names);               

            for ($i = 0; $i < $count; $i++) 
            {
                $fields_arr[$field_names[$i]]['type']        = $field_types[$i];
                $fields_arr[$field_names[$i]]['constraint']  = $field_lengths[$i];
                
                if($field_nulls[$i] == 'TRUE' ? $fields_arr[$field_names[$i]]['null'] = TRUE : $fields_arr[$field_names[$i]]['null'] = FALSE);
                if($field_auto_inc[$i] == 'TRUE' ? $fields_arr[$field_names[$i]]['auto_increment'] = TRUE : $fields_arr[$field_names[$i]]['auto_increment'] = FALSE);

                if($field_defaults[$i] != "") 
                {
                    $fields_arr[$field_names[$i]]['default'] = $field_defaults[$i];
                }

                //capture foreign keys
                if($field_keys[$i] == 'FOREIGN')
                {
                    array_push($key_arr, $field_names[$i]);
                }
            }   

            //return an array of arrays for the db_forge functions.
            $data['key_arr']    = $key_arr;
            $data['fields_arr'] = $fields_arr;

            return $data;     
    }

}//end Servicebuilder
