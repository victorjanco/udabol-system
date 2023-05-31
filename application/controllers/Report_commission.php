<?php

/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 16/09/2019
 * Time: 14:46 PM
 */
ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');

class Report_commission extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('brand_model');
		$this->load->model('model_model');
		$this->load->model('product_model');
		$this->load->model('commission_branch_office_model');
		$this->load->model('serie_model');
		$this->load->model('report_commission_model');
		$this->load->model('brand_model');
		$this->load->model('model_model');
		$this->load->model('product_model');
	}

	public function index()
	{
		$data["list_brand"] = $this->brand_model->get_brand_enable();
		$data["list_model"] = $this->model_model->get_model_enable();
		$data["list_product"] = $this->product_model->get_product_enable();
		$data["list_commission_branch_office"] = $this->commission_branch_office_model->get_all_commission_branch_office();
		$data["list_serie"] = $this->serie_model->get_all_serie();
		template('report/report_for_commission', $data);
	}

	public function report_import_commission()
	{
		template('report/report_import_commission', null);
	}

	public function get_report_commission()
	{
		if ($this->input->is_ajax_request()) {
			$start = $this->input->post('start');
			$limit = $this->input->post('length');
			$search = $this->input->post('search')['value'];
			$order = $this->input->post('order')['0']['dir'];
			$column_num = $this->input->post('order')['0']['column'];
			$column = $this->input->post('columns')[$column_num]['data'];

			/*Parametros de busqueda sin Datatable*/
			$brand = $this->input->post('brand');
			$model = $this->input->post('model');
			$serie = $this->input->post('serie');
			$product = $this->input->post('product');
			$commission_branch_office = $this->input->post('commission_branch_office');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');

			// Se almacenan los parametros recibidos en un array para enviar al model
			$params = array(
				'start' => $start,
				'limit' => $limit,
				'search' => $search,
				'column' => $column,
				'order' => $order,
				'brand' => $brand,
				'model' => $model,
				'serie' => $serie,
				'product' => $product,
				'commission_branch_office' => $commission_branch_office,
				'start_date' => $start_date,
				'end_date' => $end_date
			);

			$data_commission = $this->report_commission_model->get_data_report_commission($params);

			echo json_encode($data_commission);
		} else {
			show_404();
		}
	}

	public function import_report_commission()
	{
		// Manipulacion del archivo excel
		$file = $_FILES['excel']['name'];

		if (!is_dir("./excel_files"))
			mkdir("./excel_files", 0777);

		if ($file && copy($_FILES['excel']['tmp_name'], "./excel_files/" . $file)) {
			require_once APPPATH . 'libraries/excel/PHPExcel.php';
			require_once APPPATH . 'libraries/excel/PHPExcel/Reader/Excel2007.php';

			$objReader = new PHPExcel_Reader_Excel2007();
			$objPHPExcel = $objReader->load("./excel_files/" . $file);

			// Número de filas del archivo excel
			$filas = $objPHPExcel->getActiveSheet()->getHighestRow();
			$NRO_FILA_EXCEL = 2;

			$this->db->trans_begin();

			for ($i = $NRO_FILA_EXCEL; $i <= $filas; $i++) {

				$date_transaction_excel = $objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue()+1;

//				$date_transaction = date("Y-m-d", strtotime($date_transaction_excel));
				$date_transaction = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($date_transaction_excel));
				$code_product = trim(strval($objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue()));
				$branch_office_commission = trim(strval($objPHPExcel->getActiveSheet()->getCell("C" . $i)->getValue()));
				$glosa = trim(strval($objPHPExcel->getActiveSheet()->getCell("D" . $i)->getValue()));
				$quantity = trim(strval($objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue()));
				$total_bs = trim(strval($objPHPExcel->getActiveSheet()->getCell("F" . $i)->getValue()));
				$total_sus = trim(strval($objPHPExcel->getActiveSheet()->getCell("G" . $i)->getValue()));
				$code_transaction = trim(strval($objPHPExcel->getActiveSheet()->getCell("H" . $i)->getValue()));
				$percentage = trim(strval($objPHPExcel->getActiveSheet()->getCell("I" . $i)->getValue()));
				$commission_bs = floatval($percentage) * floatval($total_bs);

				/*echo 'fecha de excel: '.$date_transaction_excel;
				echo 'fecha convertida es: '.$date_transaction;
				return;*/
				if ($branch_office_commission != '') {
					// PRODUCTO
					if ($this->product_model->exists_product_by_code($code_product) == 0) {
						unlink("./excel_files/" . $file);
						echo '2';
						return;
					}
					$product_id = $this->product_model->get_product_by_code($code_product)->id;

					// SUCURSAL COMISION
					if ($this->commission_branch_office_model->exists_commission_branch_office_by_name($branch_office_commission) == 0) {
						$data_sucursal = array(
							'name' => $code_transaction,
							'descripcion' => $code_product,
							'fecha_registro' => date('Y-m-d'),
							'estado' => ACTIVO

						);
						$this->db->insert('sucursal_comision', $data_sucursal);


						/*unlink("./excel_files/" . $file);
						echo '3';
						return;*/
					}
					$commission_branch_office_id = $this->commission_branch_office_model->get_commission_branch_office_by_name($branch_office_commission)->id;
					$commission_bs = floatval($this->product_model->get_product_by_code($code_product)->porcentaje_comision) * floatval($total_bs);
					if ($this->report_commission_model->exists_transaction_commission($date_transaction, $code_product, $branch_office_commission, $glosa, $code_transaction) == 0) {

						$data_commission = array(
							'codigo_transaccion' => $code_transaction,
							'codigo_producto' => $code_product,
							'nombre_sucursal_comision' => $branch_office_commission,
							'glosa_productos' => $glosa,
							'serie' => $this->product_model->get_product_by_id($product_id)->nombre_serie,
							'cantidad' => $quantity,
							'total_bs' => $total_bs,
							'total_sus' => $total_sus,
							'porcentaje_comision' => $this->product_model->get_product_by_code($code_product)->porcentaje_comision,
							'comision_bs' => $commission_bs,
							'fecha_transaccion' => $date_transaction,
							'fecha_registro' => date('Y-m-d H:i:s'),
							'estado' => ACTIVO,
							'producto_id' => $product_id,
							'sucursal_comision_id' => $commission_branch_office_id,
							'usuario_id' => get_user_id_in_session(),
							'sucursal_id' => get_branch_id_in_session()
						);
						$this->db->insert('transaccion_comision', $data_commission);

					} else {

						$transaction_commission_id = $this->report_commission_model->get_transaction_commission($date_transaction, $code_product, $branch_office_commission, $glosa, $code_transaction)->id;

						$data_commission = array(
							'cantidad' => $quantity,
							'total_bs' => $total_bs,
							'total_sus' => $total_sus,
							'porcentaje_comision' => $this->product_model->get_product_by_code($code_product)->porcentaje_comision,
							'comision_bs' => $commission_bs,
							'fecha_modificacion' => date('Y-m-d H:i:s'),
							'usuario_id' => get_user_id_in_session(),
							'sucursal_id' => get_branch_id_in_session()
						);

						$this->db->where('id', $transaction_commission_id);
						$this->db->update('transaccion_comision', $data_commission);
					}

				}
			}

			unlink("./excel_files/" . $file);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo '0';
			} else {
				$this->db->trans_commit();
				echo '1';
			}
		}

	}

	public function import_permission()
	{
		$response = array(
			'success' => 1,
		);

		if (get_user_type_in_session() == 2 || get_user_type_in_session() == 6) {
			$response['success'] = 1;
		} else {
			$response['success'] = 0;
		}

		echo json_encode($response);
	}

	public function export_report_commission()
	{
		$this->load->library("excel/PHPExcel");

		/*Parametros*/
		$brand = $this->input->post('brand');
		$model = $this->input->post('model');
		$serie = $this->input->post('serie');
		$product = $this->input->post('product');
		$commission_branch_office = $this->input->post('commission_branch_office');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');

		/*Array*/
		$params = array(
			'brand' => $brand,
			'model' => $model,
			'serie' => $serie,
			'product' => $product,
			'commission_branch_office' => $commission_branch_office,
			'start_date' => $start_date,
			'end_date' => $end_date
		);

		$data_commission = $this->report_commission_model->get_data_report_commission($params);
		$list_data = $data_commission['data'];

		if ($brand != 0) {
			$data_brand = $this->brand_model->get_brand_id($brand)->nombre;
		} else {
			$data_brand = 'Todos';
		}
		if ($model != 0) {
			$data_model = $this->model_model->get_model_id($model)->nombre;
		} else {
			$data_model = 'Todos';
		}
		if ($serie != 0) {
			$data_serie = $this->serie_model->get_serie_by_id($serie)->nombre;
		} else {
			$data_serie = 'Todos';
		}
		if ($product != 0) {
			$data_name_product = $this->product_model->get_product_by_id($product)->nombre_comercial;
			$data_product_code = $this->product_model->get_product_by_id($product)->codigo;
		} else {
			$data_name_product = 'Todos';
			$data_product_code = 'Todos';
		}
		if ($commission_branch_office != 0) {
			$data_commission_branch_office = $this->commission_branch_office_model->get_commission_branch_office_by_id($commission_branch_office)->nombre;
		} else {
			$data_commission_branch_office = 'Todos';
		}
		if ($start_date != '') {
			$date_start = $start_date;
		} else {
			$date_start = 'Todos';
		}

		if ($end_date != '') {
			$date_end = $end_date;
		} else {
			$date_end = 'Todos';
		}

		//membuat objek PHPExcel
		$objPHPExcel = new PHPExcel();

		//Unir celdas
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2'); //Titulo
		$objPHPExcel->getActiveSheet()->mergeCells('B4:C4'); //Marca
		$objPHPExcel->getActiveSheet()->mergeCells('F4:G4'); //Modelo
		$objPHPExcel->getActiveSheet()->mergeCells('B5:C5'); //Serie
		$objPHPExcel->getActiveSheet()->mergeCells('F5:G5'); //Sucursal Comision
		$objPHPExcel->getActiveSheet()->mergeCells('F6:G6'); //Producto
		$objPHPExcel->getActiveSheet()->mergeCells('B6:C6'); //Fecha inicio
		$objPHPExcel->getActiveSheet()->mergeCells('F3:G3'); //Fecha fin

		// Datos
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A2', 'REPORTE COMISION')
			->setCellValue('A4', 'MARCA: ')
			->setCellValue('B4', $data_brand)
			->setCellValue('E4', 'MODELO: ')
			->setCellValue('F4', $data_model)
			->setCellValue('A5', 'SERIE: ')
			->setCellValue('B5', $data_serie)
			->setCellValue('E5', 'SUCURSAL COMISION: ')
			->setCellValue('F5', $data_commission_branch_office)
			->setCellValue('E6', 'PRODUCTO:')
			->setCellValue('F6', $data_product_code)
			->setCellValue('A6', 'FECHA INICIO:')
			->setCellValue('B6', $date_start)
			->setCellValue('E3', 'FECHA FIN:')
			->setCellValue('F3', $date_end)
			//Encabezado de la tabla
			->setCellValue('A8', 'NRO.')
			->setCellValue('B8', 'FECHA TRANSACCION')
			->setCellValue('C8', 'CODIGO DE PRODUCTO')
			->setCellValue('D8', 'SUCURSALES DE COMISION')
			->setCellValue('E8', 'GLOSA DE PRODUCTOS')
			->setCellValue('F8', 'SERIE')
			->setCellValue('G8', 'CANTIDAD')
			->setCellValue('H8', 'TOTAL BS')
			->setCellValue('I8', 'TOTAL USD')
			->setCellValue('J8', 'CODIGO TRANSACCION')
			->setCellValue('K8', 'PORCENTAJE')
			->setCellValue('L8', 'COMISION BS');

		//Negrita
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('E4')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('E6')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('E3')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('A8')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('B8')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('C8')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('D8')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('E8')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('F8')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('G8')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('H8')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('I8')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('J8')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('K8')->getFont()->setBold(TRUE)
			->getActiveSheet()->getStyle('L8')->getFont()->setBold(TRUE);

		//Centrar los titulos
		$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('F8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('G8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('H8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('I8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('J8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('K8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->getActiveSheet()->getStyle('L8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		//Pintar los bordes
		$objPHPExcel->getActiveSheet()->getStyle('A8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('B8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('C8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('D8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('E8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('F8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('G8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('H8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('I8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('J8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('K8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('L8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		$fila = 9; // Empieza a escribir desde la linea 9
		$i = 1;
		foreach ($list_data as $row) {

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A' . $fila, $i)
				->setCellValue('B' . $fila, $row['fecha_transaccion'])
				->setCellValue('C' . $fila, $row['codigo'])
				->setCellValue('D' . $fila, $row['nombre_sucursal_comision'])
				->setCellValue('E' . $fila, $row['glosa_productos'])
				->setCellValue('F' . $fila, $row['nombre_serie'])
				->setCellValue('G' . $fila, $row['cantidad'])
				->setCellValue('H' . $fila, $row['total_bs'])
				->setCellValue('I' . $fila, $row['total_sus'])
				->setCellValue('J' . $fila, $row['codigo_transaccion'])
				->setCellValue('K' . $fila, intval($row['porcentaje']) . '%')
				->setCellValue('L' . $fila, $row['comision_bs']);

			//Pintar los bordes
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
				->getActiveSheet()->getStyle('L' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$fila = $fila + 1;
			$i++;
		}

		// Establece anchura automatico a la celda
		$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(TRUE);

		//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		//sesuaikan headernya
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="Reporte por comision' . "_" . date('d-m-Y') . "_" . '.xlsx"');

		//unduh file
		$objWriter->save("php://output");

	}


}
