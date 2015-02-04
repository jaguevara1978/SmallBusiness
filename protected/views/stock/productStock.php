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
?>
<?php
/* @var $this ProductController */
/* @var $model Product */

$this->breadcrumbs=array(
	Yii::t('app','general.label.Stock'),
	Yii::t('app','general.label.View'),
);

$this->menu=array(
	array('label'=>Yii::t('app','general.label.Create')." ".Yii::t('app','general.label.StockMovement'), 'url'=>array('create')),
);
?>

<?php
$totalProvider = explode('_', $model->totalProvider($model->search()->criteria));
$measure_unitList=MeasureUnit::getList();
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'=>'product-grid',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search_stock(),
	'fixedHeader'=>true,
	'filter'=>$model,
		'columns'=>array(
			'code',
			'name',
			array(
				'class'=>'EditableColumn',
				'name'=>'measure_unit',
				'filter'=>$measure_unitList,
				'editable'=>array(
						'url'=>CController::createUrl('Product/updateMeasureUnit'),
						
						'type'=>'select',
					'source'=>$measure_unitList,
					'options'=>array('showbuttons'=>false,'disabled'=>true),
						
				),
			),
			array(
				'name'=>'stock_quantity',
				'htmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalColumn']),
			),
			array(
				'name'=>'stock_detail_value',
				'htmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalColumn']),
				'footerHtmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalFooter']),
				'footer'=>$totalProvider[0],
				'filter'=>false,
			),
			array(
				'name'=>'stock_manual_value',
				'htmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalColumn']),
				'footerHtmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalFooter']),
				'footer'=>$totalProvider[1],
				'filter'=>false,
			),
			array (
				'class'=>'ButtonColumnClearFilters',
				'template'=>'{delete}',
				'label'=>Yii::t('app','general.button.clearFilters'),
				'header'=>Yii::t('app','general.label.ops'),
				'buttons'=>array('delete'=>array('visible'=>'false',),),
			),
		),
	)
); 

?>
