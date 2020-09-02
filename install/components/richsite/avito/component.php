<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("catalog"))
{
	ShowError(GetMessage("RICHSITE_CATALOG_MODULE_NOT_INSTALL"));
	return;
}

if($arParams["IBLOCK_ID"] == NULL){
    ShowError(GetMessage("RICHSITE_PARAMS_IBLOCK_NOT_EXIST"));
    return;
}

if(!$USER->isAdmin()){
    if(file_exists("saved_file.xml") && !$arParams["CRON"]){
        $APPLICATION->RestartBuffer();
        $arResult["sxe"] = simplexml_load_file("saved_file.xml");
    }
    else{
        $currentUrl = (CMain::IsHTTPS()) ? "https://" : "http://";

        $currentUrl .= $_SERVER["HTTP_HOST"];
        
        if($arParams["QUANTITY_ALLOW"] == "Y"){
            $quantity_allow = true;
        }
        else{
            $quantity_allow = false;
        }

        $avito = new Avito($arParams["IBLOCK_ID"], $currentUrl, $quantity_allow, $arParams["PRODUCT_SUBCATEGORY"], 
                $arParams["AD_TYPE"], 
                $arParams["CONTACT_PHONE"], 
                $arParams["ADDRESS"], 
                $arParams["MORE_PHOTO"],
                $arParams["OEM"],
                $arParams["CONDITION"],
                $arParams["CML2_ARTICLE"]);

        $sectionsIDs = $avito->getSectionsIDs();
        
        $arResult["products"] = $avito->getProducts($sectionsIDs);
        
        if($arResult["products"] == NULL){
            ShowError(GetMessage("RICHSITE_PRODUCTS_NOT_EXIST"));
            return;
        }

        $arResult["sxe"] = $avito->getSimpleXmlElement($arResult["products"]);

        $APPLICATION->RestartBuffer();

        if($arParams["SAVED_FILE"] != NULL){
            if($arParams["SAVED_FILE"] == "Y"){
                if(!file_exists("saved_file.xml") || $arParams["CRON"]){
                    $arResult["sxe"]->asXML("saved_file.xml");
                }
            }
        }
    }
    $this->IncludeComponentTemplate();

    die;
}

?>