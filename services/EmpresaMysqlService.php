<?php
require_once '../util/connectionMysql.php';

class EmpresaMysqlService {
	private $banco;
	function __construct() {
		$this->banco = new BancoMysql ();
		try {
			$this->banco->connect ();
		} catch ( Exception $e ) {
			echo "Falha na Conexão com Base de Dados" . $e->getMessage ();
		}
	}

	public function getEmpresa($sistema){
		$sistema = strtolower($sistema);
		$sql = "SELECT * FROM empresa WHERE LOWER(empresa.SISTEMA) = '$sistema' ORDER BY empresa.CODIGO desc LIMIT 1";
		$consulta = $this->banco->getConexaoBanco ()->query ( $sql );
		$empresa = $consulta->fetch_array ( MYSQLI_ASSOC );
		$consulta->close ();
		return $empresa;
	}
}