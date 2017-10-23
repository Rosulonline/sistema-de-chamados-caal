<?php
    include '/funcoes.php';
    $mesP = date('Y-m')."-01";
    $mesF = date('Y-m-t');
    
    //$chamados = retorna_chamados(null,null,null,null,null,TRUE,null,null,null,$mesP,$mesF);
    
?>
<!DOCTYPE html>
<html lang="pt-br">
	<?php include 'header.html'; ?>
<script>
$(function () {
    $('#container').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Total: 47 Funcionários'
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.y}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
                ['Peças Agrícolas',    9],
                ['Compras e Apoio',    8],
                ['Agroelétrica',       4],
                ['Tintas e Ferragens', 8],
                ['Coopservice (Pneus)',4],
                ['Veterinária',       14],
            ]
        }]
    });
});
</script>


	<body>
		<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		
		
	</body>
</html>
