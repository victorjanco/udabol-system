<?php
/**
 * Created by code.
 * User: JANCO
 * Date: 20/10/2023
 * Time: 09:40 AM
 */
ini_set('max_execution_time', 0);
ini_set('memory_limit','2048M');
class Report_provider extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('office_model');
        $this->load->model('report_provider_model');
        $this->load->model('warehouse_model');
    }

    /* Inicia la vista de reporte por Producto*/
    public function index()
    {
        $data["list_product"] = $this->product_model->get_product_enable();
        $data["list_warehouse"] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
        $data['list_branch_office'] = $this->office_model->get_offices();
        template('report/report_provider', $data);
    }
    /*Para cargar la lista de tipo de almacen en el dataTable*/
    public function get_report_provider_list()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $branch_office_report = $this->input->post('branch_office_report');
            $warehouse_report = $this->input->post('warehouse_report');
            $report_start_date = $this->input->post('report_start_date');
            $report_end_date = $this->input->post('report_end_date');

            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'branch_office_report' => $branch_office_report,
                'warehouse_report' => $warehouse_report,
                'report_start_date' => $report_start_date,
                'report_end_date' => $report_end_date
            );

            echo json_encode($this->report_provider_model->get_report_provider_list($params));
        } else {
            show_404();
        }
    }


    

    public function export_to_excel_provider()
    {

        // Parametros
        $branch_office_report = $this->input->post('branch_office_report');
        $warehouse_report = $this->input->post('warehouse_report');
        $report_start_date = $this->input->post('report_start_date');
        $report_end_date = $this->input->post('report_end_date');

        // Array
        $params = array(
            'branch_office_report' => $branch_office_report,
            'warehouse_report' => $warehouse_report,
            'report_start_date' => $report_start_date,
            'report_end_date' => $report_end_date
        );

        // $product = $this->product_model->get_all_products_type_product();

        $data = $this->report_provider_model->get_report_provider_list($params);
        $list_data = $data['data'];

        $array_cell=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        $array_header=array(
            "ID",
            "PROVEEDOR",
            "DIRECCION",
            "TELEDONO",
            "CODIGO",
            "PRODUCTO",
            "STOCK"
        );

        $this->load->library("excel/PHPExcel");

        

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();
        
        //Encabezado de la tabla
        for ($i=0; $i < sizeof($array_header); $i++) { 
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($array_cell[$i].'1', $array_header[$i]);
        }

        for ($i=0; $i < sizeof($array_header); $i++) { 
            //Negrita
            $objPHPExcel->getActiveSheet()->getStyle($array_cell[$i].'1')->getFont()->setBold(TRUE);
            //Centrar los titulos
            $objPHPExcel->getActiveSheet()->getStyle($array_cell[$i].'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //Pintar los bordes
            $objPHPExcel->getActiveSheet()->getStyle($array_cell[$i].'1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }

        $fila = 2; // Empieza a escribir desde la linea 2
        $i = 1;
        foreach ($list_data as $row) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('B' . $fila, $row['nombre_proveedor'])
                ->setCellValue('C' . $fila, $row['direccion'])
                ->setCellValue('D' . $fila, $row['telefono'])
                ->setCellValue('E' . $fila, 'CODIGO-'.$row['id'])
                ->setCellValue('F' . $fila, $row['nombre_comercial'])
                ->setCellValue('G' . $fila, $row['stock']);

            for ($j=0; $j < sizeof($array_header); $j++) { 
                $objPHPExcel->getActiveSheet()->getStyle($array_cell[$j].$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            }
            $fila = $fila + 1;
            $i++;
        }

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
        header('Content-Disposition: attachment;filename="Lista_Productos_' . date('d-m-Y') . "_" . '.xlsx"');

        //unduh file
        $objWriter->save("php://output");
    }
}