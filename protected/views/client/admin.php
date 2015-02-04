<?php
/* 
Small business administrator
Copyright (C) 2013 JULIO ALEXANDER GUEVARA MARULANDA

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
/* @var $this ClientController */
/* @var $model Client */
$this->breadcrumbs=array(
	Yii::t('app','general.label.Clients')=>array('admin'),
	Yii::t('app','general.label.manage'),
);

$this->menu=array(
	array('label'=>Yii::t('app','general.label.Create')." ".Yii::t('app','general.label.Client'), 'url'=>array('create')),
);
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'=>'client-grid',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
		'columns'=>array(
		array(
			'class'=>'EditableColumn',
			'name'=>'code',
			
			'editable'=>array(
					'url'=>CController::createUrl('Client/updateCode'),
					'inputclass'=>'span2',
					
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'name',
			
			'editable'=>array(
					'url'=>CController::createUrl('Client/updateName'),
					'inputclass'=>'span3',
					
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'description',
			
			'editable'=>array(
					'url'=>CController::createUrl('Client/updateDescription'),
					'inputclass'=>'span2',
					'type'=>'textarea',
					
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'address',
			
			'editable'=>array(
					'url'=>CController::createUrl('Client/updateAddress'),
					'inputclass'=>'span2',
					'type'=>'textarea',
					
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'phone',
			
			'editable'=>array(
					'url'=>CController::createUrl('Client/updatePhone'),
					'inputclass'=>'span2',
					
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'mobile',
			
			'editable'=>array(
					'url'=>CController::createUrl('Client/updateMobile'),
					'inputclass'=>'span3',
					
					'placement'=>'left',
			),
		),

		array (
			'class'=>'ButtonColumnClearFilters',
			'template'=>'{delete}',
			'label'=>Yii::t('app','general.button.clearFilters'),
			'header'=>Yii::t('app','general.label.ops'),
			'viewButtonImageUrl'=>Yii::app()->params['General_BaseImageDirectory'].'Show.png',
			'updateButtonImageUrl'=>Yii::app()->params['General_BaseImageDirectory'].'Edit.png',
			'deleteButtonImageUrl'=>Yii::app()->params['General_BaseImageDirectory'].'Erase.png',
			'deleteConfirmation'=>"js:'".Yii::t('app','message.confirmDelete')."\\n"
				.Yii::t('app','model.Client.Code').": '+$(this).parent().parent().children(':nth-child(1)').text()
				+'\\n".Yii::t('app','model.Client.Name').": '+$(this).parent().parent().children(':nth-child(2)').text()
				+'\\n".Yii::t('app','model.Client.Description').": '+$(this).parent().parent().children(':nth-child(3)').text()
				+'\\n".Yii::t('app','model.Client.Address').": '+$(this).parent().parent().children(':nth-child(4)').text()
				+'\\n".Yii::t('app','model.Client.Phone').": '+$(this).parent().parent().children(':nth-child(5)').text()
				+'\\n".Yii::t('app','model.Client.Mobile').": '+$(this).parent().parent().children(':nth-child(6)').text()",
		),
	),
)); ?>
