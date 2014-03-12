<?php
// Here we define constants /!\ You need to replace this parameters

require_once('../Connection.php');

if (isset($_GET['id']))
{		
	try
	{		
		// Call for a deletion, we specify the resource name and the id of the resource in order to delete the item
		$webService->delete(array('resource' => 'customers', 'id' => intval($_GET['id'])));
		// If there's an error we throw an exception
		echo 'Successfully deleted';
	}
	catch (PrestaShopWebserviceException $e)
	{
		// Here we are dealing with errors
		$trace = $e->getTrace();
		if ($trace[0]['args'][0] == 404) echo 'Bad ID';
		else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
		else echo 'Other error';
	}
}
?>
