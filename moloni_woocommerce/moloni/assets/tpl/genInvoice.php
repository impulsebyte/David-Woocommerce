<?php 
	global $wpdb;	
	
		
	if($_GET['delete'] == 'permission'){
		$message = "<div style='margin-bottom: 10px; font-size: 20px; margin-top: -30px; float: left; padding: 10px;'> Tem a certeza que deseja marcar o documento <a href='post.php?post=".$_REQUEST['id']."&action=edit'>#".$_REQUEST['id']."</a> como gerado? <a href='admin.php?page=moloni_settings&action=genInvoice&delete=delete&id=".$_REQUEST['id']."'>Sim</a></div>";
	}elseif($_GET['delete'] == 'delete'){
		
		$message = "<div style='margin-bottom: 10px; font-size: 20px; margin-top: -30px; float: left; padding: 10px;'>Factura marcada como gerada!</div>";
		$wpdb->query("INSERT INTO  ".TABLE_PREFIX."postmeta(post_id, meta_key, meta_value) VALUES('".$_REQUEST['id']."', '_moloni_sent', '11')");

	}elseif($_GET['deleteAll'] == 'permission'){
		$message = "<div style='margin-bottom: 10px; font-size: 20px; margin-top: -30px; float: left; padding: 10px;'> Tem a certeza que deseja marcar TODOS os documentos como gerados? <a href='admin.php?page=moloni_settings&action=genInvoice&deleteAll=delete'>Sim</a></div>";
	}elseif($_GET['deleteAll'] == 'delete'){
		$message = "<div style='margin-bottom: 10px; font-size: 20px; margin-top: -30px; float: left; padding: 10px;'>Todas as encomendas marcadas como geradas!!</div>";
		$orders = WC_moloni::getAllOrders("-1");
		foreach($orders as $order){
			$wpdb->query("INSERT INTO  ".TABLE_PREFIX."postmeta(post_id, meta_key, meta_value) VALUES('".$order['id']."', '_moloni_sent', '11')");
		}
		
	}else{

	$order = new WC_Order($_REQUEST['id']);
	
	#print_r($orderInfo);
	#print_r($orderOpt);
	
	$client = entities::getCostumerID($_REQUEST['id']);
		
	$companyMe['company_id'] = COMPANY_ID;
	$meInfo = cURL::simple("companies/getOne", $companyMe);
	unset($companyMe);
	
	$values["company_id"] 				= COMPANY_ID;
	$values["date"] 					= date("d-m-Y");
	$values["expiration_date"] 			= date("d-m-Y");
	$values["document_set_id"] 			= DOCUMENT_SET_ID;
	$values["customer_id"] 				= $client['customer_id'];
	$values["alternate_address_id"] 	= (isset($client['address_id']) ? $client['address_id'] : "");
	$values["our_reference"] 			= "";
	$values["your_reference"] 			= "#".$_REQUEST['id'];
	$values["financial_discount"] 		= "";
	$values["salesman_id"] 				= "";
	$values["salesman_commission"] 		= "";
	$values["deduction_id"] 			= "";
	$values["special_discount"] 		= "";
	$values["related_documents_notes"] 	= "";
	$values["products"] = array();
	$x = 0;
	foreach($order->get_items() as $item){
		$price = $item->get_subtotal()/$item->get_quantity();
		$taxPerItem = @($item->get_subtotal_tax()/$item->get_quantity());
		$taxRate = @round(($taxPerItem*100)/$price);
		$discount = @(100-(($item->get_total()*100)/$item->get_subtotal()));
		if($discount < 0 OR $discount > 100){
			$discount = 0;
		}
		
		$values["products"][$x]["product_id"] 		= products::getItemByRef($item);
		$values["products"][$x]["name"] 			= $item->get_name();
		$values["products"][$x]["summary"] 			= "";
		$values["products"][$x]["qtd"] 				= $item->get_quantity();
		$values["products"][$x]["price"] 			= $price;
		$values["products"][$x]["discount"] 		= ($price == 0 ? "100" : $discount);
		$values["products"][$x]["order"] 			= $x+1;
		if($taxRate > 0){
			$values["products"][$x]["taxes"] = array();
			$values["products"][$x]["taxes"][0]["tax_id"] = products::getTaxByVal($taxRate);
			$values["products"][$x]["taxes"][0]["value"] = $taxPerItem;
		}else{
			$values["products"][$x]["exemption_reason"] = EXEMPTION_REASON;
		}
		$x=$x+1;
	}

	if($order->get_shipping_total() > 0){
		
		$price = $order->get_shipping_total();
		$taxPerItem = $order->get_shipping_tax();
		$taxRate = round(($taxPerItem*100)/$price);
				
		$values["products"][$x]["product_id"] 		= products::getShipByRef("Portes", $price, $taxPerItem);
		$values["products"][$x]["name"] 			= "Portes";
		$values["products"][$x]["summary"] 			= "";
		$values["products"][$x]["qtd"] 				= "1";
		$values["products"][$x]["price"] 			= $price;
		$values["products"][$x]["discount"] 		= "";
		$values["products"][$x]["order"] 			= $x+1;
		$values["products"][$x]["exemption_reason"] = EXEMPTION_REASON_SHIPPING;
		if($taxRate > 0 ){
			$values["products"][$x]["taxes"] = array();
			$values["products"][$x]["taxes"][0]["tax_id"] = products::getTaxByVal($taxRate);
			$values["products"][$x]["taxes"][0]["value"] = $taxPerItem;
			
			if(empty($values["products"][$x]["taxes"][0]["tax_id"]) || $values["products"][$x]["taxes"][0]["tax_id"] == ''){
				$values["products"][$x]["taxes"][0]["tax_id"] = products::getTaxByVal(23);
			}
		}
	}
	

	if(SHIPPING_INFO){
		
		if($order->get_shipping_country() == "PT"){
			$codPostal = entities::zipCheck($order->get_shipping_postcode());
		}else{
			$codPostal = $order->get_shipping_postcode();
		}
			
		$values["delivery_method_id"] 			= $meInfo['delivery_method_id'];
		$values["delivery_datetime"] 			= date("d-m-Y H:i");
		$values["delivery_departure_address"]	= $meInfo['address'];
		$values["delivery_departure_city"] 		= $meInfo['city'];
		$values["delivery_departure_zip_code"]	= $meInfo['zip_code'];
		$values["delivery_departure_country"] 	= $meInfo['country_id'];
		$values["delivery_destination_address"]	= $order->get_shipping_address_1()." ".$order->get_shipping_address_2();
		$values["delivery_destination_city"] 	= $order->get_shipping_city();
		$values["delivery_destination_zip_code"]= $codPostal;
		$values["delivery_destination_country"] = general::getCountryID($order->get_shipping_country());
	
	}
	
	$values["notes"] = $order->get_customer_order_notes();
	$values["status"] = "0";
	
	if(!base::$errors){ 
		$genInvoice = cURL::simple(DOCUMENT_TYPE."/insert", $values);
	}
	
	if($genInvoice['valid'] == 1){
	
		$checkSent = $wpdb->get_row("SELECT * FROM ".TABLE_PREFIX."postmeta WHERE meta_key = '_moloni_sent' AND post_id = '".$_REQUEST['id']."'");
		if($checkSent){
			$wpdb->query("UPDATE ".TABLE_PREFIX."postmeta SET meta_value = '".$genInvoice['document_id']."' WHERE meta_key = '_moloni_sent' AND post_id = '".$_REQUEST['id']."'");
		}else{
			$wpdb->query("INSERT INTO  ".TABLE_PREFIX."postmeta(post_id, meta_key, meta_value) VALUES('".$_REQUEST['id']."', '_moloni_sent', '$genInvoice[document_id]')");
		}
		
		$message = "<div style='margin-bottom: 10px; font-size: 20px; margin-top: -30px; float: left; padding: 10px;'>Documento gerado com sucesso!</div>";

		unset($values);
		$values["company_id"] = COMPANY_ID;
		$values["document_id"] = $genInvoice["document_id"];
		$invoiceInsert = cURL::simple("documents/getOne", $values);
		
		switch(DOCUMENT_TYPE){
			case "invoices" : 		
				$docName = "Fatura";
				$typeName = "Faturas";
			break;
			case "invoiceReceipts" :
				$docName = "Fatura/Recibo";
				$typeName = "FaturasRecibo";
			break;
			case "billsOfLading" : 		
				$docName = "Guia de Transporte";
				$typeName = "GuiasTransporte";
			break;
			case "purchaseOrder" : 	
				$docName = "Nota de Encomenda";
				$typeName = "NotasEncomenda";
			break;
		}
		
		if((trim($order->get_total()) == trim($invoiceInsert['net_value'])) AND DOCUMENT_STATUS == 1){
			
			unset($values);
			$values = array();
			
			$values["company_id"] = COMPANY_ID;
			$values["document_id"] = $genInvoice["document_id"];
			$values["status"] = "1";
			$updateInv = cURL::simple(DOCUMENT_TYPE."/update", $values);
			$invoice = cURL::simple("documents/getOne", $values);
			$pdfURL =  cURL::simple("documents/getPDFLink", $values);
			unset($values);
			if(EMAIL_SEND){
				$email = $order->get_billing_email();
				$subject = "Envio de documento | Fatura ".$invoice['document_set']['name']."-".$invoice['number']." | ".date("Y-m-d");
				
				$date = explode("T", $invoice['date']);
				$date = $date[0];			
								
				$con = curl_init();
				$url = "http://plugins.moloni.com/templates/emails/invoice.txt";
				curl_setopt($con, CURLOPT_URL, $url);
				curl_setopt($con, CURLOPT_POST, FALSE);
				curl_setopt($con, CURLOPT_POSTFIELDS, FALSE);
				curl_setopt($con, CURLOPT_HEADER, false);
				curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
								 
				$mensagem = curl_exec($con);
				curl_close($con);
				
				$mensagem = str_replace('{{image}}'			, $meInfo['image'], $mensagem);
				$mensagem = str_replace('{{nome_empresa}}'	, $meInfo['name'], $mensagem);
				$mensagem = str_replace('{{data_hoje}}'		, date("Y-m-d"), $mensagem);
				$mensagem = str_replace('{{nome_cliente}}'	, $order->get_billing_first_name()." ".$order->get_billing_last_name(), $mensagem);
				$mensagem = str_replace('{{documento_tipo}}'	, $docName, $mensagem);
				$mensagem = str_replace('{{documento_numero}}'		, $invoice['document_set']['name']."-".$invoice['number'], $mensagem);
				$mensagem = str_replace('{{documento_emissao}}'		, $date, $mensagem);
				$mensagem = str_replace('{{documento_vencimento}}'	, $date, $mensagem);
				$mensagem = str_replace('{{documento_total}}'	, $invoice['net_value']."€", $mensagem);
				$mensagem = str_replace('{{documento_url}}'		, $pdfURL['url'], $mensagem);
				$mensagem = str_replace('{{empresa_nome}}'		, $meInfo['name'], $mensagem);
				$mensagem = str_replace('{{empresa_morada}}'		, $meInfo['address'], $mensagem);
				$mensagem = str_replace('{{empresa_email}}'		, $meInfo['mails_sender_address'], $mensagem);

				$headers = array(
					'Reply-To' => $meInfo['mails_sender_name']." <".$meInfo['mails_sender_address'].">"
				);

				add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

				$result = wp_mail( $email, $subject, $mensagem, $headers);				
			}
			$message .= "<a 
				href='https://moloni.com/".$meInfo['slug']."/".$typeName."/showDetail/".$invoice['document_id']."' target='_BLANK' 
				style='margin-bottom: 10px;
					font-size: 20px;
					margin-top: -30px;
					float: left;
					padding: 10px;
					padding-left: 0px;'>Ver documento</a>";
		}else{
			$message .= "<a href='https://moloni.com/".$meInfo['slug']."/".$typeName."/showUpdate/".$genInvoice['document_id']."' target='_BLANK' 
				style='margin-bottom: 10px;
					font-size: 20px;
					margin-top: -30px;
					float: left;
					padding: 10px;
					padding-left: 0px;'>Ver documento</a>";
		}
		
		
	}else{
		$message = "<div style='margin-bottom: 10px; font-size: 20px; margin-top: -30px; float: left; padding: 10px;'>Erro ao gerar documento!</div><br><pre>".print_r($genInvoice, true)."</pre>";
	}
	
	}
?>