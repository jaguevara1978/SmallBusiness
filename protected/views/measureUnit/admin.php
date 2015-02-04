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
/* @var $this MeasureUnitController */
/* @var $model MeasureUnit */
?>

<?php
$disabled=!$model->isEditable();
$disabled=false;
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'=>'measure-unit-grid',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
		'columns'=>array(
		array(
			'class'=>'EditableColumn',
			'name'=>'code',
			
			'editable'=>array(
					'url'=>CController::createUrl('MeasureUnit/updateCode'),
					'inputclass'=>'span1',
					
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'name',
			
			'editable'=>array(
					'url'=>CController::createUrl('MeasureUnit/updateName'),
					'inputclass'=>'span3',
					
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'eq_reference',
			
			'editable'=>array(
					'url'=>CController::createUrl('MeasureUnit/updateEqReference'),
					'inputclass'=>'span2',
					
					
			),
		),
		Utilities::toggleButtonColumn('reference','MeasureUnit/toggle'),
		array (
			'class'=>'ButtonColumnClearFilters',
			'template'=>'{delete}',
			'label'=>Yii::t('app','general.button.clearFilters'),
			'header'=>Yii::t('app','general.label.ops'),
			'deleteConfirmation'=>"js:'".Yii::t('app','message.confirmDelete')."\\n"
				.Yii::t('app','model.MeasureUnit.Code').": '+$(this).parent().parent().children(':nth-child(1)').text()
				+'\\n".Yii::t('app','model.MeasureUnit.Name').": '+$(this).parent().parent().children(':nth-child(2)').text()
				+'\\n".Yii::t('app','model.MeasureUnit.EqReference').": '+$(this).parent().parent().children(':nth-child(3)').text()
				+'\\n".Yii::t('app','model.MeasureUnit.Reference').": '+$(this).parent().parent().children(':nth-child(4)').text()",
			'buttons'=>array(
				'delete'=>array('visible'=>'$data->reference==0'),
			),
		),
	),
)); ?>
