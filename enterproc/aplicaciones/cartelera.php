<? include("../librerias/lib/@session.php");
	header('Content-Type: text/xml; charset=ISO-8859-1');
    echo '<?xml version="1.0" encoding="ISO-8859-1"?>';	
    verifica_menu("principal.html");
	$id_invitacion = $id_invitacion_pasa;
	
	$busca_procesos = "select * from $t5 where pro1_id = $id_invitacion";
	$sql_e=traer_fila_row(query_db($busca_procesos));

	$busca_confirmacion = traer_fila_row(query_db("select * from $t9 where  pro1_id = $id_invitacion and pv_id = ".$_SESSION["id_proveedor"]." order by fecha desc"));

?>



<html>
<head>
<title>Datos Cliente</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../css/principal.css" rel="stylesheet" type="text/css">
</head>
<body >
  
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td class="titulos_procesos">PROCESOS DE CONTRATACION</td>
  </tr>
</table>

<fieldset style="width:98%">
			<legend>Informaci&oacute;n General del Proceso</legend>
<table width="95%" border="0" cellspacing="4" cellpadding="4">
  <tr>
    <td colspan="4"></td>
  </tr>
  <tr>
    <td width="30%" height="26"><strong>Consecutivo del proceso:</strong></td>
    <td width="26%"><div align="left"><?=$sql_e[22];?></div></td>
    <td width="22%"><strong>Tipo de proceso:</strong></td>
    <td width="22%"><div align="left"><?=listas_sin_select($tp2,$sql_e[2],1);?>
    </div>    </td>
  </tr>
  <tr>
    <td height="26"><strong>Detalle y cantidad del objeto a contratar:</strong></td>
    <td colspan="3"><div align="left">
      <?=$sql_e[12];?>
      </textarea>
    </div></td>
  </tr>
</table>
<table width="95%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><label>
      <input name="button6" type="button" class="cancelar" id="button6" value="Volver al proceso" onClick="ajax_carga('../aplicaciones/crea_proceso.php?id_p=<?=$id_invitacion_pasa;?>','contenidos')">
      <input name="button7" type="button" class="calcular" id="button7" value="     Exportar cartelera a excel" onClick="window.parent.location.href='../aplicaciones/exporta_cartelera.php?id_invitacion_pasa=<?=$id_invitacion;?>'">
    </label></td>
  </tr>
</table>
<br>
</fieldset>

