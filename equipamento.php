<?php
	include_once('funcoes.php');
	
	$login = protegePagina();
	
	/* DEFINIÇÃO DE VARIÁVEIS */
	$id_equipamento	= $_GET['id_equipamento']	;
	$id_filial		= NULL						;
	$id_setor		= NULL						;
	$id_usuario		= NULL						;
	$id_chamado		= NULL						;
	$aberto			= NULL						;
	$S_id			= 0							;
	$inicial		= NULL						;
	$por_pagina		= NULL						;
	$redirecionado	= FALSE						;
	/* FIM DEFINIÇÃO DE VARIÁVEIS */

	$equip = retorna_dados_equipamento($id_equipamento);

	/*
	 * REDIRECIONAMENTO DE COMPONENTE PARA COMPUTADOR
	 * 
	 * faz uma verificação para validar se é um componente e altera a informação para que seja
	 * exibido o computador do equipamento 
	 * */
	if(  verificaSeComponente($equip['C_id']) == TRUE){
		$novo_id = retorna_computador_componente($id_equipamento);
		
		if($novo_id != (null || 0 )){
			$redirecionado	= TRUE											;
			$id_equipamento	= $novo_id										;
			$equip			= retorna_dados_equipamento($id_equipamento)	;
		}
	}
	/* fim: REDIRECIONAMENTO DE COMPONENTE PARA COMPUTADOR */
		
	$classes		= retorna_classes();	
	$chamados		= retorna_chamados($id_filial, $id_setor, $id_usuario, $id_equipamento, $id_chamado,$aberto, $inicial, $por_pagina);
	$usuarios		= retorna_usuarios_equipamento($id_equipamento);
	$transferencias	= retorna_redirecionamentos_equipamento($id_equipamento);
	
	if($equip['C_nome'] == 'Computador'){
		$componentes	=	retorna_componentes_computador($equip['E_id'])	;
		$descricao		=	''												;
		$temCompomente	=	1												;
	}else{
		$descricao = $equip['E_descricao'];
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>CAAL - Chamados</title>
		<?php include_once '/dados_index/header.html'; ?>
		<!-- INÍCIO: ESTILOS PERSONALIZADOS -->
		<style type="text/css">
			#accordion-equipamento{
				float:left;
				width: 40%;
				margin: 0 1% 1% 1%;
			}
			#accordion-usuarios,
			#accordion-transferencias,
			#accordion-chamados-equipamento{
				float:left;
				min-width: 55%;
				margin: 0 1% 1% 0;
			}
			.button-atualizar,
			.componentes, 
			.add, 
			.atr{
				float:left;
				width:97%;
				display: block;
				text-align:left;
				margin: 0 1% 1% 0;
			}
			.button-atualizar{
				text-align: center;
			}
			.add, .atr{
				display: inline;
				width: 48%;
			}
		</style>
		<!-- FIM: ESTILOS PERSONALIZADOS -->
		<script>
			$(function() {
				/*TABS*/
				$( "#tabs" ).tabs({
					beforeLoad: function( event, ui ) {
						ui.jqXHR.error(function() {
							ui.panel.html(
								"Carregando..."
							);
						});
					}
				});
				/*FIM TABS*/
				$( "#accordion-equipamento" ).accordion({
					heightStyle: "content"
				});
				
				$( "#accordion-usuarios" ).accordion({
					heightStyle: "content",
					collapsible: true,
					active: true,
					change: function (e, ui) {
						$url = $(ui.newHeader[0]).children('a').attr('href');
						$.get($url, function (data) {
							$(ui.newHeader[0]).next().html(data);
						});
					}
				});
				$( "#accordion-transferencias" ).accordion({
					heightStyle: "content",
					collapsible: true,
					active: true,
					change: function (e, ui) {
						$url = $(ui.newHeader[0]).children('a').attr('href');
						$.get($url, function (data) {
							$(ui.newHeader[0]).next().html(data);
						});
					}
				});
				$( "#accordion-chamados-equipamento" ).accordion({
					heightStyle: "content",
					collapsible: true,
					active: true,
					change: function (e, ui) {
						$url = $(ui.newHeader[0]).children('a').attr('href');
						$.get($url, function (data) {
							$(ui.newHeader[0]).next().html(data);
						});
					}
				});
				$( ".button-atualizar" ).button({
				});
				$( ".componentes" ).button({
					icons: {
						primary: "ui-icon-arrowreturnthick-1-e"
					}
				});
				$( ".add" ).button({
					icons: {
						primary: "ui-icon-plus"
					}
				});
				$('.add').live('click',function(){
					url = $(this).attr("href");
					dialog_box = $('<div id="dialog-form" title="Adicionar Componente" style="display:hidden"></div>').appendTo('body');
					dialog_box.html(function(){
						$('<img src="imagens/ajax-loader.gif" >').appendTo('#dialog-form');
						dialog_box.dialog({
							height: 600,
							width: 750,
							modal: true,
							resizable: false
						});
					});
					dialog_box.load(url,{},function() {
						dialog_box.dialog({
							height: 650,
							width: 750,
							modal: true,
							resizable: false,
							buttons: {
								"Adicionar Componente":function(){
									$( "#novo-componente" ).submit();
								},
								"Cancelar": function() {
									$( this ).dialog( "close" );
								}
							},
							close: function(){					
								$("#dialog-form").remove();
							}
						});
					});
					return false;
				});
				$( ".atr" ).button({
					icons: {
						primary: "ui-icon-circle-plus"
					}
				});
				$('.atr').live('click',function(){
					url = $(this).attr("href");
					dialog_box = $('<div id="dialog-form" title="Atribuir Componente" style="display:hidden"></div>').appendTo('body');
					dialog_box.html(function(){
						$('<img src="imagens/ajax-loader.gif" >').appendTo('#dialog-form');
						dialog_box.dialog({
							height: 300,
							width: 750,
							modal: true,
							resizable: false
						});
					});
					dialog_box.load(url,{},function() {
						dialog_box.dialog({
							height: 300,
							width: 750,
							modal: true,
							resizable: false,
							buttons: {
								"Atribuir":function(){
									$( "#atribuir-componente" ).submit();
								},
								"Cancelar": function() {
									$( this ).dialog( "close" );
								}
							},
							close: function(){					
								$("#dialog-form").remove();
							}
						});
					});
					return false;
				});
				/*FIM REDIRECIONAR*/
				$( ".componentes" ).live( "click" , function(){
					url = $(this).attr("href");
					transferir = $(this).attr("link");
					dialog_box = $('<div id="dialog-form" title="Atualizar componente" style="display:hidden"></div>').appendTo('body');
					dialog_box.html(function(){
						$('<img src="imagens/ajax-loader.gif" >').appendTo('#dialog-form');
						dialog_box.dialog({
							height: 600,
							width: 750,
							modal: true,
							resizable: false
						});
					});
					dialog_box.load(url,{},function() {
						dialog_box.dialog({
							height: 600,
							width: 750,
							modal: true,
							resizable: false,
							buttons: {
								"Atualizar Componente": function() {
									$( "#atualiza-componente" ).submit();
								},
								"Mover Componente":function(){
									dialog_aux = $('<div id="dialog-form-mover" title="Transferir Componente" style="display:hidden"></div>').appendTo('body');
									dialog_aux.html(function(){
										$('<img src="imagens/ajax-loader.gif" >').appendTo('#dialog-form-mover');
										dialog_aux.dialog({
											height: 400,
											width: 750,
											modal: true,
											resizable: false
										});
					
									});
									dialog_aux.load(transferir,{},function() {
										dialog_aux.dialog({
											height: 400,
											width: 750,
											modal: true,
											resizable: false,
											buttons: {
												"Mover Equipamento":function(){
													$( "#form-transferir-componente" ).submit();
												},
												"Cancelar": function() {
													$( this ).dialog( "close" );
												}												
											},
											close: function(){
												$("#dialog-form-mover").remove();
											}
										});
									});
									$( this ).dialog( "close" );
								},
								"Remover Componente":function(){
									$( "#remove-componente" ).submit();
								},
								"Cancelar": function() {
									$( this ).dialog( "close" );
								}
							},
							close: function(){
								$("#dialog-form").remove();
							}
						});
					});
					return false;
				});
				
			});
		</script>
	</head>
	<body>
		<div id="corpo">
			<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
			<div id="conteudo">
				<div id="tabs" style="float: left; width: 100%;">
					<ul>
						<li><a href="#dados">Equipamento</a></li>
					</ul>
					<div id="dados">
						<div id="accordion-equipamento">
							<h3>Dados do Equipamento</h3>
							<div>
								<form method='post' action='../atualizar/equipamento.php?id_equipamento=<?php echo $id_equipamento; ?>'>
											

											<label for="nome">Nome:</label>
											<input type="text" name="nome" value='<?php echo $equip['E_nome']; ?>' id="nome" >
											
											<label for="classe">Tipo Equipamento:</label>
											<select name="classe" id="classe">
												<option value="">Selecione o tipo</option>
												<?php	for($i=0;$i<count($classes);$i++) { ?>
												<option <?php if($classes[$i]['C_id'] == $equip['C_id']){ echo "selected='selected'"; } ?> value="<?php echo $classes[$i]['C_id']; ?>"><?php echo $classes[$i]['C_nome']; ?></option>
												<?php	} ?>
											</select>
											
											<label for="ip">IP:</label>
											<input type="text" name="ip" id="ip" value='<?php echo $equip['E_ip']; ?>'>
											
											<?php	if(isset($componentes)){ ?>
											
											<label>Componentes: </label>
											<?php		for($i=0;$i<count($componentes);$i++){ ?>
											
											<div class="componentes" link="/dados_index/equipamento_transferir.php?id_pc=<?php echo $id_equipamento; ?>&id_componente=<?php echo $componentes[$i]['E_id']; ?>" href="/dados_index/componente.php?id_pc=<?php echo $id_equipamento; ?>&id_componente=<?php echo $componentes[$i]['E_id']; ?>"><?php echo $componentes[$i]['C_nome'].": ".$componentes[$i]['E_nome']." ".$componentes[$i]['E_descricao']; ?></div>
											<?php $descricao .= $componentes[$i]['C_nome'].": ".$componentes[$i]['E_nome']." ".$componentes[$i]['E_descricao']."\n";?>
											<?php		} ?>
											
											<input type="hidden" name="descricao" value="<?php echo $descricao; ?>">
											<?php 	}if(isset($temCompomente) && $temCompomente == 1){ ?>
											<div class="atr" href="/dados_index/equipamento_atribuir.php?id_equipamento=<?php echo $id_equipamento; ?>">Atribuir Componente</div>
											<div class="add" href="/dados_index/componente.php?id_pc=<?php echo $id_equipamento; ?>">Adicionar Componente</div>
											<?php 	}else{ ?>
											<label for="descricao">Descrição:</label>
											<textarea id="descricao" name="descricao" rows='9' cols='50'><?php echo $equip['E_descricao']; ?></textarea>
											<?php 	} ?>
											
											<label for="ns">Número de Série:</label>
											<input type="text" name="ns" id="ns" value='<?php echo $equip['E_ns']; ?>'>
											
											<label for="nf">Nota Fiscal:</label>
											<input type="text" name="nf" id="nf" value='<?php echo $equip['E_nf']; ?>'>
											
											<label for="mac">MAC:</label>
											<input type="text" name="mac" id="mac" value='<?php echo $equip['E_mac']; ?>'>
											
											<label for="descartado">Descartado:</label>
											<input type="checkbox" id="descartado" name="descartado" <?php if($equip['E_descartado'] == 1){echo "checked ";} ?>/></td>
											
											<button class="button-atualizar">Atualizar</button>
								</form>
							</div>
						</div>
						<div id="accordion-transferencias">
							<h3><a href="/dados_index/equipamento_transferencias.php?id_equipamento=<?php echo $id_equipamento; ?>">Transferências do Equipamento</a></h3>
							<div><img src='imagens/ajax-loader.gif' ></div>
						</div>
						<div id="accordion-chamados-equipamento">
							<h3><a href="/dados_index/equipamento_chamados.php?id_equipamento=<?php echo $id_equipamento; ?>">Chamados do Equipamento</a></h3>
							<div><img src='imagens/ajax-loader.gif' ></div>
						</div>
						<div id="accordion-usuarios">
							<h3><a href="/dados_index/usuario.php?id_equipamento=<?php echo $id_equipamento; ?>">Usuários do Equipamento</a></h3>
							<div><img src='imagens/ajax-loader.gif' ></div>
						</div>
					</div><!-- #dados -->
				</div>
			</div>
			<div id="rodape"></div><!-- #rodape -->
		</div><!-- #corpo -->
		<?php include_once './dados_index/nav-bar.php'; ?>
	</body>
</html>