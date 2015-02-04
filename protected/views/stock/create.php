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
/* @var $this StockController */
/* @var $model Stock */
/* @var $filterModel Stock */

$this->breadcrumbs=array(
	Yii::t('app','general.label.StockMovements'),
	Yii::t('app','general.label.Create'),
);

$this->menu=array(
	array('label'=>Yii::t('app','general.label.View')." ".Yii::t('app','general.label.Stock'), 'url'=>array('productStock')),
);
?>

<?php
	if (Yii::app()->user->checkAccess('Stock.Create')
		|| Yii::app()->user->checkAccess('Stock.*')) 
		echo $this->renderPartial('_form', array('model'=>$model)); 
?>

<?php 
	if (Yii::app()->user->checkAccess('Stock.Admin')
		|| Yii::app()->user->checkAccess('Stock.*')) 
		echo $this->renderPartial('admin', array('model'=>$filterModel));
?>