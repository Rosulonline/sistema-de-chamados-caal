<?php 
	include_once '../funcoes.php';
	
	$login = protegePagina();
	
	/*INICIALIZA Variáveis*/
	
	$id_filial		= null;
	$id_setor		= null;
	$id_usuario		= null;
	$id_responsavel	= null;
	$id_classe		= null;
	$ativo			= TRUE;
	$setores		= null;
	$filiais		= null;
	$aux			= null;
	$contadorVar	= 0;
	$assuntos 		= array('Informar Problema', 
							'Liberação de Páginas',
							'Liberação de E-mails',
							'Liberação de Pendrive',
							'Cadastro de Usuário',
							'Manutenção',
							'Garantia',
							'Troca',
							'Aquisição de Equipamento',
							'Outro'	
					);
	/*fim: INICIALIZA Variáveis*/
	if(isset($_SESSION['filial']) && $_SESSION['filial']!=0){
		$id_filial	= $_SESSION['filial'];
		$setores	= retorna_setores($id_filial);	
	}else{
		$setores	= retorna_setores();
	}
	
	if(isset($_SESSION['setor']) && $_SESSION['setor']!=0){
		$id_setor	= $_SESSION['setor'];
		$aux		= retorna_setores(null,$id_setor);
		$id_filial	= $aux[0]['F_id'];
		$aux		= null;
	}	
	
	if(isset($_SESSION['id_comum'])){
		$id_usuario				= $_SESSION['id_comum'];
		$_SESSION['usuario']	= $id_usuario;
		$aux					= retorna_usuarios(null, null,$id_usuario);
		$id_filial				= $aux[0]['F_id'];
		$id_setor				= $aux[0]['S_id'];
		$aux					= null;
	}else if(isset($_SESSION['usuario']) && $_SESSION['usuario']!=0){
		$id_usuario	= $_SESSION['usuario'];
		$aux		= retorna_usuarios(null, null,$id_usuario);
		$id_filial	= $aux[0]['F_id'];
		$id_setor	= $aux[0]['S_id'];
		$aux		= null;
	}
	
	if(isset($_SESSION['equipamento']) && $_SESSION['equipamento'] != 0){
		$equipamento = $_SESSION['equipamento'];
	}
	
	if(isset($_SESSION['assunto'])){
		$assunto = $_SESSION['assunto'];
	}
	
	if(isset($_SESSION['descricao'])){
		$descricao = $_SESSION['descricao'];
	}
	
	if(isset($_SESSION['responsavel']) && $_SESSION['responsavel'] != 0){
		$responsavel = $_SESSION['responsavel'];
	}
	
	$filiais = retorna_filiais();
	
	$usuarios = retorna_usuarios($id_filial,$id_setor,null,$ativo,1);
	
	if($id_usuario != (0 || null)){
		$equipamentos = retorna_equipamentos_usuario($id_usuario,$id_classe);
	}
	
	$responsaveis = retorna_responsaveis($id_responsavel);
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>CAAL - Chamados</title>
		<?php include_once '../dados_index/header.html'; ?>
		<script type="text/javascript">
			var filial		= 0;
			var setor		= 0;
			var equipamento	= 0;
			var usuario		= 0;
			var urgencia	= "";
			var descricao	= "";
			var assunto		= 0;
			var responsavel	= 0;
			
			$(function() {
				$( "#tabs" ).tabs({
					heightStyle: "fill"
				});
				
				/* CADASTRO DE ANEXOS */
				$( ".button-anexo-adicionar" ).button({
					icons: {
						primary: "ui-icon-plus"
					}
				}).live('click',function(){
					url = $(this).attr("href");
					dialog_box = $('<div id="dialog-anexo-adicionar" title="Adicionar Imagem" style="display:hidden"></div>').appendTo('body');
					dialog_box.html(function(){
						$('<img src="imagens/ajax-loader.gif" />').appendTo('#dialog-anexo-adicionar');
						dialog_box.dialog({
							height: 200,
							width: 750,
							modal: true,
							resizable: false
						});
					});
					dialog_box.load(url,{},function() {
						dialog_box.dialog({
							height: 200,
							width: 750,
							modal: true,
							resizable: false,
							buttons: {
								"Adicionar": function(){
									$('<p>Enviando....</p>').appendTo("#enviando");
									$( "#adicionar-anexo" ).ajaxForm({
										target: "#enviando"
									}).submit(); //id do formulario do link externo
									$(this).dialog({
										buttons:{
											"Fechar": function(){
												$(this).dialog("close");
											}
										}
									});
								},
								"Cancelar": function() {
									$( this ).dialog( "close" );
								}
							},
							close: function(){					
								$("#dialog-anexo-adicionar").remove();
							}
						});
					});
					return false;
				});
				
				
				
				/*BOTÕES*/
				
				$(".button-chamado-incluir").button({
					icons: {
						primary: "ui-icon-plus"
					}
					<?php	if(!isset($_SESSION['usuario']) || !isset($_SESSION['equipamento']) || !isset($_SESSION['assunto'])  || !isset($_SESSION['descricao']) || !isset($_SESSION['responsavel'])){	?>
					,disabled: true	
					<?php	}					?>					
				});
				
				/*fim: BOTÕES*/
				
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
					dayNamesMin: ['D','S','T','Q','Q','S','S'],
					weekHeader: 'Sm',
					dateFormat: 'dd/mm/yy',
					firstDay: 0,
					isRTL: false,
					showMonthAfterYear: false,
					yearSuffix: ''
				};
				$.datepicker.setDefaults($.datepicker.regional['pt-BR']);
				$("#prioridade_mod").datepicker({
					beforeShowDay: FinaisDeSemanaOuFeriados,
					altField: "#prioridade_envio",
					altFormat: "dd-mm-yy",
					gotoCurrent: true,
					minDate: 0,
      				showButtonPanel: true,
      				dateFormat: "dd 'de' MM 'de' yy"
				});
				
				diasFeriados = [
					[ 1,  1],	//Confraternização universal
					[12,  2],	//Carnaval
					[ 3, 29],	//Paixão de Cristo
					[ 5,  1],	//DIA DO TRABALHADOR
					[ 5, 30],	//CORPUS CHRISTI
					[ 8, 11],	//DIA DOS PAIS
					[ 9,  7],	//Independência do Brasil
					[ 9, 20],	//Revolução Farroupilha
					[11,  2],	//FINADOS
					[11, 15],	//PROCLAMAÇÃO DA REPÚBLICA
					[12, 25],	//NATAL
					[12, 31]	//VIRADA DE ANO					
				];
				
				function nationalDays(date) {
					for (i = 0; i < diasFeriados.length; i++) {
						if (date.getMonth() == diasFeriados[i][0] - 1 && date.getDate() == diasFeriados[i][1]) {
							return [false, diasFeriados[i][2] + '_day'];
						}
					}
					return [true, ''];
				}
				
				function FinaisDeSemanaOuFeriados(date) {
					var noWeekend = $.datepicker.noWeekends(date);
					if (noWeekend[0]) {
						return nationalDays(date);
					} else {
						return noWeekend;
					}
				}
				
				/*fim: DATAS*/
				
				
				/*Change dos SELECT*/
				
				$("#filial").on('change', function() {
					$.post("../novo/dados_temp_formulario.php", {filial: $(this).val()}).done(function(){ location.reload(); });
				});
				
				$("#setor").on('change', function() {
					$.post("../novo/dados_temp_formulario.php", {setor: $(this).val()}).done(function(){ location.reload(); });
				});
				
				$("#usuario").on('change', function() {
					$.post("../novo/dados_temp_formulario.php", {usuario: $(this).val()}).done(function(){ location.reload(); });
				});
				
				$("#equipamento").on('change', function(){
					$.post("../novo/dados_temp_formulario.php", {equipamento: $(this).val()}).done(function(){ location.reload(); });
				});
				
				$("#descricao").on('change', function(){
					$.post("../novo/dados_temp_formulario.php", {descricao: $(this).val()}).done(function(){ location.reload(); });
				});
				
				$("#assunto").on('change', function(){
					$.post("../novo/dados_temp_formulario.php", {assunto: $(this).val()}).done(function(){ location.reload(); });
				});
				$("#responsavel").on('change', function(){
					$.post("../novo/dados_temp_formulario.php", {responsavel: $(this).val()}).done(function(){ location.reload(); });
				});
								
				/*fim: Change dos SELECT*/
			});
		</script>
		<style type="text/css">
			.dados{
				width: 20%;
				float: left;
			}
			.usuario{
				width: 58%;
				float: left;				
			}
			
		</style>
	</head>
	<body>
		<div id="corpo">
			<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
			<div id="conteudo">
				<div id="tabs" style="height: 550px;">
					<ul>
						<li><a href="#tab1">Novo Chamado</a></li>
					</ul>
					<div id="tab1">
						<form action="../novo/chamado.php" method="post">
							<div class="dados">
								<label for="filial">Filial:</label>
								<select id="filial" name="filial">
									<option value="0">Selecione a Filial</option><?php
										for($i=0; $i<count($filiais); $i++){
											if($id_filial == $filiais[$i]['F_id']){
												$aux = "\t\t\t\t\t\t\t\t\t<option selected='selected' ";
											}else{
												$aux = "\t\t\t\t\t\t\t\t\t<option ";
											}
											$aux .= "value='".$filiais[$i]['F_id']."' >".$filiais[$i]['F_nome']."</option>\n";
											echo $aux;
										}
										$aux = null;
									?>
								</select>
							</div>
							<div class="dados">
								<label for="setor">Setor:</label>
								<select id="setor" name="setor">
									<option value="0">Selecione o Setor</option><?php
										for($i=0; $i<count($setores); $i++){
											if($id_setor == $setores[$i]['S_id']){
												$aux = "\t\t\t\t\t\t\t\t\t<option selected='selected' ";
											}else{
												$aux = "\t\t\t\t\t\t\t\t\t<option ";
											}
											$aux .= "value='".$setores[$i]['S_id']."' >".$setores[$i]['S_nome']."</option>\n";
											echo $aux;
										}
										$aux = null;
									?>
								</select>
							</div>
							<div class="usuario">
								<label for="usuario">Usuário:</label>
								<select id="usuario" name="usuario">
									<option value="0">Selecione seu nome</option><?php
										for($i=0; $i<count($usuarios); $i++){
											if($id_usuario == $usuarios[$i]['U_id']){
												$aux = "\t\t\t\t\t\t\t\t\t<option selected='selected' ";
											}else{
												$aux = "\t\t\t\t\t\t\t\t\t<option ";
											}
											$aux .= "value='".$usuarios[$i]['U_id']."' >".$usuarios[$i]['U_nome']."</option>\n";
											echo $aux;
										}
										$aux = null;
									?>
								</select>
							</div>
							<label for="equipamento">Equipamento:</label>
							<select id="equipamento" name="equipamento"><?php
									if(isset($_SESSION['usuario']) && $_SESSION['usuario'] != 0){
										$aux = "\t\t\t\t\t\t\t\t\t<option value='0'>Selecione o Equipamento</option>\n";
										echo $aux;
										for($i=0;$i<count($equipamentos);$i++){
											$aux = "\t\t\t\t\t\t\t\t\t<option value=".$equipamentos[$i]['E_id'];
											if($equipamentos[$i]['E_id'] == $equipamento){
												$aux .= " selected='selected' ";
											}
											$aux .= ">".$equipamentos[$i]['C_nome'].": ".$equipamentos[$i]['E_nome']."</option>\n";
											echo $aux;
											$aux = null;
											$componentes = retorna_componentes_computador($equipamentos[$i]['E_id']);
											for($j=0;$j<count($componentes);$j++){
												if($componentes[$j]['C_id']==4 || $componentes[$j]['C_id']==5 || $componentes[$j]['C_id']==7){
													$aux = "\t\t\t\t\t\t\t\t\t<option value=".$componentes[$j]['E_id'];
													if($componentes[$j]['E_id'] == $equipamento){
														$aux .= " selected='selected' ";
													}
													$aux .= ">".$componentes[$j]['C_nome'].": ".$componentes[$j]['E_nome']." ".$componentes[$j]['E_descricao']."</option>\n";
													echo $aux;
													$aux = null;
												}
											}
										}
									}else{
										echo "\t\t\t\t\t\t\t\t\t<option>Selecione Usuário Primeiro</option>\n";
									}
								?>
							</select>
							<label for="assunto">Assunto:</label>
							<select id="assunto" name="assunto">
								<option value="0">Selecione o Assunto</option><?php
									for($i=0; $i< count($assuntos); $i++){
										$aux = "\t\t\t\t\t\t\t\t\t<option ";
										
										if($assunto == $assuntos[$i]){
											$aux .= "selected='selected' ";
										}
										
										$aux .= "value='".$assuntos[$i]."' >".$assuntos[$i]."</option>\n";
										
										echo $aux;
										
										$aux = null;
									}
								?>
							</select>
							<label for="descricao">Descrição:</label>
							<textarea id="descricao" name="descricao" cols="100" rows="10"><?php echo $descricao; ?></textarea>
							<div class="usuario" style="width: 78%">				
								<label for="responsavel">Responsável:</label>
								<select id="responsavel" name="responsavel">
									<option value="0">Selecione o responsável</option><?php	
										for($i=0;$i<count($responsaveis);$i++){
											$aux = "\t\t\t\t\t\t\t\t\t<option ";
											if($responsaveis[$i]['R_id'] == $responsavel){
												$aux .= "selected='selected' ";
											}
											$aux .= "value='".$responsaveis[$i]['R_id']."' >".$responsaveis[$i]['R_nome']."</option>\n";
											echo $aux;
										}
									?>
								</select>
							</div>
							<div class="dados" style="margin: 0 0 0 -2%; padding: 0;">
								<label for="prioridade_mod">Urgência:</label>
								<input type="text"		id="prioridade_mod"		name="prioridade_mod" value="<?php echo $urgencia; ?>" />
								<input type="hidden"	id="prioridade_envio"	name="prioridade" />
							</div>							
							<div id="imagens" style="display:inline-block;"></div>
							<!--button style="display: block;	width:95%; margin: 10px 0 10px 0;" class="button-anexo-adicionar" href="/formularios/cadastro_anexo.php">Adicionar Imagem</button-->
							<input type="submit" value="Incluir" class="button-chamado-incluir" />
						</form>
					</div>
				</div>
				
			</div>
			<div id="rodape"></div>
		</div>
		<?php include '../dados_index/nav-bar.php'; ?>
	</body>
</html>