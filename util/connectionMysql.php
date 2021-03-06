<?php
define("MYSQL_CONN_ERROR", "Unable to connect to database.");
// Ensure reporting is setup correctly
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once '../util/constantes.php';

class BancoMysql{
	private $nome_banco;
	private $nome_usuario;
	private $senha_banco;
	private $host_banco;
	private $conexao_banco;
	private $status_conexao = true;
	private $status_login = false;
	function __construct(){
		
	}

	public function getConexaoBanco(){
		return $this->conexao_banco;
	}
	
	public function getStatusConexao(){
		try {
			$this->connect();
			return $this->status_conexao;
		} catch (Exception $e) {
			echo "Falha na Conexão com Base de Dados" .$e->getMessage();
			throw $e;
			return false;
		}
	}

	public function getStatusLogin(){
		return $this->status_login;
	}


	public function connect(){
		$this->nome_banco = NOME_BANCO;
		$this->nome_usuario = NOME_USUARIO;
		$this->senha_banco = SENHA_BANCO;
		$this->host_banco = HOST_BANCO;
			
		try{

			$this->conexao_banco = new mysqli($this->host_banco,$this->nome_usuario,$this->senha_banco);
			$this->conexao_banco->set_charset("utf8");
			$this->conexao_banco->select_db($this->nome_banco);
			$this->status_conexao = true;
		}
		catch(mysqli_sql_exception $e){
			$this->status_conexao = false;
			throw $e;
		}
	}


}

?>