<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../connection.php');
require_once('./CRUD.php');
try {
    $psWebService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
}
catch (PrestaShopWebserviceException $e)
{
	// Here we are dealing with errors
	$trace = $e->getTrace();
	if ($trace[0]['args'][0] == 404) echo 'Bad ID';
	else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
	else echo 'Other error';
}
if(isset($_GET['resource']))
{
    $opt = array('resource' => $_GET['resource']);  
    $crud= new CRUD($psWebService,$opt);//,$_GET['resource']);
//CRUD crud=new CRUD($psWebService,$opt,$_GET['resource']);
    $action=  strtoupper($_GET['action']);
    switch ($action) {
        case 'CREATE':
            $crud->create();
            break;
        
       case 'RETRIEVE':
            if(isset($_GET['id']))
                $crud->retrieve($_GET['id']);
            break;
            
        case 'UPDATE':
            if(isset($_GET['id']))
                $crud->update($_GET['id']);
            break;
        
        case 'DELETE':
            if(isset($_GET['id']))
                $crud->update($_GET['id']);
            break;    
            
        case 'GETALLID':
            $crud->getAllId();
            break;
        
        default:
            echo "undefined Action";        
            break;
    }    
}
 else {
    echo 'Resource is not set. Please set the resource first.';
}
?>
