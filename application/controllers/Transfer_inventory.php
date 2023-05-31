<?php

/**
 *  * Created by PhpStorm.
 * User: Ariel Alejandro Gomez Chavez ( @ArielGomez )
 * Date: 7/5/2018
 * Time: 7:37 PM
 */
class Transfer_inventory extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transfer_inventory_model');
        $this->load->model('office_model');
        $this->load->model('type_inventory_entry_model');
        $this->load->model('warehouse_model');
        $this->load->model('inventory_model');
        $this->load->model('provider_model');
        $this->load->model('type_product_model');
    }

    /* Obtener Listado de salida de inventario  */
    public function get_transfer_inventory_output_list()
    {
        if ($this->input->is_ajax_request()) {
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
            echo json_encode($this->transfer_inventory_model->get_transfer_inventory_output_list($params));
        } else {
            show_404();
        }
    }

    /*para cargar la lista de traspaso de salida*/
    public function transfer_inventory_output()
    {
        template('transfer_inventory/transfer_inventory_output');
    }

    /*Nuevo traspaso salida de inventario*/
    public function new_transfer_inventory_output()
    {
        $data['branch_office'] = get_branch_office_name_in_session();
        $data['list_branch_office'] = $this->office_model->get_offices();
        $data['list_type_product'] = $this->type_product_model->get_type_product();
        $data['list_type_exit_inventory'] = $this->type_inventory_entry_model->get_type_exit_inventory_enable1();
        $data['list_warehouse'] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
        template('transfer_inventory/new_transfer_inventory_output', $data);
    }

    /*  Registrar salida de inventario  */
    public function register_transfer_inventory_output()
    {
        try {
            if ($this->input->is_ajax_request()) {
                echo json_encode($this->transfer_inventory_model->register_transfer_inventory_output());
            } else {
                show_404();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function view_transfer_inventory_output()
    {
        if ($this->input->post()) {
            $inventory_output_id = $this->input->post('id');
            $data["transfer_inventory_output"] = $this->transfer_inventory_model->get_transfer_output($inventory_output_id);
            $data["inventory_output_detail"] = $this->inventory_model->get_detail_inventory_output($inventory_output_id);
            template('transfer_inventory/view_transfer_inventory_output', $data);
        } else {
            show_404();
        }
    }

    /*para cargar la lista de traspaso de ingreso*/
    public function transfer_inventory_entry()
    {
        template('transfer_inventory/transfer_inventory_entry');
    }

    /* Obtener Listado de ingreso de inventario  */
    public function get_transfer_inventory_entry_list()
    {
        if ($this->input->is_ajax_request()) {
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
            echo json_encode($this->transfer_inventory_model->get_transfer_inventory_entry_list($params));
        } else {
            show_404();
        }
    }

    public function disable_trasfer_inventory_entry()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            echo json_encode($this->transfer_inventory_model->disable_transfer_inventory_entry($id));
        } else {
            show_404();
        }
    }

    public function disable_trasfer_inventory_output()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            echo json_encode($this->transfer_inventory_model->disable_transfer_inventory_output($id));
        } else {
            show_404();
        }
    }
    
    public function aprobation_transfer_inventory_entry()
    {
        try {
            if ($this->input->is_ajax_request()) {
                $id = $this->input->post('id');
                echo json_encode($this->transfer_inventory_model->aprobation_transfer_inventory_entry($id));
            } else {
                show_404();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function descart_transfer_inventory_entry()
    {
        try {
            if ($this->input->is_ajax_request()) {
                $id = $this->input->post('id');
                echo json_encode($this->transfer_inventory_model->descart_transfer_inventory_entry($id));
            } else {
                show_404();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function view_transfer_inventory_entry()
    {
        if ($this->input->post()) {
            $inventory_output_id = $this->input->post('id');
            $data["transfer_inventory_output"] = $this->transfer_inventory_model->get_transfer_entry($inventory_output_id);
            $data["inventory_output_detail"] = $this->inventory_model->get_detail_inventory_view($inventory_output_id);
            template('transfer_inventory/view_transfer_inventory_entry', $data);
        } else {
            show_404();
        }
    }
    public function aprobation_inventory_entry()
    {
        if ($this->input->post()) {
            $inventory_entry_id = $this->input->post('id');
            $data["transfer_inventory_entry"] = $this->transfer_inventory_model->get_transfer_entry($inventory_entry_id);
            $data["inventory_entry_detail"] = $this->inventory_model->get_detail_inventory_view($inventory_entry_id);
            template('transfer_inventory/aprobation_inventory_entry', $data);
        } else {
            show_404();
        }
    }

    public function print_transfer_inventory()
    {
        $inventory_output_id = $this->input->post('id');
        $transfer_inventory_output = $this->transfer_inventory_model->get_transfer_output($inventory_output_id);
        $inventory_output_detail = $this->inventory_model->get_detail_inventory_output($inventory_output_id);
        $data['branch_office'] = $this->office_model->get_branch_office_id(get_branch_id_in_session());

        /* Datos generales para generar PDF */
        // Nota: el ancho total de la pagina es 192

        $height = 5;
        $newline = 5;

        // Bordes
        $no_frame = 0;
        $frame = 1;
        $left = 'L';
        $right = 'R';
        $top = 'T';
        $below = 'B';

        // Alinear
        $align_left = 'L';
        $align_center = 'C';
        $align_right = 'R';

        // Estilo
        $style_font = 'Arial';
        $style_border = 'B';
        $style_size = 8;

        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();
        $this->pdf->AliasNbPages();

        /* TITULO */
        $this->pdf->SetTitle("TRASPASO SALIDA DE INVENTARIO");

        /* IMAGEN LOGO */
        $var_img = base_url() . 'assets/images/'.$data['branch_office']->imagen;;
        $this->pdf->Image($var_img, 13, 10, 80, 28);

        /*  ENCABEZADO: DATOS SUCURSAL */
        // Primera fila
        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(130, $height, '', $no_frame, 0, $align_center);
        $this->pdf->Cell(60, $height, utf8_decode($data['branch_office']->nombre_comercial), $no_frame, 0, $align_center);

        // Segunda fila
        $this->pdf->Ln(5);
        $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(90, $height, '', $no_frame, 0, $align_center);
        $this->pdf->Cell(35, $height, utf8_decode('Traspaso de Salida'), $no_frame, 0, $align_center);
        $this->pdf->SetFont($style_font, $style_border, $style_size + 1);
        $this->pdf->SetTextColor(0, 000, 000);
        $this->pdf->MultiCell(70, $height, utf8_decode(get_branch_office_name_in_session()), $no_frame, $align_center);

        // Tercera fila
        $this->pdf->Ln(0);
        $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(85, $height, '', $no_frame, 0, $align_center);
        $this->pdf->Cell(40, $height, '', $no_frame, 0, $align_center);
        $this->pdf->SetFont($style_font, '', $style_size + 1);
        $this->pdf->SetTextColor(0, 000, 000);
        $this->pdf->MultiCell(70, $height - 1, utf8_decode($data['branch_office']->direccion), $no_frame, $align_center);

        // Cuarta fila
        $this->pdf->Ln(0);
        $this->pdf->Cell(125, $height, '', $no_frame, 0, $align_center);
        $this->pdf->Cell(70, $height - 1, 'Telf. ' . utf8_decode($data['branch_office']->telefono), $no_frame, 0, $align_center);

        // Quinta fila
        $this->pdf->Ln(4);
        $this->pdf->Cell(85, $height, '', $no_frame);
        $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(40, $height, '', $no_frame, 0, $align_center);
        $this->pdf->SetFont($style_font, '', $style_size + 1);
        $this->pdf->SetTextColor(0, 000, 000);
        $this->pdf->Cell(70, $height - 1, utf8_decode($data['branch_office']->ciudad_impuestos), $no_frame, 0, $align_center);

        $this->pdf->Ln($newline + 7);

        /* DATOS */
        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(192, $height, utf8_decode('DATOS'), $frame, 0, $align_center);
        $this->pdf->Ln($newline + 2);

        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(30, $height,  utf8_decode('Nro Salida Traspaso: '), $top . $left);
        $this->pdf->SetFont($style_font, '', $style_size);
        $this->pdf->Cell(66, $height, utf8_decode($transfer_inventory_output->nro) , $top);

        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(30, $height,  utf8_decode('Tipo Salida: '), $top );
        $this->pdf->SetFont($style_font, '', $style_size);
        $this->pdf->Cell(66, $height, utf8_decode($transfer_inventory_output->nombre_tipo_salida) ,$top.$right);
        $this->pdf->Ln($newline);

        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(30, $height, utf8_decode('Sucursal Origen: '), $left);
        $this->pdf->SetFont($style_font, '', $style_size);
        $this->pdf->Cell(66, $height, utf8_decode($transfer_inventory_output->sucursal_origen), $no_frame);

        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(30, $height, utf8_decode('Sucursal destino: '), $no_frame);
        $this->pdf->SetFont($style_font, '', $style_size);
        $this->pdf->Cell(66, $height, utf8_decode($transfer_inventory_output->sucursal_destino) , $right);
        $this->pdf->Ln($newline);

        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(30, $height, utf8_decode('Almacen Destino: '), $left);
        $this->pdf->SetFont($style_font, '', $style_size);
        $this->pdf->Cell(66, $height, utf8_decode($transfer_inventory_output->almacen_destino), $no_frame);

        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(30, $height, utf8_decode('Fecha Salida: '), $no_frame);
        $this->pdf->SetFont($style_font, '', $style_size);
        $this->pdf->Cell(66, $height, utf8_decode($transfer_inventory_output->fecha_modificacion), $right);
        $this->pdf->Ln($newline);

        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(30, $height, utf8_decode('Observacion: '), $left. $below);
        $this->pdf->SetFont($style_font, '', $style_size);
        $this->pdf->Cell(162, $height, utf8_decode($transfer_inventory_output->observacion), $right . $below);

        $this->pdf->Ln($newline + 4);

        /* DETALLE */
        $this->pdf->SetFont($style_font, 'B', $style_size);
        $this->pdf->Cell(192, $height, utf8_decode('DETALLE'), $frame, 0, $align_center);
        $this->pdf->Ln($newline + 2);

        $this->pdf->Cell(24, $height, "CODIGO", $frame, 0, $align_center);
        $this->pdf->Cell(90, $height, "PRODUCTO", $frame, 0, $align_center);
        $this->pdf->Cell(20, $height, "P. COSTO", $frame, 0, $align_center);
        $this->pdf->Cell(20, $height, "P. VENTA", $frame, 0, $align_center);
        // $this->pdf->Cell(17, $height, "LOTE", $frame, 0, $align_center);
        // $this->pdf->Cell(24, $height, "F. VENC.", $frame, 0, $align_center);
        $this->pdf->Cell(19, $height, "CANTIDAD", $frame, 0, $align_center);
        $this->pdf->Cell(19, $height, "ALMACEN", $frame, 0, $align_center);
        $this->pdf->Ln($newline);

        $nro = 1;
        $this->pdf->SetFont($style_font, '', $style_size);

        $number_rows = 0;
        $number_items = 0;
        $total_detail = 10;

        $this->pdf->SetWidths(array(24, 90, 20, 20, 19, 19));
        $this->pdf->SetAligns(array($align_center, $align_left, $align_center, $align_center, $align_center, $align_center, $align_center, $align_center));

        foreach ($inventory_output_detail as $row) {
            $inventory = $this->inventory_model->get_inventory_new_by_id($row->inventario_id);
            $producto = $this->product_model->get_product_entity_by_id($inventory->producto_id);
            $almacen = $this->warehouse_model->get_warehouse_id($inventory->almacen_id);

            $number_rows++;

            $this->pdf->Row(array(
                utf8_decode($producto->codigo),
                utf8_decode($producto->nombre_comercial.' - '.$producto->nombre_generico ),
                utf8_decode($row->precio_costo),
                utf8_decode($row->precio_venta),
                // utf8_decode($inventory->codigo),
                // utf8_decode($inventory->fecha_vencimiento),
                utf8_decode($row->cantidad),
                utf8_decode($almacen->nombre)
            ));
            $nro = $nro + 1;
            $number_items = $number_items + 1;
        }

        while ($total_detail - 1 >= $number_items) {

            $number_rows++;
            $style = $right . $left;
            if ($nro == 1) {
                $style = $style . $top;
            }
            if ($number_rows == $total_detail) {
                $style = $left . $right . $below;
            }

            $this->pdf->Cell(24, $height - 1, '', $style, 0, $align_center);
            $this->pdf->Cell(90, $height - 1, '', $style, 0, $align_left);
            $this->pdf->Cell(20, $height - 1, '', $style, 0, $align_center);
            $this->pdf->Cell(20, $height - 1, '', $style, 0, $align_center);
            // $this->pdf->Cell(17, $height - 1, '', $style, 0, $align_center);
            // $this->pdf->Cell(24, $height - 1, '', $style, 0, $align_center);
            $this->pdf->Cell(19, $height - 1, '', $style, 0, $align_center);
            $this->pdf->Cell(19, $height - 1, '', $style, 0, $align_center);
            $this->pdf->Ln($newline-1);
            $number_items = $number_items + 1;
        }

        $this->pdf->ln(18);

        $this->pdf->Cell(95, 5, '-------------------------------- ', '', 0, 'C');
        $this->pdf->Cell(75, 5, '-------------------------------- ', '', 0, 'C');
        $this->pdf->ln(2);
        $this->pdf->Cell(95, 5, 'Entregue Conforme ', '', 0, 'C');
        $this->pdf->Cell(75, 5, 'Recibi Conforme', '', 0, 'C');

        $this->pdf->Output("Traspaso salida" . utf8_decode('000') . ".pdf", 'I');



    }

}