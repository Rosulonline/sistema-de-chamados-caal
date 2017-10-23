<!DOCTYPE html>
<html lang="pt-br">
	<?php include 'header.html'; ?>
<script>
    $(function() {
        var colors = Highcharts.getOptions().colors, categories = ['Pe�as Agr�colas', 'Compras e Apoio', 'Agroel�trica', 'Tintas e Ferragem', 'Coopservice (Pneus)', 'Veterin�ria'], name = 'Browser brands', data = [{
            y : 9,
            color : colors[0],
            drilldown : {
                name : 'Pe�as Agr�colas',
                categories : ['Encarregado', 'Auxiliar de Loja', 'Vendedor', 'Caixa', 'Operada de Caixa', 'Auxiliar Limpeza'],
                data : [1, 4, 1, 1, 1, 1],
                color : colors[0]
            }
        }, {
            y : 6,
            color : colors[1],
            drilldown : {
                name : 'Compras e Apoio',
                categories : ['Encarregado', 'Comprador', 'Conferente', 'Motorista', 'Aux. Estoque', 'Aux. Adm. Lj Pe�as'],
                data : [1, 1, 1, 1, 1, 1],
                color : colors[1]
            }
        }, {
            y : 4,
            color : colors[2],
            drilldown : {
                name : 'Agroel�trica',
                categories : ['Operador de Caixa', 'Auxiliar de loja', 'Conferente', 'Vendedor'],
                data : [1, 1, 1, 1],
                color : colors[2]
            }
        }, {
            y : 7,
            color : colors[3],
            drilldown : {
                name : 'Tintas e Ferragem',
                categories : ['Encarregado', 'Vendedor', 'Operador de Caixa', 'Auxiliar de Dep�sito'],
                data : [1, 4, 1, 1],
                color : colors[3]
            }
        }, {
            y : 4,
            color : colors[4],
            drilldown : {
                name : 'Coopservice (Pneus)',
                categories : ['Encarregado', 'Operador de Caixa', 'Geometrista'],
                data : [1, 1, 2],
                color : colors[4]
            }
        }, {
            y : 14,
            
            color : colors[5],
            drilldown : {
                name : 'Veterin�ria',
                categories : ['Vendedor', 'Supervisor Veterin�ria', 'Auxiliar de Dep�sito', 'Auxiliar de Loja', 'Operador Caixa', 'M�dico Veter. Treinee', 'M�dico Veterin�rio', 'Auxiliar de Estoque', 'An�lista de Estoques', 'Conferente'],
                data : [4, 1, 2, 1, 1, 1, 1, 1, 1, 1],
                color : colors[5]
            }
        }];

        // Build the data arrays
        var browserData = [];
        var versionsData = [];
        for (var i = 0; i < data.length; i++) {

            // add browser data
            browserData.push({
                name : categories[i],
                y : data[i].y,
                color : data[i].color
            });

            // add version data
            for (var j = 0; j < data[i].drilldown.data.length; j++) {
                var brightness = 0.2 - (j / data[i].drilldown.data.length) / 5;
                versionsData.push({
                    name : data[i].drilldown.categories[j],
                    y : data[i].drilldown.data[j],
                    color : Highcharts.Color(data[i].color).brighten(brightness).get()
                });
            }
        }

        // Create the chart
        $('#container').highcharts({
            chart : {
                type : 'pie'
            },
            title : {
                text : 'Total: 44 Funcion�rios'
            },

            plotOptions : {
                pie : {
                    shadow : false,
                    center : ['50%', '50%']
                }
            },
            tooltipe : {
                name : 'Funcion�rios'
            },
            series : [{
                name : 'Setor',
                data : browserData,
                size : '60%',
                dataLabels : {

                    color : 'white',
                    distance : -30
                }
            }, {
                name : 'Fun��o',
                data : versionsData,
                size : '80%',
                innerSize : '60%',
                dataLabels : {
                    formatter : function() {
                        // display only if larger than 1
                        return this.y > 0 ? '<b>' + this.point.name + ': ' + this.y : null;
                    }
                }
            }]
        });
    });

</script>


	<body>
		<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		
		
	</body>
</html>
