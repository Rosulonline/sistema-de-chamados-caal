<?php
	include("../funcoes.php");
	include("../class_vcf.php");
	/**
	 * 	RETORNA UMA MATRIZ COM OS DADOS DE UM Usuário
	 *	
	 *	@param int	$id_filial	- ID da filial
	 *	@param int	$id_setor	- ID do Setor
	 *	@param int	$id_usuario	- ID do Usuário
	 *	@param bool	$ativo		- TRUE: usuário está ativo, FALSE: usuário inativo, NULL: indiferente
	 *
	 *	@return array|int		['U_id']		- id do Usuario
	 *	@return array|string	['U_nome']		- nome do Usuario
	 *	@return array|int 		['U_ramal']		- ramal do Usuário
	 *	@return array|string	['U_email']		- email do Usuário
	 *	@return array|string	['U_usuario']	- nome de Usuário do Usuário
	 *	@return array|string	['U_senha']		- senha do Usuário
	 *	@return array|int		['U_msn']		- MSN do Usuário: 1-liberado, 0-não liberado
	 *	@return array|string	['U_email_msn'] - endereço do MSN do Usuário
	 *	@return array|int		['U_disp']		- Acesso a CD,Pendrive,etc do Usuário: 1-liberado, 0-não liberado
	 *	@return array|int		['U_net']		- Acesso a internet do Usuário: 1-liberado, 0-não liberado
	 *	@return array|int		['U_ativo']		- Usuário ativo: 1-ativo, 0-não ativo
	 *	@return array|int		['S_id']		- id do Setor do Usuário
	 *	@return array|string	['S_nome']		- nome do Setor do Usuário
	 *	@return array|int		['F_id']		- id da Filial do Usuário
	 *	@return array|string	['F_nome']		- nome da Filial do Usuário
	 *	@return array|string	['F_endereco']	- endereço da Filial do Usuário
	 *	@return array|string	['F_telefone']	- telefone da Filial do Usuário
	 */		
	$usuarios = retorna_usuarios();
	
	for($i=0;$i<count($usuarios);$i++){
		$vCard = new VCF($data);
		
			$data = array(
            'firstname' => $usuarios[$i]['U_nome'],
              'surname' => '',
             'nickname' => $usuarios[$i]['U_nome'],
             'birthday' => '',
              'company' => 'Coop. Agroind. Alegrete Ltda.',
             'jobtitle' => '',
         'workbuilding' => '',
           'workstreet' => '',
             'worktown' => '',
           'workcounty' => '',
         'workpostcode' => '',
          'workcountry' => '',
        'worktelephone' => '',
            'workemail' => $usuarios[$i]['U_email'],
              'workurl' => 'http://www.caal.com.br',
         'homebuilding' => '',
           'homestreet' => '',
             'hometown' => '',
           'homecounty' => '',
         'homepostcode' => '',
          'homecountry' => '',
        'hometelephone' => '',
            'homeemail' => '',
              'homeurl' => '',
               'mobile' => '',
                'notes' => '');
		//$vCard->show();
	}
	
	
	
?>