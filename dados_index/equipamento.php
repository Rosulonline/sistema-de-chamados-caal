<?php
	include_once '../funcoes.php';
	
	$login = protegePagina();
	
	/*INICIALIZAR VARIÁVEIS*/
	$id_equipamento	= null;
	$id_usuario		= null;
	$conteudo		= "";
	$equipamento	= null;
	$componentes	= null;
	$classes 		= null;
	$form_nome		= null;
	$form_descricao	= null;
	$form_ns		= null;
	$form_mac		= null;
	$form_ip		= null;
	$form_classe	= null;
	
	
	if (isset($_GET['id_equipamento'])) {
		$id_equipamento = $_GET['id_equipamento'];
		$equipamento = retorna_dados_equipamento($id_equipamento);
	} else if (isset($_GET['id_usuario'])) {
		$id_usuario = $_GET['id_usuario'];
		$classes = retorna_classes();
	}
	
	if (isset($id_equipamento)) {
		$conteudo .= "<script>";
		$conteudo .= "	$(function() {";
		$conteudo .= "		$( '.button-detalhar-equipamento' ).button({";
		$conteudo .= "			icons: {";
		$conteudo .= "				primary: 'ui-icon-pencil'";
		$conteudo .= "			}";
		$conteudo .= "		}).click(function(){";
		$conteudo .= "			window.location = $(this).attr('href')";
		$conteudo .= "		});";
		$conteudo .= "	});";
		$conteudo .= "</script>";
		$conteudo .= "Nome: <a>" . $equipamento['E_nome'] . "</a> <br/>";
	
		if ($equipamento['C_id'] == 1) {
			$componentes = retorna_componentes_computador($id_equipamento);
			$conteudo .= "Descrição:<a>";
			if (count($componentes) > 0 && $componentes != null) {
				for ($i = 0; $i < count($componentes); $i++) {
					$conteudo .= $componentes[$i]['C_nome'] . ": " . $componentes[$i]['E_nome'] . " " . $componentes[$i]['E_descricao'];
					$conteudo .= "<br/>";
				}
			} else {
				$conteudo .= "-";
			}
	
			$conteudo .= "</a> <br/>";
		} else {
			$conteudo .= "Descrição: <a>" . nl2br($equipamento['E_descricao']) . "</a> <br/>";
		}
		$conteudo .= "Número de série: <a>" . $equipamento['E_ns'] . "</a> <br/>";
		$conteudo .= "IP: <a>" . $equipamento['E_ip'] . "</a> <br/>";
		$conteudo .= "Tipo de equipamento: <a>" . $equipamento['C_nome'] . "</a> <br/>";
	
		if ($login == 2) {
			$conteudo .= "<button class='button-detalhar-equipamento' href='/equipamento.php?id_equipamento=" . $id_equipamento . "'>Visualizar/Editar Equipamento</button>";
		}
	
	} else if (isset($id_usuario)) {

		$conteudo .= "<style type='text/css'>";
		$conteudo .= "#adicionar-equipamento label,";
		$conteudo .= "#adicionar-equipamento input ,";
		$conteudo .= "#adicionar-equipamento textarea{";
		$conteudo .= "	display:block;";
		$conteudo .= "}";
		$conteudo .= "#adicionar-equipamento select,";
		$conteudo .= "#adicionar-equipamento textarea,";
		$conteudo .= "#adicionar-equipamento input.text{";
		$conteudo .= "	margin-bottom:12px;";
		$conteudo .= "	width:95%;";
		$conteudo .= "	padding: .4em;";
		$conteudo .= "}";
		$conteudo .= "#adicionar-equipamento fieldset{";
		$conteudo .= "	padding:0;";
		$conteudo .= "	border:0;";
		$conteudo .= "	margin-top:10px;";
		$conteudo .= "}";
		$conteudo .= "</style>";

		$conteudo .= "<form method='post' id='adicionar-equipamento' action='/novo/equipamento.php?id_usuario=" . $id_usuario . "' >";
		$conteudo .= "	<fieldset>";
		$conteudo .= "		<label for='nome'>Nome:</label>";
		$conteudo .= "		<input type='text' name='nome' id='nome' class='text ui-widget-content ui-corner-all'>";
		$conteudo .= "		<label for='descricao'>Descrição:</label>";
		$conteudo .= "		<textarea id='descricao' name='descricao' rows='8' cols='100' class='text ui-widget-content ui-corner-all'></textarea>";
		$conteudo .= "		<label for='ns'>Número de série:</label>";
		$conteudo .= "		<input type='text' name='ns' id='ns' class='text ui-widget-content ui-corner-all'>";
		$conteudo .= "		<label for='nf'>Nota fiscal:</label>";
		$conteudo .= "		<input type='text' name='nf' id='nf' class='text ui-widget-content ui-corner-all'>";
		$conteudo .= "		<label for='mac'>MAC:</label>";
		$conteudo .= "		<input type='text' name='mac' id='mac' class='text ui-widget-content ui-corner-all'>";
		$conteudo .= "		<label for='ip'>IP:</label>";
		$conteudo .= "		<input type='text' name='ip' id='ip' class='text ui-widget-content ui-corner-all'>";
		$conteudo .= "		<label for='classe'>Tipo Equipamento:</label>";
		$conteudo .= "		<select name='classe' id='classe'>";
		$conteudo .= "			<option value='0'>Selecione o tipo</option>";
		for ($i = 0; $i < count($classes); $i++) {
			$conteudo .= "			<option value='" . $classes[$i]['C_id'] . "'>" . $classes[$i]['C_nome'] . "</option>";
		}
		$conteudo .= "		</select>";
		$conteudo .= "	</fieldset>";
		$conteudo .= "</form><!-- #adicionar-equipamento -->";
	}

	echo $conteudo;
?>