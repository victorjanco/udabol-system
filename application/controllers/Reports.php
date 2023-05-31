<?php

/**
 * Created by PhpStorm.
 * User: juan
 * Date: 30/11/2017
 * Time: 23:12
 */
class reports extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reception_model');
		$this->load->model('report_sale_model');
		$this->load->model('office_model');

	}
	/***funcion para llamar al metodo imprimir ***/

	/* function imprimir_reportes(element) {

         var table = $(element).closest('table').DataTable();
         var fila = $(element).parents('tr');
         if (fila.hasClass('child')) {
             fila = fila.prev();
         }
         var rowData = table.row(fila).data();
         var id = rowData['id']
         window.open(base_url + '/reports/imprimir_orden_trabajo/' + id, 'Impresion', 'width=auto, height=auto');
        }*/

	/***IMPRIMIR RECIBO OFICIAL ***/
	public function imprimir_recibo()
	{
		//  $pago_id = $this->uri->segment(3);
		$pago = 5;// ($this->payment_model->get_datos_pago($pago_id));
		//$cliente = ($this->payment_model->get_datos_cliente($pago_id));
		//variable de pago
		//$datosImpresion = $pago['monto'];
		//$datos_cliente = $cliente['cliente'];

		$this->load->library('pdf');
		$this->pdf = new Pdf('P', 'mm', 'Legal');
		// $ingresos  = $this->flujo_caja_model->obtener_ingresos($fecha_inicio,$fecha_fin); array(216, 279)
		$this->pdf->AddPage();
		// Define el alias para el número de página que se imprimirá en el pie
		$this->pdf->AliasNbPages();
		/* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
         */
		$this->pdf->SetTitle("RECIBO");
		/* titulo de ingreso*/
		// $var_img = base_url() . 'assets/images/logo1.png';
		//  $this->pdf->Image($var_img, 10, 10, 30, 25);

		//   $var_img = base_url() . 'assets/images/logo1.png';
		// $this->pdf->Image($var_img, 160, 10, 30, 20);

		$this->pdf->Ln(13);

		$this->pdf->SetFont('Arial', 'B', 9);
		$this->pdf->MultiCell(72, 5, '', 0, 'C');
		$this->pdf->Ln(0);

		$this->pdf->Cell(75, 5, ' ', 0, 0, 'C');
		$this->pdf->SetFont('Arial', 'B', 16);
		$this->pdf->SetTextColor(254, 000, 000);
		$this->pdf->Cell(40, 15, 'RECIBO OFICIAL', 0, 0, 'C');

		$this->pdf->Cell(60, 4, '', 0);
		$this->pdf->SetFont('Arial', '', 9);
		$this->pdf->MultiCell(72, 4, '', 0, 'C');
		$this->pdf->Ln(0);

		$this->pdf->Cell(75, 5, '', 0, 0, 'C');
		//$this->pdf->SetFont('Arial', 'B', 12);
		//$this->pdf->SetTextColor(0, 0, 0);

		$this->pdf->Cell(61, 5, ' ', 0, 0, 'C');
		$this->pdf->SetTextColor(0, 0, 0);
		$this->pdf->SetFont('Arial', 'B', 12);
		$this->pdf->Cell(61, 5, 'NRO. ' . '000601', 0, 0, 'C');
		$this->pdf->Ln(15);

		$nro = 1;
		$nro = $nro + 1;

		/*  RECIBIDO*/
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(27, 5, 'HE RECIBIDO DE  : ' . 'fernanda arias sanchez', 'TL');
		$this->pdf->Cell(165, 5, '', 'TR');

		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Ln(5);

		/*  SUMAS */
		$this->pdf->Cell(27, 5, utf8_decode('LA SUMA DE         : ' . '100 bolivianos' . '                                                                                            BOLIVIANOS / DOLARES'), 'LB');
		$this->pdf->Cell(165, 5, '', 'RB');

		/*PRUEBA*/
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Ln(5);

		/* FECHA */
		$this->pdf->Cell(27, 5, utf8_decode('FECHA                   : ' . '12-05-17'), 'LB');
		$this->pdf->Cell(165, 5, '', 'RB');
		/*FIN*/
		$this->pdf->Ln(8);

		/* Encabezado de la columna*/
		$this->pdf->Cell(25, 5, "CANT.", 1, 0, 'C');
		$this->pdf->Cell(84, 5, "DETALLE", 1, 0, 'C');
		$this->pdf->Cell(50, 5, "P.UNIT.", 1, 0, 'C');
		$this->pdf->Cell(33, 5, "TOTAL", 1, 0, 'C');
		$this->pdf->Ln(5);
		$this->pdf->SetFont('Arial', '', 8);
		//------------------------------------------
		$cantidad_filas = 0;
		$numero_items = 15;
		$estilo = 'RL';

		// foreach ($datosImpresion as $row_detalle) {
		$cantidad_filas++;
		$estilo = 'RL';
		if ($nro == 1) {
			$estilo = $estilo . 'T';
		}
		if ($cantidad_filas == count($pago)) {
			$estilo = 'LRB';
		}

		$this->pdf->Cell(25, 4, utf8_decode('12'), $estilo, 0, 'C');
		$this->pdf->Cell(84, 4, utf8_decode('ningun detalle'), $estilo, 0, 'C');
		$this->pdf->Cell(50, 4, utf8_decode('55'), $estilo, 0, 'C');
		$this->pdf->Cell(33, 4, utf8_decode('100'), $estilo, 0, 'C');
		// }
		//INICIO
		// Convertimos el monto en literal
		// include APPPATH . '/libraries/convertidor.php';
		// $v = new EnLetras();
		//$valor = $v->ValorEnLetras($datosImpresion['datos_venta']->total, " ");

		$this->pdf->Ln(8);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(7, 5, 'Son:', 'LTB', 0, 'L');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(125, 5, '20000', 'TBR', 0, 'L');
		$this->pdf->Cell(5, 5, '', '', 0, 'L');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(22, 5, 'TOTAL :', 1, 0, 'R');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(33, 5, '100', 1, 0, 'R');
		//FIN
		$this->pdf->Ln(17);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(7, 5, 'RECIBI CONFORME : .......................................................................', '', 0, 'L');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(130, 5, '', '', 0, 'L');
		$this->pdf->Cell(5, 5, '', '', 0, 'L');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(50, 5, 'ENTREGUE CONFORME :  .......................................................................', '', 0, 'R');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(70, 5, '', '', 0, 'R');


		//-----------------------------------------------------------------------------

		$this->pdf->SetMargins(10, 10, 10);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->SetWidths(array(20, 100, 60));
		//un arreglo con alineacion de cada celda
		$this->pdf->SetAligns(array('C', 'L', 'R'));
		/*  quitamos bolf, y empezamos a dibujar las grillas*/
		$this->pdf->SetFont('Arial', '', 9);
		$this->pdf->SetFont('Arial', '', 9);
		$this->pdf->Output("Recibo.pdf", 'I');

	}

	/*** IMPRIMIR ENTRADA A ALMACEN***/
	public function imprimir_entrada_almacen()
	{
		//$pago_id = $this->uri->segment(3);
		$pago = 5;//($this->payment_model->get_datos_pago($pago_id));
		//$cliente = ($this->payment_model->get_datos_cliente($pago_id));
		//variable de pago
		// $datosImpresion = $pago['monto'];
		// $datos_cliente = $cliente['cliente'];

		$this->load->library('pdf');
		$this->pdf = new Pdf('P', 'mm', 'Legal');
		// $ingresos  = $this->flujo_caja_model->obtener_ingresos($fecha_inicio,$fecha_fin); array(216, 279)
		$this->pdf->AddPage();
		// Define el alias para el número de página que se imprimirá en el pie
		$this->pdf->AliasNbPages();
		/* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
         */
		$this->pdf->SetTitle("ENTRADA A ALMACEN");
		/* titulo de ingreso*/
		// $var_img = base_url() . 'assets/img/logo.png';
		// $this->pdf->Image($var_img, 10, 10, 30, 25);

		//$var_img = base_url() . 'assets/img/logo.png';
		//$this->pdf->Image($var_img, 160, 10, 30, 20);

		$this->pdf->Ln(13);

		$this->pdf->SetFont('Arial', 'B', 9);
		$this->pdf->MultiCell(72, 5, '', 0, 'C');
		$this->pdf->Ln(0);

		$this->pdf->Cell(75, 5, ' ', 0, 0, 'C');
		$this->pdf->SetFont('Arial', 'B', 16);
		$this->pdf->SetTextColor(254, 000, 000);
		$this->pdf->Cell(40, 15, 'ENTRADA A ALMACEN', 0, 0, 'C');

		$this->pdf->Cell(60, 4, '', 0);
		$this->pdf->SetFont('Arial', '', 9);
		$this->pdf->MultiCell(72, 4, '', 0, 'C');
		$this->pdf->Ln(0);

		$this->pdf->Cell(75, 5, '', 0, 0, 'C');

		//$this->pdf->SetFont('Arial', 'B', 12);
		//$this->pdf->SetTextColor(0, 0, 0);

		$this->pdf->Cell(61, 5, ' ', 0, 0, 'C');
		$this->pdf->SetTextColor(0, 0, 0);
		$this->pdf->SetFont('Arial', 'B', 12);
		$this->pdf->Cell(61, 5, 'NRO. ' . '000601', 0, 0, 'C');
		$this->pdf->Ln(15);

		$nro = 1;
		$nro = $nro + 1;

		/*  RECIBIDO*/
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(27, 5, 'NOMBRE DEL PROVEEDOR      : ' . 'fernanda arias sanchez', 'TL');
		$this->pdf->Cell(165, 5, '', 'TR');

		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Ln(5);

		/*  SUMAS */
		$this->pdf->Cell(27, 5, utf8_decode('TELEFONO DEL PROVEEDOR   : ' . '789746464' . '                                                                                            FACTURA Nro :'), 'LB');
		$this->pdf->Cell(165, 5, '', 'RB');

		/*PRUEBA*/
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Ln(5);

		/* FECHA */
		$this->pdf->Cell(27, 5, utf8_decode('FECHA                                          : ' . '12-05-17' . '                                                                                  LOCAL / IMPORTACION'), 'LB');
		$this->pdf->Cell(165, 5, '', 'RB');

		$this->pdf->Ln(9);

		/* Encabezado de la columna*/
		$this->pdf->Cell(10, 5, "No.", 1, 0, 'C');
		$this->pdf->Cell(45, 5, "COD. ARTICULO", 1, 0, 'C');
		$this->pdf->Cell(44, 5, "DESCRIPCION", 1, 0, 'C');
		$this->pdf->Cell(33, 5, "U. MEDIDA", 1, 0, 'C');
		$this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
		$this->pdf->Cell(20, 5, "PRECIO", 1, 0, 'C');
		$this->pdf->Cell(20, 5, "VALOR ", 1, 0, 'C');
		$this->pdf->Ln(5);
		$this->pdf->SetFont('Arial', '', 8);
		//------------------------------------------
		$cantidad_filas = 0;
		$numero_items = 15;
		$estilo = 'RL';

		// foreach ($datosImpresion as $row_detalle) {
		$cantidad_filas++;
		$estilo = 'RL';
		if ($nro == 1) {
			$estilo = $estilo . 'T';
		}
		if ($cantidad_filas == count($pago)) {
			$estilo = 'LRB';
		}

		$this->pdf->Cell(10, 4, utf8_decode('12'), $estilo, 0, 'C');
		$this->pdf->Cell(45, 4, utf8_decode('ningun detalle'), $estilo, 0, 'C');
		$this->pdf->Cell(44, 4, utf8_decode('55'), $estilo, 0, 'C');
		$this->pdf->Cell(33, 4, utf8_decode('100'), $estilo, 0, 'C');
		$this->pdf->Cell(20, 4, utf8_decode('100'), $estilo, 0, 'C');
		$this->pdf->Cell(20, 4, utf8_decode('100'), $estilo, 0, 'C');
		$this->pdf->Cell(20, 4, utf8_decode('100'), $estilo, 0, 'C');
		//  }
		// Convertimos el monto en literal
		// include APPPATH . '/libraries/convertidor.php';
		// $v = new EnLetras();
		//$valor = $v->ValorEnLetras($datosImpresion['datos_venta']->total, " ");

		$this->pdf->Ln(8);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(7, 5, 'Son:', 'LTB', 0, 'L');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(125, 5, '20000', 'TBR', 0, 'L');
		$this->pdf->Cell(5, 5, '', '', 0, 'L');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(22, 5, 'TOTAL :', 1, 0, 'C');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(33, 5, '100', 1, 0, 'C');


		$this->pdf->Ln(17);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(7, 5, 'RECIBI CONFORME : .......................................................................', '', 0, 'L');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(130, 5, '', '', 0, 'L');
		$this->pdf->Cell(5, 5, '', '', 0, 'L');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(50, 5, 'ENTREGUE CONFORME :  .......................................................................', '', 0, 'R');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(70, 5, '', '', 0, 'R');


		//-----------------------------------------------------------------------------

		$this->pdf->SetMargins(10, 10, 10);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->SetWidths(array(20, 100, 60));
		//un arreglo con alineacion de cada celda
		$this->pdf->SetAligns(array('C', 'L', 'R'));
		/*  quitamos bolf, y empezamos a dibujar las grillas*/
		$this->pdf->SetFont('Arial', '', 9);
		$this->pdf->SetFont('Arial', '', 9);
		$this->pdf->Output("Entrada_Almacen.pdf", 'I');

	}
	
	
	function obtener_mes($valor)
	{
		$result = '';
		switch ($valor) {
			case '01':
				$result = 'Enero';
				break;
			case '02':
				$result = 'Febrero';
				break;
			case '03':
				$result = 'Marzo';
				break;
			case '04':
				$result = 'Abril';
				break;
			case '05':
				$result = 'Mayo';
				break;
			case '06':
				$result = 'Junio';
				break;
			case '07':
				$result = 'Julio';
				break;
			case '08':
				$result = 'Agosto';
				break;
			case '09':
				$result = 'Septiembre';
				break;
			case '10':
				$result = 'Octubre';
				break;
			case '11':
				$result = 'Noviembre';
				break;
			case '12':
				$result = 'Diciembre';
				break;
		}
		return $result;
	}

	public function invoice_lcv()
	{
		//$this->load->model('model_login');
		$res['sucursal'] = $this->office_model->get_offices();
		template('report/report_lcv', $res);
	}

	public function get_facturas_lcv()
	{
		if ($this->input->is_ajax_request()) {
			$start = $this->input->post('start');
			$limit = $this->input->post('length');
			$search = $this->input->post('search')['value'];
			$order = $this->input->post('order')['0']['dir'];
			$column_num = $this->input->post('order')['0']['column'];
			$column = $this->input->post('columns')[$column_num]['data'];

			/*Parametros de busqueda sin datatable*/
			$month = $this->input->post('month');
			$year = $this->input->post('year');

			// Se almacenan los parametros recibidos en un array para enviar al model
			$params = array(
				'start' => $start,
				'limit' => $limit,
				'search' => $search,
				'column' => $column,
				'order' => $order,
				'month' => $month,
				'year' => $year
			);
			$data = $this->report_sale_model->get_facturas_lcv($params);
			echo json_encode($data);
		} else {
			show_404();
		}


		/*  if ($this->input->is_ajax_request()){
              $mes = $this->input->post('mes');
              $anio = $this->input->post('anio');
              $sucursal = $this->input->post('sucursal');
              $tipo_factura = $this->input->post('tipo_factura');

              echo json_encode($this->report_sale_model->get_facturas_lcv($mes,$anio,$sucursal,$tipo_factura));
          }else{
              show_404();
          }*/
	}

	function export_excel_lcv()
	{
		$this->load->library("excel/PHPExcel");

		/*Parametros*/
		$month = $this->input->post('month');
		$year = $this->input->post('year');

		/*Array*/
		$params = array(
			'month' => $month,
			'year' => $year
		);

		$data = $this->report_sale_model->get_facturas_lcv($params);
		$list_data = $data['data'];

		$branch_office = $this->office_model->get_branch_office_id(get_branch_id_in_session());

		//membuat objek PHPExcel
		$objPHPExcel = new PHPExcel();

		//Unir celdas
		$objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
		$objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
		$objPHPExcel->getActiveSheet()->mergeCells('H3:I3');
		$objPHPExcel->setActiveSheetIndex(0)//elegimos hoja donde vamos a escribir
//empesamos a escribir en la hoja de excel

		->setCellValue('A1', 'Libro de Ventas I.V.A.')
//            //CABEZERA DE LA TABLA
//            ->setCellValue('A2', 'ID Transaccion: '.$_REQUEST['idTransaccion'])
//            ->setCellValue('C2', 'Paciente: '.$_REQUEST['paciente'])
			->setCellValue('A3', 'Sucursal: ' . $branch_office->nombre_comercial)
			->setCellValue('F3', 'Periodo Fiscal: ' . $this->obtener_mes($month))
			->setCellValue('H3', 'Año Fiscal: ' . $year)
			////Encabezado de la tabla
			->setCellValue('A5', 'ESPECIFICACION')
			->setCellValue('B5', 'NRO.')
			->setCellValue('C5', 'FECHA')
			->setCellValue('D5', 'NRO. FACTURA')
			->setCellValue('E5', 'NRO. AUTORIZACION')
			->setCellValue('F5', 'ESTADO')
			->setCellValue('G5', 'NIT')
			->setCellValue('H5', 'NOMBRE CLIENTE')
			->setCellValue('I5', 'MONTO TOTAL')
			->setCellValue('J5', 'IMPORTE ICE')
			->setCellValue('K5', 'IMPORTE EXENCTO')
			->setCellValue('L5', 'VENTAS GRABADAS A TASA CERO')
			->setCellValue('M5', 'SUBTOTAL')
			->setCellValue('N5', 'DESCUENTOS Y BONIDICACIONES')
			->setCellValue('O5', 'IMPORTE BASE PARA DEBITO FISCAL')
			->setCellValue('P5', 'DEBITO FISCAL')
			->setCellValue('Q5', 'CODIGO DE CONTROL');
		//poner en negritas
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('D3')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('F3')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('H3')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('B5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('C5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('F5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('G5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('H5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('I5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('J5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('K5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('L5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('M5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('N5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('O5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('P5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('Q5')->getFont()->setBold(TRUE);
//centrar los titulos
		$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('K5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('L5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('M5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('N5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('O5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('P5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('Q5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// //Pintamos los bordes
		$objPHPExcel->getActiveSheet()->getStyle('A5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('B5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('C5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('D5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('E5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('F5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('G5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('H5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('I5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('J5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('K5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('L5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('M5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('N5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('O5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('P5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('Q5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);




		$fila = 6; //enpieza a escribir desde la linea 6\
		$i = 1;
		$importe_total_venta = 0;
		$importe_no_sujeto_iva = 0;
		$operacion_excenta = 0;
		$venta_tasa_cero = 0;
		$subtotal = 0;
		$descuento = 0;
		$importe_base_iva = 0;
		$iva = 0;
		foreach ($list_data as $row) {

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A' . $fila, 3)
				->setCellValue('B' . $fila, $i)
				->setCellValue('C' . $fila, $row['fecha'])
				->setCellValue('D' . $fila, $row['nro_factura'])
				->setCellValue('E' . $fila, $row['nro_autorizacion'] . ' ')
				->setCellValue('F' . $fila, $row['estado'])
				->setCellValue('G' . $fila, $row['nit_cliente'])
				->setCellValue('H' . $fila, $row['nombre_cliente'])
				->setCellValue('I' . $fila, number_format($row['importe_total_venta'], 2,'.','') . ' ')
				->setCellValue('J' . $fila, number_format($row['importe_no_sujeto_iva'], 2,'.','') . ' ')
				->setCellValue('K' . $fila, number_format($row['operacion_excenta'], 2,'.','') . ' ')
				->setCellValue('L' . $fila, number_format($row['venta_tasa_cero'], 2,'.','') . ' ')
				->setCellValue('M' . $fila, number_format($row['subtotal'], 2,'.','') . ' ')
				->setCellValue('N' . $fila, number_format($row['descuento'], 2,'.','') . ' ')
				->setCellValue('O' . $fila, number_format($row['importe_base_iva'], 2,'.','') . ' ')
				->setCellValue('P' . $fila, number_format($row['iva'], 2,'.','') . ' ')
				->setCellValue('Q' . $fila, $row['codigo_control']);
			$objPHPExcel->getActiveSheet()->getStyle('A'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
				->getActiveSheet()->getStyle('B'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
				->getActiveSheet()->getStyle('C'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
				->getActiveSheet()->getStyle('D'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
				->getActiveSheet()->getStyle('E'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
				->getActiveSheet()->getStyle('F'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
				->getActiveSheet()->getStyle('G'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
				->getActiveSheet()->getStyle('H'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
				->getActiveSheet()->getStyle('I'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
				->getActiveSheet()->getStyle('J'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
				->getActiveSheet()->getStyle('K'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
				->getActiveSheet()->getStyle('L'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
				->getActiveSheet()->getStyle('M'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
				->getActiveSheet()->getStyle('N'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
				->getActiveSheet()->getStyle('O'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
				->getActiveSheet()->getStyle('P'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
				->getActiveSheet()->getStyle('Q'. $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//     //Pintar los bordes
			$objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('B' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('C' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('D' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('E' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('F' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('G' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('H' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('I' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('J' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('K' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('L' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('M' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('N' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('O' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('P' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
				->getActiveSheet()->getStyle('Q' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$fila = $fila + 1;
			$i++;
			$importe_total_venta += number_format($row['importe_total_venta'], 2,'.','');
			$importe_no_sujeto_iva += number_format($row['importe_no_sujeto_iva'], 2,'.','');
			$operacion_excenta += number_format($row['operacion_excenta'], 2,'.','');
			$venta_tasa_cero += number_format($row['venta_tasa_cero'], 2,'.','');
			$subtotal += number_format($row['subtotal'], 2,'.','');
			$descuento += number_format($row['descuento'], 2,'.','');
			$importe_base_iva += number_format($row['importe_base_iva'], 2,'.','');
			$iva += number_format($row['iva'], 2,'.','');
		}

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A' . $fila, '')
			->setCellValue('B' . $fila, '')
			->setCellValue('C' . $fila, '')
			->setCellValue('D' . $fila, '')
			->setCellValue('E' . $fila, '')
			->setCellValue('F' . $fila, '')
			->setCellValue('G' . $fila, '')
			->setCellValue('H' . $fila, '')
			->setCellValue('I' . $fila, $importe_total_venta)
			->setCellValue('J' . $fila, $importe_no_sujeto_iva)
			->setCellValue('K' . $fila, $operacion_excenta)
			->setCellValue('L' . $fila, $venta_tasa_cero)
			->setCellValue('M' . $fila, $subtotal)
			->setCellValue('N' . $fila, $descuento)
			->setCellValue('O' . $fila, $importe_base_iva)
			->setCellValue('P' . $fila, $iva)
			->setCellValue('Q' . $fila, '');
//     //Pintar los bordes
		$objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('B' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('C' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('D' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('E' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('F' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('G' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('H' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('I' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('J' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('K' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('L' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('M' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('N' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('O' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('P' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('Q' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//ESTABLECE LA ANCHURA DE LAS CELDA
		$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO
		$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO
		$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("O")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("P")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("Q")->setAutoSize(TRUE);

//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

//sesuaikan headernya
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="ventas' . $month . $year . "_" . $branch_office->nit . '.xlsx"');
//unduh file
		$objWriter->save("php://output");
	}

	public function get_txt()
	{
		$month = $this->input->post('month');
		$year = $this->input->post('year');

		/*Array*/
		$params = array(
			'month' => $month,
			'year' => $year
		);

		$data = $this->report_sale_model->get_facturas_lcv($params);
		$list_data = $data['data'];
		$branch_office = $this->office_model->get_branch_office_id(get_branch_id_in_session());

		$archivo = "ventas_" . $month . $year . "_" . $branch_office->nit . ".txt";
		header('Content-Disposition: attachment;filename="' . $archivo . '"');
		ob_start();

		$linea = "";
		$i = 1;
		foreach ($list_data as $row) {
			$nit_cliente = $row['nit_cliente'];
			$nombre = $row['nombre_cliente'];
			$nro_factura = $row['nro_factura'];
			$autorizacion = $row['autorizacion'];
			$fecha = $row['fecha'];
			$monto_total = $row['importe_total_venta'];
			$ice = $row['importe_no_sujeto_iva'];
			$excento = $row['operacion_excenta'];
			$codigo_control = $row['codigo_control'];
			$ventasGrabadas = $row['venta_tasa_cero'];
			$descuento = $row['descuento'];
			$subtotal = $row['subtotal'];
			$base_iva = $row['importe_base_iva'];
			$iva = $row['iva'];
			$estado = $row['estado'];

			$linea .= '3'. "|" .$i . "|" . $fecha . "|" . $nro_factura . "|" . $autorizacion . "|" . $estado . "|" . $nit_cliente . "|" . $nombre . "|" . $monto_total . "|" . $ice . "|" . $excento . "|" . $ventasGrabadas . "|" . $subtotal . "|" . $descuento . "|" . $base_iva . "|" . $iva . "|" . $codigo_control . "\r\n";
			$i++;
		}
		ob_end_clean();
		header('Content-Type: application/txt');
		header('Content-Disposition: attachment;filename=' . $archivo . '');
		header('Pragma:no-cache');
		echo $linea;
	}


	/*** IMPRIMIR ORDEN DE TRABAJO***/
	public function imprimir_orden_trabajo()
	{
		$reception_id = $this->input->post('id');
		$data_reception = $this->reception_model->getdata_printer_report($reception_id);

		$this->load->library('pdf');
		$this->pdf = new Pdf('P', 'mm', 'Legal');
		$this->pdf->SetTopMargin(0.0);
		$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		/* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
         */
		$type_impresion = 'ORDEN DE TRABAJO';
		if (intval($data_reception['order_work']->estado_trabajo) === ENTREGADO) {
			$type_impresion = 'RECIBO DE ENTREGA';
		}

		$this->pdf->SetTitle($type_impresion);
		$this->pdf->SetFont('Arial', 'B', 7);


		$this->pdf->Ln(6);
		$var_img = base_url() . 'assets/images/level-fix.png';
		$start_x = ($this->pdf->GetPageWidth() / 2) - 39;
		$this->pdf->Image($var_img, 10, 5, 66, 25);
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(124, 4, '', '', 0, 'C');
		$this->pdf->MultiCell(70, 4, utf8_decode($data_reception['company']->nombre_empresa), '', 'C');
		$this->pdf->Ln(0);
		$this->pdf->Cell(64, 2, ' ', '', 0, 'C');
		$this->pdf->SetTextColor(248, 000, 000);
		$this->pdf->SetFont('Arial', 'B', 14);
		$this->pdf->Cell(60, 12, $type_impresion, '', 0, 'C');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->Cell(75, 4, utf8_decode($data_reception['branch_office']->nombre_comercial), '', 0, 'C');
		$this->pdf->SetTextColor(248, 000, 000);
		//$this->pdf->Cell(61, 5, 'NRO. ' . '000601', 0, 0, 'C');
		$this->pdf->Ln(3);
		$this->pdf->SetFont('Arial', 'B', 14);
		$this->pdf->Cell(64, 5, '', '', 0, 'C');
		$this->pdf->Cell(60, 17, 'NRO.' . $data_reception['reception']->codigo_recepcion, '', 0, 'C');
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->MultiCell(75, 4, utf8_decode($data_reception['branch_office']->direccion), '', 'J');
		$this->pdf->Cell(120, 4, '', '', 0, 'C');
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->MultiCell(79, 4, '    ' . utf8_decode($data_reception['branch_office']->web), '', 'J');
		$this->pdf->Cell(120, 4, '', '', 0, 'C');
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->MultiCell(79, 4, utf8_decode($data_reception['branch_office']->ciudad_impuestos), '', 'C');
		$this->pdf->Ln(4);
		$nro = 1;
		$nro = $nro + 1;
		$this->pdf->SetTextColor(0, 000, 000);
		/*  DATOS DE LA RECEPCION*/

		if ($data_reception['reception']->garantia == 1) {
			$garantia = 'CON GARANTÍA';
		} elseif ($data_reception['reception']->garantia == 2) {
			$garantia = 'POR VERIFICAR';
		} else {
			$garantia = 'SIN GARANTÍA';
		}
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(15, 5, 'Cliente    : ', 'LT', 0, 'L');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(35, 5, utf8_decode(text_format($data_reception['reception']->nombre)), 'T', 0, 'L');
		// $this->pdf->SetFont('Arial', 'B', 8);
		// $this->pdf->Cell(18, 5, 'Contacto : ', 'T', 0, 'L');
		// $this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(84, 5, '', 'T', 0, 'L');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(15, 5, utf8_decode('Teléfono: '), 'T', 0, 'L');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(40, 5, $data_reception['reception']->telefono1 . '-' . $data_reception['reception']->telefono2, 'TR', 0, 'L');
		$this->pdf->Ln(5);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(15, 5, utf8_decode('C.I.           :   '), 'L', 0, 'L');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(35, 5, utf8_decode(text_format($data_reception['reception']->ci)), '', 0, 'L');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(18, 5, 'Vendedor  : ', '', 0, 'L');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(66, 5, utf8_decode(text_format($data_reception['user']->nombre)), '', 0, 'L');
		$this->pdf->SetFont('Arial', 'B', 8);
		//$this->pdf->Cell(15, 5, utf8_decode('Técnico: '), '', 0, 'L');
		$this->pdf->Cell(18, 5, utf8_decode('F. recepción :'), '', 0, 'L');
		$this->pdf->SetFont('Arial', '', 8);
		//$this->pdf->Cell(34, 5, utf8_decode(text_format($data_reception['user_technical']->usuario)).'SN', 'R', 0, 'L');
		$this->pdf->Cell(37, 5, utf8_decode(' ' . date('d-m-Y', strtotime($data_reception['reception']->fecha_registro))), 'R', 0, 'L');
		$this->pdf->Ln(5);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(15, 5, utf8_decode('Equipo    : '), 'LB', 0, 'L');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(35, 5, utf8_decode($garantia), 'B', 0, 'L');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(18, 5, utf8_decode('Dirección : '), 'B', 0, 'L');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(121, 5, utf8_decode(text_format($data_reception['reception']->direccion)), 'BR', 0, 'L');


		$this->pdf->Ln(7);
		/* Encabezado de la columna*/
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(8, 5, "NRO.", 1, 0, 'C');
		$this->pdf->Cell(19, 5, "MARCA", 1, 0, 'C');
		$this->pdf->Cell(24, 5, "MODELO", 1, 0, 'C');
		$this->pdf->Cell(21, 5, "IMEI1", 1, 0, 'C');
		$this->pdf->Cell(30, 5, "IMEI2", 1, 0, 'C');
		$this->pdf->Cell(66, 5, "ACCESORIOS", 1, 0, 'C');
		$this->pdf->Cell(21, 5, utf8_decode("CONTRASEÑA"), 1, 0, 'C');
		$this->pdf->Ln(5);
		$this->pdf->SetFont('Arial', '', 8);
		//------------------------------------------
		$cantidad_filas = 0;
		$img_x=75;
		$numero_items = 15;
		$estilo = 'RL';
		$estilo = 'RL';
		$this->pdf->SetWidths(array(8, 19, 24, 21, 30, 66, 21));
		$this->pdf->Row(array(
			utf8_decode('1'),
			utf8_decode($data_reception['reception']->nombre_marca),
			utf8_decode($data_reception['reception']->nombre_comercial),
			utf8_decode($data_reception['reception']->imei),
			utf8_decode($data_reception['reception']->imei2),
			utf8_decode($data_reception['reception']->accesorio_dispositivo),
			utf8_decode($data_reception['reception']->codigo_seguridad)
		));

		$this->pdf->Ln(2);
		/* Encabezado de la columna*/
		$data_failures = '';
		$data_solutions = '';
		$list_failures = $data_reception['detail_failures'];
		$list_solutions = $data_reception['detail_solutions'];
		$switch = false;
		foreach ($list_failures AS $row_failure):
			if (!$switch) {
				$data_failures = $data_failures . $row_failure->nombre;
				$switch = true;
			} else {
				$data_failures = $data_failures . ' - ' . $row_failure->nombre;
			}

		endforeach;

		$switch = false;
		foreach ($list_solutions AS $row_solition):
			if (!$switch) {
				$data_solutions = $data_solutions . $row_solition->nombre;
				$switch = true;
			} else {
				$data_solutions = $data_solutions . ' - ' . $row_solition->nombre;
			}

		endforeach;
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(94.5, 5, "PROBLEMAS QUE MANIFIESTA EL CLIENTE", 1, 0, 'C');
		$this->pdf->Cell(94.5, 5, "POSIBLES SOLUCIONES", 1, 0, 'C');
		$this->pdf->Ln(5);
		$this->pdf->SetWidths(array(94.5, 94.5));
		$this->pdf->SetFont('Arial', '', 6);


		$this->pdf->Row(array(
			utf8_decode($data_failures),
			utf8_decode($data_solutions)
		));

		/*  LISTADO DE SERVICIOS */
		$data_list_services = $data_reception['detail_services'];
		if ($data_reception['reception']->garantia == SIN_GARANTIA || $data_reception['reception']->garantia == POR_VERIFICAR) {
			if (count($data_list_services) > 0) {
				$this->pdf->Ln(1);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(192, 5, "LISTA DE SERVICIOS", 0, 0, 'C');
				$this->pdf->Ln(5);
				$this->pdf->Ln(1);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(110, 5, "DETALLE ", 1, 0, 'C');
				$this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
				$this->pdf->Cell(30, 5, "PRECIO(Bs.)", 1, 0, 'C');
				$this->pdf->Cell(29, 5, "SUBTOTAL(Bs.)", 1, 0, 'C');
				$this->pdf->SetWidths(array(110, 20, 30, 29));
				$this->pdf->SetAligns(array('L', 'R', 'R', 'R'));
				$this->pdf->Ln(5);
				$this->pdf->SetFont('Arial', '', 6);
				$total_amount_reception = 0;
				$img_x+=35;
				foreach ($data_list_services AS $row_service):
					$service_observation = '( ' . $row_service->observacion . ' ).';
					if (is_null($row_service->observacion) || $row_service->observacion == '') {
						$service_observation = '';
					}
					$this->pdf->Row(array(
						utf8_decode($row_service->nombre_servicio . $service_observation),
						utf8_decode(number_format(1, 0, '.', '')),
						utf8_decode($row_service->precio_servicio),
						utf8_decode($row_service->precio_servicio)
					));
					$total_amount_reception = $total_amount_reception + $row_service->precio_servicio;
					$img_x+=1.5;
				endforeach;

				if ($data_reception['order_work']->estado_trabajo != ENTREGADO) {
					$this->pdf->Cell(130, 5, '', '', 0, 'C');
					$this->pdf->Cell(30, 5, "MONTO SUBTOTAL:", 'LB', 0, 'C');
					$this->pdf->Cell(29, 5, number_format($total_amount_reception, CANTIDAD_MONTO_DECIMAL, '.', ''), 'LRB', 0, 'R');
				}

			}
		} else {
			// if (count($data_list_services) > 0) {
			// 	$this->pdf->Ln(2);
			// 	$this->pdf->SetFont('Arial', 'B', 8);
			// 	$this->pdf->Cell(192, 5, "LISTA DE SERVICIOS", 0, 0, 'C');
			// 	$this->pdf->Ln(5);
			// 	$this->pdf->Ln(2);
			// 	$this->pdf->SetFont('Arial', 'B', 8);
			// 	$this->pdf->Cell(169, 5, "DETALLE ", 1, 0, 'C');
			// 	$this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
			// 	$this->pdf->SetWidths(array(169, 20));
			// 	$this->pdf->SetAligns(array('L', 'R'));
			// 	$this->pdf->Ln(5);
			// 	$this->pdf->SetFont('Arial', '', 6);
			// 	$total_amount_reception = 0;
			// 	foreach ($data_list_services AS $row_service):
			// 		$this->pdf->Row(array(
			// 			utf8_decode($row_service->nombre_servicio . '( ' . $row_service->observacion . ' ).'),
			// 			utf8_decode(number_format(1, CANTIDAD_MONTO_DECIMAL, '.', ''))
			// 		));
			// 		$total_amount_reception = $total_amount_reception + $row_service->precio_servicio;
			// 	endforeach;
			// }
		}

		/*  LISTADO DE PRODUCTOS */
		$data_list_products = $data_reception['detail_products'];
		// if ($data_reception['order_work']->estado_trabajo == ENTREGADO) {
			if (count($data_list_products) > 0) {
				$this->pdf->Ln(6);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(192, 5, "REPUESTOS O ACCESORIOS NECESARIOS", 0, 0, 'C');
				$this->pdf->Ln(5);
				$this->pdf->Ln(1);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(110, 5, "DETALLE ", 1, 0, 'C');
				$this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
				$this->pdf->Cell(30, 5, "PRECIO(Bs.)", 1, 0, 'C');
				$this->pdf->Cell(29, 5, "SUBTOTAL(Bs.)", 1, 0, 'C');

				$this->pdf->SetWidths(array(110, 20, 30, 29));
				$this->pdf->SetAligns(array('L', 'R', 'R', 'R'));
				$this->pdf->Ln(5);
				$this->pdf->SetFont('Arial', '', 6);
				$img_x+=22;
				foreach ($data_list_products AS $row_product):
					$this->pdf->Row(array(
						utf8_decode($row_product->nombre_producto),
						utf8_decode(number_format($row_product->cantidad, CANTIDAD_MONTO_DECIMAL, '.', '')),
						utf8_decode(number_format($row_product->precio_venta, CANTIDAD_MONTO_DECIMAL, '.', '')),
						utf8_decode(number_format(intval($row_product->cantidad) * intval($row_product->precio_venta), CANTIDAD_MONTO_DECIMAL, '.', ''))
					));
					$img_x+=1.5;
				endforeach;
			}
		// } else {
		// 	if (count($data_list_products) > 0) {
		// 		$this->pdf->Ln(6);
		// 		$this->pdf->SetFont('Arial', 'B', 8);
		// 		$this->pdf->Cell(192, 5, "REPUESTOS O ACCESORIOS NECESARIOS", 0, 0, 'C');
		// 		$this->pdf->Ln(5);
		// 		$this->pdf->Ln(1);
		// 		$this->pdf->SetFont('Arial', 'B', 8);
		// 		$this->pdf->Cell(169, 5, "DETALLE ", 1, 0, 'C');
		// 		$this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
		// 		$this->pdf->SetWidths(array(169, 20));
		// 		$this->pdf->SetAligns(array('L', 'R'));
		// 		$this->pdf->Ln(5);
		// 		$this->pdf->SetFont('Arial', '', 6);
		// 		foreach ($data_list_products AS $row_product):
		// 			$this->pdf->Row(array(
		// 				utf8_decode($row_product->nombre_producto),
		// 				utf8_decode(number_format($row_product->cantidad, CANTIDAD_MONTO_DECIMAL, '.', ''))
		// 			));
		// 		endforeach;
		// 	}
		// }

		$this->pdf->Ln(7);
		if (count($data_list_products) > 0 || count($data_list_services) > 0) {
			$this->pdf->Cell(130, 5, "", 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(30, 5, "SUBTOTAL", 1, 0, 'C');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(29, 5, utf8_decode($data_reception['order_work']->monto_subtotal), 1, 0, 'R');
			$this->pdf->Ln(5);
			$this->pdf->Cell(130, 5, "", 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(30, 5, "DESCUENTO", 1, 0, 'C');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(29, 5, ($data_reception['order_work']->descuento != '') ? utf8_decode($data_reception['order_work']->descuento) : '0.00', 1, 0, 'R');
			$this->pdf->Ln(5);
		
			$this->pdf->Cell(130, 5, "", 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(30, 5, "TOTAL", 1, 0, 'C');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(29, 5, utf8_decode($data_reception['order_work']->monto_total), 1, 0, 'R');

			$this->pdf->ln(6);
			$this->pdf->SetFont('Arial', 'B', 8);
			// $img_x+=2;	
		}
		
		// $this->pdf->Ln(1);
		if (count($data_list_products) == 0 && count($data_list_services) == 0) {
			$var_img = base_url() . 'assets/images/patron.png';
			$this->pdf->Image($var_img, $this->pdf->GetPageWidth() - 40, $img_x+5, 30, 30);
		}
		// $this->pdf->Ln(1);
		$this->pdf->Cell(7, 1, 'OBSERVACIONES', '', 0, 'L');
		$this->pdf->Ln(1);
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Ln(2);
		$observation_reception = $data_reception['reception']->observacion_recepcion;
		$observation_order_work = $data_reception['order_work']->observacion;
		//$this->pdf->Cell(189, 5, utf8_decode(is_null($observation_order_work) ? $observation_reception : $observation_order_work), 'B', 0, 'L');
		/*$this->pdf->SetWidths(array(189));
        $this->pdf->Row(array(utf8_decode(is_null($observation_order_work) ? $observation_reception : $observation_order_work)));*/
		$this->pdf->SetFont('Arial', '', 7);
		$this->pdf->MultiCell(150, 3, utf8_decode(is_null($observation_order_work) ? $observation_reception : $observation_order_work), 'B', 'L', '');
		$this->pdf->Ln(20);
		if (intval($data_reception['order_work']->estado_trabajo) === ENTREGADO) {
			$this->pdf->Cell(95, 15, '--------------------------- ', '', 0, 'C');
			$this->pdf->Cell(75, 5, '--------------------------- ', '', 0, 'C');
			$this->pdf->ln(2);
			$this->pdf->Cell(95, 5, 'Responsable ', '', 0, 'C');
			$this->pdf->Cell(75, 5, 'Cliente ', '', 0, 'C');

		} else {
			$this->pdf->Cell(55, 5, '--------------------------- ', '', 0, 'C');
			$this->pdf->Cell(55, 5, '--------------------------- ', '', 0, 'C');
			$this->pdf->Cell(55, 5, '--------------------------- ', '', 0, 'C');
			$this->pdf->ln(2);
			$this->pdf->Cell(55, 5, 'Recepcion ', '', 0, 'C');
			$this->pdf->Cell(55, 5, 'Cliente ', '', 0, 'C');
			$this->pdf->Cell(55, 5, 'Entrega ', '', 0, 'C');
		}


		$this->pdf->ln(5);
		$this->pdf->SetFont('Arial', '', 7);
		$size_width = $this->pdf->GetPageWidth();
		$this->pdf->Cell(192, 1, 'PASADO LOS 30 DIAS CALENDARIO, LA EMPRESA NO SE HACE RESPONSABLE DE CUALQUIER PERDIDA, ROBO O PROBLEMA POSTERIOR A LO ACLARADO, ', '', 0, 'C');
		$this->pdf->ln(3);
		$this->pdf->Cell(192, 1, 'TODOS LOS DISPOSITIVOS SERAN FLASHEADOS PARA EL BUEN FUNCIONAMIENTO DEL SOFTWARE Y/O NUEVO HARDWARE, POR LO TANTO EL CLIENTE', '', 0, 'C');
		$this->pdf->ln(3);
		$this->pdf->Cell(192, 1, 'ES CONSCIENTE QUE SU INFORMACION PERSONAL SE BORRARA POR COMPLETO', '', 0, 'C');
		$this->pdf->ln(3);
		$this->pdf->Cell(192, 1, 'EN CASO DE NO ACEPTAR LA REPARACION, DEBERA CANCELAR POR EL DIAGNOSTICO BS 50.', '', 0, 'C');
		$this->pdf->ln(3);
		$this->pdf->Cell(192, 1, 'EL CLIENTE DEBE PERITAR LA FUNCIONALIDAD DEL DISPOSITIVO. UNA VEZ RETIRADO, LA EMPRESA NO SE RESPONSABILIZA DE OTRAS FALLAS NO DECLARADAS', '', 0, 'C');
		$print_day = date('Y-m-d');
		$print_hour = date('H:i:s');
		$year = substr($print_day, 0, 4);
		$month = substr($print_day, 5, 2);
		$day = substr($print_day, 8, 2);
		$print_date = $day . ' de ' . get_month($month) . ' del ' . $year;

		$this->pdf->Ln(3);
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
		$this->pdf->Cell(44, 5, get_user_name_in_session(), 'BTR', 0, 'L');

		$this->pdf->SetMargins(10, 10, 10);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->SetWidths(array(20, 100, 60));
		//un arreglo con alineacion de cada celda
		$this->pdf->SetAligns(array('C', 'L', 'R'));
		/*  quitamos bolf, y empezamos a dibujar las grillas*/
		$this->pdf->SetFont('Arial', '', 9);
		$this->pdf->SetFont('Arial', '', 9);
		$this->pdf->Output("OrdenTrabajo.pdf", 'I');

	}
}
