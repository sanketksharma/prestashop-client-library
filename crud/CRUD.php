<?php
/**
 * Description of CRUD
 *
 * This file is used for performing crud (create,retrieve,update,delete) for prestashop from java platform.
 * This file is accessed by action.php file.
 * 
 * 
 * @author Sanket
 */

class CRUD {
    //put your code here

    /** @var PrestaShopWebservice Operation handling variable */
    protected $psWebService;

    /** @var Array Contains the request parameters */
    protected $opt;

    /** @var InputStream Contains the requested xml */
    protected $erpXml;
    
    /** @var String contains value either create or update */
    protected $operation;

    /** CRUD constructor for creating an instance of PrestashopWebservice     */
    public function __construct($prestaWebService, $options) {
        $this->psWebService = $prestaWebService;
        $this->opt = $options;
        //$this->resource=$res;        
    }

    public function create() {
        try {
            $this->erpXml = simplexml_load_file("php://input");
            $this->operation = "create";
            $xmlschema = $this->psWebService->get(array('url' => PS_SHOP_PATH . '/api/' . $this->opt['resource'] . '?schema=blank'));
            $resXml = $this->xmlOperation($xmlschema);	
            //$opttions = array('resource' => $_GET['resource']);

            $this->opt['postXml'] = $resXml->asXML();
            $xml = $this->psWebService->add($this->opt);
            $resources = $xml->children()->children();
            $this->writeXml($resources);
        } catch (PrestaShopWebserviceException $ex) {
            // Here we are dealing with errors
            $trace = $ex->getTrace();
            if ($trace[0]['args'][0] == 404)
                echo 'Bad ID';
            else if ($trace[0]['args'][0] == 401)
                echo 'Bad auth kdey';
            else
                echo 'Other error<br />' . $ex->getMessage();
        }
    }

    public function retrieve($elementId) {
        try {
            $this->erpXml = simplexml_load_file("php://input");
            $this->opt['id'] = (int) $elementId; // cast string => int for security measures      
            $xml = $this->psWebService->get($this->opt);
            //$resources = $xml->children()->children();
            $this->updateXml($xml);
            $this->sendXml($this->erpXml);
        } catch (PrestaShopWebserviceException $ex) {
            // Here we are dealing with errors
            $trace = $ex->getTrace();
            if ($trace[0]['args'][0] == 404)
                echo 'Bad ID';
            else if ($trace[0]['args'][0] == 401)
                echo 'Bad auth kdey';
            else
                echo 'Other error<br />' . $ex->getMessage();
        }
    }

    public function update($id) {
        
        $this->operation = "update";
        $this->opt['id'] = $id;
        $xmlForUpdate = $this->psWebService->get($this->opt);              
        try {
            $this->erpXml = simplexml_load_file("php://input");
            $resxml = $this->xmlOperation($xmlForUpdate);
            $this->opt['putXml'] = $resxml->asXML();
            //$this->opt['id'] = $id;
            $xml = $this->psWebService->edit($this->opt);
            echo "Successfully updated.";
        } catch (PrestaShopWebserviceException $ex) {
            // Here we are dealing with errors
            $trace = $ex->getTrace();
            if ($trace[0]['args'][0] == 404)
                echo 'Bad ID';
            else if ($trace[0]['args'][0] == 401)
                echo 'Bad auth kdey';
            else
                echo 'Other error<br />' . $ex->getMessage();
        }
    }

    public function delete($id) {
        try {
            // Call for a deletion, we specify the resource name and the id of the resource in order to delete the item
            $this->psWebService->delete(array('resource' => $this->opt['resource'], 'id' => intval($id)));
            // If there's an error we throw an exception
            echo 'Successfully deleted';
        } catch (PrestaShopWebserviceException $e) {
            // Here we are dealing with errors
            $trace = $e->getTrace();
            if ($trace[0]['args'][0] == 404)
                echo 'Bad ID';
            else if ($trace[0]['args'][0] == 401)
                echo 'Bad auth key';
            else
                echo 'Other error';
        }
    }

    public function getAllId() {
        try {                                                                   
            $xml = $this->psWebService->get($this->opt);           
            // Here we get the elements from children of customer markup which is children of prestashop root markup	
            $resources = $xml->children()->children();           
            foreach ($resources as $resource) {
                echo $resource->attributes() . "\n";
            }
        } catch (PrestaShopWebserviceException $e) {
            // Here we are dealing with errors
            $trace = $e->getTrace();
            if ($trace[0]['args'][0] == 404)
                echo 'Bad ID';
            else if ($trace[0]['args'][0] == 401)
                echo 'Bad auth key';
            else
                echo 'Other erroras';
        }
    }

    protected function xmlOperation($xml) {
        $resources = $xml->children()->children();
        foreach ($resources as $nodeKey => $node) {            
            if ($this->operation === "update")
                $resources->id = intval($this->opt['id']);
            foreach ($this->erpXml as $chNodeKey => $chNode) {
                if ($nodeKey === $chNodeKey) {
                    $resources->$nodeKey = $chNode;
                }
            }           
        }
        return $xml;
    }

    protected function updateXml($xml) {
        $resources = $xml->children()->children();

        foreach ($resources as $nodeKey => $node) {
            foreach ($this->erpXml as $chNodeKey => $chNode) {
                if ($nodeKey === $chNodeKey) {
                    $this->erpXml->$chNodeKey = $node;
                }
            }           
        }
    }

    protected function sendXml($data) {
        $result = new SimpleXMLElement("<".$data->getName()."/>");
        foreach ($data as $key => $val) {
            $track = $result->addChild($key, $val);
        }
        header("Content-Type: text/xml");
        echo $result->asXML();
    }

    protected function writeXml($resources) {
        foreach ($resources as $child => $value) {
            echo $child . ": " . $value . "\n";
        }
    }

}

?>
