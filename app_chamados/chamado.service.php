<?php 

	class ChamadoService{
		
		private $conexao;
		private $chamado;
		private $inicio;
		private $item_pag;

		public function __construct(Conexao $conexao, Chamado $chamado){
			$this->conexao = $conexao->conectar();
			$this->chamado = $chamado;
		}	

		//CRUD

		public function inserir(){ //create
			$query = 'insert into tb_chamado (descricao,id_solicitante,local)values(:tarefa,:id_solicitante,:local)';

			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':tarefa',$this->chamado->__get('descricao'));
			$stmt->bindValue(':id_solicitante',$this->chamado->__get('id_solicitante'));
			$stmt->bindValue(':local',$this->chamado->__get('local'));
			$stmt->execute();
		}

		public function recuperar($inicio, $item_pag){ //read
			$query = "
				select 
					c.id, s.status, c.descricao, c.id_solicitante, c.local, c.dt_cadastro, u.nome 
				from 
					tb_chamado as c 
					left join tb_status as s on (c.id_status = s.id)
					left join tb_usuarios as u on(c.id_solicitante = u.id)
				where id_tecnico IS null AND id_status = 3 AND exclusao = 0
				ORDER BY id
				LIMIT $inicio,$item_pag
			";	
			$stmt = $this->conexao->prepare($query);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		public function numero_registros($op){ //read
			if ($op == 1) {
				$query = "
				select 
					c.id, s.status, c.descricao, c.id_solicitante, c.local, c.dt_cadastro, u.nome 
				from 
					tb_chamado as c 
					left join tb_status as s on (c.id_status = s.id)
					left join tb_usuarios as u on(c.id_solicitante = u.id)
				where id_tecnico IS null 
			";	
				$stmt = $this->conexao->prepare($query);
			}else if($op == 2){
				$query = '
				select 
					c.id, s.status, c.descricao, c.id_solicitante, c.local, c.dt_cadastro, u.nome 
				from 
					tb_chamado as c 
					left join tb_status as s on (c.id_status = s.id)
					left join tb_usuarios as u on(c.id_solicitante = u.id)
				where id_tecnico = :solicitante AND exclusao = 0
				';
				$stmt = $this->conexao->prepare($query);
				$stmt->bindValue(':solicitante',$_SESSION['id_user']);
			}else if($op == 3){
				$query = '
				select 
					c.id, s.status, c.descricao, c.id_solicitante, c.local, c.dt_cadastro, u.nome 
				from 
					tb_chamado as c 
					left join tb_status as s on (c.id_status = s.id)
					left join tb_usuarios as u on(c.id_solicitante = u.id)
				where id_solicitante = :solicitante and exclusao=0
			';
				$stmt = $this->conexao->prepare($query);
				$stmt->bindValue(':solicitante',$_SESSION['id_user']);
			}else if($op == 4){
				$query = '
				select c.id, s.status, c.descricao, c.local, c.dt_cadastro, u.nome, c.id_tecnico
				from tb_chamado as c 
				left join tb_status as s on (c.id_status = s.id) 
				left join tb_usuarios as u on(c.id_solicitante = u.id) 
				WHERE id_tecnico IS NOT null AND exclusao = 0
			';
				$stmt = $this->conexao->prepare($query);
			}else if($op == 5){
				$query = "select s.status, c.id,c.descricao,c.local,c.dt_cadastro, u1.nome as usuario , u2.nome as tecnico 
				from tb_chamado as c 
				LEFT JOIN tb_usuarios as u1 ON c.id_solicitante = u1.id 
				LEFT JOIN tb_usuarios as u2 ON c.id_tecnico = u2.id 
				LEFT JOIN tb_status as s on s.id = c.id_status 
				WHERE (id_tecnico IS NOT null and exclusao=0) and 
				((id_status = :id_status and id_tecnico = :id_tecnico) or (id_status = :id_status and :id_tecnico = 0) or (:id_status = 0 and id_tecnico = :id_tecnico) ) 
				ORDER BY id
			";
				$stmt = $this->conexao->prepare($query);
				$stmt->bindValue(':id_tecnico',$this->chamado->__get('id_tecnico'));
				$stmt->bindValue(':id_status',$this->chamado->__get('id_status'));
			}
			
			
			$stmt->execute();
			$stmt->fetchAll(PDO::FETCH_OBJ);
			return $stmt->rowCount();
		}

		public function recuperarChamadosUser($inicio, $item_pag){

			$query = "
				select 
					c.id, s.status, c.descricao, c.id_solicitante, c.local, c.dt_cadastro, u.nome 
				from 
					tb_chamado as c 
					left join tb_status as s on (c.id_status = s.id)
					left join tb_usuarios as u on(c.id_solicitante = u.id)
				where id_solicitante = :solicitante and exclusao=0 LIMIT $inicio,$item_pag
			";
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':solicitante',$_SESSION['id_user']);
			
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		public function recuperarChamadosAdm($inicio,$item_pag){
			$query = "
				select 
					c.id, s.status, c.descricao, c.id_solicitante, c.local, c.dt_cadastro, u.nome 
				from 
					tb_chamado as c 
					left join tb_status as s on (c.id_status = s.id)
					left join tb_usuarios as u on(c.id_solicitante = u.id)
				where exclusao = 0 and id_tecnico = :solicitante
				ORDER BY id
				LIMIT $inicio,$item_pag
			";
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':solicitante',$_SESSION['id_user']);
			
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		public function pegarChamado(){ //update
			
			echo "dentro do servico";
			$query = 'update tb_chamado set id_tecnico = :tecnico where id = :id';
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':tecnico',$this->chamado->__get('id_tecnico'));
			$stmt->bindValue(':id', $this->chamado->__get('id'));

			return $stmt->execute();
		}

		public function marcarRealizado(){ //update
			$query = 'update tb_chamado 
			set id_status = :id_status,
			dt_atendimento = :dt_atendimento
			 where id = :id';
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':id_status', $this->chamado->__get('id_status'));
			$stmt->bindValue(':dt_atendimento', $this->chamado->__get('data_atendido'));
			$stmt->bindValue(':id', $this->chamado->__get('id'));
			return $stmt->execute();
		}

		public function remover(){ //delete
			$query = "update tb_chamado
					set exclusao = 1
					WHERE id=:id"
			;
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':id', $this->chamado->__get('id'));
			$stmt->execute();
		}
		public function recuperar_chamados_globais($inicio,$item_pag){
			$query ="select s.status, c.id,c.descricao,c.local,c.dt_cadastro, u1.nome as usuario , u2.nome as tecnico
				from tb_chamado as c
				LEFT JOIN tb_usuarios as u1 ON  c.id_solicitante = u1.id
				LEFT JOIN tb_usuarios as u2 ON  c.id_tecnico = u2.id
				LEFT JOIN tb_status as s on s.id = c.id_status
				WHERE id_tecnico IS NOT null and exclusao=0 
				ORDER BY id
				LIMIT $inicio,$item_pag";

			$stmt = $this->conexao->prepare($query);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		public function recuperar_status(){
			$query ="select *
				from tb_status
				ORDER BY id";
			$stmt = $this->conexao->prepare($query);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		public function recuperar_tecnicos(){
			$query ="select u.id,u.nome
				from tb_usuarios as u
				where u.id_permissao = 1
				ORDER BY id";
			$stmt = $this->conexao->prepare($query);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		public function filtragem_chamados($inicio,$item_pag){
			$id_tecnico = $this->chamado->__get('id_tecnico');
			$id_status = $this->chamado->__get('id_status');
			
			$query = "select s.status, c.id,c.descricao,c.local,c.dt_cadastro, u1.nome as usuario , u2.nome as tecnico 
				from tb_chamado as c 
				LEFT JOIN tb_usuarios as u1 ON c.id_solicitante = u1.id 
				LEFT JOIN tb_usuarios as u2 ON c.id_tecnico = u2.id 
				LEFT JOIN tb_status as s on s.id = c.id_status 
				WHERE (id_tecnico IS NOT null and exclusao=0) and 
				((id_status = $id_status and id_tecnico = $id_tecnico) or (id_status = $id_status and $id_tecnico = 0) or ($id_status = 0 and id_tecnico = $id_tecnico) ) 
				ORDER BY id
				LIMIT $inicio, $item_pag
			";
			$stmt = $this->conexao->prepare($query);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		public function manipular_chamado(){
			$query ='select s.status, c.id,c.descricao,c.local,c.dt_cadastro, u1.nome as usuario , u2.nome as tecnico
				from tb_chamado as c
				LEFT JOIN tb_usuarios as u1 ON  c.id_solicitante = u1.id
				LEFT JOIN tb_usuarios as u2 ON  c.id_tecnico = u2.id
				LEFT JOIN tb_status as s on s.id = c.id_status
				WHERE c.id = :id_chamado and exclusao=0 
				ORDER BY id ';

			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':id_chamado', $this->chamado->__get('id'));
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ);
		}

		public function recuperar_comentarios(){
			$query = "select c.comentario, u.nome,u.id_permissao, c.id
				FROM tb_comentarios as c 
				LEFT JOIN tb_usuarios as u ON (c.id_usuario = u.id) 
				WHERE id_chamado = :id_chamado ORDER BY c.id";

			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':id_chamado', $this->chamado->__get('id'));
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		public function comentar($id_usuario, $id_chamado, $comentario){
			$query = "insert into tb_comentarios (id_usuario, id_chamado, comentario) values($id_usuario, $id_chamado, '$comentario')";
			$stmt = $this->conexao->prepare($query);
			$stmt->execute();
		}

		public function atualizar_chamado(){
			$id_tecnico = $this->chamado->__get('id_tecnico');
			$id_status = $this->chamado->__get('id_status');

			if ($id_tecnico == 0 && $id_status != 0) { //atualizo so o status
				$query = 'update tb_chamado
							set id_status = :id_status
							where tb_chamado.id = :id_chamado';
				$stmt = $this->conexao->prepare($query);
				$stmt->bindValue(':id_status', $id_status);
				$stmt->bindValue(':id_chamado',$this->chamado->__get('id'));
			}else if($id_tecnico != 0 && $id_status == 0){ // atualizo so o tecnico
					$query = 'update tb_chamado
							set id_tecnico = :id_tecnico
							where tb_chamado.id = :id_chamado';
				$stmt = $this->conexao->prepare($query);
				$stmt->bindValue(':id_tecnico', $id_tecnico);
				$stmt->bindValue(':id_chamado',$this->chamado->__get('id'));

			}else{
				$query = 'update tb_chamado
							set id_tecnico = :id_tecnico, id_status = :id_status
							where tb_chamado.id = :id_chamado';
				$stmt = $this->conexao->prepare($query);
				$stmt->bindValue(':id_status', $id_status);
				$stmt->bindValue(':id_tecnico', $id_tecnico);
				$stmt->bindValue(':id_chamado',$this->chamado->__get('id'));
			}
			
			$stmt->execute();
		}
/*
		public function recuperarTarefasPendentes(){
			$query = '
				select 
					t.id, s.status, t.tarefa 
				from 
					tb_tarefas as t 
					left join tb_status as s on (t.id_status = s.id)
				where
					t.id_status = :id_status
			';
			$stmt = $this->conexao->prepare($query);
			$stmt->bindValue(':id_status', $this->tarefa->__get('id_status'));
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}*/
	}


?>