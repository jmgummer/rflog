<?php
$this->pageTitle=Yii::app()->name.' | Process Logs';
$this->breadcrumbs=array('Process Logs');

$runid = $model->id;
$excelfile = "docs/reconfiles/$model->filename";
if($model->recon_state!=1){
	if(file_exists($excelfile)){
		$Reader = new SpreadsheetReader($excelfile);
		$rowcounter =  1;
		foreach ($Reader as $Row)
		{
			if($rowcounter>1){
				$insertarray = array();
				if(is_array($Row) && count($Row)==9){
					$recon_temp = new ReconTemp;
					$recon_temp->recon_file = $model->id;
					$recon_temp->log_campaign_name = trim($Row[5]);
					$recon_temp->log_station_name = trim($Row[2]);
					$recon_temp->log_date = date("Y-m-d", strtotime(trim($Row[0])));
					$recon_temp->log_time = date("H:i:s", strtotime(trim($Row[1])));
					$recon_temp->log_company_name = trim($Row[7]);
					$recon_temp->log_brand_name = trim($Row[3]);
					$recon_temp->log_sub_brand_name = trim($Row[4]);
					$recon_temp->adtype_name = trim($Row[6]);
					$recon_temp->ad_duration = trim($Row[8]);
					$recon_temp->save();
				}
			}
			$rowcounter++;
		}
		$model->recon_state = 1;
		$model->save();
	}else{
		echo "<p><strong>Could not find File</strong></p>";
	}
}
// if($model->recon_state == 1){
// 	echo "<p>Excel File Processed</p>";
// }
$recon_file = $model->id;
$recon_temp = new ReconTemp;
?>

