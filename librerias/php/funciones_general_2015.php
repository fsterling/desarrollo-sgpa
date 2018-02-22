	<?php

/***************************************************/
/**** Enternova SAS - 2015                     *****/
/**** funciones_general_2015.php               *****/
/**** Segundo Archivo de funciones generales   *****/
/**** Este archivo se incluira como libreria   *****/
/**** general                                  *****/
/**** Archivo principal: funciones_general.php *****/
/***************************************************/


/*Variable del valor maximo anual para los servicios menores*/
$_SESSION["valor_maximo_ser_menor"] = 24999;
/*Variable del valor maximo anual para los servicios menores*/

function valor_contrato_puntual($id_contrato_funcion){
	
	$sel_rol_usu = traer_fila_row(query_db("select count(*) from tseg11_roles_general where id_usuario in (".$_SESSION["usuarios_con_reemplazo"].") and id_rol_general in (21, 24)"));
	$alert_validacion_valor_contra="";
	
$sel_tipo_contrato = traer_fila_row(query_db("select t1_tipo_documento_id from t7_contratos_contrato where id = ".$id_contrato_funcion));
if($sel_tipo_contrato[0]!= 2 and $sel_rol_usu[0]>0){
	
//	$query_reporte_validacion = traer_fila_row(query_db("select valor_usd_solicitud,valor_cop_solicitud, manual_usd_en_contrato, manual_cop_en_contrato from reporte_juan_david where id =".$id_contrato_funcion." "));
	
	$query_reporte_validacion = traer_fila_row(query_db("select SUM(isnull(valor_usd,0) + isnull(usd_otrosi,0)) as usd, SUM(isnull(valor_cop,0) + isnull(cop_otrosi,0)) as cop from v_reporte_valor_contrato_puntual where id =".$id_contrato_funcion." "));
	
	
	$total_aprobaciones = $query_reporte_validacion[0]+($query_reporte_validacion[1]/3000); 
	$total_modulo_contratos = $query_reporte_validacion[2]+($query_reporte_validacion[3]/3000); 
	if($total_aprobaciones != $total_modulo_contratos){
		$alert_validacion_valor_contra="ATENCION: El valor del contrato no coincide con el valor de las aprobaciones";
	}
}
		/*$query_reporte_validacion = traer_fila_row(query_db("select SUM(valor_usd + usd_otrosi) as usd, SUM(valor_cop + cop_otrosi) as cop, manual_usd_en_contrato, manual_cop_en_contrato from v_reporte_valor_contrato_puntual where id =".$id_contrato_funcion." group by manual_usd_en_contrato, manual_cop_en_contrato"));
$total_aprobaciones = $query_reporte_validacion[0]+($query_reporte_validacion[1]/3000); 
$total_modulo_contratos = $query_reporte_validacion[2]+($query_reporte_validacion[3]/3000); 
if($total_aprobaciones != $total_modulo_contratos){
		
	}*/
	return $alert_validacion_valor_contra;

	}
	
