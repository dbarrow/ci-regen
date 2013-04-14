<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* 
*
* An open source project to quickly generate and manage web resources
*
* @package   CI Re:Gen
* @author    Darryl Barrow 
* @copyright Copyright (c) 2013, Darryl Barrow
* @license   MIT
* @link      
* @since     Version 1.0
* @filesource
*/
/**
* Installer library
*
* This library performs file writing and database creation for installation.
*
* @package    Codeigniter Rest Kit
* @subpackage install_filebuilder
* @category   Libraries
* @author     Darryl Barrow
* @link      
*
*/
class Installer 
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
    * Setup the Constructor
    *
    * @return void
    */
    function __construct()
    {
        $this->CI = &get_instance();   
    }//end __construct

    //--------------------------------------------------------------------

    /**
    * Write the database config file.  
    *
    * @return boolean
    */
    public function build_db_config($data) 
    {        
        $this->CI->load->helper('file');
        
        $db_config = $this->CI->load->view('templates/database.php', $data, true);       //generate the database config file
        if(ENVIRONMENT == 'development')
        {
            $result = write_file('application/config/development/database.php', $db_config); //write the generated file to the config folder           
        }
        else if(ENVIRONMENT == 'production')
        {
            $result = write_file('application/config/production/database.php', $db_config); //write the generated file to the config folder
        }
        if($result) return true;
        else return false;

    }//end build_db_config()
    
    //--------------------------------------------------------------------

    /**
    * Write the tables to the database.  
    *
    * @return boolean
    */
    public function install_tables($data)
    {   
        $this->CI->load->dbforge();    
               
        // Sessions
        $this->CI->dbforge->add_field("`session_id` varchar(40) DEFAULT '0'");
        $this->CI->dbforge->add_field("`ip_address` varchar(16) NOT NULL DEFAULT '0'");
        $this->CI->dbforge->add_field("`user_agent` varchar(50) NOT NULL");
        $this->CI->dbforge->add_field("`last_activity` int(10) unsigned NOT NULL DEFAULT '0'");
        $this->CI->dbforge->add_field("`user_data` text NOT NULL");
        $this->CI->dbforge->add_key('session_id', true);
        $this->CI->dbforge->create_table('ci_sessions');

        // Login Attempts
        $this->CI->dbforge->add_field("`id` int(11) NOT NULL AUTO_INCREMENT");
        $this->CI->dbforge->add_field("`ip_address` varchar(40) NOT NULL");
        $this->CI->dbforge->add_field("`login` varchar(50) NOT NULL");
        $this->CI->dbforge->add_field("`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_key('id', true);
        $this->CI->dbforge->create_table('login_attempts');

        // Roles
        $this->CI->dbforge->add_field("`id` int(11) NOT NULL AUTO_INCREMENT");
        $this->CI->dbforge->add_field("`role` varchar(255) NOT NULL");
        $this->CI->dbforge->add_field("`default` tinyint(2) NOT NULL DEFAULT '0'");
        $this->CI->dbforge->add_key('id', true);
        $this->CI->dbforge->create_table('roles');
        $this->CI->db->query("INSERT INTO roles VALUES(1, 'admin', 0)");
        $this->CI->db->query("INSERT INTO roles VALUES(2, 'user', 1)");

        // Users
        $this->CI->dbforge->add_field("`id` int(50)  NOT NULL AUTO_INCREMENT");
        $this->CI->dbforge->add_field("`username` varchar(50) NOT NULL DEFAULT ''");
        $this->CI->dbforge->add_field("`password` varchar(255) NOT NULL");
        $this->CI->dbforge->add_field("`email` varchar(120) NOT NULL");
        $this->CI->dbforge->add_field("`role_id` int(11) NOT NULL");
        $this->CI->dbforge->add_field("`activated` tinyint(1) NOT NULL DEFAULT '1'");
        $this->CI->dbforge->add_field("`banned` tinyint(1) NOT NULL DEFAULT '0'");
        $this->CI->dbforge->add_field("`ban_reason` varchar(255) DEFAULT NULL");
        $this->CI->dbforge->add_field("`new_password_key` varchar(50) DEFAULT NULL");
        $this->CI->dbforge->add_field("`new_password_requested` datetime DEFAULT NULL");
        $this->CI->dbforge->add_field("`new_email` varchar(100) DEFAULT NULL");
        $this->CI->dbforge->add_field("`new_email_key` varchar(50) DEFAULT NULL");
        $this->CI->dbforge->add_field("`last_ip` varchar(40) NOT NULL DEFAULT ''");
        $this->CI->dbforge->add_field("`last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $this->CI->dbforge->add_field("`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $this->CI->dbforge->add_field("`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");                
        $this->CI->dbforge->add_key('id', true);
        $this->CI->dbforge->create_table('users');

         // user_autologin
        $this->CI->dbforge->add_field("`key_id` char(32) NOT NULL");
        $this->CI->dbforge->add_field("`user_id` int(11) NOT NULL  DEFAULT '0'");
        $this->CI->dbforge->add_field("`user_agent` varchar(150) NOT NULL");
        $this->CI->dbforge->add_field("`last_ip` varchar(40) NOT NULL");
        $this->CI->dbforge->add_field("`last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_key('key_id', true);
        $this->CI->dbforge->add_key('user_id');
        $this->CI->dbforge->create_table('user_autologin');

        // user_profiles
        $this->CI->dbforge->add_field("`id` int(11) NOT NULL AUTO_INCREMENT");
        $this->CI->dbforge->add_field("`user_id` int(11) NOT NULL ");
        $this->CI->dbforge->add_field("`first_name` varchar(20) DEFAULT NULL");
        $this->CI->dbforge->add_field("`last_name` varchar(20) DEFAULT NULL");
        $this->CI->dbforge->add_field("`country` varchar(20) DEFAULT NULL");
        $this->CI->dbforge->add_field("`website` varchar(255) DEFAULT NULL");
        $this->CI->dbforge->add_field("`street_1` varchar(255) DEFAULT NULL");
        $this->CI->dbforge->add_field("`street_2` varchar(255) DEFAULT NULL");
        $this->CI->dbforge->add_field("`city` varchar(40) DEFAULT NULL");
        $this->CI->dbforge->add_field("`state_id` int(11) DEFAULT NULL");
        $this->CI->dbforge->add_field("`zipcode` int(7) DEFAULT NULL");
        $this->CI->dbforge->add_field("`zip_extra` int(5) DEFAULT NULL");
        $this->CI->dbforge->add_field("`country_id` int(11) DEFAULT NULL");
        $this->CI->dbforge->add_key('id', true);
        $this->CI->dbforge->create_table('user_profiles');
        $this->CI->load->library('auth/tank_auth');
        $this->CI->tank_auth->create_user($data['user_user'],$data['email'], $data['user_password'], 0);

        //Services 
        $this->CI->dbforge->add_field("`id` int(10) NOT NULL AUTO_INCREMENT");
        $this->CI->dbforge->add_field("`name` varchar(50) NOT NULL");
        $this->CI->dbforge->add_field("`enabled` tinyint(1) DEFAULT '1'");     
        $this->CI->dbforge->add_field("`authorization` tinyint(1) DEFAULT '0'");          
        $this->CI->dbforge->add_key('id', true);
        $this->CI->dbforge->create_table('services');
        $this->CI->db->query("INSERT INTO services VALUES(1, 'users', 1)");
        $this->CI->db->query("INSERT INTO services VALUES(2, 'user_profiles', 1)");

        return true;
    }//end install_tables

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // PRIVATE METHODS
    //--------------------------------------------------------------------

    //--------------------------------------------------------------------

}//end Installer


