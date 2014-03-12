Information

  1. We have downloaded the PSWebServiceLibrary.php file from following link.
    https://github.com/PrestaShop/PrestaShop-webservice-lib/blob/master/PSWebServiceLibrary.php
  2. We have created a connection.php file which will test the connection to the Prestashop with providd API       Key.
  3. We have created a action.php file that will handle all the incoming requests.
  4. We need to send the following parameters in order to perform the operations on prestashop resource.
	    > Neccessary parameters :
        1. Akey=API Key
        2. action=Action [create/retrieve/update/delete/getallid]
        3. resource=Resource name that you want to access.
	    > Optional parameters :
        1. id= prestashop resource id
      (Note: Please use GET method to send these parameters)