function funcion_duplicar_solicitud($tipo, $original, $destino){
	global $pi2;
	$s_i_orig = traer_fila_row(query_db("select id_pecc, t2_nivel_servicio_id, id_us, t1_tipo_contratacion_id, t1_area_id, t1_tipo_proceso_id, CAST(objeto_solicitud AS TEXT), CAST(objeto_contrato AS TEXT), CAST(alcance AS TEXT), CAST(proveedores_sugeridos AS TEXT), CAST(justificacion AS TEXT), CAST(recomendacion AS TEXT), t1_tipo_otro_si_id, t2_pecc_proceso_id, contrato_id, fecha_creacion, id_us_profesional_asignado, aprobacion_comite_adicional, dondeo_adicional, id_item_peec_aplica, aprobado, esta_en_e_procurement,tiempos_estandar, congelado, CAST(ob_contrato_adjudica AS TEXT), CAST(alcance_adjudica AS TEXT), CAST(justificacion_adjudica AS TEXT), CAST(recomendacion_adjudica AS TEXT), CAST(ob_solicitud_adjudica AS TEXT), id_us_preparador, num_solped, cargo_contable, de_historico, destino_ots, duracion_ots, id_gerente_ot, id_solicitud_relacionada, solicitud_rechazada, solicitud_desierta, CAST(justificacion_tecnica AS TEXT), CAST(justificacion_tecnica_ad AS TEXT), CAST(criterios_evaluacion AS TEXT), convirte_marco, conflicto_intereses, requiere_socios_adicional, id_proveedor_relacionado, CAST(justificacion_presupuesto AS TEXT), CAST(antecedentes_permiso AS TEXT), CAST(antecedentes_adjudicacion AS TEXT), origen_pecc, revision1_conflicto_intereces, revision2_conflicto_intereces, req_contra_mano_obra_local, req_contra_serv_bien_local, req_crear_otro_si, req_contra_mano_obra_local_ad, req_contra_serv_bien_local_ad, par_tecnico, gerente_contrato, par_tecnico_ad, gerente_contrato_ad, CAST(equipo_negociador AS TEXT), estado, vencimiento_contrato, pecc_linea,pecc_modificado, pecc_modificado_id_solicitud_aprobacion, pecc_modificado_observacion, id_urna,numero_urna, categoria_requiere_urna, tiene_reajuste, tiene_retencion, tiene_reembolsable, como_reembolsable from $pi2 where id_item=".$original));
	//$sel_item_destino = traer_fila_row(query_db("select * from $pi2 where id_item=".$destino));
	
	if($tipo==15){//si es modificacion
			if($s_i_orig[5] == 5){$estado_resultado = 31; $valor_permiso_ad = "1";}
			if($s_i_orig[5] == 6){$estado_resultado = 14; $valor_permiso_ad = "2";}
			if(($s_i_orig[5] == 1 or $s_i_orig[5] == 2) and $s_i_orig[62] <=14){$estado_resultado = 31;  $valor_permiso_ad = "1";}
			if(($s_i_orig[5] == 1 or $s_i_orig[5] == 2) and $s_i_orig[62] > 14){$estado_resultado = 14;  $valor_permiso_ad = "1,2";}
			$update = query_db("update t2_item_pecc set estado = ".$estado_resultado.", es_modificacion = 1 where id_item = ".$destino);
			}//fin si es modificacion
			
			
if($s_i_orig[62]>=20 and ($s_i_orig[5] == 1 or $s_i_orig[5] == 2 or $s_i_orig[5] == 3 or $s_i_orig[5] == 6)){//si es una solicitud que genero contrato

	$sel_gerente = traer_fila_row(query_db('select gerente from t7_contratos_contrato where id_item ='.$original));
	$sel_areas = traer_fila_row(query_db('select t1_area.t1_area_id from tseg3_usuario_areas, t1_area where tseg3_usuario_areas.id_area = t1_area.t1_area_id and  tseg3_usuario_areas.id_usuario ='.$sel_gerente[0].' and tseg3_usuario_areas.estado =1 and t1_area.estado=1'));
	
	if($sel_gerente[0]>0) $s_i_orig[2]=$sel_gerente[0];//asigna gerente de la solicitud
	if($sel_areas[0]>0) $s_i_orig[4]=$sel_areas[0];// asigna area del geretne de contrato
	}


	$sel_profesional = traer_fila_row(query_db('select id_us_profesional, id_us_prof_compras_corp from tseg10_usuarios_profesional where id_us='.$s_i_orig[2].' and id_area='.$s_i_orig[4].''));

	if($s_i_orig[3]==1){
		if($sel_profesional[0]>0){
		$s_i_orig[16]=$sel_profesional[0];//asigna profesional
		}
	}else{
		if($sel_profesional[1]>0){
		$s_i_orig[16]=$sel_profesional[1];// asigna comprador
		}
	}
	
	if(verifica_usuario_si_tiene_el_permiso(8) == "SI"){//si el que esta creando en un profesional de abastecimiento 
			$s_i_orig[16]=$_SESSION["id_us_session"];// si el que esta creando es un profesional o comprador asigna al usuario de la session
		}
		
$actualiza_tabla_principal = query_db("update t2_item_pecc set id_pecc='".$s_i_orig[0]."', t2_nivel_servicio_id='".$s_i_orig[1]."', id_us='".$s_i_orig[2]."', t1_tipo_contratacion_id='".$s_i_orig[3]."', t1_area_id='".$s_i_orig[4]."', t1_tipo_proceso_id='".$s_i_orig[5]."', objeto_solicitud='".$s_i_orig[6]."', objeto_contrato='".$s_i_orig[7]."', alcance='".$s_i_orig[8]."', proveedores_sugeridos='".$s_i_orig[9]."', justificacion='".$s_i_orig[10]."', recomendacion='".$s_i_orig[11]."', t1_tipo_otro_si_id='".$s_i_orig[12]."', t2_pecc_proceso_id='".$s_i_orig[13]."', contrato_id='".$s_i_orig[14]."',  id_us_profesional_asignado='".$s_i_orig[16]."', aprobacion_comite_adicional='".$s_i_orig[17]."', dondeo_adicional='".$s_i_orig[18]."', id_item_peec_aplica='".$s_i_orig[19]."', aprobado='".$s_i_orig[20]."', esta_en_e_procurement='".$s_i_orig[21]."', tiempos_estandar='".$s_i_orig[22]."', congelado='".$s_i_orig[23]."', ob_contrato_adjudica='".$s_i_orig[24]."', alcance_adjudica='".$s_i_orig[25]."', justificacion_adjudica='".$s_i_orig[26]."', recomendacion_adjudica='".$s_i_orig[27]."', ob_solicitud_adjudica='".$s_i_orig[28]."', id_us_preparador='".$_SESSION["id_us_session"]."', num_solped='".$s_i_orig[30]."', cargo_contable='".$s_i_orig[31]."', destino_ots='".$s_i_orig[33]."', duracion_ots='".$s_i_orig[34]."', id_gerente_ot='".$s_i_orig[35]."', justificacion_tecnica='".$s_i_orig[39]."', justificacion_tecnica_ad='".$s_i_orig[40]."', criterios_evaluacion='".$s_i_orig[41]."', convirte_marco='".$s_i_orig[42]."', conflicto_intereses='".$s_i_orig[43]."', requiere_socios_adicional='".$s_i_orig[44]."', id_proveedor_relacionado='".$s_i_orig[45]."', justificacion_presupuesto='".$s_i_orig[46]."', antecedentes_permiso='".$s_i_orig[47]."', antecedentes_adjudicacion='".$s_i_orig[48]."', origen_pecc='".$s_i_orig[49]."', revision1_conflicto_intereces='".$s_i_orig[50]."', revision2_conflicto_intereces='".$s_i_orig[51]."', req_contra_mano_obra_local='".$s_i_orig[52]."', req_contra_serv_bien_local='".$s_i_orig[53]."', req_crear_otro_si='".$s_i_orig[54]."', req_contra_mano_obra_local_ad='".$s_i_orig[55]."', req_contra_serv_bien_local_ad='".$s_i_orig[56]."', par_tecnico='".$s_i_orig[57]."', gerente_contrato='".$s_i_orig[58]."', par_tecnico_ad='".$s_i_orig[59]."', gerente_contrato_ad='".$s_i_orig[60]."', equipo_negociador='".$s_i_orig[61]."', vencimiento_contrato='".$s_i_orig[63]."', pecc_linea='".$s_i_orig[64]."',pecc_modificado='".$s_i_orig[65]."', pecc_modificado_id_solicitud_aprobacion='".$s_i_orig[66]."', pecc_modificado_observacion='".$s_i_orig[67]."', id_urna='".$s_i_orig[68]."',numero_urna='".$s_i_orig[69]."', categoria_requiere_urna='".$s_i_orig[70]."', tiene_reajuste='".$s_i_orig[71]."', tiene_retencion='".$s_i_orig[72]."', tiene_reembolsable='".$s_i_orig[73]."', como_reembolsable='".$s_i_orig[74]."'
where id_item = ".$destino);

$trae_id_insrte = " select SCOPE_IDENTITY() AS [SCOPE_IDENTITY]";
	$sel_anexos = query_db("select t2_anexo_id, t2_item_pecc_id, aleatorio, tipo, detalle, adjunto, estado, id_us, antecedente_comite, id_categoria from t2_anexo where t2_item_pecc_id = ".$original." and estado = 1 and adjunto <> ''");
	while($s_ane = traer_fila_db($sel_anexos)){
		$insert = "insert into t2_anexo (t2_item_pecc_id, tipo, detalle, adjunto, estado, id_us, antecedente_comite) values (".$destino.", '".$s_ane[3]."', '".$s_ane[4]."', '".$s_ane[5]."', '".$s_ane[6]."', '".$s_ane[7]."', '".$s_ane[8]."', '".$s_ane[9]."')";
		$sql_ex=query_db($insert.$trae_id_insrte);
		$id_ane_destino = id_insert($sql_ex);
		copy(SUE_PATH_ARCHIVOS."pecc/".$s_ane[0]."_2.txt",SUE_PATH_ARCHIVOS."pecc/".$id_ane_destino."_2.txt");		
		}
	$sel_afe_ceco = query_db("select id, id_item, aleatorio, id_campo, afe_ceco, adjunto, permiso_adjudica, estado from t2_relacion_afe_ceco where id_item = ".$original." and estado = 1");
	while($s_ane = traer_fila_db($sel_afe_ceco)){
		
		$insert = "insert into t2_relacion_afe_ceco (id_item, id_campo, afe_ceco, adjunto, permiso_adjudica, estado) values (".$destino.", '".$s_ane[3]."', '".$s_ane[4]."', '".$s_ane[5]."', '".$s_ane[6]."', '".$s_ane[7]."')";
		$sql_ex=query_db($insert.$trae_id_insrte);
		$id_ane_destino = id_insert($sql_ex);
		copy(SUE_PATH_ARCHIVOS."pecc/afe_ceco/".$s_ane[0]."_8.txt",SUE_PATH_ARCHIVOS."pecc/afe_ceco/".$id_ane_destino."_8.txt");		
		}
		
	$sel_presupuesto = query_db("select t2_presupuesto_id, t2_item_pecc_id, t1_campo_id, adjunto, valor_usd, valor_cop, ano, permiso_o_adjudica,destino_final, cargo_contable, al_valor_inicial_para_marco, aleatorio from t2_presupuesto where t2_item_pecc_id = ".$original." and permiso_o_adjudica in (".$valor_permiso_ad.")");
	while($sel_p = traer_fila_db($sel_presupuesto )){
		if($sel_p[10] == ""){
			$valor_inicial = NULL;
			}else{
				$valor_inicial = $sel_p[10];
				}
			
		$insert_princi = "insert into t2_presupuesto (t2_item_pecc_id, t1_campo_id, adjunto, valor_usd, valor_cop, ano, permiso_o_adjudica,destino_final, cargo_contable, al_valor_inicial_para_marco, aleatorio) values (".$destino.", ".$sel_p[2].", '".$sel_p[3]."', '".$sel_p[4]."', '".$sel_p[5]."', '".$sel_p[6]."', '".$sel_p[7]."', '".$sel_p[8]."', '".$sel_p[9]."', '".$valor_inicial."', '".$sel_p[11]."') ";
		
		$sql_ex=query_db($insert_princi.$trae_id_insrte);
		$id_presup_destino = id_insert($sql_ex);
		copy(SUE_PATH_ARCHIVOS."pecc/".$sel_p[0]."_3.txt",SUE_PATH_ARCHIVOS."pecc/".$id_presup_destino."_3.txt");
		

	if($sel_p[7]==2){		
/*COMPARACION SOLO CON EL PRESUPUESTO CONTRATO NORMAL*/
		$sel_prove_adjudi = query_db("select id_relacion,t1_proveedor_id, t1_tipo_documento_id, vigencia_mes, apellido,t2_item_pecc_id_marco from t2_presupuesto_proveedor_adjudica where t2_presupuesto_id = ".$sel_p[0]." ");
		while($sel_pro_ad = traer_fila_db($sel_prove_adjudi)){
			//$sel_si_esta = traer_fila_row(query_db("select count(*) from t2_presupuesto_proveedor_adjudica where t2_item_pecc_id_marco = ".$destino." AND t1_proveedor_id='".$sel_pro_ad[1]."' and  t1_tipo_documento_id ='".$sel_pro_ad[2]."' and apellido='".$sel_pro_ad[4]."'"));
			//if($sel_si_esta[0]==0){
			$insert = "insert into t2_presupuesto_proveedor_adjudica (t2_presupuesto_id, t1_proveedor_id, t1_tipo_documento_id, vigencia_mes, apellido, t2_item_pecc_id_marco) values (".$id_presup_destino.", '".$sel_pro_ad[1]."', '".$sel_pro_ad[2]."', '".$sel_pro_ad[3]."', '".$sel_pro_ad[4]."', '".$destino."')";
			$sql_ex=query_db($insert.$trae_id_insrte);
			$id_pro_ad_destino = id_insert($sql_ex);
			//}
			$sel_aplica_contra = query_db("select t7_contrato_id, t2_proveedor_adjudica from t2_presupuesto_aplica_contrato where t2_presupuesto_id = ".$sel_p[0]." and t2_proveedor_adjudica = ".$sel_pro_ad[0]);
		while($sel_pro_ad = traer_fila_db($sel_aplica_contra)){			
			$insert = query_db("insert into t2_presupuesto_aplica_contrato (t2_presupuesto_id, t7_contrato_id, t2_proveedor_adjudica) values (".$id_presup_destino.", '".$sel_pro_ad[0]."', '".$id_pro_ad_destino."')");
			}
			
			}
/* FIN COMPARACION SOLO CON EL PRESUPUESTO CONTRATO NORMAL*/
/*COMPARACION SI ES CONTRATO MARCO*/
			
		$sel_prove_adjudi = query_db("select id_relacion,t1_proveedor_id, t1_tipo_documento_id, vigencia_mes, apellido,t2_item_pecc_id_marco from t2_presupuesto_proveedor_adjudica where t2_presupuesto_id = 0 and t2_item_pecc_id_marco = ".$original);
		while($sel_pro_ad = traer_fila_db($sel_prove_adjudi)){
			$sel_si_esta = traer_fila_row(query_db("select count(*) from t2_presupuesto_proveedor_adjudica where t2_item_pecc_id_marco = ".$destino." AND t1_proveedor_id='".$sel_pro_ad[1]."' and  t1_tipo_documento_id ='".$sel_pro_ad[2]."' and apellido='".$sel_pro_ad[4]."'"));
			if($sel_si_esta[0]==0){
	
			$insert = "insert into t2_presupuesto_proveedor_adjudica (t2_presupuesto_id, t1_proveedor_id, t1_tipo_documento_id, vigencia_mes, apellido, t2_item_pecc_id_marco) values (".$id_presup_destino.", '".$sel_pro_ad[1]."', '".$sel_pro_ad[2]."', '".$sel_pro_ad[3]."', '".$sel_pro_ad[4]."', '".$destino."')";
			$sql_ex=query_db($insert.$trae_id_insrte);
			$id_pro_ad_destino_marco = id_insert($sql_ex);
			}
			$sel_aplica_contra = query_db("select t7_contrato_id, t2_proveedor_adjudica from t2_presupuesto_aplica_contrato where t2_presupuesto_id = ".$sel_p[0]." and t2_proveedor_adjudica = ".$sel_pro_ad[0]);
		while($sel_pro_ad = traer_fila_db($sel_aplica_contra)){			
			$insert = query_db("insert into t2_presupuesto_aplica_contrato (t2_presupuesto_id, t7_contrato_id, t2_proveedor_adjudica) values (".$id_presup_destino.", '".$sel_pro_ad[0]."', '".$id_pro_ad_destino_marco."')");
			}
			
			}
/* FIN COMPARACION SI ES CONTRATO MARCO*/
			/*
		$sel_aplica_contra = query_db("select t7_contrato_id from t2_presupuesto_aplica_contrato where t2_presupuesto_id = ".$sel_p[0]." and (t2_proveedor_adjudica = 0 or t2_proveedor_adjudica is null or t2_proveedor_adjudica = '')");
		while($sel_pro_ad = traer_fila_db($sel_aplica_contra)){			
			$insert = query_db("insert into t2_presupuesto_aplica_contrato (t2_presupuesto_id, t7_contrato_id) values (".$id_presup_destino.", '".$sel_pro_ad[0]."')");
			}
			*/
	}
		}

$sel_proveedores = query_db("select  id_proveedor, permiso_o_adjudica, estado, id_us_crea from t2_relacion_proveedor where id_item = ".$original." and estado = 1");
while($sel_pro = traer_fila_db($sel_proveedores)){
	$insert_prov = query_db("insert into t2_relacion_proveedor (id_item, id_proveedor, permiso_o_adjudica, estado, id_us_crea) values (".$destino.", '".$sel_pro[0]."', '".$sel_pro[1]."', '".$sel_pro[2]."', '".$sel_pro[3]."')");
	}


$sel_ob_proceso = traer_fila_row(query_db("select CAST(p_oportunidad AS TEXT), CAST(p_costo AS TEXT), CAST(p_calidad AS TEXT), CAST(p_optimizar AS TEXT), CAST(p_trazabilidad AS TEXT), CAST(p_transparencia AS TEXT), CAST(p_sostenibilidad AS TEXT), CAST(a_oportunidad AS TEXT), CAST(a_costo AS TEXT), CAST(a_calidad AS TEXT), CAST(a_optimizar AS TEXT), CAST(a_trazabilidad AS TEXT), CAST(a_transparencia AS TEXT), CAST(a_sostenibilidad AS TEXT) from t2_objetivos_proceso where id_item = ".$original));

$update_ob_proceso = query_db("update t2_objetivos_proceso set p_oportunidad='".$sel_ob_proceso[0]."', p_costo='".$sel_ob_proceso[1]."', p_calidad='".$sel_ob_proceso[2]."', p_optimizar='".$sel_ob_proceso[3]."', p_trazabilidad='".$sel_ob_proceso[4]."', p_transparencia='".$sel_ob_proceso[5]."', p_sostenibilidad='".$sel_ob_proceso[6]."', a_oportunidad='".$sel_ob_proceso[7]."', a_costo='".$sel_ob_proceso[8]."', a_calidad='".$sel_ob_proceso[9]."', a_optimizar='".$sel_ob_proceso[10]."', a_trazabilidad='".$sel_ob_proceso[11]."', a_transparencia='".$sel_ob_proceso[12]."', a_sostenibilidad='".$sel_ob_proceso[13]."' where id_item = ".$destino);
	
	}



	
function legalizaciones_de_contratos($tipo, $id, $edita){
	global $co1, $fecha;
	//echo "entro aqui tipo: ".$tipo." id:".$id." edita:".$edita;
	/**** inicio para el des-091 se busca a que tipo de solicitud pertenece servicios/vinenes ***/
	$contraparte="no";
	$comple_where="";
	//echo $tipo." TIPO: ".$edita." ID: ".$id;
	if($tipo=="contrato"){//si es un contrato y no un otrosi
		$sel_co="select id_item, consecutivo, contratista, creacion_sistema, tipo_bien_servicio, t1_tipo_documento_id from $co1 where id=".$id;
		$res_co=traer_fila_row(query_db($sel_co));
		if($res_co[5]!=2){
			$res_co[4]=preg_replace('/ +/', '', $res_co[4]);
			if($res_co[4]=="Bienes"){//si es contrato puntual de bienes
				$muestra_mensaje_fechas="<strong>No aplica debido a que es un Contrato Puntual de Bienes</strong>";
				$contraparte="si";
			}
		}elseif($res_co[5]==2){//si es un contrato marco
			/********* PARA EL DES 091 *********/
			$contraparte="si";
			/**** inicio para el des-091 se busca a que tipo de solicitud pertenece servicios/vinenes ***/
			$comple_where1="";
			$sel_co1="select id_item, consecutivo, contratista, creacion_sistema, tipo_bien_servicio from $co1 where id=".$id;
			$res_co1=traer_fila_row(query_db($sel_co1));
			$muestra_mensaje_fechas="<strong>No aplica debido a que es un Contrato Marco de ".$res_co1[4]."</strong>";
			$ano1=explode("-", $res_co1[3]);
			$comple_where1.=" id_item=".$res_co1[0]." and consecutivo=".$res_co1[1]." and contratista=".$res_co1[2]." and creacion_sistema like '%".$ano1[0]."%'";
			$res_co1[4]=preg_replace('/ +/', '', $res_co1[4]);
			if($res_co1[4]=="Servicios"){
				$comple_where1.=" and tipo_bien_servicio like '%Bienes%'";
			}
			if($res_co1[4]=="Bienes"){
				$comple_where1.=" and tipo_bien_servicio like '%Servicios%'";
			}
			$sel_co1="select count(*) from $co1 where ".$comple_where1;
			$res_co1=traer_fila_row(query_db($sel_co1));
			if($res_co1[0]>0){
				$sel_co12="select count(*) from $co1 where id=".$id." and tipo_bien_servicio like '%servicio%'";
				$res_co12=traer_fila_row(query_db($sel_co12));
				if($res_co12[0]>0){//si el contrato que viene es de servicios y tiene contraparte en bienes pide las fechas
					$contraparte="";
				}else{
					$contraparte="si";
				}
			}
	/**** frin para el des-091 se busca a que tipo de solicitud pertenece servicios/vinenes ***/
			/********* PARA EL DES 091 *********/
		}
	}else{//es un otrosi o una orden de trabajo PARA EL DES039 OTROSI CHECK LIST
		//Se obtiene el id del contrato par saber que tipo de contrato (marco/puntual) es y el tipo(bien/servicio)
		$sel_id_contrato_original="select id_contrato, tipo_otrosi, tipo_complemento from t7_contratos_complemento where id=".$id;
		$res_co_id=traer_fila_row(query_db($sel_id_contrato_original));
		if($res_co_id[1]==9 and $res_co_id[2]==1){//si el tipo de otrosi es 9=otros
			$sel_co="select id_item, consecutivo, contratista, creacion_sistema, tipo_bien_servicio, t1_tipo_documento_id from $co1 where id=".$res_co_id[0];
			$res_co=traer_fila_row(query_db($sel_co));
			if($res_co[5]==1){//si es un contrato puntual
				$sel_co12="select count(*) from $co1 where id=".$res_co_id[0]." and (tipo_bien_servicio not like '%bien%' or tipo_bien_servicio is null)";
				$res_co12=traer_fila_row(query_db($sel_co12));
				if($res_co12[0]>0){//si el contrato puntual es de servicios pide check list
					$contraparte="";
				}else{//si el contrato puntual es de bienes no pide check list
					$contraparte="si";
					$muestra_mensaje_fechas="<strong>No aplica debido a que es un Contrato Puntual de Bienes</strong>";
				}
			}else{//si es diferente a contrato puntual no pide check list
				$muestra_mensaje_fechas="<strong>No aplica debido a que no es un Contrato Puntual de Servicios</strong>";
				$contraparte="si";
			}
		}elseif($res_co_id[2]==1 and($res_co_id[1]==3 or $res_co_id[1]==10 or $res_co_id[1]==11 or $res_co_id[1]==17 or $res_co_id[1]==13)){//para los dem�s otros�s que requieren soporte descentralizado
			$muestra_mensaje_fechas="";
			$contraparte="no";
		}elseif($res_co_id[2]==2){//para las ordenes de trabajo requieren soporte descentralizado
			$muestra_mensaje_fechas="";
			$contraparte="no";
		}else{//si el tipo otrosi es diferente de otros
			$muestra_mensaje_fechas="";
			$contraparte="si";
		}
	}
	//echo "<br>".$contraparte;
	/**** frin para el des-091 se busca a que tipo de solicitud pertenece servicios/vinenes ***/
	?>
    <input type="hidden" name="tipo_check_list" id="tipo_check_list" value="<?=$tipo?>" />
    
	<table width="100%" align="center" class="tabla_lista_resultados" <? if($_GET["genera_excel"]=="si"){?> border="1" <? }?>>
            <?
        	$entro = 0;
			?>
            <tr class="fondo_3">
              <td colspan="9" align="center">LISTA DE CHEQUEO DE LA LEGALIZACION</td>
              </tr>
            <tr class="fondo_3">
              <td colspan="2" align="center">Detalle</td>
              <td width="10%" align="center">Inicio<strong style="font-size:10px"><br />
                Rol Encargado
              </strong></td>
              <td width="10%" align="center">Fin<strong style="font-size:10px"><br />
                Rol Encargado
              </strong></td>
              <td width="42%" align="center"><strong>Observaciones</strong></td>
              <td width="5%" align="center">&nbsp;</td>
              <td width="3%" align="center">Dias Estimados</td>
              <td width="2%" align="center">Dias Reales</td>
              <td width="3%" align="center">Dias  Retraso</td>
              </tr>
			  <?
	if($tipo == "contrato"){
		$id_campo_aplica = " id_contrato ";
		
/*validacion para que el valor del contrato sea igual a las aprobaciones*/
$alert_validacion_valor_contra = valor_contrato_puntual($id);
/*FIN validacion*/
		
		$busca_contrato = "select aplica_garantia, t1_tipo_documento_id, creacion_sistema, recibido_abastecimiento_e, estado, aseguramiento_admin, informe_hse, id, id, garantia_seguro, gerente_por_aseguramiento from $co1 where id =". $id;
		
		
		$sql_con=traer_fila_row(query_db($busca_contrato));
		$sql_con[7]="";//esto es por que no aplican los campos aseguramiento admin ni hse
		$sql_con[8]="";//esto es por que no aplican los campos aseguramiento admin ni hse
			
		//$busca_contrato = "select id,id_item,consecutivo,objeto,nit,contratista,contacto_principal,email1,telefono1,gerente,fecha_inicio,vigencia_mes,aplica_acta_inicio,representante_legal,email2,telefono2,especialista,monto_usd,monto_cop,creacion_sistema,recibido_abastecimiento,sap,revision_legal,firma_hocol,firma_contratista,revision_poliza,legalizacion_final,estado,sap_e,revision_legal_e,firma_hocol_e,firma_contratista_e,revision_poliza_e,legalizacion_final_e,t1_tipo_documento_id,acta_socios,recibido_poliza,camara_comercio,ok_fecha,sel_representante,legalizacion_final_par,legalizacion_final_par_e,analista_deloitte,aplica_acta,recibo_poliza,fecha_informativa_e,fecha_informativa,recibido_abastecimiento_e,area_ejecucion,obs_congelado,aplica_portales,destino,aseguramiento_admin, aplica_garantia, porcentaje, en_que_momento, informe_hse, oferta_mercantil from $co1 where id =". $id;
 		
		}
		
		if($tipo == "modificacion"){
			$id_campo_aplica = " id_modificacion ";
			 $busca_contrato = "select t1.id_contrato, t1.id_contrato, t1.creacion_sistema, t1.recibido_abastecimiento_e, t1.estado,t1.id_contrato, t1.id_contrato, t1.tipo_complemento, t1.tipo_otrosi, t2.garantia_seguro  from t7_contratos_complemento as t1, $co1 as t2 where t1.id_contrato = t2.id and t1.id =".$id." ";
			$sql_con=traer_fila_row(query_db($busca_contrato));
			$sel_modifica = traer_fila_row(query_db("select tipo_complemento, id_contrato, numero_otrosi from t7_contratos_complemento where id = ".$id));
			
			
			$sql_con[0]="";//esto es por que no aplican los campos aseguramiento admin ni hse
			$sql_con[1]="";//esto es por que no aplican los campos aseguramiento admin ni hse
			$sql_con[5]="";//esto es por que no aplican los campos aseguramiento admin ni hse
			$sql_con[6]="";//esto es por que no aplican los campos aseguramiento admin ni hse
			
				/*validacion para que el valor del contrato sea igual a las aprobaciones*/
$alert_validacion_valor_contra = valor_contrato_puntual($sel_modifica[1]);
/*FIN validacion*/
		}
		
		


		
		
		
		$id_contrato_arr=$id;
	
	
	
	$campos_tabla = " id, ".$id_campo_aplica.", f_ini_creacion_sistema, f_fin_creacion_sistema, CONVERT(text,creacion_sistema_ob), f_ini_elaboracion, f_fin_elaboracion, CONVERT(text,elaboracion_ob) as elaboracion_ob, 
                         f_ini_recibido_ini_proceso, f_fin_recibido_ini_proceso, CONVERT(text,recibido_ini_proceso_ob) as recibido_ini_proceso_ob, f_ini_firma_rep_legal, f_fin_firma_rep_legal, CONVERT(text,firma_rep_legal_ob) as firma_rep_legal_ob, 
                         f_ini_capacida_contratis, f_fin_capacida_contratis, CONVERT(text,capacida_contratis_ob) as capacida_contratis_ob, f_ini_recibido_pol, f_fin_recibido_pol, CONVERT(text,recibido_pol_ob) as recibido_pol_ob, f_ini_pago_pol,
						 f_fin_pago_pol, CONVERT(text,pago_pol_ob) as pago_pol_ob, f_ini_rev_legal, f_fin_rev_legal, CONVERT(text,rev_legal_ob) as rev_legal_ob, f_ini_ver_rut, f_fin_ver_rut, CONVERT(text,ver_rut_ob) as ver_rut_ob, f_ini_rev_estrategia, f_fin_rev_estrategia, CONVERT(text,rev_estrategia_ob) as rev_estrategia_ob, 
                         f_ini_aprob_sap, f_fin_aprob_sap, CONVERT(text,aprob_sap_ob) as aprob_sap_ob, f_ini_firma_hocol, f_fin_firma_hocol, CONVERT(text,firma_hocol_ob) as firma_hocol_ob, f_ini_envio_ej_firma, f_fin_envio_ej_firma, 
                         CONVERT(text,envio_ej_firma_ob) as envio_ej_firma_ob, f_ini_inscrip_dian, f_fin_inscrip_dian, CONVERT(text,inscrip_dian_ob) as inscrip_dian_ob, f_ini_entrega_doc_contrac, f_fin_entrega_doc_contrac, CONVERT(text,entrega_doc_contrac_ob) as entrega_doc_contrac_ob, 
                         f_ini_elabora_pedido, f_fin_elabora_pedido, CONVERT(text,elabora_pedido_ob) as elabora_pedido_ob, f_ini_aproba_sap, f_fin_aproba_sap, CONVERT(text,aproba_sap_ob) as aproba_sap_ob, f_ini_entrega_doc, f_fin_entrega_doc, 
                         CONVERT(text,entrega_doc_ob) as entrega_doc_ob, f_ini_entrega_todo, f_fin_entrega_todo, CONVERT(text,entrega_todo_ob) as entrega_todo_ob, f_ini_entre_doc_cont, f_fin_entre_doc_cont, CONVERT(text,f_ini_entre_doc_cont_ob) as f_ini_entre_doc_cont_ob,f_ini_revision_polizas, f_fin_revision_polizas, CONVERT(text,revision_polizas_ob) as revision_polizas_ob, f_ini_entreg_vobo_poliz, f_fin_entreg_vobo_poliz, CONVERT(text,entreg_vobo_poliz_ob) as entreg_vobo_poliz_ob, f_ini_garantia_recibo, f_fin_garantia_recibo, CONVERT(text,garantia_recibo_ob) as garantia_recibo_ob, 
                        CONVERT(text,garantia_recibo_ob2) as garantia_recibo_ob2, f_ini_garantia_rev_leg, f_fin_garantia_rev_leg, CONVERT(text,garantia_rev_leg_ob) as garantia_rev_leg_ob, CONVERT(text,garantia_rev_leg_ob2) as garantia_rev_leg_ob2, f_ini_garantia_env_reci, f_fin_garantia_env_reci, 
                         CONVERT(text,garantia_env_reci_ob) as garantia_env_reci_ob, CONVERT(text,garantia_env_reci_ob2) as garantia_env_reci_ob2, f_ini_garantia_dili_form, f_fin_garantia_dili_form, CONVERT(text,garantia_dili_form_ob) as garantia_dili_form_ob, CONVERT(text,garantia_dili_form_ob2) as garantia_dili_form_ob2, 
                         f_ini_garantia_fir_rep, f_fin_garantia_fir_rep, CONVERT(text,garantia_fir_rep_ob) as garantia_fir_rep_ob, CONVERT(text,garantia_fir_rep_ob2) as garantia_fir_rep_ob2, f_ini_garantia_en_cont_for, f_fin_garantia_en_cont_for, CONVERT(text,garantia_en_cont_for_ob) as garantia_en_cont_for_ob, CONVERT(text,garantia_en_cont_for_ob2) as garantia_en_cont_for_ob2";
						 
$campos_tabla.=", CONVERT(text, elaboracion_ob2) as elaboracion_ob2,
CONVERT(text, recibido_ini_proceso_ob2) as recibido_ini_proceso_ob2,
CONVERT(text, firma_rep_legal_ob2) as firma_rep_legal_ob2,
CONVERT(text, capacida_contratis_ob2) as capacida_contratis_ob2,
CONVERT(text, recibido_pol_ob2) as recibido_pol_ob2,
CONVERT(text, pago_pol_ob2) as pago_pol_ob2,
CONVERT(text, rev_legal_ob2) as rev_legal_ob2,
CONVERT(text, ver_rut_ob2) as ver_rut_ob2,
CONVERT(text, rev_estrategia_ob2) as rev_estrategia_ob2,
CONVERT(text, aprob_sap_ob2) as aprob_sap_ob2,
CONVERT(text, firma_hocol_ob2) as firma_hocol_ob2,
CONVERT(text, envio_ej_firma_ob2) as envio_ej_firma_ob2,
CONVERT(text, inscrip_dian_ob2) as inscrip_dian_ob2,
CONVERT(text, entrega_doc_contrac_ob2) as entrega_doc_contrac_ob2,
CONVERT(text, elabora_pedido_ob2) as elabora_pedido_ob2,
CONVERT(text, aproba_sap_ob2) as aproba_sap_ob2,
CONVERT(text, entrega_doc_ob2) as entrega_doc_ob2,
CONVERT(text, entrega_todo_ob2) as entrega_todo_ob2,
CONVERT(text, f_ini_entre_doc_cont_ob2) as f_ini_entre_doc_cont_ob2,
CONVERT(text, revision_polizas_ob2) as revision_polizas_ob2,
CONVERT(text, entreg_vobo_poliz_ob2) as entreg_vobo_poliz_ob2,
f_ini_garantia_tramite, f_fin_garantia_tramite,
CONVERT(text, garantia_tramite_ob) as garantia_tramite_ob,
CONVERT(text, garantia_tramite_ob2) as garantia_tramite_ob2,
f_ini_garantia_sol_inf, f_fin_garantia_sol_inf,
CONVERT(text, garantia_sol_inf_ob) as garantia_sol_inf_ob,
CONVERT(text, garantia_sol_inf_ob2) as garantia_sol_inf_ob2,
f_ini_gar_banc, f_fin_gar_banc,
CONVERT(text, gar_banc_ob) as gar_banc_ob,
CONVERT(text, gar_banc_ob2) as gar_banc_ob2,
f_ini_garantia_legal, f_fin_garantia_legal,
CONVERT(text, garantia_legal_ob) as garantia_legal_ob,
CONVERT(text, garantia_legal_ob2) as garantia_legal_ob2,

f_ini_rs_notifi, f_fin_rs_notifi,
CONVERT(text, rs_notifi_ob) as rs_notifi_ob,
CONVERT(text, rs_notifi_ob2) as rs_notifi_ob2,

f_ini_rs_elab, f_fin_rs_elab,
CONVERT(text, rs_elab_ob) as rs_elab_ob,
CONVERT(text, rs_elab_ob2) as rs_elab_ob2,

f_ini_rs_ajust_fec, f_fin_rs_ajust_fec,
CONVERT(text, rs_ajust_fec_ob) as rs_ajust_fec_ob,
CONVERT(text, rs_ajust_fec_ob2) as rs_ajust_fec_ob2,

f_ini_rs_recibi, f_fin_rs_recibi,
CONVERT(text, rs_recibi_ob) as rs_recibi_ob,
CONVERT(text, rs_recibi_ob2) as rs_recibi_ob2,

f_ini_rs_firm_hoco, f_fin_rs_firm_hoco,
CONVERT(text, rs_firm_hoco_ob) as rs_firm_hoco_ob,
CONVERT(text, rs_firm_hoco_ob2) as rs_firm_hoco_ob2,

f_ini_creacion_carp, f_fin_creacion_carp,
CONVERT(text, creacion_carp_ob) as creacion_carp_ob,
CONVERT(text, creacion_carp_ob2) as creacion_carp_ob2


";
						 
						 
			  $alerta_incompletos ="";

			  if($sql_con[0]!=1){//si aplica retencion en garantias
				  $comple_sql_leg.= " and id not in (20,1023, 1024, 1025, 1026, 1027, 1028, 1029, 2030)";
					  }
					  
				
/***********************CONVINACION DE CAMPOS QUE APLICAN PARA EL CAMPO SEGUROS Y GARANTIAS POLIZAS ************************/
				
				if(($sql_con[9]==3 or $sql_con[9]==4 or $sql_con[9]==2)){//si no aplica polizas
						if($sql_con[9]!=4){//si diferente a garantia bancarias
								$comple_sql_leg.= " and id not in (1030)";
							}			
								
						if($sel_modifica[0] == 2 and $sql_con[9]==2){//si es OT
							$comple_sql_leg.= "";
							}else{
							  $comple_sql_leg.= " and id not in (6, 7, 1021, 1022)";
							}
				}else{
					//no incluya revision de garantias
					$comple_sql_leg.= " and id not in (1030)";
					
					if($sel_modifica[0] == 2 and $sql_con[9]==1){//si es OT y es polizas no aplica para las OTs
							  $comple_sql_leg.= " and id not in (6, 7, 1021, 1022)";
							}
					
					}
/***********************CONVINACION DE CAMPOS QUE APLICAN PARA EL CAMPO SEGUROS Y GARANTIAS POLIZAS ************************/
				
				if($sql_con[7]==1 ){//si es Otro Si.
					$comple_sql_leg.= " and id not in (2036)";//campos que no aplican para ningun otrosi
					if($sql_con[8] == 4 or $sql_con[8] == 12 or $sql_con[8] == 8 or $sql_con[8] == 2 or $sql_con[8] == 15 or $sql_con[8] == 16){//si son los tipos de otros si Alcance * Alcance / Tiempo * Alcance / Tiempo / Valor * Gerente * Tiempo *Tarifas * Tarifas / Tiempo / Alcance
					  $comple_sql_leg.= " and id not in (15, 16, 17, 21, 2036)";
					}
					
					
				}
				if($sql_con[7]==2 ){//si es OW.
					  $comple_sql_leg.= " and id not in (8, 12, 13, 14, 2036)";
				}
				
				$campo_nombre="";
				$campo_ayuda="";
				$campo_dias_esti="";
				if($sql_con[7]==3){//si es SUSPENCION. use los campos exluxivos para suepenciones nombre_en_suspencion, ayuda_en_suspencion, dias_estimados_suspencion y solo muestre la lista pasos que aplica
						$campo_nombre="nombre_en_suspencion";
						$campo_ayuda="ayuda_en_suspencion";
						$campo_dias_esti="dias_estimados_suspencion";
					  $comple_sql_leg.= " and id in (1,2031,2032,2035,2034,4,5,6,7,1021,1022,2033,13,18,19)";
				}elseif($sql_con[7]==4){//si es REINIICIO.  use los campos exluxivos para suepenciones nombre_en_reinicio, ayuda_en_reinicio, dias_estimados_reinicio y solo muestre la lista pasos que aplica
						$campo_nombre="nombre_en_reinicio";
						$campo_ayuda="ayuda_en_reinicio";
						$campo_dias_esti="dias_estimados_reinicio";

						$comple_sql_leg.= " and id in (1,2031,2032,2034,4,5,6,7,1021,1022,2033,13,18,19)";
					
					}else{//si es un otrosi OW o contrato oculte campos exclucivos de las suspenciones y reinicios y use los campos de nombres, dias estimados y ayudas normales
						$campo_nombre="nombre";
						$campo_ayuda="ayuda";
						$campo_dias_esti="dias_estimados";
  					  $comple_sql_leg.= " and id not in (2031, 2032, 2033, 2034, 2035)";//campos exlucivos de reinicio y/o Suspencion
					}
					
			
					//echo "select id, id_actividad_nivel_servicio, ".$campo_nombre.", campo_fecha_inicial, campo_fecha_final, CAST(".$campo_ayuda." AS TEXT), orden, rol_edita_fecha_ini, rol_edita_fecha_fin, fecha_inicial_igual_a_id_relacion_campo, campo_ob, ".$campo_dias_esti.", alerta, ob_obligatoria, Devolucion, campo_ob_fin_si_aplicara, edita_fecha_inicial, edita_fecha_final from t7_relacion_campos_legalizacion where id > 0 ".$comple_sql_leg." order by orden";
              $sel_campos = query_db("select id, id_actividad_nivel_servicio, ".$campo_nombre.", campo_fecha_inicial, campo_fecha_final, CAST(".$campo_ayuda." AS TEXT), orden, rol_edita_fecha_ini, rol_edita_fecha_fin, fecha_inicial_igual_a_id_relacion_campo, campo_ob, ".$campo_dias_esti.", alerta, ob_obligatoria, Devolucion, campo_ob_fin_si_aplicara, edita_fecha_inicial, edita_fecha_final from t7_relacion_campos_legalizacion where id > 0 ".$comple_sql_leg." order by orden");
			  $conteo1=0;
			  $conteo2=1;
		
/*-------------define si creo o actualiza en la tabla de los datos. -------------*/	  

$sel_campos_contra = traer_fila_db(query_db("select count(*) from t7_relacion_campos_legalizacion_datos where ".$id_campo_aplica." =".$id_contrato_arr));			
if($sel_campos_contra[0]==0){//si tiene creado en la tabla de la relacion de los campos
$insert = query_db("insert into t7_relacion_campos_legalizacion_datos (".$id_campo_aplica.", f_fin_creacion_sistema, f_ini_elaboracion,f_fin_elaboracion) values (".$id_contrato_arr.", '".$sql_con[2]."', '".$sql_con[2]."', '".$sql_con[3]."')");
}else{
	if($sql_con[4] <48){
$update = query_db("update t7_relacion_campos_legalizacion_datos set f_fin_elaboracion='".$sql_con[3]."' where ".$id_campo_aplica." = ".$id_contrato_arr);
	}
	}
/*-------------define si creo o actualiza en la tabla de los datos. -------------*/	  

//echo "select ".$campos_tabla." from t7_relacion_campos_legalizacion_datos where ".$id_campo_aplica." =".$id_contrato_arr;
$sel_campos_contra = traer_fila_db(query_db("select ".$campos_tabla." from t7_relacion_campos_legalizacion_datos where ".$id_campo_aplica." =".$id_contrato_arr));


			  while($s_cam = traer_fila_db($sel_campos)){
				  	$edita_fecha_1=0;
					$edita_fecha_2=0;
					$edita_ob=0;
					
					
/*ACTUALIZA CAMPOS QUE SE ALIMENTEN DE OTRAS FECHAS fecha_inicial_igual_a_id_relacion_campo*/
if($s_cam[9] != "" and $s_cam[9] != "0" and $sql_con[4] <48){
	$sel_si_tiene_devol = traer_fila_row(query_db("select count(*) from t7_relacion_campos_legalizacion_datos_devoluciones where ".$id_campo_aplica." = ".$id_contrato_arr." and id_campo_legalizacion = ".$s_cam[0],""));

	if(($sel_si_tiene_devol[0]==0 or $s_cam[0] == 17) and ($sel_campos_contra[$s_cam[4]]=="" or $sel_campos_contra[$s_cam[4]]==" ")){
		
	$update =query_db("update t7_relacion_campos_legalizacion_datos set ".$s_cam[3]." = '".$sel_campos_contra[$s_cam[9]]."' where ".$id_campo_aplica." =".$id_contrato_arr);
		
	$sel_campos_contra = traer_fila_db(query_db("select ".$campos_tabla." from t7_relacion_campos_legalizacion_datos where ".$id_campo_aplica." =".$id_contrato_arr));
	}
	}
/*ACTUALIZA CAMPOS QUE SE ALIMENTEN DE OTRAS FECHAS fecha_inicial_igual_a_id_relacion_campo*/



			/*INICIO PERMISOS DE EDICION*/
			

//			$sel_permiso_edita_fecha_ini = traer_fila_row(query_db("select count(*) from tseg12_relacion_usuario_rol where id_usuario = ".$_SESSION["id_us_session"]." and id_rol_general in (".$s_cam[7].")"));
	//		$sel_permiso_edita_fecha_fin = traer_fila_row(query_db("select count(*) from tseg12_relacion_usuario_rol where id_usuario = ".$_SESSION["id_us_session"]." and id_rol_general in (".$s_cam[8].")"));
	
		$sel_permiso_edita_fecha_ini = traer_fila_row(query_db("select count(*) from tseg12_relacion_usuario_rol where id_usuario in (".$_SESSION["usuarios_con_reemplazo"].") and id_rol_general in (".$s_cam[7].")"));
			$sel_permiso_edita_fecha_fin = traer_fila_row(query_db("select count(*) from tseg12_relacion_usuario_rol where id_usuario in (".$_SESSION["usuarios_con_reemplazo"].") and id_rol_general in (".$s_cam[8].")"));

				  if($s_cam[7] != 0 and $sel_permiso_edita_fecha_ini[0]>0 and ($sel_campos_contra[$s_cam[3]] =="" or $sel_campos_contra[$s_cam[3]] ==" ") and $sql_con[4] <48 and $s_cam[16] == 1){$edita_fecha_1=1; if($s_cam[7] == 21 and $edita != 1) $edita_fecha_1=0; }
				  if($s_cam[8] != 0 and $sel_permiso_edita_fecha_fin[0]>0 and ($sel_campos_contra[$s_cam[4]] =="" or $sel_campos_contra[$s_cam[4]] ==" ") and $sql_con[4] <48 and $s_cam[17] == 1){
					  $edita_fecha_2=1; 
					  if($s_cam[8] == 21 and $edita != 1) $edita_fecha_2=0;
					  }


				if($_SESSION["id_us_session"] == 32){//solo para el usuario admin
					//$edita_fecha_2 = 2;
					//$edita_fecha_2 = 1;					
					}
					
									  
				  if($edita_fecha_1 != 0 or $edita_fecha_2 != 0){
					  $edita_ob=1; 
					  
					   }
					   
		

		   /*FIN PERMISOS DE EDICION*/  
			   
			   			   
			   
		   
$dias_reales="";
$dias_retraso="";	
			if($sel_campos_contra[$s_cam[3]] != "" and $sel_campos_contra[$s_cam[3]] != " " and $sel_campos_contra[$s_cam[3]] != "  " and $sel_campos_contra[$s_cam[4]] != "" and $sel_campos_contra[$s_cam[4]] != " " and $sel_campos_contra[$s_cam[4]] != "  "){

				if($sel_campos_contra[$s_cam[3]] <= $sel_campos_contra[$s_cam[4]])
						$dias_retraso=0;
						$dias_reales = dias_habiles_entre_fechas($sel_campos_contra[$s_cam[3]],$sel_campos_contra[$s_cam[4]]);
			}
			
			if($dias_reales!=""){
					$dias_retraso = $dias_reales-$s_cam[11];
					if($dias_retraso <=0) {$dias_retraso=0;}else{ $dias_retraso="<strong class='letra-descuentos'>".$dias_retraso."</strong>";}
				}
				  
				  $expo = explode(".", $s_cam[6]);
				//  if($expo[1]==0 or $expo[1]==""){
					if($_GET["genera_excel"]!="si"){ $comple_num_ayuda = '<img src="../imagenes/botones/help.gif" alt="'.$s_cam[5].'" width="20" height="20" title="'.$s_cam[5].'" />'; }
					 
					  if($expo[1]==0 or $expo[1]==""){
							  $conteo1=$conteo1+1;
							  $num_imprime =  $comple_num_ayuda." ".$conteo1.". ".$s_cam[2];				  
									 if($clase==""){
										  $clase="class='filas_resultados'";
										  }else{
											  $clase="";
											  }
							$conteo2=1;
						  }else{	
						 	  $num_imprime =  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$comple_num_ayuda." ".$conteo1.".".$conteo2.". ".$s_cam[2];
							  $conteo2 = $conteo2+1;
							  } 
					  
					 
						  
						  
						  
						  $alerta="";
						  if($s_cam[12]!=""){
						  $alerta='<br /><strong class="letra-descuentos"><img src="../imagenes/botones/aler-interro.gif" height="20" /> '.$s_cam[12].'</strong>';
                          }
                          $bloquea_check="";
						  if($_GET["da"] == 1 and $s_cam[0]!=3){//solo si es el perfil de legal oculta todo menos la fila 3 del la tabla legalizacion_contrato
							   $bloquea_check = $display;
							   }
			  ?>
            <tr <?=$clase?> <?=$bloquea_check?>>
              <td colspan="2" valign="top"><? ?> 
			  <?
             echo $num_imprime;
			  ?> 
              
              
              </td>
              <td align="center" valign="top">
    <?
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
			if($contraparte=="si"){
				if($s_cam[3]!="f_ini_elabora_pedido" and $s_cam[3]!="f_ini_entrega_doc_contrac" and $s_cam[3]!="f_ini_aproba_sap" and $s_cam[3]!="f_ini_entre_doc_cont"){//LAS FECHAS QUE SEAN DIFERENTES DE PEDIDO
					  //echo "pedido 1";
				  //}	  
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
			?>
			  
  <? if($edita_fecha_1 == 1){?>
     <input name="<?=$s_cam[3]?>" type="text" id="<?=$s_cam[3]?>" value="<?=$sel_campos_contra[$s_cam[3]]?>" size="10" maxlength="10" onclick="this.value='<?=$fecha;?>'" readonly />
     
<!--     <input name="< ?=$s_cam[3]?>" type="text" id="< ?=$s_cam[3]?>" value="< ?=$sel_campos_contra[$s_cam[3]]?>" size="10" maxlength="10" onclick="" onmouseover="calendario_sin_hora('< ?=$s_cam[3]?>')" readonly="readonly" onchange="valida_fecha_ideal_legalizacion_contrato(this, 'ini')"/>-->
			  
  <? }else{ echo $sel_campos_contra[$s_cam[3]]; ?> <input name="<?=$s_cam[3]?>" type="hidden" id="<?=$s_cam[3]?>" value="<?=$sel_campos_contra[$s_cam[3]]?>"/><? }?>
              
              <? 
			  
			   /* SELECCIONA ROL LA UNICA DIFERENCIA A TODOS LOS OTROS ES $s_cam[8]*/
			   $rol_encargado_inicial = "";
			   $rol_encargado_inicial_id = 0;
			  $sel_rol_encargado = traer_fila_row(query_db("select nombre from tseg11_roles_general where id_rol = ".$s_cam[7]));
			  if($sel_rol_encargado[0]!= ""){
				  echo "<br /><strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
				  $rol_encargado_inicial = "<strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
				  $rol_encargado_inicial_id=$s_cam[7];
				  }else{
					  $id_rol_si_aplica_otro_campo=0;
					  $sel_segun_campo_alimenta = traer_fila_row(query_db("select rol_edita_fecha_ini from t7_relacion_campos_legalizacion where campo_fecha_inicial = '".$s_cam[9]."'"));
					  if($sel_segun_campo_alimenta[0] <> 0){
						  $id_rol_si_aplica_otro_campo = $sel_segun_campo_alimenta[0];
						  }else{
							  $sel_segun_campo_alimenta = traer_fila_row(query_db("select rol_edita_fecha_fin from t7_relacion_campos_legalizacion where campo_fecha_final = '".$s_cam[9]."'"));
							  $id_rol_si_aplica_otro_campo = $sel_segun_campo_alimenta[0];
							  }
							  
							  $sel_rol_encargado = traer_fila_row(query_db("select nombre from tseg11_roles_general where id_rol = ".$id_rol_si_aplica_otro_campo));
								  if($sel_rol_encargado[0]!= ""){
									  echo "<br /><strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
									  $rol_encargado_inicial = "<strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
									  $rol_encargado_inicial_id=$id_rol_si_aplica_otro_campo;
									  }
					  }
					  
					   /* SELECCIONA ROL LA UNICA DIFERENCIA A TODOS LOS OTROS ES $s_cam[8]*/

					}else{//LAS FECHAS QUE SEAN DE PEDIDO
						echo "&nbsp;&nbsp;N/A";
					}
				}else{// PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
	if($edita_fecha_1 == 1){?>
     <input name="<?=$s_cam[3]?>" type="text" id="<?=$s_cam[3]?>" value="<?=$sel_campos_contra[$s_cam[3]]?>" size="10" maxlength="10" onclick="this.value='<?=$fecha;?>'" readonly />
     
<!--     <input name="< ?=$s_cam[3]?>" type="text" id="< ?=$s_cam[3]?>" value="< ?=$sel_campos_contra[$s_cam[3]]?>" size="10" maxlength="10" onclick="" onmouseover="calendario_sin_hora('< ?=$s_cam[3]?>')" readonly="readonly" onchange="valida_fecha_ideal_legalizacion_contrato(this, 'ini')"/>-->
			  
  <? }else{ echo $sel_campos_contra[$s_cam[3]]; ?> <input name="<?=$s_cam[3]?>" type="hidden" id="<?=$s_cam[3]?>" value="<?=$sel_campos_contra[$s_cam[3]]?>"/><? }?>
              
              <? 
			  
			   /* SELECCIONA ROL LA UNICA DIFERENCIA A TODOS LOS OTROS ES $s_cam[8]*/
			   $rol_encargado_inicial = "";
			   $rol_encargado_inicial_id = 0;
			  $sel_rol_encargado = traer_fila_row(query_db("select nombre from tseg11_roles_general where id_rol = ".$s_cam[7]));
			  if($sel_rol_encargado[0]!= ""){
				  echo "<br /><strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
				  $rol_encargado_inicial = "<strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
				  $rol_encargado_inicial_id=$s_cam[7];
				  }else{
					  $id_rol_si_aplica_otro_campo=0;
					  $sel_segun_campo_alimenta = traer_fila_row(query_db("select rol_edita_fecha_ini from t7_relacion_campos_legalizacion where campo_fecha_inicial = '".$s_cam[9]."'"));
					  if($sel_segun_campo_alimenta[0] <> 0){
						  $id_rol_si_aplica_otro_campo = $sel_segun_campo_alimenta[0];
						  }else{
							  $sel_segun_campo_alimenta = traer_fila_row(query_db("select rol_edita_fecha_fin from t7_relacion_campos_legalizacion where campo_fecha_final = '".$s_cam[9]."'"));
							  $id_rol_si_aplica_otro_campo = $sel_segun_campo_alimenta[0];
							  }
							  
							  $sel_rol_encargado = traer_fila_row(query_db("select nombre from tseg11_roles_general where id_rol = ".$id_rol_si_aplica_otro_campo));
								  if($sel_rol_encargado[0]!= ""){
									  echo "<br /><strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
									  $rol_encargado_inicial = "<strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
									  $rol_encargado_inicial_id=$id_rol_si_aplica_otro_campo;
									  }
					  }
				}// FIN PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
			  ?>
              
              </td>
              <td align="center" valign="top">
            <?
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
			if($contraparte=="si"){
				if($s_cam[4]!="f_fin_elabora_pedido" and $s_cam[4]!="f_fin_entrega_doc_contrac" and $s_cam[4]!="f_fin_aproba_sap" and $s_cam[4]!="f_fin_entre_doc_cont"){//LAS FECHAS QUE SEAN DIFERENTES DE PEDIDO
					  //echo "pedido 1";
				  //}	  
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
			?>
	<? 
	
	if($sel_campos_contra[$s_cam[3]] == "" or $sel_campos_contra[$s_cam[3]] == " "){
				$edita_fecha_2 = 0;
			}
			
	if($edita_fecha_2 == 1){
		
		?>
        <input name="<?=$s_cam[4]?>" type="text" id="<?=$s_cam[4]?>" value="<?=$sel_campos_contra[$s_cam[4]]?>" size="10" maxlength="10" onclick="pone_fecha_fin(this, document.principal.<?=$s_cam[3]?>, '<?=$fecha?>')" readonly/>
        
        <!-- <input name="< ?=$s_cam[4]?>" type="text" id="< ?=$s_cam[4]?>" value="< ?=$sel_campos_contra[$s_cam[4]]?>" size="10" maxlength="10" onmouseover="calendario_sin_hora('< ?=$s_cam[4]?>')" readonly="readonly" onchange="valida_fecha_ideal_legalizacion_contrato(this, 'fin', document.principal.< ?=$s_cam[3]?>)"/> -->
        
	<? } else{ echo $sel_campos_contra[$s_cam[4]]; ?><input name="<?=$s_cam[4]?>" type="hidden" id="<?=$s_cam[4]?>" value="<?=$sel_campos_contra[$s_cam[4]]?>"/><? }?>
    
    
     <? 
	  /* SELECCIONA ROL LA UNICA DIFERENCIA A TODOS LOS OTROS ES $s_cam[8]*/
	  $rol_encargado_final="";
	  $rol_encargado_final_id=0;
			  $sel_rol_encargado = traer_fila_row(query_db("select nombre from tseg11_roles_general where id_rol = ".$s_cam[8]));
			  if($sel_rol_encargado[0]!= ""){
				  echo "<br /><strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
				  $rol_encargado_final = "<strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
				  $rol_encargado_final_id=$s_cam[8];
				  }else{
					  $id_rol_si_aplica_otro_campo=0;
					  $sel_segun_campo_alimenta = traer_fila_row(query_db("select rol_edita_fecha_ini from t7_relacion_campos_legalizacion where campo_fecha_inicial = '".$s_cam[9]."'"));
					  if($sel_segun_campo_alimenta[0] <> 0){
						  $id_rol_si_aplica_otro_campo = $sel_segun_campo_alimenta[0];
						  }else{
							  $sel_segun_campo_alimenta = traer_fila_row(query_db("select rol_edita_fecha_fin from t7_relacion_campos_legalizacion where campo_fecha_final = '".$s_cam[9]."'"));
							  $id_rol_si_aplica_otro_campo = $sel_segun_campo_alimenta[0];
							  }
							  
							  $sel_rol_encargado = traer_fila_row(query_db("select nombre from tseg11_roles_general where id_rol = ".$id_rol_si_aplica_otro_campo));
								  if($sel_rol_encargado[0]!= ""){
									  echo "<br /><strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
									  $rol_encargado_final = "<strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
									   $rol_encargado_final_id=$id_rol_si_aplica_otro_campo;
									  }
					  }
		 /* SELECCIONA ROL LA UNICA DIFERENCIA A TODOS LOS OTROS ES $s_cam[8]*/
		 		}else{//LAS FECHAS QUE SEAN DE PEDIDO
						echo "&nbsp;&nbsp;N/A";
					}
			}else{// PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
	if($sel_campos_contra[$s_cam[3]] == "" or $sel_campos_contra[$s_cam[3]] == " "){
				$edita_fecha_2 = 0;
			}
			
	if($edita_fecha_2 == 1){
		
		?>
        <input name="<?=$s_cam[4]?>" type="text" id="<?=$s_cam[4]?>" value="<?=$sel_campos_contra[$s_cam[4]]?>" size="10" maxlength="10" onclick="pone_fecha_fin(this, document.principal.<?=$s_cam[3]?>, '<?=$fecha?>')" readonly/>
        
        <!-- <input name="< ?=$s_cam[4]?>" type="text" id="< ?=$s_cam[4]?>" value="< ?=$sel_campos_contra[$s_cam[4]]?>" size="10" maxlength="10" onmouseover="calendario_sin_hora('< ?=$s_cam[4]?>')" readonly="readonly" onchange="valida_fecha_ideal_legalizacion_contrato(this, 'fin', document.principal.< ?=$s_cam[3]?>)"/> -->
        
	<? } else{ echo $sel_campos_contra[$s_cam[4]]; ?><input name="<?=$s_cam[4]?>" type="hidden" id="<?=$s_cam[4]?>" value="<?=$sel_campos_contra[$s_cam[4]]?>"/><? }?>
    
    
     <? 
	  /* SELECCIONA ROL LA UNICA DIFERENCIA A TODOS LOS OTROS ES $s_cam[8]*/
	  $rol_encargado_final="";
	  $rol_encargado_final_id=0;
			  $sel_rol_encargado = traer_fila_row(query_db("select nombre from tseg11_roles_general where id_rol = ".$s_cam[8]));
			  if($sel_rol_encargado[0]!= ""){
				  echo "<br /><strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
				  $rol_encargado_final = "<strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
				  $rol_encargado_final_id=$s_cam[8];
				  }else{
					  $id_rol_si_aplica_otro_campo=0;
					  $sel_segun_campo_alimenta = traer_fila_row(query_db("select rol_edita_fecha_ini from t7_relacion_campos_legalizacion where campo_fecha_inicial = '".$s_cam[9]."'"));
					  if($sel_segun_campo_alimenta[0] <> 0){
						  $id_rol_si_aplica_otro_campo = $sel_segun_campo_alimenta[0];
						  }else{
							  $sel_segun_campo_alimenta = traer_fila_row(query_db("select rol_edita_fecha_fin from t7_relacion_campos_legalizacion where campo_fecha_final = '".$s_cam[9]."'"));
							  $id_rol_si_aplica_otro_campo = $sel_segun_campo_alimenta[0];
							  }
							  
							  $sel_rol_encargado = traer_fila_row(query_db("select nombre from tseg11_roles_general where id_rol = ".$id_rol_si_aplica_otro_campo));
								  if($sel_rol_encargado[0]!= ""){
									  echo "<br /><strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
									  $rol_encargado_final = "<strong style='font-size:10px'>".$sel_rol_encargado[0]."</strong>";
									   $rol_encargado_final_id=$id_rol_si_aplica_otro_campo;
									  }
					  }
			}// FIN PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
			  ?>
    
    
              </td>
              <td valign="top">
              <?
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
			if($contraparte=="si"){
				if($s_cam[10]!="elabora_pedido_ob" and $s_cam[10]!="f_ini_entre_doc_cont_ob" and $s_cam[10]!="entrega_doc_contrac_ob" and $s_cam[10]!="aproba_sap_ob"){//LAS FECHAS QUE SEAN DIFERENTES DE PEDIDO
					  //echo "pedido 1";
				  //}	  
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
			?>
			  <? 
			  
			  if($edita_ob == 1){

				  if($edita_fecha_2 != 1){
				  ?>
              
              <textarea name="<?=$s_cam[10]?>" id="<?=$s_cam[10]?>" cols="5" rows="3"><?=$sel_campos_contra[$s_cam[10]]?></textarea>
              <input type="hidden" name="<?=$s_cam[15]?>" id="<?=$s_cam[15]?>" value="<?=$sel_campos_contra[$s_cam[15]]?>" />
              <?
				  }else{
					  ?><input type="hidden" name="<?=$s_cam[10]?>" id="<?=$s_cam[10]?>" value="<?=$sel_campos_contra[$s_cam[10]]?>" /><?
					  }
				  
				  if($edita_fecha_2 == 1){
					  if($sel_campos_contra[$s_cam[10]] <> " " and $sel_campos_contra[$s_cam[10]] <> ""){?><strong><?=$rol_encargado_inicial?>:</strong> <?=$sel_campos_contra[$s_cam[10]]?><? }
					  
			  ?>
              <textarea name="<?=$s_cam[15]?>" id="<?=$s_cam[15]?>" cols="5" rows="3"><?=$sel_campos_contra[$s_cam[15]]?></textarea> 
              
              <?
				  }
			  ?>
			  
			  
			  
			  <? echo $alerta;} else{ 
			  
			  
				if($sel_campos_contra[$s_cam[10]] <> " " and $sel_campos_contra[$s_cam[10]] <> ""){?><strong><?=$rol_encargado_inicial?>:</strong> <?=$sel_campos_contra[$s_cam[10]]?><? }
				  
			 if($sel_campos_contra[$s_cam[15]] <> " " and $sel_campos_contra[$s_cam[15]] <> ""){?><br /><strong><?=$rol_encargado_final?>:</strong> <?=$sel_campos_contra[$s_cam[15]]?><? }
			  
			  
			  
			  }
					}else{//LAS FECHAS QUE SEAN DE PEDIDO
						echo $muestra_mensaje_fechas;
					}
			}else{// PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
		if($edita_ob == 1){

				  if($edita_fecha_2 != 1){
				  ?>
              
              <textarea name="<?=$s_cam[10]?>" id="<?=$s_cam[10]?>" cols="5" rows="3"><?=$sel_campos_contra[$s_cam[10]]?></textarea>
              <input type="hidden" name="<?=$s_cam[15]?>" id="<?=$s_cam[15]?>" value="<?=$sel_campos_contra[$s_cam[15]]?>" />
              <?
				  }else{
					  ?><input type="hidden" name="<?=$s_cam[10]?>" id="<?=$s_cam[10]?>" value="<?=$sel_campos_contra[$s_cam[10]]?>" /><?
					  }
				  
				  if($edita_fecha_2 == 1){
					  if($sel_campos_contra[$s_cam[10]] <> " " and $sel_campos_contra[$s_cam[10]] <> ""){?><strong><?=$rol_encargado_inicial?>:</strong> <?=$sel_campos_contra[$s_cam[10]]?><? }
					  
			  ?>
              <textarea name="<?=$s_cam[15]?>" id="<?=$s_cam[15]?>" cols="5" rows="3"><?=$sel_campos_contra[$s_cam[15]]?></textarea> 
              
              <?
				  }
			  ?>
			  
			  
			  
			  <? echo $alerta;} else{ 
			  
			  
				if($sel_campos_contra[$s_cam[10]] <> " " and $sel_campos_contra[$s_cam[10]] <> ""){?><strong><?=$rol_encargado_inicial?>:</strong> <?=$sel_campos_contra[$s_cam[10]]?><? }
				  
			 if($sel_campos_contra[$s_cam[15]] <> " " and $sel_campos_contra[$s_cam[15]] <> ""){?><br /><strong><?=$rol_encargado_final?>:</strong> <?=$sel_campos_contra[$s_cam[15]]?><? }
			  
			  
			  
			  }
			}// FIN PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
			?>
              
              
              </td>
              <td valign="top">
              <?
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
			if($contraparte=="si"){
				if($s_cam[10]!="elabora_pedido_ob" and $s_cam[10]!="f_ini_entre_doc_cont_ob" and $s_cam[10]!="entrega_doc_contrac_ob" and $s_cam[10]!="aproba_sap_ob"){//LAS FECHAS QUE SEAN DIFERENTES DE PEDIDO
					  //echo "pedido 1";
				  //}	  
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
			?>
			  
<? 
$es_profesional_aseguramiento = traer_fila_row(query_db("select count(*) from tseg12_relacion_usuario_rol where id_usuario = ".$_SESSION["id_us_session"]." and id_rol_general in (24)"));
if($edita_fecha_1 ==1 or $edita_fecha_2 ==1){
	if($alerta_incompletos !="" and $s_cam[0] == 12){
		$alerta_incompletos_alerta = "muestra_alerta_error_solo_texto('', 'Error', '* No puede completar este paso hasta que complete: ".$alerta_incompletos."', 20, 10, 18)";
		
	}elseif($s_cam[0] == 10 and $es_profesional_aseguramiento[0] > 0 and $tipo == "contrato" and  ($sql_con[6] == "" or $sql_con[6] == " " or $sql_con[6] == "0" or $sql_con[5] == "" or $sql_con[5] == " " or $sql_con[5] == "0" or $sql_con[10] == " " or $sql_con[10] == "0")){

		$alerta_incompletos_alerta = "muestra_alerta_error_solo_texto('', 'Error', '* No puede completar este paso hasta que complete el aseguramiento Administrativo, el informe de HSE y Confirmar el Gerente de Contrato', 20, 10, 18)";

	}else{
		$ob_obligatoria = "NO";
		if($s_cam[13] == 1){
			$ob_obligatoria = "SI";
			}
		$alerta_incompletos_alerta = "graba_fecha_leg(document.principal.".$s_cam[3].", document.principal.".$s_cam[4].", document.principal.".$s_cam[10].", '".$s_cam[3]."', '".$s_cam[4]."', '".$s_cam[10]."','".$ob_obligatoria."', document.principal.".$s_cam[15].", '".$s_cam[15]."', '".$rol_encargado_inicial_id."', '".$rol_encargado_final_id."', '".$edita_fecha_2."', document.principal.id_actividad_".$s_cam[0].")";
		}
		
        if($alert_validacion_valor_contra != ""){
			$alerta_incompletos_alerta = "muestra_alerta_error_solo_texto('', 'Error', '* ".$alert_validacion_valor_contra."', 20, 10, 18)";
			}
		
	
	?><input type="hidden" name="id_actividad_<?=$s_cam[0]?>" id="id_actividad_<?=$s_cam[0]?>" value="<?=$s_cam[0]?>" />
    <input name="button" type="button" class="boton_grabar" id="button" value="Grabar" onclick="<?=$alerta_incompletos_alerta?>;"/>
    <?
			 if($s_cam[14] == 1 and $edita_fecha_2 != 0){
				 $devuelve_paso = "devolver_anterior(document.principal.".$s_cam[3].", document.principal.".$s_cam[10].", ".$s_cam[0].", '".$s_cam[3]."', '".$s_cam[10]."','".$rol_encargado_inicial_id."', '".$rol_encargado_final_id."', document.principal.".$s_cam[15].", '".$s_cam[15]."','')";
			?>
            
			<br /><input name="button" type="button" class="boton_grabar_cancelar" id="button" value="<? if ($s_cam[0] == 17) echo "Rechazar"; else echo "Devolver";?>" onclick="<?=$devuelve_paso?>"/>
			
			<? 
			 }
	}
					}else{//LAS FECHAS QUE SEAN DE PEDIDO
						echo "&nbsp;&nbsp;";
					}
			}else{// PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO

	$es_profesional_aseguramiento = traer_fila_row(query_db("select count(*) from tseg12_relacion_usuario_rol where id_usuario = ".$_SESSION["id_us_session"]." and id_rol_general in (24)"));
if($edita_fecha_1 ==1 or $edita_fecha_2 ==1){
	if($alerta_incompletos !="" and $s_cam[0] == 12){
		$alerta_incompletos_alerta = "muestra_alerta_error_solo_texto('', 'Error', '* No puede completar este paso hasta que complete: ".$alerta_incompletos."', 20, 10, 18)";
		
	}elseif($s_cam[0] == 10 and $es_profesional_aseguramiento[0] > 0 and $tipo == "contrato" and  ($sql_con[6] == "" or $sql_con[6] == " " or $sql_con[6] == "0" or $sql_con[5] == "" or $sql_con[5] == " " or $sql_con[5] == "0" or $sql_con[10] == " " or $sql_con[10] == "0")){

		$alerta_incompletos_alerta = "muestra_alerta_error_solo_texto('', 'Error', '* No puede completar este paso hasta que complete el aseguramiento Administrativo, el informe de HSE y Confirmar el Gerente de Contrato', 20, 10, 18)";

	}else{
		$ob_obligatoria = "NO";
		if($s_cam[13] == 1){
			$ob_obligatoria = "SI";
			}
		$alerta_incompletos_alerta = "graba_fecha_leg(document.principal.".$s_cam[3].", document.principal.".$s_cam[4].", document.principal.".$s_cam[10].", '".$s_cam[3]."', '".$s_cam[4]."', '".$s_cam[10]."','".$ob_obligatoria."', document.principal.".$s_cam[15].", '".$s_cam[15]."', '".$rol_encargado_inicial_id."', '".$rol_encargado_final_id."', '".$edita_fecha_2."', document.principal.id_actividad_".$s_cam[0].")";
		}
		
        if($alert_validacion_valor_contra != ""){
			$alerta_incompletos_alerta = "muestra_alerta_error_solo_texto('', 'Error', '* ".$alert_validacion_valor_contra."', 20, 10, 18)";
			}
		
	
	?><input type="hidden" name="id_actividad_<?=$s_cam[0]?>" id="id_actividad_<?=$s_cam[0]?>" value="<?=$s_cam[0]?>" />
    <input name="button" type="button" class="boton_grabar" id="button" value="Grabar" onclick="<?=$alerta_incompletos_alerta?>;"/>
    <?
			 if($s_cam[14] == 1 and $edita_fecha_2 != 0){
				 $devuelve_paso = "devolver_anterior(document.principal.".$s_cam[3].", document.principal.".$s_cam[10].", ".$s_cam[0].", '".$s_cam[3]."', '".$s_cam[10]."','".$rol_encargado_inicial_id."', '".$rol_encargado_final_id."', document.principal.".$s_cam[15].", '".$s_cam[15]."','')";
			?>
            
			<br /><input name="button" type="button" class="boton_grabar_cancelar" id="button" value="<? if ($s_cam[0] == 17) echo "Rechazar"; else echo "Devolver";?>" onclick="<?=$devuelve_paso?>"/>
			
			<? 
			 }
	}
			}// FIN PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
	?>
              
              </td>
              <td align="center" valign="top">
               <?
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
			if($contraparte=="si"){
				if($s_cam[10]!="elabora_pedido_ob" and $s_cam[10]!="f_ini_entre_doc_cont_ob" and $s_cam[10]!="entrega_doc_contrac_ob" and $s_cam[10]!="aproba_sap_ob"){//LAS FECHAS QUE SEAN DIFERENTES DE PEDIDO
					  //echo "pedido 1";
				  //}	  
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
				echo $s_cam[11];
				}else{//LAS FECHAS QUE SEAN DE PEDIDO
						echo "&nbsp;&nbsp;";
					}
			}else{// PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
				echo $s_cam[11];

			}// FIN PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
			?>
              	
              </td>
              <td align="center" valign="top">
              	 <?
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
			if($contraparte=="si"){
				if($s_cam[10]!="elabora_pedido_ob" and $s_cam[10]!="f_ini_entre_doc_cont_ob" and $s_cam[10]!="entrega_doc_contrac_ob" and $s_cam[10]!="aproba_sap_ob"){//LAS FECHAS QUE SEAN DIFERENTES DE PEDIDO
					  //echo "pedido 1";
				  //}	  
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
				echo $dias_reales;
				}else{//LAS FECHAS QUE SEAN DE PEDIDO
						echo "&nbsp;&nbsp;";
					}
			}else{// PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
				echo $dias_reales;

			}// FIN PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
			?>
			</td>
              <td align="center" valign="top">
              	<?
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
			if($contraparte=="si"){
				if($s_cam[10]!="elabora_pedido_ob" and $s_cam[10]!="f_ini_entre_doc_cont_ob" and $s_cam[10]!="entrega_doc_contrac_ob" and $s_cam[10]!="aproba_sap_ob"){//LAS FECHAS QUE SEAN DIFERENTES DE PEDIDO
					  //echo "pedido 1";
				  //}	  
			/*** PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO ***/
				echo $dias_retraso;
				}else{//LAS FECHAS QUE SEAN DE PEDIDO
						echo "&nbsp;&nbsp;";
					}
			}else{// PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
				echo $dias_retraso;

			}// FIN PARA EL DES 091 EVALUA SI TIENE CONTRAPARTE EN BIENES O SERVICIO
			?>
			</td>
              </tr>
              
              
            <?
			$total_dias_reales =0;
			$total_dias_reales = $total_dias_reales +$dias_reales;
			  
			  if($s_cam[14] == 1 or $s_cam[0] == 16){	
	  $cont_devoluciones = traer_fila_row(query_db("select count(*) from t7_relacion_campos_legalizacion_datos_devoluciones where ".$id_campo_aplica." = ".$id_contrato_arr." and id_campo_legalizacion=".$s_cam[0]));  
	  $sel_devoluciones = query_db("select inicio, fin, ob1, ob2 from t7_relacion_campos_legalizacion_datos_devoluciones where ".$id_campo_aplica." = ".$id_contrato_arr." and id_campo_legalizacion=".$s_cam[0]." order by id desc");  
			  
			  $cual_fila = 1;
			  $muestra_total="NO";
			  while($sel_dev = traer_fila_db($sel_devoluciones)){
				  $muestra_total="SI";
				  if($cual_fila == 1){
			  ?>
            
            <tr >
              <td align="right" valign="top" >&nbsp;</td>
              <td rowspan="<?=$cont_devoluciones[0]?>" align="right" valign="middle"  <?=$clase?>><?=$conteo1?>.1. <? if($s_cam[0] == 16) echo "Historico de Modificaciones"; elseif ($s_cam[0] == 17) echo "Historico de Rechazos"; else echo "Historico de Devoluciones";?>
              </td>
              <td align="center" valign="top" class="filas_sub_resultados"><?=$sel_dev[0]?></td>
              <td align="center" valign="top"  class="filas_sub_resultados"><?=$sel_dev[1]?></td>
              <td valign="top" colspan="2"  class="filas_sub_resultados">
			  

			  
			 <?
			 
			 if($sel_dev[2]<>"" and $sel_dev[2]<>" " and $sel_dev[2]<>"  "){
			  echo $rol_encargado_inicial.": ".$sel_dev[2];
			 }
			  
              if($sel_dev[3]<>"" and $sel_dev[3]<>" " and $sel_dev[3]<>"  "){
			  echo "<br />".$rol_encargado_final.": ".$sel_dev[3];
              
              }
			  
			  $dias_reales_dev = dias_habiles_entre_fechas($sel_dev[0],$sel_dev[1]);
			  
              ?>
              
              </td>
              <td align="center" valign="top"  class="filas_sub_resultados">&nbsp;</td>
              <td align="center" valign="top"  class="filas_sub_resultados"><?=$dias_reales_dev?></td>
              <td align="center" valign="top"  class="filas_sub_resultados">&nbsp;</td>
            </tr>
            <?
			$total_dias_reales = $total_dias_reales +$dias_reales_dev;
			$total_dias_reales_dev=0;
				  }else{//si es mayor a la primera fila
				   $dias_reales_dev = dias_habiles_entre_fechas($sel_dev[0],$sel_dev[1]);
			$total_dias_reales = $total_dias_reales +$dias_reales_dev;
			$total_dias_reales_dev = $total_dias_reales_dev +$dias_reales_dev;
			?>
             <tr >
   			<td valign="top"  >&nbsp;</td>
              <td align="center" valign="top" class="filas_sub_resultados"><?=$sel_dev[0]?></td>
              <td align="center" valign="top"  class="filas_sub_resultados"><?=$sel_dev[1]?></td>
              <td valign="top" colspan="2"  class="filas_sub_resultados"><?
			 
			 if($sel_dev[2]<>"" and $sel_dev[2]<>" " and $sel_dev[2]<>"  "){
			  echo $rol_encargado_inicial.": ".$sel_dev[2];
			 }
			  
              if($sel_dev[3]<>"" and $sel_dev[3]<>" " and $sel_dev[3]<>"  "){
			  echo $rol_encargado_final.": ".$sel_dev[3];
              
              }
              ?></td>

              <td align="center" valign="top"  class="filas_sub_resultados">&nbsp;</td>
              <td align="center" valign="top"  class="filas_sub_resultados"><?=$dias_reales_dev?></td>
              <td align="center" valign="top"  class="filas_sub_resultados">&nbsp;</td>
            </tr>
            <?
				 
				  }//fin si no es la primera fila
				  $cual_fila = $cual_fila+1; 
			  }//fin while quwe recorre las devoluciones
			  $dias_retraso_total=0;
			  if($muestra_total=="SI"){
				  
				  $dias_retraso_to = $total_dias_reales-$s_cam[11];
				  
				  if($total_dias_reales!=""){
					$dias_retraso_total = $total_dias_reales-$s_cam[11];
					if($dias_retraso_to <=0) {$dias_retraso=0;}else{ $dias_retraso_total="<strong class='letra-descuentos'>".$dias_retraso_to."</strong>";}
				}
			  ?>
              
			  <tr  >
              <td colspan="2" valign="top">
              </td>
              <td align="center" valign="top"> 
              </td>
              <td align="center" valign="top">
    
              </td>
              <td colspan="2" valign="top" align="right" class="filas_sub_resultados"><strong>Total de dias de <? if($s_cam[0] == 16) echo "Modificaciones"; elseif ($s_cam[0] == 17) echo "Rechazos"; else echo "Devoluciones";?>: </strong>
              </td>
              <td align="center" valign="top" class="filas_sub_resultados"></td>
              <td align="center" valign="top" class="filas_sub_resultados"><?=$total_dias_reales_dev?></td>
              <td align="center" valign="top" class="filas_sub_resultados"></td>
              </tr>
			  <tr  >
              <td colspan="2" valign="top">
              </td>
              <td align="center" valign="top"> 
              </td>
              <td align="center" valign="top">
    
              </td>
              <td colspan="2" valign="top" align="right" <?=$clase?>><strong>Total de dias de Gesti&oacute;n: </strong>
              </td>
              <td align="center" valign="top" <?=$clase?>><?=$s_cam[11]?></td>
              <td align="center" valign="top" <?=$clase?>><?=$total_dias_reales?></td>
              <td align="center" valign="top" <?=$clase?>><?=$dias_retraso_total?></td>
              </tr>
			  <?
			  }
				}
				if ($s_cam[0] <> 1 and $s_cam[0] <> 12 and ($sel_campos_contra[$s_cam[3]] =="" or $sel_campos_contra[$s_cam[3]] ==" " or $sel_campos_contra[$s_cam[4]] =="" or $sel_campos_contra[$s_cam[4]] ==" ")){ if($s_cam[0] != 12) $alerta_incompletos.= "\\n * ".$conteo1.". ".$s_cam[2];
			}
			  }
			?></table> <?  
			  
	}

function llena_tabla_temporal_reporte_marco($tipo, $id_contrato){
	$id_usuario_reporte = $_SESSION["id_us_session"];
	if($tipo == "ejecucion" or $tipo=="ejecucion_reporte_valor"){
		$tabla_temporal = "t2_reporte_marco_temporal_ejecuciones_excel";
		if($tipo=="ejecucion_reporte_valor"){//actualice el corte
		$id_usuario_reporte = 18463;	
			}
		}
	if($tipo == "saldos"){
		$tabla_temporal = "t2_reporte_marco_temporal";
		}
	
	$truncate_table = query_db("delete from ".$tabla_temporal." where id_us = ".$id_usuario_reporte);
	
	

	$sel_contratos_que_viene = traer_fila_row(query_db("select consecutivo,creacion_sistema,apellido, id_item from t7_contratos_contrato where id = ".$id_contrato." "));
$contratos_que_viene=numero_item_pecc_contrato_antes_formato("C",$sel_contratos_que_viene[1],$sel_contratos_que_viene[0],$sel_contratos_que_viene[2], $id_contrato);
$id_solicitud=$sel_contratos_que_viene[3];

	/*Inicial*/
	$numero_solicitud="";
/*selecciona vista para solicitud inicial*/

$seleccion_si_es_de_antiguos = traer_fila_row(query_db("select count(*) from vista_reporte_saldos_marco_3_crea_inicial where id_item = $id_solicitud"));
if($seleccion_si_es_de_antiguos[0]>0){
	$vista_inicial = "vista_reporte_saldos_marco_3_crea_inicial";
	}else{
		$vista_inicial = "vista_reporte_saldos_marco_2_crea_inicial";
		}
/*selecciona vista*/
	$sel_valor_inicial_sq = "select t2_presupuesto_id, ano,campo,t1_campo_id,valor_usd,valor_cop, num1, num2, num3 from ".$vista_inicial." where id_item = $id_solicitud group by t2_presupuesto_id, ano, campo,t1_campo_id,valor_usd,valor_cop, num1, num2, num3";
	$sel_valor_inicial = query_db($sel_valor_inicial_sq);
	$ano_solicitud_aproba = "0";
	while($v_ini = traer_fila_db($sel_valor_inicial)){//se selecciona el valor ingresado en la creacion de los contratos pero sin los contratos
		$contratos="";
		$contratista="";
		if($numero_solicitud==""){//como es un solo numero se llena la variable una ves para no cargar el sistema
		$numero_solicitud=numero_item_pecc($v_ini[6], $v_ini[7], $v_ini[8]);
		}
		$sel_contratos = query_db("select consecutivo,creacion_sistema,apellido,contratista, id_contrato from ".$vista_inicial." where id_item = $id_solicitud and t2_presupuesto_id=".$v_ini[0]." group by consecutivo, creacion_sistema, apellido, contratista, id_contrato");
		$cont = 0;
		while($s_contras = traer_fila_db($sel_contratos)){//se seleccionan los contratos creados en la creacion
		if($contratos_que_viene==numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2], $s_contras[4])){
				$num_contra_while="<font color=blue>".numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2], $s_contras[4])."</font>";
			}else{
				$num_contra_while=numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2], $s_contras[4]);
				}
		 if($cont == 0){
		  	$clase= "class=filas_resultados_reporte_saldos1";
			$cont = 1;
		  }else{
		  	$clase= "class=filas_resultados_reporte_saldos2";
		  }
					$contratos.="<div ".$clase.">".$num_contra_while."</div>";
					$contratista.="<div ".$clase.">".substr($s_contras[3],0,47)."</div>";
		}
		$insert = query_db("insert into ".$tabla_temporal." (id_us, tipo, id_item, contratos, ano, campo, usd, cop, id_campo,t2_presupuesto_id,num_item,contratista) values (".$id_usuario_reporte.", 'inicial', $id_solicitud, '$contratos','".$v_ini[1]."','".$v_ini[2]."','".$v_ini[4]."','".$v_ini[5]."','".$v_ini[3]."','".$v_ini[0]."','".$numero_solicitud."','".$contratista."')");
		$ano_solicitud_aproba.= ",".$v_ini[1];
		}
		
		
		
		
	/*Inicial*/
	/*Ampliaciones*/
	$numero_solicitud="";
	$sel_valor_inicial_sq = "select t2_presupuesto_id, ano,campo,t1_campo_id,valor_usd,valor_cop, num1, num2, num3, id_item from vista_reporte_saldos_marco_3_ampliaciones where id_item_peec_aplica = $id_solicitud group by t2_presupuesto_id, ano, campo,t1_campo_id,valor_usd,valor_cop, num1, num2, num3, id_item";
	$sel_valor_inicial = query_db($sel_valor_inicial_sq);
	while($v_ini = traer_fila_db($sel_valor_inicial)){//se selecciona el valor ingresado en la ampliacion
			if($id_ampliacion == 0){
			$id_ampliacion =$v_ini[9]; 
			}
		$contratos="";
		$contratista="";
		$numero_solicitud=numero_item_pecc($v_ini[6], $v_ini[7], $v_ini[8]);
		$sel_contratos = query_db("select consecutivo,creacion_sistema,apellido,contratista, id_contrato from vista_reporte_saldos_marco_3_ampliaciones where t2_presupuesto_id = ".$v_ini[0]." group by consecutivo, creacion_sistema, apellido, contratista, id_contrato");
		$cont = 0;
		while($s_contras = traer_fila_db($sel_contratos)){//se seleccionan los contratos relacionados por presupuesto
		if($contratos_que_viene==numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2], $s_contras[4])){
				$num_contra_while="<font color=#0000FF>".numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2], $s_contras[4])."</font>";
			}else{
				$num_contra_while=numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2], $s_contras[4]);
				}
				if($cont == 0){
		  	$clase= "class=filas_resultados";
			$cont = 1;
		  }else{
		  	$clase= "";
			$cont = 0;
		  }
				if($contratos==""){
				$contratos.="<span >".$num_contra_while."</span>";
				$contratista.="<span >".$s_contras[3]."</span>";
				}else{
					$contratos.=",<br /><span >".$num_contra_while."</span>";
					$contratista.=",<br /><span >".$s_contras[3]."</span>";
					}
		}
		$insert = query_db("insert into ".$tabla_temporal." (id_us, tipo, id_item, contratos, ano, campo, usd, cop, id_campo,t2_presupuesto_id,num_item,contratista) values (".$id_usuario_reporte.", 'ampliacion', ".$v_ini[9].", '$contratos','".$v_ini[1]."','".$v_ini[2]."','".$v_ini[4]."','".$v_ini[5]."','".$v_ini[3]."','".$v_ini[0]."','".$numero_solicitud."','".$contratista."')");
		$ano_solicitud_aproba.= ",".$v_ini[1];
		$campos_solicitud_aproba.= ",".$v_ini[3];
		
		
		}
		
		

	/*FIN Ampliaciones*/
	
	/*RECLASIFICACIONES AUMENTA VALOR*/
	$numero_solicitud="";
	$sel_valor_inicial_sq = "select t2_presupuesto_id, ano,campo,t1_campo_id,valor_usd,valor_cop, num1, num2, num3, id_item from vista_reporte_saldos_marco_3_ampliaciones_reclasificacion where id_item_peec_aplica = $id_solicitud group by t2_presupuesto_id, ano, campo,t1_campo_id,valor_usd,valor_cop, num1, num2, num3, id_item";
	$sel_valor_inicial = query_db($sel_valor_inicial_sq);
	while($v_ini = traer_fila_db($sel_valor_inicial)){//se selecciona el valor ingresado en la ampliacion
		$contratos="";
		$contratista="";
		$numero_solicitud=numero_item_pecc($v_ini[6], $v_ini[7], $v_ini[8]);
		$sel_contratos = query_db("select consecutivo,creacion_sistema,apellido,contratista, id_contrato from vista_reporte_saldos_marco_3_ampliaciones_reclasificacion where t2_presupuesto_id = ".$v_ini[0]." group by consecutivo, creacion_sistema, apellido, contratista, id_contrato");
		$cont = 0;
		while($s_contras = traer_fila_db($sel_contratos)){//se seleccionan los contratos relacionados por presupuesto
		if($contratos_que_viene==numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2], $s_contras[4])){
				$num_contra_while="<font color=#0000FF>".numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2], $s_contras[4])."</font>";
			}else{
				$num_contra_while=numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2], $s_contras[4]);
				}
				if($cont == 0){
		  	$clase= "class=filas_resultados";
			$cont = 1;
		  }else{
		  	$clase= "";
			$cont = 0;
		  }
				if($contratos==""){
				$contratos.="<span >".$num_contra_while."</span>";
				$contratista.="<span >".$s_contras[3]."</span>";
				}else{
					$contratos.=",<br /><span >".$num_contra_while."</span>";
					$contratista.=",<br /><span >".$s_contras[3]."</span>";
					}
		}
		$insert = query_db("insert into ".$tabla_temporal." (id_us, tipo, id_item, contratos, ano, campo, usd, cop, id_campo,t2_presupuesto_id,num_item,contratista) values (".$id_usuario_reporte.", 'reclasificacion', ".$v_ini[9].", '$contratos','".$v_ini[1]."','".$v_ini[2]."','".$v_ini[4]."','".$v_ini[5]."','".$v_ini[3]."','".$v_ini[0]."','".$numero_solicitud."','".$contratista."')");
		}
	/*RECLASIFICACIONES AUMENTA VALOR*/
	
	/*OTROS TIPOS DE PROCESO*/
	if($tipo == "saldos"){
	$numero_solicitud="";
	$sel_contratos = query_db("select id from t7_contratos_contrato where id_item = ".$id_solicitud." and estado <> 50");
	$ids_contras = "";
	while($sel_contras = traer_fila_db($sel_contratos)){
		$ids_contras.= ",".$sel_contras[0]; 
		}
		$ids_contras = "0".$ids_contras;
		$ids_contras = str_replace("0,","", $ids_contras);//es fundamental quitar el 0 para que no traiga datos errdos
		
		global $pi2, $g13;
 $sel_valor_inicial_sq = "select $pi2.id_item, num1,num2,num3 from $pi2 inner join $g13 on $g13.t1_tipo_proceso_id = $pi2.t1_tipo_proceso_id where  $pi2.t1_tipo_proceso_id in (9,10,11,15) and (id_solicitud_relacionada in (".$id_solicitud.") or contrato_id in (".$ids_contras.") or id_item_peec_aplica in (".$id_solicitud."))  and $pi2.estado <> 33";
	
	$sel_valor_inicial = query_db($sel_valor_inicial_sq);
	while($v_ini = traer_fila_db($sel_valor_inicial)){
		$numero_solicitud=numero_item_pecc($v_ini[1], $v_ini[2], $v_ini[3]);
		$insert = query_db("insert into ".$tabla_temporal." (id_us, tipo, id_item, num_item) values (".$id_usuario_reporte.", 'otros','".$v_ini[0]."', '".$numero_solicitud."' )");
		}
	}
	/*FIN OTROS TIPOS DE PROCESO*/
	
	
	/*RECLASIFICACION RESTA VALOR*/
	$numero_solicitud="";
	$sel_valor_inicial_sq = "select t2_presupuesto_id, ano,campo,t1_campo_id,valor_usd,valor_cop, num1, num2, num3, id_item,id_item_ots_aplica from vista_reporte_saldos_marco_4_ots_reclasificacion where id_item_peec_aplica = $id_solicitud group by t2_presupuesto_id, ano, campo,t1_campo_id,valor_usd,valor_cop, num1, num2, num3, id_item,id_item_ots_aplica";
		
	$sel_valor_inicial = query_db($sel_valor_inicial_sq);
	while($v_ini = traer_fila_db($sel_valor_inicial)){//se selecciona el valor ingresado en la ampliacion
		$contratos="";
		$contratista="";
		$numero_solicitud=numero_item_pecc($v_ini[6], $v_ini[7], $v_ini[8]);
		$sel_contratos = query_db("select consecutivo,creacion_sistema,apellido,contratista, id_contrato from vista_reporte_saldos_marco_4_ots_reclasificacion where t2_presupuesto_id = ".$v_ini[0]." group by consecutivo, creacion_sistema, apellido, contratista, id_contrato");
		while($s_contras = traer_fila_db($sel_contratos)){//se seleccionan los contratos relacionados por presupuesto
				if($contratos==""){
				$contratos.=numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2],$s_contras[4]);
				$contratista.=substr($s_contras[3], 0, 47);
				}else{
					$contratos.=",<br />".numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2],$s_contras[4]);
					$contratista.=",<br />".substr($s_contras[3],0,47);
					}
		}
		$insert = query_db("insert into ".$tabla_temporal." (id_us, tipo, id_item, contratos, ano, campo, usd, cop, id_campo,t2_presupuesto_id,num_item,contratista,id_item_ots_aplica) values (".$id_usuario_reporte.", 'ots', '".$v_ini[9]."', '$contratos','".$v_ini[1]."','".$v_ini[2]."','".$v_ini[4]."','".$v_ini[5]."','".$v_ini[3]."','".$v_ini[0]."','".$numero_solicitud."','".$contratista."','".$v_ini[10]."')");
		}
	/*FIN RECLASIFICACION RESTA VALOR*/	
	
	/*OTS*/
	if($tipo!="ejecucion_reporte_valor"){
	$numero_solicitud="";
	$sel_valor_inicial_sq = "select t2_presupuesto_id, ano,campo,t1_campo_id,valor_usd,valor_cop, num1, num2, num3, id_item,id_item_ots_aplica from vista_reporte_saldos_marco_4_ots where id_item_peec_aplica = $id_solicitud group by t2_presupuesto_id, ano, campo,t1_campo_id,valor_usd,valor_cop, num1, num2, num3, id_item,id_item_ots_aplica";
	$sel_valor_inicial = query_db($sel_valor_inicial_sq);
	while($v_ini = traer_fila_db($sel_valor_inicial)){//se selecciona el valor ingresado en la ampliacion
		$contratos="";
		$contratista="";
		$numero_solicitud=numero_item_pecc($v_ini[6], $v_ini[7], $v_ini[8]);
		$sel_contratos = query_db("select consecutivo,creacion_sistema,apellido,contratista, id_contrato from vista_reporte_saldos_marco_4_ots where t2_presupuesto_id = ".$v_ini[0]." group by consecutivo, creacion_sistema, apellido, contratista, id_contrato");
		while($s_contras = traer_fila_db($sel_contratos)){//se seleccionan los contratos relacionados por presupuesto
				if($contratos==""){
				$contratos.=numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2],$s_contras[4]);
				$contratista.=substr($s_contras[3], 0, 47);
				}else{
					$contratos.=",<br />".numero_item_pecc_contrato_antes_formato("C",$s_contras[1],$s_contras[0],$s_contras[2],$s_contras[4]);
					$contratista.=",<br />".substr($s_contras[3],0,47);
					}
		}
		$insert = query_db("insert into ".$tabla_temporal." (id_us, tipo, id_item, contratos, ano, campo, usd, cop, id_campo,t2_presupuesto_id,num_item,contratista,id_item_ots_aplica) values (".$id_usuario_reporte.", 'ots', ".$v_ini[9].", '$contratos','".$v_ini[1]."','".$v_ini[2]."','".$v_ini[4]."','".$v_ini[5]."','".$v_ini[3]."','".$v_ini[0]."','".$numero_solicitud."','".$contratista."','".$v_ini[10]."')");
		}
	}
	/*OTS*/	
		
		
	/*VERIFICA SI HAY OTS A LA SOLICITUD INICIAL DE A�OS DIFERENTES PARA AGREGARLOS*/

		if($tipo == "ejecucion"){//solo para reporte de ejecucion
		

		 $sql_ots = "select  id_item_ots_aplica, ano, campo, id_campo, contratos  from t2_reporte_marco_temporal_ejecuciones_excel where id_us= '".$id_usuario_reporte."' and tipo in ('ots')  group by id_item_ots_aplica, ano, campo, id_campo, contratos";
		
		$sql_sql_ots = query_db($sql_ots);
		
		while($v_sql_ots = traer_fila_db($sql_sql_ots)){
		
			if($v_sql_ots[0] > 0){
				$id_ampli_recla_inicial = $v_sql_ots[0];
				}else{
					$id_ampli_recla_inicial = $id_solicitud;
					}
					
					$contrato_ot_sin_espa = str_replace(" ","",$v_sql_ots[4]);
					
					
				
$sql_si_tiene_linea = query_db("select count(*) from t2_reporte_marco_temporal_ejecuciones_excel where id_item = ".$id_ampli_recla_inicial." and ano = ".$v_sql_ots[1]." and id_campo = ".$v_sql_ots[3]." and REPLACE(contratos, ' ', '') like '%".$contrato_ot_sin_espa."%' and id_us = ".$id_usuario_reporte."");

					$sele_si_tiene_ano_campo = traer_fila_row($sql_si_tiene_linea);
					
					
					if($sele_si_tiene_ano_campo[0]==0){
	$sele_ampli_recla_in = traer_fila_row(query_db("select tipo, num_item from t2_reporte_marco_temporal_ejecuciones_excel where id_item = ".$id_ampli_recla_inicial));
	

					$insert = "insert into t2_reporte_marco_temporal_ejecuciones_excel (id_us, tipo, id_item, contratos, ano, campo, usd, cop, id_campo,t2_presupuesto_id,num_item,contratista) values (".$id_usuario_reporte.", '".$sele_ampli_recla_in[0]."', '".$id_ampli_recla_inicial."', '".$v_sql_ots[4]."','".$v_sql_ots[1]."','".$v_sql_ots[2]."',0,0,'".$v_sql_ots[3]."','0','".$sele_ampli_recla_in[1]."','')";
		$insert = query_db($insert);
						}
			}
		
	
		}
		/*FIN VERIFICA SI HAY OTS A LA SOLICITUD INICIAL DE A�OS DIFERENTES PARA AGREGARLOS*/
		
		
	}

