<?php
	include("../funcoes.php");
	
	$login = protegePagina();
?>
<style type="text/css">
	#adicionar-anexo label,
	#adicionar-anexo input ,
	#adicionar-anexo textarea{ 
		display:block;
	}
	#adicionar-anexo select,
	#adicionar-anexo textarea,
	#adicionar-anexo input.text{
		margin-bottom:12px;
		width:95%;
		padding: .4em;
	}
	#adicionar-anexo fieldset{
		padding:0;
		border:0;
		margin-top:10px;
	}
</style>
<form id="adicionar-anexo" action="/novo/imagem.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<label for="imagem">Arquivo:</label>
		<input type="file" name="anexo" id="imagem" />
		<div id="enviando"></div>
	</fieldset>
</form>