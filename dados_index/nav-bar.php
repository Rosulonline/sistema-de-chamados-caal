<?php
	include_once '../funcoes.php';
	$login = protegePagina();
	$pagina_atual = retorna_pagina_atual();
	$filiais = retorna_filiais();
	$setores = null;
	$usuarios = null;
	$aux_usr = null;
	$menu = "";
	$menu .= "<div id='nav-bar'>\n";
	$menu .= "	<ul>\n";
    $id = 0;
    
    if(isset($_SESSION['id_comum'])){
        $id = $_SESSION['id_comum'];
    }else if(isset($_SESSION['id_resp'])){
        $id = $_SESSION['id_resp'];
    }
	if($login != 0){
		
		
		
		if($login == (1 || 2)){
			$menu .= "		<li><a>".$_SESSION['nome']."</a>\n";
			$menu .= "			<ul>\n";
			$menu .= "				<li><a href=\"/usuario.php?id=".$id."\">Atualizar dados</a>\n";
			$menu .= "				<li><a href=\"#\">Chamados Abertos</a>\n";
			$menu .= "				<li><a href='/sair.php'>Encerrar Sessão</a></li>\n";
			$menu .= "			</ul>\n";
			$menu .= "		</li>\n";
		}
		
		if($login == 2){
			$menu .= "<li><a>Filiais</a>\n";			
			$menu .= "	<ul>\n";
			for($i=0;$i<count($filiais);$i++){
				$setores = retorna_setores($filiais[$i]['F_id']);
				$menu .= "		<li><a href=\"#\">".$filiais[$i]['F_nome']."</a>\n";
				$menu .= "			<ul>\n";
				for($j=0;$j<count($setores);$j++){
					$usuarios = retorna_usuarios($filiais[$i]['F_id'],$setores[$j]['S_id']);
					$menu .= "				<li><a href=\"#\">".$setores[$j]['S_nome']."</a>\n";
					$menu .= "					<ul>\n";
					for($k=0;$k<count($usuarios);$k++){
						$aux_usr = explode(" ", $usuarios[$k]['U_nome']); 
						$menu .= "						<li><a href=\"/usuario.php?id=".$usuarios[$k]['U_id']."\">".$aux_usr[0]." ".$aux_usr[(count($aux_usr) -1)]."</a></li>\n";
					}
					$menu .= "					</ul>\n";
					$menu .= "				</li><!-- #".$setores[$j]['S_nome']." -->\n";					
				}
				$menu .= "			</ul>\n";
				$menu .= "		</li><!-- #".$filiais[$i]['F_nome']." -->\n";
			}
			$menu .= "	</ul>\n";
			$menu .= "</li><!-- #Filiais -->\n";
		}
		
		if($pagina_atual != "index_resp"  && $pagina_atual != "index_comum"){
			$menu .= "		<li><a href='/index.php'>Página Inicial</a></li>\n";
		}
		
		if($login == 2){
			if($pagina_atual == "index_resp"){
				$menu .= "		<li><a href='/relatorio.php'>Relatórios</a></li>\n";
			}else if($pagina_atual == "chamado"){
				$menu .= "		<li><a href='/imprimir/chamado.php?id=".$id_chamado."'>Imprimir</a></li>\n";
			}else if($pagina_atual == "usuario"){
				$menu .= "		<li><a href='/imprimir/usuario.php?usuario=".$id_usuario."'>Imprimir</a></li>\n";
			}else if($pagina_atual == "equipamento"){
				$menu .= "		<li><a href='/imprimir/equipamento.php?id_equipamento=".$id_equipamento."'>Imprimir</a></li>\n";
			}	
		}
		
		$menu .= "		<li><a class='chamado-novo' href='/formularios/cadastro_chamado.php'>Novo Chamado</a></li>\n";
		if($login == 2){
			$menu .= "		<li><a><form action='/buscar.php' method='get'><input class='input-busca' name='nome' type='text' title='Busca' /></form></a></li>";
		}
		
	}else{
		$menu .= "		<li><a>Usuário não autenticado.</a></li>\n";
	}
	
	$menu .= "	</ul>\n";
	$menu .= "</div><!-- #nav-bar -->\n";
	
	$script_menu = "";
	$script_menu .= "<script type=\"text/javascript\">\n";
	/*
	$script_menu .= "
		String.prototype.filterData = function() {
			var str = this.toLowerCase(),
				specialChars = [
					{val:'a', let:'áàãâä'},
					{val:'e', let:'éèêë'},
					{val:'i', let:'íìîï'},
					{val:'o', let:'óòõôö'},
					{val:'u', let:'úùûü'},
					{val:'c', let:'ç'},
				],
				regex;
				
				for (var i in  specialChars) {
					regex = new RegExp('[' + specialChars[i].let + ']', 'g');
					str = str.replace(regex, specialChars[i].val);
					regex = null;
				}
				
				return str;
		};
	";
	 * 
	 */
	$script_menu .= "	$(function() {\n";
	$script_menu .= "		$('.input-busca').val($('.input-busca').attr('title'));\n";
	$script_menu .= "		$('.input-busca').focusout(function(){\n";
	$script_menu .= "			$('.input-busca-in').switchClass('input-busca-in', 'input-busca', 200 );\n";
	$script_menu .= "			$(this).val($(this).attr('title'));\n";
	$script_menu .= "		});\n";
	$script_menu .= "		$('.input-busca').focusin(function(){\n";
	$script_menu .= "			$( '.input-busca' ).switchClass('input-busca', 'input-busca-in', 200 );\n";
	$script_menu .= "			$(this).val('');\n";
	$script_menu .= "		});\n";
	$script_menu .= "		$('.input-busca').keypress(function(event){\n";
	$script_menu .= "			if(event.which == 13 ){\n";
	$script_menu .= "				$( this ).submit();\n";
	$script_menu .= "			}\n";
	$script_menu .= "		})\n";
	$script_menu .= "		.tooltip({ content: 'Digite alguma informação e pressione ENTER para confirmar' })\n";
	//$script_menu .= "		.filterData()";
	$script_menu .= "		.autocomplete({\n";
	$script_menu .= "			source:'/json/busca.php',\n";
	$script_menu .= "			minLength: 3,\n";
	$script_menu .= "			select: function(event, ui) {\n";
	$script_menu .= "						location.replace('/usuario.php?id=' + ui.item.id);\n";
	$script_menu .= "					}\n";
	$script_menu .= "		});\n";
	$script_menu .= "		$('.chamado-novo').click(function(event){\n";
	$script_menu .= "			event.preventDefault();\n";
	$script_menu .= "			$.post('/novo/dados_temp_formulario.php', { novo: true } ).done(function(){	location.replace($('.chamado-novo').attr('href')); });\n";
	$script_menu .= "		});\n";
	$script_menu .= "	});\n";
	$script_menu .= "</script>\n";
	$script_menu .= "<script>
      (function($) {
        var \$nav = \$('#nav-bar');
        \$nav.find('li').click(function() {
          \$(this).children('ul').show() // Show the submenu
          .prev().addClass('active'); // Add `active` class to parent menu link
          \$(this).parent().data('clicked', null);
        }, function() {
          \$(this).children('ul').hide() // Hide the submenu
          .prev().removeClass('active'); // Remove `active` class from parent menu link
          \$(this).parent().data('clicked', null);
        }).children('a').filter(function() {
        	\$(this).parent().data('clicked', null);
            return $(this).next().is('ul'); // Filter to disable link that has an `ul` element after it (a submenu)
            
        }).click(function() {
          \$(this).toggleClass('active').next()[\$(this).next().is(':hidden') ? \"show\" : \"hide\"](); // Toggle submenu on mousedown. Would be better if you use touchstart event or something similar
          \$(this).parent().data('clicked', null);
          return false;
        }).click(function() {
          return false; // Disable link on click
        });
      })(jQuery);
    </script>";
	
	echo $menu;
	echo $script_menu;
?>