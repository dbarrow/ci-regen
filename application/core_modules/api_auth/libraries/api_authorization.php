<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Api_Authorization
 *
 * Authorization library for API login and logout.
 *
 * @package		Api_auth
 * @author		Darryl Barrow
 * @version		0.1
 * @license		MIT License Copyright
 */
class Api_authorization
{
	function __construct()
	{
		$this->ci =& get_instance();		
		$this->ci->load->model('api_auth/api_auth_model');		
	}

	function login($user)
	{		
		$data['token'] = time();
		$this->ci->api_auth_model->insert($data);
		return $data['token'];
	}

	function logout()
	{
		$data['token'] =  $_SERVER['HTTP_API'];
		$this->ci->api_auth_model->delete_by($data);
		return null;
	}

	function authorize_token($token)
	{		
		$records = $this->ci->api_auth_model->get_by("token",$token);
		if($records)
		{
			return $token;
		}
		else
		{
			return false;
		}
	}

	function update_token($token, $new_token)
	{
		$data['token'] = $token;
		$new_data['token'] = $new_token;
		$this->ci->api_auth_model->update_by($data, $new_data);
	}

}



