<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');require APPPATH . '/libraries/RS_REST_Controller.php';

class users extends RS_REST_Controller {

	protected $model		= 'users_model';	

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
	}

	public function with_profiles_get()
	{
		$this->load->model('user_profiles/user_profiles_model');
		$this->db->join('user_profiles', 'users.id = user_id');
		$result = $this->users_model->get_all();
		$this->response($result);
	}

	//--------------------------------------------------------------------

} 






