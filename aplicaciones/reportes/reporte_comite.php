<?    header("Content-type: application/octet-stream");//indicamos al navegador que se est� devolviendo un archivo
header("Content-Disposition: attachment; filename=Consolidado de Comite.xls");//con esto evitamos que el navegador lo grabe en su cach�
header("Pragma: no-cache");
header("Expires: 0");

//include("../../librerias/lib/@include.php");
include("../../librerias/lib/@config.php");
   include(SUE_PATH."global.php");
include("../../librerias/php/funciones_general_2017.php");	
include("../../librerias/php/funciones_general_2015.php");
   include("../../librerias/php/funciones_general.php");








	$muestra_consolidado = "NO";
if($_GET["comite_numero"] <> 0){
	$muestra_consolidado = "SI";
	$comple_sql = " and id_comite = ".$_GET["comite_numero"];
	}else{
		
			$mes="";
if($_GET["comite_mes"] <> 0){
		$mes = $_GET["comite_mes"];
		if($mes<10){
			$mes = "0".$mes;
			}	
			$mes = $_GET["comite_ano"]."-".$mes."-";
		$comple_sql = " and fecha like '%".$mes."%' ";
	}

	
if($_GET["comite_ano"] <> 0 and $mes==""){

			$comple_sql = " and fecha like '%".$_GET["comite_ano"]."%' ";
			}	

if($_GET["comite_mes2"] <> 0 and $_GET["comite_mes"] <> 0 ){
		$mes2 = $_GET["comite_mes2"];
		if($mes2<10){
			$mes2 = "0".$mes2;
			}	
			$mes = $mes."01";
			$mes2 = $_GET["comite_ano"]."-".$mes2."-31";
			
		$comple_sql = " and fecha > '".$mes."' and fecha < '".$mes2."' ";
	}
		
		
		}
	

$indicador_porcentaje = 70;
$dias_maximo_indicador = 36;//LGALIZACION DE oTROSI
$dias_maximo_indicador_ad = 0;
$desde_cuando_aplica_id_comite=171;	
$desde_cuando_aplica_id_comite_2016=162;	
$abre_indicador = "NO";
$bloquea_ratificacion = "";

if($_GET["comite_numero"] >= $desde_cuando_aplica_id_comite_2016){
	$bloquea_ratificacion = "SI";
	}
if($_GET["comite_numero"] > $desde_cuando_aplica_id_comite){
	$abre_indicador = "SI";
	}
	
