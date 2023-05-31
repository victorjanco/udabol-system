<?php

/**
 * Created by PhpStorm.
 * User: Gustavo Cisneros Leon
 * Date: 25/07/2017
 * Time: 05:01 PM
 */
class Reception extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reception_model');
        $this->load->model('brand_model');
        $this->load->model('order_work_model');
        $this->load->model('user_model');
        $this->load->model('product_model');
        $this->load->model('warehouse_model');
        $this->load->model('type_notification_model');
//        $this->load->model('payment_reception_model');
        $this->load->model('reception_payment_model');
        $this->load->model('change_type_model');
    }

    //region Vistas de recepcion
    public function index()
    {
        $brand_for_reception = $this->brand_model->get_brand_enable();
        $type_reason_for_reception = $this->product_model->get_type_reason(TIPO_MOTIVO_RECEPCION);
//        if ($brand_for_new) {
        $data = array(
            'brand_for_reception' => $brand_for_reception,
            "list_warehouse" => $this->warehouse_model->get_warehouse_enable(),
            'type_reason_for_reception' => $type_reason_for_reception,
             /////////////////////////////////////////////////////////////////////////////
            
            ////////////////////////////////////////////////////////////////////////////
        );
        template('reception/index', $data);
    }

    public function delivered()
    {
        template('reception/index_delivered');
    }

    public function new_reception()
    {
        $data["list_brand"] = $this->brand_model->get_brand_enable();
        $data["list_users"] = $this->user_model->get_list_users_admin();
        $data["list_warehouse"] = $this->warehouse_model->get_warehouse_enable();

        template('reception/new_reception', $data);
    }

    //endregion

    public function edit()
    {
        $reception_id = $this->input->post('id');
        if ($reception_id == null) {
            template('reception/index');
            return;
        }
        $reception = $this->reception_model->get_only_recepetion_by_id($reception_id);
//        $detail_service = $this->reception_model->get_reception_detail_service($reception_id);
        $detail_service = $this->order_work_model->get_detail_service_by_order_work_id($this->order_work_model->get_order_by_reception_id($reception_id)->id);
        $data = array(
            "reception" => $reception,
            "detail_service" => $detail_service,
            "detail_product" => $this->order_work_model->get_detail_product_by_reception_id($reception_id),
            "list_warehouse" => $this->warehouse_model->get_warehouse_enable(),
            "list_brand" => $this->brand_model->get_brand_enable(),
            "list_users" => $this->user_model->get_list_users_admin(),
            "reception_payments" => $this->reception_payment_model->get_sum_reception_payments($reception_id)
        );

        template('reception/new_reception', $data);
    }

    public function edit_reception()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->reception_model->edit_reception());
        } else {
            show_404();
        }
    }

    public function register_reception()
    {
        try {
            if ($this->input->is_ajax_request()) {
                echo json_encode($this->reception_model->register_reception());
            } else {
                show_404();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function view_reception()
    {
        if ($this->input->is_ajax_request()) {
            $reception_id = $this->input->post('id');
            echo json_encode($this->reception_model->getdata_printer_report($reception_id));
        } else {
            show_404();
        }
    }

    public function register_gallery()
    {
        if ($_POST) {
            $id_reception = $this->input->post('id');
            $data['reception'] = $this->reception_model->get_reception_by_id($id_reception);
            template('reception/gallery', $data);
        } else {
            redirect('reception/index');
        }
    }

    public function get_receptions_list()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];


            /* Parametros */
            $days = $this->input->post('filter_date');
            $date_start_reception = $this->input->post('filter_date_start_reception');
            $date_end_reception = $this->input->post('filter_date_end_reception');
            $reception_code = $this->input->post('filter_reception_code');
            $reception_brand = $this->input->post('filter_reception_brand');
            $reception_state = $this->input->post('filter_reception_state');

            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'days' => $days,
                'reception_date_start' => $date_start_reception,
                'reception_date_end' => $date_end_reception,
                'reception_number' => $reception_code,
                'reception_brand' => $reception_brand,
                'reception_state' => $reception_state
            );

            $data_reception = $this->reception_model->get_receptions_list($params);
            $data = array();
            foreach ($data_reception['data'] as $row) {
                $registration_date = $row['fecha_registro'];
                $deliver_date = $row['fecha_entrega'];
                if ($deliver_date == null) {
                    $deliver_date = date('Y-m-d H:i:s');
                }
                $work_hours = $this->work_hours($registration_date, $deliver_date);

                if ($row['garantia'] == 1) {
                    $garantia = 'Con Garantia';
                } else if ($row['garantia'] == 0) {
                    $garantia = 'Sin garantia';
                } else {
                    $garantia = 'Por Verifcar';
                }
                $array = array();
                $array['id'] = $row['id'];
                $array['codigo_recepcion'] = $row['codigo_recepcion'];
                $array['nombre'] = $row['nombre'];
                $array['prioridad'] = get_priority_text($row['prioridad']);
                $array['monto_total'] = $row['monto_total'];
                $array['estado'] = $row['estado'];
                $array['imei'] = $row['imei'];
                $array['galeria'] = $row['galeria'];
                $array['nombre_marca'] = $row['nombre_marca'];
                $array['nombre_modelo'] = $row['nombre_modelo'];
                $array['fecha_registro'] = date('d-m-Y', strtotime($row['fecha_registro']));
                $array['fecha_entrega'] = $this->get_delivery_date($row['fecha_entrega']);
                $array['estado_trabajo'] = get_work_order_states($row['estado_trabajo']);
                $array['garantia'] = $garantia;
                $array['horas'] = $work_hours;
                $array['dias'] = $this->is_late($row['fecha_modificacion'], date('Y-m-d'));
                $array['recepcion_usuario'] = $this->user_model->get_user_id($row['recepcion_usuario_id'])->usuario;
                if ($row['perito_usuario_id'] != null) {
                    $array['perito_usuario'] = $this->user_model->get_user_id($row['perito_usuario_id'])->usuario;
                } else {
                    $array['perito_usuario'] = 'NO PERITO';
                }

                $data[] = $array;
            }


            $json_data = array(
                'draw' => $data_reception['draw'],
                'recordsTotal' => $data_reception['recordsTotal'],
                'recordsFiltered' => $data_reception['recordsFiltered'],
                'data' => $data,
            );

            echo json_encode($json_data);
        } else {
            show_404();
        }
    }

    public function get_receptions_list_delivered()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            /* Parametros */
            $date_start_delivered = $this->input->post('filter_date_start_delivered');
            $date_end_delivered = $this->input->post('filter_date_end_delivered');

            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'delivered_date_start' => $date_start_delivered,
                'delivered_date_end' => $date_end_delivered
            );


            $data_reception = $this->reception_model->get_receptions_list_delivered($params);

            $data = array();
            foreach ($data_reception['data'] as $row) {
                $array = array();
                $array['id'] = $row['id'];
                $array['codigo_recepcion'] = $row['codigo_recepcion'];
                $array['nombre'] = $row['nombre'];
                $array['prioridad'] = get_priority_text($row['prioridad']);
                $array['monto_total'] = $row['monto_total'];
                $array['estado'] = $row['estado'];
                $array['imei'] = $row['imei'];
                $array['galeria'] = $row['galeria'];
                $array['nombre_marca'] = $row['nombre_marca'];
                $array['nombre_modelo'] = $row['nombre_modelo'];
                $array['fecha_registro'] = date('d-m-Y', strtotime($row['fecha_registro']));
                $array['fecha_entrega'] = $this->get_delivery_date($row['fecha_entrega']);
                $array['estado_trabajo'] = get_work_order_states($row['estado_trabajo']);

                if ($row['perito_usuario_id'] != null) {
                    $array['perito_usuario'] = $this->user_model->get_user_id($row['perito_usuario_id'])->usuario;
                } else {
                    $array['perito_usuario'] = 'Usuario sin peritar';
                }

                $data[] = $array;
            }

            $json_data = array(
                'draw' => $data_reception['draw'],
                'recordsTotal' => $data_reception['recordsTotal'],
                'recordsFiltered' => $data_reception['recordsFiltered'],
                'params' => $data_reception['params'],
                'data' => $data,
            );

            echo json_encode($json_data);
        } else {
            show_404();
        }
    }

    public function work_hours($start_date, $start_end)
    {
        $star_hour = date("H", strtotime($start_date));
        $star_minute = date("i", strtotime($start_date));
        $star_second = date("s", strtotime($start_date));
        $star_day = date("d", strtotime($start_date));
        $star_mounth = date("m", strtotime($start_date));
        $star_year = date("Y", strtotime($start_date));

        $end_hour = date("H", strtotime($start_end));
        $end_minute = date("i", strtotime($start_end));
        $end_second = date("s", strtotime($start_end));
        $end_day = date("d", strtotime($start_end));
        $end_mounth = date("m", strtotime($start_end));
        $end_year = date("Y", strtotime($start_end));


        $start_date = mktime($star_hour, $star_minute, $star_second, $star_mounth, $star_day, $star_year);
        $start_end = mktime($end_hour, $end_minute, $end_second, $end_mounth, $end_day, $end_year);
        $diferencia = $start_end - $start_date;
        $diff['horas'] = (int)($diferencia / (60 * 60));
        $diff['dias'] = (int)($diferencia / (60 * 60 * 24));

        return $diff['horas'];
    }

    public function disable()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->reception_model->disable();
        } else {
            show_404();
        }
    }

    public function get_delivery_date($date)
    {
        if ($date == null) {
            return 'sin entregar';
        }
        return date('d-m-Y', strtotime($date));
    }

    public function get_html_state()
    {
        if ($this->input->is_ajax_request()) {
            $state_reception = $this->input->post('state_reception');
            $reception_id = $this->input->post('reception_id');
            $type_option = $this->input->post('type_option');
            echo $this->reception_model->get_html_state($state_reception, $reception_id, $type_option);
        } else {
            show_404();
        }
    }

    public function add_gallery()
    {
        $output = '';
        if (is_array($_FILES)) {
            $reception_id = $this->input->post('id_reception');
            $reception_code = $this->input->post('reception_code');
            foreach ($_FILES['files']['name'] as $name => $value) {
                $file_name = explode(".", $_FILES['files']['name'][$name]);
                $allowed_ext = array("jpg", "jpeg", "png", "gif");
                if (in_array($file_name[1], $allowed_ext)) {
                    // $new_name = /*md5(rand())*/ $file_name[0] . '.' . $file_name[1];
                    $new_name = $reception_id . "_" . $reception_code . "_" . uniqid() . "." . $file_name[1];
                    $sourcePath = $_FILES['files']['tmp_name'][$name];
                    $targetPath = DIRECTORY_RAIZ_PATH . $reception_id . '/' . $new_name;
                    if (move_uploaded_file($sourcePath, $targetPath)) {
                        $this->reception_model->register_image_reception($reception_id, $new_name);
                    }
                }
            }
            echo true;
            //echo $output;
        } else {
            echo false;
        }
    }

    public function get_reception_images()
    {
        if ($this->input->is_ajax_request()) {
            $reception_id = $this->input->post('reception_id');
            echo $this->reception_model->get_reception_images($reception_id);
        } else {
            show_404();
        }
    }

    public function delete_image()
    {
        if ($this->input->is_ajax_request()) {
            $image_id = $this->input->post('image_id');
            echo $this->reception_model->delete_image($image_id);
        } else {
            show_404();
        }
    }

    public function is_late($date_latter, $date_begin)
    {
        $dias = (strtotime($date_begin) - strtotime(date('Y-m-d', strtotime($date_latter)))) / 86400;
        $dias = abs($dias);
        $dias = floor($dias);
        return $dias;

    }

    public function substrack_date()
    {
        $fecha = date('Y-m-d');
        $nuevafecha = strtotime('-3 day', strtotime($fecha));
        $nuevafecha = date('Y-m-d', $nuevafecha);
        echo $nuevafecha;
    }

    /* Capturar la imagen desde la camara web*/
    public function take_picture_from_webcam()
    {
        /* Obtenemos parametros */
        $reception_id = $this->input->post('reception_id');
        $coded_image = $this->input->post('image');
        $reception_code = $this->input->post('reception_code');

        if (strlen($coded_image) <= 0)
            exit("No se recibió ninguna imagen");

        $clean_coded_image = str_replace("data:image/png;base64,", "",
            urldecode($coded_image));                                                       //La imagen traerá al inicio de la imagen codificada " data:image/png;base64 ", debemos remover
        $decoded_image = base64_decode($clean_coded_image);                                 // Lo decodificamos la imagen
        $image_name = $reception_id . "_" . $reception_code . "_" . uniqid() . ".png";      // Ponemos un nombre unico a la imagen
        $path = DIRECTORY_RAIZ_PATH . $reception_id . '/' . $image_name;                    // La ubicacion donde estara almacenada la imagen

        file_put_contents($path, $decoded_image);                                            // Escribir el archivo en la direccion

        $this->reception_model->register_image_reception($reception_id, $image_name);       // Registramos en la base de datos
    }

    public function get_colors()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->reception_model->get_colors());
        } else {
            show_404();
        }
    }

    public function get_color_by_reception_id()
    {
        if ($this->input->is_ajax_request()) {
            $reception_id = $this->input->post('reception_id');
            echo json_encode($this->reception_model->get_color_by_reception_id($reception_id));
        } else {
            show_404();
        }
    }

    public function register_reception_reason()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->reception_model->register_reception_reason());
        } else {
            show_404();
        }
    }

    public function get_warehouse_for_type_warranty()
    {
        if ($this->input->is_ajax_request()) {
            $warranty = $this->input->post('warranty');
            switch ($warranty) {
                case SIN_GARANTIA:
                    // $result = $this->warehouse_model->get_warehouse_for_type_warranty(9);
                    $result = $this->warehouse_model->get_warehouse_for_sin_garantia();
                    break;
                case CON_GARANTIA:
                    $result = $this->warehouse_model->get_warehouse_for_type_warranty(10);
                    break;
                default:
                    $result = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
                    break;
            }

            echo json_encode($result);
        } else {
            show_404();
        }
    }

    public function view_reception_and_note_sale()
    {
        try {
            $reception_id = $this->input->post('reception_id');
            $data["totals"] = $this->reception_payment_model->get_sum_reception_payments($reception_id);
            $data["recepcion"] = $this->reception_model->getdata_printer_report($reception_id);
            $data["calculate"] = $this->reception_model->calculate_total_reception_deliver($data["recepcion"], $data["totals"]);
            $data["reception_id"] = $reception_id;
            $data['cash_id']=get_session_cash_id(); //caja aperturada
            $data['cash_aperture_id']=get_session_cash_aperture_id(); //aperturada_caja_id
            $data['change_type']= $this->change_type_model->get_first();

            template('reception/view_reception_note_sale', $data);
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }
}
