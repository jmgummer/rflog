<?php
$this->pageTitle=Yii::app()->name.' | Users';
$this->breadcrumbs=array('User Accounts'=>array('account/users'), 'Staff Details');
?>
<div class="row-fluid clearfix">
	<div class="col-md-12">
        <div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
            <header role="heading"><h2><?php echo Yii::app()->user->company_name; ?> | Staff</h2></header>
        </div>
        <?php
        $agencyid = Yii::app()->user->company_id;
        $form = $this->beginWidget('CActiveForm', array('id'=>'users-form', 'method'=>'POST','enableAjaxValidation'=>false,)); 

        $model=new CActiveDataProvider('AgencyUsers', array('criteria'=>array('condition'=>'agency_id=:a','params'=>array(':a'=>$agencyid,),'order'=>'agency_users_id ASC'),'pagination'=>array('pageSize'=>15,)));                 

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'bordered striped condensed hover smart-form has-tickbox',
            'dataProvider'=>$model,
            'filter'=>$model,
            'filterPosition'=>'none',
            'template'=>"{items}\n{pager}",
            'selectableRows'=>10,
            'pager' => array('htmlOptions'=>array('class'=>'pagination')),
            'columns'=>array(
                array('class'=>'CCheckBoxColumn','value'=>'$data->agency_users_id','id'=>'agency_users_id'),
                array('header'=>'#','value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',),
                array('name'=>'username', 'header'=>'Username','type' => 'raw', 'value' =>'CHtml::link("$data->username",Yii::app()->createUrl("account/updateuser",array("id"=>$data->agency_users_id)))'),
                array('name'=>'UserName', 'header'=>'Name'),
                array('name' => 'Assignments', 'header' => 'Assignments', 'type' => 'raw', 
                    'value' =>'CHtml::link("Clients",Yii::app()->createUrl("account/assignclients",array("id"=>$data->agency_users_id)))'),
                array('name'=>'Alerts', 'header'=>'Email Alerts'),
                array('name'=>'Level', 'header'=>'Level'),
                array('name'=>'Status', 'header'=>'Status'),
                array('name' => 'reset', 'header' => 'Reset Password', 'type' => 'raw', 
                    'value' =>'CHtml::link("Reset",Yii::app()->createUrl("account/resetpassword",array("id"=>$data->agency_users_id)), array("class"=>"btn btn-success btn-xs"))',
                    
                    ),
            ),
            
        ));
        $this->endWidget(); 

        ?>
    </div>
</div>
<script type="text/javascript">
function test(){
    var data = $(this).attr('id');
    alert(data);
}
</script>
<style type="text/css">
.jarviswidget {
    margin: 0px 0px 0px;
    position: relative;
    border-radius: 0px;
    padding: 0px;
}
.table .btn{
	color: #fff;
}
.pagination .selected {
    border: none !important;
}
.selected:after{
    border-top: none !important;
    content: " " !important;
}
.pagination .pagination{
	float:right !important;
	margin-top: -5px !important;
	padding-left: 10px !important;
}
</style>