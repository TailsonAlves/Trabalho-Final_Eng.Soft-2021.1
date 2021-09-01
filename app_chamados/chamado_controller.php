<?php

session_start();
	require "../../app_chamados/chamado.model.php";
	require "../../app_chamados/chamado.service.php";
	require "../../app_chamados/conexao.php";
	
	

	$acao = isset($_GET['acao']) ? $_GET['acao']: $acao;


	if($acao == 'inserir'){

		$chamado = new Chamado();
		
		//inclui a tarefa no objeto tarefa
		$chamado->__set('descricao', $_POST['descricao']);
		$chamado->__set('id_solicitante', $_SESSION['id_user']);
		$chamado->__set('local', $_POST['local']);
		// recupera o objeto de conexão
		$conexao = new Conexao();
		// objeto que faz o CRUD
		$chamadoService = new ChamadoService($conexao, $chamado);
		$chamadoService->inserir();
		header('Location: novo_chamado.php?inclusao=1');

	}else if($acao == 'recuperar'){

		$chamado = new Chamado();
		$conexao = new Conexao();
		$chamadoService = new ChamadoService($conexao, $chamado);

		$chamados = $chamadoService->recuperar($inicio,$item_pag);
		$reg_total = $chamadoService->numero_registros(1);
		/*print_r("<pre>");
		print_r($reg);
		print_r("</pre>");*/

	}else if ($acao == 'recuperarAdm') {

		$chamado = new Chamado();

		$conexao = new Conexao();
		$chamadoService = new ChamadoService($conexao, $chamado);

		$chamados = $chamadoService->recuperarChamadosAdm($inicio,$item_pag);
		$reg_total = $chamadoService->numero_registros(2);	

	}else if ($acao =='recuperarUser') {

		$chamado = new Chamado();

		$conexao = new Conexao();
		$chamadoService = new ChamadoService($conexao, $chamado);

		$chamados = $chamadoService->recuperarChamadosUser($inicio,$item_pag);
		$reg_total = $chamadoService->numero_registros(3);					

	}else if ($acao =='pegarChamado') {

		$chamado = new Chamado();
		$chamado->__set('id',$_GET['id']);
		$chamado->__set('id_tecnico',$_SESSION['id_user']);
		$conexao = new Conexao();

		$chamadoService = new ChamadoService($conexao, $chamado);

		if($chamadoService->pegarChamado() ){
			header('Location: index.php?');
		}

	}else if($acao == 'remover') {
		$chamado = new Chamado();
		$chamado->__set('id', $_GET['id']);

		$conexao = new Conexao();

		$chamadoService = new ChamadoService($conexao, $chamado);
		$chamadoService->remover();

		if (isset($_GET['pag']) && $_GET['pag'] == 1){
			header('Location: index.php');
		}else{
			 header('Location: todos_chamados.php');
		}

	}else if($acao == 'marcarRealizado'){
		$chamado = new Chamado();
		$chamado->__set('id', $_GET['id']);
		$chamado->__set('id_status', 2);
		$chamado->__set('data_atendido',date("Y/m/d"));

		$conexao = new Conexao();

		$chamadoService = new ChamadoService($conexao, $chamado);
		$chamadoService->marcarRealizado();

		header('Location: index.php');
	}
	else if($acao == 'recuperar_chamados_globais'){

		$chamado = new Chamado();

		$conexao = new Conexao();
		$chamadoService = new ChamadoService($conexao, $chamado);

		$chamados = $chamadoService->recuperar_chamados_globais($inicio,$item_pag);
		$reg_total = $chamadoService->numero_registros(4);

	}else if($acao == 'manipular_chamado'){

		if (isset($_POST['comentario'])) {
			$chamado = new Chamado();
			$conexao = new Conexao();
			$chamado->__set('id',$_GET['ch']);

			$chamadoService = new ChamadoService($conexao,$chamado);
			$chamadoService->comentar($_SESSION['id_user'], $_GET['ch'],$_POST['comentario'] );
		}
		
		$chamado = new Chamado();
		$conexao = new Conexao();
		$chamado->__set('id',$_GET['ch']);

		$chamadoService = new ChamadoService($conexao,$chamado);
		
		$chamado_v = $chamadoService->manipular_chamado();
		$comentarios = $chamadoService->recuperar_comentarios();
	}else if($acao == 'alterar_chamado'){
		if ($_POST['t'] == 0 && $_POST['s'] == 0) {
			header('Location: Manipular_chamado.php?ch='.$_GET['ch']);
		}else{
			$chamado = new Chamado();
			$conexao = new Conexao();
			$chamado->__set('id',$_GET['ch']);
			$chamado->__set('id_tecnico', $_POST['t']);
			$chamado->__set('id_status',$_POST['s']);
			$chamadoService = new ChamadoService($conexao,$chamado);
			$chamadoService->atualizar_chamado();
			header('Location: Manipular_chamado.php?ch='.$_GET['ch']);
		}
	}

	

	
	if($acao2 == 'recuperar_tecnicos_status'){
		$chamado = new Chamado();
		$conexao = new Conexao();
		$chamadoService = new ChamadoService($conexao, $chamado);
		$select_tecnicos = $chamadoService->recuperar_tecnicos();
		$select_status = $chamadoService->recuperar_status();
	}

	if ($_SESSION['filtro'] != 'false' && isset($_SESSION['tecnico_f']) && isset($_SESSION['status_f'])){
		if ($_SESSION['tecnico_f'] != 0 || $_SESSION['status_f'] != 0) 
		{
			
			$tecnico_f = $_SESSION['tecnico_f'];
			$status_f = $_SESSION['status_f'];

			$chamado = new Chamado();
			$chamado->__set('id_tecnico',$tecnico_f);
			$chamado->__set('id_status',$status_f);
			$conexao = new Conexao();

			$chamadoService = new ChamadoService($conexao,$chamado);
			$chamados = $chamadoService->filtragem_chamados($inicio,$item_pag);
			$reg_total = $chamadoService->numero_registros(5);
		}
	}
	/*else if($acao == 'atualizar'){
		$tarefa = new Tarefa();
		$tarefa->__set('id',$_POST['id']);
		$tarefa->__set('tarefa',$_POST['tarefa']);

		$conexao = new Conexao(); 
		$tarefaService = new TarefaService($conexao, $tarefa);

		if ($tarefaService->atualizar()) {
			if (isset($_GET['pag']) && $_GET['pag'] == 'index'){
				header('Location: index.php');
			}else{
			 	header('Location: todas_tarefas.php');
			}
		} 	
	}else if ($acao = 'recuperarTarefasPendentes') {
		$tarefa = new Tarefa();
		$tarefa->__set('id_status', 1);

		$conexao = new Conexao();
		$tarefaService = new TarefaService($conexao, $tarefa);

		$tarefas = $tarefaService->recuperarTarefasPendentes();
	}
	*/

?>