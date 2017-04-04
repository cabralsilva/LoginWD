<?php
include '../util/constantes.php';
include '../util/funcoesWS.php';
include '../util/funcoes.php';
include '../services/EmpresaMysqlService.php';
// $dom = new DOMDocument("1.0", "UTF-8");
// $dom->preserveWhiteSpace = false;
// $dom->formatOutput = true;

// $root = $dom->createElement("DNS");
	
	
// $ip = $dom->createElement("IP", gethostbyname($_GET["domain"]));
// $domain = $dom->createElement("DOMINIO", $_GET["domain"]);
// $root->appendChild($ip);
// $root->appendChild($domain);
// $dom->appendChild($root);

// echo utf8_encode($dom->saveXML());



if (isset ( $_POST ["usr"] ) && isset ( $_POST ["pwd"] ) && isset($_POST["system"])) {
	login ( $_POST ["usr"], $_POST ["pwd"], $_POST["system"] );
} else {
	http_response_code ( 404 );
	die ();
}
function login($usr, $pwd, $system) {
	$arrayLogin = array (
			'system' => $system,
			'usr' => $usr,
			'pwd' => $pwd 
	);
	
	$objJson = json_encode ( $arrayLogin );
	$return = sendWsJson ( $objJson, UrlWs . "login" );
	if ($return != null) {
// 		print_r($return);
		if ($return->codStatus != 1) {
			switch ($return->codStatus) {
				case 2 :
					echo "<script> alert('Falha no Login: $return->msg.'); </script>";
					echo "<script> parent.window.location.href='" . BaseProjeto . "/".$system. "'; </script>";
					die();
					break;
				case 3 :
					echo "<script> alert('Falha no Login: $return->msg.'); </script>";
					echo "<script> parent.window.location.href='" . BaseProjeto . "/".$system. "'; </script>";
					die();
					break;
				default :
					$_conteudo = "<strong><u>Mensagem de Log Erro do site www.plander.com.br</u></strong><br><br><br>";
					$_conteudo .= "<strong>Descrição: </strong>" . $return->msg . "<br><br>";
					$_conteudo .= "<strong>Data: </strong>" . date ( 'd/M/y G:i:s' ) . "<br><br>";
					$_conteudo .= "<strong>Página Anterior: </strong>" . (isset ( $_SERVER ['HTTP_REFERER'] ) ? $_SERVER ['HTTP_REFERER'] : "Não identificada") . "<br><br>";
					$_conteudo .= "<strong>Página Atual: </strong>" . $_SERVER ['PHP_SELF'] . "<br><br>";
					$_conteudo .= "<strong>URL: </strong>" . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] . "<br><br>";
					$_conteudo .= "<strong>IP Cliente: </strong>" . $_SERVER ["REMOTE_ADDR"] . "<br><br>";
					$_conteudo .= "<strong>Browser: </strong>" . getBrowser () . "<br><br>";
					$_conteudo .= "<strong>Sistema Operacional: </strong>" . php_uname () . "<br><br>";
// 					sendEmailLog ( $_conteudo );
					echo "<script> alert('Ops, falha ($return->codStatus). Você será direcionado ao login novamente!'); </script>";
					echo "<script> parent.window.location.href='" . BaseProjeto . "/".$system. "'; </script>";
					die();
					break;
			}
		}else {
			if ($return->model->flagAcessoIpExterno != 1){
				$ems = new EmpresaMysqlService();
				$empresa = $ems->getEmpresa("ciadotapete"); //deve-se alterar para o sistema recebido no post, por enquando não há por isso foi deixado fixo
				if($empresa != null){
					$ip = "";
					if (!filter_var($empresa["DOMINIO"], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
						$pattern = '/(?:https?:\/\/)?(?:[a-zA-Z0-9.-]+?\.(?:[a-zA-Z])|\d+\.\d+\.\d+\.\d+)/';
						if(preg_match($pattern, $empresa["DOMINIO"])){//é ip dinamico
							$ip = gethostbyname($empresa["DOMINIO"]);
						}else { //não tem validação de IP
							echo "<script> alert('Acesso negado! (1).'); </script>";//NÃO POSSUI VERIFICAÇÃO POR IP 
							echo "<script> parent.window.location.href='" . BaseProjeto . "/" . $_POST["system"] . "'; </script>";
							die();
						}
					}else{//é ip fixo
						if ($empresa["DOMINIO"] == "127.0.0.1")
							$ip = $_SERVER ["REMOTE_ADDR"];
						else
							$ip = $empresa["DOMINIO"];
					}
					
					if ($ip == $_SERVER ["REMOTE_ADDR"]){
						inserirChave($usr, $system);
					}else{
						echo "<script> alert('Acesso negado! (2).'); </script>"; //POSSUI VERIFICAÇÃO POR IP MAS NÃO ESTÁ ACESSANDO DO IP CADASTRADO
						echo "<script> parent.window.location.href='" . BaseProjeto . "/" . $_POST["system"] . "'; </script>";
					}
				}else{
					echo "<script> alert('Acesso negado! (3).'); </script>"; //FALHA NA BUSCA DO IP PERMITIDO, VERIFICAR SQL
					echo "<script> parent.window.location.href='" . BaseProjeto . "/" . $system . "'; </script>";
				}
			}else{
				inserirChave($usr, $system);
			}
			die();
		}
	}else{
		echo "<script> parent.window.location.href='" . BaseProjeto . "/".$system. "'; </script>";
	}
}
	
function inserirChave($usr, $system){
	$dataAtual = new DateTime();
	$chave = chr($dataAtual->format("H") + 97) . geraSenha(9, true, true, true);
	$arrayLogin = array (
			'usuario' => $usr,
			'chave' => $chave,
			'system' => $system
	);
		
	$objJson = json_encode ( $arrayLogin );
	$return = sendWsJson ( $objJson, UrlWs . "insertLogin" );
	if ($return != null) {
		if ($return->codStatus != 1) {
			echo "<script> alert('Falha no Login: $return->msg.'); </script>";
			echo "<script> parent.window.location.href='" . BaseProjeto . "/".$system. "'; </script>";
		}else{
			echo "<script> parent.window.location.href='http://www.ibolthost2.com.br/fmi/webd#$system?script=AbreLogin&$"."cod=" . $return->model->codigo . "&$" . "key=" . $return->model->chave . "'; </script>";
		}
	}
}