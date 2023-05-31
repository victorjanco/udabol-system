<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 20/07/2017
 * Time: 04:45 PM
 */

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');

class Product extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('proveider_model');
        $this->load->model('provider_model');
        $this->load->model('model_model');
        $this->load->model('group_model');
        //$this->load->model('category_model');
        $this->load->model('unit_model');
        $this->load->model('brand_model');
        $this->load->model('serie_model');
        $this->load->model('color_model');
        $this->load->model('inventory_model');
        $this->load->model('inventory_branch_office_model');
    }

    public function index()
    {
        template('product/index');
    }
    public function product_inactive()
    {
        template('product/product_inactive');
    }
    public function generar_codigo_barra(){
        return $this->product_model->generar_codigo_ean($this->product_model->get_ultimo_producto_id() + 1);
    }
    public function generar_codigo()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->generar_codigo_barra());
        } else {
            show_404();
        }
    }
    public function imprimir_codigo()
    {
        // if (!logueado()) {
        //     redirect(site_url('login'));
        // }
        $producto_id = $this->uri->segment(3);
        $inventario_sucursal_id = $this->uri->segment(3);

        $inventario_sucursal = $this->inventory_branch_office_model->find($inventario_sucursal_id);

        $codigo['inventario_sucursal'] = $inventario_sucursal;
        $codigo['producto'] = $this->db->get_where('producto', array('id' => $inventario_sucursal->producto_id))->row();
        $codigo['tipo'] = 1;
        $this->load->view('product/imprimir_codigo', $codigo);
    }
    public function imprimir_codigos()
    {
        // $this->db->select("p.id, p.codigo_barra, p.nombre_item, p.precio_venta, t.descripcion as talla, c.descripcion as color")
        //     ->from("talla t, color c, producto p")
        //     ->where("t.id = p.talla_id")
        //     ->where("p.estado", 1)
        //     ->where("c.id = p.color_id");
        // $codigo['codigos'] = $this->db->get()->result();
        $codigo['productos']=$this->product_model->get_product_enable();
        $codigo['tipo'] = 2;
        $this->load->view('product/imprimir_codigo', $codigo);
    }
    public function new_product()
    {
        $data['groups'] = $this->group_model->get_groups();
        $data['proveiders'] = $this->proveider_model->get_proveiders();
        $data['units'] = $this->unit_model->get_units();
        $data['brands'] = $this->brand_model->get_brand_enable();
        $data['series'] = $this->serie_model->get_all_serie();
        $data['codigo_barra'] = $this->generar_codigo_barra();
        $data['new'] = 'new';
        template('product/new_product', $data);
    }

    public function edit()
    {
        $id_product = $this->input->post('id');
        $data_product['product'] = $this->product_model->get_product_by_id($id_product);
       
        $data_product['proveiders'] = $this->proveider_model->get_proveiders();
        $data_product['units'] = $this->unit_model->get_units();
        $data_product['brands'] = $this->brand_model->get_brand_enable();
        $data_product['models'] = $this->model_model->get_model_by_brand_id($data_product['product']->marca_id);
        $data_product['series'] = $this->serie_model->get_all_serie();
        $data_product['group_selected'] = $this->group_model->get_father_group($data_product['product']->subgrupo_id);/* Recuperamos el id del grupo. para poder seleccionarlo*/
        // $data_product['groups'] = $this->group_model->get_father_subgroup($data_product['product']->subgrupo_id);
        $data_product['groups'] = $this->group_model->get_groups();
        $data_product['subgroups'] = $this->group_model->get_subgroups($data_product['group_selected']->grupo_padre_id);
        $data_product['product_providers'] = $this->provider_model->get_providers_product_by_product($id_product);
        template('product/edit_product', $data_product);
    }

    public function view()
    {
        $id_product = $this->input->post('id');
        $data_product['product'] = $this->product_model->get_product_by_id($id_product);
        
        $data_product['proveiders'] = $this->proveider_model->get_proveiders();
        $data_product['units'] = $this->unit_model->get_units();
        $data_product['brands'] = $this->brand_model->get_brand_enable();
        $data_product['models'] = $this->model_model->get_model_by_brand_id($data_product['product']->marca_id);
        $data_product['series'] = $this->serie_model->get_all_serie();
        $data_product['group_selected'] = $this->group_model->get_father_group($data_product['product']->subgrupo_id);/* Recuperamos el id del grupo. para poder seleccionarlo*/
        $data_product['groups'] = $this->group_model->get_father_subgroup($data_product['product']->subgrupo_id);
        $data_product['subgroups'] = $this->group_model->get_subgroups($data_product['group_selected']->grupo_padre_id);
        $data_product['product_providers'] = $this->provider_model->get_providers_product_by_product($id_product);
        $data_product['view'] = 'view';/* para poder ver los datos*/
        $data_product['codigo_barra'] ='';
        template('product/edit_product', $data_product);
    }

    public function import_price_products()
    {
        $this->load->model('warehouse_model');
        $data["list_warehouse"] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());

        template('product/import_price_products', $data);
    }

    public function import_products()
    {
        $this->load->model('warehouse_model');
        $data["list_warehouse"] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());

        template('product/import_product', $data);
    }
    public function import_stock_products()
    {
        $this->load->model('warehouse_model');
        $data["list_warehouse"] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());

        template('product/import_stock_products', $data);
    }
    public function delete_stock_products()
    {
        $this->load->model('warehouse_model');
        $data["list_warehouse"] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());

        template('product/delete_stock_products', $data);
    }
    public function register_product()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->product_model->register_product());
        } else {
            show_404();
        }
    }

    public function modify_product()
    {
        if ($this->input->is_ajax_request()) {
            $id_product = $this->input->post('id');
            echo json_encode($this->product_model->modify_product($id_product));
        } else {
            show_404();
        }
    }

    public function delete()
    {
        if ($this->input->is_ajax_request()) {
            $id_product = $this->input->post('id');
            echo json_encode($this->product_model->delete($id_product));
        } else {
            show_404();
        }
    }
    public function activate_product()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->product_model->activate_product();
        }
        else
        {
            show_404();
        }
    }
    public function delete_product()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->product_model->delete_product();
        }
        else
        {
            show_404();
        }
    }
    public function get_products_list()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $length = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            $data_product = $this->product_model->get_products_list($start, $length, $search, $column, $order);

            $result = $data_product['data_products'];
            $total_data = $data_product['total_register'];

            $data = array();
            foreach ($result->result_array() as $row) {
                $array = array();
                $array['id'] = $row['id'];
                $array['codigo'] = $row['codigo'];
                $array['nombre_comercial'] = $row['nombre_comercial'];
                $array['nombre_generico'] = $row['nombre_generico'];
                $array['dimension'] = $row['dimension'];
                $array['precio_venta'] = number_format($row['precio_venta'], CANTIDAD_MONTO_DECIMAL);
                $array['modelo'] = $row['modelo'];
                $array['grupo'] = $row['grupo'];
                $array['subgrupo'] = $row['subgrupo'];
                $array['marca'] = $row['marca'];
                $array['estado'] = $row['estado'];
                $data[] = $array;
            }

            $count_products = $result->num_rows();

            $json_data = array(
                'draw' => intval($this->input->post('draw')),
                'recordsTotal' => intval($count_products),
                'recordsFiltered' => intval($total_data),
                'data' => $data,
            );

            echo json_encode($json_data);
        } else {
            show_404();
        }
    }

    public function get_product_inactive_list()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $length = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            $data_product = $this->product_model->get_products_inactive_list($start, $length, $search, $column, $order);

            $result = $data_product['data_products'];
            $total_data = $data_product['total_register'];

            $data = array();
            foreach ($result->result_array() as $row) {
                $array = array();
                $array['id'] = $row['id'];
                $array['codigo'] = $row['codigo'];
                $array['nombre_comercial'] = $row['nombre_comercial'];
                $array['nombre_generico'] = $row['nombre_generico'];
                $array['dimension'] = $row['dimension'];
                $array['precio_venta'] = number_format($row['precio_venta'], CANTIDAD_MONTO_DECIMAL);
                $array['modelo'] = $row['modelo'];
                $array['grupo'] = $row['grupo'];
                $array['subgrupo'] = $row['subgrupo'];
                $array['marca'] = $row['marca'];
                $array['estado'] = $row['estado'];
                $data[] = $array;
            }

            $count_products = $result->num_rows();

            $json_data = array(
                'draw' => intval($this->input->post('draw')),
                'recordsTotal' => intval($count_products),
                'recordsFiltered' => intval($total_data),
                'data' => $data,
            );

            echo json_encode($json_data);
        } else {
            show_404();
        }
    }

    public function get_product_by_id()
    {
        if ($this->input->is_ajax_request()) {
            $id_product = $this->input->post('id');
            echo json_encode($this->product_model->get_product_by_id($id_product));
        } else {
            show_404();
        }
    }

    /*
     * Metodo llamado
     * */
    public function get_producto_and_model_by_brand()
    {
        $id_brand = $this->input->post('id');
        echo json_encode($this->product_model->get_producto_and_model_by_brand($id_brand));
    }


    // Obtener producto por autocompletado
    public function get_code_name_product_autocomplete()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->product_model->get_code_name_product_autocomplete());
        } else {
            show_404();
        }
    }

    public function search_product()
    {
        $search = $this->input->post_get('search');
        echo json_encode($this->product_model->search_product($search));
    }

    public function get_model_by_model()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->product_model->get_model_by_model());
        } else {
            show_404();
        }
    }

    public function register_gallery_product()
    {
        if ($_POST) {
            $product_id = $this->input->post('id');
            $data['product'] = $this->product_model->get_product_by_id($product_id);
            $data['marca'] = $this->product_model->get_marca_by_id($data['product']->marca_id);
            template('product/gallery_product', $data);
        } else {
            redirect('product/index');
        }
    }

    public function take_product_picture_from_webcam()
    {
        /* Obtenemos parametros */
        $product_id = $this->input->post('product_id');
        $codigo = $this->input->post('codigo');
        $coded_image = $this->input->post('image');

        if (strlen($coded_image) <= 0)
            exit("No se recibió ninguna imagen");

        $clean_coded_image = str_replace("data:image/png;base64,", "",
            urldecode($coded_image));                                                       //La imagen traerá al inicio de la imagen codificada " data:image/png;base64 ", debemos remover
        $decoded_image = base64_decode($clean_coded_image);                                 // Lo decodificamos la imagen
        $image_name = $product_id . "_" . $codigo . "_" . uniqid() . ".png";      // Ponemos un nombre unico a la imagen
        $path = DIRECTORY_RAIZ_PATH_PRODUCT . $product_id . '/' . $image_name;                    // La ubicacion donde estara almacenada la imagen

        file_put_contents($path, $decoded_image);                                            // Escribir el archivo en la direccion

        $this->product_model->register_image_product($product_id, $image_name);       // Registramos en la base de datos
    }

    public function get_images_product()
    {
        if ($this->input->is_ajax_request()) {
            $product_id = $this->input->post('product_id');
            echo $this->product_model->get_images_product($product_id);
        } else {
            show_404();
        }
    }

    public function delete_image_product()
    {
        if ($this->input->is_ajax_request()) {
            $image_id = $this->input->post('image_id');
            echo $this->product_model->delete_image_product($image_id);
        } else {
            show_404();
        }
    }

    public function add_gallery_product()
    {
        if (is_array($_FILES)) {
            $product_id = $this->input->post('id_product');
            $codigo = $this->input->post('codigo');
            foreach ($_FILES['files']['name'] as $name => $value) {
                $file_name = explode(".", $_FILES['files']['name'][$name]);
                $allowed_ext = array("jpg", "jpeg", "png", "gif");
                if (in_array($file_name[1], $allowed_ext)) {
                    $new_name = $product_id . "_" . $codigo . "_" . uniqid() . "." . $file_name[1];
                    $sourcePath = $_FILES['files']['tmp_name'][$name];
                    $targetPath = DIRECTORY_RAIZ_PATH_PRODUCT . $product_id . '/' . $new_name;
                    if (move_uploaded_file($sourcePath, $targetPath)) {
                        $this->product_model->register_image_product($product_id, $new_name);
                    }
                }
            }
            echo true;
        } else {
            echo false;
        }
    }

    public function create_directories_products()
    {
        $this->db->select('*')
            ->from('producto')
            ->where('estado', ACTIVO);
        $result = $this->db->get()->result();

        foreach ($result as $producto) {
            $directory = DIRECTORY_RAIZ_PATH_PRODUCT;

            $file_directory = DIRECTORY_RAIZ_PATH_PRODUCT . $producto->id;

            //verificamos si la carpeta galeria_recepcion se creo
            if (!is_dir($directory)) {
                mkdir($directory, 0777);
            }

            //verificamos si el directorio ya existe
            if (!is_dir($file_directory)) {
                mkdir($file_directory, 0777);
            }
        }
    }


    public function create_product()
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

    public function export_to_excel_product()
    {
        $array_cell=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        $array_header=array(
            "NRO",
            "CODIGO SISTEMA",
            "CODIGO",
            "NOMBRE COMERCIAL",
            "NOMBRE GENERICO",
            "COLOR",
            "MARCA",
            "MODELO",
            "GRUPO",
            "SUBGRUPO",
            "UNIDAD MEDIDA",
            "PRECIO COSTO",
            "PRECIO VENTA",
            "PRECIO VENTA MAYORISTA",
            "STOCK MINIMO"
        );

        $this->load->library("excel/PHPExcel");

        $product = $this->product_model->get_all_products_type_product();

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
        foreach ($product as $row) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('B' . $fila, 'COD-SIS-UNIQ-' . $row->producto_id)
                ->setCellValue('C' . $fila, $row->codigo_producto)
                ->setCellValue('D' . $fila, $row->nombre_comercial)
                ->setCellValue('E' . $fila, $row->nombre_generico)
                ->setCellValue('F' . $fila, $row->dimension)
                ->setCellValue('G' . $fila, $row->nombre_marca)
                // ->setCellValue('H' . $fila, $row->codigo_modelo)
                ->setCellValue('H' . $fila, $row->nombre_modelo)
                ->setCellValue('I' . $fila, $row->nombre_grupo)
                ->setCellValue('J' . $fila, $row->nombre_grupo)
                ->setCellValue('K' . $fila, $row->nombre_unidad_medida)
                ->setCellValue('L' . $fila, $row->precio_compra)
                ->setCellValue('M' . $fila, $row->precio_venta)
                ->setCellValue('N' . $fila, $row->precio_venta_mayor)
                // ->setCellValue('P' . $fila, $row->precio_venta_express)
                // ->setCellValue('Q' . $fila, $row->precio_venta_laboratorio)
                // ->setCellValue('R' . $fila, $row->porcentaje_precioventa_laboratorio)
                ->setCellValue('O' . $fila, $row->stock_minimo);

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

    public function product_reason()
    {
        $data['list_branch_office'] = $this->office_model->get_offices();
        $data['tipo_motivo'] = $this->product_model->get_type_reason(TIPO_MOTIVO_PRODUCTO);
        template('product/product_reason', $data);
    }
    public function product_reason_inactive()
    {
        $data['tipo_motivo'] = $this->product_model->get_type_reason(TIPO_MOTIVO_PRODUCTO);
        template('product/product_reason_inactive', $data);
    }

    public function register_product_reason()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->product_model->register_product_reason());
        } else {
            show_404();
        }
    }

    function save_import_products()
    {
        // Manipulacion del archivo excel
        $file = $_FILES['excel']['name'];
        $warehouse_id = $this->input->post('warehouse');

        if (!is_dir("./excel_files"))
            mkdir("./excel_files", 0777);

        if ($file && copy($_FILES['excel']['tmp_name'], "./excel_files/" . $file)) {
            require_once APPPATH . 'libraries/excel/PHPExcel.php';
            require_once APPPATH . 'libraries/excel/PHPExcel/Reader/Excel2007.php';

            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("./excel_files/" . $file);

            // Número de filas del archivo excel
            $filas = $objPHPExcel->getActiveSheet()->getHighestRow();

            $data_file = array();

            $this->db->trans_begin();

            for ($i = 5; $i <= $filas; $i++) {

                $product_id = str_replace("COD-SIS-UNIQ-", "", $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue());

                if ($this->product_model->exists_product($product_id) == 1) {

                    // Actualizar producto
                    /*$data_product = array(
                        'precio_compra' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("L" . $i)->getValue(),2, '.', ''),
                        'precio_ponderado' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("L" . $i)->getValue(), 2,'.', ''),
                    );
                    $this->db->where('id', $product_id);
                    $this->db->update('producto', $data_product);*/

                    // Registra o Actualizar precios de Inventario Sucursal
                    $data_branch_office_inventory = array(
                        'nombre_comercial' => strval($objPHPExcel->getActiveSheet()->getCell("D" . $i)->getValue()),
                        'nombre_generico' => strval($objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue()),
                        'precio_compra' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("L" . $i)->getValue(), 2, '.', ''),
                        'precio_costo' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("L" . $i)->getValue(), 2, '.', ''),
                        'precio_costo_poderado' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("L" . $i)->getValue(), 2, '.', ''),
                        'precio_venta' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("M" . $i)->getValue(), 2, '.', ''),
                        'precio_venta_mayor' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("N" . $i)->getValue(), 2, '.', ''),
                        'precio_venta_express' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("O" . $i)->getValue(), 2, '.', ''),
                        'precio_venta_laboratorio' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("P" . $i)->getValue(), 2, '.', ''),
                        'fecha_modificacion' => date('Y-m-d H:i:s'),
                        'usuario_id' => get_user_id_in_session(),
                        'sucursal_registro_id' => get_branch_id_in_session(),
                        'producto_id' => $product_id,
                        'warehouse_id' => $warehouse_id
                    );

                    $this->product_model->register_update_branch_office_inventory($data_branch_office_inventory);

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

    function save_import_price_products_warehouse()
    {
        // Manipulacion del archivo excel
        $file = $_FILES['excel']['name'];

        // Almacen
        $warehouse_id = $this->input->post('warehouse');

        if (!is_dir("./excel_files"))
            mkdir("./excel_files", 0777);

        if ($file && copy($_FILES['excel']['tmp_name'], "./excel_files/" . $file)) {
            require_once APPPATH . 'libraries/excel/PHPExcel.php';
            require_once APPPATH . 'libraries/excel/PHPExcel/Reader/Excel2007.php';

            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("./excel_files/" . $file);

            // Número de filas del archivo excel
            $filas = $objPHPExcel->getActiveSheet()->getHighestRow();
            $NRO_COLUMNA_EXCEL = 2;

            $this->db->trans_begin();

            for ($i = $NRO_COLUMNA_EXCEL; $i <= $filas; $i++) {

                $product_id = str_replace("COD-SIS-UNIQ-", "", $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue());

                if ($this->product_model->exists_product($product_id) == 1) {

                    // Datos dell EXCEL
                    $data_branch_office_inventory = array(
                        'nombre_comercial' => strval($objPHPExcel->getActiveSheet()->getCell("D" . $i)->getValue()),
                        'nombre_generico' => strval($objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue()),
                        'precio_compra' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("M" . $i)->getValue(), 2, '.', ''),
                        'precio_costo' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("M" . $i)->getValue(), 2, '.', ''),
                        'precio_costo_poderado' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("M" . $i)->getValue(), 2, '.', ''),
                        'precio_venta' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("N" . $i)->getValue(), 2, '.', ''),
                        'precio_venta_mayor' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("O" . $i)->getValue(), 2, '.', ''),
                        'precio_venta_express' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("P" . $i)->getValue(), 2, '.', ''),
                        'precio_venta_laboratorio' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("Q" . $i)->getValue(), 2, '.', ''),
                        'porcentaje_precioventa_laboratorio' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("R" . $i)->getValue(), 2, '.', ''),
                        'fecha_modificacion' => date('Y-m-d H:i:s'),
                        'usuario_id' => get_user_id_in_session(),
                        'sucursal_registro_id' => get_branch_id_in_session(),
                        'producto_id' => $product_id,
                        'warehouse_id' => $warehouse_id
                    );

                    $this->product_model->register_update_branch_office_inventory_by_warehouse($data_branch_office_inventory);

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

    function save_import_products_warehouse()
    {
        // Manipulacion del archivo excel
        $file = $_FILES['excel']['name'];

        // Almacen
        $warehouse_id = $this->input->post('warehouse');

        if (!is_dir("./excel_files"))
            mkdir("./excel_files", 0777);

        if ($file && copy($_FILES['excel']['tmp_name'], "./excel_files/" . $file)) {
            require_once APPPATH . 'libraries/excel/PHPExcel.php';
            require_once APPPATH . 'libraries/excel/PHPExcel/Reader/Excel2007.php';

            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("./excel_files/" . $file);

            // Número de filas del archivo excel
            $filas = $objPHPExcel->getActiveSheet()->getHighestRow();
            $FILA = 2;
            $sin_nombre="S-N";

            $this->db->trans_begin();

            $obj_ingreso_inventario = [];
            $obj_ingreso_inventario["nombre"] = 'INGRESO MASIVO A INVENTARIO';
            $obj_ingreso_inventario["fecha_ingreso"] = date('Y-m-d');
            $obj_ingreso_inventario["fecha_registro"] = date('Y-m-d H:i:s');
            $obj_ingreso_inventario["fecha_modificacion"] = date('Y-m-d H:i:s');
            $obj_ingreso_inventario["estado"] = ACTIVO;
            $obj_ingreso_inventario["tipo_ingreso_inventario_id"] = 1; //Ingreso Habitual
            $obj_ingreso_inventario["sucursal_id"] = get_branch_id_in_session();
            $obj_ingreso_inventario["usuario_id"] = get_user_id_in_session();
            $obj_ingreso_inventario["nro_ingreso_inventario"] = $this->inventory_model->last_number_inventory_entry();
            $obj_ingreso_inventario["nro_comprobante"] = 'ING:000';

            $this->inventory_model->_insert_inventory_entry($obj_ingreso_inventario);
			$inventory_entry_inserted = $this->inventory_model->_get_inventory_entry($obj_ingreso_inventario);

            for ($i = $FILA; $i <= $filas; $i++) {

                // $code_product = trim($objPHPExcel->getActiveSheet()->getCell("C" . $i)->getValue());
                // $code_product = ($code_product!='')? $code_product: strval($this->generar_codigo_barra());
                $code_product =  strval($this->generar_codigo_barra());
                    // $product_id = str_replace("COD-SIS-UNIQ-", "", $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue());
                $nro = trim($objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue());    
                if ($nro != '') {
                // if ($code_product != '') {
                //     if ($this->product_model->exists_code_product($code_product) == 0) {
                        $cantidad_stock = strtoupper(trim($objPHPExcel->getActiveSheet()->getCell("D" . $i)->getValue()));

                        $nametrade = strtoupper(trim($objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue()));
                        $generic_name = strtoupper(trim($objPHPExcel->getActiveSheet()->getCell("F" . $i)->getValue()));

                        // Color
                        $color_name = strtoupper(trim($objPHPExcel->getActiveSheet()->getCell("G" . $i)->getValue()));
                        $color_name = ($color_name!='')? $color_name: $sin_nombre;
                        if ($color_name != '') {
                            if ($this->color_model->exists_color_name($color_name) == 1) {
                                $color_id = $this->color_model->get_color_by_name($color_name)->id;
                            } else {
                                $data_color = array(
                                    'nombre' => $color_name,
                                    'descripcion' => $color_name,
                                    'estado' => ACTIVO
                                );
                                $this->db->insert('color', $data_color);
                                $color_id = $this->color_model->get_color($data_color)->id;
                            }
                        } else {
                            $color_name = '';
                            $color_id = '';
                        }

                        // Marca
                        $brand_name = strtoupper(trim($objPHPExcel->getActiveSheet()->getCell("H" . $i)->getValue()));
                        $brand_name = ($brand_name!='')? $brand_name: "S-M";
                        if ($brand_name != '') {
                            if ($this->brand_model->exists_brand_name($brand_name) == 1) {
                                $brand_id = $this->brand_model->get_brand_by_name($brand_name)->id;
                            } else {
                                $data_brand = array(
                                    'nombre' => $brand_name,
                                    'codigo' => $brand_name,
                                    'estado' => ACTIVO
                                );
                                $this->db->insert('marca', $data_brand);
                                $brand_id = $this->brand_model->get_brand($data_brand)->id;
                            }
                        } else {
                            unlink("./excel_files/" . $file);
                            echo '2';
                            return;
                        }

                        // Modelo
                        $code_model = trim($objPHPExcel->getActiveSheet()->getCell("I" . $i)->getValue());
                        $code_model = ($code_model!='')? $code_model: $sin_nombre;
                        $model_name = strtoupper(trim($objPHPExcel->getActiveSheet()->getCell("I" . $i)->getValue()));
                        $model_name = ($model_name!='')? $model_name: $sin_nombre;
                        if ($model_name != ''){
                            // if ($this->model_model->exists_code_model($code_model, $brand_id) == 1) {
                            if ($this->model_model->exists_name_model($model_name, $brand_id) == 1) {
                                $model_id = $this->model_model->get_model_by_name_and_brand_id($model_name, $brand_id)->id;
                            } else {
                                $data_model = array(
                                    'codigo' => $code_model,
                                    'nombre' => $model_name,
                                    'estado' => ACTIVO,
                                    'marca_id' => $brand_id
                                );
                                $this->db->insert('modelo', $data_model);
                                $model_id = $this->model_model->get_model($data_model)->id;
                            }
                        }else{
                            unlink("./excel_files/" . $file);
                            echo '3';
                            return;
                        }

                        // Grupo
                        $group_name = strtoupper(trim($objPHPExcel->getActiveSheet()->getCell("J" . $i)->getValue()));
                        $group_name = ($group_name!='')? $group_name: $sin_nombre;
                        if ($group_name != ''){
                            if ($this->group_model->exists_group_name($group_name) == 1) {
                                $group_id = $this->group_model->get_group_by_name($group_name)->id;
                            } else {
                                $data_group = array(
                                    'nombre' => $group_name,
                                    'descripcion' => $group_name,
                                    'estado' => ACTIVO
                                );
                                $this->db->insert('grupo', $data_group);
                                $group_id = $this->group_model->get_group($data_group)->id;
                            }
                        }else{
                            unlink("./excel_files/" . $file);
                            echo '4';
                            return;
                        }

                        // Subgrupo
                        $subgroup_name = strtoupper(trim($objPHPExcel->getActiveSheet()->getCell("K" . $i)->getValue()));
                        $subgroup_name = ($subgroup_name!='')? $subgroup_name: $group_name;
                        if ($subgroup_name != ''){
                            if ($this->group_model->exists_group_name($subgroup_name) == 1) {
                                $subgroup_id = $this->group_model->get_group_by_name($subgroup_name)->id;
                            } else {
                                $subdata_group = array(
                                    'nombre' => $subgroup_name,
                                    'descripcion' => $subgroup_name,
                                    'estado' => ACTIVO
                                );
                                $this->db->insert('grupo', $subdata_group);
                                $subgroup_id = $this->group_model->get_group($subdata_group)->id;
                            }
                        }else{
                            unlink("./excel_files/" . $file);
                            echo '5';
                            return;
                        }

                        // Asignacion grupo
                        if ($this->group_model->exists_assignment_group($group_id, $subgroup_id) == 0) {
                            $subdata_group = array(
                                'grupo_padre_id' => $group_id,
                                'grupo_hijo_id' => $subgroup_id
                            );
                            $this->db->insert('asignacion_grupo', $subdata_group);
                        }

                        // Unidad Medida
                        $unit_name = strtoupper(trim($objPHPExcel->getActiveSheet()->getCell("L" . $i)->getValue()));
                        $unit_name = ($unit_name!='')? $unit_name: $sin_nombre;
                        if ($unit_name != ''){
                            if ($this->unit_model->exists_unit_name($unit_name) == 1) {
                                $unit_id = $this->unit_model->get_unit_by_name($unit_name)->id;
                            } else {
                                $data_unit = array(
                                    'nombre' => $unit_name,
                                    'abreviatura' => $unit_name,
                                    'estado' => ACTIVO
                                );
                                $this->db->insert('unidad_medida', $data_unit);
                                $unit_id = $this->unit_model->get_unit($data_unit)->id;
                            }
                        }else{
                            unlink("./excel_files/" . $file);
                            echo '6';
                            return;
                        }

                        // Datos producto
                        $cost_price = trim($objPHPExcel->getActiveSheet()->getCell("M" . $i)->getValue());
                        $cost_price = ($cost_price!='')? $cost_price: 0;
                        $sale_price = trim($objPHPExcel->getActiveSheet()->getCell("N" . $i)->getValue());
                        $sale_price = ($sale_price!='')? $sale_price:  $cost_price;
                        $wholesale_price = trim($objPHPExcel->getActiveSheet()->getCell("O" . $i)->getValue());
                        $wholesale_price = ($wholesale_price!='')? $wholesale_price: 0;
                        $express_sale_price = trim($objPHPExcel->getActiveSheet()->getCell("O" . $i)->getValue());
                        $express_sale_price = ($express_sale_price!='')? $express_sale_price: 0;
                        $laboratory_sale_price = trim($objPHPExcel->getActiveSheet()->getCell("O" . $i)->getValue());
                        $laboratory_sale_price = ($laboratory_sale_price!='')? $laboratory_sale_price: 0;
                        // $express_sale_price = (trim($objPHPExcel->getActiveSheet()->getCell("O" . $i)->getValue())!='')? trim($objPHPExcel->getActiveSheet()->getCell("O" . $i)->getValue()) : 0;
                        // $laboratory_sale_price = (trim($objPHPExcel->getActiveSheet()->getCell("O" . $i)->getValue())!='')? trim($objPHPExcel->getActiveSheet()->getCell("O" . $i)->getValue()) : 0;
                        // $laboratory_price_percentage = (trim($objPHPExcel->getActiveSheet()->getCell("R" . $i)->getValue())!='')? trim($objPHPExcel->getActiveSheet()->getCell("R" . $i)->getValue()) : 0;
                        // $commission_percentage = (trim($objPHPExcel->getActiveSheet()->getCell("S" . $i)->getValue());
                        

                        $imei1 = trim($objPHPExcel->getActiveSheet()->getCell("P" . $i)->getValue());
                        $imei1 = ($imei1!='')? $imei1: '';
                        
                        $imei2 = trim($objPHPExcel->getActiveSheet()->getCell("Q" . $i)->getValue());
                        $imei2 = ($imei2!='')? $imei2: '';

                        $stock_minimo = trim($objPHPExcel->getActiveSheet()->getCell("R" . $i)->getValue());
                        $stock_minimo = ($stock_minimo!='')? $stock_minimo: 0;
                        
                        // Registrar
                        $data_product = array(
                            'codigo' => $code_product,
                            'nombre_comercial' => $nametrade,
                            'nombre_generico' => $generic_name,
                            'dimension' => $color_name,
                            'precio_compra' => $cost_price,
                            'precio_venta' => $sale_price,
                            'precio_venta_mayor' => $wholesale_price,
                            'precio_venta_express' => $express_sale_price,
                            'precio_venta_laboratorio' => $laboratory_sale_price,
                            'porcentaje_precioventa_laboratorio' => 10,
                            'stock_minimo' => $stock_minimo,
                            'fecha_registro' => date('Y-m-d'),
                            'fecha_modificacion' => date('Y-m-d'),
                            'estado' => ACTIVO,
                            'grupo_id' => $group_id,
                            'subgrupo_id' => $subgroup_id,
                            'modelo_id' => $model_id,
                            'serie_id' => 1,     //DEFAULT
                            'unidad_medida_id' => $unit_id,
                            'usuario_id' => get_user_id_in_session(),
                            'tipo_producto_id' => 1, // Tipo de Producto
                            // 'porcentaje_comision' => $commission_percentage,
                            'imei1'=> $imei1,
                            //'imei2'=> $imei2
                        );

                        $this->db->insert('producto', $data_product);

                        $product_inserted = $this->db->get_where('producto', $data_product)->row();
                        /*********************** Insertamos proveedor **********************************/
                        $producto_provedor['producto_id'] = $product_inserted->id;
                        $producto_provedor['proveedor_id'] = 1;                 //defecto proveedor 1
                        $this->db->insert('producto_proveedor', $producto_provedor);

						$list_warehouse = $this->warehouse_model->get_warehouse_all();
						foreach ($list_warehouse as $warehouse) {
                            // $warehouse_id = $warehouse->id;
                            if($warehouse_id == $warehouse->id){
                                $data_branch_office_inventory = array(
                                    'precio_compra' =>$cost_price,
                                    'precio_costo' => $cost_price,
                                    'precio_costo_ponderado' => $cost_price,
                                    'precio_venta' => $sale_price,
                                    'stock' => $cantidad_stock,
                                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                                    'usuario_id' => get_user_id_in_session(),
                                    'sucursal_registro_id' => get_branch_id_in_session(),
                                    'sucursal_id' => $warehouse->sucursal_id,
                                    'almacen_id' => $warehouse_id,
                                    'producto_id' => $product_inserted->id,
                                    'precio_venta_1' => $wholesale_price,
                                    'precio_venta_2' => $express_sale_price,
                                    'precio_venta_3' => $laboratory_sale_price,
                                    'porcentaje_precio_venta_3' => 10 / 100
                                );
                                $this->db->insert('inventario_sucursal', $data_branch_office_inventory);
                            
                            
                                $obj_inventary["codigo"] = $code_product;
                                $obj_inventary["cantidad"] = $cantidad_stock;
                                $obj_inventary["cantidad_ingresada"] =  $cantidad_stock;
                                $obj_inventary["precio_compra"] =  $cost_price;
                                $obj_inventary["precio_costo"] =  $cost_price;
                                $obj_inventary["precio_venta"] = $sale_price;
                                $obj_inventary["fecha_ingreso"] = date('Y-m-d H:i:s');
                                $obj_inventary["fecha_modificacion"] = date('Y-m-d H:i:s');
                                $obj_inventary["estado"] = ACTIVO;
                                $obj_inventary["almacen_id"] = $warehouse_id;
                                $obj_inventary["ingreso_inventario_id"] = $inventory_entry_inserted->id;
                                $obj_inventary["producto_id"] = $product_inserted->id;
            
                                $this->inventory_model->_insert_inventory($obj_inventary); 
                            }else{
                                // $data_branch_office_inventory = array(
                                //     'precio_compra' =>$cost_price,
                                //     'precio_costo' => $cost_price,
                                //     'precio_costo_ponderado' => $cost_price,
                                //     'precio_venta' => $sale_price,
                                //     'stock' => 0,
                                //     'fecha_modificacion' => date('Y-m-d H:i:s'),
                                //     'usuario_id' => get_user_id_in_session(),
                                //     'sucursal_registro_id' => get_branch_id_in_session(),
                                //     'sucursal_id' => $warehouse->sucursal_id,
                                //     'almacen_id' => $warehouse->id,
                                //     'producto_id' => $product_inserted->id,
                                //     'precio_venta_1' => $wholesale_price,
                                //     'precio_venta_2' => $express_sale_price,
                                //     'precio_venta_3' => $laboratory_sale_price,
                                //     'porcentaje_precio_venta_3' => 10 / 100
                                // );
                                // $this->db->insert('inventario_sucursal', $data_branch_office_inventory);
                            }
						}

                        //-------------------------------------------------------------
                       
                //     }
                // }
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
    function save_import_stock_products_warehouse()
    {
        // Manipulacion del archivo excel
        $file = $_FILES['excel']['name'];

        // Almacen
        $warehouse_id = $this->input->post('warehouse');

        if (!is_dir("./excel_files"))
            mkdir("./excel_files", 0777);

        if ($file && copy($_FILES['excel']['tmp_name'], "./excel_files/" . $file)) {
            require_once APPPATH . 'libraries/excel/PHPExcel.php';
            require_once APPPATH . 'libraries/excel/PHPExcel/Reader/Excel2007.php';

            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("./excel_files/" . $file);

            // Número de filas del archivo excel
            $filas = $objPHPExcel->getActiveSheet()->getHighestRow();
            $NRO_COLUMNA_EXCEL = 2;

            $this->db->trans_begin();

            $obj_ingreso_inventario = [];
            $obj_ingreso_inventario["nombre"] = 'INGRESO MASIVO A INVENTARIO';
            $obj_ingreso_inventario["fecha_ingreso"] = date('Y-m-d');
            $obj_ingreso_inventario["fecha_registro"] = date('Y-m-d H:i:s');
            $obj_ingreso_inventario["fecha_modificacion"] = date('Y-m-d H:i:s');
            $obj_ingreso_inventario["estado"] = ACTIVO;
            $obj_ingreso_inventario["tipo_ingreso_inventario_id"] = 1; //Ingreso Habitual
            $obj_ingreso_inventario["sucursal_id"] = get_branch_id_in_session();
            $obj_ingreso_inventario["usuario_id"] = get_user_id_in_session();
            $obj_ingreso_inventario["nro_ingreso_inventario"] = $this->inventory_model->last_number_inventory_entry();
            $obj_ingreso_inventario["nro_comprobante"] = 'ING:000';

            $this->inventory_model->_insert_inventory_entry($obj_ingreso_inventario);
			$inventory_entry_inserted = $this->inventory_model->_get_inventory_entry($obj_ingreso_inventario);
                
            for ($i = $NRO_COLUMNA_EXCEL; $i <= $filas; $i++) {

                $product_id = str_replace("COD-SIS-UNIQ-", "", $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue());

                if ($this->product_model->exists_product($product_id) == 1) {

                    $obj_inventary["codigo"] = strval($objPHPExcel->getActiveSheet()->getCell("C" . $i)->getValue());
					$obj_inventary["cantidad"] =  number_format((float)$objPHPExcel->getActiveSheet()->getCell("P" . $i)->getValue(), 2, '.', '');
					$obj_inventary["cantidad_ingresada"] =  number_format((float)$objPHPExcel->getActiveSheet()->getCell("P" . $i)->getValue(), 2, '.', '');
					$obj_inventary["precio_compra"] =  number_format((float)$objPHPExcel->getActiveSheet()->getCell("L" . $i)->getValue(), 2, '.', '');
					$obj_inventary["precio_costo"] =  number_format((float)$objPHPExcel->getActiveSheet()->getCell("L" . $i)->getValue(), 2, '.', '');
					$obj_inventary["precio_venta"] = number_format((float)$objPHPExcel->getActiveSheet()->getCell("M" . $i)->getValue(), 2, '.', '');
					$obj_inventary["fecha_ingreso"] = date('Y-m-d H:i:s');
					$obj_inventary["fecha_modificacion"] = date('Y-m-d H:i:s');
					$obj_inventary["estado"] = ACTIVO;
					$obj_inventary["almacen_id"] = $warehouse_id;
					$obj_inventary["ingreso_inventario_id"] = $inventory_entry_inserted->id;
					$obj_inventary["producto_id"] = $product_id;

					$this->inventory_model->_insert_inventory($obj_inventary);
                    // Datos dell EXCEL
                    $data_branch_office_inventory = array(
                        'nombre_comercial' => strval($objPHPExcel->getActiveSheet()->getCell("D" . $i)->getValue()),
                        'nombre_generico' => strval($objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue()),
                        'precio_compra' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("L" . $i)->getValue(), 2, '.', ''),
                        'precio_costo' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("L" . $i)->getValue(), 2, '.', ''),
                        'precio_costo_ponderado' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("L" . $i)->getValue(), 2, '.', ''),
                        'precio_venta' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("M" . $i)->getValue(), 2, '.', ''),
                        'precio_venta_mayor' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("N" . $i)->getValue(), 2, '.', ''),
                        'precio_venta_express' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("N" . $i)->getValue(), 2, '.', ''),
                        'precio_venta_laboratorio' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("N" . $i)->getValue(), 2, '.', ''),
                        'porcentaje_precioventa_laboratorio' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("N" . $i)->getValue(), 2, '.', ''),
                        'cantidad' => number_format((float)$objPHPExcel->getActiveSheet()->getCell("P" . $i)->getValue(), 2, '.', ''),
                        'fecha_modificacion' => date('Y-m-d H:i:s'),
                        'usuario_id' => get_user_id_in_session(),
                        'sucursal_registro_id' => get_branch_id_in_session(),
                        'producto_id' => $product_id,
                        'warehouse_id' => $warehouse_id
                    );

                    $this->product_model->register_update_branch_office_inventory_by_warehouse2($data_branch_office_inventory);

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

    /*Para cargar la lista de tipo de almacen en el dataTable*/
    public function get_product_reason_list()
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

            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'branch_office_report' => $branch_office_report,
                'warehouse_report' => $warehouse_report,
            );

            echo json_encode($this->product_model->get_product_reason_list($params));
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de tipo de almacen en el dataTable*/
    public function get_inactive_product_reason_list()
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
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order
            );

            echo json_encode($this->product_model->get_inactive_product_reason_list($params));
        } else {
            show_404();
        }
    }
    // Insertar a todos los productos con serie ninguno
    public function insert_serie_none()
    {

        $this->db->select('*')
            ->from('producto')
            ->where('estado', ACTIVO);
        $result = $this->db->get()->result();

        foreach ($result as $producto) {

            $this->db->set('serie_id', 1); // ninguno
            $this->db->where('id', $producto->id);
            $this->db->update('producto');
        }

    }

    function delete_import_products_warehouse()
    {
        // Manipulacion del archivo excel
        $file = $_FILES['excel']['name'];

        // Almacen
        $warehouse_id = $this->input->post('warehouse');

        if (!is_dir("./excel_files"))
            mkdir("./excel_files", 0777);

        if ($file && copy($_FILES['excel']['tmp_name'], "./excel_files/" . $file)) {
            require_once APPPATH . 'libraries/excel/PHPExcel.php';
            require_once APPPATH . 'libraries/excel/PHPExcel/Reader/Excel2007.php';

            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("./excel_files/" . $file);

            // Número de filas del archivo excel
            $filas = $objPHPExcel->getActiveSheet()->getHighestRow();
            $FILA = 1;
            $this->db->trans_begin();

            for ($i = $FILA; $i <= $filas; $i++) {

                $nro = trim($objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue());    
                if ($nro != '') {
                    $codigo = strtoupper(trim($objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue()));
                    $cantidad_stock = trim($objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue());
                    
                    $product = $this->db->get_where('producto', array('codigo' => $codigo))->row();

                    $this->db->where('producto_id', $product->id);
                    $this->db->where('almacen_id', $warehouse_id);
                    $this->db->delete('inventario_sucursal');

                    $this->db->set('cantidad', 0);
                    $this->db->where('producto_id', $product->id);
                    $this->db->where('almacen_id', $warehouse_id);
                    $this->db->update('inventario');

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
    public function generate_code(){
        $numero = rand(0, 99999999);
        return $numero;
    }


}
