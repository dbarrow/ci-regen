<?php 

$contents = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');";
$contents .= "require APPPATH . '/libraries/RS_REST_Controller.php';

class " . $service_name . " extends RS_REST_Controller {

	protected \$model = '" . $service_name . "_model';
	

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
	}

	//--------------------------------------------------------------------
} 
";

echo $contents;
?>






