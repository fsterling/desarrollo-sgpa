<? include("../../librerias/lib/@session.php"); 
	verifica_menu("administracion.html");
	header('Content-Type: text/xml; charset=ISO-8859-1');
	echo '<?xml version="1.0" encoding="ISO-8859-1"?>';	
	$trae_id_insrte = " select SCOPE_IDENTITY() AS [SCOPE_IDENTITY]"; // para optener elid del insert into SQL SERVER
	$id_item_pecc = elimina_comillas(arreglo_recibe_variables($_GET["id_item_pecc"]));
	$id_tipo_proceso_pecc = elimina_comillas(arreglo_recibe_variables($_GET["id_tipo_proceso_pecc"]));
	//SELECCION DE PERMISO E ITEM
	$sel_item = traer_fila_row(query_db("select * from $pi2 where id_item=".$id_item_pecc));
//	
	$id_pecc = $sel_item[1];
	$sel_pecc = traer_fila_row(query_db("select $g10.valor from $pi1, $g1, $g10 where $pi1.id_pecc = ".$sel_item[1]." and $g1.us_id = $pi1.id_us_encargado and $g10.id_pecc = $pi1.id_pecc and $g10.estado=1"));
	
	$edicion_datos_generales_permiso = "NO";
	$es_profesional_designado = verifica_usuario_indicado_solo_si(8,$sel_item[0]);
	if(verifica_permiso_pecc($sel_item[14], $sel_item[0]) == "SI" and $es_profesional_designado == "SI" and $sel_item[14] == 6){
			$edicion_datos_generales_permiso = "SI";
		}
	
	
	

		//FIN SELECCION DE PERMISO E ITEM
		//SELECCION SI LAS FIRMAS SON DE PERMISO O ADJUDICACION
			//selecciona el nivel de servicio
			$sele_que_nivel_servicio = traer_fila_row(query_db("select t2_nivel_servicio_id from $vpeec2 where id_item=".$id_item_pecc));	
			$activa_firmas_de_permiso = "NO";
			$seleccione_tipo_firmas_adj_permiso = traer_fila_row(query_db("select count(*) from $pi5 where t2_nivel_servicio_id = ".$sele_que_nivel_servicio[0]." and t2_nivel_servicio_actividad_id = 7"));
			if($seleccione_tipo_firmas_adj_permiso[0] > 0){//verifica si tiene el rol de firmas en el sistema para el permiso
					$activa_firmas_de_permiso = "SI";
				}
			$seleccione_tipo_firmas_adj_permiso = traer_fila_row(query_db("select count(*) from $pi5 where t2_nivel_servicio_id = ".$sele_que_nivel_servicio[0]." and t2_nivel_servicio_actividad_id = 16"));
			if($seleccione_tipo_firmas_adj_permiso[0] > 0){// verifica si tiene el rol de firmas en el sistema para la adjudicacion
					$activa_firmas_de_adjudicacion = "SI";
				}
			//FIN SELECCION SI LAS FIRMAS SON DE PERMISO O ADJUDICACION
	if($activa_firmas_de_permiso == "SI"){
	$sel_si_ya_se_creo = traer_fila_row(query_db("select count(*) from $pi14 where id_item_pecc = ".$id_item_pecc." and por_sistema = 2 and tipo_adj_permiso = 1"));
	if($sel_si_ya_se_creo[0] == 0){// verifica si hay accion del profesional de C&C
			$sel_para_eliminar = query_db("select * from $pi14 where id_item_pecc=".$id_item_pecc." and tipo_adj_permiso = 1");
			while($elim1 = traer_fila_db($sel_para_eliminar)){// si hay algo creado eliminar las firmas
				$dele_pro_sistem_us = query_db("delete from $pi15 where id_secuencia_solicitud =".$elim1[0]);
				$dele_pro_sistem_us = query_db("delete from $pi16 where id_secuencia_solicitud =".$elim1[0]);		
				}
				$aprobacion_us = "";
			$dele_pro_sistem = query_db("delete from $pi14 where id_item_pecc =".$id_item_pecc." and tipo_adj_permiso = 1");
			
			//super intendentes zona este y valle magdalena
			if($sel_item[5] == 24 or $sel_item[5] == 25){				
				$sel_valor_solicitud = traer_fila_row(query_db("select sum (eq_usd) from v_pecc_n_servicio_2 where id_item = ".$id_item_pecc));
				if($sel_valor_solicitud[0]<=30000){
						$rol_jefe_no_aplica = " and id_rol_permiso not in (9)";						
						$insert = "insert into $pi14 (id_item_pecc, id_rol, orden, estado,por_sistema, tipo_adj_permiso) values (".$id_item_pecc.", 35,7,1,1,1)";
						$sql_ex=query_db($insert.$trae_id_insrte);
				$id_ingreso = id_insert($sql_ex);						
						$sele_pro_sistem_usuarios = query_db("select us_id from v_seg1 where id_premiso = 35 and id_area = ".$sel_item[5]." group by us_id");	
						while($sel_p_sist_us = traer_fila_db($sele_pro_sistem_usuarios)){	
			$insert52 = query_db("insert into $pi15 (id_secuencia_solicitud,id_usuario, estado) values (".$id_ingreso.", ".$sel_p_sist_us[0].",1)");		
				}						
					}
				
				}			
			// finn super intendentes zona este y valle magdalena
			
			
			
			$sele_pro_sistem = query_db("select id_rol_permiso, orden from $vpeec13 where id_item = ".$id_item_pecc.$rol_jefe_no_aplica." group by id_rol_permiso, orden");// selecciona AGL
			while($sel_p_sist = traer_fila_db($sele_pro_sistem)){// recorre el AGL
				$id_ingreso=0;	
				$insert = "insert into $pi14 (id_item_pecc, id_rol, orden, estado,por_sistema, tipo_adj_permiso) values (".$id_item_pecc.", ".$sel_p_sist[0].",".$sel_p_sist[1].",1,1,1)";
				$sql_ex=query_db($insert.$trae_id_insrte);
				$id_ingreso = id_insert($sql_ex);
				//CREA LOS USUARIOS ENCARGADOS
				
				if($sel_p_sist[0] == 31){//SI ES profesional de compras
					$insert52 = query_db("insert into $pi15 (id_secuencia_solicitud,id_usuario, estado) values (".$id_ingreso.", 17967,1)");
					
					}
					
					
				$sele_pro_sistem_usuarios = query_db("select us_id from $vpeec14 where id_item = ".$id_item_pecc." and id_rol_permiso = '".$sel_p_sist[0]."' group by us_id");	
				
				if($sel_p_sist[0] == 15){//SI ES EL ROL GERENTE CREAR, EL GERENTE EN EL ROL
					$insert52 = query_db("insert into $pi15 (id_secuencia_solicitud,id_usuario, estado) values (".$id_ingreso.", ".$sel_item[3].",1)");					
					if($aprobacion_us == ""){
					$sele_gestion_para_aprobacion_usuario = traer_fila_row(query_db("select fecha_real from $pi17 where id_item = ".$id_item_pecc." and t2_nivel_servicio_actividad_id < 6 and estado = 1"));
					$aprobacion_us = $sele_gestion_para_aprobacion_usuario[0];
					
					$sele_usuario_ulti_poen_firme = traer_fila_row(query_db("select id_usua from t2_nivel_servicio_gestiones where id_item = ".$sel_item[0]." and t2_nivel_servicio_actividad_id = 1 and estado = 1 order by t2_gestion desc"));
					
					$insert_aprobacion = query_db("insert into $pi16 (id_secuencia_solicitud,id_us,fecha,aprobado) values (".$id_ingreso.",".$sele_usuario_ulti_poen_firme[0].",'".$aprobacion_us."', 1)");
					
					
					}
					
					}// FIN SI ES EL ROL GERENTE CREAR, EL GERENTE EN EL ROL
				if($sel_p_sist[0] == 8 and $sel_item[23] <> ""){//SI ES EL ROL profesional
					$insert52 = query_db("insert into $pi15 (id_secuencia_solicitud,id_usuario, estado) values (".$id_ingreso.", ".$sel_item[23].",1)");			
					}		
				
				if($sel_p_sist[0] == 42 and $sel_item[23] <> ""){//SI ES EL ROL PREAPROVAR sap
					$insert52 = query_db("insert into $pi15 (id_secuencia_solicitud,id_usuario, estado) values (".$id_ingreso.", ".$sel_item[23].",1)");			
					}		
					
				
					
					if($sel_p_sist[0] == 11 and $sel_item[23] <> ""){//SI ES EL ROL Socios
					$insert52 = query_db("insert into $pi15 (id_secuencia_solicitud,id_usuario, estado) values (".$id_ingreso.", ".$sel_item[23].",1)");			
//					$sele_pro_sistem_usuarios = query_db("select us_id from $v_seg1 where id_premiso = 11");
					}

					if($sel_p_sist[0] == 10){//SI ES EL ROL comite
					$sele_pro_sistem_usuarios = query_db("select us_id from $v_seg1 where id_premiso = 10  group by us_id");
					}
									
					if(($sel_p_sist[0] <> 15 and $sel_p_sist[0] <> 11 and $sel_p_sist[0] <> 31) and ($sel_p_sist[0] <> 8 or $sel_item[23] == "") and $sel_p_sist[0] <> 42){//SI ES EL ROL diferente de gerente y profesional
			
			while($sel_p_sist_us = traer_fila_db($sele_pro_sistem_usuarios)){	
			$insert52 = query_db("insert into $pi15 (id_secuencia_solicitud,id_usuario, estado) values (".$id_ingreso.", ".$sel_p_sist_us[0].",1)");
				
				}
					}
				
			//CREA LOS USUARIOS ENCARGADOS
			}
		}
	}//fin activa firmas de permiso
	if($activa_firmas_de_adjudicacion == "SI"){
	$sel_si_ya_se_creo = traer_fila_row(query_db("select count(*) from $pi14 where id_item_pecc = ".$id_item_pecc." and por_sistema = 2 and tipo_adj_permiso = 2"));
	if($sel_si_ya_se_creo[0] == 0){		
			$sel_para_eliminar = query_db("select * from $pi14 where id_item_pecc=".$id_item_pecc." and tipo_adj_permiso = 2");
			while($elim1 = traer_fila_db($sel_para_eliminar)){
				$dele_pro_sistem_us = query_db("delete from $pi15 where id_secuencia_solicitud =".$elim1[0]);		
				}
			$dele_pro_sistem = query_db("delete from $pi14 where id_item_pecc =".$id_item_pecc." and tipo_adj_permiso = 2");
			$sele_pro_sistem = query_db("select id_rol_permiso, orden from $vpeec13 where id_item = ".$id_item_pecc);	
			while($sel_p_sist = traer_fila_db($sele_pro_sistem)){
				$id_ingreso=0;	
				$insert = "insert into $pi14 (id_item_pecc, id_rol, orden, estado,por_sistema, tipo_adj_permiso) values (".$id_item_pecc.", ".$sel_p_sist[0].",".$sel_p_sist[1].",1,1,2)";
				$sql_ex=query_db($insert.$trae_id_insrte);
				$id_ingreso = id_insert($sql_ex);
				//CREA LOS USUARIOS ENCARGADOS


				if($sel_p_sist[0] == 31){//SI ES profesional de compras
					$insert52 = query_db("insert into $pi15 (id_secuencia_solicitud,id_usuario, estado) values (".$id_ingreso.", 17967,1)");
					
					}
					
				
				$sele_pro_sistem_usuarios = query_db("select us_id from $vpeec14 where id_item = ".$id_item_pecc." and id_rol_permiso = '".$sel_p_sist[0]."'");
	
				if($sel_p_sist[0] == 15){//SI ES EL ROL GERENTE CREAR, EL GERENTE EN EL ROL
					$insert52 = query_db("insert into $pi15 (id_secuencia_solicitud,id_usuario, estado) values (".$id_ingreso.", ".$sel_item[3].",1)");
					
	
			
										
					}// FIN SI ES EL ROL GERENTE CREAR, EL GERENTE EN EL ROL
				if($sel_p_sist[0] == 8 and $sel_item[23] <> ""){//SI ES EL ROL profesional
					$insert52 = query_db("insert into $pi15 (id_secuencia_solicitud,id_usuario, estado) values (".$id_ingreso.", ".$sel_item[23].",1)");			
					}
					
				if($sel_p_sist[0] == 11 and $sel_item[23] <> ""){//SI ES EL ROL Socios
					$insert52 = query_db("insert into $pi15 (id_secuencia_solicitud,id_usuario, estado) values (".$id_ingreso.", ".$sel_item[23].",1)");			
					}
											
					if($sel_p_sist[0] <> 15 and $sel_p_sist[0] <> 11 and $sel_p_sist[0] <> 31 and ($sel_p_sist[0] <> 8 or $sel_item[23] == "")){//SI ES EL ROL diferente de gerente y profesional
			while($sel_p_sist_us = traer_fila_db($sele_pro_sistem_usuarios)){	
			$insert52 = query_db("insert into $pi15 (id_secuencia_solicitud,id_usuario, estado) values (".$id_ingreso.", ".$sel_p_sist_us[0].",1)");
				}
					}
				
			//CREA LOS USUARIOS ENCARGADOS
			}
		}
	}//fin activa firmas de adjudicacion
	if($sel_item[4] <> 1){
		$no_contratos = ", 30";
		}
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>

