<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
        <title>Principal</title>
        <link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
       
        <script type="text/javascript" src="../js/validar.js"></script>
        <script language="javascript">

        function cancelar() {
                location.href="index.php";
        }

        var cursor;
        if (document.all) {
        // Está utilizando EXPLORER
        cursor='hand';
        } else {
        // Está utilizando MOZILLA/NETSCAPE
        cursor='pointer';
        }

        function limpiar() {
                document.getElementById("formulario").reset();
        }

        </script>
    </head>
    <body>
        <div id="pagina">
            <div id="zonaContenido">
                <div align="center">
                    <div id="tituloForm" class="header">INSERTAR SUBGRUPO </div>
                    <div id="frmBusqueda">
                        <form id="formulario" name="formulario" method="post" action="save_subgrupo.php">
                            <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
                                <tr>
                                    <td width="15%">Codigo (Maximo 6 caracteres)</td>
                                    <td width="43%"><input NAME="Acodigo" type="text" class="cajaGrande" id="cogigo" size="6" maxlength="6"></td>
                                    <td width="42%" rowspan="8" align="left" valign="top"><ul id="lista-errores"></ul></td>
                                </tr>
                                <tr>
                                    <td width="15%">Nombre</td>
                                    <td width="43%"><input NAME="Anombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45"></td>

                                </tr>
                                <?php 
                                    include_once '../conexion/conexion.php';
                                    include_once 'class/subgrupo.php';
                                    $usuario = new ServidorBaseDatos();
                                    $conn= $usuario->getConexion();

                                    $subgrupo= new Subgrupo();
                                    $rows = $subgrupo->listado_grupos($conn);
                                ?>
                                <tr>
                                    <td width="17%">Grupo</td>
                                    <td><select id="cboFamilias" name="AcboGrupos" class="comboGrande">
                                            <option value="0">Seleccione un Grupo</option>
                                            <?php 
                                                foreach ($rows as $row => $value)
                                                {
                                            ?>
                                            <option value="<?php  echo $rows[$row]['id_grupo']?>"><?php  echo $rows[$row]['nombre']?></option>
                                            <?php 
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>

                            </table>
                    </div>

                    <div id="botonBusqueda">
                        <img src="../img/botonaceptar.jpg" width="85" height="22" onClick="validar(formulario,true)" border="1" onMouseOver="style.cursor=cursor">
                        <img src="../img/botonlimpiar.jpg" width="69" height="22" onClick="limpiar()" border="1" onMouseOver="style.cursor=cursor">
                        <img src="../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar()" border="1" onMouseOver="style.cursor=cursor">
                        <input id="accion" name="accion" value="alta" type="hidden">
                        <input id="id" name="Zid" value="" type="hidden">
                    </div>
            </form>
            </div>
            </div>
        </div>
    </body>
</html>