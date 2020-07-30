<?php
$this->pageTitle=Yii::app()->name.' | Dashboard';
$this->breadcrumbs=array('Dashboard');
$adtypes = DjmentionsEntryTypes::EntryTypes(1);
$allstations = Station::AllStations();
?>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card o-hidden mb-4">
                    <div class="card-header d-flex align-items-center border-0">
                        <h3 class="w-50 float-left card-title m-0">Add Auto Ads</h3>
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
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="brands">Brand</label>
                                            <div id="showbrands">
                                                <?php 
                                                echo $form->dropDownList($model,'brand_id', array(), 
                                                    array(
                                                        'name'=>'brand_id','id'=>'brand_id' , 'class'=>'form-control', 'required'=>'required'
                                                    )
                                                ); 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="brands">Station</label>
                                            <div id="showbrands">
                                                <?php 
                                                echo $form->dropDownList($model,'station_id', Station::AllStations(), 
                                                    array(
                                                        'empty'=>'-Select Station--',
                                                        'ajax'=>array(
                                                            'type'=>'POST',
                                                            'data'=>array(
                                                                'station_id'=>'js:this.value',
                                                                'brand_id'=>'js:brand_id.value',
                                                                'entry_type_id'=>'js:entry_type_id.value',
                                                                'getads'=>TRUE
                                                            ),
                                                            'url'=>CController::createURL('getdata'),'update'=>'#incantation_id',
                                                        ),
                                                        'name'=>'station_id','id'=>'station_id','class'=>'form-control'
                                                    )
                                                );  
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="brands">Entry Type</label>
                                            <div id="showbrands">
                                                <?php 
                                                echo $form->dropDownList($model,'entry_type_id', DjmentionsEntryTypes::EntryTypes(1), 
                                                    array(
                                                        'ajax'=>array(
                                                            'type'=>'POST',
                                                            'data'=>array(
                                                                'entry_type_id'=>'js:this.value',
                                                                'station_id'=>'js:station_id.value',
                                                                'brand_id'=>'js:brand_id.value',
                                                                'getads'=>TRUE
                                                            ),
                                                            'url'=>CController::createURL('getdata'),'update'=>'#incantation_id',
                                                        ),
                                                        'name'=>'entry_type_id','id'=>'entry_type_id','class'=>'form-control'
                                                    )
                                                );  
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="brands">Incantation</label>
                                            <div id="showbrands">
                                                <?php echo $form->dropDownList($model,'incantation_id', array(), array('name'=>'incantation_id', 'id'=>'incantation_id', 'class'=>'form-control', 'required'=>'required')); ?>
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
                                        'reel_date'=>array(
                                            'type'=>'text',
                                            'placeholder'=>"$startdate",
                                            'class'=>'form-control input-sm col-md-3'
                                        ),
                                        'reel_time'=>array(
                                            'type'=>'text',
                                            'placeholder'=>"$starttime,$starttime",
                                            'class'=>'form-control input-sm col-md-12'
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