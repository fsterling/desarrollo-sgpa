<? include("../../../librerias/lib/@session.php"); 
	verifica_menu("administracion.html");
	header('Content-Type: text/xml; charset=ISO-8859-1');
		
	

	

	function valida_muestra_contrato_reporte($estado,$estado_bu){
		if($estado_bu=="1"){
			$estado_bu_tex = "Elaboracion de contrato";
		}
		if($estado_bu=="2"){
			$estado_bu_tex = "Recibido Abastecimiento";
		}
		if($estado_bu=="3"){
			$estado_bu_tex = "SAP";
		}
		if($estado_bu=="4"){
			$estado_bu_tex = "Revision Legal";
		}
		if($estado_bu=="5"){
			$estado_bu_tex = "Firma Representante Hocol";
		}
		if($estado_bu=="6"){
			$estado_bu_tex = "Firma Representante Contratista";
		}
		if($estado_bu=="7"){
			$estado_bu_tex = "Revison Polizas";
		}
		if($estado_bu=="8"){
			$estado_bu_tex = "Gerente Contrato";
		}
		if($estado_bu=="9"){
			$estado_bu_tex = "Legalizacion Final Contrato";
		}
		if($estado_bu=="10"){
			$estado_bu_tex = "Legalizado";
		}

		if($estado_bu==101){
			if ($estado!="Elaboracion de contrato" and $estado!="Legalizado"){
				return true;
			}else{
				return false;
			}
		}else{
			//echo $estado." ".$estado_bu_tex;			
			if ($estado==$estado_bu_tex){
				return true;
			}else{
				if($estado_bu=="0"){
					return true;					
				}else{
					return false;
				}
			}
			
		}		
	}
	
	$query_comple = "";
	
	if($contratista_bu!=""){
		$explode = explode("----,",elimina_comillas_2($contratista_bu));
		$id_contratista = $explode[1];
		$query_comple = $query_comple." and contratista = ".$id_contratista;
	}
	
	if($especialista_bu!=""){
		$explode = explode("----,",elimina_comillas_2($especialista_bu));
		$id_especialista = $explode[1];
		$query_comple = $query_comple." and especialista = ".$id_especialista;
	}
	
	if($objeto_bu!=""){
		$query_comple = $query_comple." and t7cc.alcance like '%".$objeto_bu."%'";
	}
	
	if($tipo_contrato_bu!="0"){
		$query_comple = $query_comple." and t1_tipo_documento_id =".$tipo_contrato_bu."";
	}
	if($aplica_portales_bu!="0" and $aplica_portales_bu!=""){
		$query_comple = $query_comple." and aplica_portales =".$aplica_portales_bu."";
	}
	
	if($destino_bu!="0" and $destino_bu!=""){
		$query_comple = $query_comple." and destino_id =".$destino_bu."";
	}
	if($gerente_bu!=""){
		$explode = explode("----,",elimina_comillas_2($gerente_bu));
		$id_gerente = $explode[1];
		$query_comple = $query_comple." and t7cc.gerente = ".$id_gerente;
	}
	
	if($vigencia_bu!="0"){
		$fecha_hoy = getdate();
		if($vigencia_bu==1){
			$query_comple = $query_comple." and vc.vigencia_mes >='".$fecha_hoy["year"]."-".$fecha_hoy["mon"]."-".$fecha_hoy["mday"]."'";
		}
		if($vigencia_bu==2){
			$query_comple = $query_comple." and vc.vigencia_mes <'".$fecha_hoy["year"]."-".$fecha_hoy["mon"]."-".$fecha_hoy["mday"]."'";
		}
	}
	
	if($estado_bu!="0" and $estado_bu!=""){
		if($estado_bu==$est_firma_hocol or $estado_bu==$est_firma_contratista){
			if($estado_bu==$est_firma_hocol){
				$query_comple_es = $query_comple_es." and (t7cc.estado in (".$est_firma_hocol.") and t7cc.sel_representante = 2 or t7cc.estado in (".$est_firma_contratista.") and t7cc.sel_representante = 1)";
			}
			if($estado_bu==$est_firma_contratista){
				$query_comple_es = $query_comple_es." and (t7cc.estado in (".$est_firma_hocol.") and t7cc.sel_representante = 1 or t7cc.estado in (".$est_firma_contratista.") and t7cc.sel_representante = 2)";
			}
			
			
		}else{
			if($estado_bu==101){
				$query_comple_es = $query_comple_es." and t7cc.estado in (".$est_abastecimiento.",".$est_sap.",".$est_revision.",".$est_firma_hocol.",".$est_firma_contratista.",".$est_poliza.",".$est_gerente_contrato.",".$est_legalizacion.")";
			}else{
				$query_comple_es = $query_comple_es." and t7cc.estado = ".$estado_bu;	
			}
		}
		
	}
	
	
	
	$query_comple_temp="";
	if($contrato_bu!=""){
		$contrato_bu2 = str_replace("-","",$contrato_bu);
		$contrato_bu2 = str_replace(" ","",$contrato_bu2);
		
		$query_comple_temp = $query_comple_temp." and (consecutivo like '%".$contrato_bu2."%')";
		
		$query_create = "CREATE TABLE #t7_contratos_contrato_temp (id int, consecutivo varchar(50))";
		$sql_contrato=query_db($query_create);
		
		$lista_contrato = "select * from $co1 where estado >= 1".$permisos;
		$sql_contrato=query_db($lista_contrato);
		while($rs_array=traer_fila_row($sql_contrato)){
			$numero_contrato1 = "C";// los campos de la tabla t7_contratos_contrato			
			$separa_fecha_crea = explode("-",$rs_array[19]);//fecha_creacion
			$ano_contra = $separa_fecha_crea[0];					
			$numero_contrato2 = substr($ano_contra,2,2);
			$numero_contrato3 = $rs_array[2];//consecutivo
			$numero_contrato4 = $rs_array[43];//apellido
			$numero_contrato_fin = numero_item_pecc_contrato($numero_contrato1,$numero_contrato2,$numero_contrato3, $numero_contrato4);
			$numero_contrato_fin = str_replace("-","",$numero_contrato_fin);
			$numero_contrato_fin = str_replace(" ","",$numero_contrato_fin);
			
			//echo $numero_contrato1." ".$numero_contrato2." ".$numero_contrato3." ".$numero_contrato4;
			$query_create_int = "insert into #t7_contratos_contrato_temp values (".$rs_array[0].",'".$numero_contrato_fin."')";
			$sql_contrato_int=query_db($query_create_int);
		}
		
		$lista_contrato_temp = "select * from #t7_contratos_contrato_temp where id > 0 ".$query_comple_temp;
		$sql_contrato_temp=query_db($lista_contrato_temp);
		
		$array_id_bu = "0";
		while($rs_array_temp=traer_fila_row($sql_contrato_temp)){
			$array_id_bu =  $array_id_bu.",".$rs_array_temp[0];
		}
		
		$query_comple = $query_comple." and vc.id in (".$array_id_bu.")";
	}
	
	
