<?php
	include_once '../funcoes.php';
	$login = protegePagina();
	
	$filiais = retorna_filiais();
?>
<script>
	$( "#accordion_filial" ).accordion({
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
	$(".button-adicionar-usuario").button({
		icons:{
			primary: "ui-icon-plus"
		}
	});
	$(".button-adicionar-usuario").live('click',function(){
		url = $(this).attr("href");
		dialog_box = $('<div id="dialog-form" title="Adicionar Usuário" style="display:hidden"></div>').appendTo('body');
		dialog_box.html(function(){
			$('<img src="imagens/ajax-loader.gif" >').appendTo('#dialog-form');
			dialog_box.dialog({
				height: 550,
				width: 750,
				modal: true,
				resizable: false
			});
		});
		dialog_box.load(url,{},function() {
			dialog_box.dialog({
				height: 550,
				width: 750,
				modal: true,
				resizable: false,
				buttons: {
					"Cadastrar Usuário":function(){
						$( "#form-usuario-cadastro" ).submit();
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
</script>
<style type="text/css">
	.button-adicionar-usuario{
		width:100%;
		margin-top:10px;
		margin-right:5px;
	}
</style>
<div id="accordion_filial">
	<?php	if(count($filiais)>0){ ?>
	<?php		for($i=0; $i<count($filiais); $i++){ ?>
	<h3><a href="/dados_index/sub_index_setor.php?id_filial=<?php echo $filiais[$i]['F_id']; ?>"><?php echo $filiais[$i]['F_nome']; ?></a></h3>
	<div>
		<p>Carregando...</p>
	</div>
	<?php		} ?>
	<?php	}else{ ?>
	<h3>Nenhum Registro</h3>
	<?php	} ?>
</div>
<a class="button-adicionar-usuario" href="/dados_index/usuario_cadastro.php">Adicionar Usuário</a>
