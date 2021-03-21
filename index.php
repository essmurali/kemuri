<?php
error_reporting(0);
include_once("validator/FormValidator.php");
include_once("services/FileParserService.php");
include_once("services/AnalyzeStockService.php");
$errorMsg = $uploadFile = $predictionMsg = "";
$displaySecondTab = false;
if(isset($_POST['uploadListBtn']))
{
    $rules = [
        [
            'fieldName' => 'stockList',
            'type' => 'file',
            'required' => true,
            'allowedTypes' => true
        ]
    ];
    
    $validator = new FormValidator();
    $isValid = $validator->validate($rules, $_FILES);
    if($isValid)
    {
    	$parserService = new  FileParserService;
    	$stockList = $parserService->fetchStockListAsArray($_FILES);
    	$parserService->getStockInfo($stockList);
    	$stockCompanies = $parserService->getStockCompanies();
    	$uploadFile = $parserService->uploadStockList($_FILES);
    	$displaySecondTab = true;
    }
    else
    {
        $errorMsg = $validator->getError('stockList');
        
    }
}
if(isset($_POST['analysisBtn']))
{
    $rules = [
        [
            'fieldName' => 'company',
            'type' => 'string',
            'required' => true
        ],
        [
            'fieldName' => 'startdate',
            'type' => 'date',
            'required' => true
        ],
        [
            'fieldName' => 'enddate',
            'type' => 'date',
            'required' => true
        ]
    ];
    
    $validator = new FormValidator();
    $isValid = $validator->validate($rules, $_POST);
    if($isValid)
    {
    	$analyzeStockService = new AnalyzeStockService;
    	$analyzeStockService->parseStockList($_POST);
    	$predictionMsg = $analyzeStockService->getPredictionInfo();
    }
    else
    {
        $errorMsg = $validator->getError('stockList');
        
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Stock Trading</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/css/jquery-ui.css" rel="stylesheet" />
</head>
<body>
    <?php include('includes/header.php');?>
    <div class="content-wrapper">
        <div class="container">
            <form name="stockFrm" method="post" enctype="multipart/form-data">
            <input type="hidden" name="uploadFile" id="uploadFile" value="<?php echo $uploadFile; ?>">
            <div class="row">
                <?php if($errorMsg != '') { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errorMsg; ?>
                    </div>
                <?php } ?>
                <?php if($predictionMsg != '') { ?>
                    <div class="alert alert-success" role="alert">
                        <strong><?php echo $predictionMsg; ?></strong>
                    </div>
                <?php } ?>
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
                        <?php if($displaySecondTab) { ?>
                      		<li><a data-toggle="tab" href="#analyze" id="analyzeTab">Analyze</a></li>
                      <?php } ?>
                    </ul>

                    <div class="tab-content">
                      <div id="home" class="tab-pane fade in active">
                            <h4>Upload CSV File</h4>
                            <div class="custom-file">
                                <input type="file" name="stockList" class="custom-file-input" id="stockList">

                            </div>
                            <button type="submit" name="uploadListBtn" id="uploadListBtn" class="btn btn-primary">Submit</button>
                      </div>
                      <div id="analyze" class="tab-pane fade inactive">
                        <h4>Stock Analysis</h4>
                        	<div class="form-group">
							    <label for="company">Company</label>
							    <input class="form-control" id="company" name="company">
						  	</div>
						  	<div class="form-group">
							    <label for="startdate">Start Date</label>
							    <input type="text" class="form-control datepicker" name="startdate" id="startdate">
						  	</div>
						  	<div class="form-group">
							    <label for="enddate">End Date</label>
							    <input type="text" class="form-control datepicker" name="enddate" id="enddate">
						  	</div>
						  	<div class="form-group">
							    <button type="submit" name="analysisBtn" id="analysisBtn" class="btn btn-primary">Submit</button>
						  	</div>
                      </div>
                      
                    </div>
                </div>
                <div class="col-md-2"></div>
                </form>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.11.1.js"></script>
    <script src="assets/js/jquery-ui.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <script type="text/javascript">
	  $(document).ready(function(){
	  		<?php if($displaySecondTab) { ?>
	  			$("#analyzeTab").trigger("click");
	  		<?php } ?>
	  		var availableComp  =  <?php echo json_encode($stockCompanies); ?>;
            $( "#company" ).autocomplete({
               source: availableComp
            });

		    $("#startdate").datepicker({
		    	dateFormat: 'dd-mm-yy',
		        onSelect: function(selected) {
		          $("#enddate").datepicker("option","minDate", selected)
		        }
		    });
		    $("#enddate").datepicker({ 
		    	dateFormat: 'dd-mm-yy',
		        onSelect: function(selected) {
		           $("#startdate").datepicker("option","maxDate", selected)
		        }
		    });  
		});
  </script>
</body>
</html>
