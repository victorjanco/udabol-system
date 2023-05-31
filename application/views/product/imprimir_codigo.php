<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 21/10/2017
 * Time: 03:52 AM
 */
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/estilos_factura/stilo.css') ?>" media="screen">
    <!--<link rel="stylesheet" type="text/css" href="../css/print/print.css" media="print">-->
    <link href="<?= base_url('assets/estilos_factura/printListaTransac.css') ?>" rel="stylesheet" type="text/css" media="print"/>
    <link href="<?= base_url('assets/estilos_factura/estiloReportes.css') ?>" rel="stylesheet" type="text/css"/>
    <style type="text/css" media="print">
        @page{
            margin: 0.5cm;
        }
        table tr td{
            font-size: 9pt;
        }
    </style>
    <script src="<?= base_url('assets/js/jquery-2.2.4.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
</head>
<body>
<script language="JavaScript">
    var producto=<?= json_encode(isset($producto)? $producto : []); ?>;
    var inventario_sucursal=<?= json_encode(isset($inventario_sucursal)? $inventario_sucursal : []); ?>;
    var productos=<?= json_encode(isset($productos)? $productos : []); ?>;
    var tipo=<?= json_encode($tipo); ?>;

    $(document).ready(function ()
    {   
        if(tipo==1){
            var text_barcode=producto.codigo+" Bs " + parseInt(inventario_sucursal.precio_venta).toFixed(2);
            JsBarcode("#codigo",producto.codigo,
            {text: text_barcode,
                fontOptions: "bold",
                fontSize: 15,
                width: 1.5, 
                height: 50,
                marginTop: 2, // Margen superior
                marginRight: 2, // Margen derecho
                marginBottom: 2, // Margen inferior
                marginLeft: 2, // Margen izquierdo
               });
        }else{
            const $contenedor = document.querySelector("#contenedor");
            var xmlns = "http://www.w3.org/2000/svg";
            productos.forEach(producto => {
                // const elemento = document.createElement("SVG");
                const elemento = document.createElementNS(xmlns, "svg");
                // elemento.dataset.format = "CODE128";
                elemento.dataset.value = producto.codigo;
                // elemento.dataset.text = producto.codigo +"-"+ producto.nombre_comercial;
                elemento.dataset.text = producto.codigo + " Bs " + parseInt(producto.precio_venta).toFixed(2);
                elemento.classList.add("codigo");
                elemento.style.height="100%";
                elemento.style.width="100%";
                $contenedor.appendChild(elemento);
            });
            JsBarcode(".codigo")
            .options({
                fontOptions: "bold",
                fontSize: 15,
                width: 1.5, 
                height: 50,
                marginTop: 2, // Margen superior
                marginRight: 2, // Margen derecho
                marginBottom: 2, // Margen inferior
                marginLeft: 2, // Margen izquierdo
            })
            .init();
        }

        doPrint();
    });

    function doPrint()
    {
        //document.all.item("mostrarUser").style.visibility='visible';
        window.print();
        //document.    {
//        all.item("mostrarUser").style.visibility='hidden';
    }
    function Salir(dato)
    {
        window.close();
    }
    
</script>
<div id="noprint">
    <table>
        <tr>
            <td>
                <button type="button" name="volver" id="volver" onclick="Salir()">Salir</button>
            </td>
            <td>&nbsp;&nbsp;</td>
            <td>
                <button type="button" onclick="doPrint()">Imprimir</button>
            </td>
        </tr>
    </table>
</div>
<div id="hoja">
    <?php
    if ($tipo == 1){
        ?>
        <div style="text-align: center;padding-top: 4mm; padding-right: 3mm; width: 45mm; height: 15mm">
                <svg width="100%" height="100%" id="codigo" ></svg>
        </div>
    <?php
    }else{ ?>
        
        <div style="text-align: center;padding-top: 4mm; padding-right: 3mm; width: 45mm; height: 15mm" id="contenedor">
            <!-- <br>
            <br> -->
        </div>
            
    <?php
    }
    ?>
</div>
</body>
</html>
