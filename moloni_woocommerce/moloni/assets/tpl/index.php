<?php 
	global $wpdb;	
	include("header.php");
	
	$orders = WC_moloni::getAllOrders("50");

	echo "<div id='content'>";
		echo $message;
		echo "<table class='listOrders tblMoloni'>";
			echo "<thead>";
				echo "<th></th>";
				echo "<th>Cliente</th>";
				echo "<th>Contribuinte</th>";
				echo "<th>Total</th>";
				echo "<th>Data pagamento</th>";
				echo "<th>Acção</th>";
			echo "</thead>";	
			
			foreach($orders as $order){
					echo "<form id='delInvoice".$order['id']."' method='POST' action='admin.php?page=moloni_settings&action=genInvoice&delete=permission'>";
					echo "<input type='hidden' name='orderID' value='".$order['id']."'>";
					echo "</form>";
					
					echo "<form id='genInvoice".$order['id']."' method='POST' action='admin.php?page=moloni_settings&action=genInvoice'>";
					echo "<input type='hidden' name='orderInfo' value='".base64_encode(serialize($order))."'>";
					echo "<input type='hidden' name='orderID' value='".$order['id']."'>";
					echo "<tr>";
						echo "<td><a href='post.php?post=".$order['id']."&action=edit'>#".$order['id']."</a></td>";
						echo ("<td>".$order['info']['_billing_first_name']." ".$order['info']['_billing_last_name']."</td>");
						echo ("<td>".$order['info'][VAT_FIELD]."</td>");
						echo "<td>".$order['info']['_order_total']."</td>";
						echo ("<td>".$order['info']['_completed_date']);
						echo"</td>";
						echo '<td style="width: 150px">';
						echo '<a class="actionButton" style="width: 60px" href="admin.php?page=moloni_settings&action=genInvoice&id='.$order['id'].'&delete=permission">Limpar</a>';
						echo ' <a class="actionButton" style="width: 60px" href="admin.php?page=moloni_settings&action=genInvoice&id='.$order['id'].'">Gerar</a>';
						echo '</td>';
					echo "</tr>";
					echo "</form>";
			}
			if(count($orders) == 0){
				echo "<tr><td colspan='10'>Sem facturas por gerar!</td></tr>";
			}
		
		echo "</table>";
		
	echo "</div>";
?>