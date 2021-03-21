<?php
class AnalyzeStockService {

	protected $minPurchase = [];
	protected $maxPurchase = [];
	public function parseStockList($filterData)
	{
		$fname = $filterData['uploadFile'];
		$stockList = @array_map('str_getcsv', file("uploads/".$fname));
		$stockHeader = array_shift(array_slice($stockList, 0, 1));
		array_shift($stockList);
		$stockCompanies = $sortedCompanies = [];
		foreach ($stockList as $key => $value) {
			if(in_array($filterData['company'], $value))
				$stockCompanies[] = $value;
		}
		foreach($stockCompanies as $compKey => $compVal)
		{
			if($compVal[1] >= $filterData['startdate'] && $compVal[1] <= $filterData['enddate'])
				$sortedCompanies[$compVal[1]] = $compVal[3];
		}
		asort($sortedCompanies);
		$this->minPurchase = array_slice($sortedCompanies, 0, 1, true);
		$this->maxPurchase = array_slice($sortedCompanies, -1, 1, true);
	}

	public function getPredictionInfo()
	{
		return "Buy stocks on ".key($this->minPurchase)." and sell it on ".key($this->maxPurchase);
	}
}
?>