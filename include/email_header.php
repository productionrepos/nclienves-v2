<?php

function email_header($tipo){
	if($tipo == 'Activación de cuenta'){
		$header = 'activar ';
	}
	elseif($tipo == 'Reinicio de contraseña'){
		$header = 'generar una nueva contraseña para ';
	}

	return '<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
		<style>
			table {
				border: 1px solid black;
				border-spacing: 0;
				border-radius: 25px;
			}
			#initialColumn {
				background: #D8D8D8;
				border-radius: 25px 25px 0px 0px;
			}
			#finalColumn1 {
				background: #BDBDBC;
				border-radius: 0px 0px 0px 25px;
			}
			#finalColumn2 {
				background: #BDBDBC;
				border-radius: 0px 0px 25px 0px;
			}
		</style>
	
	</head>
	<body>
		<div align="center">
			<table width=80% cellspacing=0 cellpadding=0>
				<tr>
					<td align="center" id="initialColumn" colspan="2"><img src="http://'.$_SERVER['HTTP_HOST'].'/include/Logotipo_Spread.png" width=60%></td>
				</tr>
				<tr bgcolor="#D8D8D8">
					<td align="center" colspan="2">
						<h1><span style="color: #3C3C3B">Bievenido a</span> <span style="color: #009972">Spread</span></h1>
						<h4>Este email ha sido generado de manera automatica con la finalidad de '.$header.'tu cuenta en Spread.<br>
						Solo debes presionar el botón de activación.</h4>
					</td>
				</tr>';
}

