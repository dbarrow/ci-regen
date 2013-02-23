<?php 
	echo($db_installed && $tables_installed) ? "<h1>Install Complete!</h1>"  : "<h1>Installation Error</h1>";
	echo($db_installed)                      ? "DB Config successfully installed" : "Database config installation error ";
	echo($tables_installed)                  ? "Database tables successfully installed"   : "Table Installation error";

	if($db_installed && $tables_installed) 
	{
		echo anchor(base_url('auth/login'), 'Login Now', 'class="btn btn-success"');
	}
	else
	{
		echo anchor(base_url('install'), 'Reinstall', 'class="btn btn-danger"');
	}
?>