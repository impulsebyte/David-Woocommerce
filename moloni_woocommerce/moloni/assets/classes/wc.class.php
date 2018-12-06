<?php 
	class WC_moloni{

		public static function getAllOrders($qty = 50){
			$orderdata = array();
			$args = array(
				'post_type'			=> 'shop_order',
				'post_status' 		=> array("wc-processing", "wc-completed"),
				'posts_per_page' 	=> $qty,
				'orderby' 	=> 'date',
				'order' 	=> 'DESC',		
								
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => '_moloni_sent',
						'compare' => 'NOT EXISTS'
					),
					array(
						'key' => '_moloni_sent',
						'value' => '0',
						'compare' => '='
					)				
				),
			);
			$my_query = new WP_Query($args);
			$customer_orders = $my_query->posts;
			
			foreach ($customer_orders as $customer_order) {
			 
			 $order = new WC_Order();
			 $order->populate($customer_order);
			 			  
			 /*
			 $shipping 	=  $order->get_shipping_address_1();
			 $billing 	=  $order->get_billing_address_1();
			 $items 	=  $order->get_items();
			 foreach ( $items as $item ) {
				$products[] = $item;	
			 }*/
			 
			 $meta = self::getOrderMeta($order->get_order_number());
			
			if(!isset($meta["_moloni_sent"]) || $meta["_moloni_sent"] == "0"){
				$orderdata[] = array( "info" => $meta, "id" => $order->get_order_number());
			}
							
			}

			return($orderdata);
		}
	
		public static function getOrderMeta($postID = ''){	
			global $wpdb;
			if($postID <> ''){
				$results = $wpdb->get_results("SELECT * FROM ".TABLE_PREFIX."postmeta WHERE post_id = '$postID'", ARRAY_A);
				foreach($results as $user_r){
					$userInfo[ (string)$user_r['meta_key'] ] = $user_r['meta_value'];
				}
				return($userInfo);
			}else{
				echo "Utilizador não encontrado no Woocommerce!";
			}
		}
		
		public static function getCustomFields(){
			global $wpdb;
			$results = $wpdb->get_results("SELECT DISTINCT meta_key FROM ".TABLE_PREFIX."postmeta ORDER BY `".TABLE_PREFIX."postmeta`.`meta_key` ASC", ARRAY_A);
			$customfields = array();
			foreach($results as $custom_r){
				$customfields[] = $custom_r;
			}
			return($customfields);
		}

	}

?>