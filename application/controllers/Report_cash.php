<?php
/**
 * Created by PhpStorm.
 * User: Victor Janco Colque
 */

class Report_cash extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('sucursal_model');
        // $this->load->model('caja_model');
        // $this->load->model('usuario_model');
        // $this->load->model('reporte_caja_model');
        $this->load->model('office_model');
        $this->load->model('cash_model');
        $this->load->model('user_model');
        $this->load->model('reporte_caja_model');
    }

    /* Accede al reporte de cajas */
    public function index()
    {
        // eliminar_detalle_virtual_sesion();
        $data['list_office'] = $this->office_model->get_offices();
        $data['list_cash'] = $this->cash_model->get_cash_enable();
        $data['list_user'] = $this->user_model->get_user_enable();
        template('report/report_cash', $data);
    }

    public function buscar_caja(){
        if ($this->input->is_ajax_request()) {

            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            /*Parametros de busqueda sin Datatable*/
            $report_office = $this->input->post('report_office');
            $report_cash = $this->input->post('report_cash');
            $report_user = $this->input->post('report_user');
            $report_start_date = $this->input->post('report_start_date');
            $report_end_date = $this->input->post('report_end_date');
            // Se almacenan los parametros recibidos en un array para enviar al model
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'report_start_date' => $report_start_date,
                'report_end_date' => $report_end_date,
                'report_office' => $report_office,
                'report_cash' => $report_cash,
                'report_user' => $report_user
            );

            $data_cash = $this->reporte_caja_model->buscar_caja($params);
             /*Set a la consulta por obtener la suma de la salida de inventario*/
            //  $data = array();
            //  foreach ($data_cash['data'] as $row) {
            //     $array = array();
            //     $array['sucursal'] = $row['sucursal'];
            //     $array['caja'] = $row['caja'];
            //     $array['usuario'] = $row['usuario'];
            //     $array['monto_apertura_bs'] = $row['monto_apertura_bs'];
            //     $array['monto_apertura_sus'] = $row['monto_apertura_sus'];
            //     $array['total_ingreso_bs'] = $row['total_ingreso_bs'];
            //     $array['total_ingreso_sus'] = $row['total_ingreso_sus'];
            //     $array['total_egreso_bs'] = $row['total_egreso_bs'];
            //     $array['total_egreso_sus'] = $row['total_egreso_sus'];
            //     $array['monto_cierre_bs'] = $row['monto_cierre_bs'];
            //     $array['monto_cierre_sus'] = $row['monto_cierre_sus'];
            //     $array['total_tarjeta'] = isset($row['total_tarjeta'])? $row['total_tarjeta']: 0;
            //     $array['total_cheque'] = $row['total_cheque'];
            //     $array['total_venta'] = $row['total_venta'];
            //     $array['fecha_cierre'] = $row['fecha_cierre'];
            //     $data[] = $array;
            //  }
 
            //  $json_data = array(
            //      'draw' => $data_cash['draw'],
            //      'recordsTotal' => $data_cash['recordsTotal'],
            //      'recordsFiltered' => $data_cash['recordsFiltered'],
            //      'data' => $data,
            //  );
 
             echo json_encode($data_cash);

        } else {
            show_404();
        }
    }

    public function export_to_excel_caja()
    {
        $this->load->library("excel/PHPExcel");

        $list_data = $this->reporte_caja_model->buscar_caja_excel();

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();
        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1'); //Titulo
        // Cabecera de la tabla
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'REPORTE DE CIERRE DE CAJAS')
            //Encabezado de la tabla
            ->setCellValue('A3', 'Total:')
            ->setCellValue('A4', 'Cantidad de Cierre de Cajas:')

            ->setCellValue('A8', 'NRO')
            ->setCellValue('B8', 'FECHA')
            ->setCellValue('C8', 'SUCURSAL')
            ->setCellValue('D8', 'CAJA')
            ->setCellValue('E8', 'USUARIO')
            ->setCellValue('F8', 'CONTADO')
            ->setCellValue('G8', 'DEPOSITO')
            ->setCellValue('H8', 'TRANSFERENCIA')
            ->setCellValue('I8', 'TARJETA')
            ->setCellValue('J8', 'MONTO APERTURA')
            ->setCellValue('K8', 'MONTO INGRESOS')
            ->setCellValue('L8', 'MONTO EGRESOS')
            ->setCellValue('M8', 'TOTAL');
        //Negrita
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE)

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
            ->getActiveSheet()->getStyle('L8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('M8')->getFont()->setBold(TRUE);
        //Centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)

            ->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
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
            ->getActiveSheet()->getStyle('L8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('M8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //Pintar los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('A4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('A8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
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
            ->getActiveSheet()->getStyle('L8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('M8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $fila = 9; // Empieza a escribir desde la linea 9
        $nro = 1;
        $primera_fila = $fila;
        foreach ($list_data['tabla'] as $row) {
            //$fecha = date("d/m/Y", strtotime($row->fecha));
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $nro)
                ->setCellValue('B' . $fila, ''.$row->fecha_cierre)
                ->setCellValue('C' . $fila, ''.$row->sucursal)
                ->setCellValue('D' . $fila, ''.$row->descripcion)
                ->setCellValue('E' . $fila, ''.$row->usuario)
                ->setCellValue('F' . $fila, ''.$row->total_contado)
                ->setCellValue('G' . $fila, ''.$row->total_deposito)
                ->setCellValue('H' . $fila, ''.$row->total_cheque)
                ->setCellValue('I' . $fila, ''.$row->total_tarjeta)
                ->setCellValue('J' . $fila, ''.$row->monto_apertura)
                ->setCellValue('K' . $fila, ''.$row->total_ingreso)
                ->setCellValue('L' . $fila, ''.$row->total_egreso)
                ->setCellValue('M' . $fila, ''.$row->monto_cierre);
            //Pintar los bordes
            $objPHPExcel->getActiveSheet()->getStyle('A'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('B'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('C'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('D'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('E'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('F'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('G'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('H'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('I'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('J'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('K'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('L'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('M'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $nro++;
            $fila++;

        }

        $ultima_fila = $fila -1;
        $nro = $nro - 1;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B3', '=SUM(M'.$primera_fila.':M'.$ultima_fila.')')
            ->setCellValue('B4', $nro);

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
        $objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(TRUE);

        //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        //sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="Reporte cierre de caja' . "_" . date('d-m-Y') . '.xlsx"');

        //unduh file
        $objWriter->save("php://output");
    }

    public function export_to_excel_cash_close()
    {
        $array_cell=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        $array_header=array(
            "NRO",
            "FECHA",
            "SUCURSAL",
            "CAJA",
            "USUARIO",
            "MONTO APERTURA BS",
            "MONTO APERTURA Sus",
            "TOTAL INGRESO Bs",
            "TOTAL INGRESO Sus",
            "TOTAL TARJETA",
            "TOTAL TRANSFERENCIA",
            "TOTAL EGRESO Bs",
            "TOTAL EGRESO Sus",
            "MONTO CIERRE Bs",
            "MONTO CIERRE Sus",
            "TOTAL EFECTIVO",
            "TOTAL GENERAL",
        );

        $this->load->library("excel/PHPExcel");

        /*Parametros de busqueda sin datatable*/
        $report_office = $this->input->post('report_office');
        $report_cash = $this->input->post('report_cash');
        $report_user = $this->input->post('report_user');
        $report_start_date = $this->input->post('report_start_date');
        $report_end_date = $this->input->post('report_end_date');
        /*Array*/
        $params = array(
            'report_start_date' => $report_start_date,
            'report_end_date' => $report_end_date,
            'report_office' => $report_office,
            'report_cash' => $report_cash,
            'report_user' => $report_user
        );
        $data = $this->reporte_caja_model->buscar_caja($params);
        /*echo json_encode($params);
        exit();*/
        $branch_office = 'TODOS';
        $user = 'TODOS';
        $cash = 'TODOS';
        $start_date = 'TODOS';
        $end_date = 'TODOS';


        if ($report_start_date != '') {
            $start_date = $report_start_date;
        } else {
            
        }

        if ($report_end_date != '') {
            $end_date = $report_end_date;
        } else {
            
        }

        if ($report_office != 0) {
            $branch_office = $this->office_model->get_branch_office_id($report_office)->nombre;
        } else {
           
        }

        if ($report_user != 0) {
            $user = $this->user_model->get_user_id($report_user)->nombre;
        } else {
            
        }

        
        if ($report_cash != 0) {
            $cash = $this->cash_model->get_cash_id($report_cash)->nombre;
        } else {
            
        }

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');

        $objPHPExcel->getActiveSheet()->mergeCells('A4:B4');
        $objPHPExcel->getActiveSheet()->mergeCells('C4:D4');
        $objPHPExcel->getActiveSheet()->mergeCells('A5:B5');
        $objPHPExcel->getActiveSheet()->mergeCells('C5:D5');
        $objPHPExcel->getActiveSheet()->mergeCells('A6:B6');
        $objPHPExcel->getActiveSheet()->mergeCells('C6:D6');

        $objPHPExcel->getActiveSheet()->mergeCells('E4:F4');
        $objPHPExcel->getActiveSheet()->mergeCells('G4:H4');
        $objPHPExcel->getActiveSheet()->mergeCells('E5:F5');
        $objPHPExcel->getActiveSheet()->mergeCells('G5:H5');

        // Datos
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A2', 'REPORTE DE CIERRE DE CAJAS')
        ->setCellValue('A4', 'SUCURSAL')
        ->setCellValue('C4', $branch_office)
        ->setCellValue('A5', 'CAJA')
        ->setCellValue('C5', $cash)
        ->setCellValue('A6', 'USUARIO')
        ->setCellValue('C6', $user)
        ->setCellValue('E4', 'FECHA INICIO')
        ->setCellValue('G4', $start_date)
        ->setCellValue('E5', 'FECHA FIN')
        ->setCellValue('G5', $end_date);

        //Negrita
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('J4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('J5')->getFont()->setBold(TRUE);

        for ($i=0; $i < sizeof($array_header); $i++) { 
            //Encabezado de la tabla
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($array_cell[$i].'8', $array_header[$i]);
            //NEGRITA
            $objPHPExcel->getActiveSheet()->getStyle($array_cell[$i].'8')->getFont()->setBold(TRUE);
            //Centrar los titulos
            $objPHPExcel->getActiveSheet()->getStyle($array_cell[$i].'8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //Pintar los bordes
            $objPHPExcel->getActiveSheet()->getStyle($array_cell[$i].'8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }
        

        $fila = 9; // Empieza a escribir desde la linea 8
        $i = 1;
        foreach ($data['data'] as $row) {
         
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('B' . $fila, $row['fecha_cierre'])
                ->setCellValue('C' . $fila, $row['sucursal'])
                ->setCellValue('D' . $fila, $row['caja'])
                ->setCellValue('E' . $fila, $row['usuario'])
                ->setCellValue('F' . $fila, $row['monto_apertura_bs'])
                ->setCellValue('G' . $fila, $row['monto_apertura_sus'])
                ->setCellValue('H' . $fila, $row['total_ingreso_bs'])
                ->setCellValue('I' . $fila, $row['total_ingreso_sus'])
                ->setCellValue('J' . $fila, $row['total_tarjeta'])
                ->setCellValue('K' . $fila, $row['total_cheque'])
                ->setCellValue('L' . $fila, $row['total_egreso_bs'])
                ->setCellValue('M' . $fila, $row['total_egreso_sus'])
                ->setCellValue('N' . $fila, $row['monto_cierre_bs'])
                ->setCellValue('O' . $fila, $row['monto_cierre_sus'])
                ->setCellValue('P' . $fila, $row['total_efective'])
                ->setCellValue('Q' . $fila, $row['total_efective'] + $row['total_tarjeta'] + $row['total_cheque']);

            //Pintar los bordes
           
            for ($j=0; $j < sizeof($array_header); $j++) { 
                $objPHPExcel->getActiveSheet()->getStyle($array_cell[$j].$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            }

            for ($j=5; $j < sizeof($array_header); $j++) {
                $objPHPExcel->getActiveSheet()->getStyle($array_cell[$j] . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            }
            $fila = $fila + 1;
            $i++;
        }

        for ($j=0; $j < sizeof($array_header); $j++){ 
            $objPHPExcel->getActiveSheet()->getStyle($array_cell[$j] . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $fila, 'TOTALES');
        $objPHPExcel->getActiveSheet()->getStyle('E'. $fila)->getFont()->setBold(TRUE);
        
        for ($j=5; $j < sizeof($array_header); $j++){ 
            // $objPHPExcel->getActiveSheet()->getStyle($array_cell[$j] . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($array_cell[$j].$fila,'=SUM('.$array_cell[$j].'9:'.$array_cell[$j].($fila-1).')'); 
            $objPHPExcel->getActiveSheet()->getStyle($array_cell[$j] . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        }
        

        // Establece anchura automatico a la celda
        for ($i=0; $i < sizeof($array_header); $i++) { 
            $objPHPExcel->getActiveSheet()->getColumnDimension($array_cell[$i])->setAutoSize(TRUE);
        }
       

        //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        //sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="Reporte cierre de caja' . "_" . date('d-m-Y') . '.xlsx"');

        //unduh file
        $objWriter->save("php://output");

    }
}