function anos_consulta_ulti_numeros($por_defecto){
	global $fecha;
	$fecha_explode = explode("-",$fecha);
	$ano_actual = $fecha_explode[0];
	$ano_actual = $ano_actual[2].$ano_actual[3];
	
	for($i = 13; $i <=$ano_actual; $i ++ ){
		
		if($por_defecto == $i){
			$seleccionar = "selected='selected'";
		}else{
			$seleccionar="";
			}
        
		$text_funcion = $text_funcion."<option value='".$i."' $seleccionar>".$i."</option>";
		}
		return $text_funcion;
	}


function solo_anos_actual($incluye_actual){
	//solo se utiliza para seleccionar el a�o del pecc en la creacion
	global $fecha;
	$fecha_explode = explode("-",$fecha);
	$ano_actual = $fecha_explode[0];

if($incluye_actual == $ano_actual){
			$seleccionar = "selected='selected'";
		}else{
			$seleccionar = "";
			}
			
			
			
		//$text_funcion = "<option value='2017' ".$seleccionar.">2017</option>"."<option value='".$ano_actual."' ".$seleccionar.">".$ano_actual."</option>";
		$text_funcion = "<option value='".$ano_actual."' ".$seleccionar.">".$ano_actual."</option>";

		return $text_funcion;
	}

