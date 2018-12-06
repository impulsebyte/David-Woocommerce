<?php 

class start{
	
	const version = 1.00;
		
	public static function login(){
			global $wpdb;
						
			if(trim($_POST['user']) <> '' AND  trim($_POST['pass']) <> ''){
				$login = cURL::login(trim($_POST['user']), trim($_POST['pass']));
				if($login){
					moloniDB::setTokens($login["access_token"], $login["refresh_token"]);
				}else{
					$error = "Ups, ocorreu um erro!";
				}
			}
			
			if($_POST['action'] == 'registarAlteracoes'){
				foreach($_POST['opt'] as $key => $val){
					$check_row = $wpdb->get_row("SELECT * FROM moloni_api_config WHERE config = '".$key."'");
					if($check_row){
						$wpdb->query("UPDATE moloni_api_config SET selected = '".$val."' WHERE config = '".$key."'");
					}else{
						$wpdb->query("INSERT INTO `moloni_api_config`(config, description, selected) VALUES('".$key."', '', '".$val."')");
					}
				}
			}
			
			$dbInfo = moloniDB::getInfo();
			if($dbInfo['refresh_token']<> ""){
				define("LOGGED", TRUE);
			}else{
				define("LOGGED", FALSE);
			}
			
			if(!LOGGED){
				self::loginForm($error);
				return(FALSE);
			}else{
				moloniDB::refreshTokens();
				moloniDB::defineValues();
				if(defined('COMPANY_ID')){
					moloniDB::defineConfigs();
					return(TRUE);
				}else{
					if(isset($_GET['company_id'])){
						$wpdb->query("UPDATE moloni_api SET company_id = '".$_GET['company_id']."' WHERE id = ".SESSION_ID."");
						moloniDB::defineValues();
						moloniDB::defineConfigs();
						return(TRUE);
					}else{
						self::companiesForm();
						return(FALSE);
					}
				}
				
			}
			
		}
		
	public static function loginForm($error = FALSE){
			echo "<div id='formLogin'>"; 
			echo "<a href='https://moloni.pt/dev/' target='_BLANK'> <img src='https://www.moloni.pt/_imagens/_tmpl/bo_logo_topo_01.png' width='300px'> </a>
			<hr> <form id='formPerm' method='POST' action='admin.php?page=moloni_settings'><table>";
			echo "<tr> <td><label for='username'>Utilizador/Email</label> </td><td><input type='text' name='user'></td></tr>";
			
			
			echo "<tr> <td><label for='password'>Password</label></td><td><input type='password' name='pass'></td></tr>";
			if($error){
				echo "<tr> <td></td><td style='text-align: center;'> Utilizador/Password Errados</td></tr>";
			}
			echo "<tr> <td></td><td><input type='submit' name='submit' value='login'><span class='goRight power'>Powered by: Moloni API</span></td></tr>";
			echo "</table></form></div>";
		}
		
	public static function companiesForm(){
		$companies = base::selectCompanies();
		echo "<div class='outBoxEmpresa'>";
		foreach($companies as $key => $company)
		{
			echo '<div class="caixaLoginEmpresa" onclick=" window.location.href=\'admin.php?page=moloni_settings&company_id='.$company["company_id"].'\' " title="Login/Entrar '.$company["name"].'">';
			echo '<div class="caixaLoginEmpresa_logo">';
			echo '		<span>';
			if (trim($company["image"])<>"") echo '<img src="https://www.moloni.pt/_imagens/?macro=imgAC_iconeEmpresa_s2&amp;img='.$company["image"].'" alt="'.$company["name"].'" style="margin:0 10px 0 0; vertical-align:middle;">';
			echo '		</span>';
			echo '	</div>';
			echo '	<span class="t14_b">'.$company["name"].'</span>';
			echo '	<br>'.$company["address"].'';
			echo '	<br>'.$company["zip_code"].'';
			echo '	<p><b>Contribuinte</b>: '.$company["vat"].'</p></div>';
		}
		echo "</div>";
	}

}
?>