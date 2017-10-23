<?php
include_once '../funcoes.php';

/**
 * VERIFICA SE É UM NOVO CHAMADO E ZERA AS VARIÁVEIS DESENECESSÁRIAS
 */
if (isset($_POST['novo']) && $_POST['novo'] == TRUE) {
	if (isset($_SESSION['equipamento'])) {
		unset($_SESSION['equipamento']);
	}

	if (isset($_SESSION['assunto'])) {
		unset($_SESSION['assunto']);
	}

	if (isset($_SESSION['descricao'])) {
		unset($_SESSION['descricao']);
	}

	if (isset($_SESSION['urgencia'])) {
		unset($_SESSION['urgencia']);
	}

	if (isset($_SESSION['responsavel'])) {
		unset($_SESSION['responsavel']);
	}
}

/**
 * VERIFICA SE É ENCAMINHADO A FILIAL VIA POST E ZERA usuario E setor
 */
if (isset($_POST['filial'])) {
	$_SESSION['filial'] = $_POST['filial'];
	if (isset($_SESSION['usuario'])) {
		unset($_SESSION['usuario']);
	}
	if (isset($_SESSION['setor'])) {
		unset($_SESSION['setor']);
	}
}

/**
 * VERIFICA SE É ENCAMINHADO O SETOR POR POST E ZERA usuario
 */
if (isset($_POST['setor'])) {
	$_SESSION['setor'] = $_POST['setor'];
	if (isset($_SESSION['usuario'])) {
		unset($_SESSION['usuario']);
	}
}

/**
 * VERIFICA SE É ENCAMINHADO O USUÁRIO POR POST ATRIBUÍ A
 * VARIÁVEL SESSION['usuario'] COM ESTE VALOR E ZERA SESSION['filial']
 * E SESSION['setor']
 */
if (isset($_POST['usuario']) && $_POST['usuario'] != 0) {
	$_SESSION['usuario'] = $_POST['usuario'];
	if (isset($_SESSION['filial'])) {
		unset($_SESSION['filial']);
	}
	if (isset($_SESSION['setor'])) {
		unset($_SESSION['setor']);
	}
}

if (isset($_POST['equipamento']) && $_POST['equipamento'] != 0) {
	$_SESSION['equipamento'] = $_POST['equipamento'];
}

if (isset($_POST['assunto'])) {
	$_SESSION['assunto'] = $_POST['assunto'];
}

if (isset($_POST['descricao'])) {
	$_SESSION['descricao'] = $_POST['descricao'];
}

if (isset($_POST['urgencia'])) {
	$_SESSION['urgencia'] = $_POST['urgencia'];
}

if (isset($_POST['responsavel'])) {
	$_SESSION['responsavel'] = $_POST['responsavel'];
}
?>