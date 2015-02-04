<?php 
$folderName='beerlink';
$dirName=str_ireplace($folderName,'Base',dirname(__FILE__));
return CMap::mergeArray(
	require($dirName.'/main.php'),
	array(
		'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
		'name'=>'BeerLink',
		'modules'=>array(
			'settings'=>array('path'=>__DIR__.DIRECTORY_SEPARATOR),
			'backup'=>array('path'=>__DIR__.'/../_backup/'),
		),
	
		// application components
		'components'=>array(
			'db'=>array(
				'connectionString'=>'mysql:host=localhost;dbname=arkeasco_helber',
				//'emulatePrepare'=>true,
				'username'=>'arkeasco_admindb',
				'password'=>'Admindb2012',
				'charset'=>'utf8',
				//'enableParamLogging'=>true,
				//'enableProfiling'=>true,
			),
		),
	
		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params'=>CMap::mergeArray(
			require(__DIR__.DIRECTORY_SEPARATOR.'params.php'),
			array(
				'General_CustomCSS_Directory'=>__DIR__.'/../../css/',
				'Technical_FolderName'=>$folderName,
				'General_ClientInvoice_Enabled'=>true,
				'General_ClientInvoiceDetail_Enabled'=>true,
				'General_ClientInvoiceDeposit_Enabled'=>true,
				'General_AutoDepositCi_Enabled'=>false,
				'General_Stock_Enabled'=>true,
				'General_CartList_Enabled'=>false,
				'General_ProviderInvoice_Enabled'=>true,
				'General_ProviderInvoiceDetail_Enabled'=>true,
				'General_ProviderInvoiceDeposit_Enabled'=>true,
				'General_Rights_Enabled'=>false,

				'General_General_Enabled'=>true,
				'General_Product_Enabled'=>true,
				'General_Client_Enabled'=>true,
				'General_Provider_Enabled'=>true,
				'General_MeasureUnit_Enabled'=>true,
				'General_User_Enabled'=>true,
				'General_Config_Enabled'=>true,
				'General_Backup_Enabled'=>true,
				'General_UserMy_Enabled'=>true,
			)
		)
	)
);
?>