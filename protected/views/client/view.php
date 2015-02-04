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
/* @var $this ClientController */
/* @var $model Client */

$this->breadcrumbs=array(
	Yii::t('app','general.label.Clients')=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>Yii::t('app','general.label.Manage')." ".Yii::t('app','general.label.Client'), 'url'=>array('admin')),
	array('label'=>Yii::t('app','general.label.Create')." ".Yii::t('app','general.label.Client'), 'url'=>array('create')),
	array('label'=>Yii::t('app','general.label.Update')." ".Yii::t('app','general.label.Client'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('app','general.label.Delete')." ".Yii::t('app','general.label.Client'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('app','message.confirmDelete'))),
);
?>

<h1><?php echo Yii::t('app','general.button.view').' '.Yii::t('app','general.label.Client').' - '.$model->id;?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'code',
		'name',
		'description',
		'address',
		'phone',
		'mobile',
	),
));
?>