function anos_consulta($incluye_actual){
	global $fecha;
	$fecha_explode = explode("-",$fecha);
	$ano_actual = $fecha_explode[0];
	if($incluye_actual=="NO"){
		$ano_actual = $ano_actual -1;
		}
	for($i = $ano_actual; $i >=2012; $i -- ){
		$text_funcion = $text_funcion."<option value='".$i."'>".$i."</option>";
		}
		return $text_funcion;
	}
	
function anos_consulta_defecto_pecc($por_defecto){
	global $fecha;
	$fecha_explode = explode("-",$fecha);
	$ano_actual = $fecha_explode[0]+1;
	
	for($i = 2016; $i <=$ano_actual; $i ++ ){
	
		if($por_defecto == $i){
			$seleccionar = "selected='selected'";
		}else{
			$seleccionar = "";
			}
		
		$text_funcion = $text_funcion."<option value='".$i."' $seleccionar>".$i."</option>";
		}
		return $text_funcion;
	}
	
function anos_consulta_defecto($por_defecto){
	global $fecha;
	$fecha_explode = explode("-",$fecha);
	$ano_actual = $fecha_explode[0];
	
	for($i = 2013; $i <=$ano_actual; $i ++ ){
	
		if($por_defecto == $i){
			$seleccionar = "selected='selected'";
		}else{
			$seleccionar = "";
			}
		
		$text_funcion = $text_funcion."<option value='".$i."' $seleccionar>".$i."</option>";
		}
		return $text_funcion;
	}
