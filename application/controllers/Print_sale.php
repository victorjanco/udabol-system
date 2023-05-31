<?php

/**
 * Created by PhpStorm.
 * User: Gomez
 * Date: 27/12/2017
 * Time: 2:29 PM
 */
class Print_sale extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('sale_model');
		$this->load->model('product_model');
		$this->load->model('credit_payment_model');
		$this->load->model('office_model');
		
	}

	public function print_note_sale()
	{
		if ($this->input->post()) {
			$sale_id = $this->input->post('id');
//            echo json_encode($this->sale_model->get_print_note_sale($sale_id));
			$data_sale = $this->sale_model->get_print_note_sale($sale_id);

			$this->load->library('pdf');
			$this->pdf = new Pdf('P', 'mm', 'Legal');

			$this->pdf->AddPage();
			// Define el alias para el número de página que se imprimirá en el pie
			$this->pdf->AliasNbPages();

			/* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
             */
			$this->pdf->SetTitle("NOTA");
			/* La variable $x se utiliza para mostrar un número consecutivo */

			/* titulo de ingreso*/
			$var_img = base_url() . 'assets/images/'.$data_sale['branch_office']->imagen;
			$this->pdf->Image($var_img, 10, 10, 80, 28);
			/*  NIT Y NRO FACTURA   */


			/* 1ra fila   */
//			$this->pdf->SetFont('Arial', 'B', 8);
//			$this->pdf->Cell(115, 5, '', 0, 0, 'C');
//			$this->pdf->Cell(80, 5, utf8_decode($data_sale['company']->nombre_empresa), 0, 0, 'C');

			/*2da fila*/
			$this->pdf->Ln(5);
			$this->pdf->SetFont('Arial', 'B', 12);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(85, 5, '', 0, 0, 'C');
			$this->pdf->Cell(40, 5, $data_sale['sale']->tipo_venta, 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 9);
			$this->pdf->SetTextColor(0, 000, 000);
			$this->pdf->MultiCell(70, 5, utf8_decode($data_sale['branch_office']->nombre_comercial), 0, 'C');

			/*3ra fila*/
			$this->pdf->Ln(0);
			$this->pdf->SetFont('Arial', 'B', 12);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(85, 5, '', 0, 0, 'C');
			$this->pdf->Cell(40, 5, utf8_decode('Nº' . $data_sale['sale_branch_office']->nro_venta), 0, 0, 'C');
			$this->pdf->SetFont('Arial', '', 9);
			$this->pdf->SetTextColor(0, 000, 000);
			$this->pdf->MultiCell(70, 4, utf8_decode($data_sale['branch_office']->direccion), 0, 'C');

			/*4ta fila*/
			$this->pdf->Ln(0);
			$this->pdf->Cell(121, 5, '', 0, 0, 'C');
			$this->pdf->MultiCell(80, 4, 'Telf. ' . utf8_decode($data_sale['branch_office']->telefono), 0, 'C');

			/*5ta fila*/
//			$this->pdf->Ln(4);
			$this->pdf->Cell(87, 5, '', 0);
			$this->pdf->SetFont('Arial', 'B', 12);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(40, 5, utf8_decode($data_sale['sale']->codigo_recepcion), 0, 0, 'C');
			$this->pdf->SetFont('Arial', '', 9);
			$this->pdf->SetTextColor(0, 000, 000);
			$this->pdf->Cell(70, 4, utf8_decode($data_sale['branch_office']->ciudad_impuestos), 0, 0, 'C');

			$this->pdf->Ln(12);

			$anio = substr($data_sale['sale']->fecha_registro, 0, 4);
			$mes = substr($data_sale['sale']->fecha_registro, 5, 2);
			$dia = substr($data_sale['sale']->fecha_registro, 8, 2);
			$sale_date = $dia . ' de ' . get_month($mes) . ' del ' . $anio;
			/*  LUGAR Y FECHA ///// NIT CI*/
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, 'Lugar y Fecha : ', 'TL');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(100, 5, utf8_decode($data_sale['branch_office']->ciudad) . ', ' . $sale_date, 'T');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(15, 5, utf8_decode('Telefono : '), 'T');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(55, 5, utf8_decode($data_sale['customer']->telefono1), 'TR');
			// $this->pdf->Cell(70, 5, '', 'TR');

			$this->pdf->Ln(5);


			/*  CLIENTE */
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, utf8_decode('Señor(es)         : '), 'LB');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(100, 5, utf8_decode($data_sale['customer']->nombre), 'B');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(15, 5, utf8_decode('CI            : '), 'B');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(55, 5, utf8_decode($data_sale['customer']->ci), 'RB');
			$this->pdf->Ln(7);

			/*  DETALLE DE ITEMS */
			$this->pdf->SetMargins(10, 10, 10);
			$this->pdf->SetFont('Arial', 'B', 8);

			/* Encabezado de la columna*/
			$this->pdf->Cell(8, 5, "ITEM", 1, 0, 'C');
			$this->pdf->Cell(27, 5, "CODIGO", 1, 0, 'C');
			$this->pdf->Cell(82, 5, "DESCRIPCION", 1, 0, 'C');
			$this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
			$this->pdf->Cell(23, 5, "P. UNITARIO", 1, 0, 'C');
			$this->pdf->Cell(32, 5, "SUBTOTAL", 1, 0, 'C');
			$this->pdf->Ln(5);

			/*  detalle*/
			$nro = 1;
			$sale_detail = (ARRAY)$data_sale['sale_detail'];
			$this->pdf->SetFont('Arial', '', 8);
			/*$this->pdf->SetAligns(array('C', 'L', 'R'));*/
			$cantidad_filas = 0;
			$numero_items = 0;
			$estilo = 'RL';
			$total_detail = 10;

			//Table with 20 rows and 4 columns
			$this->pdf->SetWidths(array(8, 27, 82, 20, 23, 32));
			$this->pdf->SetAligns(array('C', 'C', 'L', 'C', 'R', 'R'));


			foreach ($sale_detail as $row_detalle) {
				$cantidad_filas++;
				$productos = $this->product_model->get_product_by_id($row_detalle->producto_id);
				// echo json_encode($productos);
				if(intval($productos->tipo_producto_id)==2){//producto servicio
					$this->pdf->Row(array(
						utf8_decode($cantidad_filas),
						utf8_decode(isset($productos->codigo)? $productos->codigo:'SERVICIO'),
						utf8_decode($row_detalle->descripcion),
						utf8_decode(number_format($row_detalle->cantidad)),
						utf8_decode($row_detalle->precio_venta_descuento),
						utf8_decode(number_format(($row_detalle->total),2))
					));
				}else{
					$this->pdf->Row(array(
						utf8_decode($cantidad_filas),
						utf8_decode(isset($productos->codigo)? $productos->codigo:'SERVICIO'),
						utf8_decode($productos->nombre_comercial."(".$productos->dimension.")"),
						//utf8_decode($productos->nombre_comercial."(".$productos->dimension.")(".$productos->imei1.")(".$productos->imei2.")"),
						utf8_decode(number_format($row_detalle->cantidad)),
						utf8_decode($row_detalle->precio_venta_descuento),
						utf8_decode(number_format(($row_detalle->total),2))
					));
				}
				

				$nro = $nro + 1;
				$numero_items = $numero_items + 1;
			}

			while ($total_detail - 1 >= $numero_items) {

				$cantidad_filas++;
				$estilo = 'RL';
				if ($nro == 1) {
					$estilo = $estilo . 'T';
				}
				if ($cantidad_filas == $total_detail) {
					$estilo = 'LRB';
				}

				$this->pdf->Cell(8, 4, '', $estilo, 0, 'C');
				$this->pdf->Cell(27, 4, '', $estilo, 0, 'L');
				$this->pdf->Cell(82, 4, '', $estilo, 0, 'L');
				$this->pdf->Cell(20, 4, '', $estilo, 0, 'C');
				$this->pdf->Cell(23, 4, '', $estilo, 0, 'R');
				$this->pdf->Cell(32, 4, '', $estilo, 0, 'R');
				$this->pdf->Ln(4);
				$numero_items = $numero_items + 1;
			}

			// Convertimos el monto en literal
			include APPPATH . '/libraries/convertidor.php';
			$v = new EnLetras();
			$valor = utf8_decode($v->ValorEnLetras($data_sale['sale']->total, "Bolivianos"));
			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(7, 5, 'Son:', 'LTB', 0, 'L');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(129, 5, $valor, 'TBR', 0, 'L');
			$this->pdf->Cell(1, 5, '', '', 0, 'L');

			if ($data_sale['sale']->descuento > 0) {
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(23, 5, 'SubTotal :', 1, 0, 'R');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->Cell(32, 5, $data_sale["sale"]->subtotal, 1, 0, 'R');

				$this->pdf->Ln(5);
				$this->pdf->Cell(137, 5, '', '', 0, 'L');
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(23, 5, 'Descuento :', 1, 0, 'R');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->Cell(32, 5, $data_sale["sale"]->descuento, 1, 0, 'R');

				$this->pdf->Ln(5);
				$this->pdf->Cell(137, 5, '', '', 0, 'L');
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(23, 5, 'Total :', 1, 0, 'R');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->Cell(32, 5, $data_sale["sale"]->total, 1, 0, 'R');
			} else {
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(23, 5, 'TOTAL :', 1, 0, 'R');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->Cell(32, 5, $data_sale["sale"]->total, 1, 0, 'R');
			}

			$print_day = date('Y-m-d');
			$print_hour = date('H:i:s');
			$year = substr($print_day, 0, 4);
			$month = substr($print_day, 5, 2);
			$day = substr($print_day, 8, 2);
			$print_date = $day . ' de ' . get_month($month) . ' del ' . $year;

			$this->pdf->Ln(7);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(26, 5, 'Fecha Impresion :', 'TBL', 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(40, 5, $print_date, 'BT', 0, 'L');

			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(30, 5, 'Hora Impresion :', 'BT', 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(30, 5, $print_hour, 'BT', 0, 'L');

			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, 'Usuario :', 'BT', 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(44, 5, $data_sale['user']->usuario, 'BTR', 0, 'L');

		

			/* GLOSA*/
			$this->pdf->Ln(6);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, 'Garantia por desperfectos de fabrica: ', 'TL');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(100, 5, '', 'T');
			$this->pdf->Cell(70, 5, '', 'TR');
			$this->pdf->Ln(5);


			/*  GLOSA */
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, utf8_decode('Celulares homologados 1 año'), 'L');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(170, 5, '', 'R');
			$this->pdf->Ln(5);

			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, utf8_decode('Celulares no homologados, accesorios y repuestos originales 3 meses'), 'L');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(170, 5, '', 'R');
			$this->pdf->Ln(5);

			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, utf8_decode('Accesorios y repuestos alternativos 30 dias calendario'), 'LB');
			$this->pdf->SetFont('Arial', '', 8);
			
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(170, 5, '', 'RB');
			$this->pdf->Ln(7);

			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(10, 5, 'Glosa:', 'LTB', 0, 'L');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(182, 5, $data_sale["sale"]->glosa, 'TBR', 0, 'L');
			$this->pdf->Cell(40, 5, '', '', 0, 'L');

			$this->pdf->Output("Nota Venta Nro. " . $data_sale['sale_branch_office']->nro_venta . ".pdf", 'I');
			
		} else {
			show_404();
		}


	}

	public function print_invoice_sale()
	{

		if ($this->input->post()) {
			$invoice_id = $this->input->post('id');
			$data_invoice = $this->sale_model->get_print_invoice_sale($invoice_id);
			$this->load->library('pdf');
			$this->pdf = new Pdf('P', 'mm', 'Legal');

			$this->pdf->AddPage();
			// Define el alias para el número de página que se imprimirá en el pie
			$this->pdf->AliasNbPages();

			/* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
             */
			$this->pdf->SetTitle("FACTURA");
			/* La variable $x se utiliza para mostrar un número consecutivo */

			/* titulo de ingreso*/
			$var_img = base_url() . 'assets/images/'.$data_invoice['branch_office']->imagen;
			$this->pdf->Image($var_img, 13, 10, 60, 20);
			/*  NIT Y NRO FACTURA   */

			/* 1ra fila   */
			$this->pdf->Cell(130, 5, '', 0);
			$this->pdf->SetFont('Arial', 'B', 9);
			$this->pdf->Cell(30, 5, 'NIT', 'TL');
			$this->pdf->SetFont('Arial', '', 9);
			$this->pdf->Cell(32, 5, ': ' . get_branch_office_nit_in_session(), 'TR', 0, 'L');
			$this->pdf->Ln(5);
			$this->pdf->Cell(130, 5, '', 0);
			$this->pdf->SetFont('Arial', 'B', 9);
			$this->pdf->Cell(30, 5, utf8_decode('Nº FACTURA'), 'L');
			$this->pdf->SetFont('Arial', '', 9);
			$this->pdf->Cell(32, 5, ': ' . $data_invoice['invoice']->nro_factura, 'R', 0, 'L');
			$this->pdf->Ln(5);
			$this->pdf->SetFont('Arial', 'B', 16);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(70, 5, '', 0, 0, 'C');
			$this->pdf->Cell(60, 5, 'FACTURA', 0, 0, 'C');
			$this->pdf->SetTextColor(0, 000, 000);
			$this->pdf->SetFont('Arial', 'B', 9);
			$this->pdf->Cell(30, 5, utf8_decode('Nº AUTORIZACION'), 'BL');
			$this->pdf->SetFont('Arial', '', 9);
			$this->pdf->Cell(32, 5, ': ' . $data_invoice['invoice']->nro_autorizacion, 'RB', 0, 'L');


			$this->pdf->Ln(9);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(80, 5, utf8_decode($data_invoice['company']->nombre_empresa), 0, 0, 'C');


			$this->pdf->Ln(3);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(80, 5, utf8_decode($data_invoice['branch_office']->nombre), 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 13);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(40, 5, '', 0, 0, 'C');
			$this->pdf->Cell(70, 5, 'ORIGINAL', 0, 0, 'C');


			/*2da fila*/
			$this->pdf->Ln(5);
			$this->pdf->SetAligns('C');
			$this->pdf->SetTextColor(0, 000, 000);

			$this->pdf->SetLeftMargin(10);
			$this->pdf->SetRightMargin(115);
			$this->pdf->SetFont('Arial', '', 7);
			$html_datos = utf8_decode($data_invoice['branch_office']->direccion);
			$this->pdf->WriteHTML($html_datos);

			$this->pdf->Ln(5);
			$this->pdf->SetRightMargin(10);
			$this->pdf->SetLeftMargin(130);

			$this->pdf->SetFont('Arial', '', 8);
			$html_activity = utf8_decode($data_invoice['activity']->nombre);
			$this->pdf->WriteHTML($html_activity);

			$this->pdf->SetLeftMargin(10);


			/*4ta fila*/
			$this->pdf->Ln(-2);
			$this->pdf->Cell(80, 4, 'Telf. ' . utf8_decode($data_invoice['branch_office']->telefono), 0, 0, 'C');

			/*5ta fila*/
			$this->pdf->Ln(3);
			$this->pdf->Cell(80, 4, utf8_decode($data_invoice['branch_office']->ciudad_impuestos), 0, 0, 'C');

			$this->pdf->Ln(5);

			$anio = substr($data_invoice['invoice']->fecha, 0, 4);
			$mes = substr($data_invoice['invoice']->fecha, 5, 2);
			$dia = substr($data_invoice['invoice']->fecha, 8, 2);
			$sale_date = $dia . ' de ' . get_month($mes) . ' del ' . $anio;
			/*  LUGAR Y FECHA ///// NIT CI*/
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, 'Lugar y Fecha : ', 'TL');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(100, 5, utf8_decode($data_invoice['branch_office']->ciudad) . ', ' . $sale_date, 'T');
			$this->pdf->Cell(70, 5, '', 'TR');

			$this->pdf->Ln(5);


			/*  CLIENTE */
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, utf8_decode('Señor(es)         : '), 'LB');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(100, 5, utf8_decode($data_invoice['invoice']->nombre_cliente), 'B');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(6, 5, utf8_decode('Nit : '), 'B');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(64, 5, utf8_decode($data_invoice['invoice']->nit_cliente), 'RB');
			$this->pdf->Ln(7);

			/*  DETALLE DE ITEMS */
			$this->pdf->SetMargins(10, 10, 10);
			$this->pdf->SetFont('Arial', 'B', 8);

			/* Encabezado de la columna*/
			$this->pdf->Cell(8, 5, "ITEM", 1, 0, 'C');
			$this->pdf->Cell(27, 5, "CODIGO", 1, 0, 'C');
			$this->pdf->Cell(82, 5, "DESCRIPCION", 1, 0, 'C');
			$this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
			$this->pdf->Cell(23, 5, "P. UNITARIO", 1, 0, 'C');
			$this->pdf->Cell(32, 5, "SUBTOTAL", 1, 0, 'C');
			$this->pdf->Ln(5);

			/*  detalle*/
			$nro = 1;
			$sale_detail = (ARRAY)$data_invoice['sale_detail'];
			$this->pdf->SetFont('Arial', '', 8);
			/*$this->pdf->SetAligns(array('C', 'L', 'R'));*/
			$cantidad_filas = 0;
			$numero_items = 0;
			$estilo = 'RL';
			$total_detail = 10;
			//Table with 20 rows and 4 columns
			$this->pdf->SetWidths(array(8, 27, 82, 20, 23, 32));
			$this->pdf->SetAligns(array('C', 'C', 'L', 'C', 'R', 'R'));
			$add_row_qr = 0;
			for ($index = 0; $index < count($sale_detail); $index++) {
				$row_sale = $sale_detail[$index];
				foreach ($row_sale as $row_detalle) {

					$cantidad_filas++;
					$productos = $this->product_model->get_product_by_id($row_detalle->producto_id);

					$this->pdf->Row(array(
						utf8_decode($cantidad_filas),
						utf8_decode($productos->codigo),
						utf8_decode($row_detalle->descripcion."(".$productos->dimension.")(".$productos->imei1.")(".$productos->imei2.")"),
						utf8_decode(number_format($row_detalle->cantidad, 0, '.', ',')),
						utf8_decode(number_format($row_detalle->precio_venta_descuento, 2, '.', ',')),
						utf8_decode(number_format($row_detalle->total, 2, '.', ','))
					));
					$number_line=$this->pdf->NbLines(82, $row_detalle->descripcion);
					$nro = $nro + 1;
					$numero_items = $numero_items + 1;
					if ($number_line==2){
						$add_row_qr+=5;
					}
					if ($number_line==3){
						$add_row_qr+=10;
					}
					if ($number_line==4){
						$add_row_qr+=15;
					}
					if ($number_line==5){
						$add_row_qr+=20;
					}
					if ($number_line==6){
						$add_row_qr+=25;
					}
					if ($number_line==7){
						$add_row_qr+=30;
					}
					$add_row_qr+=1.4;
				}
			}
			while ($total_detail - 1 >= $numero_items) {

				$cantidad_filas++;
				$estilo = 'RL';
				if ($nro == 1) {
					$estilo = $estilo . 'T';
				}
				if ($cantidad_filas == $total_detail) {
					$estilo = 'LRB';
				}

				$this->pdf->Cell(8, 4, '', $estilo, 0, 'C');
				$this->pdf->Cell(27, 4, '', $estilo, 0, 'L');
				$this->pdf->Cell(82, 4, '', $estilo, 0, 'L');
				$this->pdf->Cell(20, 4, '', $estilo, 0, 'C');
				$this->pdf->Cell(23, 4, '', $estilo, 0, 'R');
				$this->pdf->Cell(32, 4, '', $estilo, 0, 'R');
				$this->pdf->Ln(4);
				$numero_items = $numero_items + 1;
			}

			// Convertimos el monto en literal
			include APPPATH . '/libraries/convertidor.php';
			$v = new EnLetras();
			$valor = utf8_decode($v->ValorEnLetras(number_format($data_invoice['invoice']->importe_base_iva, 2, '.', ''), "Bolivianos"));
			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(7, 5, 'Son:', 'LTB', 0, 'L');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(129, 5, $valor, 'TBR', 0, 'L');
			$this->pdf->Cell(1, 5, '', '', 0, 'L');
			$x_qr = $add_row_qr;
			if ($data_invoice['invoice']->descuento > 0) {
				$x_qr = $x_qr + 10;
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(23, 5, 'SubTotal :', 1, 0, 'R');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->Cell(32, 5, number_format($data_invoice["invoice"]->importe_total_venta, 2, '.', ','), 1, 0, 'R');

				$this->pdf->Ln(5);
				$this->pdf->Cell(137, 5, '', '', 0, 'L');
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(23, 5, 'Descuento :', 1, 0, 'R');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->Cell(32, 5, number_format($data_invoice["invoice"]->descuento, 2, '.', ','), 1, 0, 'R');

				$this->pdf->Ln(5);
				$this->pdf->Cell(137, 5, '', '', 0, 'L');
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(23, 5, 'Total :', 1, 0, 'R');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->Cell(32, 5, number_format($data_invoice["invoice"]->importe_base_iva, 2, '.', ','), 1, 0, 'R');
			} else {
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(23, 5, 'TOTAL :', 1, 0, 'R');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->Cell(32, 5, number_format($data_invoice["invoice"]->importe_base_iva, 2, '.', ','), 1, 0, 'R');
			}
			$var_qr = base_url() . 'assets/invoice_qr/qr' . $data_invoice['invoice']->id . '.png';
			$this->pdf->Image($var_qr, $this->pdf->GetPageWidth() - 29, 132 + $x_qr, 22, 22);


			$print_day = date('Y-m-d');
			$print_hour = date('H:i:s');
			$year = substr($print_day, 0, 4);
			$month = substr($print_day, 5, 2);
			$day = substr($print_day, 8, 2);
			$print_date = $day . ' de ' . get_month($month) . ' del ' . $year;

			$this->pdf->Ln(10);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(26, 5, 'Codigo Control', 'BTL', 0, 'L');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(40, 5, ': ' . $data_invoice['invoice']->codigo_control, 'BRT', 0, 'L');
			$this->pdf->Ln(5);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(26, 5, 'Fecha Limite', 'BTL', 0, 'L');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(40, 5, ': ' . date("d/m/Y", strtotime($data_invoice['dosage']->fecha_limite)), 'BRT', 0, 'L');

			$this->pdf->Ln(16);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(190, 5, utf8_decode('ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS. EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LEY.'), 'BTLR', 0, 'C');

			$this->pdf->Ln(5);
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(190, 5, utf8_decode($data_invoice['dosage']->leyenda), 0, 0, 'C');

			/*-------------------------------------------COPIA---------------------------------------------*/
			$this->pdf->AddPage();
			// Define el alias para el número de página que se imprimirá en el pie
			$this->pdf->AliasNbPages();

			/* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
             */
			$this->pdf->SetTitle("FACTURA");
			/* La variable $x se utiliza para mostrar un número consecutivo */

			/* titulo de ingreso*/
			$var_img = base_url() . 'assets/images/'.$data_invoice['branch_office']->imagen;
			$this->pdf->Image($var_img, 13, 10, 60, 20);
			/*  NIT Y NRO FACTURA   */

			/* 1ra fila   */
			$this->pdf->Cell(130, 5, '', 0);
			$this->pdf->SetFont('Arial', 'B', 9);
			$this->pdf->Cell(30, 5, 'NIT', 'TL');
			$this->pdf->SetFont('Arial', '', 9);
			$this->pdf->Cell(32, 5, ': ' . get_branch_office_nit_in_session(), 'TR', 0, 'L');
			$this->pdf->Ln(5);
			$this->pdf->Cell(130, 5, '', 0);
			$this->pdf->SetFont('Arial', 'B', 9);
			$this->pdf->Cell(30, 5, utf8_decode('Nº FACTURA'), 'L');
			$this->pdf->SetFont('Arial', '', 9);
			$this->pdf->Cell(32, 5, ': ' . $data_invoice['invoice']->nro_factura, 'R', 0, 'L');
			$this->pdf->Ln(5);
			$this->pdf->SetFont('Arial', 'B', 16);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(70, 5, '', 0, 0, 'C');
			$this->pdf->Cell(60, 5, 'FACTURA', 0, 0, 'C');
			$this->pdf->SetTextColor(0, 000, 000);
			$this->pdf->SetFont('Arial', 'B', 9);
			$this->pdf->Cell(30, 5, utf8_decode('Nº AUTORIZACION'), 'BL');
			$this->pdf->SetFont('Arial', '', 9);
			$this->pdf->Cell(32, 5, ': ' . $data_invoice['invoice']->nro_autorizacion, 'RB', 0, 'L');


			$this->pdf->Ln(9);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(80, 5, utf8_decode($data_invoice['company']->nombre_empresa), 0, 0, 'C');


			$this->pdf->Ln(3);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(80, 5, utf8_decode($data_invoice['branch_office']->nombre), 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 13);
			$this->pdf->SetTextColor(248, 000, 000);
			$this->pdf->Cell(40, 5, '', 0, 0, 'C');
			$this->pdf->Cell(70, 5, 'COPIA', 0, 0, 'C');


			/*2da fila*/
			$this->pdf->Ln(5);
			$this->pdf->SetAligns('C');
			$this->pdf->SetTextColor(0, 000, 000);

			$this->pdf->SetLeftMargin(10);
			$this->pdf->SetRightMargin(115);
			$this->pdf->SetFont('Arial', '', 7);
			$html_datos = utf8_decode($data_invoice['branch_office']->direccion);
			$this->pdf->WriteHTML($html_datos);

			$this->pdf->Ln(5);
			$this->pdf->SetRightMargin(10);
			$this->pdf->SetLeftMargin(130);

			$this->pdf->SetFont('Arial', '', 8);
			$html_activity = utf8_decode($data_invoice['activity']->nombre);
			$this->pdf->WriteHTML($html_activity);

			$this->pdf->SetLeftMargin(10);


			/*4ta fila*/
			$this->pdf->Ln(-2);
			$this->pdf->Cell(80, 4, 'Telf. ' . utf8_decode($data_invoice['branch_office']->telefono), 0, 0, 'C');

			/*5ta fila*/
			$this->pdf->Ln(3);
			$this->pdf->Cell(80, 4, utf8_decode($data_invoice['branch_office']->ciudad_impuestos), 0, 0, 'C');

			$this->pdf->Ln(5);

			/*  LUGAR Y FECHA ///// NIT CI*/
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, 'Lugar y Fecha : ', 'TL');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(100, 5, utf8_decode($data_invoice['branch_office']->ciudad) . ', ' . $sale_date, 'T');
			$this->pdf->Cell(70, 5, '', 'TR');

			$this->pdf->Ln(5);


			/*  CLIENTE */
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(22, 5, utf8_decode('Señor(es)         : '), 'LB');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(100, 5, utf8_decode($data_invoice['invoice']->nombre_cliente), 'B');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(6, 5, utf8_decode('Nit : '), 'B');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(64, 5, utf8_decode($data_invoice['invoice']->nit_cliente), 'RB');
			$this->pdf->Ln(7);

			/*  DETALLE DE ITEMS */
			$this->pdf->SetMargins(10, 10, 10);
			$this->pdf->SetFont('Arial', 'B', 8);

			/* Encabezado de la columna*/
			$this->pdf->Cell(8, 5, "ITEM", 1, 0, 'C');
			$this->pdf->Cell(27, 5, "CODIGO", 1, 0, 'C');
			$this->pdf->Cell(82, 5, "DESCRIPCION", 1, 0, 'C');
			$this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
			$this->pdf->Cell(23, 5, "P. UNITARIO", 1, 0, 'C');
			$this->pdf->Cell(32, 5, "SUBTOTAL", 1, 0, 'C');
			$this->pdf->Ln(5);

			/*  detalle*/
			$nro = 1;
			$sale_detail = (ARRAY)$data_invoice['sale_detail'];
			$this->pdf->SetFont('Arial', '', 8);
			/*$this->pdf->SetAligns(array('C', 'L', 'R'));*/
			$cantidad_filas = 0;
			$numero_items = 0;
			$estilo = 'RL';
			$total_detail = 10;

			//Table with 20 rows and 4 columns
			$this->pdf->SetWidths(array(8, 27, 82, 20, 23, 32));
			$this->pdf->SetAligns(array('C', 'C', 'L', 'C', 'R', 'R'));

			for ($index = 0; $index < count($sale_detail); $index++) {
				$row_sale = $sale_detail[$index];
				foreach ($row_sale as $row_detalle) {

					$cantidad_filas++;
					$productos = $this->product_model->get_product_by_id($row_detalle->producto_id);
					$this->pdf->Row(array(
						utf8_decode($cantidad_filas),
						utf8_decode($productos->codigo),
						utf8_decode($row_detalle->descripcion."(".$productos->dimension.")(".$productos->imei1.")(".$productos->imei2.")"),
						utf8_decode(number_format($row_detalle->cantidad, 0, '.', ',')),
						utf8_decode(number_format($row_detalle->precio_venta_descuento, 2, '.', ',')),
						utf8_decode(number_format($row_detalle->total, 2, '.', ','))
					));
					$nro = $nro + 1;
					$numero_items = $numero_items + 1;
				}
			}

			while ($total_detail - 1 >= $numero_items) {

				$cantidad_filas++;
				$estilo = 'RL';
				if ($nro == 1) {
					$estilo = $estilo . 'T';
				}
				if ($cantidad_filas == $total_detail) {
					$estilo = 'LRB';
				}

				$this->pdf->Cell(8, 4, '', $estilo, 0, 'C');
				$this->pdf->Cell(27, 4, '', $estilo, 0, 'L');
				$this->pdf->Cell(82, 4, '', $estilo, 0, 'L');
				$this->pdf->Cell(20, 4, '', $estilo, 0, 'C');
				$this->pdf->Cell(23, 4, '', $estilo, 0, 'R');
				$this->pdf->Cell(32, 4, '', $estilo, 0, 'R');
				$this->pdf->Ln(4);
				$numero_items = $numero_items + 1;
			}

			// Convertimos el monto en literal

			$this->pdf->Ln(2);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(7, 5, 'Son:', 'LTB', 0, 'L');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(129, 5, $valor, 'TBR', 0, 'L');
			$this->pdf->Cell(1, 5, '', '', 0, 'L');
			$x_qr = $add_row_qr;
			if ($data_invoice['invoice']->descuento > 0) {
				$x_qr = $x_qr + 8;
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(23, 5, 'SubTotal :', 1, 0, 'R');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->Cell(32, 5, number_format($data_invoice["invoice"]->importe_total_venta, 2, '.', ','), 1, 0, 'R');

				$this->pdf->Ln(5);
				$this->pdf->Cell(137, 5, '', '', 0, 'L');
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(23, 5, 'Descuento :', 1, 0, 'R');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->Cell(32, 5, number_format($data_invoice["invoice"]->descuento, 2, '.', ','), 1, 0, 'R');

				$this->pdf->Ln(5);
				$this->pdf->Cell(137, 5, '', '', 0, 'L');
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(23, 5, 'Total :', 1, 0, 'R');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->Cell(32, 5, number_format($data_invoice["invoice"]->importe_base_iva, 2, '.', ','), 1, 0, 'R');
			} else {
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(23, 5, 'TOTAL :', 1, 0, 'R');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->Cell(32, 5, number_format($data_invoice["invoice"]->importe_base_iva, 2, '.', ','), 1, 0, 'R');
			}

			$this->pdf->Image($var_qr, $this->pdf->GetPageWidth() - 29, 132 + $x_qr, 22, 22);


			$this->pdf->Ln(10);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(26, 5, 'Codigo Control', 'BTL', 0, 'L');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(40, 5, ': ' . $data_invoice['invoice']->codigo_control, 'BRT', 0, 'L');
			$this->pdf->Ln(5);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(26, 5, 'Fecha Limite', 'BTL', 0, 'L');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(40, 5, ': ' . date("d/m/Y", strtotime($data_invoice['dosage']->fecha_limite)), 'BRT', 0, 'L');

			$this->pdf->Ln(16);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(190, 5, utf8_decode('ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS. EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LEY.'), 'BTLR', 0, 'C');

			$this->pdf->Ln(5);
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(190, 5, utf8_decode($data_invoice['dosage']->leyenda), 0, 0, 'C');

			$this->pdf->Output("Factura Nro. " . $data_invoice['invoice']->nro_factura . ".pdf", 'I');
		} else {
			show_404();
		}

	}

	public function print_payment_credit_sale()
    {
        if ($this->input->post()) {

            $id = $this->input->post('id');
            $payment_sale_credit = $this->credit_payment_model->get_payment_id($id);
            $detail_payment_sale_credit = $this->credit_payment_model->get_detail_payment_by_payment_id($id);

            $customer = $this->customer_model->get_customer_id($payment_sale_credit->cliente_id);
            $user = $this->user_model->get_user_id($payment_sale_credit->usuario_id);
            $branch_office = $this->office_model->get_branch_office_id($payment_sale_credit->sucursal_id);
            $company = $this->company_model->get_company();
            // $currency_local = $this->company_model->get_local_currency_settings_by_id(1);
            // $currency_company = $this->company_model->get_company_currency_settings_by_id(1);




            $this->load->library('pdf');
            $this->pdf = new Pdf('P', 'mm', 'Legal');

            $this->pdf->AddPage();

            // Define el alias para el número de página que se imprimirá en el pie
            $this->pdf->AliasNbPages();

            // Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
            $this->pdf->SetTitle("RECIBO DE PAGO". utf8_decode('NRO. ' . $payment_sale_credit->nro_transaccion_pago));
            // La variable $x se utiliza para mostrar un número consecutivo

            // Titulo de ingreso
            $var_img = base_url() . 'assets/images/'.$branch_office->imagen;
            $this->pdf->Image($var_img, 10, 10, 80, 28);


            // NIT Y NRO FACTURA
            // 1ra fila
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(125, 5, '', 0, 0, 'C');
            $this->pdf->Cell(70, 5, utf8_decode($company->nombre_empresa), 0, 0, 'C');

            // 2da fila
            $this->pdf->Ln(5);
            $this->pdf->SetFont('Arial', 'B', 12);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(85, 5, '', 0, 0, 'C');
            $this->pdf->Cell(40, 5, 'RECIBO DE PAGO', 0, 0, 'C');
            $this->pdf->SetFont('Arial', 'B', 9);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->MultiCell(70, 5, utf8_decode($branch_office->nombre_comercial), 0, 'C');

            /*3ra fila*/
            $this->pdf->Ln(0);
            $this->pdf->SetFont('Arial', 'B', 12);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(85, 5, '', 0, 0, 'C');
            $this->pdf->Cell(40, 5, utf8_decode('Nº' .  $payment_sale_credit->nro_transaccion_pago), 0, 0, 'C');
            $this->pdf->SetFont('Arial', '', 9);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->MultiCell(70, 4, utf8_decode($branch_office->direccion), 0, 'C');

            /*4ta fila*/
            $this->pdf->Ln(0);
            $this->pdf->Cell(125, 5, '', 0, 0, 'C');
            $this->pdf->MultiCell(70, 4, 'Telf. ' . utf8_decode($branch_office->telefono), 0, 'C');

            /*5ta fila*/
            $this->pdf->Ln(4);
            $this->pdf->Cell(85, 5, '', 0);
            $this->pdf->SetFont('Arial', 'B', 12);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(40, 5, utf8_decode(''), 0, 0, 'C');
            $this->pdf->SetFont('Arial', '', 9);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->Cell(70, 4, utf8_decode($branch_office->ciudad_impuestos), 0, 0, 'C');
            $this->pdf->Ln(12);

            $year = substr($payment_sale_credit->fecha_emision, 0, 4);
            $month = substr($payment_sale_credit->fecha_emision, 5, 2);
            $day = substr($payment_sale_credit->fecha_emision, 8, 2);
            $payment_sale_credit_date = $day . ' de ' . get_month($month) . ' del ' . $year;

            // CLIENTE
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(22, 5, 'Lugar y Fecha:', 'TL');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(65, 5, utf8_decode($branch_office->ciudad) . ', ' . $payment_sale_credit_date, 'T');
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(5, 5, 'CI:', 'T');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(25, 5, utf8_decode($customer->ci), 'T');
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(13, 5, 'Nombre:', 'T');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(62, 5, utf8_decode($customer->nombre), 'TR');
            $this->pdf->Ln(5);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(16, 5, utf8_decode('Telefono1:'), 'L');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(25, 5, utf8_decode((!empty($customer->telefono1)) ? $customer->telefono1 : 'SIN REGISTRO'), '');
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(16, 5, utf8_decode('Telefono2:'), '');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(28, 5, utf8_decode((!empty($customer->telefono2)) ? $customer->telefono2 : 'SIN REGISTRO'), '');
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(28, 5, utf8_decode('Correo Electrónico:'), '');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(38, 5, utf8_decode((!empty($customer->email)) ? $customer->email : 'SIN REGISTRO'), '');
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(19, 5, utf8_decode('Tipo Cliente:'), '');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(22, 5, utf8_decode((!empty($customer->nombre_tipo_cliente)) ? $customer->nombre_tipo_cliente : 'SIN REGISTRO'), 'R');
            $this->pdf->Ln(5);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(32, 5, utf8_decode('Observacion del Pago:'), 'LB');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(160, 5, utf8_decode(($payment_sale_credit->observacion)), 'RB');
            $this->pdf->Ln(7);

            //  DETALLE DE PAGO
            $this->pdf->SetMargins(10, 10, 10);
            $this->pdf->SetFont('Arial', 'B', 8);

            // Encabezado de la columna
            $this->pdf->Cell(16, 5, "NRO.",  'LTR',0,'C');
            $this->pdf->Cell(14, 5, "NRO.", 'LTR',0,'C');
            $this->pdf->Cell(44, 5, "FECHA", 'LTR',0,'C');
            $this->pdf->Cell(36, 5, "TOTAL", 'LTR', 0, 'C');
            // $this->pdf->Cell(24, 5, "TOTAL", 'LTR', 0, 'C');
            $this->pdf->Cell(38, 5, "SALDO", 'LTR', 0, 'C');
            $this->pdf->Cell(22, 5, "MONTO", 'LTR', 0, 'C');
            $this->pdf->Cell(22, 5, "SALDO ", 'LTR', 0, 'C');
            $this->pdf->Ln(5);
            $this->pdf->Cell(16, 5, "CREDITO",  'LBR',0,'C');
            $this->pdf->Cell(14, 5, "CUOTA",  'LBR',0,'C');
            $this->pdf->Cell(44, 5, "CUOTA",  'LBR',0,'C');
            $this->pdf->Cell(36, 5, "CREDITO",  'LBR',0,'C');
            // $this->pdf->Cell(24, 5, "CUOTA",  'LBR',0,'C');
            $this->pdf->Cell(38, 5, "ANTERIOR",  'LBR',0,'C');
            $this->pdf->Cell(22, 5, "PAGADO",  'LBR',0,'C');
            $this->pdf->Cell(22, 5, "ACTUAL",  'LBR',0,'C');
            $this->pdf->Ln(5);

            // Detalle
            $nro = 1;
            $number_row = 0;
            $number_item = 0;
            $total_detail = 5;

            $detail_payment = (ARRAY)$detail_payment_sale_credit;
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->SetWidths(array(16, 14, 44, 36, 38, 22, 22));
            $this->pdf->SetAligns(array('C', 'C', 'C', 'R', 'R', 'R', 'R', 'R'));

            foreach ($detail_payment as $row) {
				$number_row++;
				$show_date = $row->fecha_emision;
                // if ($row->retraso == 1){
                //     $show_date = 'RETRASO  ' . strval($row->fecha_emision);
                // } else {
                //     $show_date = $row->fecha_emision;
                // }

                $this->pdf->Row(array(
                    utf8_decode($row->nro_venta_credito),
                    utf8_decode($row->nro_venta_credito),
                    utf8_decode($show_date),
                    utf8_decode(number_format($row->monto_credito, CANTIDAD_MONTO_DECIMAL, '.','')),
                    // utf8_decode(number_format($row->monto_plan_cuota, CANTIDAD_MONTO_DECIMAL, '.','')),
                    utf8_decode(number_format($row->monto_saldo_actual, CANTIDAD_MONTO_DECIMAL, '.', '')),
                    utf8_decode(number_format($row->monto_pagado, CANTIDAD_MONTO_DECIMAL, '.', '')),
                    utf8_decode(number_format($row->monto_saldo_actual-$row->monto_pagado, CANTIDAD_MONTO_DECIMAL, '.', ''))
                ));
                $nro = $nro + 1;
                $number_item = $number_item + 1;
            }

            while ($total_detail - 1 >= $number_item) {

                $number_row++;
                $style = 'RL';
                if ($nro == 1) {
                    $style = $style . 'T';
                }
                if ($number_row == $total_detail) {
                    $style = 'LRB';
                }

                $this->pdf->Cell(16, 5, "",  'LBR',0,'C');
                $this->pdf->Cell(14, 5, "",  'LBR',0,'C');
                $this->pdf->Cell(44, 5, "",  'LBR',0,'C');
                $this->pdf->Cell(36, 5, "",  'LBR',0,'C');
                // $this->pdf->Cell(24, 5, "",  'LBR',0,'C');
                $this->pdf->Cell(38, 5, "",  'LBR',0,'C');
                $this->pdf->Cell(22, 5, "",  'LBR',0,'C');
                $this->pdf->Cell(22, 5, "",  'LBR',0,'C');
                $this->pdf->Ln(4);
                $number_item = $number_item + 1;
            }

            $this->pdf->Ln(1);
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(122, 5, '', 1, 0, 'L');
            $this->pdf->Cell(26, 5, 'TOTAL PAGADO:', 1, 0, 'C');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(22, 5, utf8_decode($payment_sale_credit->monto_total), 1, 0, 'R');
            $this->pdf->Cell(22, 5, "", 1, 0, 'C');

            $print_day = date('Y-m-d');
            $print_hour = date('H:i:s');
            $year = substr($print_day, 0, 4);
            $month = substr($print_day, 5, 2);
            $day = substr($print_day, 8, 2);
            $print_date = $day . ' de ' . get_month($month) . ' del ' . $year;

            $this->pdf->Ln(10);
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(26, 5, 'Fecha Impresion :', 'TBL', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(40, 5, $print_date, 'BT', 0, 'L');

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(30, 5, 'Hora Impresion :', 'BT', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(30, 5, $print_hour, 'BT', 0, 'L');

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(22, 5, 'Usuario :', 'BT', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(44, 5, $user->usuario, 'BTR', 0, 'L');


            $this->pdf->Output("Recibo Pago Nro. " . $payment_sale_credit->nro_transaccion_pago . ' - ' . utf8_decode(date('Y-m-d H:i:s')) .".pdf", 'I');
        } else {
            show_404();
        }
	}
	
	
}
