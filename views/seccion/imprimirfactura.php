<?php
$this->title = 'Imprimir Factura';

?>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<style type="text/css">
 table {
    margin: 0 auto;
	border:2px #9932CC solid;
    border-radius:5px;
    min-width:140px;
    font-family: verdana, Helvetica, sans-serif;
	font-size: 12px;
}
	
 label {
    padding:0px;
	font-weight: bold;
	font-size: 14px;
	
}
 label.t {
    padding:0px;
	font-weight: bold;
	font-size: 18px;
	
}
label.r {
text-align: right;
vertical-align: middle;
margin-right: 20px;
margin-left: 20px;
display: inline;
}

 label.anulado {
    padding:0px;
	font-weight: bold;
	font-size: 25px;
	color: red;
}
</style>
<title>Imprimir Factura</title>
</head>
<body onload='window.print()'>
<table width="950" border="1" align="center" bordercolor="#000000">
  <tr>
    <td align="center" colspan = "3"><h2>DOCUMENTO EQUIVALENTE REGIMEN SIMPLIFICADO</h2></td>
  </tr>
  <tr>
    <td align="center"colspan = "3"><img src="images/logo.png" align="left" height="122" width="122"><br><label class="t">BEAUTYICE </label><br><label class="t">VICTORIA EUGENIA SALINAS TOBAR</label><br><label class="t">CARRERA 48 N° 25 AA SUR 70 COMPLEX LAS VEGAS - LOCAL 103</label><br><label class="t">NIT: 42897128</label></td>
  </tr>
  <tr>
    <td class="yesPrint" align="center"><label>N°: <?php echo $model->seccion_pk;?></label></td>
	<td align="center"><label>FECHA: <?php echo $model->fecha_pago;?></label></td>	
	<td align="center"><label>SEDE: <?php echo $model->sedeFk->sede;?></label></td>
  </tr>
  <tr>
    <td colspan = "3"><label>FACTURADO A: <?php echo $model->clienteFk->nombrecompletosinidentificacion;?></label><label class="r">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IDENTIFICACIÓN: <?php echo $model->clienteFk->identificacion;?></label>&nbsp;&nbsp;&nbsp;<label class="anulado"><?php if ($model->estado_anulado == "SI"){ echo "ANULADO";}else {echo "";}?></label><br><label>POR CONCEPTO DE: <?php echo $model->clienteFk->nombrecompletosinidentificacion . " - " . $model->clienteFk->identificacion . " - " . $model->seccionTipoFk->tipo;?></label>&nbsp;&nbsp;&nbsp;<label class="anulado"><?php if ($model->estado_anulado == "SI"){ echo "ANULADO";}else {echo "";}?></label><br><label>LA SUMA DE:
	<?php echo '$ '.number_format($model->total_pago);?> <?php if ($model->estado_anulado == "SI"){ echo "ANULADO";}else {echo "";} ?></label></td>
  </tr>
  <tr>
    <td align="center"><label>CUENTA PUC</label></td>
	<td align="center"><label>DEBITO</label></td>
	<td align="center"><label>CREDITO</label></td>
  </tr>
  <tr>
    <td align="center"><?php echo $model->clienteFk->nombrecompletosinidentificacion . " - " . $model->clienteFk->identificacion;?></td>
	<td align="center"><?php echo '$ '.number_format($model->total_pago);?></td>
	<td align="center">-</td>
  </tr>
  <tr>
    <td align="center">Caja General</td>
	<td align="center">-</td>
	<td align="center"><?php echo '$ '.number_format($model->total_pago);?></td>
  </tr>
  <tr>
    <td colspan = "3"><label>FIRMA Y C.C.: </label>&nbsp;&nbsp;&nbsp;<label class="anulado"><?php if ($model->estado_anulado == "SI"){ echo "ANULADO";}else {echo "";}?></label></td>
  </tr>  
  <tr>
    <td colspan = "3"><label>ELABORADO POR: <?php echo $usuario->nombrecompleto;?></label>&nbsp;&nbsp;&nbsp;<label class="anulado"><?php if ($model->estado_anulado == "SI"){ echo "ANULADO";}else {echo "";} ?></label></td>
  </tr>
</table>


</body>
</html>