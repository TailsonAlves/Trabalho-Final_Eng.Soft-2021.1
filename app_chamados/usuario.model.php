<?php 
	class Usuario{
		private $id;
		private $id_permissao;
		private $nome;
		private $email;
		private $senha;


		public function __get($atributo){
			return $this->$atributo;
		}

		public function __set($atributo, $valor){
			$this->$atributo = $valor;
		}
	}

?>