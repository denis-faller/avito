<?if(!check_bitrix_sessid()) return;?>
<?
echo CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));
?>
<p><a href="/avito/"><?=GetMessage('RICHSITE_MODULE_INSTALL_FINISHED');?></a></p>

<?=BeginNote();?><?=GetMessage('RICHSITE_MODULE_SEND_EVENT')?><?=EndNote();?>