<?php
$this->pageTitle=Yii::app()->name.' | Assignments';
$this->breadcrumbs=array('User Accounts'=>array('account/users'), 'User Assignments');
?>
<div class="row-fluid clearfix">
<div class="col-md-12">
	<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>User Assignments</h2></header>
	<div role="content">
<?php
$agencyid = Yii::app()->user->company_id;
$form = $this->beginWidget('CActiveForm', array('id'=>'users-form', 'method'=>'POST','enableAjaxValidation'=>false,));
echo '<div class="row-fluid assignments">';
echo CHtml::submitButton('Assign',array('action'=>'assign','name'=>'assign',"class"=>"btn btn-success btn-sm"), array());
echo '&nbsp;';
echo CHtml::submitButton('Remove',array('action'=>'remove','name'=>'remove',"class"=>"btn btn-danger btn-sm"), array());
echo '<br><div class="clear"></div>';
echo '</div>';
$agency_sql = "SELECT 
	company.company_id as company_id, 
	company.company_name as company_name,
	IF(agency_user_client.agency_users_id IS NULL, 'Assign','Remove') AS status
FROM company 
	LEFT JOIN agency_client ON (company.company_id=agency_client.company_id)
	LEFT JOIN agency_user_client ON (company.company_id = agency_user_client.company_id && agency_users_id = $user_id)
WHERE  agency_client.agency_id=$agencyid
ORDER BY company_name ASC";

$companies=Yii::app()->db2->createCommand($agency_sql);
$count = Yii::app()->db2->createCommand('SELECT COUNT(*) FROM (' . $agency_sql . ') as count_alias')->queryScalar();
$dataProvider=new CSqlDataProvider($companies, array('sort'=>array('attributes'=>array('company_name',)),'keyField' => 'company_id','totalItemCount' => $count,'pagination'=>array('pageSize'=>15)));

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'bordered striped condensed hover smart-form has-tickbox',
    'dataProvider'=>$dataProvider,
    'filterPosition'=>'none',
    'template'=>"{items}\n{pager}",
    'selectableRows'=>10,
    'pager' => array('htmlOptions'=>array('class'=>'pagination')),
    'columns'=>array(
    	array('class'=>'CCheckBoxColumn','name'=>'company_id','id'=>'company_id'),
        array('name'=>'company_name', 'header'=>'Name'),
        array('name'=>'status', 'header'=>'status'),
    ),
));
echo '<div class="row-fluid assignments">';
echo CHtml::submitButton('Assign',array('action'=>'assign','name'=>'assign',"class"=>"btn btn-success btn-sm"), array());
echo '&nbsp;';
echo CHtml::submitButton('Remove',array('action'=>'remove','name'=>'remove',"class"=>"btn btn-danger btn-sm"), array());
echo '<br><div class="clear"></div>';
echo '</div>';
$this->endWidget(); 
?>
</div>
</div>
</div>
</div>

<style type="text/css">
.assignments{
	margin-bottom: 10px !important;
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