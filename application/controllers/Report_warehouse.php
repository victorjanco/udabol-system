<?php
/**
 * Created by PhpStorm.
 * User: Workcorp
 * Date: 23/04/2018
 * Time: 02:10 PM
 */
ini_set('max_execution_time', 0);
ini_set('memory_limit','2048M');
class Report_warehouse extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('brand_model');
        $this->load->model('report_warehouse_model');
        $this->load->model('inventory_model');
    }

    /* Inicia la vista de reporte por Almacen*/
    public function report_for_warehouse()
    {
        $data["list_warehouse"] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
        $data["list_brand"] = $this->brand_model->get_brand_enable();
        $data["list_model"] = $this->model_model->get_model_enable();
        $data["list_product"] = $this->product_model->get_product_enable();
        template('report/report_for_warehouse', $data);
    }

    /*Retorna almacen de la Vista de BD*/
    public function get_report_warehouse_list()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            /*Parametros de busqueda sin datatable*/
            $report_warehouse = $this->input->post('report_warehouse');
            $report_brand = $this->input->post('report_brand');
            $report_model = $this->input->post('report_model');
            $report_product = $this->input->post('report_product');

            // Se almacenan los parametros recibidos en un array para enviar al model
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'report_warehouse' => $report_warehouse,
                'report_brand' => $report_brand,
                'report_model' => $report_model,
                'report_product' => $report_product
            );
            $data_warehouse = $this->report_warehouse_model->get_data_report_warehouse($params);

            echo json_encode($data_warehouse);
        } else {
            show_404();
        }
    }

    /*Reporte por Almacen*/
    public function export_to_excel_warehouse()
    {
        $this->load->library("excel/PHPExcel");

        /*Parametros*/
        $report_warehouse = $this->input->post('report_warehouse');
        $report_brand = $this->input->post('report_brand');
        $report_model = $this->input->post('report_model');
        $report_product = $this->input->post('report_product');

        /*Array*/
        $params = array(
            'report_warehouse' => $report_warehouse,
            'report_brand' => $report_brand,
            'report_model' => $report_model,
            'report_product' => $report_product
        );

        $data = $this->report_warehouse_model->get_data_report_warehouse($params);
        $list_data = $data['data'];
        if ($report_warehouse != 0) {
            $warehouse = $this->warehouse_model->get_warehouse_id($report_warehouse)->nombre;
        } else {
            $warehouse = 'Todos';
        }
        if ($report_brand != 0) {
            $brand = $this->brand_model->get_brand_id($report_brand)->nombre;
        } else {
            $brand = 'Todos';
        }
        if ($report_model != 0) {
            $model = $this->model_model->get_model_id($report_model)->nombre;
        } else {
            $model = 'Todos';
        }
        if ($report_product != 0) {
            $product = $this->product_model->get_product_by_id($report_product)->nombre_comercial;
            $product_code = $this->product_model->get_product_by_id($report_product)->codigo;
        } else {
            $product = 'Todos';
        }

        $branch_office = get_branch_office_name_in_session();

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2'); //Titulo
        $objPHPExcel->getActiveSheet()->mergeCells('B4:C4'); //Almacen
        $objPHPExcel->getActiveSheet()->mergeCells('F4:G4'); //Marca
        $objPHPExcel->getActiveSheet()->mergeCells('B5:C5'); //Modelo
        $objPHPExcel->getActiveSheet()->mergeCells('F5:G5'); //Producto
        $objPHPExcel->getActiveSheet()->mergeCells('B6:C6'); //SUCURSAL

        // Datos
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'REPORTE POR ALMACEN')
            ->setCellValue('A4', 'ALMACEN: ')
            ->setCellValue('B4', $warehouse)
            ->setCellValue('E4', 'MARCA: ')
            ->setCellValue('F4', $brand)
            ->setCellValue('A5', 'SUCURSAL: ')
            ->setCellValue('B5', $branch_office)
            ->setCellValue('E5', 'PRODUCTO: ')
            ->setCellValue('F5', $product)
            ->setCellValue('A6', 'N. GENERICO:  ')
            ->setCellValue('B6', $product)
            //Encabezado de la tabla
            ->setCellValue('A8', 'NRO.')
            ->setCellValue('B8', 'ALMACEN')
            ->setCellValue('C8', 'MARCA')
            ->setCellValue('D8', 'PRODUCTO')
            ->setCellValue('E8', 'NOMBRE GENERICO')
            //->setCellValue('F8', 'CODIGO MODELO')
            //->setCellValue('G8', 'MODELO')
            ->setCellValue('F8', 'CANTIDAD')
            ->setCellValue('G8', 'PRECIO COSTO')
            ->setCellValue('H8', 'TOTAL');

        //Negrita
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('B8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('C8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('H8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE);

        //Centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('F8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('G8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('H8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //Pintar los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('C8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('D8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('E8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('F8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('G8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('H8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $fila = 9; // Empieza a escribir desde la linea 9
        $i = 1;
        $primera_fila=$fila;
        foreach ($list_data as $row) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('B' . $fila, $row['nombre_almacen'])
                ->setCellValue('C' . $fila, $row['nombre_marca'])
                ->setCellValue('D' . $fila, $row['nombre_comercial'])
                ->setCellValue('E' . $fila, $row['nombre_generico'])
                //->setCellValue('F' . $fila, $row['codigo_modelo'])
                //->setCellValue('G' . $fila, $row['nombre_modelo'])
                ->setCellValue('F' . $fila, $row['stock'])
                ->setCellValue('G' . $fila, $row['precio_costo'])
                ->setCellValue('H' . $fila, $row['total']);;

            //Pintar los bordes
            $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('B' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('C' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('D' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('E' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('F' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('G' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('H' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $fila = $fila + 1;
            $i++;
        }

        $ultima_fila = $fila -1;
        $nro = $i - 1;

        $objPHPExcel->getActiveSheet()->getStyle('G' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        // $objPHPExcel->getActiveSheet()->getStyle('R' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $fila, 'TOTALES');
        $objPHPExcel->getActiveSheet()->getStyle('G'. $fila)->getFont()->setBold(TRUE);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$fila, '=SUM(H'.$primera_fila.':H'.$ultima_fila.')');
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$fila, '=SUM(R'.$primera_fila.':R'.$ultima_fila.')');
        $objPHPExcel->getActiveSheet()->getStyle('H' . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        // $objPHPExcel->getActiveSheet()->getStyle('R' . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        // Establece anchura automatico a la celda
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(TRUE);

        //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        //sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="Reporte por Almacen' . "_" . date('d-m-Y') . "_" . '.xlsx"');

        //unduh file
        $objWriter->save("php://output");

    }


}
