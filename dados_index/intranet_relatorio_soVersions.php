<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>Relatório para Intranet</title>
		<meta name="description" content="">
		<meta name="author" content="madson.junior">

		<meta name="viewport" content="width=device-width; initial-scale=1.0">

		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico">
		<link rel="apple-touch-icon" href="/apple-touch-icon.png">
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
		<link href="/css/smoothness/jquery-ui.custom.css" rel="stylesheet" />
		<script src="/js/jquery.js"></script>
		<script src="/js/jquery-ui.custom.js"></script>
		<script src="/js/jquery.toastmessage.js"></script>
		<script src="/js/highcharts.js"></script>
		<?php
			include_once '../funcoes.php';
		
			//$login = protegePagina();	
			
			$equipamento = retorna_equipamentos(FALSE, FALSE, null, null);
			$html = "";
			$html .="<script>\n";
			$html .="	var windows8 = 0;\n";
			$html .="	var windowsSeven = 0;\n";
			$html .="	var windowsXP = 0;\n";
			$html .="	var windowsServerOito = 0;\n";
			$html .="	var windowsServerDoze = 0;\n";
			$html .="	var windowsServerTres = 0;\n";
			$html .="	var windowsServerZero = 0;\n";
			$html .="	var office2003 = 0;\n";
			$html .="	var office2007 = 0;\n";
			$html .="	var office2010 = 0;\n";
			$html .="	var office2013 = 0;\n";
			for ($i=0; $i < count($equipamento) ; $i++) {
				if($equipamento[$i]['C_nome'] == 'Sistema Operacional'){
					if (stristr($equipamento[$i]['E_descricao'], "7")) {
						$html .="	windowsSeven++;\n";
					} else if(stristr($equipamento[$i]['E_descricao'], "xp")){
						$html .="	windowsXP++;\n";
					} else if(stristr($equipamento[$i]['E_descricao'], "8")){
						$html .="	windows8++;\n";
					}
				}else if($equipamento[$i]['C_nome'] == 'Software'){
					if(stristr($equipamento[$i]['E_descricao'], "Office") && stristr($equipamento[$i]['E_descricao'], "2003")){
						$html .="	office2003++;\n";
					}
					if(stristr($equipamento[$i]['E_descricao'], "Office") && stristr($equipamento[$i]['E_descricao'], "2007")){
						$html .="	office2007++;\n";
					}
					if(stristr($equipamento[$i]['E_descricao'], "Office") && stristr($equipamento[$i]['E_descricao'], "2010")){
						$html .="	office2010++;\n";
					}
					if(stristr($equipamento[$i]['E_descricao'], "Office") && stristr($equipamento[$i]['E_descricao'], "2013")){
						$html .="	office2013++;\n";
					}
				}
			}
			for ($i=0; $i < count($usuario) ; $i++) {
				$html .="	usuarios++;\n";
			}
			
			$html .="	$(function () {\n";
			$html .="		var chart;\n";
			$html .="		$(document).ready(function() {\n";
			$html .="			chart = new Highcharts.Chart({\n";
			$html .="				credits:{\n";
			//$html .="					href: 'http://www.caal.com.br/',\n";
			//$html .="					text: \"caal.com.br\",\n";
			$html .="					enable:false\n";
			$html .="				},\n";
			$html .="				chart: {\n";
			$html .="					renderTo: 'licencas',\n";
			$html .="					type: 'column'\n";
			$html .="				},\n";
			$html .="				title: {\n";
			$html .="					text: 'Quantidade de Licenças'\n";
			$html .="				},\n";
			$html .="				subtitle: {\n";
			$html .="					text: 'Licenças'\n";
			$html .="				},\n";
			$html .="				xAxis: {\n";
			$html .="					categories: [\n";
			$html .="						'Windows 8', 'Windows 7', 'Windows XP', 'Office 2003', 'Office 2007', 'Office 2010', 'Office 2013'\n";
			$html .="					]\n";
			$html .="				},\n";
			$html .="				yAxis: {\n";
			$html .="					min: 0,\n";
			$html .="					title: {\n";
			$html .="						text: 'Quantidade'\n";
			$html .="					}\n";
			$html .="				},\n";
			$html .="				legend: {\n";
			$html .="					backgroundColor: '#FFFFFF'\n";
			$html .="				},\n";
			$html .="				plotOptions: {\n";
			$html .="					column: {\n";
			$html .="						stacking: 'normal'\n";
			$html .="					}\n";
			$html .="				},\n";
			$html .="				series: [\n";
			$html .="					{\n";
			$html .="						name: 'Total',\n";
			$html .="						data: [ windows8, windowsSeven, windowsXP, office2003,office2007, office2010, office2013 ],\n";
			$html .="						stack: 'Total',\n";
			$html .="						dataLabels: {\n";
			$html .="							enabled: true,\n";
			$html .="							color: '#FFFFFF',\n";
			$html .="							align: 'center'\n";
			$html .="						}\n";
			$html .="					}\n";
			$html .="				]\n";
			$html .="			});\n";
			$html .="		});\n";
			$html .="	});\n";
			$html .="</script>\n";
			//$html .="<div id=\"licencas\" style=\"min-width: 400px; height: 400px; margin: 0 auto\"></div>\n";
			
			echo $html;
		?>
	</head>

	<body>
		<div id="licencas" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
	</body>
</html>
