<?php
$this->pageTitle=Yii::app()->name.' | Dashboard';
$this->breadcrumbs=array('Dashboard');
$adtypes = DjmentionsEntryTypes::EntryTypes(2);
$allstations = Station::AllStations();
?>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card o-hidden mb-4">
                    <div class="card-header d-flex align-items-center border-0">
                        <h3 class="w-50 float-left card-title m-0">Add Manual Ads</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- <div class="row"> -->
                            <?php 
                            if($insertaddittions!=0){
                                echo "<p>Added $insertaddittions manual ads</p>";
                            }
                            ?>
                            <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'col-md-12'))); ?>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="plantitle">Search for Company</label>
                                            <input type="text" class="form-control input-sm" name="companysearch" id="companysearch" placeholder="Search ... ">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="plantitle">Company</label>
                                            <select class="form-control input-sm" id="company_id" name="company_id" onchange="GetClientBrands();" required="required">
                                                <option value=''>Select Company</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="brands">Brand</label>
                                            <div id="showbrands">
                                                <?php echo $form->dropDownList($model,'brand_id', array(), array('name'=>'brand_id', 'class'=>'form-control', 'required'=>'required')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="brands">Station</label>
                                            <div id="showbrands">
                                                <?php echo $form->dropDownList($model,'station_id', Station::AllStations(), array('name'=>'station_id','class'=>'form-control'));  ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <?php
                                $startdate = date("Y-m-d");
                                $starttime = date("H:i");
                                $memberFormConfig = array(
                                    'elements'=>array(
                                        'date'=>array(
                                            'type'=>'text',
                                            'maxlength'=>40,
                                            'placeholder'=>"$startdate",
                                            'class'=>'form-control input-sm datepicker'
                                        ),
                                        'time'=>array(
                                            'type'=>'text',
                                            'maxlength'=>40,
                                            'placeholder'=>"$starttime",
                                            'class'=>'form-control input-sm timepicker'
                                        ),
                                        'duration'=>array(
                                            'type'=>'text',
                                            'maxlength'=>40,
                                            'placeholder'=>'e.g. 30',
                                            'class'=>'form-control input-sm'
                                        ),
                                        'entry_type_id'=>array(
                                            'type'=>'dropdownlist',
                                            'items'=>$adtypes,
                                            'class'=>'form-control input-sm',
                                            'maxlength'=>40
                                        ),
                                        'Program'=>array(
                                            'type'=>'text',
                                            'maxlength'=>40,
                                            'class'=>'form-control input-sm'
                                        )
                                    )
                                );
                                $this->widget('ext.multimodel.MultiModelForm',array(
                                    'id' => 'id_member', //the unique widget id
                                    'formConfig' => $memberFormConfig, //the form configuration array
                                    'model' => $complianceItems, //instance of the form model
                                    'bootstrapLayout'=>true,
                                    'tableView' => true,
                                    'removeHtmlOptions'=>array('class'=>'btn btn-danger btn-sm'),
                                    'validatedItems' => $validatedMembers
                                ));
                                ?>
                                <div class="clearfix"></div>
                                <div><input type='submit' name='submit' value='Save Entries' class="btn btn-primary pull-right"></div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>