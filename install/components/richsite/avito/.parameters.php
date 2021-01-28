<?
CModule::IncludeModule("iblock");

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock = array();
$iblockFilter = (
	!empty($arCurrentValues['IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch())
	$arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
unset($arr, $rsIBlock, $iblockFilter);

$properties = CIBlockProperty::GetList(Array("name"=>"asc"), Array("IBLOCK_ID"=>$arCurrentValues['IBLOCK_ID']));
while ($propFields = $properties->GetNext()){
  $arProp[$propFields['CODE']] = '['.$propFields['ID'].'] '.$propFields['NAME'];
}

$arComponentParameters = array(
   "PARAMETERS" => array(
    "IBLOCK_TYPE" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("IBLOCK_TYPE"),
        "TYPE" => "LIST",
        "VALUES" => $arIBlockType,
        "REFRESH" => "Y",
    ),
    "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK_IBLOCK"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
            "REFRESH" => "Y",
    ), 
    "MORE_PHOTO" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("RICHSITE_MORE_PHOTO"),
        "TYPE" => "LIST",
        "ADDITIONAL_VALUES" => "Y",
        "VALUES" => $arProp,
        "REFRESH" => "Y",
    ),      
    "OEM" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("RICHSITE_OEM"),
        "TYPE" => "LIST",
        "ADDITIONAL_VALUES" => "Y",
        "VALUES" => $arProp,
        "REFRESH" => "Y",
    ),      
    "CONDITION" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("RICHSITE_CONDITION"),
        "TYPE" => "LIST",
        "ADDITIONAL_VALUES" => "Y",
        "VALUES" => $arProp,
        "REFRESH" => "Y",
    ),       
    "CML2_ARTICLE" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("RICHSITE_CML2_ARTICLE"),
        "TYPE" => "LIST",
        "ADDITIONAL_VALUES" => "Y",
        "VALUES" => $arProp,
        "REFRESH" => "Y",
    ),        
    "PRODUCT_SUBCATEGORY" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("RICHSITE_PRODUCT_SUBCATEGORY"),
        "TYPE" => "LIST",
        "ADDITIONAL_VALUES" => "Y",
        "VALUES" => $arProp,
        "REFRESH" => "Y",
    ),      
    "AD_TYPE" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("RICHSITE_AD_TYPE"),
        "TYPE" => "LIST",
        "ADDITIONAL_VALUES" => "Y",
        "VALUES" => $arProp,
        "REFRESH" => "Y",
    ),
    "CONTACT_PHONE" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("RICHSITE_CONTACT_PHONE"),
        "TYPE" => "LIST",
        "ADDITIONAL_VALUES" => "Y",
        "VALUES" => $arProp,
        "REFRESH" => "Y",
    ),
    "ADDRESS" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("RICHSITE_ADDRESS"),
        "TYPE" => "LIST",
        "ADDITIONAL_VALUES" => "Y",
        "VALUES" => $arProp,
        "REFRESH" => "Y",
    ),   
    "QUANTITY_ALLOW" => array(
        "NAME" => GetMessage("RICHSITE_QUANTITY_ALLOW"),
        "TYPE" => "CHECKBOX",
    ),  
    "SAVED_FILE" => array(
        "NAME" => GetMessage("RICHSITE_SAVED_FILE"),
        "TYPE" => "CHECKBOX",
    ),   
   )
);
?>