<?php
    include_once './funcoes.php';
   
	$login			= protegePagina();
	
	if(isset($_SESSION['id_comum'])){
		$id_usuario		= $_SESSION['id_comum']; 
	}else{
		$id_usuario		= $_GET['id'];
	}
	
	$id_filial		= null;
	$id_setor		= null;
	$id_equipamento	= null;
	$id_chamado		= null;
	$aberto			= null;
	$inicial		= 1;
	$por_pagina		= 10;
	$conteudo		= null;
	$componentes	= null;
	
	$classes		= retorna_classes();
    $usr			= retorna_usuarios(null, null, $id_usuario, FALSE);
	$usuarios_setor	= retorna_usuarios(null, $usr[0]['S_id'], null, TRUE);
    $equip			= retorna_equipamentos_usuario($id_usuario, null);
    $setores		= retorna_setores(null, null);
    $chamados		= retorna_chamados($id_filial, $id_setor, $id_usuario, $id_equipamento, $id_chamado, $aberto, $inicial, $por_pagina);
    $descricao		= '';
	
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>CAAL - Chamados</title>
		<?php include_once './dados_index/header.html'; ?>
		<script>
			$(function() {
				/*	 ------  TABS  ------  	*/
				$( "#tabs" ).tabs({
					beforeLoad: function( event, ui ) {
						ui.jqXHR.error(function() {
							ui.panel.html(
								"Carregando..."
							);
						});
					}
				});
				/*	 ------  fim: TABS  ------		*/
				/* DATAS*/
				$.datepicker.regional['pt-BR'] = {
					closeText: 'Fechar',
					prevText: '&#x3c;Anterior',
					nextText: 'Pr&oacute;ximo&#x3e;',
					currentText: 'Hoje',
					monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
					monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
					dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
					dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
					dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
					weekHeader: 'Sm',
					dateFormat: 'dd/mm/yy',
					firstDay: 0,
					isRTL: false,
					showMonthAfterYear: false,
					yearSuffix: ''
				};
				$.datepicker.setDefaults($.datepicker.regional['pt-BR']);
				$("#nascimento").datepicker({
					changeMonth: true,
      				changeYear: true,
      				showButtonPanel: true,
      				closeText: "Fechar",
      				minDate: "-100Y",
      				maxDate: "-18Y",
      				dateFormat: "dd-mm-yy"
				});
				/*fim: DATAS*/
				/*	 ------  DIALOG  ------  			*/
				$( "#dialog-usuario-extras" ).dialog({
					autoOpen: false,
					resizable: false,
					height: 300,
					width: 300,
					modal: true,
					buttons: {
						"Atualizar": function() {
							$(	"#ativo").val(		$("#value-ativo"	).val());
							$(	"#msn"	).val(		$("#value-msn"		).val());
							$(	"#net"	).val(		$("#value-net"		).val());
							$(	"#disp"	).val(		$("#value-pendrive"	).val());
							$(	this 	).dialog(	"close"	);
						},	
						"Cancelar": function() {
							$( this ).dialog( "close" );
						}
					 }	
				});
				
	
				
				$( "#dialog-equipamento-remover" ).dialog({
					autoOpen: false,
					resizable: false,
					height: 250,
					width: 600,
					modal: true,
					buttons: {
						"Remover Equipamento do Usuário": function() {
							$("#rem_equip").submit();
						},
						"Descartar Equipamento": function() {
							$("#desc_equip").submit();
						},
						"Cancelar": function() {
							$( this ).dialog( "close" );
						}
					 }	
				});
				
				/*	 ------  fim: DIALOG  ------  	*/
				
				
				/*	 ------  ACCORDION  ------		*/
				$( "#accordion" ).accordion({
					collapsible: true,
					heightStyle: "content"
				});
				
				$( "#accordion-usuario, #accordion-equipamentos" ).accordion({
					heightStyle: "content"
				});
				
				$( "#accordion-chamados-detalhes, #accordion-setor, #accordion-chamados" ).accordion({
					heightStyle: "content",
					collapsible: true,
					active: false
				});
				
				$( "#accordion-usuarios-setor" ).accordion({
					heightStyle: "content",
					collapsible: true,
					active: false,
					change: function (e, ui) {
						$url = $(ui.newHeader[0]).children('a').attr('href');
						$.get($url, function (data) {
							$(ui.newHeader[0]).next().html(data);
						});
					}
				});
				
				/*	 ------  fim: ACCORDION  ------	*/
				
				
				/*	 ------  BUTTON  ------  		*/
				$( ".button-equipamento-remover" ).button({
					icons: {
						
						primary: "ui-icon-trash"
					}
				});
				$( ".button-equipamento-remover" ).button().click(function() {
					var equipamento = $(this).attr("value");
					$( "#equipamento" ).val(equipamento);
					$( "#id_equipamento" ).val(equipamento);
					$( "#dialog-equipamento-remover" ).dialog( "open" );					
				});
				$( ".button-chamado-detalhar, .button-usuario-atualizar, .button-equipamento-editar" ).button({
					icons: {
						primary: "ui-icon-pencil"
					}
				});
				$(".button-usuario-atualizar").hide();
				$( ".button-equipamento-adicionar" ).button({
					icons: {
						primary: "ui-icon-plus"
					}
				}).live('click',function(){
					url = $(this).attr("href");
					dialog_box = $('<div id="dialog-equipamento-adicionar" title="Adicionar Equipamento" style="display:hidden"></div>').appendTo('body');
					dialog_box.html(function(){
						$('<img src="imagens/ajax-loader.gif" >').appendTo('#dialog-equipamento-adicionar');
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
								"Adicionar":function(){
									$( "#adicionar-equipamento" ).submit(); //id do formulario do link externo
								},
								"Cancelar": function() {
									$( this ).dialog( "close" );
								}
							},
							close: function(){					
								$("#dialog-equipamento-adicionar").remove();
							}
						});
					});
					return false;
				});
				/*INÍCIO: MOVER EQUIPAMENTO*/
				$( ".button-equipamento-mover" ).button({
					icons: {
						primary: "ui-icon-transfer-e-w"
					}
				}).live('click',function(){
					url = $(this).attr("href");
					dialog_box = $('<div id="dialog-transferir-equipamento" title="Transferir Equipamento" style="display:hidden"></div>').appendTo('body');
					dialog_box.html(function(){
						$('<img src="imagens/ajax-loader.gif" >').appendTo('#dialog-transferir-equipamento');
						dialog_box.dialog({
							height: 400,
							width: 750,
							modal: true,
							resizable: false
						});
					});
					dialog_box.load(url,{},function() {
						dialog_box.dialog({
							height: 400,
							width: 750,
							modal: true,
							resizable: false,
							buttons: {
								"Transferir Equipamento":function(){
									$( "#form-transferir-equipamento" ).submit();
								},
								"Cancelar": function() {
									$( this ).dialog( "close" );
								}
							},
							close: function(){					
								$("#dialog-equipamento-transferir").remove();
							}
						});
					});
					return false;
				});
				/*FIM: MOVER EQUIPAMENTO*/

				$( ".button-equipamento-atribuir" ).button({
					icons: {
						primary: "ui-icon-circle-plus"
					}
				});
				
				$( ".button-usuario-extras" ).button({
					icons: {
						primary: "ui-icon-gear"
					}
				}).click(function(){
					$( "#dialog-usuario-extras" ).dialog( "open" );
				});
				
				$('.button-equipamento-atribuir').live('click',function(){
					url = $(this).attr("href");
					dialog_box = $('<div id="dialog-equipamento-atribuir" title="Atribuir Componente" style="display:hidden"></div>').appendTo('body');
					dialog_box.html(function(){
						$('<img src="imagens/ajax-loader.gif" >').appendTo('#dialog-equipamento-atribuir');
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
									$( "#atribuir-equipamento" ).submit();
								},
								"Cancelar": function() {
									$( this ).dialog( "close" );
								}
							},
							close: function(){					
								$("#dialog-equipamento-atribuir").remove();
							}
						});
					});
					return false;
				});
				
				/*	------  fim: BUTTON  ------		*/
				
				/*	------  início: VERIFICA ALTERAÇÕES	------	*/
				
				$("input, select").change(function(){
					$(".button-usuario-atualizar").show();
				});
				
				/*	------  fim: VERIFICA ALTERAÇÕES		------	*/
			});
		</script>
	</head>
	<body>
		<div id="corpo">
			<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
			<div id="conteudo">
				<div id="tabs" style="float: left; width: 95%;" >
					<ul>
						<li><a href="#usuario">Usuário</a></li>
					</ul>
					<div id="usuario">
						<div id="accordion-usuario" style="float:left; width: 30%; margin-right:5px;" >
							<h3>Dados do Usuário</h3>
							<div>
								<form id="dados_usuario" action='/atualizar/usuario.php?id=<?php echo $usr[0]['U_id']; ?>' method='post' >
									<label for='nome'		>Nome:					</label><input	id='nome'	type='text' name='nome' value='<?php echo $usr[0]['U_nome']; ?>'/>
									<label for='usuario'	>Usuário:				</label><input	type='text'	id='usuario' name='usuario' value='<?php echo $usr[0]['U_usuario']; ?>'/>								
									<label for='senha'		>Senha:					</label><input	type='text'	id='senha' name='senha' value='<?php echo $usr[0]['U_senha']; ?>'/>
									<label for='email'		>Email:					</label><input	type='text'	id='email' name='email' value='<?php echo $usr[0]['U_email']; ?>'/>
									<label for='emailmsn'	>Email (msn):			</label><input	type='text'	id='emailmsn' name='emailmsn' value='<?php echo $usr[0]['U_email_msn']; ?>'/>
									<label for='ramal'		>Ramal:					</label><input	type='text'	id='ramal' name='ramal' value='<?php echo $usr[0]['U_ramal']; ?>'/>
									<label for='matricula'	>Matrícula:				</label><input	type='text'	id='matricula' name='matricula' value='<?php echo $usr[0]['U_matricula']; ?>'/>
									<label for='nascimento'	>Data de Nascimento:	</label><input	type='text'	id='nascimento' name='nascimento' value='<?php echo date("d-m-Y",strtotime($usr[0]['U_nascimento'])); ?>'/>
									<label for='setor'		>Setor:					</label>
									<select id='setor' name='setor'>
										<?php for ($i = 0; $i < count($setores); $i++) { ?>									
										<option <?php if ($setores[$i]['S_id'] == $usr[0]['S_id']){ echo "selected='selected'";} ?> value='<?php echo $setores[$i]['S_id']; ?>'><?php echo $setores[$i]['S_nome']; ?></option>
										<?php } ?>
									</select>
									<label for='filial'>Filial:</label><input type='text' id='filial' value="<?php echo $usr[0]['F_nome']; ?>" />
									<input type="hidden" value="<?php echo $usr[0]['U_ativo']; ?>" id="ativo" name="ativo" />
									<input type="hidden" value="<?php echo $usr[0]['U_msn']; ?>" id="msn" name="msn" />
									<input type="hidden" value="<?php echo $usr[0]['U_net']; ?>" id="net" name="net" />
									<input type="hidden" value="<?php echo $usr[0]['U_disp']; ?>" id="disp" name="disp" />
									<p style="width:100%; float: left; clear: left;" class="button-usuario-extras">Outras definições</p>
									<button style='width:100%; float: left; clear: left;' type='submit' class='button-usuario-atualizar'>Atualizar Usuário</button>
									
								</form>
            				</div>
            			</div>
						<?php	if($login == 2){ ?>
            			<div id="accordion-setor" style="float:left; width: 68%; margin-right:1%;">
            				<h3>Usuários do Setor <?php echo $usr[0]['S_nome']; ?></h3>
            				<div>
            					<div id="accordion-usuarios-setor">
            						<?php	if(count($usuarios_setor)>0){ ?>
									<?php		for($i=0; $i<count($usuarios_setor); $i++){ ?>
									<h3><a href="/dados_index/usuario.php?id_usuario=<?php echo $usuarios_setor[$i]['U_id']; ?>"><?php echo $usuarios_setor[$i]['U_nome']; ?></a></h3>
									<div>
										<p>Carregando...</p>
									</div>
									<?php		} ?>
									<?php	}else{ ?>
									<h3>Nenhum Registro</h3>
									<?php	} ?>
            						
            					</div>
            				</div>
            			</div>
						<?php	} ?>
            			<div id="accordion-equipamentos" style="float:left; width: 68%; margin-right:1%;" >
            				<h3>Equipamentos do Usuário</h3>
            				<div>
            					<div id="accordion">
									<?php 	for ($i = 0; $i < count($equip); $i++){
											$conteudo = "";
											$conteudo .= "<h3>".$equip[$i]['C_nome'].": ".$equip[$i]['E_nome']."</h3>";
											$conteudo .= "<div>";
											$conteudo .= "Nome: <a>".$equip[$i]['E_nome']."</a>";
											$conteudo .= "Descrição: <a>";
											if($equip[$i]['C_id'] == 1){
												$componentes = retorna_componentes_computador($equip[$i]['E_id']);
												if($componentes != null){
													for($j=0;$j<count($componentes);$j++){
														$conteudo .= $componentes[$j]['C_nome'].": ".$componentes[$j]['E_nome']." ".nl2br($componentes[$j]['E_descricao']);
														$conteudo .= "<br/>";
													}
												}else{
													$conteudo .="-";
												}
												$componentes = null;
											}else{
												$conteudo .= nl2br($equip[$i]['E_descricao']);
											}
											$conteudo .= "</a>";
											if ($equip[$i]['C_nome'] == 'Computador' || $equip[$i]['C_nome'] == 'Impressora'){
												$conteudo .= "IP: <a>".$equip[$i]['E_ip']."</a>";
											}
											if ($equip[$i]['C_nome'] == 'Software' || $equip[$i]['C_nome'] == 'Sistema Operacional') {
												$conteudo .= "N/S: <a>".$equip[$i]['E_ns']."</a>";
											}
											$conteudo .= "<br/>";
											$conteudo .= "<br/>";
											echo $conteudo;
											$conteudo = null;
										?>
										<?php 	if($login == 2){ ?>
										<button class="button-equipamento-editar" onclick="document.location.href='equipamento.php?id_equipamento=<?php echo $equip[$i]['E_id']; ?>'">Editar Equipamento</button>
										<button class="button-equipamento-remover" value="<?php echo $equip[$i]['E_id']; ?>" >Remover Equipamento</button>
										<button class="button-equipamento-mover" href="/dados_index/equipamento_transferir.php?id_equipamento=<?php echo $equip[$i]['E_id']; ?>&id_usuario=<?php echo $id_usuario; ?>" >Mover Equipamento</button>
										<?php 	} ?>
									</div>
									<?php 	} ?>
								</div>
								<?php 	if($login == 2){ ?>
								<button style="display: block;	width:100%;" class="button-equipamento-atribuir" href="/dados_index/equipamento_atribuir.php?id_usuario=<?php echo $usr[0]['U_id']; ?>">Atribuir Equipamento</button>
								<button style="display: block;	width:100%;" class="button-equipamento-adicionar" href="/dados_index/equipamento.php?id_usuario=<?php echo $usr[0]['U_id']; ?>">Adicionar Equipamento</button>
								<?php 	} ?>
            				</div>
            			</div>
            			<div id="accordion-chamados" style=" clear: both; float: left; width: 100%;">
            				<h3>Chamados do Usuário</h3>
            				<div>
            					<?php	if($chamados != null){ ?>
            					<div id="accordion-chamados-detalhes">
									<?php 		for ($i = 0; $i < count($chamados); $i++) { ?>
									<?php			if($chamados[$i]['Ch_prioridade'] == NULL){ $vencimento = "Sem Urgência"; }else{ $vencimento = formata_data($chamados[$i]['Ch_prioridade']); } ?>
									<?php			$ver_data = verifica_data($chamados[$i]['Ch_prioridade'], date("Y-m-d H:i:s")); ?>
			
									<h3>
										ID: <?php echo str_pad($chamados[$i]['Ch_id'], 4, "0", STR_PAD_LEFT); ?> | <?php echo $chamados[$i]['Ch_assunto']; ?>: <?php echo $chamados[$i]['E_nome']; ?> | Vencimento: <?php echo  $vencimento; ?>
										<?php	if($ver_data == true && $chamados[$i]['Ch_aberto'] == 1){ ?>
										<span style="display: inline; float: right;" class="ui-icon ui-icon-alert">data expirada</span>
										<?php	}else if($chamados[$i]['Ch_aberto'] == 0){ ?>
										<span style="display: inline; float: right;" class="ui-icon ui-icon-check">Finalizado</span>
										<?php	} ?>
									</h3>
									<div>
										Assunto: <a><?php echo $chamados[$i]['Ch_assunto']; ?></a>
										Descrição: <a><?php echo nl2br($chamados[$i]['Ch_descricao']); ?></a>
										Usuário: <a href='usuario.php?id=<?php echo $chamados[$i]['U_id']; ?>' ><?php echo $chamados[$i]['U_nome']; ?></a>
										Técnico Responsável: <a><?php echo $chamados[$i]['R_nome']; ?></a>
										Data Abertura: <a><?php echo formata_data($chamados[$i]['Ch_data_abertura']); ?></a>
										Data Vencimento: <a><?php echo $vencimento; ?></a>
										<br/>
										<button class="button-chamado-detalhar" onclick="document.location.href='../chamado.php?id=<?php echo $chamados[$i]['Ch_id']; ?>'" >Detalhar Chamado</button>
									</div>
								
								<?php 		} ?>
								</div><!-- #accordion-chamados-detalhes -->
								<?php	}else { ?>
								<p>Nenhum registro.</p>
								<?php	} ?>
            				</div>
            			</div>       		
            		</div>
            	</div>
            	
			</div>
			<div id="rodape"></div>
		</div>
		<?php include_once './dados_index/nav-bar.php'; ?>
		<div id="dialog-equipamento-remover" title="Remover Equipamento?">
			<p>
				<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
				<b>Remover Equipamento do Usuário</b>: Remove o vínculo entre o equipamento e o usuário, mantendo os outros usuários.
			</p>
			<p>
				<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
				<b>Descartar Equipamento</b>: Remove todos os usuários do equipamento e define o usuário informatica.caal como seu proprietário.
			</p>
			<form id="rem_equip" action="../remover_equip_usuario.php" method="get">
				<fieldset>
					<input type="hidden" name="usuario" id="usuario" value="<?php echo $_GET['id']; ?>" >
					<input type="hidden" name="equipamento" id="equipamento" value="" >
				</fieldset>
			</form>
			<form id="desc_equip" action="../formularios/descartar_equipamento.php" method="get">
				<fieldset>
					<input type="hidden" name="id_equipamento" id="id_equipamento" value="" >
				</fieldset>
			</form>
		</div><!-- #dialog-equipamento-remover -->
		<div id="dialog-usuario-extras">
				<p>
				<label for="value-ativo">Ativo: </label>
				<select id="value-ativo" name="ativo">
					<option value="1" <?php if($usr[0]['U_ativo'] == 1){ echo "selected='selected'"; }?> > SIM </option>
					<option value="0" <?php if($usr[0]['U_ativo'] != 1){ echo "selected='selected'"; }?> > NÃO </option>
				</select>
				</p>
				<p>			
				<label for="value-msn">Acesso ao MSN: </label>
				<select id="value-msn" name="msn">
					<option value="1" <?php if($usr[0]['U_msn'] == 1){ echo "selected='selected'"; }?> > SIM </option>
					<option value="0" <?php if($usr[0]['U_msn'] != 1){ echo "selected='selected'"; }?> > NÃO </option>
				</select>
				</p>
				<p>
				<label for="value-net">Librerações Internet: </label>
				<select id="value-net" name="net">
					<option value="1" <?php if($usr[0]['U_net'] == 1){ echo "selected='selected'"; }?> > SIM </option>
					<option value="0" <?php if($usr[0]['U_net'] != 1){ echo "selected='selected'"; }?> > NÃO </option>
				</select>
				</p>
				<p>
				<label for="value-pendrive">Liberação Pendrive: </label>
				<select id="value-pendrive" name="pendrive">
					<option value="1"  <?php if($usr[0]['U_disp'] == 1){ echo "selected='selected'"; }?> > SIM </option>
					<option value="0"  <?php if($usr[0]['U_disp'] != 1){ echo "selected='selected'"; }?> > NÃO </option>
				</select>
				</p>
		</div><!-- #dialog-usuario-extras -->
	</body>
</html>