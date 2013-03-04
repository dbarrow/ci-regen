<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services_model extends MY_Model {

	protected $_table		= 'services';
	protected $key			= 'id';
	protected $soft_deletes	= false;
	protected $date_format	= 'datetime';
	protected $set_created	= false;
	protected $set_modified = false;
	protected $created_field = 'created_on';
	protected $modified_field = 'modified_on';	

	public function select_name()
    {
        $this->db->select('name');
        return $this;
    }
}