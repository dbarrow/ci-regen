<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * Rest Suite - Rest Controller
 *
 * Provides a extendable controller to create generic CRUD Web Services.
 * Extended from Phil Sturgeons CI Rest Controller.
 *
 * @package   Rest Suite
 * @author    Darryl Barrow
 * @copyright Copyright (c) 2013, Traversepoint
 * @license   
 * @link      http://www.traversepoint.com
 * @since     Version 1.0
 * @filesource
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
		header('Access-Control-Allow-Origin: *');	
		header('Content-type: *');
	}

	//--------------------------------------------------------------------

	/**
	*	Method: index_get()
	*
	*	get records.
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
					$this->response($records);  //success
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
		Method: index_post()

		Create new record.
	*/
	public function index_post()
	{
		$post = $this->post();
		if(!$post)
		{
			$post = json_decode($this->request->body());
		}
		
			//$post = json_decode(file_get_contents("php://input"), true);
		

		if ($post) 
		{
			$success = $this->{$this->model}->insert($post);

			if($success)
			{
				$this->response(array('status' => true), 200); //success
			}    
			else

			{
				$this->response(array('status' => false, 'error' => "Unknown error occured"), 402); //error
			}    	
		} 

		else 
		{
       		$this->response(array('status' => false, 'error' => 'Record not inserted'), 401); //no post values
		}
	}

	//--------------------------------------------------------------------

	/*
		Method: index_put()

		Update record.
	*/

	public function index_put() 
	{
	  	$query_string = $_SERVER['QUERY_STRING']; 
	  	$put = $this->put();                      		

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
				$this->response(array('status' => false, 'error' => 'No PUT values'), 402);  //no put values
			}			
		}		

		//*********************** Update by id ***********************

		if($this->uri->segment('3')!='') // id is in url eg - api/books/54
		{
			$id  = $this->uri->segment('3');

		  	if(!$record = $this->{$this->model}->get($id)) //no record in db to update
		  	{
		  		$this->response(array('status' => false, 'error' => 'Record does not exist'), 402); 
		  	}

		  	if($put)  //url contains query string. eg - api/books/?title=moby dick$hardback=true
		  	{
		  		if($this->{$this->model}->update($id,  $put))
		  		{
		  			$this->response(array('status' => true), 200);  //success
		  		}
			}

			else 
			{
				$this->response(array('status' => false, 'error' => 'No PUT values'), 402);  //no put values
			}
		}

		else  //no id in url - Error
		{
		  	$this->response(array('status' => false, 'error' => 'No id in url'), 402); 
		}
	}

	//--------------------------------------------------------------------

	/*
		Method: index_delete()

		Delete record.
	*/
	public function index_delete()
	{		  	
		$query_string = $_SERVER['QUERY_STRING']; 

		if($query_string)  //is there a query string
		{			
			parse_str($query_string, $where);                   //convert query_string to associative array

			if($this->{$this->model}->delete_by($where))  //update 
			{
				$this->response(array('status' => true), 200);  //success
			}			

			else
			{
				$this->response(array('status' => false, 'error' => 'No PUT values'), 402);  //no put values
			}			
		}		

	  	if($this->uri->segment('3')!='')  //is there an id?
		{
		  	$id = $this->uri->segment('3');
			$success = $this->{$this->model}->delete($id) ;

			if($success)
			{
				$this->response(array('status' => true));  //success
			}

			else
			{
				$this->response(array('status' => false, 'error' => 'Record not deleted or does not exist'), 402);  //Record not deleted
			}
        }

        else
        {
        	$this->response(array('status' => false, 'error' => 'No id in url'), 402); 
        }
	}	
}



  