?>
<style>
.columna_subtitulo_resultados_oscuro{ height:20px;font-size:14px; color:#FFF; 
 BORDER-BOTTOM: #CCCCCC 1px solid; BORDER-RIGHT: #CCCCCC 1px solid; BORDER-LEFT: #CCCCCC 1px solid; background:#666 }
 .tabla_lista_resultados{  margin:1px;
  BORDER-BOTTOM: #cccccc 3px double; BORDER-RIGHT: #cccccc 3px  double; BORDER-TOP: #cccccc 1px solid;  	BORDER-LEFT: #cccccc 1px solid; 
  border-spacing:2px;
  overflow:scroll;
  cursor:pointer;
 }
</style>
<table width="100%" border="0" cellpadding="2" cellspacing="2" class="tabla_lista_resultados">
<?
  	if($xls!=1){
  	?>
<tr>
 
<td colspan="13" align="left">
  
  <A href="javascript:document.location.target='_blank';document.location.href='../aplicaciones/contratos/reportes/ot.php?xls=1&paginas='+this.value+'&contrato_bu='+document.principal.contrato_bu.value+'&  contratista_bu='+document.principal.contratista_bu.value+'&especialista_bu='+document.principal.especialista_bu.value+'&objeto_bu='+document.principal.objeto_bu.value+'&gerente_bu='+document.principal.gerente_bu.value+'&estado_bu='+document.principal.estado_bu.value+'&c_contrato='+document.principal.c_contrato.checked+'&c_otrosi='+document.principal.c_otrosi.checked+'&c_orden_trabajo='+document.principal.c_orden_trabajo.checked+'&tipo_contrato_bu='+document.principal.tipo_contrato_bu.value+'&aplica_portales_bu='+document.principal.aplica_portales_bu.value+'&destino_bu='+document.principal.destino_bu.value+'&vigencia_bu='+document.principal.vigencia_bu.value">Exportar a Excel</A></td>
</tr>
  <?
  }
 ?>
<tr >
	<td width="9%" align="center" class="columna_subtitulo_resultados_oscuro">N&uacute;mero Contrato</td>
	<td width="5%" align="center" class="columna_subtitulo_resultados_oscuro">Contratista</td>
	<td width="4%" align="center" class="columna_subtitulo_resultados_oscuro">Id OT</td>
	<td width="7%" align="center" class="columna_subtitulo_resultados_oscuro">Numero OT</td>
    <td width="6%" align="center" class="columna_subtitulo_resultados_oscuro">Objeto OT</td>
    <td width="8%" align="center" class="columna_subtitulo_resultados_oscuro">Objeto Solicitud</td>
    <td width="7%" align="center" class="columna_subtitulo_resultados_oscuro">Fecha Inicio</td>
    <td width="8%" align="center" class="columna_subtitulo_resultados_oscuro">Fecha Fin</td>
    <td width="8%" align="center" class="columna_subtitulo_resultados_oscuro">Valor COP</td>
    <td width="10%" align="center" class="columna_subtitulo_resultados_oscuro">Valor USD</td>
    <td width="11%" align="center" class="columna_subtitulo_resultados_oscuro">Gerente Solicitante</td>
    <td width="8%" align="center" class="columna_subtitulo_resultados_oscuro"><p>Estado</p></td>
	<td width="9%" align="center" class="columna_subtitulo_resultados_oscuro">Equipo Usuario</td>
	
  </tr>
<?
	$permisos = valida_visualiza_contrato($_SESSION["id_us_session"]);
	$permisos = $permisos.$query_comple ;
	$permisos = str_replace("especialista","especialista_id",$permisos);
	$permisos = str_replace("contratista","contratista_id",$permisos);
	
	
	$busca_reportes = "select vc.id,vc.id_item,vc.consecutivo,vc.apellido,vc.contratista,vc.creacion_sistema,t7cc.numero_otrosi,CAST(t7cc.alcance AS text) as alcance,t7cc.fecha_inicio,t7cc.tiempo,t7cc.valor_cop,t7cc.valor,t1u.nombre_administrador as gerente,t1a.nombre,t7cc.id,t7cc.id_item_pecc,CAST(t2ip.objeto_solicitud AS text) as objeto_solicitud,CAST(t2ip.ob_solicitud_adjudica AS text) as ob_solicitud_adjudica from $co4 t7cc left join $v_contra2 vc on t7cc.id_contrato=vc.id left join $g1 t1u on t7cc.gerente=t1u.us_id left join $pi2 t2ip on t7cc.id_item_pecc = t2ip.id_item left join $g12 t1a on t2ip.t1_area_id = t1a.t1_area_id where  vc.estado <> 0 and (vc.analista_deloitte <> 1 or vc.analista_deloitte IS NULL) and t7cc.eliminado = 0 and (t7cc.congelado <> 1 or t7cc.congelado IS NULL) and t7cc.tipo_complemento=2 $permisos $query_comple_es order by t7cc.numero_otrosi ";

	$sql_re = query_db($busca_reportes);
	while($ls_re=traer_fila_row($sql_re)){
		$numero_contrato1 = "C";// los campos de la tabla t7_contratos_contrato			
		$separa_fecha_crea = explode("-",$ls_re[5]);//fecha_creacion
		$ano_contra = $separa_fecha_crea[0];					
		$numero_contrato2 = substr($ano_contra,2,2);
		$numero_contrato3 = $ls_re[2];//consecutivo
		$numero_contrato4 = $ls_re[3];//apellido
		//echo $sql_con[19]." ".$numero_contrato2." ".$numero_contrato3." ".$numero_contrato4;
		?>
		<?
        if(valida_muestra_contrato_reporte(estado_contrato_retu(arreglo_pasa_variables($ls_re[14]),$co4),$estado_bu)){
			?>
			<tr>
			  <td>
				  <?                
					echo numero_item_pecc_contrato($numero_contrato1,$numero_contrato2,$numero_contrato3, $numero_contrato4);
				  ?>
			  </td>
			  <td><?=$ls_re[4]?></td>
			  <td><?=$ls_re[14]?></td>
			  <?
               $sele_items_historico = "select $pi2.num1,$pi2.num2,$pi2.num3 from $pi2 where $pi2.id_item=".$ls_re[15];
				$sql_sele_items_historico=traer_fila_row(query_db($sele_items_historico));

			  ?>
			  <td><?=$ls_re[6]?></td>
			  <td><?=$ls_re[7]?></td>
              <?
			  $objeto_sol = "";
              if($ls_re[17]!=""){
					$objeto_sol = $ls_re[17];
				}else{
			$objeto_sol = $ls_re[16];
			}
			  ?>
			  <td><?=$objeto_sol;?></td>			  
			  <td><?=$ls_re[8]?></td>
			  <td><?=$ls_re[9]?></td>
			  <td><?=$ls_re[10]?></td>
			  <td><?=$ls_re[11]?></td>
			  <td><?=$ls_re[12]?></td>
			  <td><?=estado_contrato_retu(arreglo_pasa_variables($ls_re[14]),$co4)?></td>
              <td><?=$ls_re[13]?></td>
		    </tr>
			<?
		}		
	}
?>
</table>
<?
if($xls==1){
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=OT.xls"); 
}
?>