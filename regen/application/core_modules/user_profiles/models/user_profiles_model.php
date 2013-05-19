<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_profiles_model extends MY_Model {

	protected $_table		  = 'user_profiles';
	protected $primary_key    = 'id';
	protected $soft_deletes	  = false;
	protected $date_format	  = 'datetime';
	protected $set_created	  = false;
	protected $set_modified   = false;
	protected $created_field  = 'created_on';
	protected $modified_field = 'modified_on';

	/**
	 * A convenience method that combines a where() and find_all() call into a single call.
	 *
	 * @param mixed  $field The table field to search in.
	 * @param mixed  $value The value that field should be.
	 * @param string $type  The type of where clause to create. Either 'and' or 'or'.
	 * @param int $return_type Choose the type of return type. 0 - Object, 1 - Array
	 *
	 * @return bool|mixed An array of objects representing the results, or FALSE on failure or empty set.
	 */
	 	
}
 


