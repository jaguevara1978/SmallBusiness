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

$this->breadcrumbs=array(
	Yii::t('app','general.label.Measure Units'),
	$model->name,
);

$this->menu=array(
	array('label'=>Yii::t('app','general.label.Create')." ".Yii::t('app','general.label.MeasureUnit'), 'url'=>array('create')),
);
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'cssFile' => Yii::app()->params['General_CustomCSS'],
	'htmlOptions' => array('class'=> 'editableDetail table table-bordered table-striped table-hover'),
	'nullDisplay'=>'',
	'attributes'=>array(
		'id',
		'code',
		'name',
		'eq_reference',
		'reference',
	),
)); ?>