function anos_presupuesto($por_defecto){
	global $fecha;
	$fecha_explode = explode("-",$fecha);
	$ano_actual = $fecha_explode[0];
	$text_funcion = "<option value='".$ano_actual."'>".$ano_actual."</option>";
	for($i = 1; $i <=10; $i ++ ){
		$ano_valor = $ano_actual + $i;
		
		if($por_defecto == $ano_valor){
			$seleccionar = "selected='selected'";
		}else{
			$seleccionar = "";
			}
		
		$text_funcion = $text_funcion."<option value='".$ano_valor."' $seleccionar>".$ano_valor."</option>";
		}
		return $text_funcion;
	}

function agrega_firmas_urna_virtual($id_item, $etapa_funci, $dias){
if($id_item > 0){
	global $fecha;
	if($dias == ""){
		$dias=0;
		}
agrega_gestion_pecc($id_item, $etapa_funci,$fecha, $dias);

if($etapa_funci == "12.1"){
	$etapa_siguiente = "12.2";
	}
if($etapa_funci == "12.2"){
	$etapa_siguiente = "13";
	}

$updta_estado = query_db("update t2_item_pecc set estado = '".$etapa_siguiente."' where id_item = ".$id_item);

}

	}
	
function encabezado_contrato_tarifas($id_contrato_tarifas){
global $v_t_9, $v_t_2;
	$buscar_datos_contrato = traer_fila_row(query_db("select id_contrato from t6_tarifas_contratos where tarifas_contrato_id =". $id_contrato_tarifas));
	$id_contrato_modulo = $buscar_datos_contrato[0];
	$busca_datos_contrato = traer_fila_row(query_db("select CAST(objeto AS TEXT), contratista,  tipo_bien_servicio,  monto_usd, monto_cop, fecha_inicio, vigencia_mes, gerente, especialista, t1_tipo_documento_id from t7_contratos_contrato where id=".$id_contrato_modulo));
	$busca_reembolsable = traer_fila_row(query_db("select t6_tarifas_reembosables1_contrato_id, porcentaje_administracion, nombre_administrador, fecha_creacion, estado from $v_t_9 where t6_tarifas_contratos_id = $id_contrato_tarifas  and estado = 1 and porcentaje_administracion >=0"));
	
	$busca_tarifas_ipc = traer_fila_row(query_db("select count(*) from t6_tarifas_ipc_contrato where t6_tarifas_contratos_id = $id_contrato_tarifas and ipc_administracion = 1 and estado = 1 "));	
	$cuenta_descuentos = traer_fila_row(query_db("select count(*) from $v_t_2 where tarifas_contrato_id = $id_contrato_tarifas and estado = 1"));
	$busca_tarifas_uni = traer_fila_row(query_db("select count(*) from v_tarifas_con_descuentos where tarifas_contrato_id = $id_contrato_tarifas  "));	
	?>
	<table width="100%" border="0">
	  <tr>
	    <td colspan="3"><table width="100%" border="0" cellpadding="2" cellspacing="2"  class="tabla_lista_resultados">
	      <tr>
	        <td width="12%" align="right" ><strong>Proveedor:</strong></td>
	        <td width="88%" colspan="2" ><?=saca_nombre_lista("t1_proveedor",$busca_datos_contrato[1],'razon_social','t1_proveedor_id');?></td>
          </tr>
	      <tr>
	        <td align="right" valign="top" ><strong>Objeto del contrato:</strong></td>
	        <td colspan="2" ><?=$busca_datos_contrato[0];?></td>
          </tr>
        </table></td>
      </tr>
	  <tr>
	    <td width="32%" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="2"  class="tabla_lista_resultados">
	      <tr>
	        <td width="40%" align="right" valign="top" ><strong>Tipo de contrato:</strong></td>
	        <td width="60%" colspan="2" ><? if ($busca_datos_contrato[2] == "Bienes") echo "Bienes"; else echo "Servicios"; ?></td>
          </tr>
          
  <? if ($busca_datos_contrato[9] <> 2){ ?>
	      <tr>
	        <td align="right" ><strong>Valor del contrato USD$: </strong></td>
	        <td colspan="2" ><?=number_format($busca_datos_contrato[3],0)?></td>
          </tr>
	      <tr>
	        <td align="right" valign="top" ><strong>Valor del contrato COP$: </strong></td>
	        <td colspan="2" ><?=number_format($busca_datos_contrato[4],0)?></td>
          </tr>
           <?
	  }else{
		  ?>
		  <tr>
        <td align="right"  valign="top"><strong>Monto:</strong></td>
        <td colspan="2" valign="top"><strong onclick='window.parent.document.getElementById(&quot;div_carga_busca_sol&quot;).style.display=&quot;block&quot;;ajax_carga(&quot;../aplicaciones/reportes/lista_reporte_saldos.php?id_contrato=<?=$id_contrato_modulo?>&quot;,&quot;div_carga_busca_sol&quot;)' style="cursor:pointer">Ver reporte de contrato marco</strong></td>
        <tr>
	        <td align="right" valign="top" ><strong>Valor del contrato COP$: </strong></td>
	        <td colspan="2" ><?=number_format($busca_datos_contrato[4],0)?></td>
          </tr>
      </tr>
		  <?
		  }
	  ?>
          
	      <tr>
	        <td align="right" valign="top" ><strong>Fecha de inicio:</strong></td>
	        <td colspan="2" ><?=$busca_datos_contrato[5]?></td>
          </tr>
	      <tr>
	        <td align="right" valign="top" ><strong>Fecha de finalizaci&oacute;n:</strong></td>
	        <td colspan="2" ><?=$busca_datos_contrato[6]?></td>
          </tr>
	      
        </table></td>
	    <td width="30%" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="2"  class="tabla_lista_resultados">
        <? if(busca_tarifas_aiu($id_contrato_tarifas,4) == ""){?>
	      <tr>
	        <td width="49%" align="right" valign="top" ><strong>Aplica AIU:</strong></td>
	        <td width="51%" colspan="2" >NO</td>
          </tr>
         <? }else{?><tr><td width="51%" colspan="2" align="center" ><?=busca_tarifas_aiu($id_contrato_tarifas,4)?></td></tr><? }
		 
		 if(busca_tarifas_convenciones($id_contrato_tarifas,4) == ""){
		 ?>
          <tr>
	        <td align="right" valign="top" ><strong>Aplica convenci&oacute;n:</strong></td>
	        <td colspan="2" >NO</td>
          </tr>
          <?
		 }else{?><tr><td width="51%" colspan="2" align="center" ><?=busca_tarifas_convenciones($id_contrato_tarifas,4)?></td></tr><? }
		  ?>
	      <tr>
	        <td align="right" valign="top" ><strong>Aplica IPC:</strong></td>
	        <td colspan="2" ><? if($busca_tarifas_ipc[0]>=1) echo "Si";	else echo "NO";
			
			?></td>
          </tr>
	     
	      <tr>
	        <td align="right" valign="top" ><strong>Aplica descuentos:</strong></td>
	        <td colspan="2" ><? 
			
			if($cuenta_descuentos[0]>=1 or $busca_tarifas_uni[0]>=1){ 
			echo "SI, ";
					if($cuenta_descuentos[0]>=1){?><a href="javascript:void(0)" onclick="ajax_carga('../aplicaciones/tarifas/detalle_descuentos.php?id_contrato=<?=$id_contrato_tarifas;?>','carga_acciones_permitidas')">Configurado por Abastecimiento</a><? }
					if($busca_tarifas_uni[0]>=1){?><a href="javascript:void(0)" onclick="ajax_carga('../aplicaciones/tarifas/detalle_descuentos.php?id_contrato=<?=$id_contrato_tarifas;?>','carga_acciones_permitidas')">Configurado por el Proveedor</a> <? }
			}else {echo "NO"; }?></td>
          </tr>
	      <tr>
	        <td align="right" valign="top" ><strong>Aplica Reembolsables:</strong></td>
	        <td colspan="2" ><? if($busca_reembolsable[0]>=1) echo "Si, administracion: ".number_format($busca_reembolsable[1],0)."%";	else echo "No";
			
			?></td>
          </tr>
	     

        </table></td>
	    <td width="38%" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="2"  class="tabla_lista_resultados">
	      
	      <tr>
	        <td width="53%" align="right" valign="top" ><strong>Gerente del contrato:</strong></td>
	        <td width="47%" colspan="2" ><?=ver_si_tiene_reemplazo($busca_datos_contrato[7]);?></td>
          </tr>
          
	      <tr>
	        <td width="53%" align="right" valign="top" ><strong>Profesional de C&amp;C asignado:</strong></td>
	        <td colspan="2" ><?=ver_si_tiene_reemplazo($busca_datos_contrato[8]);?></td>
          </tr>
         
	      <tr>
	        <td width="53%" align="right" valign="top" ><strong>Jefe del gerente de contrato:</strong></td>
	        <td colspan="2" ><?=ver_si_tiene_reemplazo(busca_jefe_area_contrato($id_contrato_tarifas));?></td>
          </tr>
	      <tr>
	        <td align="right" valign="top" >&nbsp;</td>
	        <td colspan="2" >&nbsp;</td>
          </tr>
	      <tr>
	        <td align="right" valign="top" >&nbsp;</td>
	        <td colspan="2" >&nbsp;</td>
          </tr>
	      
	      
	      
        </table></td>
      </tr>
</table>
	<?
	}


