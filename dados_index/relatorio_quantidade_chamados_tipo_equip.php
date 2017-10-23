<?php
	include_once '../funcoes.php';

	$login = protegePagina();

	$filiais = retorna_filiais();
	$setores = retorna_setores();
	$tipos_equip = retorna_classes();
	$id_filial = NULL;
	$id_setor = NULL;
	$id_usuario = NULL;
	$id_equipamento = NULL;
	$id_chamado = NULL;
	$aberto = 0;
	$inicial = NULL;
	$por_pagina = NULL;
	$tipo = NULL;
	if( isset($_GET['datai'])){
		$dtInicial = $_GET['datai']." 00:00:00";
	}else{
		
		$dtInicial = date("Y-m")."-01 00:00:00";
	}
	if( isset($_GET['dataf'])){
		$dtFinal = date("Y-m-t")." 23:59:59";
	}else{
		$dtFinal = date("Y-m-t")." 23:59:59";
	}
	$chamados = retorna_chamados($id_filial,$id_setor,$id_usuario,$id_equipamento,$id_chamado,$aberto, $inicial=1, $por_pagina=20,$tipo, $dtInicial, $dtFinal);
?>
<script>
	var soft = 0;
	var hard = 0;
	<?php	for($i=0;$i<count($chamados);$i++){ ?>
	<?php		if($chamados[$i]['C_id']==4 || $chamados[$i]['C_id']==5){ ?>
					soft++;
	<?php		}else{ ?>
					hard++;
	<?php		} ?>
	<?php	} ?>
	$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
				type: 'column'
			},
            title: {
                text: 'Quantidade de Chamados (finalizados): <?php echo formata_data($dtInicial,true); ?> - <?php echo formata_data($dtFinal,true); ?>'
            },
            subtitle: {
                text: 'Tipo x Quantidade'
            },
            xAxis: {
                categories: [
					'Hardware', 'Software'
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Quantidade'
                }
            },
            legend: {
				backgroundColor: '#FFFFFF'
            },
            plotOptions: {
				column: {
                    stacking: 'normal'               
                }
            },
            series: [
				{
					name: 'Total',
					data: [ soft, hard ],
					stack: 'Total'
				}
				<?php	for($i=0;$i<count($filiais);$i++){ ?>
				<?php		$contS = 0; ?>
				<?php		$contH = 0; ?>
				<?php		if($i == 0){ ?>
				<?php			echo ","; ?>
				<?php			echo "{"; ?>
				<?php		}else{ ?>
				<?php			echo "{"; ?>
				<?php		} ?>
				<?php		echo "name: '".$filiais[$i]['F_nome']."',"; ?>
				<?php		for($j=0;$j<count($chamados);$j++){ ?>
				<?php			if(($chamados[$j]['C_id'] == 4 || $chamados[$j]['C_id'] == 5) && ($chamados[$j]['F_id'] == $filiais[$i]['F_id']) ){ ?>
				<?php				$contS++; ?>
				<?php			}else if($chamados[$j]['F_id'] == $filiais[$i]['F_id']){ ?>
				<?php				$contH++; ?>
				<?php			} ?>
				<?php		} ?>
				<?php		echo "data: [".$contS.",".$contH."],"; ?>
				<?php		echo "stack: 'Filial'"; ?>
				<?php		if($i < count($filiais)){ ?>
				<?php			echo "},"; ?>
				<?php		}else{ ?>
				<?php			echo "}"; ?>
				<?php		} ?>
				<?php	} ?>
			]
        });
    });
    
});
</script>
<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
<p><b>Hardware:</b> Todos equipamentos físicos </p>
<p><b>Softwares:</b> Todos softwares e SO </p>