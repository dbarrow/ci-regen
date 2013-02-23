<?php 
	echo($db_installed && $tables_installed) ? "<h1>Install Complete!</h1>"  : "<h1>Installation Error</h1>";
	echo($db_installed)                      ? "<p>DB Config successfully installed</p>" : "<p>Database config installation error </p>";
	echo($tables_installed)                  ? "<p>Database tables successfully installed</p>"   : "<p>Table Installation error</p>";

	if($db_installed && $tables_installed) 
	{
		echo anchor(base_url('auth/login'), 'Login Now', 'class="btn btn-success"');
	}
	else
	{
		echo anchor(base_url('install'), 'Reinstall', 'class="btn btn-danger"');
	}
?>