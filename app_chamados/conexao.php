<?php 
	
	class Conexao{

		private $host = 'localhost';
		private $dbname = 'chamados';
		private $user = 'tai';
		private $pass = '15375946';

		public function conectar(){
			try{

				$conexao = new PDO(
					"mysql:host = $this->host;dbname=$this->dbname",
					"$this->user",
					"$this->pass"
				);

				return $conexao;

			}catch(PDOException $e ){
				echo '<p>'.$e->getMessage().'</p>';
			}
		}
	}

?>