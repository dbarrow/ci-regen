<?php
class MY_Controller extends MX_Controller
{
	public $agent;

    function __construct()
    {
        parent::__construct();  
    }
}

class AuthController extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('auth/tank_auth');
        $this->is_logged_in();  
    }

    function is_logged_in()
    {        
        if (!$this->tank_auth->is_logged_in()) {
        redirect('/auth/login/');
        }
    }
}
?>
