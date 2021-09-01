<?php
		

	if (isset($_POST['email'])  && isset($_POST['senha'])) {
		
		require "../../app_chamados/usuario.model.php";
		require "../../app_chamados/conexao.php";
		require "../../app_chamados/login.service.php";
		
		$email = addslashes($_POST['email']);
		$senha = addslashes($_POST['senha']);


		$usuario = new Usuario();
		$usuario->__set('email',$email);
		$usuario->__set('senha',$senha);

		$conexao = new Conexao();

		$login_service = new LoginService($conexao, $usuario);
		$user_obj = $login_service->recuperarUsuario();
		
		
		if (!$user_obj) {
			header("Location: login.php?login=erro");
		}else{
			session_start();
			$_SESSION['id_user'] = $user_obj->id;
			$_SESSION['id_permissao_user'] = $user_obj->id_permissao;
			$_SESSION['email_user'] = $user_obj->email;
			$_SESSION['nome_user'] = $user_obj->nome;
			header("Location: index.php");
		}
	}else{
		header("Location: login.php?login=erro");
	}
	


?>
