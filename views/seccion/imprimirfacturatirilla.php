<?php
$this->title = 'Imprimir tirilla';

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

</style>
<title>Imprimir Pago</title>
</head>
<body onload='window.print()'>
<table>
  <tr>
    <td align="center"><h2>BEAUTY ICE<h2><?php if ($model->estado_anulado == "SI"){ echo "ANULADO";}else {echo "";}?></td>
  </tr>
  <tr>
    <td align="center">VICTORIA SALINAS TOBAR</td>
  </tr>
  <tr>
    <td align="center">NIT: 42897128</td>
  </tr>
  <tr>
    <td align="center">DOCUMENTO EQUIVALENTE</td>
  </tr>
  <tr>
    <td align="center">REGIMEN SIMPLICADO</td>
  </tr>
  <tr>
    <td align="center">FACTURA N°: <?php echo $model->seccion_pk;?><?php if ($model->estado_anulado == "SI"){ echo "ANULADO";}else {echo "";}?></td>
  </tr>
  <tr>
    <td align="center">CC VIVA ENVIGADO LOCAL 234B TEL: 557 86 78</td>
  </tr>
  <tr>
    <td align="center">FECHA: <?php echo $model->fecha_pago;?> </td>
  </tr>
  <tr>
    <td align="center">CLIENTE: <?php echo $model->clienteFk->nombrecompletosinidentificacion;?> </td>
  </tr>
  <tr>
    <td align="center">C.C.: <?php echo $model->clienteFk->identificacion;?> </td>
  </tr>
  <tr>
      <td align="center">TOTAL: <?php echo '$ '.number_format($model->total_pago);?><?php if ($model->estado_anulado == "SI"){ echo "ANULADO";}else {echo "";}?></td>
  </tr>  
  <tr>
    <td align="center">CAJERO: <?php echo $usuario->nombrecompleto;?> </td>
  </tr>
  <tr>
    <td align="center"><h3>GRACIAS POR PREFERIRNOS</h3></td>
  </tr>
</table>


</body>
</html>