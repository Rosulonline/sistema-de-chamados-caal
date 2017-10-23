<?php
	include("../funcoes.php");
	require_once '../wideimage-lib/WideImage.php';
	
	$login = protegePagina();
	
	$nomeImagem = date("dmyHis");
	
	$uri = "../uploads/imagens/". $nomeImagem .".jpg";
	
	$valorMax = 800;
	
	$img		= WideImage::loadFromUpload('anexo');
	$altura		= $img->getHeight();
	$largura	= $img->getWidth();
	$format		= "jpg";
	
	$proporcao	= $largura/$altura;
	
	$altura		=	800;
	$largura	=	800/$proporcao;
	
	$img->resize($largura,$altura)->saveToFile($uri);
	
	echo "<p>Enviado!</p>";
	echo '
	<script>
		$("<p>Imagem: '.$nomeImagem. '</p>").appendTo("#imagens");
	</script>';
?>