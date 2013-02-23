<?php 

$contents = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');";
$contents .= "

class " . $service_name . "_model extends MY_Model {
	protected \$_table		   = '" . $service_name . "';
	protected \$key			   = 'id';
	protected \$soft_deletes   = false;
	protected \$date_format	   = 'datetime';
	protected \$set_created	   = false;
	protected \$set_modified   = false;
	protected \$created_field  = 'created_on';
	protected \$modified_field = 'modified_on';
} 
";

echo $contents;
?>


