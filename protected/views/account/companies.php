<?php
$this->pageTitle=Yii::app()->name.' | Clients';
$this->breadcrumbs=array('User Accounts'=>array('account/users'), 'Staff Details');
?>
<div class="row-fluid clearfix">
    <div class="col-md-12">
    	<?php
    	$agencyid = Yii::app()->user->company_id;
        $form = $this->beginWidget('CActiveForm', array('id'=>'users-form', 'method'=>'POST','enableAjaxValidation'=>false,));
        $company_sql = "select company.company_id, company.company_name, company.keywords from company, agency_client where company.company_id=agency_client.company_id and agency_id=$agencyid order by company_name asc";
        $companies=Yii::app()->db2->createCommand($company_sql);
        $count = Yii::app()->db2->createCommand('SELECT COUNT(*) FROM (' . $company_sql . ') as count_alias')->queryScalar();
        $dataProvider=new CSqlDataProvider($companies, array('sort'=>array('attributes'=>array('company_name',)),'keyField' => 'company_id','totalItemCount' => $count,'pagination'=>array('pageSize'=>15)));

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'bordered striped condensed hover smart-form has-tickbox',
            'dataProvider'=>$dataProvider,
            'filterPosition'=>'none',
            'template'=>"{items}\n{pager}",
            'selectableRows'=>10,
            'pager' => array('htmlOptions'=>array('class'=>'pagination')),
            'columns'=>array(
                array('header'=>'#','value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',),
                array('name'=>'company_name', 'header'=>'Name'),
                array('name'=>'keywords', 'header'=>'Keywords'),
            ),
        ));
        $this->endWidget(); 
        ?>
    </div>
</div>

<style type="text/css">
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