<?php
	session_start();

	$usuario_autenticado = false;

	$usuarios_app = array(
		array('email' => 'adm@teste.com.br', 'senha' => '123456'),
		array('email' => 'user@teste.com.br', 'senha' => 'abcd')
	);
	
	foreach ($usuarios_app as $user){
		
		if($user['email'] == $_POST['email']  && $user['senha'] == $_POST['senha']){

			$usuario_autenticado = true;

		}
	}

	if($usuario_autenticado){
			$_SESSION['autenticado'] = 'sim';
			header('Location: home.php');
	}else {
		$_SESSION['autenticado'] = 'nao';
			header('Location: index.php?login=erro');
	}
	

?>