<?
  //error_reporting(E_ALL);  // Líneas para mostart errores
//ini_set('display_errors', '1');  // Líneas para mostart errores
  include("../../librerias/lib/@session.php"); 
	verifica_menu("administracion.html");
	header('Content-Type: text/xml; charset=ISO-8859-1');
	echo '<?xml version="1.0" encoding="ISO-8859-1"?>';	
	$valida_permiso="select * from $ts6 where id_usuario=".$_SESSION["id_us_session"]." and id_rol_general=6";
  $resultado=traer_fila_row(query_db($valida_permiso));
 

$paginador = (($pag-1)*$numero_pagi);
  if($resultado[1]==6){
    $query="select nombre_administrador from $g1 where us_id=".$_SESSION["id_us_session"];
    $nombre_usuario=traer_fila_row(query_db($query));

     //paguinacion
$numero_pagi = 30;
if ($pag=="")
  $pag = 1;
else
  $pag = $pag;
    $li_n_c=traer_fila_row(query_db("select count(*) from $vcomite3"));
      $total_r = $li_n_c[0];
      $pagina = ceil($total_r /$numero_pagi);

if($pag==($pagina))
  $proxima = $pag;
else
  $proxima = $pag +1;
  
if($pag==1)
  $anterior = $pag;
else
  $anterior = $pag -1;
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../../css/estilo-principal.css" rel="stylesheet" type="text/css" /></head>

<body>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2" class="tabla_lista_resultados">
  <tr>
    <td colspan="7"  class="titulos_secciones">Creaci&oacute;n de Tareas de Comit&eacute;</td>
  </tr>
</table>
<br />
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2" class="tabla_lista_resultados">
  <tr >
    <td width="15%" align="right"> Usuario Responsable:</td>
    <td width="25%" align="left"><input type="text" name="busca_id_responsable" id="busca_id_responsable" onKeyUp="selecciona_lista()" /></td>
    <td width="5%" align="right"> Encargado Cierre:</td>
    <td width="25%" align="left"><input type="text" disabled name="busca_id_cierre" id="busca_id_cierre" value="<?=$nombre_usuario[0]?>" /></td>
  </tr>
  <tr>
    <td width="" align="right">Fecha de cierre:</td>
    <td width="" align="left"><input type="text" name="fecha_cierre" id="fecha_cierre" onmousedown="calendario_se('fecha_cierre')" /></td>
    <td width="" align="right"><label for="agrega_solicitud">Agregar Solicitud:</label><input type="checkbox" name="agrega_solicitud" id="agrega_solicitud" onclick="valida_grega_solicitud()" /></td>
    <td width="" align="left">
          <input type="text" disabled name="busca_id_solicitud" id="busca_id_solicitud" onkeypress="selecciona_lista()" value="">
    </td>
  </tr>
  <tr>
    <td align="right">T&iacute;tulo:</td>
    <td align="left"><input type="text" name="titulo" id="titulo"/></td>
    <td align="right">Comit&eacute;:</td>
    <td align="left"><input type="text" name="busca_id_comite" id="busca_id_comite" onkeypress="selecciona_lista()"></td>
    
  </tr>
  <tr>
    <td colspan="1" align="right">Detalle:</td>
    <td colspan="5" align="left"><textarea name="detalle" id="detalle" cols="100" rows="3"></textarea></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><input type="button" name="button" id="button" value="Crear Tarea" class="boton_grabar" onclick="crear_tarea()" /></td>
  </tr>
</table>
<?php
  $query=traer_fila_row(query_db("select count(*) from $c6 where estado <> 3"));
  //$num_filas=numfilas_db($qurey);
  if($query[0]!=0){?>
    <table width="100%" border="0" cellpadding="2" cellspacing="2" class="tabla_lista_resultados">
    <tr>
    <td colspan="10" class="columna_titulo_resultados"><table width="100%" border="0" cellspacing="2" cellpadding="2" class="tabla_paginador">
      <tr>
        <td width="77%"><div align="left"></div></td>
        <td width="6%"><div align="center"><a href="javascript:busqueda_paginador_nuevo(<?=$anterior;?>,'../aplicaciones/historico_procesos.php','contenidos', '<?=$tipo_ingreso_alerta;?>')">Anterior</a></div></td>
        <td width="10%"><label>
          <select name="pagij" onchange="javascript:busqueda_paginador_nuevo(this.value,'../aplicaciones/tareas-comite.php','contenidos', '<?=$tipo_ingreso_alerta;?>')">
            <? 
      for($i=1;$i<=$pagina;$i++){
       ?>
            <option value="<?=$i;?>"  <? if($i==$pag) echo "selected"; ?>>Pagina
              <?=$i;?>
              </option>
            <? } ?>
          </select>
        </label></td>
        <td width="7%"><a href="javascript:busqueda_paginador_nuevo(<?=$proxima;?>,'../aplicaciones/tareas-comite.php','contenidos', '<?=$tipo_ingreso_alerta;?>')">Siguiente</a></td>
      </tr>
    </table></td>
  </tr>
    <tr>
      <td width="2%" height="29" class="columna_subtitulo_resultados">&nbsp;</td>
      <td width="5%" class="columna_subtitulo_resultados"><div align="center">Responsable</div></td>
      <td width="9%" align="center" class="columna_subtitulo_resultados">Encargado de Cierre</td>
      <td width="9%" class="columna_subtitulo_resultados"><div align="center">Titulo</div></td>
      <td width="6%" class="columna_subtitulo_resultados"><div align="center">Comite</div></td>
      <td width="9%" class="columna_subtitulo_resultados">Solicitud</td>
      <td width="8%" class="columna_subtitulo_resultados"><div align="center">Fecha de Cierre</div></td>
    </tr>
  <?php
  $cont = 0;
  $sel_proce=query_db("select * from $vcomite3");
  while($sele_tareas = traer_fila_db($sel_proce)){
      if($cont == 0){
        $clase= "filas_resultados";
      $cont = 1;
      }else{
        $clase= "";
      $cont = 0;//ajax_carga('../aplicaciones/comite/menu_comite.php?id_comite=<?=$sele_tareas[0]','id_div_sub');
      } ?>
    <tr class="<?=$clase?>">
      <td align="center" ><a href="javascript:ajax_carga('../aplicaciones/comite/edicion-comite-tarea.php?id_comite=<?=$sele_tareas[0]?>','contenidos');"><img src="../imagenes/botones/alerta.png" alt="Proceso pendiente, sin resolver o sin leer" width="16" height="16" /></a></td>
      <td align="center"><?=$sele_tareas[1] ?></td>
      <td align="center"><?=$sele_tareas[2] ?></td>
      <td align="center"><?=$sele_tareas[3] ?></td>
      <td align="center"><?php 
        if(strlen($sele_tareas[6])==1){
          echo $sele_tareas[4].$sele_tareas[5]."000".$sele_tareas[6];
        }else if(strlen($sele_tareas[6])==2){
          echo $sele_tareas[4].$sele_tareas[5]."00".$sele_tareas[6];
        }else if(strlen($sele_tareas[6])==3){
          echo $sele_tareas[4].$sele_tareas[5]."0".$sele_tareas[6];
        }else{
          echo $sele_tareas[4].$sele_tareas[5].$sele_tareas[6];
        }
      ?></td>
      <td align="center"><?php
        if($sele_tareas[10]!=null){
          if(strlen($sele_tareas[10])==1){
            echo $sele_tareas[8].$sele_tareas[9]."000".$sele_tareas[10];
          }else if(strlen($sele_tareas[10])==2){
            echo $sele_tareas[8].$sele_tareas[9]."00".$sele_tareas[10];
          }else if(strlen($sele_tareas[10])==3){
            echo $sele_tareas[8].$sele_tareas[9]."0".$sele_tareas[10];
          }else{
            echo $sele_tareas[8].$sele_tareas[9].$sele_tareas[10];
          }
        } 
      ?></td>
      <td align="center"><?=$sele_tareas[7] ?></td>
    </tr>
<?  }//fin while?>
</table>
<?}else{?>
  <h3>No tiene tareas creadas a&uacute;n</h3>
<?}//fin if
?>
<div id="contenidos"></div>
<input type="hidden" name="aleatorio" id="aleatorio" value="<?=$aleatorio?>" />
<input type="hidden" name="id_comite" id="id_comite" value="" />
<input type="hidden" name="id_solicitud" id="id_solicitud" value="" />
<input type="hidden" name="id_comite_agrega" id="id_comite_agrega"/>
<input type="hidden" name="id_item_agrega" id="id_item_agrega" />
<input type="hidden" name="orden_cambia" id="orden_cambia" />
<input type="hidden" name="id_relacion" id="id_relacion" />
<input type="hidden" name="quita_asistente" id="quita_asistente" />
</body>
</html>
<?}else{//si el usuario logeado no es rol secretario

//paguinacion
$numero_pagi = 30;
if ($pag=="")
  $pag = 1;
else
  $pag = $pag;

$paginador = (($pag-1)*$numero_pagi);
    $li_n_c=traer_fila_row(query_db("select * from $vcomite4 where id_responsable=".$_SESSION['id_us_session']." and estado=1"));
      $total_r = $li_n_c[0];
      $pagina = ceil($total_r /$numero_pagi);

if($pag==($pagina))
  $proxima = $pag;
else
  $proxima = $pag +1;
  
if($pag==1)
  $anterior = $pag;
else
  $anterior = $pag -1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<title>Documento sin t&iacute;tulo</title>
<link href="../../css/estilo-principal.css" rel="stylesheet" type="text/css" /></head>

<body>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2" class="tabla_lista_resultados">
  <tr>
    <td colspan="7"  class="titulos_secciones">M&oacute;dulo de Tareas de Comit&eacute;</td>
  </tr>
</table>
<br />
<?php
  $query=traer_fila_row(query_db("select count(*) from $vcomite4 where estado=1"));
  //$num_filas=numfilas_db($qurey);
  if($query[0]!=0){?>
    <table width="100%" border="0" cellpadding="2" cellspacing="2" class="tabla_lista_resultados">
    <tr>
    <td colspan="10" class="columna_titulo_resultados"><table width="100%" border="0" cellspacing="2" cellpadding="2" class="tabla_paginador">
      <tr>
        <td width="77%"><div align="left"></div></td>
        <td width="6%"><div align="center"><a href="javascript:busqueda_paginador_nuevo(<?=$anterior;?>,'../aplicaciones/historico_procesos.php','contenidos', '<?=$tipo_ingreso_alerta;?>')">Anterior</a></div></td>
        <td width="10%"><label>
          <select name="pagij" onchange="javascript:busqueda_paginador_nuevo(this.value,'../aplicaciones/tareas-comite.php','contenidos', '<?=$tipo_ingreso_alerta;?>')">
            <? 
      for($i=1;$i<=$pagina;$i++){
       ?>
            <option value="<?=$i;?>"  <? if($i==$pag) echo "selected"; ?>>Pagina
              <?=$i;?>
              </option>
            <? } ?>
          </select>
        </label></td>
        <td width="7%"><a href="javascript:busqueda_paginador_nuevo(<?=$proxima;?>,'../aplicaciones/tareas-comite.php','contenidos', '<?=$tipo_ingreso_alerta;?>')">Siguiente</a></td>
      </tr>
    </table></td>
  </tr>
    <tr>
      <td width="2%" height="29" class="columna_subtitulo_resultados">&nbsp;</td>
      <td width="5%" class="columna_subtitulo_resultados"><div align="center">Responsable</div></td>
      <td width="9%" class="columna_subtitulo_resultados"><div align="center">Titulo</div></td>
      <td width="8%" class="columna_subtitulo_resultados"><div align="center">Fecha de Cierre</div></td>
    </tr>
  <?php
  $cont = 0;
  $sel_proce=query_db("select * from $vcomite4 where id_responsable=".$_SESSION['id_us_session']." and estado=1");

  while($sele_tareas = traer_fila_db($sel_proce)){
      if($cont == 0){
        $clase= "filas_resultados";
      $cont = 1;
      }else{
        $clase= "";
      $cont = 0;//ajax_carga('../aplicaciones/comite/menu_comite.php?id_comite=<?=$sele_tareas[0]','id_div_sub');
      } ?>
    <tr class="<?=$clase?>">
      <td align="center" ><a href="javascript:ajax_carga('../aplicaciones/comite/edicion-comite-tarea.php?id_comite=<?=$sele_tareas[0]?>','contenidos');"><img src="../imagenes/botones/alerta.png" alt="Proceso pendiente, sin resolver o sin leer" width="16" height="16" /></a></td>
      <td align="center"><?=$sele_tareas[2] ?></td>
      <td align="center"><?=$sele_tareas[4] ?></td>
      <td align="center"><?=$sele_tareas[5] ?></td>
    </tr>
<?  }//fin while?>
</table>
<?}else{?>
  <h3>No tiene tareas pendientes</h3>
<?}//fin if
?>

<div id="contenidos"></div>
<input type="hidden" name="aleatorio" id="aleatorio" value="<?=$aleatorio?>" />
<input type="hidden" name="id_comite" id="id_comite" value="" />
<input type="hidden" name="id_solicitud" id="id_solicitud" value="" />
<input type="hidden" name="id_comite_agrega" id="id_comite_agrega"/>
<input type="hidden" name="id_item_agrega" id="id_item_agrega" />
<input type="hidden" name="orden_cambia" id="orden_cambia" />
<input type="hidden" name="id_relacion" id="id_relacion" />
<input type="hidden" name="quita_asistente" id="quita_asistente" />
</body>
</html>
<?}
?>