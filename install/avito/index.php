<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Выгрузка запчастей на Avito");
?>
<?$APPLICATION->IncludeComponent(
	"richsite:avito",
	"",
	Array(
	)
);?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>