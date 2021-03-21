<?php
trait fileParser {
	public function fetchStockListAsArray($uploadData)
	{
		$tmpName = $uploadData['stockList']['tmp_name'];
		$stockList = @array_map('str_getcsv', file($tmpName));
		return $stockList;
	}
}
?>