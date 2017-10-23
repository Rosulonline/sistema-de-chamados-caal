<?php 
	include_once('funcoes.php');
	
	$login = protegePagina();
	
	if(isset($_GET['id_chamado'])){
		$id_chamado = $_GET['id_chamado'];
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>CAAL - Chamados</title>
		<?php include_once '/dados_index/header.html'; ?>
		<script type="text/javascript">
			$(function(){
				$.datepicker.setDefaults({dateFormat: 'dd/mm/yy',
                              dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
                              dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
                              dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
                              monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro', 'Outubro','Novembro','Dezembro'],
                              monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set', 'Out','Nov','Dez'],
                              nextText: 'Próximo',
                              prevText: 'Anterior'
                             });
				$('#data').datepicker({
					gotoCurrent: true,
					minDate: 0,
      				showButtonPanel: true,
					inline: true
				});
			});
		</script>
	</head>
	<body>
		<div id="corpo">
		<div id="cabecalho"><p>Sistema de Chamados</p></div><!-- #cabecalho -->
		<div id="conteudo">
			<form action="../novo/solucao.php" method="post">
				<table id="tabela">
					<caption>Solução</caption>
					<tr>
						<td colspan='1'>Descrição</td>
						<td colspan='3'><textarea name="descricao" rows="25" cols="80"></textarea></td>
						<input type="hidden" name="id_chamado" id="id_chamado" value="<?php echo $id_chamado; ?>" >
					</tr>
					<tr>
						<td>Previsão: </td>
						<td>
							<input id='data' type='text' name='data' size='10'>
							<select name="hora">
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
						</td>
						<td>Solucionado: <input type="checkbox" name="solucao"/></td>
					</tr>
					<tr>
						<td colspan='4' style='text-align:center;'><input type="submit" value="Confirmar"></td>
					</tr>
				</table>
			</form>
		</div>
		<div id="rodape">
		</div>
		</div>
		<div id='nav-bar'>
			<ul>
				<li><a href='/sair.php'>Encerrar Sessão</a></li>
				<li><a><?php echo $_SESSION['nome']; ?></a></li>
				<li><a href='/index.php'>Página Inicial</a></li>
				<li><a href='/formularios/cadastro_chamado.php'>Novo Chamado</a></li>
				<li><form action='buscar.php' method='get'><input name='nome' type='text'/><input type='submit' value='Buscar'/></form></li>
			</ul>
		</div>
	</body> 
</html>