<br>
<fieldset style="width:98%">
			<legend>Historico de preguntas</legend>
            <table width="98%" border="0" cellpadding="2" cellspacing="2" class="tabla_borde_azul_fondo_blanco">
              <tr>
                <td width="23%" class="titulo_tabla_azul_sin_bordes">Solicitante</td>
                <td width="10%" class="titulo_tabla_azul_sin_bordes">Fecha de pregunta</td>
                <td width="44%" class="titulo_tabla_azul_sin_bordes">Pregunta</td>
                <td width="6%" class="titulo_tabla_azul_sin_bordes">Tipo</td>
                <td width="8%" class="titulo_tabla_azul_sin_bordes">Anexo</td>
                <td width="9%" class="titulo_tabla_azul_sin_bordes">Admin</td>
              </tr>
              
              <?
			  	$sele_car="select * from $t15 where pro1_id = $id_invitacion and tipo_aclaracio  = 1  order by fecha_pregunta desc";
				$sql_ex_c=query_db($sele_car);
				while($ls_c=traer_fila_row($sql_ex_c)){
		if($num_fila_gene%2==0)
				$class_g="campos_blancos_listas";
			else
				$class_g="campos_gris_listas";
				
				if($ls_c[7]==2) $solicitante = "HOCOL SA";
				else{
					$pv_id_pr_p = explode("|",$ls_c[2]);
					$busca_proveedor = traer_fila_row(query_db("select pv_id, razon_social from pv_proveedores where pv_id = $pv_id_pr_p[0]"));
					$solicitante= $busca_proveedor[1];
				}

				if($ls_c[10]==1) { $tipo_soli = "Economico";  }
				elseif($ls_c[10]==2) $tipo_soli = "Tecnico";
				elseif($ls_c[10]==3) $tipo_soli = "Lista de precios";				
				elseif($ls_c[10]==4) $tipo_soli = "Todas";								
				$ext="";
				$ext=extencion_archivos($ls_c[11]);

  ?>
      <tr class="<?=$class_g;?>">
        <td><?=$solicitante;?></td>
                <td><div align="center"><?=fecha_for_hora($ls_c[3]);?>
                </div></td>
                <td><div align="left"><?=$ls_c[4];?></td>
                <td><?=$tipo_soli;?></td>
                 <td><? if($ext!=""){ ?><img src="../imagenes/mime/<?=$ext;?>.gif" onClick="window.parent.location.href='../librerias/php/descarga_documentos_cartelera_pregunta.php?n1=<?=$ls_c[0];?>&n2=<?=$ls_c[11];?>'" ><? } ?></td>
                <td>
                  <input name="button" type="button" class="buscar" id="button" onClick="ver_respuestas('div_for_<?=$ls_c[0];?>')" value="Responder  ">				            </td>
              </tr>
			   
              <tr>
                <td colspan="6" id="div_for_<?=$ls_c[0];?>" style="display:none">
                <table width="95%" border="0" align="right" cellpadding="2" cellspacing="2" class="tabla_borde_azul_fondo_blanco">
                  <tr class="<?=$class;?>">
                    <td>&nbsp;</td>
                    <td class="columna_subtitulo_resultados"><strong>Fecha de envio</strong></td>
                    <td class="columna_subtitulo_resultados"><strong>Detalle del comunicado / pregunta</strong></td>
                    <td class="columna_subtitulo_resultados"><strong>Anexo</strong></td>
                  </tr>
                   <?
			  	$sele_car_foro="select pro8_id ,pro7_id,tipo_preg_respuesta ,us_id ,pv_id ,fecha_foro ,foro ,publica,if(publica=0,'Privada','Publica'),anexo  from $t16 where pro7_id = $ls_c[0]  order by fecha_foro  desc";
				$sql_ex_c_foro=query_db($sele_car_foro);
				while($ls_c_f=traer_fila_row($sql_ex_c_foro)){
				
				
		
								
									
				if($ls_c_f[4]==0) { $imagen = "respuesta_f.png"; $solicitante_foro = "GTEC"; }
				else  {$imagen =  "pregunta_f.png"; 
									
					$busca_proveedor = traer_fila_row(query_db("select pv_id, razon_social from pv_proveedores where pv_id = $ls_c_f[4]"));
					$solicitante_foro= $busca_proveedor[1];

				 }
				
		if($num_fila%2==0)
				$class="campos_blancos_listas";
			else
				$class="campos_gris_listas";
				
				$ext="";
				$ext=extencion_archivos($ls_c_f[9]);

  ?>
          
                  <tr class="<?=$class;?>">
                    <td width="4%"><div align="right"><img src="../imagenes/botones/<?=$imagen;?>" width="24" height="24"></div></td>
                    <td width="18%">
                      <div align="center">
                        <?=fecha_for_hora($ls_c_f[5]);?>
                      </div></td>
                    <td width="79%"><div align="left">
                      <?=$ls_c_f[6];?>
                    </div></td>
                    <td width="1%"><? if($ext!=""){ ?><img src="../imagenes/mime/<?=$ext;?>.gif" onClick="window.parent.location.href='../librerias/php/descarga_documentos_cartelera.php?n1=<?=$ls_c_f[0];?>&n2=<?=$ls_c_f[9];?>'" ><? } ?></td>
                  </tr>
                  <? $num_fila++;} ?>
                   
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2"><strong>&nbsp;&nbsp;
                    </strong>
                    <div align="right"><strong>Nueva respuesta:</strong></div></td>
                    <td colspan="2">
                      
                      <div align="left">
                      <textarea name="p_foro_<?=$ls_c[0];?>" id="p_foro_<?=$ls_c[0];?>" cols="80" rows="2"></textarea>
                      <br>
                      </div>
                      <label>
                      <div align="right"></div>
                    </label>                    </td>
                  </tr>
                  <tr>
                    <td colspan="2"><div align="right"><strong>Anexo:</strong></div></td>
                    <td colspan="2"><input type="file" name="anexo_re_<?=$ls_c[0];?>" id="anexo_re_<?=$ls_c[0];?>"></td>
                  </tr>
                 
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><div align="center">
                      
                      <input name="button2" type="button" class="guardar" id="button2" value="Responder aclaraci&oacute;n" onClick="crea_pregunta_general_cartelera_foro(<?=$ls_c[0];?>, document.principal.p_foro_<?=$ls_c[0];?>)"> 
                      &nbsp;
                      <input name="button3" type="button" class="cancelar" id="button3" value="Cerrar respuestas" onClick="oculat_respuestas('div_for_<?=$ls_c[0];?>')">
                      
                    </div></td>
                    <td>&nbsp;</td>
                  </tr>
                </table>                </td>
              </tr>
				  <? 
				  
				 $num_fila_gene++; } ?>           
            </table>
</fieldset>            

<input type="hidden" name="id_invitacion" value="<?=$id_invitacion_pasa;?>">
<input type="hidden" name="id_elimina">
<input type="hidden" name="ocu_re">

</body>
</html>
