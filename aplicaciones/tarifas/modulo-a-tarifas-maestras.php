<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../css/estilo-principal.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="100%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="3" valign="top">
      <table width="100%" border="0" cellpadding="2" cellspacing="2" class="tabla_lista_resultados">
        <tr>
          <td colspan="4" class="fondo_2">Buscador de tarifas</td>
        </tr>
        <tr>
          <td width="23%" onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><p align="right"><strong>Por contrato:</strong></p>          </td>
          <td width="31%" onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><label>
            <input type="text" name="textfield" id="textfield" />
          </label></td>
          <td width="22%" onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><div align="right"><strong>Por proveedor/contratista</strong></div></td>
          <td width="24%" onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><input type="text" name="textfield2" id="textfield2" /></td>
        </tr>
        <tr>
          <td onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><div align="right"><strong>Por tipo de actualización</strong></div></td>
          <td onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><label>
            <select name="select" id="select">
              <option>Seleccione</option>
              <option>Nueva</option>
              <option>Modificada</option>
            </select>
          </label></td>
          <td onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><div align="right"><strong>Por tipo de aprobación</strong></div></td>
          <td onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><select name="select2" id="select2">
            <option>Seleccione</option>
            <option>Pendiente</option>
            <option>Aprobadas</option>
            <option>Rechazadas</option>
                    </select></td>
        </tr>
        <tr>
          <td onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><div align="right"><strong>Por usuario asignado</strong></div></td>
          <td onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><select name="select3" id="select3">
            <option>Seleccione</option>
            <option>Andrea reyes</option>
                    </select></td>
          <td onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><div align="right"><strong>Por codigo</strong></div></td>
          <td onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><input type="text" name="textfield3" id="textfield3" /></td>
        </tr>
        <tr>
          <td onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><div align="right"><strong>Por maestra de tarifas</strong></div></td>
          <td colspan="3" onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')">
            <div align="left">
              <input type="text" name="textfield4" id="textfield4" />
          </div></td>
        </tr>
        <tr>
          <td onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')"><div align="right"><strong>Por descripción de tarifa</strong></div></td>
          <td colspan="3" onclick="ajax_carga('c_tarifas.php','carga_acciones_permitidas')">
            <div align="left">
              <input type="text" name="textfield5" id="textfield5" />
          </div></td>
        </tr>
      </table>      </td>
  </tr>
  <tr>
    <td width="29%" valign="top" id="carga_acciones_permitidas2">&nbsp;</td>
    <td width="29%" valign="top" id="carga_acciones_permitidas2"><label>
      <input name="button" type="submit" class="boton_grabar" id="button" value="Realizar busqueda de tarifas" />
    </label></td>
    <td width="29%" valign="top" id="carga_acciones_permitidas2">&nbsp;</td>
  </tr>
