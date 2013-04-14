<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');require APPPATH . '/libraries/RS_REST_Controller.php';

class api_auth extends RS_REST_Controller {

	protected $model = 'api_auth_model';	

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
		$this->load->library('auth/tank_auth');		
		$this->load->library('api_authorization');
	}
	//--------------------------------------------------------------------

	public function login_post()
	{
		$post = $this->post();

		//if no post data, check for json encoded data		
		if(!$post)
		{
			$post = json_decode(file_get_contents("php://input"), true);
		}

		if ($post) 
		{			
			$result = $this->tank_auth->login($post['email'], $post['password'], true, true, true);	 //use tank_auth to validate user
			if($result)  //if tank_auth validates
			{
				$token = $this->api_authorization->login($post['email']);  //login in to api
				header('API:' . $token );  //set api token in response header
				$this->response(200);  //success
			}
			else
			{
	       		$this->response(array('status' => false, 'error' => 'Invalid Credentials'), 401);  //no post values
			}
		} 

		else 
		{
       		$this->response(array('status' => false, 'error' => 'No data to validate'), 401);  //no post values
		}
	}

	public function logout_get()
	{
		$this->tank_auth->logout();
		$token = $this->api_authorization->logout();
		header('API:' . null);
		$this->response(array('status' => true, 'api' => $token), 200); //success
	}
} 






