<?php
/* Plugin Name: Moloni
  Plugin URI: https://www.moloni.com/
  Description: Plugin moloni para Wordpress
  Version: 1.5
  Author: Nuno
  Author URI: https://www.moloni.com/
  License: GPLv2 or later
 */
function moloni_activation()
{
    global $wpdb;
    define("TP", $wpdb->prefix);

    $wpdb->query("CREATE TABLE IF NOT EXISTS `moloni_api`( 
		  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
		  main_token VARCHAR(100), 
		  refresh_token VARCHAR(100), 
		  client_id VARCHAR(100), 
		  client_secret VARCHAR(100), 
		  company_id INT,
		  dated TIMESTAMP default CURRENT_TIMESTAMP
		 ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;");

    $wpdb->query("CREATE TABLE IF NOT EXISTS `moloni_api_config`( 
			  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
			  config VARCHAR(100), 
			  description VARCHAR(100), 
			  selected VARCHAR(100), 
			  changed TIMESTAMP default CURRENT_TIMESTAMP
			 ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;");

    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('document_set_id', 'Escolha uma Série de Documentos para melhor organização')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('exemption_reason', 'Escolha uma Isenção de Impostos para os produtos que não têm impostos')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('exemption_reason_shipping', 'Escolha uma Isenção de Impostos para os portes que não têm impostos')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('payment_method', 'Escolha um metodo de pagamento por defeito')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('measure_unit', 'Escolha a unidade de medida a usar')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('maturity_date', 'Prazo de Pagamento')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('document_status', 'Escolha o estado do documento (fechado ou em rascunho)')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('document_type', 'Escolha o tipo de documentos que deseja emitir')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('client_prefix', 'Prefixo da referência do cliente')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('product_prefix', 'Prefixo da referência do produto')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('update_final_consumer', 'Actualizar consumidor final')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('shipping_info', 'Informação de envio')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('vat_field', 'Número de contribuinte')");
    $wpdb->query("INSERT INTO `moloni_api_config`(config, description) VALUES('email_send', 'Enviar email')");
}

register_activation_hook(__FILE__, 'moloni_activation');
function moloni_deactivation()
{
    global $wpdb;
    $wpdb->query("DROP TABLE moloni_api");
    $wpdb->query("DROP TABLE moloni_api_config");
}

register_deactivation_hook(__FILE__, 'moloni_deactivation');



add_action('admin_menu', 'moloni_plugin_settings');
function moloni_plugin_settings()
{
    add_menu_page('Moloni', 'Moloni', 'administrator', 'moloni_settings', 'moloni_display_settings');
}

add_action('add_meta_boxes', 'moloni_add_meta_box');
function moloni_add_meta_box($post)
{
    add_meta_box('moloni_add_meta_box', 'Moloni', 'make_invoice_html', 'shop_order', 'side', 'core');
}

add_action('woocommerce_order_status_completed', 'moloni_create_document');
function moloni_create_document($order_id)
{
    ob_start();

    $_REQUEST['id'] = $order_id;

    global $wpdb;
    define("TABLE_PREFIX", $wpdb->prefix);

    $schema = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
    $full = explode("?", $schema . "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    define("FULL_URL", $full[0]);

    include("assets/classes/wc.class.php");
    include("assets/classes/start.class.php");
    include("assets/classes/curl.class.php");
    include("assets/classes/base.class.php");
    include("assets/classes/entities.class.php");
    include("assets/classes/products.class.php");

    if (start::login() && INVOICE_AUTO) {
        try {
            include("assets/tpl/genInvoice.php");
        } catch (Exception $e) {
            
        }
    }

    $result = ob_get_contents();
    ob_end_clean();
}

function make_invoice_html($post)
{
    if ($post->post_status == "wc-processing" || $post->post_status == "wc-completed") {
        $meta = get_post_meta($post->ID);
        if (isset($meta['_moloni_sent'][0])) {
            echo "O documento já foi gerado no moloni<br>";
            echo '	<div style="height: 24px; margin-top: 10px;">
						<a type="button" class="button button-primary" target="_BLANK" style="float:right" href="admin.php?page=moloni_settings&action=getInvoice&id=' . $meta['_moloni_sent'][0] . '">
							Ver documento
						</a>
						';
            echo '	<a type="button" class="button" target="_BLANK" style="float:left" href="admin.php?page=moloni_settings&action=genInvoice&id=' . $post->ID . '">
							Gerar novamente
						</a>
						</div>';
        } else {
            echo '	<div style="height: 24px">
						<a type="button" class="button button-primary" target="_BLANK" style="float:right" href="admin.php?page=moloni_settings&action=genInvoice&id=' . $post->ID . '">
							Gerar documento moloni
						</a>
						</div>';
        }
    } else {
        echo "A encomenda tem que ser dada como paga para poder ser gerada.";
    }
}

function moloni_display_settings()
{
    error_reporting(1);
    global $wpdb;
    define("TABLE_PREFIX", $wpdb->prefix);

    $schema = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
    $full = explode("?", $schema . "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    define("FULL_URL", $full[0]);

    include("assets/styles/style.php");

    include("assets/classes/wc.class.php");
    include("assets/classes/start.class.php");
    include("assets/classes/curl.class.php");
    include("assets/classes/base.class.php");
    include("assets/classes/entities.class.php");
    include("assets/classes/products.class.php");

    if (start::login()) {

        switch ($_REQUEST["action"]) {


            case "genInvoice":
                include("assets/tpl/genInvoice.php");
                break;

            case "getInvoice":

                $values['company_id'] = COMPANY_ID;
                $values["document_id"] = $_REQUEST["id"];

                $meInfo = cURL::simple("companies/getOne", $values);
                $invoice = cURL::simple("documents/getOne", $values);

                if ($invoice['status'] == 1) {
                    $url = cURL::simple("documents/getPDFLink", $values);
                    header("Location: " . $url['url']);
                } else {
                    switch ($invoice['document_type']['saft_code']) {
                        case "FT" :
                            $docName = "Fatura";
                            $typeName = "Faturas";
                            break;
                        case "FR" :
                            $docName = "Fatura/Recibo";
                            $typeName = "FaturasRecibo";
                            break;
                        case "GT" :
                            $docName = "Guia de Transporte";
                            $typeName = "GuiasTransporte";
                            break;
                        case "NEF" :
                            $docName = "Nota de Encomenda";
                            $typeName = "NotasEncomenda";
                            break;
                    }

                    header("Location: https://moloni.com/" . $meInfo['slug'] . "/" . $typeName . "/showDetail/" . $invoice['document_id']);
                }
                exit;

                break;
        }
        include("assets/tpl/index.php");
        include("assets/tpl/options.php");
    }
}

?>