<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Rest Suite - Rest Controller
 *
 * Provides a extendable controller to create generic CRUD Web Services.
 * Extended from Phil Sturgeons CI Rest Controller.
 *
 * @package   Rest Suite
 * @author    Darryl Barrow
 * @copyright Copyright (c) 2013, Traversepoint
 * @license   MIT
 * @link      http://www.traversepoint.com
 * @since     Version 1.0
 *
 */
require APPPATH . '/libraries/REST_Controller.php';

class RS_REST_Controller extends REST_Controller
{
	/**
	 * Stores the model name for the api.
	 *
	 * @var string
	 */
	protected $model = NULL;

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
		$this->load->model($this->model, null, true);

		//**********************************Headers for CORS(Cross-Origin-Resourse-Sharing)***********************
		header('Access-Control-Allow-Origin: *');	
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');	
		header('Access-Control-Allow-Headers: Content-Type, API');  //allowed headers
		header('Access-Control-Expose-Headers: API');  //expose custom headers cross domain

		//*********************************************End Headers************************************************

		//****************************************Authorization Check*********************************************
		$this->load->model('services/services_model');
		$this->db->select('authorization');
		//get service name from $this->model
		$service_name = explode( '_', $this->model);
       	$auth = $this->services_model->get_by('name', $service_name[0]);

       	if( $_SERVER['HTTP_REQUEST_METHOD'] == 'OPTIONS')
		{
			$this->response(200);  //Not authorized
		}

		if($auth->authorization)
		{
			$this->load->library('api_auth/api_authorization');
			$token = $_SERVER['HTTP_API'];

			if (!$this->api_authorization->authorize_token($token)) 
			{
    			$this->response(array('status' => $token, 'error' => 'Not Authorized - Login in'), 401);  //Not authorized
    		}	
    		
	    	$new_token = time();
	    	$this->api_authorization->update_token($token, $new_token);	
	    	header('API:' . $new_token);		
    	}
    	//****************************************End Authorization Check******************************************
	}

	//--------------------------------------------------------------------

	/**
	*	Method: index_get()
	*
	*	Get record(s).  
	*/
	public function index_get()
	{		
		$id = $this->uri->segment('3');
		$get = $this->input->get(); //check for query strings
		
		if($logged=true)  //mock for future authentication features
		{
			if($get)  //query string present
			{	
				$records = $this->{$this->model}->get_many_by($get);
				if($records)
				{
					$this->response($records, 200);  //success
				}
				else
				{
	            	$this->response(array('status' => false, 'error' => 'No records found'), 404); //No records found	            
				}
			}

			else if($id) //request for record for given id
	        {	        	
	        	$record = $this->{$this->model}->get($id);

	        	if ($record) 
	        	{
	        		$this->response($record);  //success
	        	}
	        	else
	        	{
	        		$this->response(array('status' => false, 'error' => 'Record not found'), 404);  //No record given id
	        	}
	        }

			else  //request for all records
			{
				$records = $this->{$this->model}->get_all();

				if ($records)
				{
	                $this->response($records); // success
	            }   
	            else 
	            {
	            	$this->response(array('status' => false, 'error' => 'No records found'), 404); //No records found
	            }
	        }
	    }

    	else
    	{
    		$this->response(array('status' => false, 'error' => 'Not Authorized'), 401);  //Not authorized
    	}
	}

	//--------------------------------------------------------------------

	/*
	*	Method: index_post()
	*
	*	Create new record.
	*/
	public function index_post()
	{
		//collect post information
		$post = $this->post();

		//if no post data, check for json encoded data		
		if(!$post)
		{
			$post = json_decode(file_get_contents("php://input"), true);
		}
		
		if ($post) 
		{
			$success = $this->{$this->model}->insert($post);

			if($success)
			{
				$this->response(array('status' => true), 200); //success
			}    
			else

			{
				$this->response(array('status' => false, 'error' => "Unknown error occured"), 400); //error
			}    	
		} 

		else 
		{
       		$this->response(array('status' => false, 'error' => 'Record not inserted'), 401); //no post values
		}
	}

	//--------------------------------------------------------------------

	/*
	*	Method: index_put()
	*
	*	Update record(s).
	*/
	public function index_put() 
	{
	  	//collect query string data from url
	  	$query_string = $_SERVER['QUERY_STRING']; 

	  	//collect post information
	  	$put = $this->put();

	  	//if no put data, check body for json encoded data (used by angular.js, maybe others...)     
	  	if(!$put)
		{
			$put = json_decode(file_get_contents("php://input"), true);
		}               		

	  	//*********************** Update Where ************************

	  	if($query_string)  //is there a query string
		{
			if($put)  //there needs to be _PUT values in order to update
			{
				parse_str($query_string, $where);                   //convert query_string to associative array
				if($this->{$this->model}->update_by($where, $put))  //update 
				{
					$this->response(array('status' => true), 200);  //success
				}
			}

			else
			{
				$this->response(array('status' => false, 'error' => 'No PUT values'), 400);  //no put values
			}			
		}		

		//*********************** Update by id ***********************

		if($this->uri->segment('3')!='') // id is in url eg - api/books/54
		{
			$id  = $this->uri->segment('3');

		  	if(!$record = $this->{$this->model}->get($id)) //no record in db to update
		  	{
		  		$this->response(array('status' => false, 'error' => 'Record does not exist'), 400); 
		  	}

		  	if($put)  //url contains query string. eg - api/books/?title=moby dick&hardback=true
		  	{
		  		if($this->{$this->model}->update($id,  $put))
		  		{
		  			$this->response(array('status' => true), 200);  //success
		  		}
			}

			else 
			{
				$this->response(array('status' => false, 'error' => 'No PUT values'), 400);  //no put values
			}
		}

		else  //no id in url - Error
		{
		  	$this->response(array('status' => false, 'error' => 'No id in url'), 400); 
		}
	}

	//--------------------------------------------------------------------

	/*
	* 	Method: index_delete()
    *
    *	Delete record(s).
	*/
	public function index_delete()
	{		  	
		//collect query string data from url
		$query_string = $_SERVER['QUERY_STRING']; 

		if($query_string)  //is there a query string
		{			
			parse_str($query_string, $where);  //convert query_string to associative array

			if($this->{$this->model}->delete_by($where))  //update 
			{
				$this->response(array('status' => true), 200);  //success
			}			

			else
			{
				$this->response(array('status' => false, 'error' => 'No PUT values'), 400);  //no put values
			}			
		}		

	  	if($this->uri->segment('3')!='')  //is there an id?
		{
		  	$id = $this->uri->segment('3');
			$success = $this->{$this->model}->delete($id) ;

			if($success)
			{
				$this->response(array('status' => true), 200);  //success
			}

			else
			{
				$this->response(array('status' => false, 'error' => 'Record not deleted or does not exist'), 400);  //Record not deleted
			}
        }

        else
        {
        	$this->response(array('status' => false, 'error' => 'No id in url'), 400); 
        }
	}	
}



  
