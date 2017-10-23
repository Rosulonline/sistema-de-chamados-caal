<?php
	/**
	 * ARQUIVO COM FUNÇÕES PARA O FUNCIONAMENTO DO SISTEMA
	 * 
	 * Este arquivo contém funções que permitem a manutenção de todo o sistema de Chamados
	 * @author Madson Verdi Junior <madsonverdijr@gmail.com>
	 * @version 1.0
	 * @package funcoes
	 */
	$_server['conectaServidor']		= true;						// Abre uma conexão com o servidor MySQL?
	$_server['abreSessao']			= true;						// Inicia a sessão com um session_start()?
	
	$_server['TempoCookie']			= 86400;					// Tempo de duração de um Cookie
	
	$_server['servidor']			= 'localhost';				// Servidor MySQL
	$_server['usuario']				= 'chamados';				// Usuário MySQL
	$_server['senha']				= 'ASNuBMRRYZ9j46G9';		// Senha MySQL
	$_server['banco']				= 'chamados';				// Banco de dados MySQL
	$_server['paginaLogin']			= '/index.php';				// Página de login
	$_server['codificacao']			= 'utf8';					// Codificação do Banco de dados MySQL
	
	$_server['hostServer']			= '192.168.1.9';			// Servidor de dominio
	$_server['dominio']				= 'CAALADM';				// Nome do dominio
	
	$_server['emailSuporte']		= "suporte@caal.com.br";	// Email para recebimento dos chamados
	$_server['nomeSuporte']			= "Suporte Informática";	// Nome do remetente de e-mail dos chamados
	$_server['padraoItensPagina']	= 30;						// Quantidade padrão de itens exibidos por páginas 
	
	// Verifica se precisa fazer a conexão com o MySQL
	if ($_server['conectaServidor'] == true) {
		$_server['link'] = mysql_connect($_server['servidor'], $_server['usuario'], $_server['senha']) or die("MySQL: não foi possível conectar-se ao servidor [".$_server['servidor']."].");
		mysql_select_db($_server['banco'], $_server['link']) or die("MySQL: não foi possível conectar-se ao banco de dados [".$_server['banco']."].");
		mysql_set_charset($_server['codificacao'],$_server['link']);
	}
	
	// Verifica se precisa iniciar a sessão
	if ($_server['abreSessao'] == true) {
		session_start();
		session_cache_limiter('private_no_expire');
		//session_cache_expire(30);
	}
	
	/**
	 * FUNÇÃO QUE PROTEGE A PAGINA CONTRA USUÁRIOS NÃO LOGADOS
	 *
	 * 0: erro, 1: usuário comum, 2: usuário adm
	 * @param null
	 *
	 * @return int
	 * 
	 * @see expulsaVisitante();
	 */
	function protegePagina($login = FALSE) {
		global $_server;
		
		if (!isset($_SESSION['id_resp']) && !isset($_SESSION['id_comum'])) {
			if($login == true){
				return 0;
			}else{
				expulsaVisitante();
			}
		}
		
		if(isset($_SESSION['id_resp'])){
			return 2;
		}else{
			if(isset($_SESSION['id_comum'])){
				return 1;
			}else{
				expulsaVisitante();
			}
		}
		
		
		return 0;
	}
	
	
	/*
	 * EXPULSA VISITANTE E RETORNA PARA A PÁGINA DE LOGIN 
	 */
	function expulsaVisitante() {
		global $_server;
		session_destroy();
		header("Location: ".$_server['paginaLogin']."?erro=Usuário não autenticado");
	}
	
	/**
	 *	FUNCÃO QUE RETORNA O RESULTADO DE PING
	 *
	 *	@param string $ip	- ip que deseja que seja pingado no formato 000.000.000.000
	 *
	 *	@return string		- retorna o resultado do PING
	 */
	function ping($ip){
		$ping = `ping $ip`;
		return $ping;
	}
	
	/**
	 * Função que inclui uma filial com seus dados
	 * 
	 * @package inclusão
	 *
	 * @param string $nome		- Nome para a filial a ser incluida
	 * @param string $endereco	- Endereço para a filial a ser incluida
	 * @param string $telefone	- Telefone para a filial a ser incluida
	 *
	 */
	function incluir_filial($nome,$endereco,$telefone){
		$nome		= htmlspecialchars($nome,		ENT_QUOTES);
		$endereco	= htmlspecialchars($endereco,	ENT_QUOTES);
		$telefone	= htmlspecialchars($telefone,	ENT_QUOTES);
		global $_server;
		$sql = "
			INSERT INTO
				FILIAL(
					id,
					nome,
					endereco,
					telefone
				) VALUES(
					NULL,
					'".$nome."',
					'".$endereco."',
					'".$telefone."'
				)
		;";
		$query = mysql_query($sql)or die($sql." ERRO incluir_filial(): ".mysql_error());
	}
	
	/**
	 * Função que inclui um setor
	 * 
	 * @package inclusão
	 *
	 * @param string $nome		- Nome para a filial a ser incluida
	 * @param int $id_filial	- ID da filial a qual o setor está vinculado
	 *
	 */
	function incluir_setor($nome, $id_filial){
		$nome = htmlspecialchars($nome,	ENT_QUOTES);
		global $_server;
		$query = mysql_query("
			INSERT INTO
				SETOR(
					id,
					nome,
					FILIAL_id
				) VALUES(
					NULL,
					'".$nome."',
					'".$id_filial."'
				);
		")or die("ERRO AO INCLUIR SETOR: ".mysql_error());
		
	}
		
	/**
	 * INCLUI NA TABELA USUARIO OS SEUS RESPECTIVOS DADOS
	 * 
	 * @package inclusão
	 *	
	 * @param string 	$nome			- Nome do usuário
	 * @param string 	$ramal			- Ramal do usuário
	 * @param string 	$email			- Email do usuário
	 * @param string 	$usuario		- Usuário de acesso
	 * @param string 	$senha 			- Senha de acesso
	 * @param int		$msn 			- 0: não possuí acesso a msn, 1: possuí acesso
	 * @param string	$email_msn		- email do msn
	 * @param int		$dispositivos	- 0: não possuí acesso a dispositivos removíveis, 1: possuí acesso
	 * @param int		$internet		- 0: não possuí acesso liberado a internet, 1: possuí acesso
	 * @param int		$ativo			- 0: não possuí acesso a msn, 1: possuí acesso
	 * @param int		$setor			- ID do setor ao qual o usuário está vinculado
	 */
	function incluir_usuario($nome,$ramal,$email,$usuario,$senha,$msn,$email_msn,$dispositivos,$internet,$id_setor,$ativo){
		global $_server;
		
		$nome			= trim(htmlspecialchars($nome			));
		$ramal			= trim(htmlspecialchars($ramal			));
		$email			= trim(htmlspecialchars($email			));
		$usuario		= trim(htmlspecialchars($usuario		));
		$senha			= trim(htmlspecialchars($senha			));
		$msn			= trim(htmlspecialchars($msn			));
		$email_msn		= trim(htmlspecialchars($email_msn		));
		$dispositivos	= trim(htmlspecialchars($dispositivos	));
		$internet		= trim(htmlspecialchars($internet		));
		$id_setor		= trim(htmlspecialchars($id_setor		));
		$ativo			= trim(htmlspecialchars($ativo			));
		$consulta		= "";
		
		$consulta = "";
		$consulta .= "	INSERT INTO";
		$consulta .= "		USUARIO(";
		$consulta .= "			id,";
		$consulta.= "			nome,";
		$consulta.= "			ramal,";
		$consulta.= "			email,";
		$consulta.= "			SETOR_id,";
		$consulta.= "			usuario,";
		$consulta.= "			senha,";
		$consulta.= "			msn,";
		$consulta.= "			email_msn,";
		$consulta.= "			disp,";
		$consulta.= "			net,";
		$consulta.= "			ativo";
		$consulta.= "		) VALUES(";
		$consulta.= "			NULL,";
		$consulta.= "			'".$nome."',";
		$consulta.= "			'".$ramal."',";
		$consulta.= "			'".$email."',";
		$consulta.= "			".$id_setor.",";
		$consulta.= "			'".$usuario."',";
		$consulta.= "			'".$senha."',";
		$consulta.= "			".$msn.",";
		$consulta.= "			'".$email_msn."',";
		$consulta.= "			".$dispositivos.",";
		$consulta.= "			".$internet.",";
		$consulta.= "			".$ativo."";
		$consulta.= "		)";
		$consulta.= "	;";
		
		$query = mysql_query($consulta) or die($consulta." : ERRO AO INCLUIR Usuário: ".mysql_error());
		
	}
	
	/**
	 * RELACIONA COMPUTADOR A EQUIPAMENTO
	 * 
	 * @package inclusão
	 *
	 * @param int $id_computador - ID do computador
	 * @param int $id_componente - ID do componente
	 */
	function relaciona_computador_componente($id_computador, $id_componente){
		global $_server;
		$consulta = "
			INSERT INTO 
				`chamados`.`COMPUTADOR_has_ITENS` (
					`COMPUTADOR_id`,
					`EQUIPAMENTO_id`)
				VALUES (
					".$id_computador.",
					".$id_componente.")
		;";
		$query = mysql_query($consulta)or die($consulta." ERRO relaciona_computador_componente(): ".mysql_error());
	}
	
	/**
	 * INCLUI NA TABELA EQUIPAMENTO OS SEUS RESPECTIVOS DADOS
	 * 
	 * @package inclusão
	 *
	 * @param string	$nome			- Nome para o equipamento
	 * @param string	$descricao		- Descrição do equipamento
	 * @param string	$numero_serie	- Número de série do equipamento
	 * @param string	$mac			- MAC adress do equipamento
	 * @param string	$ip				- IP adress do equipamento
	 * @param string	$numero_nota	- Número de série da nota em que o equipamento pertence
	 * @param int		$classe			- ID da classe a qual o equipamento pertence
	 *
	 * @return int $id	- ID do equipamento registrado
	 */
	function incluir_equipamento($nome,$descricao,$numero_serie,$mac,$ip,$numero_nota,$classe){
		global $_server;
		
		$nome			= trim(	htmlspecialchars($nome,			ENT_QUOTES));
		$descricao		= trim(	htmlspecialchars($descricao,	ENT_QUOTES));
		$numero_serie	= trim(	htmlspecialchars($numero_serie,	ENT_QUOTES));
		$mac			= trim(	htmlspecialchars($mac,			ENT_QUOTES));
		$ip				= trim(	htmlspecialchars($ip,			ENT_QUOTES));
		$numero_nota	= trim(	htmlspecialchars($numero_nota,	ENT_QUOTES));
		
		
		$sql			= "SHOW TABLE STATUS LIKE 'equipamento'";
		$query			= mysql_query($sql)or die($sql."ERRO incluir_equipamento(): ".mysql_error());
		$row			= mysql_fetch_array($query);
		$id				= $row['Auto_increment'];
		mysql_free_result($query);
		
		$consulta = "
			INSERT INTO\n
				EQUIPAMENTO(\n
					id,\n
					descricao,\n
					nome,\n
					mac,\n
					ip,\n
					nf,\n
					ns,\n
					CLASSE_id\n
				) VALUES(\n
					NULL,\n
					'".$descricao."',\n
					'".$nome."',\n
					'".$mac."',\n
					'".$ip."',\n
					'".$numero_nota."',\n
					'".$numero_serie."',\n
					".$classe."\n
				);
		";
		
		$query = mysql_query($consulta)or die("SQL: ".$consulta." | ERRO AO INCLUIR EQUIPAMENTO: ".mysql_error());
		mysql_free_result($query);
		
		
		return $id;
	}
	
	/**
	 * INCLUI UM NOVO CHAMADO ABERTO
	 * 
	 * @package inclusão
	 * 
	 * @param string $assunto			- Assunto do chamado
	 * @param string $descricao			- Descrição do chamado
	 * @param string $id_responsavel	- ID do responsável pelo chamado
	 * @param string $id_usuario		- ID do usuário que está incluindo o chamado
	 * @param string $id_equipamento	- ID do equipamento que o usuário necessita de assistência
	 * @param string $prioridade		- Data na qual o usuário necessita que o chamado seja atendido no formato: YYYY-MM-DD HH:MM:SS
	 * 
	 */
	function incluir_chamado($assunto, $descricao, $id_responsavel, $id_usuario, $id_equipamento, $prioridade){
		global $_server;
		$assunto	= htmlspecialchars($assunto,	ENT_QUOTES);
		$descricao	= htmlspecialchars($descricao,	ENT_QUOTES);
		
		if($prioridade == null){
			$prioridade = "'".date("Y-m-d")." 18:00:00'";
		}else{
			$prioridade = "'".$prioridade."'";
		}
		$consulta = "
			INSERT INTO
				CHAMADO(
					id, 
					assunto, 
					descricao, 
					aberto,
					data_abertura, 
					RESPONSAVEL_id, 
					USUARIO_id, 
					EQUIPAMENTO_id,
					prioridade					
				)VALUES(
					NULL,
					'".$assunto."',
					'".$descricao."',
					1,
					NOW(),
					".$id_responsavel.",
					".$id_usuario.",
					".$id_equipamento.",
					".$prioridade."
				);
		";
		$query = mysql_query($consulta)or die($consulta."ERRO AO INCLUIR CHAMADO: ".mysql_error());
		//incluir_log("incluir_chamado()", $_SESSION['usuario'], $assunto." | ".$descricao." | ".$id_responsavel." | ".$id_usuario." | ".$id_equipamento." | ".$prioridade);
		$consulta = "SELECT id FROM CHAMADO ORDER BY id DESC LIMIT 1;";
		$aux = null;
		$query = mysql_query($consulta)or die($consulta.": ERRO incluir_chamado();: ".mysql_error());
		while($row = mysql_fetch_array($query)){
			$aux = $row['id'];
		}	
		
		return $aux;
	}
	
	/**
	 * INCLUI UMA SOLUÇÃO PARA O CHAMADO
	 * 
	 * @package inclusão
	 *
	 * @param string	$descricao		- Descrição da solução
	 * @param string	$previsao		- Previsão para que seja realizada a solução no formato: YYYY-MM-DD HH:MM:SS
	 * @param int		$id_chamado		- ID do chamado ao qual a solução está sendo feita
	 * @param int		$id_responsavel	- ID do responsável pela solução
	 * @param int		$solucionado	- 0: não solucionado, 1: solucionado, 2: reaberto
	 * @param string	$data_solucao	- SE $solucionado = 1 {$data_solucao = NOW()} SENAO {data informada no formato: YYYY-MM-DD HH:MM:SS}
	 *
	 * @see fechar_chamado()
	 */
	function incluir_solucao($descricao, $previsao, $id_chamado,$id_responsavel,$solucionado,$data_solucao){
		
		$descricao	= htmlspecialchars($descricao,	ENT_QUOTES);
		
		
		global $_server;
		
		//faz as alterações necessárias nas strings para incluir no banco
		
		if($data_solucao==NULL)
			$data_solucao = "NULL";
		else
			$data_solucao = "'".$data_solucao."'";
		if($previsao != 'NOW()'){
			if($previsao != NULL)	
				$previsao = "'".$previsao."'";
			else
				$previsao = '0';
		}
		if($solucionado == 1){
			$data_solucao = "NOW()";
		}
			
		$consulta = "
			INSERT INTO
				SOLUCAO(
					id, 
					descricao, 
					data_inicio,
					data_solucionado,
					previsao,
					solucionado, 
					CHAMADO_id, 
					RESPONSAVEL_id
				)VALUES(
					NULL,
					'".$descricao."',
					NOW(),
					".$data_solucao.",
					".$previsao.",
					".$solucionado.",
					".$id_chamado.",
					".$id_responsavel."
				);
		";
		$query = mysql_query($consulta)or die($consulta." : ERRO FUNÇÃO: 'incluir_solucao()': ".mysql_error());
		if($solucionado == 1){
			fechar_chamado($id_chamado);
		}else{
			chamado_emAndamento($id_chamado);
		}
	}
	
	/**
	 *	RETORNA O NOME DO MÊS
	 *
	 *	@param int $mes				- Valor do mês desejado
	 *
	 *	@return string $string_mes	- nome do mês
	 */
	function retorna_mes($mes){
		global $_server;
		switch($mes){
			case '01': 
				$string_mes = "Janeiro";
				break;
			case '02': 
				$string_mes = "Fevereiro";
				break;
			case '03': 
				$string_mes = "Março";
				break;
			case '04': 
				$string_mes = "Abril";
				break;
			case '05': 
				$string_mes = "Maio";
				break;
			case '06': 
				$string_mes = "Junho";
				break;
			case '07':
				$string_mes = "Julho";
				break;
			case '08': 
				$string_mes = "Agosto";
				break;
			case '09': 
				$string_mes = "Setembro";
				break;
			case '10': 
				$string_mes = "Outubro";
				break;
			case '11': 
				$string_mes = "Novembro";
				break;
			case '12': 
				$string_mes = "Dezembro";
				break;
			default:
				$string_mes = "ERRO";
				break;
		}
		return $string_mes;
	}
	
	/**	FUNÇÃO QUE RETORNA A DATA FORMATA 
	 *
	 *	@param string $data	- Data a ser formatada no formato: AAAA-MM-DD HH:MM:SS
	 *
	 *	@return string		- Data formatada no seguinte formato: DD/MM/AAAA HH:MM:SS
	 */
	function formata_data($data,$somente_data = false){
		if($data != NULL){
			$aux = explode(" ",$data);
			$dia = explode("-",$aux[0]);
			$mes = retorna_mes($dia[1]);
			if($somente_data == false){
				$data_formatada = $dia[2]." de ".$mes." de ".$dia[0]." as ".$aux[1];
			}else{
				$data_formatada = $dia[2]." de ".$mes." de ".$dia[0];
			}
			//$data_formatada = $dia[2]."/".$dia[1]."/".$dia[0]." ".$aux[1];
		}else{
			$data_formatada = "-";
		}		
		return $data_formatada;
	}
	
	/**
	 *	REDIRECIONA CHAMADO DE UM RESPONSÁVEL PARA OUTRO
	 *
	 *	@param string	$motivo		- Motivo de redirecionamento
	 *	@param int		$de			- ID do antigo responsável
	 *	@param int		$para		- ID do novo responsável
	 *	@param int		$id_chamado	- ID do chamado que está sendo transferido
	 *
	 *	@see altera_responsavel_chamado()
	 */
	function redirecionar_chamado($motivo, $de, $para, $id_chamado){
		global $_server;
		$consulta = "
			INSERT INTO
				REDIRECIONAR(
					id,
					motivo,
					data,
					RESPONSAVEL_id_de,
					RESPONSAVEL_id_para,
					CHAMADO_id
				)VALUES(
					NULL,
					'".$motivo."',
					NOW(),
					".$de.",
					".$para.",
					".$id_chamado."
				);
		";
		$query = mysql_query($consulta)or die($consulta. "ERRO AO REDIRECIONAR CHAMADO: ".mysql_error());
		
		altera_responsavel_chamado($id_chamado,$para);
	}
	
	/**
	 *	ALTERA O RESPONSÁVEL PELO CHAMADO
	 *
	 *	@param int $id_chamado		- ID do chamado
	 *	@param int $id_novo_resp	- ID do novo responsável pelo chamado
	 *
	 */
	function altera_responsavel_chamado($id_chamado, $id_novo_resp){
		global $_server;
		$consulta = "
			UPDATE CHAMADO SET RESPONSAVEL_id = ".$id_novo_resp." WHERE id = ".$id_chamado.";				
		";
		$query = mysql_query($consulta)or die($consulta. " : ERRO AO ATUALIZAR REPONS�VEL NA TABELA CHAMADO: ".mysql_error());
		
	}
	
	/**
	 * ALTERA ESTADO DO CHAMADO
	 * 
	 * @param int $chamado - ID do chamado 
	 * @param int $estado - 0: finalizado, 1:Aberto, 2:Em andamento
	 */
	function alterar_chamado_estado($chamado, $estado){
		global $_server;
		
		$consulta = "";
		$consulta .= "	UPDATE";
		$consulta .= "		chamado";
		$consulta .= "	SET";
		$consulta .= "		aberto = ".$estado;
		$consulta .= "	WHERE";
		$consulta .= "		id=".$chamado;
		$consulta .= "	;";
			
		$query = mysql_query($consulta) or die($consulta." : alterar_chamado_estado(): ".mysql_error());
	}
	
	/**
	 * ALTERA A DESCRICAO DE UM CHAMADO
	 * 
	 * @param int	$chamado	- ID do chamado a ser alterada a descrição
	 * @param sring	$descricao	- Nova descrição a ser incluida no chamado
	 */
	function altera_chamado_descricao($chamado, $descricao){
		global $_server;
		
		$descricao	= trim(	htmlspecialchars($descricao,	ENT_QUOTES));		
		
		$consulta = "";
		$consulta .= "	UPDATE";
		$consulta .= "		chamado";
		$consulta .= "	SET";
		$consulta .= "		descricao = '".$descricao."'";
		$consulta .= "	WHERE";
		$consulta .= "		id=".$chamado;
		$consulta .= "	;";
			
		$query = mysql_query($consulta) or die($consulta." : altera_chamado_descricao(): ".mysql_error());
	}
	
	/**
	 * INCLUI UM LOG NO BANCO DE DADOS
	 * 
	 * @param string $funcao - funcao utilizada
	 * @param string $usuario - usuario que utilizou a funcao
	 * @param string $valor_atual - valor utilizado
	 * @param string $valor_antigo - no caso de ser um update é mantido o registro antigo 
	 */
	function incluir_log($funcao, $usuario, $valor_atual, $valor_antigo = null ){
		global $_server;
		
		if($valor_antigo == null)
			$valor_antigo = "-";
		
		$funcao			= trim(	htmlspecialchars($funcao,		ENT_QUOTES));
		$usuario		= trim(	htmlspecialchars($usuario,		ENT_QUOTES));
		$valor_atual	= trim(	htmlspecialchars($valor_atual,	ENT_QUOTES));
		$valor_antigo	= trim(	htmlspecialchars($valor_antigo,	ENT_QUOTES));
		
		$consulta = "";
		$consulta .= "	INSERT INTO `chamados`.`LOG` (";
		$consulta .= "		`id`,";
		$consulta .= "		`funcao`,";
		$consulta .= "		`usuario`,";
		$consulta .= "		`data`,";
		$consulta .= "		`valorAtual`,";
		$consulta .= "		`valorAntigo`) VALUES (";
		$consulta .= "		NULL,";
		$consulta .= "		'".$funcao."',";
		$consulta .= "		'".$usuario."',";
		$consulta .= "		NOW(),";
		$consulta .= "		'".$valor_atual."',";
		$consulta .= "		'".$valor_antigo."');";
					
		$query = mysql_query($consulta) or die($consulta." : altera_chamado_descricao(): ".mysql_error());
	}
	
	/**
	 *	DEFINE UM CHAMADO COMO FECHADO
	 *
	 *	@param int $id_chamado - ID do chamado que será fechado
	 */
	function fechar_chamado($id_chamado){
		global $_server;
		$consulta = "
			UPDATE
				CHAMADO
			SET
				aberto=0
			WHERE
				id=".$id_chamado."
			;
		";
		$query = mysql_query($consulta)or die($consulta." : ERRO AO FECHAR CHAMADO: ".mysql_error());
		
	}
	
	/**
	 *	DEFINE UM CHAMADO COMO FECHADO
	 *
	 *	@param int $id_chamado - ID do chamado que será fechado
	 */
	function chamado_emAndamento($id_chamado){
		global $_server;
		$consulta = "
			UPDATE
				CHAMADO
			SET
				aberto=2
			WHERE
				id=".$id_chamado."
			;
		";
		$query = mysql_query($consulta)or die($consulta." : ERRO AO modificar CHAMADO: ".mysql_error());
		
	}
	
	/**
	 *	DEFINE UM CHAMADO COMO ABERTO
	 *
	 *	@param int $id_chamado - ID do chamado que será reaberto
	 */
	function reabrir_chamado($id_chamado){
		global $_server;
		$numero_de_dias = 1;
		$prioridade = retorna_soma_data($numero_de_dias);
		$consulta = "
			UPDATE
				CHAMADO
			SET
				aberto=1,
				prioridade = '".$prioridade."'
			WHERE
				id=".$id_chamado."
			;
		";
		$query = mysql_query($consulta)or die($consulta." : ERRO AO REABRIR CHAMADO: ".mysql_error());
	}
	
	/**
	 *	ATRIBUI UM EQUIPAMENTO A UM USUÁRIO
	 *
	 *	@param int $id_usuario		- ID do usuário 
	 *	@param int $id_equipamento	- ID do equipamento
	 */
	function atribuir_equipamento_para_usuario($id_usuario, $id_equipamento){
		global $_server;
		$query = mysql_query("
			INSERT INTO
				USUARIO_has_EQUIPAMENTO(
					USUARIO_id, 
					EQUIPAMENTO_id
				) VALUES(
					".$id_usuario.",
					".$id_equipamento."
				);
		")or die("ERRO AO ATRIBUIR EQUIPAMENTO: ".mysql_error());
		
	}
	
	/**
	 *	ATUALIZA DADOS DO EQUIPAMENTO
	 *	
	 *	@param int		$id_equipamento	- id do equipamento a ser atualizado
	 *	@param string	$descricao		- descricao do equipamento
	 *	@param string	$nome			- nome do equipamento
	 *	@param string	$mac			- endereço físico do equipamento
	 *	@param string	$ip				- endereço ip do equipamento
	 *	@param string	$string			- número da nota fiscal a qual o equipamento esta vinculado
	 *	@param string	$ns				- número de série do equipamento
	 *	@param int		$classe			- ID da classe do equipamento
	 *	@param int		$descartado		- 0: equipamento não descartado, 1: descartado, 2: em manutenção
	 */
	function atualiza_equipamento($id_equipamento, $descricao, $nome, $mac, $ip, $nf, $ns, $classe,$descartado){
		
		$descricao	= trim(	htmlspecialchars($descricao,	ENT_QUOTES));
		if(!$descricao){
			$descricao = "-";
		}
		$nome		= trim(	htmlspecialchars($nome,			ENT_QUOTES));
		if(!$nome){
			$nome = "-";
		}
		$mac		= trim(	htmlspecialchars($mac,			ENT_QUOTES));
		if(!$mac){
			$mac = "-";
		}
		$ip			= trim(	htmlspecialchars($ip,			ENT_QUOTES));
		if(!$ip){
			$ip = "-";
		}
		$nf			= trim(	htmlspecialchars($nf,			ENT_QUOTES));
		if(!$nf){
			$nf = "-";
		}
		$ns			= trim(	htmlspecialchars($ns,			ENT_QUOTES));
		if(!$ns){
			$ns = "-";
		}
		
		global $_server;
		$consulta = "
			UPDATE
				EQUIPAMENTO
			SET
				descricao = '".$descricao."',
				nome = '".$nome."',
				mac = '".$mac."',
				ip = '".$ip."',
				nf = '".$nf."',
				ns = '".$ns."',
				CLASSE_id = ".$classe.",
				descartado = ".$descartado."
			WHERE
				id = ".$id_equipamento."
		;";
		$query = mysql_query($consulta)or die($consulta.": ERRO AO ATUALIZAR EQUIPAMENTO: ".mysql_error());
	}
	
	/**
	 * REALIZA UM VERIFICAÇÃO PARA VALIDAR SE ESTÁ CONFORME
	 * 
	 * @param int $altura - Altura da imagem
	 * @param int $largura - Largura da imagem
	 * @param int $referencia - Tamanho tomado como referencia para compara largura altura
	 * 
	 * @return bool - FALSE: não está no parametro, TRUE: está dentro da referencia
	 */
	function verifica_altura_largura($altura, $largura, $referencia){
		if($largura > $referencia){
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * ATUALIZA DADOS DO USUÁRIO
	 *
	 * @param int		$id_usuario		- ID do usuário a ser atualizado
	 * @param string	$nome			- nome do usuário
	 * @param int		$ramal			- ramal telefônico do usuário
	 * @param string	$email			- email do usuário
	 * @param string	$usuario		- usuário de acesso
	 * @param string	$senha			- senha de acesso
	 * @param int		$msn			- 0: não possuí acesso a msn, 1: possuí acesso
	 * @param string	$email_msn		- email do msn
	 * @param int		$dispositivos	- 0: não possuí acesso a dispositivos removíveis, 1: possuí acesso
	 * @param int		$internet		- 0: não possuí acesso liberado a internet, 1: possuí acesso
	 * @param int		$ativo			- 0: não possuí acesso a msn, 1: possuí acesso
	 * @param int		$setor			- ID do setor ao qual o usuário está vinculado
	 * @param string	$matricula		- Matrícula do usuário
	 * @param string	$nascimento		- Data de nascimento do usuário
	 */
	function atualiza_usuario($id_usuario,$nome,$ramal,$email,$usuario,$senha,$msn,$email_msn,$dispositivos,$internet,$ativo,$setor,$matricula, $nascimento){
		$nome		= 				htmlspecialchars($nome		,	ENT_QUOTES	);
		$ramal		= 				htmlspecialchars($ramal		,	ENT_QUOTES	);
		$email		= 				htmlspecialchars($email		,	ENT_QUOTES	);
		$usuario	= 		trim(	htmlspecialchars($usuario	,	ENT_QUOTES	));
		$senha		=		trim(	htmlspecialchars($senha		,	ENT_QUOTES	));
		$email_msn	= 				htmlspecialchars($email_msn	,	ENT_QUOTES	);
		$matricula	="'".	trim(	htmlspecialchars($matricula	,	ENT_QUOTES	))	."'";
		if($nascimento != null)
			$nascimento	="'". 			htmlspecialchars($nascimento,	ENT_QUOTES)		."'";
		
		
		global $_server;
		$consulta = "";
		$consulta .= "	UPDATE";
		$consulta .= "		USUARIO";
		$consulta .= "	SET";
		$consulta .= "		nome='".$nome."'";
		$consulta .= "		,ramal='".$ramal."'";
		$consulta .= "		,email='".$email."'";
		$consulta .= "		,usuario='".$usuario."'";
		$consulta .= "		,senha='".$senha."'";
		$consulta .= "		,msn=".$msn;
		$consulta .= "		,email_msn='".$email_msn."'";
		$consulta .= "		,disp=".$dispositivos."";
		$consulta .= "		,net=".$internet;
		$consulta .= "		,ativo=".$ativo;
		$consulta .= "		,SETOR_id=".$setor;
		if($matricula != null){
			$consulta .= "		,matricula = ".$matricula;
		}
		if($nascimento != null){
			$consulta .= "		,data_nascimento = ".$nascimento;
		}
		$consulta .= "	WHERE";
		$consulta .= "		id=".$id_usuario;
		$consulta .= "	;";
		
		$query = mysql_query($consulta)or die($consulta.": ERRO atualiza_usuario(): ".mysql_error());
		//incluir_log("atualiza_usuario()", $_SESSION['usuario'], $id_usuario." | ".$nome." | ".$ramal." | ".$email." | ".$usuario." | ".$senha." | ".$msn." | ".$email_msn." | ".$dispositivos." | ".$internet." | ".$ativo." | ".$setor." | ".$matricula." | ".$nascimento);
	}
	
	/**
	 *	VERIFICA SE É USUÁRIO DO SISTEMA
	 *  
	 *	@return bool - true: se sim, false: se não
	 */
	function verifica_login(){
		global $_server;
		if(isset($_SESSION['usuario']) && isset($_SESSION['senha'])){
			$usuarios = retorna_usuarios();
			for($i = 0; $i< count($usuarios); $i++){
				
				if($usuarios[$i]['U_usuario'] == $_SESSION['usuario'] && $usuarios[$i]['U_senha'] == $_SESSION['senha']){
					$_SESSION['nome']		=	$usuarios[$i]['U_nome'];
					if($usuarios[$i]['U_administrador'] == TRUE){
						$_SESSION['id_resp']	=	$usuarios[$i]['U_id'];
						return 2;
					}else{
						$_SESSION['id_comum']	=	$usuarios[$i]['U_id'];
						return 1;
					}							
				}
			}
			return -1;
		}else{
			return 0;
		}
	}
	
	/**
	 *	COMPARA DUAS DARTAS E DIZ SE A FINAL É MAIOR QUE A INICIAL
	 *	
	 *	@param string $inicial	- data inicial para comparação
	 *	@param string $final	- data final para comparacao
	 *
	 *	@return bool - Verdadeiro: data inicial < final, Falso: data inicial > = final
	 */
	function verifica_data($inicial, $final){
		if(($inicial != NULL) && ($inicial<$final)){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 *	REDIRECIONA COMPONENTE DE UM COMPUTADOR PARA OUTRO COMPUTADOR
	 *
	 *	@param int		$id_equipamento			- ID do Componente
	 *	@param int		$id_de					- ID do usuário que estava com o componente
	 *	@param int		$id_equipamento_novo	- ID do novo computador
	 *	@param string	$motivo					- motivo do redirecionamento
	 */
	function redirecionar_componente($id_equipamento,$id_de, $id_equipamento_novo,$motivo){
		global $_server;
		$sql = "
			UPDATE COMPUTADOR_has_ITENS SET COMPUTADOR_id = ".$id_equipamento_novo." WHERE EQUIPAMENTO_id = ".$id_equipamento."
		;";
		
		$query = mysql_query($sql)or die($sql.": ERRO: ".mysql_error());
		$id_usuario = NULL;
		$id_classe = NULL;
		$usuario = retorna_usuarios_equipamento($id_equipamento_novo);
		$id_para = $usuario[0]['U_id'];
		$motivo = "'".$motivo."'";
		
		$consulta ="
			INSERT INTO REDIRECIONAR_EQUIPAMENTO (
				id,
				motivo,
				EQUIPAMENTO_id,
				USUARIO_id_de,
				USUARIO_id_para,
				data
			) VALUES (
				NULL,
				".$motivo.",
				".$id_equipamento.",
				".$id_de.",
				".$id_para.",
				NOW()
			);
		";
		$query = mysql_query($consulta)or die($consulta.": ERRO redirecionar_componente(".$id_equipamento.",".$id_de.", ".$id_equipamento_novo.",".$motivo."): ".mysql_error());
	}
	
	/**
	 *	REDIRECIONA EQUIPAMENTO DE UM USUÁRIO PARA OUTRO
	 *	
	 *	@param int		$id_equipamento	- ID do equipamento que será transferido
	 *	@param int		$id_de			- ID do usuário de origem
	 *	@param int		$id_para		- ID do usuário de destino
	 *	@param string	$motivo			- Motivo da transferencia do equipamento
	 *
	 *	@see alterar_usuario_equipamento()
	 */
	function redirecionar_equipamento($id_equipamento,$id_de, $id_para,$motivo){
		global $_server;
		
		/*deixa somente um usuário vinculado ao equipamento*/
		$sql = "
			DELETE FROM usuario_has_equipamento
			WHERE EQUIPAMENTO_id = ".$id_equipamento." AND USUARIO_id != ".$id_de."
		;";
		
		$query = mysql_query($sql)or die($sql.": ERRO: ".mysql_error());
		
		$motivo = "'".$motivo."'";
		
		$consulta ="
			INSERT INTO REDIRECIONAR_EQUIPAMENTO (
				id,
				motivo,
				EQUIPAMENTO_id,
				USUARIO_id_de,
				USUARIO_id_para,
				data
			) VALUES (
				NULL,
				".$motivo.",
				".$id_equipamento.",
				".$id_de.",
				".$id_para.",
				NOW()
			);
		";
		$query = mysql_query($consulta)or die($consulta.": ERRO: ".mysql_error());
		alterar_usuario_equipamento($id_equipamento, $id_para);
	}
	
	/**
	 *	ALTERA VINCULO ENTRE USUARIO E EQUIPAMENTO
	 *	
	 *	@param int $id_equipamento	- ID do equipamento
	 *	@param int $id_usuario		- ID do novo usuário
	 */
	function alterar_usuario_equipamento($id_equipamento, $id_usuario){
		global $_server;
		$consulta ="
			UPDATE usuario_has_equipamento SET USUARIO_id = ".$id_usuario." WHERE EQUIPAMENTO_id = ".$id_equipamento.";
		";
		$query = mysql_query($consulta)or die($consulta.": ERRO alterar_usuario_equipamento();: ".mysql_error());	
	}
	
	/**
	 *	REMOVE VÍNCULO ENTRE EQUIPAMENTO E USUÁRIO
	 *
	 *	@param int $id_equipamento	- ID do equipamento 
	 *	@param int $id_usuario		- ID do usuário
	 */
	function retirar_vinculos_equipamento($id_equipamento,$id_usuario){
		global $_server;
		$consulta ="DELETE FROM USUARIO_has_EQUIPAMENTO";
		
		if($id_equipamento != NULL && $id_usuario != NULL)
			$consulta = $consulta." WHERE EQUIPAMENTO_id = ".$id_equipamento." AND USUARIO_id = ".$id_usuario.";";
		else{
			if($id_equipamento != NULL)
				$consulta = $consulta." WHERE EQUIPAMENTO_id = ".$id_equipamento.";";
			if($id_usuario != NULL)
				$consulta = $consulta." WHERE USUARIO_id = ".$id_usuario.";";
		}
		$query = mysql_query($consulta)or die($consulta.": ERRO retirar_vinculos_equipamento();: ".mysql_error());
	}
	
	/**
	 * REMOVE VÍNCULO ENTRE COMPUTADOR E COMPONENTES
	 *
	 * @param int $id_componente	- ID do componente
	 * @param int $id_PC			- ID do computador
	 * 
	 * @return bool - FALSE quantidade de parametros insuficientes
	 */	
	function retirar_vinculos_componentes($id_componente,$id_PC = NULL){
		global $_server;
		$consulta ="DELETE FROM COMPUTADOR_has_ITENS";
		
		if($id_componente != NULL && $id_PC != NULL){
			$consulta = $consulta." WHERE EQUIPAMENTO_id = ".$id_componente." AND COMPUTADOR_id = ".$id_PC.";";
			$query = mysql_query($consulta)or die($consulta.": ERRO retirar_vinculos_componentes(".$id_componente.",".$id_PC.");: ".mysql_error());
		}else{
			if($id_componente == null && $id_componente != NULL){
				$consulta = $consulta." WHERE EQUIPAMENTO_id = ".$id_componente.";";
				$query = mysql_query($consulta)or die($consulta.": ERRO retirar_vinculos_componentes(".$id_componente.",".$id_PC.");: ".mysql_error());
			}
			if($id_PC != NULL){
				$consulta = $consulta." WHERE COMPUTADOR_id = ".$id_PC.";";
				$query = mysql_query($consulta)or die($consulta.": ERRO retirar_vinculos_componentes(".$id_componente.",".$id_PC.");: ".mysql_error());
			}
		}
		
	}
	
	/**	RETORNA UMA MATRIZ COM OS DADOS DAS FILIAIS
	 *	
	 *	@param int $id_filial - ID da filial(NULL para todas)
	 *
	 *	@return array|int		['F_id']		- ID da Filial
	 *	@return array|string	['F_nome']		- NOME da Filial
	 *	@return array|string	['F_endereco']	- ENDEREÇO da Filial
	 *	@return array|string	['F_telefone']	- TELEFONE da Filial
	 */	
	function retorna_filiais($id_filial = NULL){
		global $_server;
		$consulta ="
			SELECT 
				id AS F_id,
				nome AS F_nome,
				endereco AS F_endereco,
				telefone AS F_telefone
			FROM
				FILIAL
		";
		if($id_filial != NULL)
			$consulta = $consulta." WHERE id=".$id_filial.";";
			
		$cont=0;
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_dados_filiais();: ".mysql_error());
		while($row = mysql_fetch_array($query)){
			$aux[] = $row;
			$cont=1;
		}
		mysql_free_result($query);
		if($cont==1)
			return $aux;
		else
			return NULL;
	}
	
	/**	RETORNA UMA MATRIZ COM OS DADOS DO SETOR
	 *	
	 *	@param int	$id_filial	- ID da Filial desejada (Padrão: NULL)
	 *	@param int	$id_filial	- ID da Filial desejada (Padrão: NULL)
	 *
	 *	@return array|int		['S_id']	- ID do Setor
	 *	@return array|string	['S_nome']	- NOME do Setor do Usuário
	 */
	function retorna_setores($id_filial = NULL,$id_setor = NULL){
		global $_server;
		$consulta ="";
		$consulta .= "	SELECT";
		$consulta .= "		s.id AS S_id,";
		$consulta .= "		s.nome AS S_nome,";
		$consulta .= "		f.nome AS F_nome,";
		$consulta .= "		f.id AS F_id";
		$consulta .= "	FROM";
		$consulta .= "		setor AS s";
		$consulta .= "		INNER JOIN";
		$consulta .= "	filial AS f ON s.FILIAL_id = f.id";
		
		if($id_setor != NULL AND $id_filial != NULL)
			$consulta .= " WHERE s.FILIAL_id=".$id_filial." AND s.id=".$id_setor; 
		else if($id_filial != NULL)
			$consulta .= " WHERE s.FILIAL_id=".$id_filial;
		else if($id_setor != NULL)
			$consulta .= " WHERE s.id=".$id_setor;
			
		$consulta .= " ORDER BY s.nome;";
		
		$cont=0;
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_setores();: ".mysql_error());
		while($row = mysql_fetch_array($query)){
			$aux[] = $row;
			$cont=1;
		}
		mysql_free_result($query);
		if($cont==1)
			return $aux;
		else
			return NULL;
	}
	
	/**
	 * RETORNA UMA MATRIZ COM OS DADOS DE UM Usuário
	 *	
	 * @param int				$id_filial		- ID da filial
	 * @param int				$id_setor		- ID do Setor
	 * @param int				$id_usuario		- ID do Usuário
	 * @param bool				$ativo			- TRUE: usuário está ativo, FALSE: usuário inativo, NULL: indiferente
	 *
	 * @return array|int		['U_id']		- id do Usuario
	 * @return array|string		['U_nome']		- nome do Usuario
	 * @return array|int 		['U_ramal']		- ramal do Usuário
	 * @return array|string		['U_email']		- email do Usuário
	 * @return array|string		['U_usuario']	- nome de Usuário do Usuário
	 * @return array|string		['U_senha']		- senha do Usuário
	 * @return array|int		['U_msn']		- MSN do Usuário: 1-liberado, 0-não liberado
	 * @return array|string		['U_email_msn'] - endereço do MSN do Usuário
	 * @return array|int		['U_disp']		- Acesso a CD,Pendrive,etc do Usuário: 1-liberado, 0-não liberado
	 * @return array|int		['U_net']		- Acesso a internet do Usuário: 1-liberado, 0-não liberado
	 * @return array|int		['U_ativo']		- Usuário ativo: 1-ativo, 0-não ativo
	 * @return array|string		['U_matricula']	- Matrícula do usuário
	 * @return array|string		['U_nascimento']- Data de nascimento do usuário (YYYY-MM-DD)
	 * @return array|int		['S_id']		- id do Setor do Usuário
	 * @return array|string		['S_nome']		- nome do Setor do Usuário
	 * @return array|int		['F_id']		- id da Filial do Usuário
	 * @return array|string		['F_nome']		- nome da Filial do Usuário
	 * @return array|string		['F_endereco']	- endereço da Filial do Usuário
	 * @return array|string		['F_telefone']	- telefone da Filial do Usuário
	 */		
	function retorna_usuarios($id_filial = NULL,$id_setor = NULL,$id_usuario = NULL,$ativo = TRUE, $ordenacao = 0, $administrador = FALSE){
		global $_server;
		
		$consulta ="SELECT * FROM usuario_setor_filial ";
		$aux = null;
		
		if($id_filial !=NULL){
			$consulta .= " WHERE F_id = ".$id_filial;
			if($ativo == TRUE){
				$consulta .= " AND U_ativo = 1";
			}			
			if($id_setor !=NULL)
				$consulta .= " AND S_id = ".$id_setor;
		}else if($id_setor !=NULL){
			$consulta .= " WHERE S_id = ".$id_setor;
			if($ativo == TRUE){
				$consulta .= " AND U_ativo = 1";
			}
		}else if($id_usuario !=NULL){
			$consulta .= " WHERE U_id = ".$id_usuario;
			if($ativo == TRUE){
				$consulta .= " AND U_ativo = 1";
			}
		}else{
			if($ativo == TRUE){
				$consulta .= " WHERE U_ativo = 1";
			}
			
		}
		if($ordenacao == 0){
			$consulta.=" ORDER BY F_nome ASC, S_nome ASC, U_nome ASC";
			
		}else if($ordenacao == 1){
			$consulta.=" ORDER BY U_nome ASC";
		}
		
		$consulta.=";";
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_usuarios(): ".mysql_error());
		while($row = mysql_fetch_array($query)){
			$aux[] = $row;
		}
		mysql_free_result($query);
		return $aux;
	}
	
	/**
	 *	RETORNA UMA MATRIZ COM DADOS DOS RESPONSÁVEIS
	 *
	 *	@param	int				$id_responsavel	- NULL=Todos registros, !NULL=Desejado
	 *
	 *	@return array|int		['R_id']		- ID do Responsável
	 *	@return array|string	['R_nome']		- NOME do Responsável
	 *	@return array|string	['R_ramal'] 	- RAMAL do Responsável
	 *	@return array|string	['R_email']		- EMAIL do Responsável
	 */	
	function retorna_responsaveis($id_responsavel){
		global $_server;
		$consulta ="
			SELECT 
				*
			FROM
				RESPONSAVEL_DADOS
		";
		if($id_responsavel != NULL){
			$consulta .= "WHERE R_id = ".$id_responsavel;
		}
		
		$consulta .= "WHERE R_administrador = TRUE";
		
		$consulta .= " ORDER BY R_nome ASC";
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_responsaveis(): ".mysql_error());
		while($row = mysql_fetch_array($query)){
			$aux[] = $row;
			$cont=1;
		}
		mysql_free_result($query);
		if($cont==1)
			return $aux;
		else
			return NULL;
	}
	
	/**
	 *	FUNÇÃO QUE RETORNA MATRIZ COM COMPONENTES DO COMPUTADOR
	 *
	 *	@param 	int				$id_computador		- ID do computador que deseja ser buscado os componentes
	 *
	 *	@return array|int		['E_id']			- ID do componente
	 *	@return array|string	['E_nome']			- NOME do componente
	 *	@return array|string	['E_descricao']		- descrição do componente
	 *	@return array|string	['E_mac']			- MAC do componente
	 *	@return array|string	['E_ip']			- IP do componente
	 *	@return array|string	['E_nf']			- Nº da Nota Fiscal do Componente
	 *	@return array|string	['E_ns']			- Nº DE SÉRIE do componente
	 *	@return array|int		['E_descartador']	- VERIFICA SE COMPONENTE ESTÁ DESCARTADO: 0 - falso, 1 - true
	 *	@return array|int		['C_id']			- ID da CLASSE ao qual o componente pertence
	 *	@return array|string	['C_nome']			- NOME da CLASSE ao qual o componente pertence
	 *	@return array|int		['PC_id']			- ID do computador ao qual o componente esta conectado
	 */
	function retorna_componentes_computador($id_computador){
		global $_server;
		
		$aux = null;

		$consulta ="SELECT * FROM COMPONENTES_COMPUTADOR WHERE PC_id=";
		$consulta .= $id_computador;
		$consulta .= " ORDER BY C_nome ASC";
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_componentes_computador(): ".mysql_error());
		while($row = mysql_fetch_array($query)){
			$aux[] = $row;
		}

		mysql_free_result($query);

		return $aux;
	}
	
	/**
	 *	RETORNA MATRIZ COM USUÁRIOS DO EQUIPAMENTO
	 *
	 *	@param	int				$id_equipamento	- ID do Equipamento a ser buscado informações
	 *
	 *	@return array|int		[U_id]			- ID do Usuário que está vinculado ao equipamento
	 *	@return array|string	[U_nome]		- NOME do Usuário que está vinculado ao equipamento
	 *	@return array|string	[U_ramal]		- RAMAL do Usuário que está vinculado ao equipamento
	 *	@return array|string	[U_email]		- EMAIL  do Usuário que está vinculado ao equipamento
	 *	@return array|string	[U_usuario]		- USUÁRIO do Usuário que está vinculado ao equipamento
	 *	@return array|string	[U_senha]		- SENHA do Usuário que está vinculado ao equipamento
	 *	@return array|int		[U_msn]			- 0:NÃO POSSUÍ, 1:POSSUÍ
	 *	@return array|int		[U_disp]		- 0:NÃO POSSUÍ, 1:POSSUÍ
	 *	@return array|int		[U_net]			- 0:NÃO POSSUÍ, 1:POSSUÍ
	 *	@return array|string	[U_email_msn]	- EMAIL DO MSN do Usuário que está vinculado ao equipamento
	 *	@return array|int		[U_aitvo]		- 0:INATIVO, 1:ATIVO
	 *	@return array|int		[S_id]			- ID do Setor do Usuário que está vinculado ao equipamento
	 *	@return array|string	[S_nome]		- NOME do Setor do Usuário que está vinculado ao equipamento
	 *	@return array|string	[F_nome]		- NOME da Filial do Usuário que está vinculado ao equipamento
	 *	@return array|int		[E_id]			- ID do Equipamento
	 */
	function retorna_usuarios_equipamento($id_equipamento){
		global $_server;
		$consulta ="SELECT * FROM USUARIOS_EQUIPAMENTO ";
		$aux = null;
		
		if($id_equipamento!=NULL){
			$consulta .=" WHERE E_id =".$id_equipamento;
		}
		
		$consulta .= " ORDER BY F_nome ASC, S_nome ASC, U_nome ASC";
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_usuarios_equipamento();: ".mysql_error());
		
		while($row = mysql_fetch_array($query)){
			$aux[]=$row;
		}
		
		mysql_free_result($query);
		
		return $aux;
	}
	
	/**
	 *	RETORNA UM VETOR COM OS DADOS DE UM EQUIPAMENTO
	 *
	 *	@param	int				$id_equipamento		- ID do equipamento
	 *	@param	int				$restricao			- 1: Busca somente Computador, Acessório, Impressor
	 *
	 *	@return array|string	['E_nome']			- nome do equipamento
	 *	@return array|string	['E_descricao'] 	- descrição do equipamento
	 *	@return array|string	['E_mac']			- endereço mac do equipamento
	 *	@return array|string	['E_ip']			- endereço IP do equipamento
	 *	@return array|string	['E_nf'] 			- Número da Nota Fiscal do equipamento
	 *	@return array|string	['E_ns']			- Número de S�rie do equipamento
	 *	@return array|int		['E_descartado']	- Equipamento descartado? 0-não, 1-Sim
	 *	@return array|int		['C_id']			- id da classe a qual o equipamento pertence
	 *	@return array|string	['C_nome']			- nome da classe a qual o equipamento pertence
	 */
	function retorna_dados_equipamento($id_equipamento,$restricao = 0){
		global $_server;
		$consulta ="
			select * from DADOS_EQUIPAMENTO
			WHERE E_id = ".$id_equipamento."
		;";
		if($restricao == 1)
			$consulta .= "AND (	C_nome = 'Computador' OR C_nome = 'Acessório' OR C_nome = 'Impressora' )";
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_dados_equipamento();: ".mysql_error());
		$row = mysql_fetch_array($query);
		mysql_free_result($query);
		return $row;
	}
	
	/**
	 *	RETORNA UMA MATRIZ COM OS DADOS DE UM EQUIPAMENTO
	 *
	 *	@param	int				$id_usuario			- ID do Usuário
	 *	@param	int				$id_classe			- ID da classe do equipamento
	 *
	 *	@return array|string	['E_nome']			- nome do equipamento
	 *	@return array|string	['E_nome']			- nome do equipamento
	 *	@return array|string	['E_descricao']		- descrição do equipamento
	 *	@return array|string	['E_mac']			- endereço mac do equipamento
	 *	@return array|string	['E_ip']			- endereço IP do equipamento
	 *	@return array|string	['E_nf']			- Número da Nota Fiscal do equipamento
	 *	@return array|string	['E_ns']			- Número de S�rie do equipamento
	 *	@return array|int		['E_descartado']	- Equipamento descartado? 0-não, 1-Sim
	 *	@return array|int		['C_id']			- id da classe a qual o equipamento pertence
	 *	@return array|string	['C_nome']			- nome da classe a qual o equipamento pertence
	 *	@return array|int		['U_id']			- id do Usuario
	 *	@return array|string	['U_nome']			- nome do Usuario
	 *	@return array|int		['U_ramal']			- ramal do Usuário
	 *	@return array|string	['U_email']			- email do Usuário
	 *	@return array|string	['U_usuario']		- nome de Usuário do Usuário
	 *	@return array|string	['U_senha']			- senha do Usuário
	 *	@return array|int		['U_msn']			- MSN do Usuário: 1-liberado, 0-não liberado
	 *	@return array|string	['U_email_msn']		- endereço do MSN do Usuário
	 *	@return array|int		['U_disp']			- Acesso a CD,Pendrive,etc do Usuário: 1-liberado, 0-não liberado
	 *	@return array|int		['U_net']			- Acesso a internet do Usuário: 1-liberado, 0-não liberado
	 *	@return array|int		['U_ativo']			- Usuário ativo: 1-ativo, 0-não ativo
	 *	@return array|int		['S_id']			- id do Setor do Usuário
	 *	@return array|string	['S_nome']			- nome do Setor do Usuário
	 *	@return array|int		['F_id']			- id da Filial do Usuário
	 *	@return array|string	['F_nome']			- nome da Filial do Usuário
	 *	@return array|string	['F_endereco']		- endereço da Filial do Usuário
	 *	@return array|string	['F_telefone']		- telefone da Filial do Usuário
	 */	
	function retorna_equipamentos_usuario($id_usuario,$id_classe){
		global $_server;
		
		$aux = null;
		
		$consulta = " SELECT * FROM RELACAO_USUARIO_EQUIPAMENTO";		
		if($id_usuario != null){
			$consulta .="	WHERE U_id =".$id_usuario;
			if($id_classe != null){
				$consulta .= " AND C_id = ".$id_classe;
			}
		}
		$consulta .= " ORDER BY C_nome, E_nome";
		
		$consulta .= ";";
		$cont=0;
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_equipamentos_usuario();: ".mysql_error());
		
		while($row = mysql_fetch_array($query)){
			$aux[]=$row;
		}
		mysql_free_result($query);
		
		return $aux;
	}
	
	/**
	 *	FUNÇÃO QUE RETORNA MATRIZ COM DADOS DO EQUIPAMENTO
	 *	
	 *	@param	bool			$com_restricao		- Restrição para retornar equipamentos para usuários, excluindo valores de registro
	 *	@param	bool			$componentes		- necessita $com_restricao == false, retorna somente componentes para computador
	 * 	@param	int				$inicial			- listagem inicial
	 * 	@param	int				$por_pagina			- quantidade de itens por pagina
	 *
	 *	@return array|string	['E_nome']			- nome do equipamento
	 *	@return array|string	['E_descricao']		- descrição do equipamento
	 *	@return array|string	['E_mac']			- endereço mac do equipamento
	 *	@return array|string	['E_ip']			- endereço IP do equipamento
	 *	@return array|string	['E_nf']			- Número da Nota Fiscal do equipamento
	 *	@return array|string	['E_ns']			- Número de Série do equipamento
	 *	@return array|int		['E_descartado']	- Equipamento descartado? 0-não, 1-Sim
	 *	@return array|int		['C_id']			- id da classe a qual o equipamento pertence
	 *	@return array|string	['C_nome']			- nome da classe a qual o equipamento pertence
	 */
	function retorna_equipamentos($com_restricao = false, $componentes = false, $inicial = 1, $por_pagina = 20, $descartado = null){
		global $_server;
		
		$aux = null;
		
		$consulta = "";
		$consulta .= "	SELECT";
		$consulta .= "		*";
		$consulta .= "	FROM";
		$consulta .= "		DADOS_EQUIPAMENTO";
		if($com_restricao == true){
			$consulta .= " WHERE";
			$consulta .= " (";
			$consulta .= "	C_nome = 'Computador'";
			$consulta .= "		OR C_nome = 'Impressora'";
			$consulta .= "		OR C_nome = 'Outro'";
			$consulta .= "		OR C_nome = 'Acessório'";
			$consulta .= "		OR C_nome = 'Pendrive'";
			$consulta .= "		OR C_nome = 'Câmera Digital'";
			$consulta .= "		OR E_nome = 'Arquivo Digital'";
			$consulta .= "		OR E_id = 28";
			$consulta .= " 		OR E_id = 169";
			$consulta .= " 		OR E_id = 932";
			$consulta .= " 		OR E_id = 944";
			$consulta .= " 		OR E_id = 954";
			$consulta .= " ) AND E_descartado = 0";
		}else if ($componentes == true){
			$consulta .= " WHERE";
			$consulta .= " (";
			$consulta .= "			C_id =  2";	//Componente
			$consulta .= "		OR	C_id =  3";	//Acessório
			$consulta .= "		OR	C_id =  4";	//Sistema Operacional
			$consulta .= "		OR	C_id =  5";	//Software
			$consulta .= "		OR	C_id =  8";	//Memória 
			$consulta .= "		OR	C_id =  9";	//HD
			$consulta .= "		OR	C_id = 10";	//Placa Mãe
			$consulta .= "		OR	C_id = 11";	//Processador
			$consulta .= "		OR	C_id = 12";	//Driver Óptico
			$consulta .= "		OR	C_id = 13";	//Fonte
			$consulta .= "		OR	C_id = 14";	//Teclado
			$consulta .= "		OR	C_id = 15";	//Mouse
			$consulta .= "		OR	C_id = 16";	//Placa de Vídeo
			$consulta .= "		OR	C_id = 20";	//ECF
			$consulta .= "		OR	C_id = 21";	//PINPAD
			$consulta .= " ) AND E_descartado = 0";
		}
		
		$consulta .= " ORDER BY C_nome ASC, E_nome ASC";
		
		if(($inicial != null) || ($por_pagina != null) ){
			$consulta .= " LIMIT ".$inicial.",".$por_pagina.";";
		}
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_equipamentos();: ".mysql_error());
		
		while($row = mysql_fetch_array($query)){
			$aux[]=$row;
		}
		
		mysql_free_result($query);
		
		return $aux;
	}

	/**
	 *	RETORNA UMA MATRIZ COM OS DADOS DOS REDIRECIONAMENTOS DO CHAMADO
	 *
	 *	@param	int				$id_chamado		- ID do chamado
	 *
	 *	@return array|int		['RC_id']		- ID do Redirecionamento
	 *	@return array|string	['RC_motivo']	- MOTIVO do Redirecionamento
	 *	@return array|string	['RC_data']		- DATA do Redirecionamento
	 *	@return array|int		['Ch_id']		- ID do Chamado ao qual o Redirecionamento está vinculado
	 *	@return array|int		['DE_id']		- ID do antigo Responsável
	 *	@return array|string	['DE_nome']		- NOME do antigo Responsável
	 *	@return array|string	['DE_ramal']	- RAMAL do antigo Responsável
	 *	@return array|string	['DE_email']	- EMAIL do antigo Responsável
	 *	@return array|int		['PARA_id']		- ID do novo Responsável
	 *	@return array|string	['PARA_nome']	- NOME do novo Responsável
	 *	@return array|string	['PARA_ramal']	- RAMAL do novo Responsável
	 *	@return array|string	['PARA_email']	- EMAIL do novo Responsável
	 */
	function retorna_redirecionamentos_chamado($id_chamado){
		global $_server;
		
		$aux = null;

		$consulta = "";
		$consulta .= "	SELECT";
		$consulta .= "		*";
		$consulta .= "	FROM";
		$consulta .= "		DADOS_REDIRECIONAMENTO_CHAMADOS";
		
		if($id_chamado != NULL){
			$consulta .= "	WHERE Ch_id = ".$id_chamado;			
		}
			
		$consulta .= " ORDER BY RC_data DESC;";
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_redirecionamentos_chamado(): ".mysql_error());
		
		while($row = mysql_fetch_array($query)){
			$aux[]=$row;
		}
		
		return $aux;
	}
	
	/**
	 * FUNÇÃO QUE RETORNA MATRIZ COM DADOS DO REDIRECIONAMENTO OU NULO CASO não HAJA DADOS
	 * 
	 * @param	int				$id_equipamento		- ID do equipamento para ser buscadas as informações
	 * 
	 * @return	array|int		['RE_id']			- ID do redirecionamento
	 * @return	array|string	['RE_data']			- DATA do redirecionamento
	 * @return	array|string	['RE_motivo']		- MOTIVO do redirecionamento
	 * @return	array|string	['E_nome']			- nome do equipamento
	 * @return	array|string	['E_descricao']		- descrição do equipamento
	 * @return	array|string	['E_mac']			- endereço mac do equipamento
	 * @return	array|string	['E_ip']			- endereço IP do equipamento
	 * @return	array|string	['E_nf']			- Número da Nota Fiscal do equipamento
	 * @return	array|string	['E_ns']			- Número de S�rie do equipamento
	 * @return	array|int		['E_descartado']	- Equipamento descartado? 0-não, 1-Sim
	 * @return	array|int		['C_id']			- id da classe a qual o equipamento pertence
	 * @return	array|string	['C_nome']			- nome da classe a qual o equipamento pertence
	 * @return	array|int		['De_id']			- ID do antigo Usuário do Equipamento
	 * @return	array|string	['De_nome'] 		- NOME do antigo Usuário do Equipamento
	 * @return	array|int		['Para_id'] 		- ID do novo Usuário do Equipamento
	 * @return	array|string	['Para_nome'] 		- NOME do novo Usuário do Equipamento
	 */
	function retorna_redirecionamentos_equipamento($id_equipamento){
		global $_server;
		
		$aux = null;
		$consulta = "";
		$consulta .= "	SELECT";
		$consulta .= "		*";
		$consulta .= "	FROM";
		$consulta .= "		DADOS_REDIRECIONAMENTO_EQUIPAMENTO";
		
		if($id_equipamento!=NULL){
			$consulta .= "	WHERE E_id = ".$id_equipamento;
		}
		
		$consulta .= "	ORDER BY RE_data ASC";	
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_redirecionamentos_equipamento();: ".mysql_error());
		
		while($row = mysql_fetch_array($query)){
			$aux[]=$row;
		}
		
		mysql_free_result($query);
		
		return $aux;
	}
	
	
	/**
	 * 
	 * FUNÇÃO QUE RETORNA TODAS AS CLASSES DE EQUIPAMENTOS
	 * 
	 * @param	null
	 * 
	 * @return	array|int		['C_id']	- ID da Classe
	 * @return	array|string	['C_nome']	- NOME da Classe
	 */
	function retorna_classes(){
		global $_server;
		
		$aux = null;

		$consulta ="";
		$consulta .= "	SELECT";
		$consulta .= "		CLASSE.id AS C_id,";
		$consulta .= "		CLASSE.nome AS C_nome";
		$consulta .= "	FROM";
		$consulta .= "		CLASSE";
		$consulta .= "	ORDER BY CLASSE.nome ASC";
		$consulta .= ";";
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_classes();: ".mysql_error());
		
		while($row = mysql_fetch_array($query)){
			$aux[]=$row;
		}
		
		mysql_free_result($query);
		
		return $aux;
	}
	
	
	/**
	 *	RETORNA DADO(S) DO(S) CHAMADO(S) POR FILIAL, SETOR, Usuário OU EQUIPAMENTO
	 *
	 *	@param	int				$id_filial				- ID da filial desejada
	 *	@param	int				$id_setor				- ID do Setor
	 *	@param	int				$id_usuario				- ID do Usuário
	 *	@param	int				$id_equipamento			- ID do Equipamento
	 *	@param	int				$id_chamado				- ID do Chamado
	 *	@param	bool			$aberto					- TRUE: Chamado aberto, FALSE: Chamado fechado, NULL: Indiferente
	 *	@param	int				$inicial				- para controle de paginação, padrão: 1
	 *	@param	int				$por_pagina				- para paginação, quanto itens por pagina, NULL: ilimitado
	 *	@param	int				$tipo					- ?????
	 *	@param	string			$dtInicial				- Para buscar dados entre datas, Formato data: AAAA/MM/DD
	 *	@param	string			$dtFinal				- Para buscar dados entre datas, Formato data: AAAA/MM/DD
	 *
	 *	@return	array|int		['Ch_id'] 				- ID do Chamado
	 *	@return	array|string	['Ch_assunto'] 			- ASSUNTO do Chamado
	 *	@return	array|string	['Ch_descricao'] 		- descrição do Chamado
	 *	@return	array|int		['Ch_aberto']			- Chamado aberto? 0-Finalizado, 1-Aberto, 2-Em Andamento 
	 *	@return	array|string	['Ch_data_abertura']	- Data de abertura do Chamado
	 *	@return	array|int		['R_id']				- id do Responsável pelo Chamado
	 *	@return	array|string	['R_nome']				- nome do Responsável pelo Chamado
	 *	@return	array|string	['R_ramal']				- ramal do Responsável pelo Chamado
	 *	@return	array|string	['R_email']				- email do Responsável pelo Chamado
	 *	@return	array|int		['U_id']				- id do Usuario do Chamado
	 *	@return	array|string	['U_nome']				- nome do Usuario do Chamado
	 *	@return	array|string	['U_ramal']				- ramal do Usuário do Chamado
	 *	@return	array|string	['U_email']				- email do Usuário do Chamado
	 *	@return	array|string	['U_usuario']			- nome de Usuário do Usuário do Chamado
	 *	@return	array|string	['U_senha']				- senha do Usuário do Chamado
	 *	@return	array|int		['U_msn']				- MSN do Usuário do Chamado: 1-liberado, 0-não liberado
	 *	@return	array|string	['U_email_msn']			- endereço do MSN do Usuário do Chamado
	 *	@return	array|int		['U_disp']				- Acesso a CD,Pendrive,etc do Usuário do Chamado: 1-liberado, 0-não liberado
	 *	@return	array|int		['U_net']				- Acesso a internet do Usuário do Chamado: 1-liberado, 0-não liberado
	 *	@return	array|int		['U_ativo']				- Usuário ativo do Chamado: 1-ativo, 0-não ativo
	 *	@return	array|int		['S_id']				- id do Setor do Usuário do Chamado
	 *	@return	array|string	['S_nome']				- nome do Setor do Usuário do Chamado
	 *	@return	array|int		['F_id']				- id da Filial do Usuário do Chamado
	 *	@return	array|string	['F_nome']				- nome da Filial do Usuário do Chamado
	 *	@return	array|string	['F_endereco']			- endereço da Filial do Usuário do Chamado
	 *	@return	array|string	['F_telefone']			- telefone da Filial do Usuário do Chamado
	 *	@return	array|int		['E_id']				- id do Equipamento do Chamado
	 *	@return	array|string	['E_nome']				- nome do Equipamento do Chamado
	 *	@return	array|string	['E_descricao']			- descricao do Equipamento do Chamado
	 *	@return	array|string	['E_mac']				- mac do Equipamento do Chamado
	 *	@return	array|string	['E_ip']				- ip do Equipamento do Chamado
	 *	@return	array|string	['E_nf']				- nf do Equipamento do Chamado
	 *	@return	array|string	['E_ns']				- ns do Equipamento do Chamado
	 *	@return	array|int		['E_descartado']		- Descarte do Equipamento do Chamado: 0-não descartado, 1-descartado
	 *	@return	array|int		['C_id']				- id da classe a qual o Equipamento do Chamado pertence
	 *	@return	array|string	['C_nome']				- nome da classe a qual o Equipamento do Chamado pertence
	 */
	function retorna_chamados($id_filial=null,$id_setor=null,$id_usuario=null,$id_equipamento=null,$id_chamado=null,$aberto = null, $inicial=1, $por_pagina=20,$tipo = null, $dtInicial = null, $dtFinal = null){
		global $_server;
		
		$aux = null;
		
		$consulta ="";
		$consulta .= "	SELECT\n";
		$consulta .= "		LPAD( CONVERT(`dados_chamado`.`Ch_id`, CHAR(4) ),4,'0') AS `Ch_id`,\n";
		$consulta .= "		`dados_chamado`.`Ch_assunto`,\n";
		$consulta .= "		`dados_chamado`.`Ch_descricao`,\n";
		$consulta .= "		`dados_chamado`.`Ch_aberto`,\n";
		$consulta .= "		`dados_chamado`.`Ch_data_abertura`,\n";
		$consulta .= "		`dados_chamado`.`Ch_prioridade`,\n";
		$consulta .= "		`dados_chamado`.`Ch_previsao`,\n";
		$consulta .= "		`dados_chamado`.`So_data_solucionado`,\n";
		$consulta .= "		`dados_chamado`.`So_data_inicial`,\n";
		$consulta .= "		`dados_chamado`.`R_id`,\n";
		$consulta .= "		`dados_chamado`.`R_nome`,\n";
		$consulta .= "		`dados_chamado`.`R_ramal`,\n";
		$consulta .= "		`dados_chamado`.`R_email`,\n";
		$consulta .= "		`dados_chamado`.`R_usuario`,\n";
		$consulta .= "		`dados_chamado`.`R_senha`,\n";
		$consulta .= "		`dados_chamado`.`U_id`,\n";
		$consulta .= "		`dados_chamado`.`U_nome`,\n";
		$consulta .= "		`dados_chamado`.`U_ramal`,\n";
		$consulta .= "		`dados_chamado`.`U_email`,\n";
		$consulta .= "		`dados_chamado`.`U_usuario`,\n";
		$consulta .= "		`dados_chamado`.`U_senha`,\n";
		$consulta .= "		`dados_chamado`.`U_msn`,\n";
		$consulta .= "		`dados_chamado`.`U_disp`,\n";
		$consulta .= "		`dados_chamado`.`U_net`,\n";
		$consulta .= "		`dados_chamado`.`U_email_msn`,\n";
		$consulta .= "		`dados_chamado`.`U_ativo`,\n";
		$consulta .= "		`dados_chamado`.`U_matricula`,\n";
		$consulta .= "		`dados_chamado`.`U_nascimento`,\n";
		$consulta .= "		`dados_chamado`.`S_id`,\n";
		$consulta .= "		`dados_chamado`.`S_nome`,\n";
		$consulta .= "		`dados_chamado`.`F_id`,\n";
		$consulta .= "		`dados_chamado`.`F_nome`,\n";
		$consulta .= "		`dados_chamado`.`F_endereco`,\n";
		$consulta .= "		`dados_chamado`.`F_telefone`,\n";
		$consulta .= "		`dados_chamado`.`E_id`,\n";
		$consulta .= "		`dados_chamado`.`E_nome`,\n";
		$consulta .= "		`dados_chamado`.`E_descricao`,\n";
		$consulta .= "		`dados_chamado`.`E_mac`,\n";
		$consulta .= "		`dados_chamado`.`E_ip`,\n";
		$consulta .= "		`dados_chamado`.`E_nf`,\n";
		$consulta .= "		`dados_chamado`.`E_ns`,\n";
		$consulta .= "		`dados_chamado`.`E_descartado`,\n";
		$consulta .= "		`dados_chamado`.`C_id`,\n";
		$consulta .= "		`dados_chamado`.`C_nome`\n";
		$consulta .= "	FROM\n";
		$consulta .= "		`chamados`.`dados_chamado`\n";
		
		if(isset($id_filial) || 
			isset($id_setor) || 
			isset($id_usuario) || 
			isset($id_equipamento) || 
			isset($id_chamado) || 
			isset($aberto) ||
			isset($tipo) || 
			isset($dtInicial) || 
			isset($dtFinal)){
			$consulta .= " WHERE \n";
		}
		if(isset($id_filial)){
			
			$consulta .= " F_id=".$id_filial;
			
			if(
			isset($id_setor) || 
			isset($id_usuario) || 
			isset($id_equipamento) || 
			isset($id_chamado) || 
			isset($aberto) ||  
			isset($tipo) || 
			isset($dtInicial) || 
			isset($dtFinal)){
				$consulta .= " AND \n";
			}
			
		}
		if(isset($id_setor)){
			
			$consulta .= " S_id=".$id_filial;
			
			if(
			isset($id_usuario) || 
			isset($id_equipamento) || 
			isset($id_chamado) || 
			isset($aberto) ||  
			isset($tipo) || 
			isset($dtInicial) || 
			isset($dtFinal)){
				$consulta .= " AND \n";
			}
			
		}
		if(isset($id_usuario)){
			
			$consulta .= " U_id=".$id_usuario;
			
			if(
			isset($id_equipamento) || 
			isset($id_chamado) || 
			isset($aberto) || 
			isset($tipo) || 
			isset($dtInicial) || 
			isset($dtFinal)){
				$consulta .= " AND \n";
			}
			
		}
		if(isset($id_equipamento)){
			
			$consulta .= " E_id=".$id_equipamento;
			
			if(
			isset($id_chamado) || 
			isset($aberto) || 
			isset($tipo) || 
			isset($dtInicial) || 
			isset($dtFinal)){
				$consulta .= " AND \n";
			}
			
		}
		if(isset($id_chamado)){
			
			$consulta .= " Ch_id=".$id_chamado;
			
			if(
			isset($aberto) || 
			isset($tipo) || 
			isset($dtInicial) || 
			isset($dtFinal)){
				$consulta .= " AND \n";
			}
			
		}
		if(isset($aberto)){
			switch ($aberto) {
				case TRUE:
					$consulta .= " Ch_aberto=1 OR Ch_aberto=2\n";
					break;
				case FALSE:
					$consulta .= " Ch_aberto=0\n";
					break;
				default:
					break;
			}
			
			if(
			isset($tipo) || 
			isset($dtInicial) || 
			isset($dtFinal)){
				$consulta .= " AND \n";
			}
			
		}
		
		if(isset($tipo)){			
			if(
			isset($dtInicial) || 
			isset($dtFinal)){
				$consulta .= " AND \n";
			}
			
		}
		if(isset($dtInicial)){
			$consulta .= " Ch_data_abertura >= '".$dtInicial."'\n";
			if(isset($dtFinal)){
				$consulta .= " AND \n";
			}
		}
		
		if(isset($dtFinal)){
			$consulta .= " So_data_solucionado <= '".$dtFinal."'\n";
		}
			
		$consulta .= "	ORDER BY CASE\n";
		$consulta .= "	    WHEN Ch_prioridade IS NULL THEN 2\n";
		$consulta .= "		WHEN Ch_aberto = 0 THEN 2\n";	
		$consulta .= "		WHEN Ch_aberto = 2 THEN 1\n";
		$consulta .= "	    WHEN Ch_aberto = 1 THEN 0\n";
		$consulta .= "	    WHEN So_data_solucionado IS NULL THEN 0\n";
		$consulta .= "	    ELSE 2\n";
		$consulta .= "	END , So_data_solucionado DESC, Ch_prioridade ASC\n";
		if(isset($inicial) && isset($por_pagina)){
			$consulta .= " LIMIT ".($inicial-1).",".$por_pagina;
		}
		$consulta .= ";\n";
		
		//echo "<!-- ".$consulta." -->\n";
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_chamados(): ".mysql_error());
		
		while($row=mysql_fetch_array($query)){
			$aux[]=$row;
		}
		
		mysql_free_result($query);
		
		return $aux;
	}
	
	/**
	 * RETORNA UMA STRING COM O ESTADO DO CHAMADO COM BASE EM SEU ID
	 * 
	 * @param int $id - ID do chamado
	 * 
	 * @return string
	 */
	function retorna_estado_chamado($chamado){
		$aux = null;
		switch ($chamado) {
			case 1:
				$aux = "Aberto";
				break;
			case 0:
				$aux = "Fechado";
				break;
				
			default:
				$aux = "ERRO";
				break;
		}
		return $aux;
	}
	
	/**
	 * RETORNA QUANTIDADE DE CHAMADOS CONFORME PARAMETROS
	 * 
	 * @todo incluir parametros.
	 * */
	function retorna_quantidade_total_de_chamados($id_filial = null,$id_setor = null,$id_usuario = null,$id_equipamento = null,$id_chamado = null,$aberto = null){
		global $_server;
		$cont=0;
		$aux = null;
		
		$consulta ="
			SELECT
				COUNT(*) AS quantidade
			FROM `chamados`.`dados_chamado`
		";
		
		if($id_filial != NULL){
			$consulta .= " WHERE F_id=".$id_filial;
			if($aberto == true)
				$consulta .= " AND Ch_aberto = 1";
			if($dtInicial != NULL && $dtFinal != NULL){
				$consulta .= "		AND Ch_data_abertura >= '".$dtInicial."'";
				$consulta .= "		AND So_data_solucionado <= '".$dtFinal."'";
			}
		}
		else if($id_setor != NULL){
			$consulta .= " WHERE S_id=".$id_setor;
			if($aberto == true)
				$consulta .= " AND Ch_aberto = 1";
			if($dtInicial != NULL && $dtFinal != NULL){
				$consulta .= "		AND Ch_data_abertura >= '".$dtInicial."'";
				$consulta .= "		AND So_data_solucionado <= '".$dtFinal."'";
			}
		}
		else if($id_usuario != NULL){
			$consulta .= " WHERE U_id=".$id_usuario;
			if($aberto == true)
				$consulta .= " AND Ch_aberto = 1";
			if($dtInicial != NULL && $dtFinal != NULL){
				$consulta .= "		AND Ch_data_abertura >= '".$dtInicial."'";
				$consulta .= "		AND So_data_solucionado <= '".$dtFinal."'";
			}
		}
		else if($id_equipamento != NULL){
			$consulta .= " WHERE E_id=".$id_equipamento;
			if($aberto == true)
				$consulta .= " AND Ch_aberto = 1";
			if($dtInicial != NULL && $dtFinal != NULL){
				$consulta .= "		AND Ch_data_abertura >= '".$dtInicial."'";
				$consulta .= "		AND So_data_solucionado <= '".$dtFinal."'";
			}
		}
		else if($id_chamado != NULL){
			$consulta .= " WHERE Ch_id=".$id_chamado;
			if($aberto == true){
				$consulta .= " AND Ch_aberto = 1";
			}
			if($dtInicial != NULL && $dtFinal != NULL){
				$consulta .= "		AND Ch_data_abertura >= '".$dtInicial."'";
				$consulta .= "		AND So_data_solucionado <= '".$dtFinal."'";
			}
		}else if($aberto == true){
			$consulta .= " WHERE Ch_aberto = 1";
		}
		else if($dtInicial != NULL && $dtFinal != NULL){
			$consulta .= "WHERE";
			$consulta .= "		Ch_data_abertura >= '".$dtInicial."'";
			$consulta .= "		AND So_data_solucionado <= '".$dtFinal."'";
		}
		
		if($inicial != NULL && $por_pagina != NULL){
			$consulta .= "	ORDER BY CASE";
			$consulta .= "	    WHEN Ch_prioridade IS NULL THEN 1";
		
			$consulta .= "	    ELSE 0";
			$consulta .= "	END , CASE Ch_aberto";
			$consulta .= "	    WHEN 1 THEN 0";
			$consulta .= "	    ELSE 1";
			$consulta .= "	END , CASE";
			$consulta .= "	    WHEN So_data_solucionado IS NULL THEN 0";
			$consulta .= "	    ELSE 1";
			$consulta .= "	END , So_data_solucionado DESC, Ch_prioridade ASC";
			$inicial = $por_pagina*($inicial-1);
			$consulta .= " LIMIT ".$inicial.",".$por_pagina;
		}else{
			$consulta .= " ORDER BY CASE WHEN Ch_prioridade Is NULL Then 1 Else 0 End, Ch_prioridade";
		}
		$consulta .= ";";
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_chamados(): ".mysql_error());
		while($row=mysql_fetch_array($query)){
			$aux[]=$row;
		}
		
		return $aux[0]['quantidade'];
	}
	
	/**
	 * RETORNA SOLUÇÕES DO CHAMADO
	 *	
	 * @param	int				$id_chamado				- ID do chamado ao qual será retornado as soluções
	 *
	 * @return	array|int		['So_id']				- ID da Solução
	 * @return	array|string	['So_descricao']		- DESCRIÇÃO da Solução
	 * @return	array|string	['So_data_inicio']		- DATA DE INÍCIO da Solução
	 * @return	array|int		['So_solucionado']		- 0 - Não Solucionado, 1 - Finalizado, 2 - Reaberto
	 * @return	array|string	['So_data_solucionado']	- DATA DE SOLUÇÃO da Solução
	 * @return	array|string	['So_previsao']			- DATA DE PREVISÃO para a Solução
	 * @return	array|int		['Ch_id']				- ID do Chamdo ao qual a Solução está ligada
	 * @return	array|int		['R_id']				- ID do Responsável pela Solução
	 * @return	array|string	['R_nome']				- NOME do Responsável pela Solução
	 * @return	array|int		['R_ramal']				- RAMAL do Responsável pela Solução
	 * @return	array|string	['R_email']				- EMAIL do Responsável pela Solução
	 */
	function retorna_chamado_solucoes($id_chamado){
		global $_server;
		
		$aux = null;
		
		$consulta = "";
		$consulta .= "	SELECT";
		$consulta .= "		*";
		$consulta .= "	FROM";
		$consulta .= "		DADOS_SOLUCAO";
		$consulta .= "	WHERE";
		$consulta .= "		Ch_id = ".$id_chamado;
		$consulta .= "	ORDER BY So_data_inicio DESC";
		$consulta .= ";";		
		
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_solucoes_chamado(): ".mysql_error());
		
		while($row = mysql_fetch_array($query)){
			$aux[] = $row;
		}
		
		mysql_free_result($query);
		
		return $aux;
	}
	
	/**
	 * RETORNA UMA STRING COM A INFORMAÇÃO REFERENTE AO TIPO DE SOLUÇÃO
	 * 
	 * @param int $tipo - [So_solucionado]
	 * 
	 * @return string - 
	 * */
	function retorna_tipo_solucao($tipo){
		$valor = null;
		switch ($tipo) {
			case 0:
				$valor = "Não Solucionado";
				break;
			case 1:
				$valor = "Finalizado";
				break;
			case 2:
				$valor = "Reaberto";
				break;
			default:
				$valor = "Erro";
				break;
		}
		return $valor;
	}
	
	/**
	 * REALIZA O DESCARTE DE UM EQUIPAMENTO
	 *	
	 * @param int 		$id_equipamento	- ID do equipamento descartado
	 * @param string	$motivo			- Motivo pelo qual o equipamento foi descartado
	 *
	 * @see retirar_vinculos_componentes()
	 * @see retirar_vinculos_equipamento()
	 * @see retorna_dados_equipamento()
	 */
	function descartar_equipamento($id_equipamento, $motivo){
		
		$dados = retorna_dados_equipamento($id_equipamento);
		if($dados['C_id']==2 || $dados['C_id']==4 || $dados['C_id']==5 || 
		$dados['C_id']==8 || $dados['C_id']==9 || $dados['C_id']==10 ||
		$dados['C_id']==11 || $dados['C_id']==12 || $dados['C_id']==13 ||
		$dados['C_id']==14 || $dados['C_id']==15 || $dados['C_id']==16){
			retirar_vinculos_componentes($id_equipamento,NULL);
		}else{
			retirar_vinculos_equipamento($id_equipamento,NULL);
		}
		
		global $_server;
		$sql = "
			INSERT INTO `DESCARTE` (
				`id`,
				`motivo`,
				`EQUIPAMENTO_id`
			) VALUES (
				NULL, 
				'".$motivo."',
				".$id_equipamento."
			)
		;";
		$query = mysql_query($sql)or die($sql.": ERRO descartar_equipamento();: ".mysql_error());
		atualiza_equipamento($id_equipamento, $dados['E_descricao'], "(descartado) " . $dados['E_nome'], $dados['E_mac'], $dados['E_ip'], $dados['E_nf'], $dados['E_ns'], $dados['C_id'],1);
	}
	
	/**	RETORNA A DATA ATUAL ACRESCIDA DE QUANTOS DIAS FORAM INFORMADOS
	 *	
	 *	@param	int	$numero_de_dias - Quantidade de dias a ser somado
	 *
	 *	@return string	- NO formato AAAA-MM-DD HH:MM:SS 
	 */	
	function retorna_soma_data($numero_de_dias){
		$dias = "+".$numero_de_dias." days";
		$soma = strtotime($dias);
		$data = date('Y-m-d', $soma);
		$data = $data." 18:00:00";
		return $data;
	}
	
	/**
	 *	RETORNA PRÓXIMO DIA ÚTIL SE FOR FINAL DE SEMANA
	 *
	 *	@param string $data - data no formato AAAA-MM-DD HH:MM:SS
	 *
	 *	@return string - no formato AAAA-MM-DD HH:MM:SS 
	 */
	function retorna_proximo_dia_util($data, $saida = 'Y-m-d H:i:s') {
		$timestamp = strtotime($data);
		$dia = date('N', $timestamp);		
		if ($dia == 7) {
			$timestamp_final = $timestamp + ((8 - $dia) * 3600 * 24);
		} else {
			$timestamp_final = $timestamp;
		}		
		return date($saida, $timestamp_final);
	}
	
	/**
	 *	VERIFICA SE O CHAMADO ESTÁ NO PRAZO SOLICITADO PELO USUÁRIO
	 *	
	 *	@param int $id_chamado - ID do chamado que sera verificado a urgência
	 *
	 *	@return int - 0: no prazo, 1: no dia do prazo, 2: atrasado
	 */
	function confere_urgencia_chamado($id_chamado){
		global $_server;
		$data_atual = date("Y-m-d H:i:s");
		$dia_atual = date("Y-m-d");
		
		$sql = "
			SELECT 
				prioridade
			FROM
				CHAMADO
			WHERE
				id = ".$id_chamado."
		;";
		$query = mysql_query($sql)or die($sql.": ERRO confere_urgencia_chamado();: ".mysql_error());
		$resultado = mysql_fetch_array($query);
		$dia_atual_v = explode(" ",$resultado['prioridade']);
		if($dia_atual_v[0] == $dia_atual){
			return 1;
		}else if($resultado['prioridade']>$data_atual || $resultado['prioridade']==NULL){
			return 0;
		}else
			return 2;
	}
	
	function retorna_dadosCSV(){
		global $_server;
		
		$aux = null;
		
		$consulta = "";
		$consulta .= "	SELECT";
		$consulta .= "	    matricula,";
		$consulta .= "		filial,";
		$consulta .= "		LCASE(TRIM(nome)) as nome,";
		$consulta .= "		TRIM(setor) as setor,";
		$consulta .= "		data_nascimento";
		$consulta .= "	FROM";
		$consulta .= "	    dados_csv";
		$consulta .= "	order by filial , setor , nome";
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_dadosCSV(): ".mysql_error());
		while($row=mysql_fetch_array($query)){
			$aux[]=$row;
		}
		return $aux;
	}
	
	/**
	 * Retorna ID do computador ao qual o componente está vinculado
	 * 
	 * @param int $componente - ID do componente a ser buscado seu computador
	 * 
	 * @return int - ID do computador do componente | NULL para nenhum
	 */
	function retorna_computador_componente($componente){
		global $_server;
		$aux = null;
		
		$consulta = "";
		$consulta .= "	SELECT";
		$consulta .= "	    COMPUTADOR_id";
		$consulta .= "	FROM";
		$consulta .= "	    computador_has_itens";
		$consulta .= "	WHERE";
		$consulta .= "		EQUIPAMENTO_id = ".$componente;
		$consulta .= "	LIMIT 1";
		$query = mysql_query($consulta)or die($consulta.": ERRO retorna_dadosCSV(): ".mysql_error());
		while($row=mysql_fetch_array($query)){
			$aux=$row['COMPUTADOR_id'];
		}
		return $aux;
	}
	
	/**
	 * Função que verifica se um equipamento é um componente
	 * 
	 * @param int $classe - ID da classe do equipamento a ser verificado
	 * 
	 * @return bool - TRUE: é componente, FALSE: caso contrário
	 */
	function verificaSeComponente($classe){
		global $_server;
		$consulta = "";
		$consulta .= "	SELECT";
		$consulta .= "	    id,";
		$consulta .= "		nome";
		$consulta .= "	FROM";
		$consulta .= "	    classe";
		$query = mysql_query($consulta)or die($consulta.": ERRO verificaSeComponente(): ".mysql_error());
		while($row=mysql_fetch_array($query)){
			if($classe == (2 || 4 || 5 || 8 || 9 || 10 || 11 || 12 || 13 || 14 || 15 || 16 || 19)){
				return TRUE;
			}
		}
		return FALSE;
	}
	/**
	 * FUNÇÃO QUE RETORNA A PÁGINA ATUAL
	 * 
	 * @param bool $ext - caso seja necessário vir com a extensão DEFAULT: FALSE
	 * 
	 * @return srting - Nome da página atual
	 */
	function retorna_pagina_atual($ext = FALSE) {
		$delimiter	= ".";
		$string		= substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
		if($ext == FALSE){
			$aux	= explode($delimiter, $string);
			return $aux[0];
		}else{
			return $string;
		}		
	}
	
?>