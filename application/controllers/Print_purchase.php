<?php
/**
 * Created by PhpStorm.
 * User: Gomez
 * Date: 27/12/2017
 * Time: 2:29 PM
 */

class Print_purchase extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_model');
    }

    public function print_detail()
    {
        $pruchase_id = $this->input->post('id');
        $data = $this->purchase_model->get_data_print($pruchase_id);

        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Letter');
        // $ingresos  = $this->flujo_caja_model->obtener_ingresos($fecha_inicio,$fecha_fin); array(216, 279)
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        /* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
         */
        $this->pdf->SetTitle("C O M P R A S");
        /* La variable $x se utiliza para mostrar un número consecutivo */

        /* titulo de ingreso*/
        $var_img = base_url() . 'assets/images/'.$data['branch_office']->imagen;
        $this->pdf->Image($var_img, 10, 10, 80, 28);
        /*  NIT Y NRO FACTURA   */

        /* 1ra fila   */
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(115, 5, '', 0, 0, 'C');
        $this->pdf->Cell(80, 5, utf8_decode($data['company']->nombre_empresa), 0, 0, 'C');

        /*2da fila*/
        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->Cell(40, 5, 'C O M P R A ', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 000, 000);
        $this->pdf->MultiCell(80, 5,utf8_decode($data['branch_office']->nombre_comercial), 0, 'C');

        /*3ra fila*/
        $this->pdf->Ln(0);
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->Cell(40, 5, utf8_decode('Nº '.$data['purchase']['nro_compra']), 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 000, 000);
        $this->pdf->MultiCell(80, 4,utf8_decode($data['branch_office']->direccion), 0, 'C');

        /*4ta fila*/
        $this->pdf->Ln(0);
        $this->pdf->Cell(115, 5, '', 0, 0, 'C');
        $this->pdf->Cell(80, 4, 'Telf. ' . utf8_decode($data['branch_office']->telefono), 0,0,'C');
        /*5ta fila*/
        $this->pdf->Ln(4);
        $this->pdf->Cell(115, 5, '', 0);
        $this->pdf->Cell(80, 4, utf8_decode($data['branch_office']->ciudad), 0, 0, 'C');

        $this->pdf->Ln(12);

        /*$anio = substr($data['purchase']->fecha_registro, 0, 4);
        $mes  = substr($data['purchase']->fecha_registro, 5, 2);
        $dia  = substr($data['purchase']->fecha_registro, 8,2);
        $sale_date = $dia.' de '.get_month($mes).' del '.$anio;*/
        /*  LUGAR Y FECHA ///// NIT CI*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'Proveedor:', 'TL');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(95, 5, utf8_decode($data['provider']['nombre']).' - '.$data['provider']['nit'], 'T');
        $this->pdf->Cell(77, 5, '', 'TR');

        $this->pdf->Ln(5);


        /*  Montos */
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, utf8_decode('Subtotal   :'), 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(44, 5, number_format($data['purchase']['monto_subtotal'],CANTIDAD_MONTO_DECIMAL), '');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, utf8_decode('Dcto 1 : '), '');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(44, 5, number_format($data['purchase']['descuento_uno'],CANTIDAD_MONTO_DECIMAL), '');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, utf8_decode('Dcto 2 : '), '');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(44, 5, number_format($data['purchase']['descuento_dos'],CANTIDAD_MONTO_DECIMAL), 'R');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Ln(5);
        $this->pdf->Cell(20, 5, utf8_decode('Dcto 3 : '), 'LB');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(44, 5, number_format($data['purchase']['descuento_tres'],CANTIDAD_MONTO_DECIMAL), 'B');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, utf8_decode('Total : '), 'B');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(108, 5, number_format($data['purchase']['monto_total'],CANTIDAD_MONTO_DECIMAL), 'BR');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Ln(7);

        /*  DETALLE DE ITEMS */
        $this->pdf->SetMargins(10, 10, 10);
        $this->pdf->SetFont('Arial', 'B', 8);

        /* Encabezado de la columna*/
        $this->pdf->Cell(10, 5, "NRO", 1, 0, 'C');
        $this->pdf->Cell(22, 5, "CODIGO", 1, 0, 'C');
        $this->pdf->Cell(72, 5, "PRODUCTO", 1, 0, 'C');
        $this->pdf->Cell(20, 5, "P. UNITARIO", 1, 0, 'C');
        $this->pdf->Cell(20, 5, "P. COSTO", 1, 0, 'C');
        $this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
        $this->pdf->Cell(28, 5, "M. TOTAL", 1, 0, 'C');
        $this->pdf->Ln(5);

        /*  detalle*/
        $nro = 1;
        $purchase_detail = (ARRAY)$data['detail'];
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetAligns(array('C', 'L', 'R'));
        $cantidad_filas = 0;
        $numero_items = 15;
        $estilo = 'RL';
        foreach ($purchase_detail as $row_detalle) {
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($cantidad_filas==count($purchase_detail)) {
                $estilo = 'LRB';
            }

            $this->pdf->Cell(10, 4, utf8_decode($cantidad_filas), $estilo, 0, 'C');
            $this->pdf->Cell(22, 4, utf8_decode($row_detalle['producto_codigo']), $estilo, 0, 'L');
            $this->pdf->Cell(72, 4, utf8_decode($row_detalle['producto_nombre']), $estilo, 0, 'R');
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle['precio_unitario']), $estilo, 0, 'R');
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle['precio_costo']), $estilo, 0, 'R');
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle['cantidad']), $estilo, 0, 'R');
            $this->pdf->Cell(28, 4, utf8_decode(number_format(($row_detalle['cantidad'] * $row_detalle['precio_costo']),2)), $estilo, 0, 'R');
            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }
        /*$cantidad_filas = 40;
        for ($index = 0;$index<=40; $index++ ){
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($cantidad_filas==$index) {
                $estilo = 'LRB';
            }
            $this->pdf->Cell(10, 4, utf8_decode($cantidad_filas), $estilo, 0, 'C');
            $this->pdf->Cell(18, 4, utf8_decode('produ'), $estilo, 0, 'L');
            $this->pdf->Cell(72, 4, utf8_decode('proombre'), $estilo, 0, 'R');
            $this->pdf->Cell(20, 4, utf8_decode('precioio'), $estilo, 0, 'R');
            $this->pdf->Cell(20, 4, utf8_decode('preosto'), $estilo, 0, 'R');
            $this->pdf->Cell(20, 4, utf8_decode('candad'), $estilo, 0, 'R');
            $this->pdf->Cell(32, 4, utf8_decode(number_format(10,2)), $estilo, 0, 'R');
            $this->pdf->Ln(4);
        }*/

        // Convertimos el monto en literal
        include APPPATH.'/libraries/convertidor.php';
        $v = new EnLetras();
        /*$valor = $v->ValorEnLetras($data['purchase']['monto_total'], " ");
        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'Son:', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, $valor, 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(35, 5, $data['purchase']['monto_total'], 1, 0, 'R');*/
        $this->pdf->Ln(8);

        $this->pdf->Output("Nota Venta".'B'.".pdf", 'I');
    }
}