function permite_firmar_proceso_de_bienes($id_item_funct){//esta funcion lo que hace es validar si habilita o no la firma o la alerta de una solicitud de bienes la cual es traida desde SAP


	$sel_item_fun = traer_fila_row(query_db("select t1_tipo_contratacion_id, t1_tipo_proceso_id from t2_item_pecc where id_item = ".$id_item_funct));
	
	
$es_de_bienes = "NO";
	if($sel_item_fun[0] <> 1){//si es una solicitud
	
	$sel_si_desierta = traer_fila_row(query_db("SELECT count(*)  FROM t2_presupuesto as t1, t2_presupuesto_proveedor_adjudica as t2 WHERE t1.t2_item_pecc_id = ".$id_item_funct." and t1.t2_presupuesto_id = t2.t2_presupuesto_id and t1_tipo_documento_id = 4"));
		if($sel_si_desierta[0] == 0){//Si no es declarada decierta
			$es_de_bienes = "SI";
		}
		
	
		}
	
	if($sel_item_fun[1] == 16){//si es un SM
	$sel_sm_dec = traer_fila_row(query_db("SELECT   count(*) FROM dbo.t2_item_pecc INNER JOIN dbo.t2_relacion_proveedor ON dbo.t2_item_pecc.id_item = dbo.t2_relacion_proveedor.id_item WHERE        (dbo.t2_relacion_proveedor.id_proveedor = 1 and t2_item_pecc.id_item = ".$id_item_funct.")"));
	if($sel_sm_dec[0]==0){//si el sm no esta declarando decierta
		$es_de_bienes = "SI";
		
	}
	}
	
	//si es un servicio menor que se declara desierto.
	
	
	
		return $es_de_bienes;
	}
function solicitud_bienes($id_solicitud){

	$selec_tipo_contras = traer_fila_row(query_db("select count(*) from t7_contratos_contrato where id_item = ".$id_solicitud." and tipo_bien_servicio = 'Bienes'"));
	
	if($selec_tipo_contras[0] > 0){
			return "SI";
		}else{ return "NO";}
	
	
	}

function saber_si_solicitud_tiene_contratos_de_bienes($id_item_bienes){
	
	$busca_contratos_bienes = traer_fila_row(query_db("select count(*) from t7_contratos_contrato where tipo_bien_servicio = 'Bienes' and id_item = ".$id_item_bienes));
	$es_de_bienes = "NO";
	if($busca_contratos_bienes[0] > 0){ $es_de_bienes = "SI"; }
	return $es_de_bienes;
	}
	
function busca_tarifas_aiu($id_contrato_tarifa,$ubicacion)
	{
//echo "select t6_tarifas_aiu_contrato_id, aiu_administracion, nombre_administrador, fecha_creacion, estado from v_tarifas_aiu_contrato where t6_tarifas_contratos_id = $id_contrato_tarifa  and estado = 1";
			$busca_descuneto = traer_fila_row(query_db("select t6_tarifas_aiu_contrato_id, aiu_administracion, nombre_administrador, fecha_creacion, estado from v_tarifas_aiu_contrato where t6_tarifas_contratos_id = $id_contrato_tarifa  and estado = 1"));
			if($busca_descuneto[1]==1) $mustra_aiu=1;
			else $mustra_aiu=2;
			//tipo ubicacion 1= para creacion, 2 para listas, 3 para menu
			if( ($ubicacion==1) && ($mustra_aiu==1) ){//if 1
				
			$imp = "<table width='100%' border='0' cellspacing='2' cellpadding='2'>
  <tr>
    <td class='letra-descuentos'><strong>ATENCION: Las  tarifas que esta apunto de registrar deben incluir el AIU.</strong></td>
  </tr>
</table>";
			} //if 1

			if( ($ubicacion==2) && ($mustra_aiu==1) ){//if 2
				
			$imp = "<table width='100%' border='0' cellspacing='2' cellpadding='2'>
  <tr>
    <td class='letra-descuentos'><strong>ATENCION: Estas tarifas ya tiene AIU incluido.</strong></td>
  </tr>
</table>";
			} //if 2

			if( ($ubicacion==3) && ($mustra_aiu==1) ){//if 2
				
			$imp = $mustra_aiu;
			} //if 2
			
			if( ($ubicacion==4) && ($mustra_aiu==1) ){//if 4
				
			$imp = "<strong class='letra-descuentos'>ATENCION: El contrato aplica AIU.</strong>";
			} //if 4
			
			return $imp;
			
		}
		
function busca_tarifas_convenciones($id_contrato_tarifa,$ubicacion)
	{
//echo "select t6_tarifas_convencion_contrato_id, convencion_administracion, nombre_administrador, fecha_creacion, estado from v_tarifas_conveciones_contrato where t6_tarifas_contratos_id = $id_contrato_tarifa  and estado = 1";
			$busca_descuneto = traer_fila_row(query_db("select t6_tarifas_convencion_contrato_id, convencion_administracion, nombre_administrador, fecha_creacion, estado from v_tarifas_conveciones_contrato where t6_tarifas_contratos_id = $id_contrato_tarifa  and estado = 1"));
			if($busca_descuneto[1]==1) $mustra_aiu=1;
			else $mustra_aiu=2;
			//tipo ubicacion 1= para creacion, 2 para listas, 3 para menu
			if( ($ubicacion==1) && ($mustra_aiu==1) ){//if 1
				
			$imp = "<table width='100%' border='0' cellspacing='2' cellpadding='2'>
  <tr>
    <td class='letra-descuentos'><strong>ATENCION: El contrato tiene habilitada la opci&oacute;n de modificaci&oacute;n de tarifas por Convenci&oacute;n.</strong></td>
  </tr>
</table>";
			} //if 1

			if( ($ubicacion==2) && ($mustra_aiu==1) ){//if 2
				
			$imp = "<table width='100%' border='0' cellspacing='2' cellpadding='2'>
  <tr>
    <td class='letra-descuentos'><strong>ATENCION: Estas tarifas ya tiene la convencion incluida.</strong></td>
  </tr>
</table>";
			} //if 2

			if( ($ubicacion==3) && ($mustra_aiu==1) ){//if 2
				
			$imp = $mustra_aiu;
			} //if 2
			
			if( ($ubicacion==4) && ($mustra_aiu==1) ){//if 4
				
			$imp = "<strong class='letra-descuentos'>ATENCION: El contrato aplica convenci&oacute;n.</strong>";
			} //if 4
			
			return $imp;
			
		}		
function busca_jefe_area_contrato_id_contrato_mc($id_contrato){ //solo para id del modulo de contratos. modulo de tarifas.

	
	$sel_contrato = traer_fila_row(query_db("select id_item, gerente from t7_contratos_contrato where id=".$id_contrato));
	$sel_item_area = traer_fila_row(query_db("select t1_area_id from t2_item_pecc where id_item=".$sel_contrato[0]));
	$id_ger_cont_fun = $sel_contrato[1];
	if($sel_contrato[1] == 63){//este if es temporal, mientras agrego una forma para que aparezcan las areas de los gerentes
	$id_area_fun = 39;
		}else{
	$id_area_fun = $sel_item_area[0];
		}
if($_SESSION["id_us_session"] == 32){

	}
/* Busca Super intendente o jefatura operacional*/	
	$sele_jefe_area = traer_fila_row(query_db("select t1.id_superintendente from tseg14_relacion_usuario_superintendente as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_superintendente = t2.us_id and t2.estado = 1 and t1.id_us = ".$id_ger_cont_fun." and t1.id_area = ".$id_area_fun." and t3.t1_area_id = t1.id_area and t3.estado = 1"));//busca super intendentes y area
	if($sele_jefe_area[0]=="" or $sele_jefe_area[0]==0 ){ // Si no encuentra la relacion gerente area superintendente, entonces solo busca gerente super intendente.
		$sele_jefe_area = traer_fila_row(query_db("select t1.id_superintendente  from tseg14_relacion_usuario_superintendente as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_superintendente = t2.us_id and t2.estado = 1 and t1.id_us = ".$id_ger_cont_fun." and t3.t1_area_id = t1.id_area and t3.estado =1"));
	}
/* Busca Super intendente o jefatura operacional*/		
	
/*Busca Jefe de Area si no encuentra super*/	
if($sele_jefe_area[0] == ""){	

	$sele_jefe_area = traer_fila_row(query_db("select t1.id_jefe_area from tseg13_relacion_usuario_jefe as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_jefe_area = t2.us_id and t2.estado = 1 and t1.id_us = ".$sel_contrato[1]." and t1.id_area = ".$sel_item_area[0]." and t1.id_area = t3.t1_area_id and t3.estado = 1"));
	if($sele_jefe_area[0]=="" or $sele_jefe_area[0]==0 ){ // Si no encuentra la relacion gerente area jefe, entonces solo busca gerente jefe.
		$sele_jefe_area = traer_fila_row(query_db("select t1.id_jefe_area  from tseg13_relacion_usuario_jefe as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_jefe_area = t2.us_id and t2.estado = 1 and t1.id_us = ".$sel_contrato[1]." and t3.t1_area_id = t1.id_area and t3.estado =1"));
	}
}
/*Busca Jefe de Area si no encuentra super*/	

/*Busca vice o director si no encuentra super ni gerente*/	
if($sele_jefe_area[0] == ""){	//vicepresidente

	$sele_jefe_area = traer_fila_row(query_db("select t1.id_vicepresidente from tseg15_relacion_usuario_vicepresidente as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_vicepresidente = t2.us_id and t2.estado = 1 and t1.id_us = ".$sel_contrato[1]." and t1.area = ".$sel_item_area[0]." and t1.area = t3.t1_area_id and t3.estado = 1"));
	if($sele_jefe_area[0]=="" or $sele_jefe_area[0]==0 ){ // Si no encuentra la relacion gerente area jefe, entonces solo busca gerente jefe.
		$sele_jefe_area = traer_fila_row(query_db("select t1.id_vicepresidente  from tseg15_relacion_usuario_vicepresidente as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_vicepresidente = t2.us_id and t2.estado = 1 and t1.id_us = ".$sel_contrato[1]." and t3.t1_area_id = t1.area and t3.estado =1"));
	}
}
if($sele_jefe_area[0] == ""){	//director
	$sele_jefe_area = traer_fila_row(query_db("select t1.id_director from tseg15_relacion_usuario_director as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_director = t2.us_id and t2.estado = 1 and t1.us_id = ".$sel_contrato[1]." and t1.id_area = ".$sel_item_area[0]." and t1.area = t3.t1_area_id and t3.estado = 1"));
	if($sele_jefe_area[0]=="" or $sele_jefe_area[0]==0 ){ // Si no encuentra la relacion gerente area jefe, entonces solo busca gerente jefe.
		$sele_jefe_area = traer_fila_row(query_db("select t1.id_director  from tseg15_relacion_usuario_director as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_director = t2.us_id and t2.estado = 1 and t1.us_id = ".$sel_contrato[1]." and t3.t1_area_id = t1.id_area and t3.estado =1"));
	}
}

/*Busca vice o director si no encuentra super ni gerente*/		

/*Busca Presidente*/	
if($sele_jefe_area[0] == ""){	
	$sele_jefe_area[0] = 18428;
}
/*Busca Presidente*/	

	
	
	
 return $sele_jefe_area[0];		


}

function busca_jefe_area_servicio_menor($id_item, $id_solicitante){ //solo para id del modulo de contratos. modulo de tarifas.
	
	//$sel_contrato = traer_fila_row(query_db("select id_item, gerente from t7_contratos_contrato where id=".$id_item));
	$sel_contrato=Array();
	$sel_contrato[0]=$id_item;
	$sel_contrato[1]=$id_solicitante;
	$sel_item_area = traer_fila_row(query_db("select t1_area_id from t2_item_pecc where id_item=".$sel_contrato[0]));
	$id_ger_cont_fun = $sel_contrato[1];
	if($sel_contrato[1] == 63){//este if es temporal, mientras agrego una forma para que aparezcan las areas de los gerentes
	$id_area_fun = 39;
		}else{
	$id_area_fun = $sel_item_area[0];
		}
if($_SESSION["id_us_session"] == 32){

	}
/* Busca Super intendente o jefatura operacional*/	
	$sele_jefe_area = traer_fila_row(query_db("select t1.id_superintendente from tseg14_relacion_usuario_superintendente as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_superintendente = t2.us_id and t2.estado = 1 and t1.id_us = ".$id_ger_cont_fun." and t1.id_area = ".$id_area_fun." and t3.t1_area_id = t1.id_area and t3.estado = 1"));//busca super intendentes y area
	if($sele_jefe_area[0]=="" or $sele_jefe_area[0]==0 ){ // Si no encuentra la relacion gerente area superintendente, entonces solo busca gerente super intendente.
		$sele_jefe_area = traer_fila_row(query_db("select t1.id_superintendente  from tseg14_relacion_usuario_superintendente as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_superintendente = t2.us_id and t2.estado = 1 and t1.id_us = ".$id_ger_cont_fun." and t3.t1_area_id = t1.id_area and t3.estado =1"));
	}
/* Busca Super intendente o jefatura operacional*/		
	
/*Busca Jefe de Area si no encuentra super*/	
if($sele_jefe_area[0] == ""){	

	$sele_jefe_area = traer_fila_row(query_db("select t1.id_jefe_area from tseg13_relacion_usuario_jefe as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_jefe_area = t2.us_id and t2.estado = 1 and t1.id_us = ".$sel_contrato[1]." and t1.id_area = ".$sel_item_area[0]." and t1.id_area = t3.t1_area_id and t3.estado = 1"));
	if($sele_jefe_area[0]=="" or $sele_jefe_area[0]==0 ){ // Si no encuentra la relacion gerente area jefe, entonces solo busca gerente jefe.
		$sele_jefe_area = traer_fila_row(query_db("select t1.id_jefe_area  from tseg13_relacion_usuario_jefe as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_jefe_area = t2.us_id and t2.estado = 1 and t1.id_us = ".$sel_contrato[1]." and t3.t1_area_id = t1.id_area and t3.estado =1"));
	}
}
/*Busca Jefe de Area si no encuentra super*/	

/*Busca vice o director si no encuentra super ni gerente*/	
if($sele_jefe_area[0] == ""){	//vicepresidente

	$sele_jefe_area = traer_fila_row(query_db("select t1.id_vicepresidente from tseg15_relacion_usuario_vicepresidente as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_vicepresidente = t2.us_id and t2.estado = 1 and t1.id_us = ".$sel_contrato[1]." and t1.area = ".$sel_item_area[0]." and t1.area = t3.t1_area_id and t3.estado = 1"));
	if($sele_jefe_area[0]=="" or $sele_jefe_area[0]==0 ){ // Si no encuentra la relacion gerente area jefe, entonces solo busca gerente jefe.
		$sele_jefe_area = traer_fila_row(query_db("select t1.id_vicepresidente  from tseg15_relacion_usuario_vicepresidente as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_vicepresidente = t2.us_id and t2.estado = 1 and t1.id_us = ".$sel_contrato[1]." and t3.t1_area_id = t1.area and t3.estado =1"));
	}
}
if($sele_jefe_area[0] == ""){	//director
	$sele_jefe_area = traer_fila_row(query_db("select t1.id_director from tseg15_relacion_usuario_director as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_director = t2.us_id and t2.estado = 1 and t1.us_id = ".$sel_contrato[1]." and t1.id_area = ".$sel_item_area[0]." and t1.area = t3.t1_area_id and t3.estado = 1"));
	if($sele_jefe_area[0]=="" or $sele_jefe_area[0]==0 ){ // Si no encuentra la relacion gerente area jefe, entonces solo busca gerente jefe.
		$sele_jefe_area = traer_fila_row(query_db("select t1.id_director  from tseg15_relacion_usuario_director as t1, t1_us_usuarios as t2, t1_area as t3   , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_director = t2.us_id and t2.estado = 1 and t1.us_id = ".$sel_contrato[1]." and t3.t1_area_id = t1.id_area and t3.estado =1"));
	}
}

/*Busca vice o director si no encuentra super ni gerente*/		

/*Busca Presidente*/	
if($sele_jefe_area[0] == ""){	
	$sele_jefe_area[0] = 18428;
}
/*Busca Presidente*/	

	
	
	
 return $sele_jefe_area[0];		


}

function busca_jefe_area_contrato($id_contrato_tarifas){ 

	$sel_tarifas_contrato = traer_fila_row(query_db("select id_contrato from t6_tarifas_contratos where tarifas_contrato_id = ".$id_contrato_tarifas));
	$sel_contrato = traer_fila_row(query_db("select id_item, gerente from t7_contratos_contrato where id=".$sel_tarifas_contrato[0]));
	$sel_item_area = traer_fila_row(query_db("select t1_area_id from t2_item_pecc where id_item=".$sel_contrato[0]));
	$id_ger_cont_fun = $sel_contrato[1];
	if($sel_contrato[1] == 63){//este if es temporal, mientras agrego una forma para que aparezcan las areas de los gerentes
	$id_area_fun = 39;
		}else{
	$id_area_fun = $sel_item_area[0];
		}
if($_SESSION["id_us_session"] == 32){

	}
/* Busca Super intendente o jefatura operacional*/	
	$sele_jefe_area = traer_fila_row(query_db("select t1.id_superintendente from tseg14_relacion_usuario_superintendente as t1, t1_us_usuarios as t2, t1_area as t3  , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and t1.id_superintendente = t2.us_id and t2.estado = 1 and t1.id_us = ".$id_ger_cont_fun." and t1.id_area = ".$id_area_fun." and t3.t1_area_id = t1.id_area and t3.estado = 1"));//busca super intendentes y area
	if($sele_jefe_area[0]=="" or $sele_jefe_area[0]==0 ){ // Si no encuentra la relacion gerente area superintendente, entonces solo busca gerente super intendente.
		$sele_jefe_area = traer_fila_row(query_db("select t1.id_superintendente  from tseg14_relacion_usuario_superintendente as t1, t1_us_usuarios as t2, t1_area as t3  , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and  t1.id_superintendente = t2.us_id and t2.estado = 1 and t1.id_us = ".$id_ger_cont_fun." and t3.t1_area_id = t1.id_area and t3.estado =1"));
	}
/* Busca Super intendente o jefatura operacional*/		
	
/*Busca Jefe de Area si no encuentra super*/	
if($sele_jefe_area[0] == ""){	

	$sele_jefe_area = traer_fila_row(query_db("select t1.id_jefe_area from tseg13_relacion_usuario_jefe as t1, t1_us_usuarios as t2, t1_area as t3  , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and t1.id_jefe_area = t2.us_id and t2.estado = 1 and t1.id_us = ".$sel_contrato[1]." and t1.id_area = ".$sel_item_area[0]." and t1.id_area = t3.t1_area_id and t3.estado = 1"));
	if($sele_jefe_area[0]=="" or $sele_jefe_area[0]==0 ){ // Si no encuentra la relacion gerente area jefe, entonces solo busca gerente jefe.
		$sele_jefe_area = traer_fila_row(query_db("select t1.id_jefe_area  from tseg13_relacion_usuario_jefe as t1, t1_us_usuarios as t2, t1_area as t3  , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and t1.id_jefe_area = t2.us_id and t2.estado = 1 and t1.id_us = ".$sel_contrato[1]." and t3.t1_area_id = t1.id_area and t3.estado =1"));
	}
}
/*Busca Jefe de Area si no encuentra super*/	

/*Busca vice o director si no encuentra super ni gerente*/	
if($sele_jefe_area[0] == ""){	//vicepresidente

	$sele_jefe_area = traer_fila_row(query_db("select t1.id_vicepresidente from tseg15_relacion_usuario_vicepresidente as t1, t1_us_usuarios as t2, t1_area as t3  , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and t1.id_vicepresidente = t2.us_id and t2.estado = 1 and t1.id_us = ".$sel_contrato[1]." and t1.area = ".$sel_item_area[0]." and t1.area = t3.t1_area_id and t3.estado = 1"));
	if($sele_jefe_area[0]=="" or $sele_jefe_area[0]==0 ){ // Si no encuentra la relacion gerente area jefe, entonces solo busca gerente jefe.
		$sele_jefe_area = traer_fila_row(query_db("select t1.id_vicepresidente  from tseg15_relacion_usuario_vicepresidente as t1, t1_us_usuarios as t2, t1_area as t3  , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and t1.id_vicepresidente = t2.us_id and t2.estado = 1 and t1.id_us = ".$sel_contrato[1]." and t3.t1_area_id = t1.area and t3.estado =1"));
	}
}
if($sele_jefe_area[0] == ""){	//director
	$sele_jefe_area = traer_fila_row(query_db("select t1.id_director from tseg15_relacion_usuario_director as t1, t1_us_usuarios as t2, t1_area as t3  , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and t1.id_director = t2.us_id and t2.estado = 1 and t1.us_id = ".$sel_contrato[1]." and t1.id_area = ".$sel_item_area[0]." and t1.area = t3.t1_area_id and t3.estado = 1"));
	if($sele_jefe_area[0]=="" or $sele_jefe_area[0]==0 ){ // Si no encuentra la relacion gerente area jefe, entonces solo busca gerente jefe.
		$sele_jefe_area = traer_fila_row(query_db("select t1.id_director  from tseg15_relacion_usuario_director as t1, t1_us_usuarios as t2, t1_area as t3  , tseg3_usuario_areas as t4 where t4.id_usuario =".$id_ger_cont_fun."  and t3.t1_area_id = t4.id_area and t1.id_director = t2.us_id and t2.estado = 1 and t1.us_id = ".$sel_contrato[1]." and t3.t1_area_id = t1.id_area and t3.estado =1"));
	}
}

/*Busca vice o director si no encuentra super ni gerente*/		

/*Busca Presidente*/	
if($sele_jefe_area[0] == ""){	
	$sele_jefe_area[0] = 18428;
}
/*Busca Presidente*/	

	
	
	
 return $sele_jefe_area[0];		


}

function tipo_bien_servicio_sin_contrato($complemento){ 
if($complemento == "B" or $complemento == "BS" or $complemento == "M"){ return " Bienes";} else { return " Servicios";} 
}

function tipo_bien_servicio_con_contrato($id_contratro_para_complemento){ 
if($id_contratro_para_complemento > 0 and $id_contratro_para_complemento != ""){ 
if($_SESSION["id_us_session"]==32){
	//echo $id_contratro_para_complemento;
	}
$sel_contrato = traer_fila_row(query_db("select tipo_bien_servicio from t7_contratos_contrato where id=".$id_contratro_para_complemento));
if($sel_contrato[0] == "Bienes" or $sel_contrato[0] == " Bienes" or $sel_contrato[0] == "Bienes "){ return " Bienes";} else { return " Servicios";}
}
}


function comprobar_nit_en_par($nit){ 
$esta_en_par = $nit;
   //compruebo que los caracteres sean los permitidos 
   $permitidos = "0123456789-., "; 
   for ($i=0; $i<strlen($nit); $i++){ 
      if (strpos($permitidos, substr($nit,$i,1))===false){ 
         $esta_en_par = " NO esta en Par Servicios"; 
      } 
   } 

   return $esta_en_par; 
}

function trm_presupuestal($anio){
	$valor_trm =2950;
/*	if($anio == 18 or $anio == 2018){return 2300; }
	if($anio == 17 or $anio == 2017){return 2300; }
	if($anio == 16 or $anio == 2016){return 2300; }
	*/
	if($anio == 17 or $anio == 2017){$valor_trm =2950; }
	if($anio == 16 or $anio == 2016){$valor_trm =3000; }
	if($anio == 15 or $anio == 2015){$valor_trm =2300; }
	if($anio == 14 or $anio == 2014){$valor_trm =1900; }
	if($anio == 13 or $anio == 2013){$valor_trm =1780; }
	return $valor_trm;
}

function trm_actual (){
	$sel_trm_diaria = traer_fila_row(query_db("select top(1)  valor_trm_cop from t1_trm_diaria order by id desc"));
	return $sel_trm_diaria[0];
	}

