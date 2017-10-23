<?php
	include_once '../funcoes.php';

	$login = protegePagina();	
	
	$equipamento = retorna_equipamentos(FALSE, FALSE, null, null);
?>
<script>
	var windowsSeven = 0;
	var windowsXP = 0;
	var windowsServerOito = 0;
	var windowsServerDoze = 0;
	var windowsServerTres = 0;
	var windowsServerZero = 0;
	var office2003 = 0;
	var office2007 = 0;
	var office2010 = 0;
	var office2013 = 0;
	
	<?php	for ($i=0; $i < count($equipamento) ; $i++) { ?> 
	<?php		if($equipamento[$i]['C_nome'] == 'Sistema Operacional'){ ?>
	<?php			if (stristr($equipamento[$i]['E_descricao'], "7")) { ?>
	windowsSeven++;
	<?php			} else if(stristr($equipamento[$i]['E_descricao'], "xp")){ ?>
	windowsXP++;
	<?php			} ?>
	<?php		}else if($equipamento[$i]['C_nome'] == 'Software'){ ?>
	<?php			if(stristr($equipamento[$i]['E_descricao'], "Office") && stristr($equipamento[$i]['E_descricao'], "2003")){ ?>
	office2003++;
	<?php			} ?>
	<?php			if(stristr($equipamento[$i]['E_descricao'], "Office") && stristr($equipamento[$i]['E_descricao'], "2007")){ ?>
	office2007++;
	<?php			} ?>
	<?php			if(stristr($equipamento[$i]['E_descricao'], "Office") && stristr($equipamento[$i]['E_descricao'], "2010")){ ?>
	office2010++;
	<?php			} ?>
	<?php			if(stristr($equipamento[$i]['E_descricao'], "Office") && stristr($equipamento[$i]['E_descricao'], "2013")){ ?>
	office2013++;
	<?php			} ?>
	<?php		} ?>
	<?php	} ?>
	
	
	<?php	for ($i=0; $i < count($usuario) ; $i++) { ?> 
	
	usuarios++;
		
	<?php	} ?>
	
	
	$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'licencas',
				type: 'column'
			},
            title: {
                text: 'Quantidade de Licenças'
            },
            subtitle: {
                text: 'Licenças'
            },
            xAxis: {
                categories: [
					'Windows 7', 'Windows XP', 'Office 2003', 'Office 2007', 'Office 2010', 'Office 2013'
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
					data: [ windowsSeven, windowsXP, office2003,office2007, office2010, office2013 ],
					stack: 'Total',
					dataLabels: {
						enabled: true,
						color: '#FFFFFF',
						align: 'center'
					}
				}
			]
        });
    });
    
});
</script>
<div id="licencas" style="min-width: 400px; height: 400px; margin: 0 auto"></div>