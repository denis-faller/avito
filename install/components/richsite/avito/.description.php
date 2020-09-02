<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("RICHSITE_AVITO_UNLOAD_NAME"),
	"DESCRIPTION" => GetMessage("RICHSITE_UNLOAD_DESCRIPTION"),
	"ICON" => "/images/sale_basket.gif",
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "catalog",
			"NAME" => GetMessage("RICHSITE_UNLOAD_CATNAME")
		)
	),
);
?>