</table>
<br />
<table width="100%" border="0" cellpadding="2" cellspacing="2" class="tabla_lista_resultados">
  <tr>
    <td colspan="11" class="columna_titulo_resultados"><table width="100%" border="0" cellspacing="2" cellpadding="2" class="tabla_paginador">
      <tr>
        <td width="78%"><div align="left">Tarifas encontradas: 4</div></td>
        <td width="7%"><div align="center"><a href="javascript:busqueda_paginador_nuevo(<?=$anterior;?>,'../aplicaciones/historico_procesos.php','contenidos', '<?=$tipo_ingreso_alerta;?>')">Anterior</a></div></td>
        <td width="8%"><label>
          <select name="pagij" onchange="javascript:busqueda_paginador_nuevo(this.value,'../aplicaciones/historico_procesos.php','contenidos', '<?=$tipo_ingreso_alerta;?>')">
            <? 
		  for($i=1;$i<=10;$i++){
		   ?>
            <option value="<?=$i;?>"  <? if($i==$pag) echo "selected"; ?>>Pagina
              <?=$i;?>
              </option>
            <? } ?>
          </select>
        </label></td>
        <td width="7%"><a href="javascript:busqueda_paginador_nuevo(<?=$proxima;?>,'../aplicaciones/historico_procesos.php','contenidos', '<?=$tipo_ingreso_alerta;?>')">Siguiente</a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="7%" class="columna_subtitulo_resultados"><div align="center">Contrato</div></td>
    <td width="24%" class="columna_subtitulo_resultados"><div align="center">Detalle</div></td>
    <td width="6%" class="columna_subtitulo_resultados"><div align="center">Unidad </div></td>
    <td width="7%" class="columna_subtitulo_resultados"><div align="center">Cantidad</div></td>
    <td width="6%" class="columna_subtitulo_resultados"><div align="center">Moneda</div></td>
    <td width="4%" class="columna_subtitulo_resultados"><div align="center">Valor</div></td>
    <td width="5%" class="columna_subtitulo_resultados"><div align="center">Nueva</div></td>
    <td width="8%" class="columna_subtitulo_resultados"><div align="center">Modificada</div></td>
    <td width="8%" class="columna_subtitulo_resultados"><div align="center">Aprobación</div></td>
    <td width="19%" class="columna_subtitulo_resultados"><div align="center">Buscar tarifa maestra</div></td>
    <td width="6%" class="columna_subtitulo_resultados"><div align="center">Ver detalle</div></td>
  </tr>
  <tr class="filas_resultados">
    <td class="filas_resultados"><label>C12-001</label></td>
    <td class="filas_resultados"><label>TARIFA DE PRUEBA 1</label></td>
    <td class="filas_resultados"><div align="center">UNI</div></td>
    <td><div align="center">50</div></td>
    <td><div align="center">USD</div></td>
    <td><div align="center">1.000</div></td>
    <td class="titulos_resumen_alertas"><div align="center">SI</div></td>
    <td class="filas_resultados"><div align="center">NO</div></td>
    <td class="titulos_resumen_alertas"><div align="center">Pendiente</div></td>
    <td class="titulos_resumen_alertas"><label>
      <input type="text" name="textfield6" id="textfield6" />
    </label></td>
    <td class="titulos_resumen_alertas"><div align="center"><img src="../../imagenes/botones/editar.jpg" alt="ver detalle" width="14" height="15" /></div></td>
  </tr>
  <tr>
    <td><label>C12-002</label></td>
    <td><label>TARIFA DE PRUEBA 2</label></td>
    <td><div align="center">UNI</div></td>
    <td><div align="center">50</div></td>
    <td><div align="center">USD</div></td>
    <td><div align="center">1.000</div></td>
    <td><div align="center">NO</div></td>
    <td><div align="center">NO</div></td>
    <td><div align="center">N/A</div></td>
    <td class="titulos_resumen_alertas"><input type="text" name="textfield7" id="textfield7" /></td>
    <td class="titulos_resumen_alertas"><div align="center"><img src="../../imagenes/botones/editar.jpg" alt="ver detalle" width="14" height="15" /></div></td>
  </tr>
  <tr class="filas_resultados">
    <td class="filas_resultados"><label>C12-003</label></td>
    <td class="filas_resultados"><label>TARIFA DE PRUEBA 3</label></td>
    <td class="filas_resultados"><div align="center">UNI</div></td>
    <td><div align="center">50</div></td>
    <td><div align="center">USD</div></td>
    <td><div align="center">1.000</div></td>
    <td class="filas_resultados"><div align="center">NO</div></td>
    <td class="titulos_resumen_alertas"><div align="center">SI</div></td>
    <td class="titulos_resumen_alertas"><div align="center">Devuelta</div></td>
    <td class="titulos_resumen_alertas"><input type="text" name="textfield8" id="textfield8" /></td>
    <td class="titulos_resumen_alertas"><div align="center"><img src="../../imagenes/botones/editar.jpg" alt="ver detalle" width="14" height="15" /></div></td>
  </tr>
  <tr>
    <td><label>C12-003</label></td>
    <td><label>TARIFA DE PRUEBA 4</label></td>
    <td><div align="center">UNI</div></td>
    <td><div align="center">50</div></td>
    <td><div align="center">USD</div></td>
    <td><div align="center">1.000</div></td>
    <td><div align="center">NO</div></td>
    <td><div align="center">NO</div></td>
    <td><div align="center">N/A</div></td>
    <td class="titulos_resumen_alertas"><input type="text" name="textfield9" id="textfield9" /></td>
    <td class="titulos_resumen_alertas"><div align="center"><img src="../../imagenes/botones/editar.jpg" alt="ver detalle" width="14" height="15" /></div></td>
  </tr>
  <tr class="<?=$class;?>">
    <td colspan="5"></td>
    <td id="contrase_<?=$ls[0];?>"></td>
    <td id="contrase_<?=$ls[0];?>"></td>
    <td id="contrase_<?=$ls[0];?>"></td>
    <td id="contrase_<?=$ls[0];?>"></td>
    <td id="contrase_<?=$ls[0];?>"></td>
    <td id="contrase_<?=$ls[0];?>"></td>
  </tr>
  <tr>
    <td colspan="11" class="columna_titulo_resultados"><table width="100%" border="0" cellspacing="2" cellpadding="2" class="tabla_paginador">
      <tr>
        <td width="78%"><div align="left">Tarifas encontradas: 4</div></td>
        <td width="7%"><div align="center"><a href="javascript:busqueda_paginador_nuevo(<?=$anterior;?>,'../aplicaciones/historico_procesos.php','contenidos', '<?=$tipo_ingreso_alerta;?>')">Anterior</a></div></td>
        <td width="8%"><label>
          <select name="pagij2" onchange="javascript:busqueda_paginador_nuevo(this.value,'../aplicaciones/historico_procesos.php','contenidos', '<?=$tipo_ingreso_alerta;?>')">
            <? 
		  for($i=1;$i<=10;$i++){
		   ?>
            <option value="<?=$i;?>"  <? if($i==$pag) echo "selected"; ?>>Pagina
              <?=$i;?>
              </option>
            <? } ?>
          </select>
        </label></td>
        <td width="7%"><a href="javascript:busqueda_paginador_nuevo(<?=$proxima;?>,'../aplicaciones/historico_procesos.php','contenidos', '<?=$tipo_ingreso_alerta;?>')">Siguiente</a></td>
      </tr>
    </table></td>
  </tr>
</table>
<br />
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="30%">&nbsp;</td>
    <td width="42%"><label>
      <input name="button2" type="submit" class="boton_grabar" id="button2" value="Grabar asociación de listas maestras" />
    </label></td>
    <td width="28%">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><br />
  <br />
</p>
</body>
</html>
