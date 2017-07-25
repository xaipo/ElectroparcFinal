<?php
/**
 * Created by PhpStorm.
 * User: VICTOR OQUENDO
 * Date: 7/17/2017
 * Time: 9:14 PM
 */

include ("../js/fechas.php");
include ("../conexion/conexion.php");
$usuario = new ServidorBaseDatos();
$conn = $usuario->getConexion();


// inicio datos retencion ------------------------------------------------------------------------------------------------------------------
$idretencion=$_GET["idretencion"];

$query_ret="SELECT r.id_factura as id_factura, r.serie1 as serie1, r.serie2 as serie2, r.codigo_retencion as codigo_retencion, r.autorizacion as autorizacion, r.concepto as concepto, r.totalretencion as totalretencion, r.fecha as fecha
            FROM retencion r 
            WHERE r.id_retencion=$idretencion";
$res_ret=mysql_query($query_ret,$conn);

$idfactura=mysql_result($res_ret,0,"id_factura");

$codigo_retencion=mysql_result($res_ret,0,"codigo_retencion");
$serie1=mysql_result($res_ret,0,"serie1");
$serie2=mysql_result($res_ret,0,"serie2");
$autorizacion=mysql_result($res_ret,0,"autorizacion");
$concepto=mysql_result($res_ret,0,"concepto");
$concepto_parte=substr($concepto,0,255);


$totalretencion=mysql_result($res_ret,0,"totalretencion");
$fecha=mysql_result($res_ret,0,"fecha");

//fin datos retencion ------------------------------------------------------------------------------------------------------------------

//inicio datos proveedor ------------------------------------------------------------------------------------------------------------------
$query_prov="SELECT fp.id_proveedor id_proveedor, p.empresa as empresa, p.ci_ruc as ci_ruc, p.direccion as direccion, fp.tipocomprobante as tipocomprobante,
                    fp.serie1 as serie1, fp.serie2 as serie2, fp.codigo_factura as codigo_factura
             FROM proveedor p INNER JOIN facturasp fp ON p.id_proveedor = fp.id_proveedor
             WHERE fp.id_facturap= $idfactura";
$res_prov=mysql_query($query_prov,$conn);

$id_prov=mysql_result($res_prov,0,"id_proveedor");
$empresa=mysql_result($res_prov,0,"empresa");
$ci_ruc=mysql_result($res_prov,0,"ci_ruc");
$direccion=mysql_result($res_prov,0,"direccion");
$tipocomprob=mysql_result($res_prov,0,"tipocomprobante");
switch ($tipocomprob)
{
    // 1 FACTURA
    case 1:
        $comprobante="FACTURA";
        break;
    // 2 LIQUIDACIONES DE COMPRA
    case 2:
        $comprobante="LIQUIDACIONES DE COMPRA";
        break;
    // 3 NOTA DE VENTA
    case 3:
        $comprobante="NOTA DE VENTA";
        break;
}
$factura_serie1=mysql_result($res_prov,0,"serie1");
$factura_serie2=mysql_result($res_prov,0,"serie2");
$numerocomprobante=mysql_result($res_prov,0,"codigo_factura");


$query_fono="SELECT numero FROM proveedorfono WHERE id_proveedor = $id_prov";
$res_fono=mysql_query($query_fono,$conn);
if(mysql_num_rows($res_fono)>0)
{
    $telefono=mysql_result($res_fono,0,"numero");
}
else
{
    $telefono="-----";
}
//fin datos proveedor ------------------------------------------------------------------------------------------------------------------


class Pro
{
    public $ejercicio_fiscal;
    public $base_imponible;
    public $tiporetencion;
    public $codigo_impuesto;
    public $porcentaje_retencion;
    public $valor_retenido;
}
$lineas_array = array();

$sel_lineas="SELECT rt.ejercicio_fiscal as ejercicio_fiscal, rt.base_imponible as base_imponible, rt.impuesto as impuesto,
                                                            rt.codigo_impuesto as codigo_impuesto, rt.porcentaje_retencion as porcentaje_retencion,
                                                            rt.valor_retenido as valor_retenido
                                                            FROM retenlinea rt  WHERE rt.id_retencion = '$idretencion'";
