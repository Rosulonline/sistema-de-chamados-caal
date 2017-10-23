<?php
	include_once '../funcoes.php';

	$login = protegePagina();	
	
	$equipamento = retorna_equipamentos(FALSE, FALSE, null, null);
	$usuario	 = retorna_usuarios(null,null,null,TRUE);
?>
<script>
	var computadores = 0;
	var impressoras = 0;
	var usuarios = 0;
	
	<?php	for ($i=0; $i < count($equipamento) ; $i++) { ?> 
	<?php		if($equipamento[$i]['C_nome'] == 'Computador'){ ?>
	computadores++;
	<?php		} else if($equipamento[$i]['C_nome'] == 'Impressora'){ ?>
	impressoras++;
	<?php		} ?>
	<?php	} ?>
	
	
	<?php	for ($i=0; $i < count($usuario) ; $i++) { ?> 
	
	usuarios++;
		
	<?php	} ?>
	
	
	$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
        	credits:{
        		enable:true,
        		text: "caal.com.br",
        		href: 'http://www.caal.com.br/'
        	},
            chart: {
                renderTo: 'userXequip',
				type: 'column'
			},
            title: {
                text: 'Quantidade de Usuários x Equipamentos'
            },
            subtitle: {
                text: 'Usuários x Equipamentos'
            },
            xAxis: {
                categories: [
					'Usuários', 'Computadores', 'Impressoras'
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
					data: [ usuarios, computadores, impressoras ],
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
<div id="userXequip" style="min-width: 400px; height: 400px; margin: 0 auto"></div>