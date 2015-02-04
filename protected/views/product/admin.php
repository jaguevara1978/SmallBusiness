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
/* @var $this ProductController */
/* @var $model Product */
$measure_unitList=MeasureUnit::getList();
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'=>'product-grid',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
		'columns'=>array(
		array(
			'class'=>'EditableColumn',
			'name'=>'code',
			
			'editable'=>array(
					'url'=>CController::createUrl('Product/updateCode'),
					'inputclass'=>'span1',
					
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'name',
			
			'editable'=>array(
					'url'=>CController::createUrl('Product/updateName'),
					'inputclass'=>'span3',
					
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'measure_unit',
			'filter'=>$measure_unitList,
			'editable'=>array(
					'url'=>CController::createUrl('Product/updateMeasureUnit'),
					
					'type'=>'select',
				'source'=>$measure_unitList,
				'options'=>array('showbuttons'=>false,),
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'custom_order',
			
			'editable'=>array(
					'url'=>CController::createUrl('Product/updateCustomOrder'),
					'inputclass'=>'span1',
					
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'default_qty',
			
			'editable'=>array(
					'url'=>CController::createUrl('Product/updateDefaultQty'),
					'inputclass'=>'span2',
					
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'default_value',
			
			'editable'=>array(
					'url'=>CController::createUrl('Product/updateDefaultValue'),
					'inputclass'=>'span2',
					
					
			),
		),
		Utilities::toggleButtonColumn('stock_movement','Product/toggle'),
		array (
			'class'=>'ButtonColumnClearFilters',
			'template'=>'{delete}',
			'label'=>Yii::t('app','general.button.clearFilters'),
			'header'=>Yii::t('app','general.label.ops'),
			'deleteConfirmation'=>"js:'".Yii::t('app','message.confirmDelete')."\\n"
				.Yii::t('app','model.Product.Code').": '+$(this).parent().parent().children(':nth-child(1)').text()
				+'\\n".Yii::t('app','model.Product.Name').": '+$(this).parent().parent().children(':nth-child(2)').text()
				+'\\n".Yii::t('app','model.Product.MeasureUnit').": '+$(this).parent().parent().children(':nth-child(3)').text()
				+'\\n".Yii::t('app','model.Product.CustomOrder').": '+$(this).parent().parent().children(':nth-child(4)').text()
				+'\\n".Yii::t('app','model.Product.DefaultQty').": '+$(this).parent().parent().children(':nth-child(5)').text()
				+'\\n".Yii::t('app','model.Product.DefaultValue').": '+$(this).parent().parent().children(':nth-child(6)').text()
				+'\\n".Yii::t('app','model.Product.StockMovement').": '+$(this).parent().parent().children(':nth-child(7)').text()",
		),
	),
)); ?>
