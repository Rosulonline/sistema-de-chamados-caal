<?php
	include("../funcoes.php");
	$usuario = retorna_usuarios_equipamento(28); //protheus
	$total = count($usuario);
	$filial = 0;
	$setor = 0;
	/*
	for($i=0;$i<$total;$i++){
		if($usuario[$i]['F_id'] != $filial){
			$filial = $usuario[$i]['F_id'];
			$aux = $i;
			if($i>0){
				echo "\n\t</div><!-- SETOR:FILIAL  -->";
				echo "\n</div><!-- FILIAL -->";
			}
			echo "\n<h3>i: ".$i." - ".$usuario[$i]['F_id'].": ".$usuario[$i]['F_nome']."</h3>";
			echo "\n<div>";
		}
		if($usuario[$i]['S_id'] != $setor){
			$setor = $usuario[$i]['S_id'];
			if(($i>0) && ($aux != $i)){
				echo "\n\t</div><!-- SETOR -->\n";
			}
			echo "\n\t<h3>i: ".$i." - ".$usuario[$i]['S_id'].": ".$usuario[$i]['S_nome']."</h3>";
			echo "\n\t<div>";
		}
		echo "\n\t\t<h3>i: ".$i." - ".$usuario[$i]['U_id'].": ".$usuario[$i]['U_nome']."</h3>";
		echo "<div>";
		echo "</div><!-- USUARIO -->";
		if($i == ($total-1)){
			echo "\n\t</div><!-- SETOR -->";
			echo "\n</div><!-- FILIAL -->";
		}
	}
	*/
?>

<script type="text/javascript">
	$(function() {
		$( ".datail" ).button({
			icons: {
				primary: "ui-icon-pencil"
			}
		});
		$( "#accordion-usuarios" ).accordion({
			heightStyle: "content",
			collapsible: false,
			active: true,
			change: function (e, ui) {
				$url = $(ui.newHeader[0]).children('a').attr('href');
				$.get($url, function (data) {
					console.log(data);
					$(ui.newHeader[0]).next().html(data);
				});
			}
		});
		$( "#accordion-filiais" ).accordion({
			heightStyle: "content"
		});
		$( "#accordion-setores" ).accordion({
			heightStyle: "content"
		});
		
		$( "#accordion-chamados<?php echo $id_usuario; ?>" ).accordion({
			heightStyle: "content",
			collapsible: false,
			active: true,
			change: function (e, ui) {
				$url = $(ui.newHeader[0]).children('a').attr('href');
				$.get($url, function (data) {
					console.log(data);
					$(ui.newHeader[0]).next().html(data);
				});
			}
		});
	});
</script>
<div id="accordion-filiais">
	<?php	for($i=0;$i<$total;$i++){ ?>
	<?php		if($usuario[$i]['F_id'] != $filial){ ?>
	<?php			$filial = $usuario[$i]['F_id']; ?>
	<?php			$aux = $i; ?>
	<?php			if($i>0){ ?>
		</div><!-- SUB:Setor  -->
	</div><!-- #accordion-filiais -->
	<?php			} ?>
	<h3><?php echo $usuario[$i]['F_nome']; ?></h3>
	<div>
		<div id="accordion-setores">
	<?php		} ?>
	<?php		if($usuario[$i]['S_id'] != $setor){ ?>
	<?php			$setor = $usuario[$i]['S_id']; ?>
	<?php			if(($i>0) && ($aux != $i)){ ?>
		</div><!-- #accordion-setores -->
	<?php			} ?>
			<h3><?php echo $usuario[$i]['S_nome']; ?></h3>
			<div id="accordion-usuarios">
	<?php		} ?>
				<h3><?php echo $usuario[$i]['U_nome']; ?></h3>
				<div></div><!-- USUARIO -->
	<?php		if($i == ($total-1)){ ?>
		</div><!-- SETOR -->
	</div><!-- FILIAL -->
	<?php		} ?>
	<?php	} ?>
</div>