<?php
/**
 * User: Ing. Ariel Alejandro Gomez Chavez
 * Github: https://github.com/ariel-ssj
 * Date: 30/9/2019 17:40
 */

class Reception_payment extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reception_payment_model');
		$this->load->model('reception_model');
	}

	public function index()
	{
		if ($this->input->post()) {
			$id_reception = $this->input->post('id');
			$reception_for_new = $this->reception_model->get_reception_by_id($id_reception);
//            $pagos = $this->pago_model->get_sum_pago_by_reception_id($id_reception);
			$data = array(
				'reception_id' => $id_reception,
				'sum_pago' => 0,
				'reception_for_new' => $reception_for_new
			);
			template('reception/reception_payment', $data);
		} else {
			show_404();
		}
	}

	public function register_payment()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->reception_payment_model->register_payment();
		} else {
			show_404();
		}
	}

	public function print_payment()
	{
		$reception_id = $this->input->post('reception_id');
		$payment_id = $this->input->post('payment_id');
		$data_pago = $this->reception_payment_model->get_payment_by_id($payment_id);
		$data_reception = $this->reception_model->getdata_printer_report($reception_id);
		$this->load->library('pdf');
		$this->pdf = new Pdf('P', 'mm', 'Legal');
		$this->pdf->SetTopMargin(0.0);
		$this->pdf->AddPage();
		$this->pdf->AliasNbPages();

		/* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado */
		$type_impresion = 'RECIBO DE PAGO';
		$this->pdf->SetTitle($type_impresion);
		$this->pdf->SetFont('Arial', 'B', 7);
		$this->pdf->Ln(6);
		$var_img = base_url() . 'assets/images/logo_empresa.jpg';
		$start_x = ($this->pdf->GetPageWidth() / 2) - 39;
		$this->pdf->Image($var_img, 10, 5, 66, 25);
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(140, 4, '', '', 0, 'C');
		$this->pdf->Cell(50, 4, utf8_decode($data_reception['company']->nombre_empresa), '', 0, 'C');
		$this->pdf->Ln(4);
		$this->pdf->Cell(50, 2, ' ', '', 0, 'C');
		$this->pdf->SetTextColor(248, 000, 000);
		$this->pdf->SetFont('Arial', 'B', 14);
		$this->pdf->Cell(90, 12, $type_impresion, '', 0, 'C');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->Cell(50, 4, utf8_decode($data_reception['branch_office']->nombre_comercial), '', 0, 'C');
		$this->pdf->SetTextColor(248, 000, 000);
		//$this->pdf->Cell(61, 5, 'NRO. ' . '000601', 0, 0, 'C');
		$this->pdf->Ln(3);
		$this->pdf->SetFont('Arial', 'B', 14);
		$this->pdf->Cell(50, 5, '', '', 0, 'C');
		$this->pdf->Cell(90, 17, 'O.T. ' . $data_reception['reception']->codigo_recepcion, '', 0, 'C');
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->MultiCell(50, 4, utf8_decode($data_reception['branch_office']->direccion), '', 'C');
		$this->pdf->Cell(140, 4, '', '', 0, 'C');
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->MultiCell(50, 4, 'Telf. ' . utf8_decode($data_reception['branch_office']->telefono), '', 'C');
		$this->pdf->Cell(140, 4, '', '', 0, 'C');
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->MultiCell(50, 4, utf8_decode($data_reception['branch_office']->ciudad_impuestos), '', 'C');
		$this->pdf->Ln(4);
		$nro = 1;
		$nro = $nro + 1;
		$this->pdf->SetTextColor(0, 000, 000);

		/*  DATOS DE LA RECEPCION*/
		$payment_year = substr($data_pago->fecha_registro, 0, 4);
		$payment_month = substr($data_pago->fecha_registro, 5, 2);
		$payment_day = substr($data_pago->fecha_registro, 8, 2);
		$payment_date = $payment_day . ' de ' . get_month($payment_month) . ' del ' . $payment_year;

		/*  LUGAR Y FECHA ///// NIT CI*/
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(22, 5, 'Fecha Pago : ', 'TL');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(100, 5, utf8_decode($data_reception['branch_office']->ciudad) . ', ' . $payment_date, 'T');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(15, 5, 'Vendedor : ', 'T');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(52, 5, utf8_decode(text_format($data_reception['user']->nombre)), 'TR');
		$this->pdf->Ln(5);

		/*  CLIENTE */
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(22, 5, utf8_decode('Señor(es)         : '), 'LB');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(100, 5, utf8_decode($data_reception['reception']->nombre), 'B');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(15, 5, utf8_decode('C.I./Nit      : '), 'B');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(52, 5, utf8_decode($data_reception['reception']->ci), 'RB');

		$this->pdf->Ln(6);

		/*  LISTADO DE SERVICIOS */
		$this->pdf->Ln(6);

		/*  LISTADO DE SERVICIOS */
		$this->pdf->Ln(1);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(192, 5, "DETALLE", 0, 0, 'C');
		$this->pdf->Ln(5);
		$this->pdf->Ln(1);
		$this->pdf->SetFont('Arial', 'B', 8);

		$this->pdf->Cell(50, 5, "USUARIO", 1, 0, 'C');
		$this->pdf->Cell(35, 5, "FECHA PAGOS", 1, 0, 'C');
		$this->pdf->Cell(80, 5, "DESCRIPCION ", 1, 0, 'C');
		$this->pdf->Cell(25, 5, "PAGOS", 1, 0, 'C');
		$this->pdf->SetWidths(array(50, 35, 80, 25));
		$this->pdf->SetAligns(array('C', 'C', 'L', 'R'));
		$this->pdf->Ln(5);
		$this->pdf->SetFont('Arial', '', 6);

		$total_payment = 0;
		$total_payment_current = 0;
		$total_discount = 0;
		foreach ($data_reception['payments'] as $row_payment) {

			$date_payment = date_create($row_payment->fecha_registro);
			if ($payment_id==$row_payment->id){
				$this->pdf->Row(array(
					utf8_decode(text_format($row_payment->nombre_usuario)),
					utf8_decode(date_format($date_payment, 'd-m-Y')),
					utf8_decode(text_format($row_payment->observacion)),
					utf8_decode(number_format($row_payment->pago, 2, '.', ','))
				));
				$total_payment_current+= $row_payment->pago;
			}
			$total_payment += $row_payment->pago;
			$total_discount += $row_payment->descuento;
		}

		$total_reception=$data_reception['order_work']->monto_subtotal-$total_discount;
		if ($total_discount > 0) {
			$this->pdf->ln(5);
			$this->pdf->Cell(129, 5, "", 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(36, 5, "TOTAL RECEPCION:", 1, 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(25, 5, utf8_decode(number_format($data_reception['order_work']->monto_subtotal, 2)), 1, 0, 'R');

			$this->pdf->ln(5);
			$this->pdf->Cell(129, 5, "", 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(36, 5, "DESCUENTO:", 1, 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(25, 5, utf8_decode(number_format($total_discount, 2)), 1, 0, 'R');

			$this->pdf->ln(5);
			$this->pdf->Cell(129, 5, "", 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(36, 5, "TOTAL A PAGAR:", 1, 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(25, 5, utf8_decode(number_format($total_reception, 2)), 1, 0, 'R');
		}else{
			$this->pdf->ln(5);
			$this->pdf->Cell(129, 5, "", 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(36, 5, "TOTAL A PAGAR:", 1, 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(25, 5, utf8_decode(number_format($total_reception, 2)), 1, 0, 'R');
		}

		$this->pdf->ln(5);
		$this->pdf->Cell(129, 5, "", 0, 0, 'C');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(36, 5, "PAGOS ANTERIORES:", 1, 0, 'R');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(25, 5, utf8_decode(number_format($total_payment-$total_payment_current, 2)), 1, 0, 'R');

		$this->pdf->ln(5);
		$this->pdf->Cell(129, 5, "", 0, 0, 'C');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(36, 5, "PAGO ACTUAL:", 1, 0, 'R');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(25, 5, utf8_decode(number_format($total_payment_current, 2)), 1, 0, 'R');
		$this->pdf->ln(5);
		$this->pdf->Cell(129, 5, "", 0, 0, 'C');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(36, 5, "TOTAL PAGADO:", 1, 0, 'R');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(25, 5, utf8_decode(number_format($total_payment, 2)), 1, 0, 'R');
		$this->pdf->ln(5);
		$this->pdf->Cell(129, 5, "", 0, 0, 'C');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(36, 5, "MONTO SALDO:", 1, 0, 'R');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(25, 5, utf8_decode(number_format($total_reception-$total_payment, 2)), 1, 0, 'R');
		$this->pdf->ln(18);

		$this->pdf->Cell(95, 5, '---------------------------- ', '', 0, 'C');
		$this->pdf->Cell(75, 5, '---------------------------- ', '', 0, 'C');
		$this->pdf->ln(2);
		$this->pdf->Cell(95, 5, 'Entregue Conforme ', '', 0, 'C');
		$this->pdf->Cell(75, 5, 'Recibi Conforme ', '', 0, 'C');

		$print_day = date('Y-m-d');
		$print_hour = date('H:i:s');
		$year = substr($print_day, 0, 4);
		$month = substr($print_day, 5, 2);
		$day = substr($print_day, 8, 2);
		$print_date = $day . ' de ' . get_month($month) . ' del ' . $year;

		$this->pdf->Ln(5);
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
		$this->pdf->Output("ReciboPago.pdf", 'I');
	}

	public function print_payment_history()
	{
		$reception_id = $this->input->post('reception_id');
		$data_reception = $this->reception_model->getdata_printer_report($reception_id);
		$this->load->library('pdf');
		$this->pdf = new Pdf('P', 'mm', 'Legal');
		$this->pdf->SetTopMargin(0.0);
		$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		/* Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
         */
		$type_impresion = 'HISTORIAL DE PAGOS';
		$this->pdf->SetTitle($type_impresion);
		$this->pdf->SetFont('Arial', 'B', 7);
		$this->pdf->Ln(6);
		$var_img = base_url() . 'assets/images/logo_empresa.jpg';
		$start_x = ($this->pdf->GetPageWidth() / 2) - 39;
		$this->pdf->Image($var_img, 10, 5, 66, 25);
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(140, 4, '', '', 0, 'C');
		$this->pdf->Cell(50, 4, utf8_decode($data_reception['company']->nombre_empresa), '', 0, 'C');
		$this->pdf->Ln(4);
		$this->pdf->Cell(50, 2, ' ', '', 0, 'C');
		$this->pdf->SetTextColor(248, 000, 000);
		$this->pdf->SetFont('Arial', 'B', 14);
		$this->pdf->Cell(90, 12, $type_impresion, '', 0, 'C');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->Cell(50, 4, utf8_decode($data_reception['branch_office']->nombre_comercial), '', 0, 'C');
		$this->pdf->SetTextColor(248, 000, 000);
		//$this->pdf->Cell(61, 5, 'NRO. ' . '000601', 0, 0, 'C');
		$this->pdf->Ln(3);
		$this->pdf->SetFont('Arial', 'B', 14);
		$this->pdf->Cell(50, 5, '', '', 0, 'C');
		$this->pdf->Cell(90, 17, 'O.T. ' . $data_reception['reception']->codigo_recepcion, '', 0, 'C');
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->MultiCell(50, 4, utf8_decode($data_reception['branch_office']->direccion), '', 'C');
		$this->pdf->Cell(140, 4, '', '', 0, 'C');
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->MultiCell(50, 4, 'Telf. ' . utf8_decode($data_reception['branch_office']->telefono), '', 'C');
		$this->pdf->Cell(140, 4, '', '', 0, 'C');
		$this->pdf->SetTextColor(0, 000, 000);
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->MultiCell(50, 4, utf8_decode($data_reception['branch_office']->ciudad_impuestos), '', 'C');
		$this->pdf->Ln(4);
		$this->pdf->SetTextColor(0, 000, 000);

		$device_customer = $data_reception['reception']->nombre_marca . ' ' . $data_reception['reception']->nombre_comercial . ' / ' . $data_reception['reception']->imei;
		$reception_year = substr($data_reception['reception']->fecha_registro, 0, 4);
		$reception_month = substr($data_reception['reception']->fecha_registro, 5, 2);
		$reception_day = substr($data_reception['reception']->fecha_registro, 8, 2);
		$reception_date = $reception_day . ' de ' . get_month($reception_month) . ' del ' . $reception_year;

		$this->pdf->Ln(5);
		/*  CLIENTE */
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(22, 5, utf8_decode('Señor(es)         : '), 'LT');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(100, 5, utf8_decode($data_reception['reception']->nombre), 'T');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(15, 5, utf8_decode('C.I./Nit      : '), 'T');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(52, 5, utf8_decode($data_reception['reception']->ci), 'RT');

		$this->pdf->Ln(5);
		/*  RECEPCION */
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(25, 5, utf8_decode('Fecha Recepcion: '), 'BL');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(72, 5, utf8_decode($reception_date), 'B');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(25, 5, utf8_decode('Equipo Cliente : '), 'B');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(67, 5, utf8_decode(text_format($device_customer)), 'BR');


		$this->pdf->Ln(6);
		/*  LISTADO DE SERVICIOS */
		$this->pdf->Ln(1);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(192, 5, "DETALLE", 0, 0, 'C');
		$this->pdf->Ln(5);
		$this->pdf->Ln(1);
		$this->pdf->SetFont('Arial', 'B', 8);

		$this->pdf->Cell(50, 5, "USUARIO", 1, 0, 'C');
		$this->pdf->Cell(35, 5, "FECHA PAGOS", 1, 0, 'C');
		$this->pdf->Cell(80, 5, "DESCRIPCION ", 1, 0, 'C');
		$this->pdf->Cell(25, 5, "PAGOS", 1, 0, 'C');
		$this->pdf->SetWidths(array(50, 35, 80, 25));
		$this->pdf->SetAligns(array('C', 'C', 'L', 'R'));
		$this->pdf->Ln(5);
		$this->pdf->SetFont('Arial', '', 6);

		$total_payment = 0;
		$total_discount = 0;
		foreach ($data_reception['payments'] as $row_payment) {
			$date_payment = date_create($row_payment->fecha_registro);
			$this->pdf->Row(array(
				utf8_decode(text_format($row_payment->nombre_usuario)),
				utf8_decode(date_format($date_payment, 'd-m-Y')),
				utf8_decode(text_format($row_payment->observacion)),
				utf8_decode(number_format($row_payment->pago, 2, '.', ','))
			));
			$total_payment += $row_payment->pago;
			$total_discount += $row_payment->descuento;
		}

		$total_reception=$data_reception['order_work']->monto_subtotal-$total_discount;
		if ($total_discount > 0) {
			$this->pdf->ln(5);
			$this->pdf->Cell(129, 5, "", 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(36, 5, "TOTAL RECEPCION:", 1, 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(25, 5, utf8_decode(number_format($data_reception['order_work']->monto_subtotal, 2)), 1, 0, 'R');

			$this->pdf->ln(5);
			$this->pdf->Cell(129, 5, "", 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(36, 5, "DESCUENTO:", 1, 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(25, 5, utf8_decode(number_format($total_discount, 2)), 1, 0, 'R');

			$this->pdf->ln(5);
			$this->pdf->Cell(129, 5, "", 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(36, 5, "TOTAL A PAGAR:", 1, 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(25, 5, utf8_decode(number_format($total_reception, 2)), 1, 0, 'R');
		}else{
			$this->pdf->ln(5);
			$this->pdf->Cell(129, 5, "", 0, 0, 'C');
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(36, 5, "TOTAL A PAGAR:", 1, 0, 'R');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(25, 5, utf8_decode(number_format($total_reception, 2)), 1, 0, 'R');
		}

		$this->pdf->ln(5);
		$this->pdf->Cell(129, 5, "", 0, 0, 'C');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(36, 5, "MONTO TOTAL PAGADO:", 1, 0, 'R');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(25, 5, utf8_decode(number_format($total_payment, 2)), 1, 0, 'R');

		$this->pdf->ln(5);
		$this->pdf->Cell(129, 5, "", 0, 0, 'C');
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(36, 5, "MONTO SALDO:", 1, 0, 'R');
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(25, 5, utf8_decode(number_format($total_reception-$total_payment, 2)), 1, 0, 'R');


		$this->pdf->ln(18);

		$this->pdf->Cell(95, 5, '---------------------------- ', '', 0, 'C');
		$this->pdf->Cell(75, 5, '---------------------------- ', '', 0, 'C');
		$this->pdf->ln(2);
		$this->pdf->Cell(95, 5, 'Entregue Conforme ', '', 0, 'C');
		$this->pdf->Cell(75, 5, 'Recibi Conforme ', '', 0, 'C');

		$print_day = date('Y-m-d');
		$print_hour = date('H:i:s');
		$year = substr($print_day, 0, 4);
		$month = substr($print_day, 5, 2);
		$day = substr($print_day, 8, 2);
		$print_date = $day . ' de ' . get_month($month) . ' del ' . $year;

		$this->pdf->Ln(5);
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
		$this->pdf->Output("ReciboPago.pdf", 'I');

	}

	public function get_reception_payment_list()
	{
		if ($this->input->is_ajax_request()) {
			// Se recuperan los parametros enviados por datatable
			$id_reception = $this->input->post('id_reception');
			$start = $this->input->post('start');
			$limit = $this->input->post('length');
			$search = $this->input->post('search')['value'];
			$order = $this->input->post('order')['0']['dir'];
			$column_num = $this->input->post('order')['0']['column'];
			$column = $this->input->post('columns')[$column_num]['data'];

			// Se almacenan los parametros recibidos en un array para enviar al modelo
			$params = array(
				'id_reception' => $id_reception,
				'start' => $start,
				'limit' => $limit,
				'search' => $search,
				'column' => $column,
				'order' => $order
			);
			echo json_encode($this->reception_payment_model->get_reception_payment_list($params));
		} else {
			show_404();
		}
	}

	public function get_sum_reception_payments()
	{
		if ($this->input->is_ajax_request()) {
			$id_reception = $this->input->post('id');
			echo json_encode($this->reception_payment_model->get_sum_reception_payments($id_reception));
		} else {
			show_404();
		}
	}

	public function disable()
	{
		if ($this->input->is_ajax_request()) {
			echo $this->reception_payment_model->disable();
		} else {
			show_404();
		}
	}

}