function disponible_serv_menor_ano_atras($id_proveedor_funcion, $id_item_fun_actual){
	global $fecha;
	$comprometido_total_usd_sm = 0;
	$v_sm_sap = 0;
	$v_sm_no_sap = 0;
	$v_sm_item_actual = 0;
	
$fecha_menos_un_ano = strtotime ( '-1 year' , strtotime ( $fecha ) ) ; 
$fecha_menos_un_ano = date ( 'Y-m-j' , $fecha_menos_un_ano ); 
$sel_valores_sm_sap=traer_fila_row(query_db("select sum (sum_valor_usd) as valor_total from vista_servicios_menores_valores_sap where id_proveedor ='".$id_proveedor_funcion." '  and Convert(char, fecha_doc, 103) >= Convert(char, '".$fecha_menos_un_ano."', 103)"));// suma de servicios_menores		
$sel_valores_sm_no_sap=traer_fila_row(query_db("select sum (valor_usd) as valor_total from vista_servicios_menores_valores_sin_aprobar_sgpa where id_proveedor ='".$id_proveedor_funcion." ' and id_item <> ".$id_item_fun_actual." and estado not in (32,33)"));//  Valor de Las solicitudes donde esta relacionado el proveedor excepto la actual
if($id_item_fun_actual > 0){
$sel_valores_sm_item_actual=traer_fila_row(query_db("select sum (valor_usd) as valor_total from vista_servicios_menores_valores_sin_aprobar_sgpa where id_proveedor ='".$id_proveedor_funcion." ' and id_item = ".$id_item_fun_actual));//  Valor solicitud actual
}
		
		$v_sm_sap =  $sel_valores_sm_sap[0] * 1;
		$v_sm_no_sap =  $sel_valores_sm_no_sap[0] * 1;
		$v_sm_item_actual =  $sel_valores_sm_item_actual[0] * 1;		
		$comprometido_total_usd_sm = 	$v_sm_sap + $v_sm_no_sap + $v_sm_item_actual;
		$disponible_total_usd_sm = 	$_SESSION["valor_maximo_ser_menor"] - ($v_sm_sap + $v_sm_no_sap + $v_sm_item_actual);
		
		return $comprometido_total_usd_sm."*".$v_sm_sap."*".$v_sm_no_sap."*".$v_sm_item_actual."*".$disponible_total_usd_sm;


	}

function busca_area_emula($campo_bd,$bus_area){
	
	switch($bus_area){
		case 34: $areas_in = $bus_area.", 24"; break;
		case 35: $areas_in = $bus_area.", 25,20";break;
		case 36: $areas_in = $bus_area.", 22,26,32";break;
		case 37: $areas_in = $bus_area.", 6";break;
		case 38: $areas_in = $bus_area.", 21, 29";break;
		case 39: $areas_in = $bus_area.", 12";break;
		case 40: $areas_in = $bus_area.", 17";break;
		case 41: $areas_in = $bus_area.", 18";break;
		case 44: $areas_in = $bus_area.", 1";break;
		case 46: $areas_in = $bus_area.", 31";break;
		case 47: $areas_in = $bus_area.", 13";break;
		case 48: $areas_in = $bus_area.", 7";break;
		case 49: $areas_in = $bus_area.", 8";break;
		case 50: $areas_in = $bus_area.", 14";break;
		case 55: $areas_in = $bus_area.", 5";break;
		default: $areas_in = $bus_area;
		}
		$query =" and $campo_bd in ($areas_in) ";
	
		return $query;
	}

function antiguo_area_emula($bus_area){
	
	switch($bus_area){
		case 24: $areas_in = 34; break;
		case ($bus_area==25 || $bus_area == 20): $areas_in = 35;break;
		case ($bus_area == 22 || $bus_area == 26 || $bus_area == 32): $areas_in = 36;break;
		case  6: $areas_in = 37;break;
		case ($bus_area == 21 || $bus_area == 29): $areas_in = 38;break;
		case 12: $areas_in = 39;break;
		case 17: $areas_in = 40;break;
		case 18: $areas_in = 41;break;
		case  1: $areas_in = 44;break;
		case 31: $areas_in = 46;break;
		case 13: $areas_in = 47;break;
		case  7: $areas_in = 48;break;
		case  8: $areas_in = 49;break;
		case 15: $areas_in = 50;break;
		case  5: $areas_in = 55;break;
		case  53: $areas_in = 60;break;
		default: $areas_in = $bus_area;
		}
		$query = $areas_in;
	
		return $query;
	}
function aprobaciones_por_area($is_area){
	global $g1;
	$comple_rep_apro = " and t1_area_id = ".$is_area;
		$_GET["consulta"] =  "si";
	
	?>
	<table width="100%" border="<? if($_GET["consulta"] ==  "si") echo "0"; else echo "1";?>" class="tabla_lista_resultados">
      <tr bgcolor="#005395">
        <td width="16%" align="center"><font color="#FFFFFF"><strong>Norma de actos y transacciones</strong></font></td>
        <td width="18%" align="center"><font color="#FFFFFF"><strong>Proceso de Selecci&oacute;n para Bienes y Servicios</strong></font></td>
        <td width="19%" align="center"><font color="#FFFFFF"><strong>Nivel que Puede Realizar la Aprobaci&oacute;n</strong></font></td>
        <td width="47%" align="center"><font color="#FFFFFF"><strong>Nombre del Responsable Aprobaci&oacute;n</strong></font></td>
      </tr>
      <?
$separacion = "<br />";
  $sel_areas = query_db("select t1_area_id, nombre_html from t1_area where estado = 1 $comple_rep_apro order by nombre_html");
  while($s_a = traer_fila_db($sel_areas)){
	  
	  $nivel_1 = "";
	  $nivel_2 = "";
	  $nivel_3 = "";
	  $nivel_4 = "";
	  $nivel_1_us = "";
	  $nivel_2_us = "";
	  $nivel_3_us = "";
	  $nivel_4_us = "";
	  
$es_reemplazo_jefe="";
$es_reemplazo_gerente="";
$es_reemplazo_vice="";
$es_reemplazo_director="";
$es_reemplazo_presidente="";

if($s_a[0] != 44){// si es diferente a abastecimiento (no aplica ver desarrollo 57 nuevo contrato)
	  $sel_usuar_nivel_4 = query_db("select t2.us_id, t2.nombre_administrador from tseg3_usuario_areas as t1, t1_us_usuarios as t2, tseg12_relacion_usuario_rol as t3 where t1.id_area = ".$s_a[0]." and t1.id_usuario = t2.us_id and t2.us_id = t3.id_usuario and t3.id_rol_general = 23 and t2.estado = 1");
	  while ($sel_n_4 = traer_fila_db($sel_usuar_nivel_4)){
	 
	 if(cual_es_el_reemplazo($sel_n_4[0]) != $sel_n_4[0]){
		  $es_reemplazo_jefe =" <font color='#0033FF'> Reemplazo de:</font> ".saca_nombre_lista($g1,$sel_n_4[0],'nombre_administrador','us_id')."</strong>";
		 $nivel_4_us = $nivel_4_us." IV. ". saca_nombre_lista($g1,cual_es_el_reemplazo($sel_n_4[0]),'nombre_administrador','us_id').$es_reemplazo_jefe." $separacion ";
	 }else{
		  			$nivel_4_us = $nivel_4_us." IV. ". $sel_n_4[1]." $separacion ";
	 }
				 
			$nivel_4 = " IV. Jefatura $separacion ";
			$id_rol_4 = "45";
			$id_us_rol_4 = $sel_n_4[0];
		  }
		  
}
	$sel_usuar_nivel_3 = query_db("select t2.us_id, t2.nombre_administrador from tseg3_usuario_areas as t1, t1_us_usuarios as t2, tseg12_relacion_usuario_rol as t3 where t1.id_area = ".$s_a[0]." and t1.id_usuario = t2.us_id and t2.us_id = t3.id_usuario and t3.id_rol_general = 10 and t2.estado = 1");
	  while ($sel_n_3 = traer_fila_db($sel_usuar_nivel_3)){
		  
	if(cual_es_el_reemplazo($sel_n_3[0]) != $sel_n_3[0]){
		  $es_reemplazo_gerente =" <font color='#0033FF'> Reemplazo de:</font> ".saca_nombre_lista($g1,$sel_n_3[0],'nombre_administrador','us_id')."</strong>";
		 $nivel_3_us = $nivel_3_us." III. ". saca_nombre_lista($g1,cual_es_el_reemplazo($sel_n_3[0]),'nombre_administrador','us_id').$es_reemplazo_gerente." $separacion ";
	 }else{
		  			$nivel_3_us = $nivel_3_us." III. ". $sel_n_3[1]." $separacion ";
	 }
	 
		  	//$nivel_3_us = $nivel_3_us." 3. ". $sel_n_3[1]." / ";
			$nivel_3 = " III. Gerente de Area $separacion ";
			$id_rol_3 = "9";
			$id_us_rol_3 = $sel_n_4[0];
		  }
	
	$sel_usuar_nivel_2 = query_db("select t2.us_id, t2.nombre_administrador from tseg3_usuario_areas as t1, t1_us_usuarios as t2, tseg12_relacion_usuario_rol as t3 where t1.id_area = ".$s_a[0]." and t1.id_usuario = t2.us_id and t2.us_id = t3.id_usuario and t3.id_rol_general = 22 and t2.estado = 1");
	  while ($sel_n_2 = traer_fila_db($sel_usuar_nivel_2)){
	if(cual_es_el_reemplazo($sel_n_2[0]) != $sel_n_2[0]){
		  $es_reemplazo_vice =" <font color='#0033FF'> Reemplazo de:</font> ".saca_nombre_lista($g1,$sel_n_2[0],'nombre_administrador','us_id')."</strong>";
		$nivel_2_us = $nivel_2_us." II. ". saca_nombre_lista($g1,cual_es_el_reemplazo($sel_n_2[0]),'nombre_administrador','us_id').$es_reemplazo_vice." $separacion ";
	 }else{
		  			$nivel_2_us = $nivel_2_us." II. ". $sel_n_2[1]." $separacion ";
	 }
		  	//$nivel_2_us = $nivel_2_us." 2. ". $sel_n_2[1]." / ";
			$nivel_2 = " II. Vicepresidencia $separacion ";
			$id_rol_2 = "20";
			$id_us_rol_2 = $sel_n_4[0];
		  }
	$sel_usuar_nivel_2 = query_db("select t2.us_id, t2.nombre_administrador from tseg3_usuario_areas as t1, t1_us_usuarios as t2, tseg12_relacion_usuario_rol as t3 where t1.id_area = ".$s_a[0]." and t1.id_usuario = t2.us_id and t2.us_id = t3.id_usuario and t3.id_rol_general = 28 and t2.estado = 1");
	  while ($sel_n_2 = traer_fila_db($sel_usuar_nivel_2)){
		  if(cual_es_el_reemplazo($sel_n_2[0]) != $sel_n_2[0]){
		  $es_reemplazo_director =" <font color='#0033FF'> Reemplazo de:</font> ".saca_nombre_lista($g1,$sel_n_2[0],'nombre_administrador','us_id')."</strong>";
		 $nivel_2_us = $nivel_2_us." II. ". saca_nombre_lista($g1,cual_es_el_reemplazo($sel_n_2[0]),'nombre_administrador','us_id').$es_reemplazo_director." $separacion ";
	 }else{
		  			$nivel_2_us = $nivel_2_us." II. ". $sel_n_2[1]." $separacion ";
	 }
		  //	$nivel_2_us = $nivel__us2." 2. ". $sel_n_2[1]." / ";
			$nivel_2 = " II. Director $separacion ";
			$id_rol_2 = "43";
			$id_us_rol_2 = $sel_n_4[0];
		  }
		  
	$sel_usuar_nivel_1 = query_db("select t2.us_id, t2.nombre_administrador from t1_us_usuarios as t2, tseg12_relacion_usuario_rol as t3 where t2.us_id = t3.id_usuario and t3.id_rol_general = 12 and  t2.estado = 1");
	  while ($sel_n_1 = traer_fila_db($sel_usuar_nivel_1)){
		  if(cual_es_el_reemplazo($sel_n_1[0]) != $sel_n_1[0]){
		  $es_reemplazo_presidente =" <font color='#0033FF'> Reemplazo de:</font> ".saca_nombre_lista($g1,$sel_n_1[0],'nombre_administrador','us_id')."</strong>";
		 $nivel_1_us = $nivel_1_us." I. ". saca_nombre_lista($g1,cual_es_el_reemplazo($sel_n_1[0]),'nombre_administrador','us_id').$es_reemplazo_presidente."  ";
	 }else{
		  			$nivel_1_us = $nivel_1_us." I. ". $sel_n_1[1]." ";
	 }
		//  	$nivel_1_us = $nivel_1_us." 1. ". $sel_n_1[1]."";
			$nivel_1 = " I. Presidente";
			$id_rol_1 = "48";
			$id_us_rol_1 = $sel_n_4[0];
		  }
	  
	  
	  if($color_principal == ""){
		  $bg_color_p = "#E8E8E8";
		  $color_principal = 1;
		  }else{
			  $bg_color_p = "";
			  $color_principal = "";
			  }
		$sel_valor_solicitud = traer_fila_row(query_db("select sum (eq_usd) from v_pecc_n_servicio_2 where id_item = ".$id_item_pecc));	  
	
  ?>
      <tr bgcolor="<?=$bg_color_p?>">
        <td align="center">0  &lt; 25.000</td>
        <td>Servicio Menor</td>
        <td><?=$nivel_4?>
          <?=$nivel_3?>
          <?=$nivel_2?>
          <?=$nivel_1?></td>
        <td><?=$nivel_4_us?>
          <?=$nivel_3_us?>
          <?=$nivel_2_us?>
          <?=$nivel_1_us?></td>
      </tr>
      <tr bgcolor="<?=$bg_color_p?>">
        <td align="center"> 0 &lt; 30.000</td>
        <td>Negociaci&oacute;n Directa</td>
        <td><?=$nivel_4?>
          <?=$nivel_3?>
          <?=$nivel_2?>
          <?=$nivel_1?></td>
        <td><?=$nivel_4_us?>
          <?=$nivel_3_us?>
          <?=$nivel_2_us?>
          <?=$nivel_1_us?></td>
      </tr>
      <? 
	  
			
	?>
      <tr bgcolor="<?=$bg_color_p?>">
        <td align="center"> 30.000 &lt; 40.000 </td>
        <td>Negociaci&oacute;n Directa</td>
        <td><?=$nivel_3?>
          <?=$nivel_2?>
          <?=$nivel_1?></td>
        <td><?=$nivel_3_us?>
          <?=$nivel_2_us?>
          <?=$nivel_1_us?></td>
      </tr>
      <tr bgcolor="<?=$bg_color_p?>">
        <td align="center"> 40.000 &lt;  200.000</td>
        <td>Negociaci&oacute;n Directa</td>
        <td><?=$nivel_2?>
          <?=$nivel_1?></td>
        <td><?=$nivel_2_us?>
          <?=$nivel_1_us?></td>
      </tr>
      <tr bgcolor="<?=$bg_color_p?>">
        <td align="center">&gt;   200.000</td>
        <td>Negociaci&oacute;n Directa</td>
        <td>Comit&eacute;</td>
        <td>COMITE DE CONTRATACION</td>
      </tr>
      <tr bgcolor="<?=$bg_color_p?>">
        <td align="center">0 &lt; 100.000</td>
        <td>Licitaci&oacute;n</td>
        <td><?=$nivel_4?>
          <?=$nivel_3?>
          <?=$nivel_2?>
        <?=$nivel_1?></td>
        <td><?=$nivel_4_us?>
          <?=$nivel_3_us?>
          <?=$nivel_2_us?>
        <?=$nivel_1_us?></td>
      </tr>
      <tr bgcolor="<?=$bg_color_p?>">
        <td align="center">100.000 &lt; 200.000</td>
        <td>Licitaci&oacute;n</td>
        <td><?=$nivel_3?>
          <?=$nivel_2?>
          <?=$nivel_1?></td>
        <td><?=$nivel_3_us?>
          <?=$nivel_2_us?>
          <?=$nivel_1_us?></td>
      </tr>
      <tr bgcolor="<?=$bg_color_p?>">
        <td align="center">200.000 &lt; 500.000 </td>
        <td>Licitaci&oacute;n</td>
        <td><?=$nivel_2?>
          <?=$nivel_1?></td>
        <td><?=$nivel_2_us?>
          <?=$nivel_1_us?></td>
      </tr>
      <tr bgcolor="<?=$bg_color_p?>">
        <td align="center">&gt; 500.000</td>
        <td>Licitaci&oacute;n</td>
        <td>Comit&eacute;</td>
        <td>COMITE DE CONTRATACION</td>
      </tr>
     
      <?
  }
  ?>
   <tr>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right"><font color="#0033FF">Descargar Norma de Actos y Transacciones</font></td>
      </tr>
    </table>
	<?
	}
	
function nombre_archivo_adjunto($archivo){
		$archivo = str_replace(".","_", $archivo);
		$archivo = str_replace("#","_", $archivo);
		$archivo = str_replace("*","_", $archivo);
		$archivo = str_replace(",","_", $archivo);
		$archivo = str_replace(";","_", $archivo);
		$archivo = str_replace("&","y", $archivo);
		return $archivo;
	}
	
function extencion_archivos_sgpa($archivo){
	$busca_archi = explode(".",$archivo);
	$cua = count($busca_archi);
	$extencion = $busca_archi[$cua-1]; 
	$largo = strlen($archivo);
	$comienzo = ($largo-3);
	$ext = substr($archivo, $comienzo , 3);
	
	return $extencion;
	}