<title>Documento sin t&iacute;tulo</title>
<link href="../../css/estilo-principal.css" rel="stylesheet" type="text/css" /></head>

<body>

<table width="100%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="2" valign="top"><?=encabezado_item_pecc($id_item_pecc)?></td>
  </tr>
  <tr>
    <td width="77%" valign="top">
    <?
    if($edicion_datos_generales_permiso == "SI"){
	?>
    <table width="100%" border="0" align="center"  class="tabla_lista_resultados">
      <tr>
        <td colspan="2" align="center"  class="fondo_3">Agregar Firma en el Sistema para el Permiso</td>
        </tr>
      
      <tr>
        <td width="51%" align="right">Rol Encargado:</td>
        <td width="49%" align="left"><select name="rol_encarga_permiso" id="rol_encarga_permiso">
          <?=listas($ts2, " estado = 1 and id_modulo = 1 and id_tipo_permiso = 2 and id_premiso in (18,21,16,22,9,17,20, 35".$no_contratos.")",0 ,'nombre', 3);?>
        </select></td>
        </tr>
      <tr>
        <td align="right">Usuarios Posibles para la Firma:</td>
        <td align="left">
          <input type="text" name="usuario_permiso" id="usuario_permiso" onkeypress="selecciona_lista()"/></td>
        </tr>
      <tr>
        <td align="right">Orden de Secuencia:</td>
        <td align="left"><input type="text" name="orden_permiso" id="orden_permiso" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="button" name="button" id="button" value="Agregar Firma en el Sistema para el Permiso" class="boton_grabar" onclick="agrega_aprobacion(1)" /></td>
        </tr>
      
    </table>
<?
	}
	
		  if($sel_item[39] == "SI"){
			  $activa_firmas_de_permiso ="NO";
			  }
    if($activa_firmas_de_permiso=="SI"){
	?>
    <table width="100%" border="0" align="center"  class="tabla_lista_resultados">
        <tr>
          <td colspan="7" align="center"  class="fondo_3">Lista de Firmas en el Sistema Requeridas para el Permiso</td>
        </tr>
        <tr>
          <td width="16%" rowspan="2" align="center" class="fondo_3">Rol Encargado</td>
          <td width="14%" rowspan="2" align="center" class="fondo_3">Usuarios Encargados</td>
          <?
		  $es_aprobador_indicado_muestra_colmuna = "NO";
			if($sel_item[14] < 7){
				$es_aprobador_indicado_muestra_colmuna = "SI";
				}
				
          if($es_aprobador_indicado_muestra_colmuna == "SI"){
		  ?><td width="7%" rowspan="2" align="center" class="fondo_3">Orden de Secuencia</td>
          <?
		  }
		  ?>
          <td colspan="3" align="center" class="fondo_3">Firmas</td>
          <td width="6%" rowspan="2" align="center" class="fondo_3">Acciones</td>
        </tr>
        <tr>
          <td width="22%" align="center" class="fondo_3">Estado Ultima Firma</td>
          <td width="30%" align="center" class="fondo_3">Observaci&oacute;n</td>
          <td width="5%" align="center" class="fondo_3">Adjunto</td>
        </tr>
        <?
			
			
				
       $sel_propuestos_real = query_db("select id_rol, rol,orden from $vpeec15 where id_item_pecc = ".$id_item_pecc." and tipo_adj_permiso = 1 and id_rol not in (10,11) group by id_rol, rol,orden order by orden");
		$cont = 0;
		while($sel_p_real = traer_fila_db($sel_propuestos_real)){
			
			$select_si_tiene_acciones = traer_fila_row(query_db("select count(*) from $vpeec16 where id_rol = ".$sel_p_real[0]." and tipo_adj_permiso = 1 and aprobado=1 and id_item_pecc = ".$id_item_pecc));
			$select_secuencia = traer_fila_row(query_db("select * from $pi14 where id_item_pecc = ".$id_item_pecc." and id_rol = ".$sel_p_real[0]." and estado=1 and tipo_adj_permiso = 1"));

			$edita_permiso = "SI";
			if($select_si_tiene_acciones[0] > 0 or $sel_p_real[0] == 8 or $sel_p_real[0] == 10){
				$edita_permiso = "NO";
				$secuencia_profesional_permiso = $select_secuencia[0];
				}
			
			$sel_real_us_aprueba = traer_fila_row(query_db("select * from $vpeec15 where id_item_pecc = ".$id_item_pecc." and id_rol = ".$sel_p_real[0]." and tipo_adj_permiso = 1 and estado = 1 and us_id = ".$_SESSION["id_us_session"]." and id_rol not in (8,15) order by nombre_administrador"));
			
			$sel_id_apro_ultima = traer_fila_row(query_db("select max(id_aprobacion) from $vpeec16 where id_rol = ".$sel_p_real[0]." and tipo_adj_permiso = 1 and id_item_pecc = ".$id_item_pecc));
			
			$sel_ultima_aprobacion = traer_fila_row(query_db("select * from $vpeec16 where id_aprobacion = ".$sel_id_apro_ultima[0]));
			
			$es_aprobador_indicado_aprueba = "NO";
			if($sel_real_us_aprueba[0]> 0 and $sel_ultima_aprobacion[5] <> 1 and $sel_item[14] == 7){
			$es_aprobador_indicado_aprueba = "SI";
			}
			

if($cont == 0){
		  	$clase= "filas_resultados";
			$cont = 1;
		  }else{
		  	$clase= "";
			$cont = 0;
		  }
		  
		  if($sel_p_real[0] == 8 and ($sel_item[4] == 2 or $sel_item[4] == 3 or $sel_item[4] == 4)){
			  $nombre_rol_im = "Comprador";
			  }else{
				  $nombre_rol_im = $sel_p_real[1];
				  }
		
		if($sel_p_real[0] == 15){
			  $nombre_rol_im = "Gerente del Item";
			  }
				  
		  
		?>
        <tr class="<?=$clase?>">


          <td align="center"><?=$nombre_rol_im?></td>
          <td align="left"><?
		  
		  
		  if($sel_p_real[0] == 15){
			  echo "*.".traer_nombre_muestra($sel_item[3], $g1,"nombre_administrador","us_id");
			  }else{
				  
				  
          $sel_real_us = query_db("select * from $vpeec15 where id_item_pecc = ".$id_item_pecc." and id_rol = ".$sel_p_real[0]." and tipo_adj_permiso = 1 and estado = 1 order by nombre_administrador");
				while($sel_re_us = traer_fila_db($sel_real_us)){
					if($sel_re_us[4] <> ""){
					if($edita_permiso == "SI" and $edicion_datos_generales_permiso == "SI"){
					echo "*.".$sel_re_us[4]." <img src='../imagenes/botones/eliminada_temporal.gif' width='10' height='10' onclick='elimina_usuario_firma(".$sel_re_us[7].")'/><br />";
					}else{
						echo "*.".$sel_re_us[4]." <br />";
						}
					}
						
				}
				
				}//fin si es el gerente
		  ?></td>
       <?
          if($es_aprobador_indicado_muestra_colmuna == "SI"){
		  ?>
          <td align="center">
          <?
          if($edicion_datos_generales_permiso == "SI" and $edita_permiso == "SI"){
		  ?>
          <input type="text"  name="orden_<?=$sel_p_real[2]?>" id="orden_<?=$sel_p_real[2]?>" value="<?=$sel_p_real[2]?>" onchange="cambia_orden_firmas(<?=$select_secuencia[0]?>,this.value,1)" />
          <?
		  }else{
			  echo $sel_p_real[2];
			  }
		  ?>
          </td>
          <?
		  }
          	
		  ?>
          <td align="left">
		  
          <?
          if($es_aprobador_indicado_aprueba == "SI"){
		  ?>
          <select name="accion_aprueba_<?=$sel_p_real[0]?>" id="accion_aprueba_<?=$sel_p_real[0]?>">
          <option value="1">Firmar</option>
          <option value="2">Devolver al Profesional</option>
          <option value="3">Rechazado</option>
          </select>
		  <?
		  }else{ 
		  	if($sel_ultima_aprobacion[5] == 1){
					echo "Usuario: ".$sel_ultima_aprobacion[6]."<br />Fecha ".$sel_ultima_aprobacion[4]."<br />Estado: Firmado";
				}
			if($sel_ultima_aprobacion[5] == 2){
					echo "Usuario: ".$sel_ultima_aprobacion[6]."<br />Fecha ".$sel_ultima_aprobacion[4]."<br />Devuelto al Profesional";
				}
			if($sel_ultima_aprobacion[5] == 3){
					echo "Usuario: ".$sel_ultima_aprobacion[6]."<br />Fecha ".$sel_ultima_aprobacion[4]."<br />Rechazado";
				}
				
			if($sel_ultima_aprobacion[5] <> 1 and $sel_ultima_aprobacion[5] <> 2 and $sel_ultima_aprobacion[5] <> 3){
					echo "Pendiente";
				}
		  }
		  ?></td>
          <td align="center"><?
          if($es_aprobador_indicado_aprueba == "SI"){
		  ?>
          <textarea name="observa_<?=$sel_p_real[0]?>" cols="10" rows="2" id="observa_<?=$sel_p_real[0]?>"></textarea>
    
          <?
		  }else{
			  echo $sel_ultima_aprobacion[11];
			  }
		  ?></td>
          <td align="center"><?
          if($es_aprobador_indicado_aprueba == "SI"){
		  ?>
            <input type="file" name="adjunto_<?=$sel_p_real[0]?>" id="adjunto_<?=$sel_p_real[0]?>" />
            <input type="file" name="adjunto2_<?=$sel_p_real[0]?>" id="adjunto2_<?=$sel_p_real[0]?>" />
            <?
		  }else{
			  	if($sel_ultima_aprobacion[9] != ""){
		  ?>
          
          
                <a href="../enterproc/librerias/php/descarga_documentos_generales.php?n2=<?=$sel_ultima_aprobacion[9]?>&n1=<?=$sel_ultima_aprobacion[2]?>&n3=4" target="grp">
                  <img src="../imagenes/mime/<?=saca_extencion_archivo($sel_ultima_aprobacion[9])?>.gif" width="16" height="16" />
                  </a>
          <?
				}
				if($sel_ultima_aprobacion[10] != ""){
		  ?>
          
         
                <a href="../enterproc/librerias/php/descarga_documentos_generales.php?n2=<?=$sel_ultima_aprobacion[10]?>&n1=<?=$sel_ultima_aprobacion[2]?>&n3=5" target="grp">
                  <img src="../imagenes/mime/<?=saca_extencion_archivo($sel_ultima_aprobacion[10])?>.gif" width="16" height="16" />
                  </a>
          <?
				}
		  }
		  ?>
          	</td>
          
          <td align="center">
          <?
		  
          if($es_aprobador_indicado_aprueba == "SI"){
			  ?><input type="button" name="sdfd" value="Firmar" onclick="aprueba_firma(<?=$sel_p_real[0]?>,document.principal.accion_aprueba_<?=$sel_p_real[0]?>.value)" /><?
		  }else{
		  
          if($edita_permiso == "SI" and $edicion_datos_generales_permiso == "SI"  and $sel_p_real[0] <> 9 and $sel_p_real[0] <> 20 and $sel_p_real[0] <> 34 and $sel_p_real[0] <> 35){
		  ?>
          <img src="../imagenes/botones/eliminada_temporal.gif" alt="" width="16" height="16" onclick="elimina_firma_completa(<?=$select_secuencia[0]?>,1)" />
          <?
		  }
		  }
		  ?>
          
          
          </td>
        </tr>
        <?
		
        }
		?>
        <?

       $sel_p_real = traer_fila_db(query_db("select id_rol, rol,orden from $vpeec15 where id_item_pecc = ".$id_item_pecc." and tipo_adj_permiso = 1 and id_rol = 10 group by id_rol, rol,orden order by orden"));
			if($sel_p_real[0]>0){
			$select_si_tiene_acciones = traer_fila_row(query_db("select count(*) from $vpeec16 where id_rol = ".$sel_p_real[0]." and tipo_adj_permiso = 1 and aprobado=1 and id_item_pecc = ".$id_item_pecc));
			$select_secuencia = traer_fila_row(query_db("select * from $pi14 where id_item_pecc = ".$id_item_pecc." and id_rol = ".$sel_p_real[0]." and tipo_adj_permiso = 1"));

			$edita_permiso = "SI";
			if($select_si_tiene_acciones[0] > 0 ){
				$edita_permiso = "NO";
				$secuencia_profesional_permiso = $select_secuencia[0];
				}

		?>
        <tr>


          <td align="center"><?=$sel_p_real[1]?></td>
          <td align="left"><?
          $sel_real_us = query_db("select nombre_administrador from $v_seg1 where id_premiso = 10 group by nombre_administrador");
				while($sel_re_us = traer_fila_db($sel_real_us)){
					
					echo "*.".$sel_re_us[0]." <br />";
					
						
				}
		  ?></td>
          <?
          if($es_aprobador_indicado_muestra_colmuna == "SI"){
		  ?><td align="center">
          
          </td><? } ?>
          <?
          	$sel_id_apro_ultima = traer_fila_row(query_db("select max(id_aprobacion) from $vpeec16 where id_rol = ".$sel_p_real[0]." and tipo_adj_permiso = 1 and id_item_pecc = ".$id_item_pecc));
			
			$sel_ultima_aprobacion = traer_fila_row(query_db("select * from $vpeec16 where id_aprobacion = ".$sel_id_apro_ultima[0]));
		  ?>
          <td align="left"><? 
		  	if($sel_ultima_aprobacion[5] == 1){
					echo "Usuario: ".$sel_ultima_aprobacion[6]."<br />Fecha ".$sel_ultima_aprobacion[4]."<br />Estado: Firmado";
				}
			if($sel_ultima_aprobacion[5] == 2){
					echo "Usuario: ".$sel_ultima_aprobacion[6]."<br />Fecha ".$sel_ultima_aprobacion[4]."<br />Rechazado";
				}
			if($sel_ultima_aprobacion[5] <> 1 and $sel_ultima_aprobacion[5] <> 2){
					echo "Pendiente";
				}
		  ?></td>
          <td align="center"><?=$sel_ultima_aprobacion[11];?></td>
          <td align="center">&nbsp;</td>
          
          <td align="center">
          
          </td>
        </tr>
        <?
	}
       $sel_p_real = traer_fila_db(query_db("select id_rol, rol,orden from $vpeec15 where id_item_pecc = ".$id_item_pecc." and tipo_adj_permiso = 1 and id_rol = 11 group by id_rol, rol,orden order by orden"));
			if($sel_p_real[0]>0){
			$select_si_tiene_acciones = traer_fila_row(query_db("select count(*) from $vpeec16 where id_rol = ".$sel_p_real[0]." and tipo_adj_permiso = 1 and aprobado=1 and id_item_pecc = ".$id_item_pecc));
			$select_secuencia = traer_fila_row(query_db("select * from $pi14 where id_item_pecc = ".$id_item_pecc." and id_rol = ".$sel_p_real[0]." and tipo_adj_permiso = 1"));



$selecciona_si_es_usuario_de_socios = traer_fila_row(query_db("select count(*) from $vpeec15 where id_item_pecc = ".$id_item_pecc." and id_rol = 11 and tipo_adj_permiso = 1 and estado = 1 and id_secuencia_solicitud = ".$select_secuencia[0]." and us_id = ".$_SESSION["id_us_session"].""));

			$es_aprobador_indicado_aprueba = "NO";
			if($selecciona_si_es_usuario_de_socios[0] > 0 and $sel_item[14] == 9){
			$es_aprobador_indicado_aprueba = "SI";
				}


		?>
        <tr class="filas_resultados">


          <td align="center"><?=$sel_p_real[1]?></td>
          <td align="left"><?
          $sel_real_us = query_db("select * from $vpeec15 where id_item_pecc = ".$id_item_pecc." and id_rol = 11 and tipo_adj_permiso = 1 and estado = 1 order by nombre_administrador");
				while($sel_re_us = traer_fila_db($sel_real_us)){
					if($sel_re_us[4] <> ""){
					
						echo "*.".$sel_re_us[4]." <br />";
						}
					
						
				}
		  ?></td>
          <?
          if($es_aprobador_indicado_muestra_colmuna == "SI"){
		  ?>
          <td align="left">
          </td>
          <?
		  }
		  ?>
          <td align="left"> <?
          	$sel_id_apro_ultima = traer_fila_row(query_db("select max(id_aprobacion) from $vpeec16 where id_rol = ".$sel_p_real[0]." and tipo_adj_permiso = 1 and id_item_pecc = ".$id_item_pecc));
			
			$sel_ultima_aprobacion = traer_fila_row(query_db("select * from $vpeec16 where id_aprobacion = ".$sel_id_apro_ultima[0]));
		  ?>
		  <?
          if($es_aprobador_indicado_aprueba == "SI"){
		  ?>
          <select name="accion_aprueba_<?=$sel_p_real[0]?>" id="accion_aprueba_<?=$sel_p_real[0]?>">
          <option value="1">Firmar</option>
          <option value="2">Rechazar</option>
          </select>
		  <?
		  
		  if($sel_item[14] == 9){
	?>
		<strong onclick="abrir_ventana('../aplicaciones/comite/pecc/impresion-socios-edicion-item-pecc.php?id_item_pecc=<?=$sel_item[0]?>&id_tipo_proceso_pecc=<?=$id_tipo_proceso_pecc?>&conse_div=<?=$conse_div?>&permiso_o_adjudica=1')" class="titulo_calendario_real_bien"> Exportar presentaci&oacute;n a socios <img src="../imagenes/mime/doc.gif" width="20" height="20"></strong>
		<?
	}

	
		  }else{ 
		  	if($sel_ultima_aprobacion[5] == 1){
					echo "Usuario: ".$sel_ultima_aprobacion[6]."<br />Fecha ".$sel_ultima_aprobacion[4]."<br />Estado: Firmado";
				}
			if($sel_ultima_aprobacion[5] == 2){
					echo "Usuario: ".$sel_ultima_aprobacion[6]."<br />Fecha ".$sel_ultima_aprobacion[4]."<br />Rechazado";
				}
			if($sel_ultima_aprobacion[5] <> 1 and $sel_ultima_aprobacion[5] <> 2){
					echo "Pendiente";
				}
		  }
		  ?></td>
          <td align="center"><?
          if($es_aprobador_indicado_aprueba == "SI"){
		  ?>
          <textarea name="observa_<?=$sel_p_real[0]?>" cols="10" rows="2" id="observa_<?=$sel_p_real[0]?>"></textarea>
    
          <?
		  }else{
			  echo $sel_ultima_aprobacion[11];
			  }
		  ?></td>
          
          <td align="center"><?
          if($es_aprobador_indicado_aprueba == "SI"){
		  ?>
            <input type="file" name="adjunto_<?=$sel_p_real[0]?>" id="adjunto_<?=$sel_p_real[0]?>" />
            <input type="file" name="adjunto2_<?=$sel_p_real[0]?>" id="adjunto2_<?=$sel_p_real[0]?>" />
            <?
		  }else{
			  	if($sel_ultima_aprobacion[9] != ""){
		  ?>
          
                <a href="../enterproc/librerias/php/descarga_documentos_generales.php?n2=<?=$sel_ultima_aprobacion[9]?>&n1=<?=$sel_ultima_aprobacion[2]?>&n3=4" target="grp">
                  <img src="../imagenes/mime/<?=saca_extencion_archivo($sel_ultima_aprobacion[9])?>.gif" width="16" height="16" />
                  </a>
          <?
				}
				if($sel_ultima_aprobacion[10] != ""){
		  ?>
          
                <a href="../enterproc/librerias/php/descarga_documentos_generales.php?n2=<?=$sel_ultima_aprobacion[10]?>&n1=<?=$sel_ultima_aprobacion[2]?>&n3=5" target="grp">
                  <img src="../imagenes/mime/<?=saca_extencion_archivo($sel_ultima_aprobacion[10])?>.gif" width="16" height="16" />
                  </a>
          <?
				}
		  }
		  ?>
          	
          
          </td>
          <td><?
		  
          if($es_aprobador_indicado_aprueba == "SI"){
			  ?><input type="button" name="sdfd" value="Firmar" onclick="aprueba_firma(11,document.principal.accion_aprueba_<?=$sel_p_real[0]?>.value)" /><?
		     
		  }
		  ?></td>
        </tr>
        <?
			}
		?>
      </table><?
	
	  ?>
      <?
	}
	if($edicion_datos_generales_permiso == "SI"){
	  ?>
      <table width="100%" border="0" align="center"  class="tabla_lista_resultados">
        <tr>
          <td width="12%" align="right">Observaci&oacute;n de la Devoluci&oacute;n:</td>
          <td width="42%" align="center"><textarea name="observa_atras" rows="5" id="observa_atras"></textarea></td>
          <td align="center">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="center"><input type="button" name="button3" id="button3" value="Devolver al Gerente del Contrato" class="boton_grabar_cancelar" onclick="devolver_item_a_gerente_contrato()" /></td>
          <td width="46%" align="center"><input type="button" name="button2" id="button2" value="Terminar el Completamiento y Firmar en el Sistema" class="boton_grabar" onclick="siguiente_nivel_agl('Esta Seguro de Enviar al Siguiente Nivel de Servicio?',<?=$secuencia_profesional_permiso?>)" /></td>
        </tr>
      </table>
      <?
	}
	  ?>
    <br /></td>
    <td width="23%" valign="top"><?=carga_sub_menu_peec($id_item_pecc,$id_tipo_proceso_pecc)?></td>
  </tr>
  <tr>
    <td colspan="2" valign="top" id="carga_acciones_permitidas">&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="id_elimian_firma" id="id_elimian_firma" value="" />
<input type="hidden" name="tipo_adj_permiso" id="tipo_adj_permiso" />
<input type="hidden" name="id_item_pecc" id="id_item_pecc" value="<?=$id_item_pecc?>" />
<input type="hidden" name="id_tipo_proceso_pecc" id="id_tipo_proceso_pecc" value="<?=$id_tipo_proceso_pecc?>" />
<input type="hidden" name="id_secuencia" id="id_secuencia" value="" />
<input type="hidden" name="orden_edita_secua" id="orden_edita_secua" value="" />
<input type="hidden" name="id_rol_aprueba" id="id_rol_aprueba" value="" />
<input type="hidden" name="estado_item_peec" id="estado_item_peec" value="<?=$sel_item[14]?>" />
</body>
</html>
