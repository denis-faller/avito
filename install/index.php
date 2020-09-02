<?
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class richsite_avito extends CModule
{
	var $MODULE_ID = "richsite.avito";
	var $MODULE_NAME = "Выгрузка запчастей на avito";
	
	function richsite_avito(){
		$this->PARTNER_NAME = "richsite";
		$this->PARTNER_URI = "https://www.1c-bitrix.ru";
		
		$arModuleVersion = array();
		include("version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		
		$this->MODULE_NAME = GetMessage("RICHSITE_AVITO_MODULE_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("RICHSITE_AVITO_MODULE_INSTALL_DESCRIPTION");
	}
	
	
	function InstallDB($install_wizard = true)
	{
		global $DB, $DBType, $APPLICATION;
		RegisterModule($this->MODULE_ID);
		
		return true;
	}

	function UnInstallDB($arParams = Array())
	{
		global $DB, $DBType, $APPLICATION;
		UnRegisterModule($this->MODULE_ID);
		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles()
	{
            CopyDirFiles(dirname(__FILE__)."/avito", $_SERVER["DOCUMENT_ROOT"]."/avito", false, true);
            $res = CopyDirFiles(dirname(__FILE__)."/components/richsite/avito", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/richsite/avito", false, true);
            return $res;
	}

	function InstallPublic()
	{
	}

	function UnInstallFiles()
	{
            if(file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/richsite/avito")){
                DeleteDirFilesEx("/avito");
                DeleteDirFilesEx("/bitrix/components/richsite/avito");
            }
            return true;
	}
		
	function DoInstall()
	{
		global $DB, $APPLICATION, $step;
		$this->InstallFiles();
		$this->InstallDB(false);
		$this->InstallEvents();
		$this->InstallPublic();
		
		$APPLICATION->IncludeAdminFile(GetMessage("RICHSITE_AVITO_MODULE_INSTALL_TITLE"), dirname(__FILE__)."/step1.php");

	}
		
	function DoUninstall()
	{
		global $DB, $APPLICATION, $step;
		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();
		$APPLICATION->IncludeAdminFile(GetMessage("RICHSITE_AVITO_MODULE_UNINSTALL_TITLE"), dirname(__FILE__)."/unstep1.php");
	}
}
?>