function tiempos_para_solicitudes($tipo, $id, $edita){
	


	
	?>
 
	
            <?
        	$entro = 0;
			?>
           
			  <?
	global $co1, $fecha;
	if($tipo == "contrato"){
		$id_campo_aplica = " id_contrato ";
		

		
		$busca_contrato = "select aplica_garantia, t1_tipo_documento_id, creacion_sistema, recibido_abastecimiento_e, estado, aseguramiento_admin, informe_hse, id, id, garantia_seguro, gerente_por_aseguramiento from $co1 where id =". $id;
		
		
		$sql_con=traer_fila_row(query_db($busca_contrato));
		$sql_con[7]="";//esto es por que no aplican los campos aseguramiento admin ni hse
		$sql_con[8]="";//esto es por que no aplican los campos aseguramiento admin ni hse
			
		//$busca_contrato = "select id,id_item,consecutivo,objeto,nit,contratista,contacto_principal,email1,telefono1,gerente,fecha_inicio,vigencia_mes,aplica_acta_inicio,representante_legal,email2,telefono2,especialista,monto_usd,monto_cop,creacion_sistema,recibido_abastecimiento,sap,revision_legal,firma_hocol,firma_contratista,revision_poliza,legalizacion_final,estado,sap_e,revision_legal_e,firma_hocol_e,firma_contratista_e,revision_poliza_e,legalizacion_final_e,t1_tipo_documento_id,acta_socios,recibido_poliza,camara_comercio,ok_fecha,sel_representante,legalizacion_final_par,legalizacion_final_par_e,analista_deloitte,aplica_acta,recibo_poliza,fecha_informativa_e,fecha_informativa,recibido_abastecimiento_e,area_ejecucion,obs_congelado,aplica_portales,destino,aseguramiento_admin, aplica_garantia, porcentaje, en_que_momento, informe_hse, oferta_mercantil from $co1 where id =". $id;
 		
		}
		
		if($tipo == "modificacion"){
			$id_campo_aplica = " id_modificacion ";
			$busca_contrato = "select t1.id_contrato, t1.id_contrato, t1.creacion_sistema, t1.recibido_abastecimiento_e, t1.estado,t1.id_contrato, t1.id_contrato, t1.tipo_complemento, t1.tipo_otrosi, t2.garantia_seguro  from t7_contratos_complemento as t1, $co1 as t2 where t1.id_contrato = t2.id and t1.id =".$id." ";
			$sql_con=traer_fila_row(query_db($busca_contrato));
			$sel_modifica = traer_fila_row(query_db("select tipo_complemento, id_contrato, numero_otrosi from t7_contratos_complemento where id = ".$id));
			
			
			$sql_con[0]="";//esto es por que no aplican los campos aseguramiento admin ni hse
			$sql_con[1]="";//esto es por que no aplican los campos aseguramiento admin ni hse
			$sql_con[5]="";//esto es por que no aplican los campos aseguramiento admin ni hse
			$sql_con[6]="";//esto es por que no aplican los campos aseguramiento admin ni hse
			
			
		}
		
		$id_contrato_arr=$id;
	
	
	
	$campos_tabla = " id, ".$id_campo_aplica.", f_ini_creacion_sistema, f_fin_creacion_sistema, CONVERT(text,creacion_sistema_ob), f_ini_elaboracion, f_fin_elaboracion, CONVERT(text,elaboracion_ob) as elaboracion_ob, 
                         f_ini_recibido_ini_proceso, f_fin_recibido_ini_proceso, CONVERT(text,recibido_ini_proceso_ob) as recibido_ini_proceso_ob, f_ini_firma_rep_legal, f_fin_firma_rep_legal, CONVERT(text,firma_rep_legal_ob) as firma_rep_legal_ob, 
                         f_ini_capacida_contratis, f_fin_capacida_contratis, CONVERT(text,capacida_contratis_ob) as capacida_contratis_ob, f_ini_recibido_pol, f_fin_recibido_pol, CONVERT(text,recibido_pol_ob) as recibido_pol_ob, f_ini_pago_pol,
						 f_fin_pago_pol, CONVERT(text,pago_pol_ob) as pago_pol_ob, f_ini_rev_legal, f_fin_rev_legal, CONVERT(text,rev_legal_ob) as rev_legal_ob, f_ini_ver_rut, f_fin_ver_rut, CONVERT(text,ver_rut_ob) as ver_rut_ob, f_ini_rev_estrategia, f_fin_rev_estrategia, CONVERT(text,rev_estrategia_ob) as rev_estrategia_ob, 
                         f_ini_aprob_sap, f_fin_aprob_sap, CONVERT(text,aprob_sap_ob) as aprob_sap_ob, f_ini_firma_hocol, f_fin_firma_hocol, CONVERT(text,firma_hocol_ob) as firma_hocol_ob, f_ini_envio_ej_firma, f_fin_envio_ej_firma, 
                         CONVERT(text,envio_ej_firma_ob) as envio_ej_firma_ob, f_ini_inscrip_dian, f_fin_inscrip_dian, CONVERT(text,inscrip_dian_ob) as inscrip_dian_ob, f_ini_entrega_doc_contrac, f_fin_entrega_doc_contrac, CONVERT(text,entrega_doc_contrac_ob) as entrega_doc_contrac_ob, 
                         f_ini_elabora_pedido, f_fin_elabora_pedido, CONVERT(text,elabora_pedido_ob) as elabora_pedido_ob, f_ini_aproba_sap, f_fin_aproba_sap, CONVERT(text,aproba_sap_ob) as aproba_sap_ob, f_ini_entrega_doc, f_fin_entrega_doc, 
                         CONVERT(text,entrega_doc_ob) as entrega_doc_ob, f_ini_entrega_todo, f_fin_entrega_todo, CONVERT(text,entrega_todo_ob) as entrega_todo_ob, f_ini_entre_doc_cont, f_fin_entre_doc_cont, CONVERT(text,f_ini_entre_doc_cont_ob) as f_ini_entre_doc_cont_ob,f_ini_revision_polizas, f_fin_revision_polizas, CONVERT(text,revision_polizas_ob) as revision_polizas_ob, f_ini_entreg_vobo_poliz, f_fin_entreg_vobo_poliz, CONVERT(text,entreg_vobo_poliz_ob) as entreg_vobo_poliz_ob, f_ini_garantia_recibo, f_fin_garantia_recibo, CONVERT(text,garantia_recibo_ob) as garantia_recibo_ob, 
                        CONVERT(text,garantia_recibo_ob2) as garantia_recibo_ob2, f_ini_garantia_rev_leg, f_fin_garantia_rev_leg, CONVERT(text,garantia_rev_leg_ob) as garantia_rev_leg_ob, CONVERT(text,garantia_rev_leg_ob2) as garantia_rev_leg_ob2, f_ini_garantia_env_reci, f_fin_garantia_env_reci, 
                         CONVERT(text,garantia_env_reci_ob) as garantia_env_reci_ob, CONVERT(text,garantia_env_reci_ob2) as garantia_env_reci_ob2, f_ini_garantia_dili_form, f_fin_garantia_dili_form, CONVERT(text,garantia_dili_form_ob) as garantia_dili_form_ob, CONVERT(text,garantia_dili_form_ob2) as garantia_dili_form_ob2, 
                         f_ini_garantia_fir_rep, f_fin_garantia_fir_rep, CONVERT(text,garantia_fir_rep_ob) as garantia_fir_rep_ob, CONVERT(text,garantia_fir_rep_ob2) as garantia_fir_rep_ob2, f_ini_garantia_en_cont_for, f_fin_garantia_en_cont_for, CONVERT(text,garantia_en_cont_for_ob) as garantia_en_cont_for_ob, CONVERT(text,garantia_en_cont_for_ob2) as garantia_en_cont_for_ob2";
						 
$campos_tabla.=", CONVERT(text, elaboracion_ob2) as elaboracion_ob2,
CONVERT(text, recibido_ini_proceso_ob2) as recibido_ini_proceso_ob2,
CONVERT(text, firma_rep_legal_ob2) as firma_rep_legal_ob2,
CONVERT(text, capacida_contratis_ob2) as capacida_contratis_ob2,
CONVERT(text, recibido_pol_ob2) as recibido_pol_ob2,
CONVERT(text, pago_pol_ob2) as pago_pol_ob2,
CONVERT(text, rev_legal_ob2) as rev_legal_ob2,
CONVERT(text, ver_rut_ob2) as ver_rut_ob2,
CONVERT(text, rev_estrategia_ob2) as rev_estrategia_ob2,
CONVERT(text, aprob_sap_ob2) as aprob_sap_ob2,
CONVERT(text, firma_hocol_ob2) as firma_hocol_ob2,
CONVERT(text, envio_ej_firma_ob2) as envio_ej_firma_ob2,
CONVERT(text, inscrip_dian_ob2) as inscrip_dian_ob2,
CONVERT(text, entrega_doc_contrac_ob2) as entrega_doc_contrac_ob2,
CONVERT(text, elabora_pedido_ob2) as elabora_pedido_ob2,
CONVERT(text, aproba_sap_ob2) as aproba_sap_ob2,
CONVERT(text, entrega_doc_ob2) as entrega_doc_ob2,
CONVERT(text, entrega_todo_ob2) as entrega_todo_ob2,
CONVERT(text, f_ini_entre_doc_cont_ob2) as f_ini_entre_doc_cont_ob2,
CONVERT(text, revision_polizas_ob2) as revision_polizas_ob2,
CONVERT(text, entreg_vobo_poliz_ob2) as entreg_vobo_poliz_ob2,
f_ini_garantia_tramite, f_fin_garantia_tramite,
CONVERT(text, garantia_tramite_ob) as garantia_tramite_ob,
CONVERT(text, garantia_tramite_ob2) as garantia_tramite_ob2,
f_ini_garantia_sol_inf, f_fin_garantia_sol_inf,
CONVERT(text, garantia_sol_inf_ob) as garantia_sol_inf_ob,
CONVERT(text, garantia_sol_inf_ob2) as garantia_sol_inf_ob2,
f_ini_gar_banc, f_fin_gar_banc,
CONVERT(text, gar_banc_ob) as gar_banc_ob,
CONVERT(text, gar_banc_ob2) as gar_banc_ob2,
f_ini_garantia_legal, f_fin_garantia_legal,
CONVERT(text, garantia_legal_ob) as garantia_legal_ob,
CONVERT(text, garantia_legal_ob2) as garantia_legal_ob2,

f_ini_rs_notifi, f_fin_rs_notifi,
CONVERT(text, rs_notifi_ob) as rs_notifi_ob,
CONVERT(text, rs_notifi_ob2) as rs_notifi_ob2,

f_ini_rs_elab, f_fin_rs_elab,
CONVERT(text, rs_elab_ob) as rs_elab_ob,
CONVERT(text, rs_elab_ob2) as rs_elab_ob2,

f_ini_rs_ajust_fec, f_fin_rs_ajust_fec,
CONVERT(text, rs_ajust_fec_ob) as rs_ajust_fec_ob,
CONVERT(text, rs_ajust_fec_ob2) as rs_ajust_fec_ob2,

f_ini_rs_recibi, f_fin_rs_recibi,
CONVERT(text, rs_recibi_ob) as rs_recibi_ob,
CONVERT(text, rs_recibi_ob2) as rs_recibi_ob2,

f_ini_rs_firm_hoco, f_fin_rs_firm_hoco,
CONVERT(text, rs_firm_hoco_ob) as rs_firm_hoco_ob,
CONVERT(text, rs_firm_hoco_ob2) as rs_firm_hoco_ob2,

f_ini_creacion_carp, f_fin_creacion_carp,
CONVERT(text, creacion_carp_ob) as creacion_carp_ob,
CONVERT(text, creacion_carp_ob2) as creacion_carp_ob2


";
						 
						 
			  $alerta_incompletos ="";

			  if($sql_con[0]!=1){//si aplica retencion en garantias
				  $comple_sql_leg.= " and id not in (20,1023, 1024, 1025, 1026, 1027, 1028, 1029, 2030)";
					  }
					  
				if($sql_con[1]==2){//si es un contrato marco
					  $comple_sql_leg.= " and id not in (15, 16, 17, 21)";
				}
/***********************CONVINACION DE CAMPOS QUE APLICAN PARA EL CAMPO SEGUROS Y GARANTIAS POLIZAS ************************/
				
				if(($sql_con[9]==3 or $sql_con[9]==4 or $sql_con[9]==2)){//si no aplica polizas
						if($sql_con[9]!=4){//si diferente a garantia bancarias
								$comple_sql_leg.= " and id not in (1030)";
							}			
								
						if($sel_modifica[0] == 2 and $sql_con[9]==2){//si es OT
							$comple_sql_leg.= "";
							}else{
							  $comple_sql_leg.= " and id not in (6, 7, 1021, 1022)";
							}
				}else{
					//no incluya revision de garantias
					$comple_sql_leg.= " and id not in (1030)";
					
					if($sel_modifica[0] == 2 and $sql_con[9]==1){//si es OT y es polizas no aplica para las OTs
							  $comple_sql_leg.= " and id not in (6, 7, 1021, 1022)";
							}
					
					}
/***********************CONVINACION DE CAMPOS QUE APLICAN PARA EL CAMPO SEGUROS Y GARANTIAS POLIZAS ************************/
				
				if($sql_con[7]==1 ){//si es Otro Si.
					$comple_sql_leg.= " and id not in (2036)";//campos que no aplican para ningun otrosi
					if($sql_con[8] == 4 or $sql_con[8] == 12 or $sql_con[8] == 8 or $sql_con[8] == 2 or $sql_con[8] == 15 or $sql_con[8] == 16){//si son los tipos de otros si Alcance * Alcance / Tiempo * Alcance / Tiempo / Valor * Gerente * Tiempo *Tarifas * Tarifas / Tiempo / Alcance
					  $comple_sql_leg.= " and id not in (15, 16, 17, 21, 2036)";
					}
					
					
				}
				if($sql_con[7]==2 ){//si es OW.
					  $comple_sql_leg.= " and id not in (8, 12, 13, 14, 2036)";
				}
				
				$campo_nombre="";
				$campo_ayuda="";
				$campo_dias_esti="";
				if($sql_con[7]==3){//si es SUSPENCION. use los campos exluxivos para suepenciones nombre_en_suspencion, ayuda_en_suspencion, dias_estimados_suspencion y solo muestre la lista pasos que aplica
						$campo_nombre="nombre_en_suspencion";
						$campo_ayuda="ayuda_en_suspencion";
						$campo_dias_esti="dias_estimados_suspencion";
					  $comple_sql_leg.= " and id in (1,2031,2032,2035,2034,4,5,6,7,1021,1022,2033,13,18,19)";
				}elseif($sql_con[7]==4){//si es REINIICIO.  use los campos exluxivos para suepenciones nombre_en_reinicio, ayuda_en_reinicio, dias_estimados_reinicio y solo muestre la lista pasos que aplica
						$campo_nombre="nombre_en_reinicio";
						$campo_ayuda="ayuda_en_reinicio";
						$campo_dias_esti="dias_estimados_reinicio";

						$comple_sql_leg.= " and id in (1,2031,2032,2034,4,5,6,7,1021,1022,2033,13,18,19)";
					
					}else{//si es un otrosi OW o contrato oculte campos exclucivos de las suspenciones y reinicios y use los campos de nombres, dias estimados y ayudas normales
						$campo_nombre="nombre";
						$campo_ayuda="ayuda";
						$campo_dias_esti="dias_estimados";
  					  $comple_sql_leg.= " and id not in (2031, 2032, 2033, 2034, 2035)";//campos exlucivos de reinicio y/o Suspencion
					}
					
			

              $sel_campos = query_db("select id, id_actividad_nivel_servicio, ".$campo_nombre.", campo_fecha_inicial, campo_fecha_final, CAST(".$campo_ayuda." AS TEXT), orden, rol_edita_fecha_ini, rol_edita_fecha_fin, fecha_inicial_igual_a_id_relacion_campo, campo_ob, ".$campo_dias_esti.", alerta, ob_obligatoria, Devolucion, campo_ob_fin_si_aplicara, edita_fecha_inicial, edita_fecha_final from t7_relacion_campos_legalizacion where id not in (1,2) ".$comple_sql_leg." order by orden");
			  $conteo1=2;
			  $conteo2=1;
		


$sel_campos_contra = traer_fila_db(query_db("select ".$campos_tabla." from t7_relacion_campos_legalizacion_datos where ".$id_campo_aplica." =".$id_contrato_arr));


			  while($s_cam = traer_fila_db($sel_campos)){
				  	$edita_fecha_1=0;
					$edita_fecha_2=0;
					$edita_ob=0;
					
						   
$dias_reales="";
$dias_retraso="";	
			if($sel_campos_contra[$s_cam[3]] != "" and $sel_campos_contra[$s_cam[3]] != " " and $sel_campos_contra[$s_cam[3]] != "  " and $sel_campos_contra[$s_cam[4]] != "" and $sel_campos_contra[$s_cam[4]] != " " and $sel_campos_contra[$s_cam[4]] != "  "){

				if($sel_campos_contra[$s_cam[3]] <= $sel_campos_contra[$s_cam[4]])
						$dias_retraso=0;
						$dias_reales = dias_habiles_entre_fechas($sel_campos_contra[$s_cam[3]],$sel_campos_contra[$s_cam[4]]);
			}
			
			if($dias_reales!=""){
					$dias_retraso = $dias_reales-$s_cam[11];
					if($dias_retraso <=0) {$dias_retraso=0;}else{ $dias_retraso="<strong class='letra-descuentos'>".$dias_retraso."</strong>";}
				}
				  
				  $expo = explode(".", $s_cam[6]);
				//  if($expo[1]==0 or $expo[1]==""){
					
					 
					  if($expo[1]==0 or $expo[1]==""){
							  $conteo1=$conteo1+1;
							  $num_imprime =  $comple_num_ayuda." >> ".$s_cam[2];				  
									 if($clase==""){
										  $clase="class='filas_resultados'";
										  }else{
											  $clase="";
											  }
							$conteo2=1;
						  }else{	
						 	  $num_imprime =  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>> ".$s_cam[2];
							  $conteo2 = $conteo2+1;
							  } 
					  
					 
						   /* SELECCIONA ROL LA UNICA DIFERENCIA A TODOS LOS OTROS ES $s_cam[8]*/
	  $rol_encargado_final="";
	  $rol_encargado_final_id=0;
			  $sel_rol_encargado = traer_fila_row(query_db("select nombre from tseg11_roles_general where id_rol = ".$s_cam[8]));
			  if($sel_rol_encargado[0]!= ""){
				   $rol_encargado_final = "<strong>Encargado:</strong> <strong  style='font-size:10px'><font color='#005395' >".$sel_rol_encargado[0]."</font></strong>";
				  $rol_encargado_final_id=$s_cam[8];
				  }else{
					  $id_rol_si_aplica_otro_campo=0;
					  $sel_segun_campo_alimenta = traer_fila_row(query_db("select rol_edita_fecha_ini from t7_relacion_campos_legalizacion where campo_fecha_inicial = '".$s_cam[9]."'"));
					  if($sel_segun_campo_alimenta[0] <> 0){
						  $id_rol_si_aplica_otro_campo = $sel_segun_campo_alimenta[0];
						  }else{
							  $sel_segun_campo_alimenta = traer_fila_row(query_db("select rol_edita_fecha_fin from t7_relacion_campos_legalizacion where campo_fecha_final = '".$s_cam[9]."'"));
							  $id_rol_si_aplica_otro_campo = $sel_segun_campo_alimenta[0];
							  }
							  
							  $sel_rol_encargado = traer_fila_row(query_db("select nombre from tseg11_roles_general where id_rol = ".$id_rol_si_aplica_otro_campo));
								  if($sel_rol_encargado[0]!= ""){
									  $rol_encargado_final = " <strong>Encargado:</strong> <strong style='font-size:10px'><font color='#005395'>".$sel_rol_encargado[0]."</font></strong>";
									   $rol_encargado_final_id=$id_rol_si_aplica_otro_campo;
									  }
					  }
		 /* SELECCIONA ROL LA UNICA DIFERENCIA A TODOS LOS OTROS ES $s_cam[8]*/
						  
						  
						  
						 
                 
						 
			  ?>
            <tr <?=$clase?> <?=$bloquea_check?>>
              <td valign="top">&nbsp;</td>
              <td valign="top"><?=$num_imprime." ".$rol_encargado_final;?> </td>
              <td align="center" valign="top"><?=$s_cam[11]?></td>
              <td align="center" valign="top">&nbsp;</td>
              <td align="center" valign="top">&nbsp;</td>
              <td align="center" valign="top">&nbsp;</td>


              <td align="center" valign="top"><?=$dias_reales?></td>
              <td align="center" valign="top">&nbsp;</td>
              <td align="center" valign="top"><?=$dias_retraso?></td>
              </tr>
              
              
            <?
			global $dias_ideales_total, $tt_valor_di_re;
			$dias_ideales_total = $dias_ideales_total + $s_cam[11];
			$tt_valor_di_re = $tt_valor_di_re  + $dias_reales;
			  

			  }
			?><?  
			  
	}
	function funcion_duplicar_solicitud_desde_adjudicaicon($original, $destino){
	global $pi2;
	$s_i_orig = traer_fila_row(query_db("select id_pecc, t2_nivel_servicio_id, id_us, t1_tipo_contratacion_id, t1_area_id, t1_tipo_proceso_id, CAST(objeto_solicitud AS TEXT), CAST(objeto_contrato AS TEXT), CAST(alcance AS TEXT), CAST(proveedores_sugeridos AS TEXT), CAST(justificacion AS TEXT), CAST(recomendacion AS TEXT), t1_tipo_otro_si_id, t2_pecc_proceso_id, contrato_id, fecha_creacion, id_us_profesional_asignado, aprobacion_comite_adicional, dondeo_adicional, id_item_peec_aplica, aprobado, esta_en_e_procurement,tiempos_estandar, congelado, CAST(ob_contrato_adjudica AS TEXT), CAST(alcance_adjudica AS TEXT), CAST(justificacion_adjudica AS TEXT), CAST(recomendacion_adjudica AS TEXT), CAST(ob_solicitud_adjudica AS TEXT), id_us_preparador, num_solped, cargo_contable, de_historico, destino_ots, duracion_ots, id_gerente_ot, id_solicitud_relacionada, solicitud_rechazada, solicitud_desierta, CAST(justificacion_tecnica AS TEXT), CAST(justificacion_tecnica_ad AS TEXT), CAST(criterios_evaluacion AS TEXT), convirte_marco, conflicto_intereses, requiere_socios_adicional, id_proveedor_relacionado, CAST(justificacion_presupuesto AS TEXT), CAST(antecedentes_permiso AS TEXT), CAST(antecedentes_adjudicacion AS TEXT), origen_pecc, revision1_conflicto_intereces, revision2_conflicto_intereces, req_contra_mano_obra_local, req_contra_serv_bien_local, req_crear_otro_si, req_contra_mano_obra_local_ad, req_contra_serv_bien_local_ad, par_tecnico, gerente_contrato, par_tecnico_ad, gerente_contrato_ad, CAST(equipo_negociador AS TEXT), estado, vencimiento_contrato, pecc_linea,pecc_modificado, pecc_modificado_id_solicitud_aprobacion, pecc_modificado_observacion, id_urna,numero_urna, categoria_requiere_urna, tiene_reajuste, tiene_retencion, tiene_reembolsable, como_reembolsable from $pi2 where id_item=".$original));
		
		
		
$actualiza_tabla_principal = query_db("update t2_item_pecc set id_pecc='".$s_i_orig[0]."', id_us='".$s_i_orig[2]."', t1_tipo_contratacion_id='".$s_i_orig[3]."', t1_area_id='".$s_i_orig[4]."', objeto_solicitud='".$s_i_orig[6]."', alcance='".$s_i_orig[8]."', proveedores_sugeridos='".$s_i_orig[9]."', justificacion='".$s_i_orig[10]."', recomendacion='".$s_i_orig[11]."',  id_us_profesional_asignado='".$s_i_orig[16]."', aprobacion_comite_adicional='".$s_i_orig[17]."', dondeo_adicional='".$s_i_orig[18]."', aprobado='".$s_i_orig[20]."', tiempos_estandar='".$s_i_orig[22]."', id_us_preparador='".$_SESSION["id_us_session"]."', num_solped='".$s_i_orig[30]."', id_gerente_ot='".$s_i_orig[35]."', justificacion_tecnica='".$s_i_orig[39]."', criterios_evaluacion='".$s_i_orig[41]."', conflicto_intereses='".$s_i_orig[43]."', requiere_socios_adicional='".$s_i_orig[44]."', id_proveedor_relacionado='".$s_i_orig[45]."', antecedentes_permiso='".$s_i_orig[47]."', origen_pecc='".$s_i_orig[49]."', equipo_negociador='".$s_i_orig[61]."', pecc_linea='".$s_i_orig[64]."',pecc_modificado='".$s_i_orig[65]."', pecc_modificado_id_solicitud_aprobacion='".$s_i_orig[66]."', pecc_modificado_observacion='".$s_i_orig[67]."',antecedentes_adjudicacion='".$s_i_orig[48]."'
where id_item = ".$destino);

$trae_id_insrte = " select SCOPE_IDENTITY() AS [SCOPE_IDENTITY]";
	$sel_anexos = query_db("select t2_anexo_id, t2_item_pecc_id, aleatorio, tipo, detalle, adjunto, estado, id_us, antecedente_comite, id_categoria from t2_anexo where t2_item_pecc_id = ".$original." and estado = 1 and adjunto <> ''");
	while($s_ane = traer_fila_db($sel_anexos)){
		$insert = "insert into t2_anexo (t2_item_pecc_id, tipo, detalle, adjunto, estado, id_us, antecedente_comite, id_categoria) values (".$destino.", '".$s_ane[3]."', '".$s_ane[4]."', '".$s_ane[5]."', '".$s_ane[6]."', '".$s_ane[7]."', '".$s_ane[8]."', '".$s_ane[9]."')";
		$sql_ex=query_db($insert.$trae_id_insrte);
		$id_ane_destino = id_insert($sql_ex);
		copy(SUE_PATH_ARCHIVOS."pecc/".$s_ane[0]."_2.txt",SUE_PATH_ARCHIVOS."pecc/".$id_ane_destino."_2.txt");		
		}
	$sel_afe_ceco = query_db("select id, id_item, aleatorio, id_campo, afe_ceco, adjunto, permiso_adjudica, estado from t2_relacion_afe_ceco where id_item = ".$original." and estado = 1");
	while($s_ane = traer_fila_db($sel_afe_ceco)){
		
		$insert = "insert into t2_relacion_afe_ceco (id_item, id_campo, afe_ceco, adjunto, permiso_adjudica, estado) values (".$destino.", '".$s_ane[3]."', '".$s_ane[4]."', '".$s_ane[5]."', '".$s_ane[6]."', '".$s_ane[7]."')";
		$sql_ex=query_db($insert.$trae_id_insrte);
		$id_ane_destino = id_insert($sql_ex);
		copy(SUE_PATH_ARCHIVOS."pecc/afe_ceco/".$s_ane[0]."_8.txt",SUE_PATH_ARCHIVOS."pecc/afe_ceco/".$id_ane_destino."_8.txt");		
		}
		
	




$sel_ob_proceso = traer_fila_row(query_db("select CAST(p_oportunidad AS TEXT), CAST(p_costo AS TEXT), CAST(p_calidad AS TEXT), CAST(p_optimizar AS TEXT), CAST(p_trazabilidad AS TEXT), CAST(p_transparencia AS TEXT), CAST(p_sostenibilidad AS TEXT), CAST(a_oportunidad AS TEXT), CAST(a_costo AS TEXT), CAST(a_calidad AS TEXT), CAST(a_optimizar AS TEXT), CAST(a_trazabilidad AS TEXT), CAST(a_transparencia AS TEXT), CAST(a_sostenibilidad AS TEXT) from t2_objetivos_proceso where id_item = ".$original));

$update_ob_proceso = query_db("update t2_objetivos_proceso set p_oportunidad='".$sel_ob_proceso[0]."', p_costo='".$sel_ob_proceso[1]."', p_calidad='".$sel_ob_proceso[2]."', p_optimizar='".$sel_ob_proceso[3]."', p_trazabilidad='".$sel_ob_proceso[4]."', p_transparencia='".$sel_ob_proceso[5]."', p_sostenibilidad='".$sel_ob_proceso[6]."', a_oportunidad='".$sel_ob_proceso[7]."', a_costo='".$sel_ob_proceso[8]."', a_calidad='".$sel_ob_proceso[9]."', a_optimizar='".$sel_ob_proceso[10]."', a_trazabilidad='".$sel_ob_proceso[11]."', a_transparencia='".$sel_ob_proceso[12]."', a_sostenibilidad='".$sel_ob_proceso[13]."' where id_item = ".$destino);
	
	}
?>