$rs_lineas=mysql_query($sel_lineas,$conn);

$totalfilas=mysql_num_rows($rs_lineas);
for ($i = 0; $i < $totalfilas; $i++) {

    $myPro = new Pro();

    $ejercicio_fiscal = mysql_result($rs_lineas, $i, "ejercicio_fiscal");
    $base_imponible = mysql_result($rs_lineas, $i, "base_imponible");
    $codigo_impuesto = mysql_result($rs_lineas, $i, "codigo_impuesto");
    $porcentaje_retencion = mysql_result($rs_lineas, $i, "porcentaje_retencion");
    $valor_retenido = mysql_result($rs_lineas, $i, "valor_retenido");

    $query_codret = "SELECT tipo FROM codretencion WHERE codigo = $codigo_impuesto";
    $res_codret = mysql_query($query_codret, $conn);
    $tiporetencion = mysql_result($res_codret, 0, "tipo");

    $myPro->ejercicio_fiscal = $ejercicio_fiscal;
    $myPro->base_imponible = $base_imponible;
    $myPro->tiporetencion = $tiporetencion;
    $myPro->codigo_impuesto = $codigo_impuesto;
    $myPro->porcentaje_retencion = $porcentaje_retencion;
    $myPro->valor_retenido = $valor_retenido;

    $lineas_array[]= $myPro;
}

$lineas_sent = json_encode($lineas_array);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script src="../js/jspdf.min.js"></script>
</head>
<body>


</body>


<script>
   

        //    console.log('hello world');

        var doc = new jsPDF('p', 'mm');

        var x=-2;
        var y=-18;
        doc.setFontSize(10);
        doc.text(x+45, y+51, '<?php echo utf8_decode($empresa)?>');//razon social
        doc.text(x+160, y+51, '<?php echo $ci_ruc?>');//cedula
        doc.text(x+45, y+58, 'Riobamba <?php echo $fecha?>');//Lugar y Fecha Emision
        doc.text(x+160, y+58, '<?php echo $comprobante?>');//TipoComprobante Venta
        doc.text(x+160, y+66, '<?php echo $factura_serie1?> - <?php echo $factura_serie2?> - <?php echo $numerocomprobante?>');//Numero comprobante de venta
        doc.setFontSize(8);
        doc.text(x+35, y+66, '<?php echo strtoupper(utf8_decode($direccion))?>');//Direccion
        doc.text(x+40, y+72, '<?php echo utf8_decode($concepto_parte)?>');//Concepto

        /*Ejercicio Fiscal*/


        var lineas_array = (<?php  echo $lineas_sent;?>);
        var n = lineas_array.length;




        var x2=x+8;
        var y2=y+85;


        for(var i=0;i<n;i++)//solo para 6 cuando se pase a implantar cambiar el 6 x var n=lista.lenght
        {
            x2=x+8;
            doc.text(x2, y2, lineas_array[i].ejercicio_fiscal);//ejercicio_fiscal

            x2+=33;
            doc.text(x2, y2, lineas_array[i].base_imponible);//base_imponible

            x2+=33;
            doc.text(x2, y2, lineas_array[i].tiporetencion);//tiporetencion

            x2+=33;
            doc.text(x2, y2, lineas_array[i].codigo_impuesto);//codigo_impuesto

            x2+=31;
            doc.text(x2, y2, lineas_array[i].porcentaje_retencion);//porcentaje_retencion

            x2+=30;
            doc.text(x2, y2, lineas_array[i].valor_retenido);//valor_retenido

            y2+=4;

        }

       y2+= ((6-n)*4);

        x2+=30;
        y2+=3;
        doc.text(x2, y2, '<?php echo $totalretencion?>');//Total Retencion

        doc.save('Test.pdf');


</script>

</html>
