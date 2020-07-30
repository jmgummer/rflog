<?php
$this->pageTitle=Yii::app()->name.' | Process Compliance';
$this->breadcrumbs=array('Process Compliance');
$schedule_id = $model->id;
$excelfile = "docs/compliancefiles/$model->mediaschedule";
if($model->runstate!=1){
	if(file_exists($excelfile)){
		$Reader = new SpreadsheetReader($excelfile);
		$rowcounter =  1;
		foreach ($Reader as $ExcelRow){
			if($rowcounter>1){
				$insertarray = array();
				if(is_array($ExcelRow) && count($ExcelRow)>=32){
					$station_name = trim($ExcelRow[0]);
					if(!empty($station_name)){
						$column_start = 2;
						$date_index = 1;
						while ($date_index <= 31) {
							$datecolumn = $column_start+$date_index;
							if(isset($ExcelRow[$datecolumn])){
								$compiance_period = new CompliancePeriod;
								$compiance_period->schedule_id = $model->id;
								$compiance_period->station_name = trim($ExcelRow[0]);
								$compiance_period->adtype = trim($ExcelRow[1]);
								$compiance_period->timeblock = trim($ExcelRow[2]);
								$adyear = $model->adyear;
								$admonth = $model->admonth;
								$adday = $date_index;
								$expected_ads = (int)$ExcelRow[$datecolumn];
								$reconstruct_date = "$adyear-$admonth-$adday";
								$addate = date("Y-m-d", strtotime($reconstruct_date));
								$compiance_period->addate = $addate;
								$compiance_period->expected_ads = $expected_ads;
								$compiance_period->save();
							}
							$date_index++;
						}
					}
				}
			}
			$rowcounter++;
		}
		$model->runstate = 1;
		$model->save();
	}else{
		echo "<p><strong>Could not find File</strong></p>";
	}
}
$compliance_records = new CompliancePeriod;

$stations = $compliance_records->getUnresolvedStations($model->id);
$adtypes = $compliance_records->getUnresolvedAdtypes($model->id);
if($adtypes || $stations){
?>
<div class="row">
    <div class="col-md-6">
        <label for="stations">Stations</label>
        <hr>
        <?php
        $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'st_ts')));
        if($stations){
            foreach ($stations as $key) {
                echo "<div class='row'>";
                echo "<div class='col-md-6' style='font-size: .71137rem;'>$key->station_name</div>";
                echo "<div class='col-md-6'>";
                echo $form->dropDownList($compliance_records,'station_id', Station::RFStations($key->station_name), array('name'=>'station_id['.$key->station_name.']','class'=>'form-control form-control-sm'));  
                echo "</div>";
                echo "</div>";
                echo "<br>";
            }
            echo "<div><input type='submit' name='submit_stations' value='Map Stations' class='btn btn-primary pull-right'></div>";
        }else{
            echo "<p>All Stations have been Mapped!</p>";
        }
        $this->endWidget();
        ?>
    </div>
    <div class="col-md-6">
        <label for="adtypes">Ad Types</label>
        <hr>
        <?php
        $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'st_ts')));
        if($adtypes){
            foreach ($adtypes as $key) {
                echo "<div class='row'>";
                echo "<div class='col-md-6' style='font-size: .71137rem;'>$key->adtype</div>";
                echo "<div class='col-md-6'>";
                echo $form->dropDownList($compliance_records,'entry_type_id', DjmentionsEntryTypes::RFEntryTypes($key->adtype), array('name'=>'entry_type_id['.$key->adtype.']','class'=>'form-control form-control-sm','multiple'=>true));  
                echo "</div>";
                echo "</div>";
                echo "<br>";
            }
            echo "<div><input type='submit' name='submit_adtypes' value='Map Ad Types' class='btn btn-primary pull-right'></div>";
        }else{
            echo "<p>All Ad Types have been Mapped!</p>";
        }
        $this->endWidget();
        ?>
    </div>
</div>
<?php
}else{
	// echo "Run";
	$brandid = $model->brand_id;
	$admonth = $model->admonth;
	$adyear = $model->adyear;
	// process data
	$processor = new ADManager;
	$handler = $processor->Handler($schedule_id,$brandid,$admonth,$adyear);
	// $app_pdf = AppPDF::StandardPDF($handler); 
	// echo $app_pdf;
}
?>
<hr>
<div class="row">
	<div id="complianceresults" class="col-md-12"></div>
</div>