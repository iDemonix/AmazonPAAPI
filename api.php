<?php

    /**
     * Class to access Amazons Product Advertising API
     * @author Dan Walker
     * @link http://github.com/iDemonix
     * @version 1.0
     */
    
    /*
    Permission is hereby granted, free of charge, to any person obtaining a
    copy of this software and associated documentation files (the "Software"),
    to deal in the Software without restriction, including without limitation
    the rights to use, copy, modify, merge, publish, distribute, sublicense,
    and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
    THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
    DEALINGS IN THE SOFTWARE.
    */

require_once('aws_signed_request.php');

class AmazonPAAPI
{
	/*
     * configuration
     */
     
    private $key_public     = "";
    private $key_private    = "";
    private $associate_tag  = "";
    private $region_domain	= "com";


    /*
     * getItemByAsin - fetches an item based on a supplied ASIN
     *
     * @param string $asin - ASIN to search by
     * @return array - array of results
     */
       
    public function getItemByAsin($asin)
    {
        $parameters = array("Operation"     => "ItemLookup",
                            "ItemId"        => $asin,
                            "ResponseGroup" => "Medium");
                            
        return $this->query($parameters);
    }
    
    
    /*
     * getItemByKeyword - fetches an item based on a supplied keyword and product type
     *
     * @param string keyword
     * @param string product_type
     * @return array - array of results
     */
    
    public function getItemByKeyword($keyword, $product_type)
    {
        $parameters = array("Operation"   => "ItemSearch",
                            "Keywords"    => $keyword,
                            "SearchIndex" => $product_type);
                            
        return $this->query($parameters);
    }
    
    
    /*
     * getTopSellers - fetches top sellers for a specified category
     *
     * @param string $nodeID - the category's node ID to use
     * @return array - array of results
     */
        
    public function getTopSellers($nodeID=1)
    {
        $parameters = array("Operation"   		=> "BrowseNodeLookup",
                            "ResponseGroup"     => "TopSellers",
                            "BrowseNodeId"		=> $nodeID);
                            
        return $this->query($parameters);
    }
    
   
   

    private function query($query)
    {        
	    $fetched = false;
	    
	    while ($fetched == false) {
	    	$result = aws_signed_request($this->region_domain, $query, $this->key_public, $this->key_private, $this->associate_tag);
	    
		    if ($result->Error->Code == 'RequestThrottled') {
			    sleep(0.1);
		    } else {
			    $fetched = true;
		    }
		}
		 
        return $result;
    }
    
}

?>