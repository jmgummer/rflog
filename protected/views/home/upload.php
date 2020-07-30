<?php
$this->pageTitle=Yii::app()->name.' | Upload';
$this->breadcrumbs=array('Upload');

?>

<div class="search-title">
    <div class="col-md-12">
    	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'col-md-12','enctype' => 'multipart/form-data'))); ?>
	        <div class="row">
	            <div class="col-md-4">
	                <div class="form-group">
	                    <label for="region">Station</label>
	                    <?php echo $form->dropDownList($model,'station_id', Station::AllStations(), array('empty'=>'Multiple Stations','name'=>'station_id','class'=>'form-control'));  ?>
	                </div>
	            </div>
	            <div class="col-md-4">
	                <div class="form-group">
	                    <label for="region">Log Type</label>
	                    <select class="form-control" name='logtype' id='logtype'>
	                        <option value="1">Excel File</option>
	                        <option value="2">Jazzler PDF</option>
	                        <option value="3">Text File</option>
	                    </select>
	                </div>
	            </div>
	            <div class="col-md-4">
	                <div class="form-group">
	                    <label for="gender">Upload POF</label><br>
	                    <input type="file" name="file" id="file">
	                </div>
	            </div>
	        </div>
	        <div class="clearfix"></div>
	        <br>
	        <div class="separator-breadcrumb border-top"></div>
	        <div><input type='submit' name='submit' value='Upload' class="btn btn-primary pull-right"></div>
        <?php $this->endWidget(); ?>
        <div id="qresults"></div>
    </div>
</div>

<br>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card o-hidden mb-4">
                    <div class="card-header d-flex align-items-center border-0">
                        <h3 class="w-50 float-left card-title m-0">Runs</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                        	<?php
							$runs = new ReconFileUploads('search');
							// $runs->campaign_id = $campaign->id;
							$this->widget('bootstrap.widgets.TbGridView', array(
							'type'=>'table text-center',
							'dataProvider'=>$runs->search(),
							'filter'=>$runs,
							'filterPosition'=>'none',
							'template'=>"{items}\n{pager}",
							'selectableRows'=>10,
							'emptyText'=>"No Results Found",
							'columns'=>array(
							array('header'=>'#','value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',),
							array('name'=>'filename', 'header'=>'Name'),
							array('name'=>'Station', 'header'=>'Station'),
							array('name'=>'RunState', 'header'=>'Run Status'),
							array('name'=>'Actions', 'header'=>'Actions'),
							),
							));
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>