<?php 

	
	class Chamado{
		private $id;
		private $id_status;
		private $descricao;
		private $data_cadastro;
		private $data_atendido;
		private $id_tecnico;
		private $id_solicitante;
		private $local;


		public function __get($atributo){
			return $this->$atributo;
		}

		public function __set($atributo, $valor){
			$this->$atributo = $valor;
		}
	}


?>