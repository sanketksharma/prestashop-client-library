<?php
require_once('../Connection.php');
// Here we use the WebService to get the schema of "customers" resource
try
{
	$custwebService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
	$opt = array('resource' => 'customers');
	if (isset($_GET['id']))
		$opt['id'] = $_GET['id'];
	$xml = $custwebService->get($opt);
	
	$resources = $xml->children()->children();
	
	$Cxml =simplexml_load_file("php://input");	
}
catch (PrestaShopWebserviceException $e)
{
	// Here we are dealing with errors
	$trace = $e->getTrace();
	if ($trace[0]['args'][0] == 404) echo 'Bad ID';
	else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
	else echo 'Other error';
}
// Here we have XML before update, lets update XML

	foreach ($resources as $nodeKey => $node )
	{
		$f=0;
		$resources->id = $_GET['id'];
		foreach ($Cxml as $chNodeKey=> $chNode)
		{
//			if($chNodeKey === "prestashopid")// && $resources->$nodeKey == "id")
//			{
//				$f=1;
//				$resources->id = $_GET['id'];
//			}
			if($nodeKey === $chNodeKey)
			{
				$f=1;
				$resources->$nodeKey = $chNode;
			}			
		}
		if($f == 0) {		
			$resources->$nodeKey = null;
		}
	}

	try
	{
		$opt = array('resource' => 'customers');			
			$opt['putXml'] = $xml->asXML();
			$opt['id'] = $_GET['id'];
			$xml = $custwebService->edit($opt);
			$resources = $xml->children()->children();		
			echo "Successfully updated.";

	}
	catch (PrestaShopWebserviceException $ex)
	{
		// Here we are dealing with errors
		$trace = $ex->getTrace();
		if ($trace[0]['args'][0] == 404) echo 'Bad IDsanket';
		else if ($trace[0]['args'][0] == 401) echo 'Bad auth kdey';
		else echo 'Other errorsanket<br />'.$ex->getMessage();
	}
?>
