<?php
$this->pageTitle=Yii::app()->name.' | Compliance';
$this->breadcrumbs=array('Compliance');

?>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card o-hidden mb-4">
                    <div class="card-header d-flex align-items-center border-0">
                        <h3 class="w-50 float-left card-title m-0">Add New Schedule</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- <div class="row"> -->
                            <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'col-md-12','enctype' => 'multipart/form-data'))); ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?php 
                                            echo $form->textFieldRow($model,'compliance_name',
                                                array('name'=>'compliance_name','class'=>'form-control input-sm','required'=>'required')
                                            ); 
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="plantitle">Search by Company or Brand ...</label>
                                            <input type="text" class="form-control input-sm" name="companysearch" id="companysearch" placeholder="Search ... ">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="plantitle">Company</label>
                                            <select class="form-control input-sm" id="company_id" name="company_id" onchange="GetClientBrands();" required="required">
                                                <option value=''>Select Company</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="brands">Brand</label>
                                            <div id="showbrands">
                                                <?php 
                                                echo $form->dropDownList($model,'brand_id', array(), 
                                                    array(
                                                        'name'=>'brand_id','id'=>'brand_id' , 'class'=>'form-control', 'required'=>'required','multiple'=>true
                                                    )
                                                ); 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="brands">Month</label>
                                                <?php 
                                                echo $form->dropDownList($model,'admonth', CHtml::encodeArray(Yii::app()->locale->getMonthNames()), 
                                                    array(
                                                        'name'=>'admonth','id'=>'admonth' , 'class'=>'form-control', 'required'=>'required'
                                                    )
                                                );
                                                ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="brands">Year</label>
                                                <?php 
                                                $startyear = 2008;
                                                $endyear = date("Y");
                                                $range = range($endyear,$startyear);
                                                $ranges = array_combine($range, $range);
                                                echo $form->dropDownList($model,'adyear', $ranges, 
                                                    array(
                                                        'name'=>'adyear','id'=>'adyear' , 'class'=>'form-control', 'required'=>'required'
                                                    )
                                                ); 
                                                ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Upload Schedule</label><br>
                                            <input type="file" name="file" id="file">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                
                                <div class="clearfix"></div>
                                <hr>
                                <div><input type='submit' name='uploadcompliance' value='Save Entries' class="btn btn-primary pull-right"></div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$complianceruns=new CActiveDataProvider('ComplianceSchedule', array('criteria'=>array('order'=>'id ASC'),'pagination'=>array('pageSize'=>15,)));                 
$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'bordered striped condensed',
    'dataProvider'=>$complianceruns,
    'filter'=>$complianceruns,
    'filterPosition'=>'none',
    'template'=>"{items}\n{pager}",
    'selectableRows'=>10,
    'pager' => array('htmlOptions'=>array('class'=>'pagination')),
    'columns'=>array(
        // array('class'=>'CCheckBoxColumn','value'=>'$data->id','id'=>'id'),
        array('header'=>'#','value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',),
        array('name'=>'actions', 'header'=>'Complaince','type' => 'raw', 'value' =>'CHtml::link("$data->compliance_name",Yii::app()->createUrl("compliance/edit",array("id"=>$data->id)))'),
        array('name'=>'adyear', 'header'=>'Year'),
        array('name'=>'admonth', 'header'=>'Month'),
        // array('name' => 'Assignments', 'header' => 'Assignments', 'type' => 'raw', 'value' =>'CHtml::link("Clients",Yii::app()->createUrl("account/assignclients",array("id"=>$data->agency_users_id)))'),
        // array('name'=>'Alerts', 'header'=>'Email Alerts'),
        // array('name'=>'Level', 'header'=>'Level'),
        // array('name'=>'Status', 'header'=>'Status'),
        array('name' => 'actions', 'header' => 'Actions', 'type' => 'raw', 'value' =>'CHtml::link("Generate",Yii::app()->createUrl("compliance/process",array("id"=>$data->id)), array("class"=>"btn btn-success btn-sm"))',),
    ),
));
// $this->endWidget(); 
?>