if($_GET["comite_ano"] >= 2016){
	$bloquea_ratificacion = "SI";
	if($_GET["comite_ano"] > 2016 or ($_GET["comite_ano"] == 2016 and $_GET["comite_mes"] >=4)){
		$abre_indicador = "SI";
		}
		
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style>
.titulo1 {
	font-size:24px;
	color:#135798;
		
}
.titulo2 {
	font-size:16px;
		
}
.titulo3 {
	font-size:20px;
	background-color:#135798;
	color:#FFF;
		
}
.titulo4 {
	font-size:20px;
	background-color:#093;
	color:#FFF;
		
}

.titulo4_sub1{
	font-size:10px;
	background-color:#093;
	color:#FFF;
		
}

</style>
</head>

<body>
<table width="100%" border="1">
  <tr>
    <td colspan="2" rowspan="3" align="center" valign="middle">&nbsp;&nbsp;<img src="https://www.abastecimiento.hocol.com.co/sgpa/imagenes/coorporativo/logo-cliente.png" alt="" /></td>
    <td colspan="28" align="left" class="titulo1"><strong>CONSOLIDADO ACTAS DE COMIT�</strong></td>
  </tr>
  <tr class="titulo2">
    <td align="right">&nbsp;</td>
    <td align="right">A�o del Comit&eacute;:</td>
    <td colspan="25" align="left">
      <?
    if($_GET["comite_ano"] <> 0){
		echo $_GET["comite_ano"];
		}else{
			echo "Todos";
			}
	?>
    </td>
  </tr>
  <tr class="titulo2">
    <td align="right">&nbsp;</td>
    <td align="right">Mes del Comit&eacute;:</td>
    <td colspan="25" align="left"><?
    if($_GET["comite_mes"] <> 0){
		if($_GET["comite_mes"] == 1){
			$mes_muestra = "Enero";
			}
		if($_GET["comite_mes"] == 2){
			$mes_muestra = "Febrero";
			}
		if($_GET["comite_mes"] == 3){
			$mes_muestra = "Marzo";
			}
		if($_GET["comite_mes"] == 4){
			$mes_muestra = "Abril";
			}
		if($_GET["comite_mes"] == 5){
			$mes_muestra = "Mayo";
			}
		if($_GET["comite_mes"] == 6){
			$mes_muestra = "Junio";
			}
		if($_GET["comite_mes"] == 7){
			$mes_muestra = "Julio";
			}
		if($_GET["comite_mes"] == 8){
			$mes_muestra = "Agosto";
			}
		if($_GET["comite_mes"] == 9){
			$mes_muestra = "Septiembre";
			}
		if($_GET["comite_mes"] == 10){
			$mes_muestra = "Actubre";
			}
		if($_GET["comite_mes"] == 11){
			$mes_muestra = "Noviembre";
			}
		if($_GET["comite_mes"] == 12){
			$mes_muestra = "Diciembre";
			}
		echo $mes_muestra;
		
		
		}else{
			echo "Todos";
			}
	?></td>
  </tr>
  <?

  if(	$muestra_consolidado == "SI"){
		
		$sele_comite_uno = traer_fila_row(query_db("select * from $c1 where id_comite = ".$_GET["comite_numero"]));
		
  ?>
  <tr class="titulo2">
    <td colspan="4" align="right">Comit&eacute; N&uacute;mero:</td>
    <td colspan="25" align="left"><?=numero_item_pecc($sele_comite_uno[6],$sele_comite_uno[7],$sele_comite_uno[8])?></td>
  </tr>
  <tr class="titulo2">
    <td colspan="4" align="right" style="vertical-align:middle">Asistentes de Aprobar&oacute;n:</td>
    <td colspan="25" align="left"><?
    $sel_asistentes = query_db("select t1.rol_aprobacion, t2.nombre_administrador  from t3_comite_asistentes as t1, t1_us_usuarios as t2 where t1.id_comite = ".$_GET["comite_numero"]." and t1.requiere_aprobacion = 1 and t1.id_us = t2.us_id order by t1.orden");
	while($sel_asis = traer_fila_db($sel_asistentes)){
		
		echo $sel_asis[0]." - ".$sel_asis[1]."<br />";
		}
	?></td>
  </tr>
  <tr class="titulo2">
    <td colspan="4" align="right"  style="vertical-align:middle">Otros Asistentes:</td>
    <td colspan="25" align="left"><?
    $sel_asistentes = query_db("select t1.rol_aprobacion, t2.nombre_administrador  from t3_comite_asistentes as t1, t1_us_usuarios as t2 where t1.id_comite = ".$_GET["comite_numero"]." and t1.requiere_aprobacion <> 1 and t1.id_us = t2.us_id order by t1.orden");
	while($sel_asis = traer_fila_db($sel_asistentes)){
		
		echo $sel_asis[0]." - ".$sel_asis[1]."<br />";
		}
	?></td>
  </tr>
  <?
  
  }
  ?>
  <tr>
    <td align="center" rowspan="2" class="titulo3" >N&uacute;mero del Comit�</td>
    <td align="center" rowspan="2" class="titulo3">Fecha del Comit�</td>
    <td align="center" rowspan="2" class="titulo3">Tipo de Comit&eacute;</td>
    <td align="center" rowspan="2" class="titulo3">Tipo de Permiso</td>
    <td align="center" rowspan="2" class="titulo3">Tipo de Proceso </td>
    <td colspan="4" align="center" class="titulo3">Informacion de PECC</td>
    <td align="center" rowspan="2" class="titulo3">Tipo de la Solicitud</td>
    <td align="center" rowspan="2" class="titulo3">N&uacute;mero de la Solicitud</td>
    <td align="center" rowspan="2" class="titulo3">Resultado del Comit�</td>
   <? if($bloquea_ratificacion == ""){?> <td align="center" rowspan="2" class="titulo3">Verificaci&oacute;n del Presidente</td><? }?>
    <td align="center" rowspan="2" class="titulo3">&Aacute;rea Responsable</td>
    <td align="center" rowspan="2" class="titulo3">Gerente Solicitud</td>
    <td align="center" rowspan="2" class="titulo3">Profesional Encargado</td>
    <td align="center" rowspan="2" class="titulo3">Objeto de la Solicitud</td>
    <td align="center" rowspan="2" class="titulo3">Recomendaci&oacute;n</td>
    <td align="center" rowspan="2" class="titulo3">Aplica Socios</td>
    <td align="center" rowspan="2" class="titulo3">Fecha de la Respuesta de los Socios</td>
    <td align="center" rowspan="2" class="titulo3">Respuesta de los Socios</td>
    <td align="center" rowspan="2" class="titulo3">N&uacute;mero de Contrato</td>
    <td align="center" rowspan="2" class="titulo3">Contratista</td>
    <td align="center" rowspan="2" class="titulo3">Valor USD</td>
    <td align="center" rowspan="2" class="titulo3">Valor COP</td>
    <td align="center" rowspan="2" class="titulo3">Comentario del Comit�</td>
    
    <?
	
	
      if(	$abre_indicador == "SI"){
	?>
    <td align="center" rowspan="2" class="titulo4">Fecha Ideal de Presentaci�n a Comit� (<strong class="titulo4_sub1">Esta informaci�n es calculada con respecto a los tiempos promedio por proceso</strong>)</td>
    <td align="center" rowspan="2" class="titulo4">Fecha de Vencimiento del Contrato  (<strong class="titulo4_sub1">Esta informaci�n es tomada del m�dulo de contratos, congelada en la fecha de presentaci�n al comit�</strong>)</td>
    <td align="center" rowspan="2" class="titulo4">Fecha real de Presentaci&oacute;n a Comit� (<strong class="titulo4_sub1">Esta informaci�n es tomada de la fecha de celebraci�n del comit�</strong>)</td>
    <td align="center" rowspan="2" class="titulo4">A Tiempo / Fuera de Tiempo Vigencia (<strong class="titulo4_sub1"> 1. ADJUDICACIONES / PERMISOS: Que la "Fecha Ideal de Presentaci�n a Comit�" sea igual o anterior a la "Fecha Real de Presentaci�n al Comit�"  2. OTROSI: Valida que la "Fecha de Vencimiento del Contrato" mas 36 d�as h�biles no sea superior a la fecha real de presentaci�n a Comit�</strong>) </td>
    <td align="center" rowspan="2" class="titulo4">Porcentaje de Ejecuci&oacute;n (<strong class="titulo4_sub1">Esta informaci�n se toma del ultimo reporte de contratos a la fecha de presentaci�n al comit�</strong>)</td>
    <td align="center" rowspan="2" class="titulo4">A tiempo / Fuera de Tiempo Ejecuci&oacute;n (<strong class="titulo4_sub1">Esta informaci�n se calcula con base en el porcentaje de ejecuci�n: Menor o igual a <?=$indicador_porcentaje?>% A Tiempo, mayor a <?=$indicador_porcentaje?>% Fuera de Tiempo</strong>)</td>
    <td align="center" rowspan="2" class="titulo4">Observaci�n</td>
    <?
	  }
	?>
  </tr>
  <tr class="titulo3">
   <td width="8%" align="center" class="columna_subtitulo_resultados">Origen PECC</td>
   <td width="8%" align="center" class="columna_subtitulo_resultados">Linea del PECC</td>
   <td width="8%" align="center" class="columna_subtitulo_resultados">Requiere modificacion</td>
   <td width="8%" align="center" class="columna_subtitulo_resultados">Justificacion de la modificacion</td>
 </tr>
  <?


$sel_repor = query_db("select num1, num2, num3, fecha, permiso_o_adjudica, tipo_proceso, Expr1, Expr2, Expr3, estado, area, gerente_solicitud, CAST(objeto_solicitud AS text), Cast(ob_solicitud_adjudica as text), Cast(recomendacion_adjudica as text), cast(recomendacion as text), usd_permiso, cop_permiso, usd_ad, cop_ad, Cast(comentario_secretrario AS text), id_comite, Profesional, t1_tipo_proceso_id, contrato_id, id_item, t1_tipo_contratacion_id, orden, tipo_comite_extraordinario, Expr4, solicitud_rechazada, solicitud_desierta, proveedores_sugeridos, valor_solicitud_comite, presidente, aplica_presidente, verificacion_general_comite, presidente_fecha, campo_contrato_vencimiento,valor_solicitado_eq, estado_solicitud, convirte_marco from vista_reporte_comite where Expr4 <> 33 $comple_sql order by num3 asc, valor_solicitud_comite desc");
  while($sel_r = traer_fila_db($sel_repor)){
  		$select_info_pecc="SELECT origen_pecc, pecc_linea, pecc_modificado, Cast(pecc_modificado_observacion AS TEXT) AS justificacion FROM v_peec_historico WHERE id_item=$sel_r[25]";
  		$result_info_pecc=traer_fila_db(query_db($select_info_pecc));
	  $comple_texto_tp_proceso="";
	$res_comi="";
	  $rechazado="";
		$desierto="";
		if($sel_r[30]==1){
			$rechazado="SI";
			}
		if($sel_r[31]==1){
			$desierto="SI";
			}
	  
	  $numero_comite = numero_item_pecc($sel_r[0],$sel_r[1],$sel_r[2]);
	  $numero_consecut = numero_item_pecc($sel_r[6],$sel_r[7],$sel_r[8]);
	  
	  	
		if($sel_r[4] ==1){
			$nombre_tp = "PERMISO";
			
			$valor_usd=$sel_r[16];
			$valor_cop=$sel_r[17];
			
			$fecha_aprueba_ad = "";
			$ob = $sel_r[12];
			$reco = $sel_r[15];	
			
					
		if($ob == ""){
			$ob = $sel_r[13];
			}
		if($reco == ""){
			$reco = $sel_r[14];
			}
				
				$sel_si_socios = traer_fila_row(query_db("select * from t2_agl_secuencia_solicitud where id_rol = 11 and tipo_adj_permiso = 1 and estado = 1 and id_item_pecc = ".$sel_r[25]));
			}else{
				$nombre_tp = "ADJUDICACION";
				
				$valor_usd=$sel_r[18];
				$valor_cop=$sel_r[19];
				
				if($sel_r[9] == 1){
				$fecha_aprueba_ad = $sel_r[3];
				
				
				}
				
				$ob = $sel_r[13];
				$reco = $sel_r[14];
				
				if($ob == ""){
			$ob = $sel_r[12];
			}
		if($reco == ""){
			$reco = $sel_r[15];
			}
				
		$sel_si_socios = traer_fila_row(query_db("select * from t2_agl_secuencia_solicitud where id_rol = 11 and tipo_adj_permiso = 2 and estado = 1 and id_item_pecc = ".$sel_r[25]));
				}// FIN SI ES ADJUDICACION
				
		if($sel_si_socios[0]>0){//si tiene socios
				$tex_socios = "SI";	
				$sel_fecha_aprob = traer_fila_row(query_db("select * from t2_agl_secuencia_solicitud_aprobacion where id_secuencia_solicitud = ".$sel_si_socios[0]));
				if($sel_fecha_aprob[0]>0){
				if($sel_fecha_aprob[4] == 1 or $sel_fecha_aprob[4] == 4){
						if($sel_r[23]==11){
							$resultado_socios = "INFORMADO";
							}else{
								$resultado_socios = "APROBADO";
							}
					}else{
						if($sel_r[23]==11){
							$resultado_socios = "NO INFORMADO - DEVUELTO AL PROFESIONAL";
							}else{
						$resultado_socios = "DEVUELTO AL PROFESIONAL";
							}
						}
				}else{
					$resultado_socios = "SIN RESPUESTA";
					}
						
					$fecha_socios = $sel_fecha_aprob[3];
				
			}else{// si no tiene socios
				$tex_socios = "NO";	
				$fecha_socios="N/A";
				$resultado_socios = "N/A";		
				}
		
		if($sel_r[9] == 1){
			if($sel_r[23]==11){
			$res_comi = "INFORMADO";
			}else{
				$res_comi = "APROBADO";
				}
			}else{
				if($sel_r[9] == 2){
					$res_comi = "PENDIENTE";
					}else{
				if($sel_r[23]==11){
					$res_comi = "NO INFORMADO - DEVUELTO AL PROFESIONAL";
				}else{
					$res_comi = "DEVUELTO AL PROFESIONAL";
					}
					}
				}
				
				
				if($sel_r[9] == 10){
					$res_comi="RECHAZADO";
					}
					$comple_texto="";
				if($sel_r[31]==1){
					$res_comi="DECLARADO DESIERTO";
					$comple_texto_tp_proceso="DECLARADO DESIERTO - ";
					}

					
					if($sel_r[29]==3){
						$res_comi="SIN ACCIONES";
						}
				
		if($sel_r[26] == 1){
			$text_tipo_solici = "SERVICIO";
			}else{
				$text_tipo_solici = "BIENES";
				}
		
  ?>
  <tr>
    <td><?=$numero_comite?></td>
    <td><?=$sel_r[3]?></td>
    <td><? if ($sel_r[28] == 1) echo "EXTRAORDINARIO"; else echo "NORMAL";?></td>
    <td><?=$nombre_tp?></td>
    <td><?=$comple_texto_tp_proceso?><?=$sel_r[5]?></td>
    <td align="center"><? if($result_info_pecc[0]==1) echo "Ninguno"; if($result_info_pecc[0]>1) echo $result_info_pecc[0];?></td>
	<td align="center"><? if($result_info_pecc[1]!="") echo saca_nombre_lista("t1_lineas_pecc",$result_info_pecc[1],'detalle','id');?></td>
	<td align="center"><? if($result_info_pecc[2]==1) echo "SI"; if($result_info_pecc[2]==2) echo "NO";?></td>
	<td align="center"><? if($result_info_pecc[3]!="") echo $result_info_pecc[3];?></td>
    <td><?=$text_tipo_solici?></td>
    <td><?=$numero_consecut?></td>
    <td><?=$res_comi?></td>
  <? if($bloquea_ratificacion == ""){?>  <td><?
    
	
		if($sel_r["aplica_presidente"] == 1 and $sel_r[21] < 117 ){
		
		if($sel_r["presidente"]==1){
				echo "Verificado el ".$sel_r["presidente_fecha"];
			}else{
				echo "Aun no se ha verificado";
				}
		
		}else{
			echo "No requiere";
			}
	
	?>
    
    
    </td><? }?>
    <td><?=$sel_r[10]?></td>
    <td><?=$sel_r[11]?></td>
    <td><?=$sel_r[22]?></td>
    <td><?=$ob?></td>
    <td><?=$reco?></td>
    <td><?=$tex_socios?></td>
    <td><?=$fecha_socios?></td>
    <td><?=$resultado_socios?></td>
    <td><?
    $contratista = "";
	$tiene_coma = "";
	$coma_contratista="";
	if($sel_r[23] == 4 or $sel_r[23] == 5 or $sel_r[23] == 11 or $sel_r[23] == 12){

		$sel_contr = query_db("select t1.consecutivo, t1.creacion_sistema, t1.apellido, t2.razon_social from t7_contratos_contrato as t1, t1_proveedor as t2 where t1.contratista = t2.t1_proveedor_id and t1.id = ".$sel_r[24]);
			while($sel_apl = traer_fila_db($sel_contr)){
					$numero_contrato1 = "C";			
					$separa_fecha_crea = explode("-",$sel_apl[1]);
					$ano_contra = $separa_fecha_crea[0];
					
					$numero_contrato2 = substr($ano_contra,2,2);
					$numero_contrato3 = $sel_apl[0];
					$numero_contrato4 = $sel_apl[2];
					
					echo numero_item_pecc_contrato($numero_contrato1,$numero_contrato2,$numero_contrato3,$numero_contrato4, $sel_r[24]);
					$contratista=  $sel_apl[3];
			}
		
		}
		
		if(($sel_r[23] == 1 or $sel_r[23] == 2 or $sel_r[23] == 3 or $sel_r[23] == 6) and $sel_r[4] ==2){
			

					$sel_contr_sql = query_db("select t1.consecutivo, t1.creacion_sistema, t1.apellido, t2.razon_social, t1.id from t7_contratos_contrato as t1, t1_proveedor as t2 where t1.contratista = t2.t1_proveedor_id and t1.id_item = ".$sel_r[25]);
					
						while($sel_contr = traer_fila_db($sel_contr_sql)){
						
							$numero_contrato1 = "C";			
							$separa_fecha_crea = explode("-",$sel_contr[1]);
							$ano_contra = $separa_fecha_crea[0];
							
							$numero_contrato2 = substr($ano_contra,2,2);
							$numero_contrato3 = $sel_contr[0];
							$numero_contrato4 = $sel_contr[2];
						
						if($tiene_coma <> ""){
							echo $tiene_coma;
							$coma_contratista = ", ";
						}else{
							$tiene_coma = ", ";
							}

							echo numero_item_pecc_contrato($numero_contrato1,$numero_contrato2,$numero_contrato3,$numero_contrato4, $sel_contr[4]);
							$contratista.=  $coma_contratista.$sel_contr[3];
						}	
							
			
			
		}
		
				
			if($sel_r[23] == 7 or $sel_r[23] == 8){
				
	
			echo contratos_relacionados_solicitud_para_campos_solo_contratos($sel_r[25], "NO");
			$contratista=contratos_relacionados_solicitud_para_campos_solo_proveedores($sel_r[25], "NO");
				
			}
			
	$contratis_permi="";
	
	if(($sel_r[23] == 1 or $sel_r[23] == 2 or $sel_r[23] == 3 or $sel_r[23] == 6) and $sel_r[4] ==1){

	$contratistas_permiso = query_db("select t2.razon_social from t2_relacion_proveedor as t1, t1_proveedor as t2 where t1.id_item = ".$sel_r[25]." and t1.id_proveedor = t2.t1_proveedor_id");
	while ($sel_pro_permiso = traer_fila_db($contratistas_permiso)){
	
	$contratis_permi = $sel_pro_permiso[0]." - ".$contratis_permi;
	
	}
	if($contratis_permi == ""){
	$contratis_permi=$sel_r[32];
	}
	}
	?></td>
    <td><?=$contratista?> </td>
    <td><? 
		
		
		
		/*Valores*/
		
		
			$valor_solicitud = explode("---",valor_solicitud($sel_r[25],$sel_r[4]));
		
			$valor_usd = $valor_solicitud[0];
			$valor_cop = $valor_solicitud[1];
		
		
		/*Valores*/
	  
	  
	

/*
	$sel_valores_aprobados_en_comite = traer_fila_row(query_db("select valor_solicitado_usd, valor_solicitado_cop,  valor_solicitado_eq from  	t3_comite_relacion_item where id_comite=".$sel_r[21]." and id_item=".$sel_r[25]));
	

$valor_equivalente = ($valor_cop/1780) +($valor_usd);
	if($sel_valores_aprobados_en_comite[0]>0){
		$valor_usd = $sel_valores_aprobados_en_comite[0];
		}
	if($sel_valores_aprobados_en_comite[1]>0){
		$valor_cop = $sel_valores_aprobados_en_comite[1];
		}
	if($sel_valores_aprobados_en_comite[2]>0){
		$valor_equivalente = $sel_valores_aprobados_en_comite[2];
		}
	*/
	echo number_format($valor_usd, 0, ",",".")?></td>
    <td><?=number_format($valor_cop, 0, ",",".")?></td>
   <td><?
    

		$text_coment = "";
	
	$sel_comentarios_ind = query_db("select observacion,  id_asistente from  t3_comite_aprobacion where id_comite = ".$sel_r[21]." and id_item = ".$sel_r[25]." and observacion is not null and observacion <> ''");

	
	
	while($sel_coment = traer_fila_db($sel_comentarios_ind)){
	
	$sel_usuario_asistente = traer_fila_row(query_db("select t2.nombre_administrador from t3_comite_asistentes as t1, t1_us_usuarios as t2 where t1.id_asistente = ".$sel_coment[1]." and t1.id_us = t2.us_id"));

		$text_coment = $text_coment." [".$sel_usuario_asistente[0]." - ".$sel_coment[0]."]";
		}
		if($sel_r[20] <> "" and $sel_r[20] <> " " and $sel_r[20] <> "  "){
			$text_coment = "[Secretario del Comite - ".$sel_r[20]."] ".$text_coment;
			}
			
		echo $text_coment;
	?></td>
    
    <?
      if(	$abre_indicador == "SI"){
	?>
    
   <td><?
   $fecha_ideal ="-";
   $primera_actividad = 0;
   if($sel_r[23]==1 or $sel_r[23]==2 or $sel_r[23]==3 or $sel_r[23]==6 ){//solo sii es neg directa licitacion o ad directa
	   if($sel_r[4] ==1){
		   $actividad_comite = 7;
	   }else{
		   $actividad_comite = 17;
		   }

    $sel_actividades_resumen = query_db("select actividad_estado,tiempo,fecha_se_requiere,fecha_real,tiempo_para_actividad,actividad_estado_id,dias_reales, estado,encargado from $vpeec3 where id_item =".$sel_r[25]."  and actividad_estado_id <=".$actividad_comite." order by actividad_estado_id");
	  while($ac_resum = traer_fila_db($sel_actividades_resumen)){
				if($fecha_reprograma != ""){
					$fecha_reprograma = sumar_fechas($fecha_reprograma, $ac_resum[1]);
					}

		  if($primera_actividad == 0){
			  	$primera_actividad = $ac_resum[5];
				$fecha_ideal = $fecha_empiesa;
				if($ac_resum[3] != ""){
					$dias_reales =0;
					$fecha_reprograma = $ac_resum[3];
				}
			  }else{
				 $fecha_ideal = restar_fechas($ac_resum[2], $ac_resum[4]);
				  }
				 
			  }
   }
   echo $fecha_ideal;
   ?></td>
   <td><?
   $fecha_vencimiento = "-";
   $contratos_aplica = "";
   $observacion = "";
   $cont=0;
//   echo $sel_r[23].": ";
   if($sel_r[23]==4 or $sel_r[23]==5 or $sel_r[23]==7 ){//solo si es otro si
	   $fecha_vencimiento_exp = explode("*",$sel_r[38]);
	   $cuantos_contratos = count($fecha_vencimiento_exp);
	   
	   for($i=0; $i < $cuantos_contratos; $i++){
		   if($solo_fecha_exp[1] != ""){
			   $cont=$cont+1;
			   }
		   $solo_fecha_exp = explode("Fecha terminaci&oacute;n ",$fecha_vencimiento_exp[$i]);
		   $contratos_aplica.= ",".$solo_fecha_exp[0];
		   if($fecha_vencimiento == "-" or ($solo_fecha_exp[1]<$fecha_vencimiento) or $solo_fecha_exp[1] != ""){//tome solo ma menor
		   $fecha_vencimiento = $solo_fecha_exp[1];
		   }
		   }
		   
		   if($cont > 1){
		   $observacion = "Este indicador toma la informaci�n del contrato mas cercana a estar fuera de tiempo en vigencia y/o ejecuci�n";
		   }
	   }
	   echo $fecha_vencimiento;
   ?></td>
   <td><?
   if(($fecha_vencimiento != "" and $fecha_vencimiento != "-") or ($fecha_ideal != "" and $fecha_ideal != "-")){ echo $sel_r[3]; } else echo "-";?></td>
   <td><?
   $tiempo_indicador="-";
   
   		if($fecha_vencimiento != "" and $fecha_vencimiento != "-"){//indicador de otros si y ampliaciones
   		$fecha_menos_dias = restar_fechas($fecha_vencimiento, $dias_maximo_indicador);//suma a la fecha de convimiento DIAS HABILES
		//echo $fecha_menos_dias.": ";
		if($fecha_menos_dias < $sel_r[3]){
			$tiempo_indicador = "Fuera de Tiempo";
			}else{
				$tiempo_indicador = "A Tiempo";
				}
		}
		
		if($fecha_ideal != "" and $fecha_ideal != "-"){//indicador de neg directa licitacion y ad directa
   		$fecha_mas_dias = sumar_fechas($fecha_ideal, $dias_maximo_indicador_ad);//suma a la fecha de convimiento DIAS HABILES
		//echo $fecha_mas_dias.": ";
		if($fecha_mas_dias < $sel_r[3]){
			$tiempo_indicador = "Fuera de Tiempo";
			}else{
				$tiempo_indicador = "A Tiempo";
				}
		}
		echo $tiempo_indicador;
   ?></td>
   <td><?
   $porcenta_ejecu = "-";

   if($sel_r[23]==4 or $sel_r[23]==5 or $sel_r[23]==7 and $sel_r[39]>0 ){//solo si es otro si
   	$fecha_ejecucio = explode("-",$sel_r[3]);
	$sel_mes_corte = traer_fila_row(query_db("select id from t7_ejecucion_cargue where anuo_corte = '".$fecha_ejecucio[0]."' and mes_corte <=".$fecha_ejecucio[1]." order by mes_corte desc"));
	//echo $sel_mes_corte[0]." -".$contratos_aplica."-";
	$contratos_exp = explode(",",str_replace(" ","",str_replace("Bienes","",str_replace("Servicios","",$contratos_aplica))));
	   $cuantos_cont = count($contratos_exp);
	   for($i=0; $i < $cuantos_cont; $i++){
		   if($contratos_exp[$i]!= ""){
			   $ano_contr = ($contratos_exp[$i][1].$contratos_exp[$i][2])+(2000);
			  $sql_ejecucion = "select t2.por_ejecucion from t7_contratos_contrato as t1, t7_ejecucion as t2 where t1.id = t2.id_contrato and t2.id_cargue = ".$sel_mes_corte[0]." and t1.consecutivo = '".$contratos_exp[$i][4].$contratos_exp[$i][5].$contratos_exp[$i][6].$contratos_exp[$i][7]."' and creacion_sistema like '%".$ano_contr."%' and apellido like '%".$contratos_exp[$i][8].$contratos_exp[$i][9]."%'";
			$sel_ejecucion = traer_fila_row(query_db($sql_ejecucion));
			$porcenta_ejecu = $sel_ejecucion[0];
		   }
	   }
   }
   echo $porcenta_ejecu;
   ?></td>
   <td><?
if($porcenta_ejecu != "-"){
   $porcentaje_ejecu_para_calcular = str_replace("%","",str_replace(",",".",$porcenta_ejecu));
 
   if($porcentaje_ejecu_para_calcular > $indicador_porcentaje){
	   echo "Fuera de Tiempo";
	   }else{
		   echo "A Tiempo";
		   }
}else{ echo "-";} 
   ?></td>
   <td><? if($contratista != "" and $contratista != " " and $contratista != "  ") echo $observacion;?></td>
   
   <?
	  }
   ?>
    
  </tr>
  <?
  $numero_contrato="";
  }
  ?>
</table>
</body>
</html>
<?

?>