<?php 

global $wpdb;	

echo "<br><input type='button' onclick='jQuery(\"#optbox\").toggle(300);' value='Opções'/>";

echo "<form method='POST' action='' id='formOpcoes'>";
echo "<div id='optbox' style='display:none'><ul class='listform'>";

	$docSets = moloniBasics::documentSets();
	$exemptionReasons = moloniBasics::exemptionReasons();
	$maturityDates = moloniBasics::maturityDates();
	$measureUnits = moloniBasics::measurementUnits();
	$payMethds = moloniBasics::paymentMethods();
	$customFields = WC_moloni::getCustomFields();
			
			echo "<li><label>Série de documento:</label>";
			
			echo "<select name='opt[document_set_id]' id='docSet' class='inputOut'>";
				foreach($docSets as $docSet){
					echo "<option value='$docSet[document_set_id]' ".(DOCUMENT_SET_ID == $docSet['document_set_id'] ? "selected" : "").">$docSet[name]</option>"; 
				}
			echo "</select></li>";
		
			echo "<li><label>Razão de Isenção:</label>";
			echo "<select name='opt[exemption_reason]' id='razaoIsencao' class='inputOut'>";
				echo "<option value=''>Nenhuma</option>";
					foreach($exemptionReasons as $exemReas){
						echo "<option value='$exemReas[code]' ".(EXEMPTION_REASON == $exemReas['code'] ? "selected" : "").">$exemReas[name]</option>"; 
					}
			echo "</select></li>";
		
			echo "<li><label>Razão de Isenção de Portes:</label>";
			echo "<select name='opt[exemption_reason_shipping]' id='razaoIsencao' class='inputOut'>";
				echo "<option value=''>Nenhuma</option>";
					foreach($exemptionReasons as $exemReas){
						echo "<option value='$exemReas[code]' ".(EXEMPTION_REASON_SHIPPING == $exemReas['code'] ? "selected" : "").">$exemReas[name]</option>"; 
					}
			echo "</select></li>";
		
			echo "<li><label>Prazo de Pagamento:</label>";
			echo "<select name='opt[maturity_date]' id='prazoVencimento' class='inputOut'>";
					foreach($maturityDates as $maturity){
						echo "<option value='$maturity[maturity_date_id]' ".(MATURITY_DATE == $maturity['maturity_date_id'] ? "selected" : "").">$maturity[name]</option>"; 
					}
			echo "</select></li>";
		
			if(defined('COMPANY_ID')){
				echo "<li><label>Método de pagamento:</label>";
				echo "<select name='opt[payment_method]' id='paymentMethod' class='inputOut'>";
					foreach($payMethds as $payMethd){
						echo "<option value='$payMethd[payment_method_id]' ".(PAYMENT_METHOD == $payMethd['payment_method_id'] ? "selected" : "").">$payMethd[name]</option>"; 
					}
				echo "</select></li>";
			}
		
			if(defined('COMPANY_ID')){
				echo "<li><label>Unidade de Medida:</label>";
				echo "<select name='opt[measure_unit]' id='measureUnit' class='inputOut'>";
					foreach($measureUnits as $measureUnit){
						echo "<option value='$measureUnit[unit_id]' ".(MEASURE_UNIT == $measureUnit['unit_id'] ? "selected" : "").">$measureUnit[name]</option>"; 
					}
				echo "</select></li>";
			};
		
			echo "<li><label>Estado do documento:</label>";
			echo "<select name='opt[document_status]' class='inputOut'>";
				echo "<option value='0' ".(DOCUMENT_STATUS == "0" ? "selected" : "").">Rascunho</option>";
				echo "<option value='1' ".(DOCUMENT_STATUS == "1" ? "selected" : "").">Fechado</option>";
			echo "</select></li>";
		
			echo "<li><label>Tipo de documento:</label>";
			echo "<select name='opt[document_type]' class='inputOut'>";
				echo "<option value='invoices' ".(DOCUMENT_TYPE == "invoices" ? "selected" : "").">Factura</option>";
				echo "<option value='invoiceReceipts' ".(DOCUMENT_TYPE == "invoiceReceipts" ? "selected" : "").">Factura/Recibo</option>";
				echo "<option value='billsOfLading' ".(DOCUMENT_TYPE == "billsOfLading" ? "selected" : "").">Guia de Transporte</option>";
				echo "<option value='purchaseOrder' ".(DOCUMENT_TYPE == "purchaseOrder" ? "selected" : "").">Nota de Encomenda</option>";
			echo "</select></li>";
		
			echo "<li><label>Ref. Clientes:</label>";
			echo "<input type='text' name='opt[client_prefix]' value='".CLIENT_PREFIX."' class='inputOut'>";
	
			echo "<li><label>Ref. Artigos:</label>";
			echo "<input type='text' name='opt[product_prefix]' value='".PRODUCT_PREFIX."' class='inputOut'>";
	
			echo "<li><label>Contribuinte</label>";
			echo "<select name='opt[vat_field]' id='field' class='inputOut'>";
				foreach($customFields as $field){
					echo "<option value='".$field['meta_key'] ."' ".(VAT_FIELD == $field['meta_key'] ? "selected" : "").">";
						echo $field['meta_key'];
					echo "</option>"; 
				}
			echo "</select></li>";
	
			#echo "<li><label>Actualizar cons. final:</label>";
			#echo "<select name='opt[update_final_consumer]' class='inputOut'>";
			#	echo "<option value='0' ".(UPDATE_FINAL_CONSUMER == "0" ? "selected" : "").">Não</option>";
			#	echo "<option value='1' ".(UPDATE_FINAL_CONSUMER == "1" ? "selected" : "").">Sim</option>";
			#echo "</select></li>";
	
			echo "<li><label>Informação de envio:</label>";
			echo "<select name='opt[shipping_info]' class='inputOut'>";
				echo "<option value='0' ".(SHIPPING_INFO == "0" ? "selected" : "").">Não</option>";
				echo "<option value='1' ".(SHIPPING_INFO == "1" ? "selected" : "").">Sim</option>";
			echo "</select></li>";
			
			echo "<li><label>Enviar email:</label>";
			echo "<select name='opt[email_send]' class='inputOut'>";
			echo "<option value='0' ".(EMAIL_SEND == "0" ? "selected" : "").">Não</option>";					
			echo "<option value='1' ".(EMAIL_SEND == "1" ? "selected" : "").">Sim</option>";
			echo "</select></li>";


			echo "<li><label>Criar documento quando completado:</label>";
			echo "<select name='opt[invoice_auto]' class='inputOut'>";
			echo "<option value='0' ".(INVOICE_AUTO == "0" ? "selected" : "").">Não</option>";					
			echo "<option value='1' ".(INVOICE_AUTO == "1" ? "selected" : "").">Sim</option>";
			echo "</select></li>";
			
			echo "<li><label></label><a href='admin.php?page=moloni_settings&action=genInvoice&deleteAll=permission'>Limpar encomendas pendentes</a></li>";
				
				
				
			
		
	echo "</ul><br><hr><br>";	
	echo "<a href='#' class='actionButton' onclick='document.getElementById(\"formOpcoes\").submit();'>Guardar Alterações</a>";
	echo "<input type='hidden' value='registarAlteracoes' name='action'>";
	echo "</form>";
			
	echo "</ul></div>";
			
			
			
?>

<script>
	jQuery('.msgAlertaForms').delay(2000).fadeOut('slow');
</script>