<div class="row">
    <div class="col-md-4">
        <label for="stations">Companies</label>
        <hr>
        <?php
        if(isset($_POST['submit_companies']) && isset($_POST['rf_company_id'])){
            $rc_companies = $_POST['rf_company_id'];
            foreach ($rc_companies as $key => $value) {
                $log_company_name = $key;
                $rf_company_id = $value;
                $update_sql = "UPDATE recon_temp SET rf_company_id = $rf_company_id WHERE log_company_name='$log_company_name' AND recon_file=$recon_file";
                $update_qry = Yii::app()->forgedb->createCommand($update_sql)->execute();
            }
        }
        
        $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'st_ts')));
        $companies = $recon_temp->getUnresolvedCompanies($model->id);
        if($companies){
            foreach ($companies as $key) {
                echo "<div class='row'>";
                echo "<div class='col-md-6' style='font-size: .71137rem;'>$key->log_company_name</div>";
                echo "<div class='col-md-6'>";
                echo $form->dropDownList($recon_temp,'rf_company_id', ReconTemp::RFCompanies($key->log_company_name), array('name'=>'rf_company_id['.$key->log_company_name.']','class'=>'form-control form-control-sm'));  
                echo "</div>";
                echo "</div>";
                echo "<br>";
            }
            echo "<div><input type='submit' name='submit_companies' value='Map Companies' class='btn btn-primary pull-right'></div>";
        }else{
            echo "<p>All Companies have been Mapped!</p>";
        }
        $this->endWidget();
        ?>
    </div>
    <div class="col-md-4">
        <label for="stations">Stations</label>
        <hr>
        <?php
        if(isset($_POST['submit_stations']) && isset($_POST['station_id'])){
            $rcstations = $_POST['station_id'];
            foreach ($rcstations as $key => $value) {
                $log_station_name = $key;
                $rf_station_id = $value;
                $update_sql = "UPDATE recon_temp SET rf_station_id = $rf_station_id WHERE log_station_name='$log_station_name' AND recon_file=$recon_file";
                $update_qry = Yii::app()->forgedb->createCommand($update_sql)->execute();
            }
        }
        
        $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'st_ts')));
        $stations = $recon_temp->getUnresolvedStations($model->id);
        if($stations){
            foreach ($stations as $key) {
                echo "<div class='row'>";
                echo "<div class='col-md-6' style='font-size: .71137rem;'>$key->log_station_name</div>";
                echo "<div class='col-md-6'>";
                echo $form->dropDownList($model,'station_id', Station::RFStations($key->log_station_name), array('name'=>'station_id['.$key->log_station_name.']','class'=>'form-control form-control-sm'));  
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
    <div class="col-md-4">
        <label for="stations">Ad Types</label>
        <hr>
        <?php
        if(isset($_POST['submit_adtypes']) && isset($_POST['entry_type_id'])){
            $rcadtypes = $_POST['entry_type_id'];
            foreach ($rcadtypes as $key => $value) {
                $adtype_name = $key;
                $rf_entry_type_id = $value;
                $update_sql = "UPDATE recon_temp SET rf_entry_type_id = $rf_entry_type_id WHERE adtype_name='$adtype_name' AND recon_file=$recon_file";
                $update_qry = Yii::app()->forgedb->createCommand($update_sql)->execute();
            }
        }
        $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'st_ts')));
        $adtypes = $recon_temp->getUnresolvedAdtypes($model->id);
        if($adtypes){
            foreach ($adtypes as $key) {
                echo "<div class='row'>";
                echo "<div class='col-md-6' style='font-size: .71137rem;'>$key->adtype_name</div>";
                echo "<div class='col-md-6'>";
                echo $form->dropDownList($recon_temp,'rf_entry_type_id', DjmentionsEntryTypes::RFEntryTypes($key->adtype_name), array('name'=>'entry_type_id['.$key->adtype_name.']','class'=>'form-control form-control-sm'));  
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
<hr>
<div class="row">
    <div class="col-md-12">
        <label for="stations">Ads from the Logs</label>
        <!-- <hr> -->
        <div class="loadingres" id="loadingres"></div>
        <?php
        $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'form-inline')));
        echo '<div class="input-group mb-2 mr-sm-2">';
        echo $form->dropDownList(
            $recon_temp,'rf_company_id', ReconTemp::LogCompanies($model->id), 
            array(
                'empty'=>'Select Company','name'=>'rf_company_id', 'id'=>'lg_rf_company_id','class'=>'custom-select form-control form-control-sm',
                'ajax'=>array(
                        'type'=>'POST',
                        'data'=>array(
                            'lg_rf_company_id'=>'js:this.value',
                            'getadtypes'=>'true',
                            'recon_file'=>$model->id
                            ),
                        'url'=>CController::createURL('logdata'),'update'=>'#lg_rf_entry_type_id',
                    ),
                )
            ); 
        echo '</div>';
        echo '<div class="input-group mb-2 mr-sm-2">';
        echo $form->dropDownList($recon_temp,'rf_entry_type_id', array(), 
            array(
                'empty'=>'Select Ad Type','name'=>'rf_entry_type_id', 'id'=>'lg_rf_entry_type_id','class'=>'custom-select form-control form-control-sm',
                'ajax'=>array(
                        'type'=>'POST',
                        'data'=>array(
                            'rf_entry_type_id'=>'js:this.value',
                            'lg_rf_company_id'=>'js:lg_rf_company_id.value',
                            'getlogbrands'=>'true',
                            'recon_file'=>$model->id
                            ),
                        'url'=>CController::createURL('logdata'),'update'=>'#lg_log_sub_brand_name',
                    ),
                )
            );
        echo '</div>';
        echo '<div class="input-group mb-2 mr-sm-2">';
        echo $form->dropDownList($recon_temp,'log_sub_brand_name', array(), 
            array(
                'empty'=>'Select Log Brand','name'=>'log_sub_brand_name', 'id'=>'lg_log_sub_brand_name','class'=>'custom-select form-control form-control-sm',
                'ajax'=>array(
                        'type'=>'POST',
                        'data'=>array(
                            'log_sub_brand_name'=>'js:this.value',
                            'rf_entry_type_id'=>'js:lg_rf_entry_type_id.value',
                            'lg_rf_company_id'=>'js:lg_rf_company_id.value',
                            'getlogcampaigns'=>'true',
                            'recon_file'=>$model->id
                            ),
                        'url'=>CController::createURL('logdata'),'update'=>'#lg_log_campaign_name',
                    ),
                )
            );
        echo '</div>';
        echo '<div class="input-group mb-2 mr-sm-2">';
        echo $form->dropDownList($recon_temp,'log_campaign_name', array(), 
            array(
                'empty'=>'Select Log Campaign','name'=>'log_campaign_name', 'id'=>'lg_log_campaign_name','class'=>'custom-select form-control form-control-sm',
                // 'onchange'=>"loadBrandOptions();",
                )
            );
        echo '</div>';
        $this->endWidget();
        ?>
        <!-- <label for="stations">Auto Ads</label> -->
        <hr>
        <label for="stations">RF Identity</label>
        <?php
        $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'form-inline')));
        echo '<div class="input-group mb-2 mr-sm-2">';
        echo $form->dropDownList($recon_temp,'rf_brand_id', array(), 
            array(
                'empty'=>'Select RF Brand','name'=>'rf_brand_id', 'id'=>'lg_rf_brand_id','class'=>'custom-select form-control form-control-sm'
                )
            );
        echo '</div>';
        echo '<div class="input-group mb-2 mr-sm-2">';
        echo $form->dropDownList($recon_temp,'rf_incantation_id', array(), 
            array(
                'empty'=>'Select RF Incantation','name'=>'rf_incantation_id', 'id'=>'lg_rf_incantation_id','class'=>'custom-select form-control form-control-sm'
                )
            );
        echo '</div>';
        echo '<button type="submit" class="btn btn-primary mb-2" id="map_set" onclick="event.preventDefault();">Map Logs</button>';
        $this->endWidget();
        ?>
    </div>
</div>
<hr>