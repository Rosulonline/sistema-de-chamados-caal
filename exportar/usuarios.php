<?php
	include("../funcoes.php");
	
	$logado = protegePagina();
	
	$usuarios = retorna_usuarios(null,null,null,TRUE);
	
	$dados_csv = retorna_dadosCSV();
	
	/*
	
	for ($i=0; $i < count($dados_csv) ; $i++) {
		echo "Nome: ".$dados_csv[$i]['nome'];
		$cont = 0;
		for ($j=0; $j < count($usuarios) ; $j++) { 
			//echo "Nome: ".$usuarios[$j]['U_nome'];
		
			if(strcmp(ucwords(strtolower($dados_csv[$i]['nome'])), ucwords(strtolower($usuarios[$j]['U_nome']))) == 0){
				$cont = 1;
				atualiza_usuario($usuarios[$j]['U_id'],ucwords(strtolower($usuarios[$j]['U_nome'])),$usuarios[$j]['U_ramal'],$usuarios[$j]['U_email'],$usuarios[$j]['U_usuario'],$usuarios[$j]['U_senha'],$usuarios[$j]['U_msn'],$usuarios[$j]['U_email_msn'],$usuarios[$j]['U_disp'],$usuarios[$j]['U_net'],$usuarios[$j]['U_ativo'],$usuarios[$j]['S_id'],$dados_csv[$i]['matricula'], $dados_csv[$i]['data_nascimento']);
			} 
		
			//atualiza_usuario($usuarios[$j]['U_id'],ucwords(strtolower($usuarios[$j]['U_nome'])),$usuarios[$j]['U_ramal'],$usuarios[$j]['U_email'],$usuarios[$j]['U_usuario'],$usuarios[$j]['U_senha'],$usuarios[$j]['U_msn'],$usuarios[$j]['U_email_msn'],$usuarios[$j]['U_disp'],$usuarios[$j]['U_net'],$usuarios[$j]['U_ativo'],$usuarios[$j]['S_id'],null, null);
		}
		if($cont == 0){
			$setor = 1;
			switch ($dados_csv[$i]['setor']) {
				case 'ADMINISTRACAO INDUSTRIAL':
					$setor = 16;
					break;
				case 'AGROVETERINARIA':
					$setor = 39;
					break;
				case 'ALMOXARIFADO BR':
					$setor = 17;
					break;
				case 'APOIO TECNICO (SESMT)':
					$setor = 26;
					break;
				case 'ARQUIVO \\ TRANSPORTE':
					$setor = 6;
					break;
				case 'ASSISTENCIA TECNICA':
					$setor = 41;
					break;
				case 'ASSOCIADOS':
					$setor = 13;
					break;
				case 'BR-ADMINIST. INDUSTRIAL':
					$setor = 16;
					break;
				case 'BR-ARMAZENAGEM':
					$setor = 19;
					break;
				case 'BR-BENEFICIAMENTO':
					$setor = 28;
					break;
				case 'BR-EMPACOTAMENTO':
					$setor = 20;
					break;
				case 'BR-FABRICA DE RACOES':
					$setor = 29;
					break;
				case 'BR-GERENCIA INDUSTRIAL':
					$setor = 16;
					break;
				case 'BR-LIMPEZA/CONSERVACAO':
					$setor = 16;
					break;
				case 'BR-MANUTENCAO ELETRICA':
					$setor = 22;
					break;
				case 'BR-MANUTENCAO MECANICA':
					$setor = 22;
					break;
				case 'BR-RECEBIMENTO E ARMAZEM':
					$setor = 28;
					break;
				case 'BR-SECAGEM':
					$setor = 28;
					break;
				case 'BR-SEGURANCA':
					$setor = 23;
					break;
				case 'CONTABILIDADE':
					$setor = 6;
					break;
				case 'CONTAS A PAGAR':
					$setor = 7;
					break;
				case 'CREDITO E CADASTRO':
					$setor = 15;
					break;
				case 'CTE-CENTRO DE TREINAMENTO':
					$setor = 16;
					break;
				case 'CURSO APRENDIZ - ADM':
					$setor = 9;
					break;
				case 'CURSO APRENDIZ - AGROVETERINARIA':
					$setor = 39;
					break;
				case 'CURSO APRENDIZ - BF':
					$setor = 31;
					break;
				case 'CURSO APRENDIZ - BR':
					$setor = 16;
					break;
				case 'CURSO APRENDIZ - C.COMERC':
					$setor = 34;
					break;
				
				case 'CURSO APRENDIZ - INSUMOS':
					$setor = 40;
					break;				
				case 'CURSO APRENDIZ - RACAO':
					$setor = 29;
					break;
				case 'CURSO APRENDIZ - RH':
					$setor = 10;
					break;
				case 'DEPARTAMENTO PESSOAL':
					$setor = 9;
					break;
				case 'INFORMATICA':
					$setor = 8;
					break;
				case 'INSUMOS':
					$setor = 40;
					break;
				case 'LIMPEZA\\ MANUT. PREDIAL':
					$setor = 16;
					break;
				case 'MARKETING':
					$setor = 2;
					break;
				case 'PECAS':
					$setor = 34;
					break;
				case 'PORTARIA \\ RECEPCAO':
					$setor = 23;
					break;
				case 'RECURSOS HUMANOS':
					$setor = 10;
					break;
				case 'REFEITORIO':
					$setor = 24;
					break;
				case 'RSM-SECAGEM':
					$setor = 30;
					break;
				case 'SECRETARIA DIRECAO':
					$setor = 1;
					break;
				case 'SERVICOS GERAIS':
					$setor = 9;
					break;
				case 'SESMT/SERV.MEDICOS':
					$setor = 26;
					break;
				
				case 'TELEFONISTA':
					$setor = 11;
					break;
				case 'TRANSPORTES INDUSTRIAL':
					$setor = 32;
					break;
				case 'UBS-UNIDADE B.F.':
					$setor = 31;
					break;
				case 'USINA GERACAO DE ENERGIA':
					$setor = 27;
					break;				
				
				default:
					$setor = 1;
					break;
			}
			$aux 		= str_word_count($dados_csv[$i]['nome'],1);
			$primeiro	= str_word_count($aux[0],2);
			$ultimo		= str_word_count(end($aux[0]),2);
			$usuario	= $aux[0].".".end($aux);
			$senha		= strtolower($primeiro[0]).strtolower($ultimo[0])."0000";
			incluir_usuario($dados_csv[$i]['nome'],'-','-',$usuario,$senha,0,"-",0,0,$setor,1);
		}
		echo "</br>";
	}
	*/
	for ($j=0; $j < count($usuarios) ; $j++) {
		 
		atualiza_usuario($usuarios[$j]['U_id'],ucwords(strtolower($usuarios[$j]['U_nome'])),$usuarios[$j]['U_ramal'],$usuarios[$j]['U_email'],$usuarios[$j]['U_usuario'],$usuarios[$j]['U_senha'],$usuarios[$j]['U_msn'],$usuarios[$j]['U_email_msn'],$usuarios[$j]['U_disp'],$usuarios[$j]['U_net'],$usuarios[$j]['U_ativo'],$usuarios[$j]['S_id'],null, null);
	}
	
?>