<?php

	
	class LoginService
	{
		private $conexao;
		private $usuario;
		
		public function __construct(Conexao $conexao, Usuario $usuario)
		{
			$this->conexao = $conexao->conectar();
			$this->usuario = $usuario;
		}

		public function recuperarUsuario(){
			// consultar banco de dados
			$query = 'select * from tb_usuarios where email = :email AND senha = :senha';
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':email', $this->usuario->__get('email'));
			$stmt->bindValue(':senha', $this->usuario->__get('senha'));
			$stmt->execute();

			
			if ($stmt->rowCount()>0) {

				return $stmt->fetch(PDO::FETCH_OBJ);
			}else{
				return false;
			}

			//return $stmt->fetchAll(PDO::FETCH_OBJ);
		}


	}

?>