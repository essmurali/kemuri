<?php

include_once 'fileParser.php';
class FileParserService
{
    use fileParser;

    protected $stockCompanies = array();

    public function getStockInfo($stockList)
    {
    	$stockHeader = array_shift(array_slice($stockList, 0, 1));
    	$stockCompanyKey = array_search("stock_name", $stockHeader); 
    	$stockCompanies = [];
    	array_shift($stockList);
    	foreach ($stockList as $key => $value) {
    		array_push($stockCompanies, $value[$stockCompanyKey]);
    	}
    	$this->stockCompanies = array_unique($stockCompanies);
    }

    public function getStockCompanies()
    {
    	return $this->stockCompanies;
    }

    public function uploadStockList($uploadFile)
    {
    	$fname = uniqid();
    	$ext = end(explode(".", $uploadFile['stockList']['name']));
		if (move_uploaded_file($uploadFile['stockList']['tmp_name'], 'uploads/'. $fname.'.'.$ext)) {
		    return $fname.".".$ext;
		} 
    }
}
