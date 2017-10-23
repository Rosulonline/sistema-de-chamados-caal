<?php 
	include_once('funcoes.php');

	$login = protegePagina();
	
	$id_filial		= NULL;
	$id_setor		= NULL;
	$id_usuario		= NULL;
	$id_equipamento	= NULL;
	$id_chamado		= $_GET['id'];
	$aberto 		= NULL;
	$inicial 		= NULL;
	$por_pagina 	= NULL;
	$id_responsavel = NULL;
	
	$total_chamados = retorna_quantidade_total_de_chamados();
	
	if($id_chamado < 1){
		$id_chamado = 1;
	}
	
	$chamado = retorna_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto, $inicial, $por_pagina);
	$responsaveis = retorna_responsaveis($id_responsavel);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>CAAL - Chamados</title>
		<?php include_once '/dados_index/header.html'; ?>
		
		<script>
			$(function() {
				$( "#tabs" ).tabs({
					beforeLoad: function( event, ui ) {
						ui.jqXHR.error(function() {
							ui.panel.html(
								"Carregando..."
							);
						});
					}
				});
				$( "#accordion" ).accordion({
					heightStyle: "content",
				});
				$( "#accordion_datas" ).accordion({
					heightStyle: "content",
				});
				$( "#accordion_descricao" ).accordion({
					heightStyle: "content",
				});
				$( "#accordion_dados" ).accordion({
					heightStyle: "content",
					collapsible: true,
					active : false,
					change: function (e, ui) {
						$url = $(ui.newHeader[0]).children('a').attr('href');
						$.get($url, function (data) {
							//console.log(data);
							$(ui.newHeader[0]).next().html(data);
						});
					}
				});
				$( ".okButton" ).button({
				});
				$( ".okButton" ).button().click(function() {
					$( "#alterarChamado" ).submit();					
				});
				$( ".navegar").button({});
				
				/*REABRIR*/
				$( ".reabrir" ).button({
					icons: {
						primary: "ui-icon-arrowreturnthick-1-e"
					}
				});
				$( ".reabrir" ).button().click(function() {
					$( "#dialog-reabrir" ).dialog( "open" );					
				});
				/*FIM REABRIR*/
				
				/*FINALIZAR*/
				$( ".finalizar" ).button({
					icons: {
						primary: "ui-icon-closethick"
					}
				});
				$( ".finalizar" ).button().click(function() {
					$( "#dialog-finalizar" ).dialog( "open" );					
				});
				/*FIM FINALIZAR*/
				
				
				/*BOTOES*/
				$(".btn-Proximo").button({
				}).click(function (){
					window.location.replace("/chamado.php?id=<?php echo ($id_chamado+1); ?>");
				});
				$(".btn-Anterior").button({
				}).click(function (){
					
					window.location.replace("/chamado.php?id=<?php if($id_chamado > $total_chamados) { echo $total_chamados; }else{ echo ($id_chamado-1); } ?>");
				});
				/*fim: BOTOES*/
				
				/*REMOVER*/
				$( ".remover" ).button({
					icons: {
						primary: "ui-icon-trash"
					}
				});
				$( ".remover" ).button().click(function() {
					$( "#dialog-confirm" ).dialog( "open" );					
				});
				/*FIM REMOVER*/
				$( ".editar" ).button({
					icons: {
						primary: "ui-icon-pencil"
					}
				});
				$( ".editar" ).button().click(function() {
					$( "#dialog-confirm" ).dialog( "open" );					
				});
				$( ".adicionar" ).button({
					icons: {
						primary: "ui-icon-plus"
					}
				});
				$( ".adicionar" ).button().click(function() {
					$( "#dialog-form" ).dialog( "open" );
				});
				$( ".atribuir" ).button({
					icons: {
						primary: "ui-icon-circle-plus"
					}
				});
				/*REDIRECIONAR*/
				$( ".redirecionar" ).button({
					icons: {
						primary: "ui-icon-arrowreturnthick-1-e"
					}
				});
				$( ".redirecionar" ).button().click(function() {
					$( "#dialog-redirecionar" ).dialog( "open" );
				});
				/*FIM REDIRECIONAR*/
				$( "#dialog-confirm" ).dialog({
					autoOpen: false,
					resizable: false,
					width: 600,
					modal: true,
					buttons: {
						"Incluir uma previsão para a solução do Chamado": function() {
							var motivo = $("#motivo_informado").attr("value");
							var data = $("#data_informada").attr("value");
							var hora = $("#hora_informada").attr("value");
							$( "#motivo_prev" ).val(motivo);
							$( "#data" ).val(data);
							$( "#hora" ).val(hora);
							$("#rem_equip").submit();
						},
						"Solucionar Chamado": function() {
							var motivo = $("#motivo_informado").attr("value");
							$( "#motivo_final" ).val(motivo);
							$("#desc_equip").submit();
						},
						"Cancelar": function() {
							$( this ).dialog( "close" );
						}
					 }	
				});
				$( "#dialog-reabrir" ).dialog({
					autoOpen: false,
					resizable: false,
					width: 600,
					modal: true,
					buttons: {
						"Reabrir": function() {
							var motivo = $("#motivo_reabrir").attr("value");
							$( "#motivo_reabre" ).val(motivo);
							$("#reabrir_chamado").submit();
						},
						"Cancelar": function() {
							$( this ).dialog( "close" );
						}
					 }	
				});
				$( "#dialog-finalizar" ).dialog({
					autoOpen: false,
					resizable: false,
					width: 600,
					modal: true,
					buttons: {
						"Fechar Chamado": function() {
							var motivo = $("#motivo_finalizar").attr("value");
							$( "#motivo_finaliza" ).val(motivo);
							$("#finalizar_chamado").submit();
						},
						"Cancelar": function() {
							$( this ).dialog( "close" );
						}
					 }	
				});				
				$( "#dialog-redirecionar" ).dialog({
					autoOpen: false,
					resizable: false,
					width: 600,
					modal: true,
					buttons: {
						"Redirecionar": function() {
							var motivo = $("#motivo_redir").attr("value");
							var para = $( "#para" ).attr( "value" );
							$( "#para_redirecionar" ).val( para )
							$( "#motivo_redirecionar" ).val( motivo );
							$( "#redirecionar_chamado" ).submit();
						},
						"Cancelar": function() {
							$( this ).dialog( "close" );
						}
					 }	
				});
				$.datepicker.setDefaults({dateFormat: 'dd/mm/yy',
                              dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
                              dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
                              dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
                              monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro', 'Outubro','Novembro','Dezembro'],
                              monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set', 'Out','Nov','Dez'],
                              nextText: 'Próximo',
                              prevText: 'Anterior'
                });
				$('#data_informada').datepicker({
					beforeShowDay: $.datepicker.noWeekends,
					minDate: 0,
      				showButtonPanel: true,
					inline: true
				});
			});
			
		</script>
		<style type="text/css">
			#alterarChamado p{
				line-height: 25px;
				padding:0 5px 0 0;
				margin:0;
			}
			#alterarChamado p,
			#alterarChamado input,
			#alterarChamado div{
				float:left;
				padding:0;
				margin: 0 2px 0 2px;
				height: 25px;
			}
		</style>
	</head>
	</head>
	<body>
		<div id="corpo">
			<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
			<div id="conteudo">
				<div id="tabs" style="float: left; width: 95%;">
					<ul>
						<li><a href="#chamado">Chamado</a></li>
						<?php
							if($login == 2){
								echo "<li style=\"float:right; height: 15px;\">\n";
								echo "						<form id=\"alterarChamado\" action=\"/chamado.php\" method=\"GET\">\n";
								if($id_chamado > 1){
									echo "							<div class=\"btn-Anterior\">&lt;&lt;</div>\n";
								}
								if($id_chamado < $total_chamados){
									echo "							<div class=\"btn-Proximo\">&gt;&gt;</div>\n";
								}
								echo "							<p>Alterar para chamando (ID):</p>\n";
								echo "							<input style=\"width:30px;\" type=\"text\" name=\"id\" value=\"".str_pad($id_chamado, 4, "0",STR_PAD_LEFT)."\">\n";
								echo "							<div class=\"okButton\">OK</div>\n";
								echo "						</form>\n";
								echo "					</li>\n";
							}
							echo "					</ul>\n";
							if($chamado == null){
								echo "					<p>\n";
								echo "						<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin: 0 7px 20px 0;\"></span>\n";
								echo "						<b>Nenhum chamado com esse ID.</b>\n";
								echo "					</p>\n";
							}
							for($i=0; $i<count($chamado); $i++){
								if($chamado[$i]['Ch_aberto']==0){
									$status = "Finalizado.";
									$data_encerramento = formata_data($chamado[$i]['So_data_solucionado']);
								} else if($chamado[$i]['Ch_aberto']==1){
									$status = "Aberto.";
									$data_encerramento = "Não finalizado.";
								} else if($chamado[$i]['Ch_aberto']==2){
									$status = "Em andamento.";
									$data_encerramento = "Não finalizado.";
								}
								if($chamado[$i]['Ch_prioridade']!=NULL){
									$vencimento = formata_data($chamado[$i]['Ch_prioridade']);
								} else{
									$vencimento = "Sem Urgência.";
								}
								if($chamado[$i]['Ch_previsao'] != NULL){
									$previsao = formata_data($chamado[$i]['Ch_previsao']);
								}else{
									$previsao = "Não definida.";
								}
								if($chamado[$i]['So_data_inicial'] != NULL){
									$solucao_inicial = formata_data($chamado[$i]['So_data_inicial']);
								}else{
									$solucao_inicial = "Não definida.";
								}
								$id_equipamento = $chamado[$i]['E_id'];
								$id_chamado = $chamado[$i]['Ch_id'];
								echo "					<div id=\"chamado\">\n";
								echo "						<div id=\"accordion_descricao\" style=\"float: left; width: 49%; margin-right:5px;\">\n";
								echo "							<h3>Dados do Chamado</h3>\n";
								echo "							<div>\n";
								echo "								ID: <a>".str_pad($chamado[$i]['Ch_id'], 4, "0", STR_PAD_LEFT)."</a>\n";
								echo "								Assunto: <a>".$chamado[$i]['Ch_assunto']."</a>\n";
								echo "								Descrição: <a>".nl2br($chamado[$i]['Ch_descricao'])."</a>\n";
								echo "								Técnico Responsável: <a>".$chamado[$i]['R_nome']."</a>\n";
								$R_id = $chamado[$i]['R_id'];
								echo "								Estado: <a>".$status."</a>\n";
								echo "							</div>\n";
								echo "						</div><!-- #accordion_descricao -->\n";
								echo "						<div id=\"accordion_datas\" style=\"float: left; width: 49%;\">\n";
								echo "							<h3>Datas</h3>\n";
								echo "							<div>\n";
								echo "								Data Abertura:<a>".formata_data($chamado[$i]['Ch_data_abertura'])."</a>\n";
								echo "								Data Encerramento: <a>".$data_encerramento."</a>\n";
								echo "								Data de Vencimento: <a>".$vencimento."</a>\n";
								echo "								Data inicial da solução do Chamado: <a>".$solucao_inicial."</a>\n";
								echo "								Data prevista para solução do Chamado: <a>".$previsao."</a>\n";
								echo "							</div>\n";
								echo "						</div><!-- #accordion_datas -->\n";
								echo "						<div id=\"accordion_dados\" style=\"float: left; width: 99%;\">\n";
								echo "							<h3><a href=\"/dados_index/usuario.php?id_usuario=".$chamado[$i]['U_id']."\">Usuário: ". $chamado[$i]['U_nome']."</a></h3>\n";
								echo "						<div>\n";
								echo "							<p>Carregando...</p>\n";
								echo "						</div>\n";
								echo "							<h3><a href=\"/dados_index/equipamento.php?id_equipamento=".$chamado[$i]['E_id']."\">Equipamento: ".$chamado[$i]['E_nome']."</a></h3>\n";
								echo "						<div>\n";
								echo "							<p>Carregando...</p>\n";
								echo "						</div>\n";
								echo "						<h3><a href=\"/dados_index/chamado_historico.php?id_chamado=".$id_chamado."\">Histórico</a></h3>\n";
								echo "						<div>\n";
								echo "							<p>Carregando...</p>\n";
								echo "						</div>\n";
								echo "					</div><!-- #accordion_dados -->\n";
								if($chamado[$i]['Ch_aberto']==(1 || 2)){
									if($login == 2){
										echo "						<button class=\"editar\">Incluir Solução</button>\n";
									} else{
										echo "						<button class=\"finalizar\">Fechar Chamado</button>\n";
									}
									if($login == 2){
										echo "						<button class=\"redirecionar\">Redirecionar Chamado</button>\n";
									}
								}else{
									echo "						<button class=\"reabrir\">Reabrir Chamado</button>\n";
								}
								echo "					</div><!-- #chamado -->\n";
								echo "				</div>\n";
							}
					?>
				
				<div id="rodape"></div>
			</div><!-- #Conteudo -->
		</div><!-- #Corpo -->
		<?php include_once './dados_index/nav-bar.php'; ?>
		<div id="dialog-confirm" title="Solução para o chamado">
			<p>
				<b>Descrição da solução:</b><br/>
				<textarea id="motivo_informado" name="motivo_informado" rows="10" cols="100"></textarea>
			</p>
			<p>
				<b>Data:</b><br/>
				<input id='data_informada' type='text' name='data_informada' size='10'><br/>
				<b>Hora:</b><br/>
				<select id="hora_informada" name="hora_informada">
					<option value=''>Hora</option>
					<option value='08:00'>08:00</option>
					<option value='09:00'>09:00</option>
					<option value='10:00'>10:00</option>
					<option value='11:00'>11:00</option>
					<option value='12:00'>12:00</option>
					<option value='13:00'>13:00</option>
					<option value='14:00'>14:00</option>
					<option value='15:00'>15:00</option>
					<option value='16:00'>16:00</option>
					<option value='17:00'>17:00</option>
					<option value='18:00'>18:00</option>
				</select>
			</p>
			<p>
				<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
				<b>Solucionar Chamado</b>: Define o chamado como solucionado, apenas necessário o preenchimento do campo <b>motivo</b>.
			</p>
			<form id="rem_equip" action="/novo/solucao.php" method="POST" >
				<fieldset>
					<input type="hidden" name="descricao" id="motivo_prev" >
					<input type="hidden" name="data" id="data" >
					<input type="hidden" name="hora" id="hora" >
					<input type="hidden" name="id_chamado" id="id_chamado" value="<?php echo $id_chamado; ?>" >
				</fieldset>
			</form>
			<form id="desc_equip" action="/novo/solucao.php" method="POST">
				<fieldset>
					<input type="hidden" name="id_chamado" id="id_chamado" value="<?php echo $id_chamado; ?>" >
					<input type="hidden" name="solucao" id="solucao" value="true" >
					<input type="hidden" name="descricao" id="motivo_final" >					
				</fieldset>
			</form>
		</div>
		
		<div id="dialog-reabrir" title="Reabrir Chamado" >
			<p>
				<b>Motivo:</b><br/>
				<textarea id="motivo_reabrir" name="motivo_reabrir" rows="10" cols="100"></textarea>
			</p>
			<p>
				<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
				<b>Reabre o chamado</b>: Permite a reabertura do chamado, sendo necessário o preenchimento do campo.
			</p>
			<form id="reabrir_chamado" action="/novo/reabrir_chamado.php" method="POST">
				<fieldset>
					<input type="hidden" name="descricao" id="motivo_reabre" >
					<input type="hidden" name="id_chamado" id="id_chamado" value="<?php echo $id_chamado; ?>" >
				</fieldset>
			</form>
		</div>
		
		<div id="dialog-finalizar" title="Fechar Chamado" >
			<p>
				<b>Motivo:</b><br/>
				<textarea id="motivo_finalizar" name="motivo_finalizar" rows="10" cols="100"></textarea>
			</p>
			<p>
				<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
				<b>Finaliza o chamado</b>: Permite que o chamado seja finalizado, caso ja tenha sido resolvido o problema.
			</p>
			<form id="finalizar_chamado" action="/novo/solucao.php" method="POST">
				<fieldset>
					<input type="hidden" name="id_resp" value="<?php echo $R_id; ?>" >
					<input type="hidden" name="solucao" id="solucao" value="true" >
					<input type="hidden" name="descricao" id="motivo_finaliza" >
					<input type="hidden" name="id_chamado" id="id_chamado" value="<?php echo $id_chamado; ?>" >
				</fieldset>
			</form>
		</div>
		
		<div id="dialog-redirecionar" title="Redirecionar Chamado" >
			<p>
				<b>Motivo:</b><br/>
				<textarea id="motivo_redir" name="motivo_redir" rows="10" cols="100"></textarea>
			</p>
			<p>
				<b>Para:</b>
				<select id="para">
					<option value="0">Selecione o responsável</option>
					<?php for($i=0;$i<count($responsaveis);$i++){ ?>
					<option value="<?php echo $responsaveis[$i]['R_id']; ?>"><?php echo $responsaveis[$i]['R_nome']; ?></option>
					<?php } ?>
				</select>
			</p>
			<form id="redirecionar_chamado" action="/novo/redirecionamento.php" method="POST">
				<fieldset>
					<input type="hidden" name="motivo" id="motivo_redirecionar" >
					<input type="hidden" name="para" id="para_redirecionar" >
					<input type="hidden" name="id_chamado" id="id_chamado" value="<?php echo $id_chamado; ?>" >
				</fieldset>
			</form>
		</div>
	</body> 
</html>
