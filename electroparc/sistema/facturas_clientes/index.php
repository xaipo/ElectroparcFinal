<?php 
session_start();
//id facturero segun el usuario de la sesion
$id_facturero=$_SESSION['id_facturero'];
$tipo=$_SESSION['tipo'];
if($id_facturero == -1){
	$id_facturero = 1;
}

?>

<html>
	<head>
		<title>Ventas</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function inicio() {
			document.getElementById("form_busqueda").submit();
		}
		
		function nuevo_facturaventa() {
			location.href="new_facturaventa.php?idfacturero=<?php  echo $id_facturero?>&tipo=<?php  echo $tipo?>";
		}		
		</script>
	</head>
	<body onLoad="inicio()">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">factura ventas</div>
				<div id="frmBusqueda">
                                    <form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">                                
                                        <br/>
                                        <img src="../img/botonnuevafactura.jpg" border="0" title ="nuevo facturaventa"  border="1" onClick="nuevo_facturaventa()" onMouseOver="style.cursor=cursor">
                                </div>
                                     </form>
				<div id="lineaResultado">
					<iframe width="100%" height="800px" id="frame_rejilla" name="frame_rejilla" frameborder="0">
						<ilayer width="100%" height="800px" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
