DROP SEQUENCE IF EXISTS "asignacion_grupo_id_seq"
;

DROP SEQUENCE IF EXISTS "venta_recepcion_id_seq"
;

DROP SEQUENCE IF EXISTS "venta_id_seq"
;

DROP SEQUENCE IF EXISTS "usuario_id_seq"
;

DROP SEQUENCE IF EXISTS "unidad_medida_id_seq"
;

DROP SEQUENCE IF EXISTS "tipo_servicio_id_seq"
;

DROP SEQUENCE IF EXISTS "tipo_salida_inventario_id_seq"
;

DROP SEQUENCE IF EXISTS "tipo_ingreso_inventario_id_seq"
;

DROP SEQUENCE IF EXISTS "tipo_ingreso_egreso_id_seq"
;

DROP SEQUENCE IF EXISTS "sucursal_id_seq"
;

DROP SEQUENCE IF EXISTS "solucion_recepcion_id_seq"
;

DROP SEQUENCE IF EXISTS "solucion_orden_trabajo_id_seq"
;

DROP SEQUENCE IF EXISTS "solucion_id_seq"
;

DROP SEQUENCE IF EXISTS "servicio_id_seq"
;

DROP SEQUENCE IF EXISTS "salida_inventario_venta_id_seq"
;

DROP SEQUENCE IF EXISTS "salida_inventario_id_seq"
;

DROP SEQUENCE IF EXISTS "detalle_salida_inventario_id_seq"
;

DROP SEQUENCE IF EXISTS "recepcion_id_seq"
;

DROP SEQUENCE IF EXISTS "proveedor_id_seq"
;

DROP SEQUENCE IF EXISTS "producto_id_seq"
;

DROP SEQUENCE IF EXISTS "precio_servicio_categoria_id_seq"
;

DROP SEQUENCE IF EXISTS "pago_trabajo_id_seq"
;

DROP SEQUENCE IF EXISTS "orden_trabajo_id_seq"
;

DROP SEQUENCE IF EXISTS "modelo_id_seq"
;

DROP SEQUENCE IF EXISTS "menu_id_seq"
;

DROP SEQUENCE IF EXISTS "marca_id_seq"
;

DROP SEQUENCE IF EXISTS "inventario_id_seq"
;

DROP SEQUENCE IF EXISTS "ingreso_venta_id_seq"
;

DROP SEQUENCE IF EXISTS "ingreso_inventario_id_seq"
;

DROP SEQUENCE IF EXISTS "ingreso_compra_producto_id_seq"
;

DROP SEQUENCE IF EXISTS "ingreso_id_seq"
;

DROP SEQUENCE IF EXISTS "impresora_id_seq"
;

DROP SEQUENCE IF EXISTS "imagen_recepcion_id_seq"
;

DROP SEQUENCE IF EXISTS "historial_recepcion_id_seq"
;

DROP SEQUENCE IF EXISTS "historial_orden_trabajo_id_seq"
;

DROP SEQUENCE IF EXISTS "historial_compra_id_seq"
;

DROP SEQUENCE IF EXISTS "falla_recepcion_id_seq"
;

DROP SEQUENCE IF EXISTS "falla_orden_trabajo_id_seq"
;

DROP SEQUENCE IF EXISTS "falla_id_seq"
;

DROP SEQUENCE IF EXISTS "factura_compra_id_seq"
;

DROP SEQUENCE IF EXISTS "factura_id_seq"
;

DROP SEQUENCE IF EXISTS "equipo_cliente_id_seq"
;

DROP SEQUENCE IF EXISTS "empresa_id_seq"
;

DROP SEQUENCE IF EXISTS "egreso_id_seq"
;

DROP SEQUENCE IF EXISTS "dosificacion_id_seq"
;

DROP SEQUENCE IF EXISTS "detalle_venta_id_seq"
;

DROP SEQUENCE IF EXISTS "detalle_recepcion_servicio_id_seq"
;

DROP SEQUENCE IF EXISTS "detalle_producto_trabajo_id_seq"
;

DROP SEQUENCE IF EXISTS "detalle_orden_trabajo_servicio_id_seq"
;

DROP SEQUENCE IF EXISTS "detalle_compra_id_seq"
;

DROP SEQUENCE IF EXISTS "compra_id_seq"
;

DROP SEQUENCE IF EXISTS "cliente_id_seq"
;

DROP SEQUENCE IF EXISTS "categoria_id_seq"
;

DROP SEQUENCE IF EXISTS "almacen_id_seq"
;

DROP SEQUENCE IF EXISTS "actividad_id_seq"
;

DROP SEQUENCE IF EXISTS "acceso_id_seq"
;

DROP TABLE IF EXISTS "asignacion_grupo" CASCADE
;

DROP TABLE IF EXISTS "venta_recepcion" CASCADE
;

DROP TABLE IF EXISTS "venta" CASCADE
;

DROP TABLE IF EXISTS "usuario" CASCADE
;

DROP TABLE IF EXISTS "unidad_medida" CASCADE
;

DROP TABLE IF EXISTS "tipo_servicio" CASCADE
;

DROP TABLE IF EXISTS "tipo_salida_inventario" CASCADE
;

DROP TABLE IF EXISTS "tipo_ingreso_inventario" CASCADE
;

DROP TABLE IF EXISTS "tipo_ingreso_egreso" CASCADE
;

DROP TABLE IF EXISTS "sucursal" CASCADE
;

DROP TABLE IF EXISTS "solucion_recepcion" CASCADE
;

DROP TABLE IF EXISTS "solucion_orden_trabajo" CASCADE
;

DROP TABLE IF EXISTS "solucion" CASCADE
;

DROP TABLE IF EXISTS "solicitud_traspaso_inventario" CASCADE
;

DROP TABLE IF EXISTS "servicio" CASCADE
;

DROP TABLE IF EXISTS "salida_inventario_venta" CASCADE
;

DROP TABLE IF EXISTS "salida_inventario" CASCADE
;

DROP TABLE IF EXISTS "detalle_salida_inventario" CASCADE
;

DROP TABLE IF EXISTS "recepcion" CASCADE
;

DROP TABLE IF EXISTS "proveedor" CASCADE
;

DROP TABLE IF EXISTS "producto" CASCADE
;

DROP TABLE IF EXISTS "precio_servicio_categoria" CASCADE
;

DROP TABLE IF EXISTS "pago_trabajo" CASCADE
;

DROP TABLE IF EXISTS "orden_trabajo" CASCADE
;

DROP TABLE IF EXISTS "modelo" CASCADE
;

DROP TABLE IF EXISTS "menu" CASCADE
;

DROP TABLE IF EXISTS "marca" CASCADE
;

DROP TABLE IF EXISTS "inventario" CASCADE
;

DROP TABLE IF EXISTS "ingreso_venta" CASCADE
;

DROP TABLE IF EXISTS "ingreso_inventario" CASCADE
;

DROP TABLE IF EXISTS "ingreso_compra_producto" CASCADE
;

DROP TABLE IF EXISTS "ingreso" CASCADE
;

DROP TABLE IF EXISTS "impresora" CASCADE
;

DROP TABLE IF EXISTS "imagen_recepcion" CASCADE
;

DROP TABLE IF EXISTS "historial_recepcion" CASCADE
;

DROP TABLE IF EXISTS "historial_orden_trabajo" CASCADE
;

DROP TABLE IF EXISTS "historial_compra" CASCADE
;

DROP TABLE IF EXISTS "grupo" CASCADE
;

DROP TABLE IF EXISTS "falla_recepcion" CASCADE
;

DROP TABLE IF EXISTS "falla_orden_trabajo" CASCADE
;

DROP TABLE IF EXISTS "falla" CASCADE
;

DROP TABLE IF EXISTS "factura_compra" CASCADE
;

DROP TABLE IF EXISTS "factura" CASCADE
;

DROP TABLE IF EXISTS "equipo_cliente" CASCADE
;

DROP TABLE IF EXISTS "empresa" CASCADE
;

DROP TABLE IF EXISTS "egreso" CASCADE
;

DROP TABLE IF EXISTS "dosificacion" CASCADE
;

DROP TABLE IF EXISTS "detalle_venta" CASCADE
;

DROP TABLE IF EXISTS "detalle_recepcion_servicio" CASCADE
;

DROP TABLE IF EXISTS "detalle_producto_trabajo" CASCADE
;

DROP TABLE IF EXISTS "detalle_orden_trabajo_servicio" CASCADE
;

DROP TABLE IF EXISTS "detalle_compra" CASCADE
;

DROP TABLE IF EXISTS "compra" CASCADE
;

DROP TABLE IF EXISTS "cliente" CASCADE
;

DROP TABLE IF EXISTS "categoria" CASCADE
;

DROP TABLE IF EXISTS "almacen" CASCADE
;

DROP TABLE IF EXISTS "actividad" CASCADE
;

DROP TABLE IF EXISTS "acceso" CASCADE
;

CREATE TABLE "asignacion_grupo"
(
	"id" bigint NOT NULL DEFAULT nextval(('"asignacion_grupo_id_seq"'::text)::regclass),
	"grupo_padre_id" bigint NOT NULL,
	"grupo_hijo_id" bigint NOT NULL
)
;

CREATE TABLE "venta_recepcion"
(
	"id" bigint NOT NULL DEFAULT nextval(('"venta_recepcion_id_seq"'::text)::regclass),
	"recepcion_id" bigint NOT NULL,
	"venta_id" bigint NOT NULL
)
;

CREATE TABLE "venta"
(
	"id" bigint NOT NULL DEFAULT nextval(('"venta_id_seq"'::text)::regclass),
	"tipo_venta" varchar(50)	,
	"fecha_registro" date,
	"subtotal" decimal(20,2),
	"descuento" decimal(20,2),
	"total" decimal(20,2),
	"sincronizado" smallint,
	"cliente_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "usuario"
(
	"id" bigint NOT NULL DEFAULT nextval(('"usuario_id_seq"'::text)::regclass),
	"ci" text,
	"nombre" text,
	"telefono" text,
	"usuario" text,
	"clave" text,
	"estado" smallint,
	"cargo_id" bigint NOT NULL
)
;

CREATE TABLE "unidad_medida"
(
	"id" bigint NOT NULL DEFAULT nextval(('"unidad_medida_id_seq"'::text)::regclass),
	"nombre" text,
	"abreviatura" varchar(20)	,
	"estado" smallint
)
;

CREATE TABLE "tipo_servicio"
(
	"id" bigint NOT NULL DEFAULT nextval(('"tipo_servicio_id_seq"'::text)::regclass),
	"nombre" text,
	"descripcion" text,
	"estado" smallint
)
;

CREATE TABLE "tipo_salida_inventario"
(
	"id" bigint NOT NULL DEFAULT nextval(('"tipo_salida_inventario_id_seq"'::text)::regclass),
	"nombre" text,
	"descripcion" text,
	"fecha_registro" date,
	"estado" smallint
)
;

CREATE TABLE "tipo_ingreso_inventario"
(
	"id" bigint NOT NULL DEFAULT nextval(('"tipo_ingreso_inventario_id_seq"'::text)::regclass),
	"nombre" text,
	"decripcion" text,
	"fecha_registro" date,
	"estado" smallint
)
;

CREATE TABLE "tipo_ingreso_egreso"
(
	"id" bigint NOT NULL DEFAULT nextval(('"tipo_ingreso_egreso_id_seq"'::text)::regclass),
	"descripcion" text,
	"tipo" smallint,
	"estado" smallint
)
;

CREATE TABLE "sucursal"
(
	"id" bigint NOT NULL DEFAULT nextval(('"sucursal_id_seq"'::text)::regclass),
	"nombre" text,
	"direccion" text,
	"ciudad" text,
	"estado" smallint,
	"empresa_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "solucion_recepcion"
(
	"id" bigint NOT NULL DEFAULT nextval(('"solucion_recepcion_id_seq"'::text)::regclass),
	"estado" smallint,
	"recepcion_id" bigint NOT NULL,
	"solucion_id" bigint NOT NULL
)
;

CREATE TABLE "solucion_orden_trabajo"
(
	"id" bigint NOT NULL DEFAULT nextval(('"solucion_orden_trabajo_id_seq"'::text)::regclass),
	"estado" smallint,
	"orden_trabajo_id" bigint NOT NULL,
	"solucion_id" bigint NOT NULL
)
;

CREATE TABLE "solucion"
(
	"id" bigint NOT NULL DEFAULT nextval(('"solucion_id_seq"'::text)::regclass),
	"nombre" decimal(20,2),
	"descripcion" text,
	"estado" smallint,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "solicitud_traspaso_inventario"
(
	"solicitud_traspaso_inventarioID" Integer NOT NULL
)
;

CREATE TABLE "servicio"
(
	"id" bigint NOT NULL DEFAULT nextval(('"servicio_id_seq"'::text)::regclass),
	"nombre" text,
	"descripcion" text,
	"precio" decimal(20,2),
	"fecha_registro" date,
	"fecha_modificacion" date,
	"estado" smallint,
	"tipo_servicio_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "salida_inventario_venta"
(
	"id" bigint NOT NULL DEFAULT nextval(('"salida_inventario_venta_id_seq"'::text)::regclass),
	"venta_id" bigint NOT NULL,
	"salida_inventario_id" bigint NOT NULL
)
;

CREATE TABLE "salida_inventario"
(
	"id" bigint NOT NULL DEFAULT nextval(('"salida_inventario_id_seq"'::text)::regclass),
	"fecha_registro" date,
	"fecha_modificacion" date,
	"sincronizado" smallint,
	"estado" smallint,
	"tipo_salida_inventario_id" bigint NOT NULL
)
;

CREATE TABLE "detalle_salida_inventario"
(
	"id" bigint NOT NULL DEFAULT nextval(('"detalle_salida_inventario_id_seq"'::text)::regclass),
	"cantidad" decimal(20,2),
	"precio_costo" decimal(20,2),
	"precio_venta" decimal(20,2),
	"salida_inventario_id" bigint NOT NULL
)
;

CREATE TABLE "recepcion"
(
	"id" bigint NOT NULL DEFAULT nextval(('"recepcion_id_seq"'::text)::regclass),
	"codigo_recepcion" varchar(50)	,
	"codigo_seguridad" varchar(50)	,
	"garantia" smallint,
	"observacion_cliente" text,
	"observacion_recepcion" text,
	"prioridad" smallint,
	"numero_ticket" varchar(50)	,
	"fecha_registro" date,
	"fecha_modificacion" date,
	"monto_total" decimal(20,2),
	"estado" smallint,
	"equipo_cliente_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "proveedor"
(
	"id" bigint NOT NULL DEFAULT nextval(('"proveedor_id_seq"'::text)::regclass),
	"nombre" text,
	"nit" text,
	"direccion" text,
	"telefono" text,
	"contacto" text,
	"estado" smallint
)
;

CREATE TABLE "producto"
(
	"id" bigint NOT NULL DEFAULT nextval(('"producto_id_seq"'::text)::regclass),
	"codigo" text,
	"nombre_comercial" text,
	"nombre_generico" text,
	"medida" text,
	"precio_venta" decimal(20,2),
	"precio_compra" decimal(20,2),
	"fecha_registro" date,
	"fecha_modificacion" date,
	"estado" smallint,
	"categoria_id" bigint NOT NULL,
	"grupo_id" bigint NOT NULL,
	"modelo_id" bigint NOT NULL,
	"proveedor_id" bigint NOT NULL,
	"unidad_medida_id" bigint NOT NULL,
	"usuario_id" bigint
)
;

CREATE TABLE "precio_servicio_categoria"
(
	"id" bigint NOT NULL DEFAULT nextval(('"precio_servicio_categoria_id_seq"'::text)::regclass),
	"precio_servicio" decimal(20,2),
	"estado" smallint,
	"categoria_id" bigint NOT NULL,
	"servicio_id" bigint NOT NULL
)
;

CREATE TABLE "pago_trabajo"
(
	"id" bigint NOT NULL DEFAULT nextval(('"pago_trabajo_id_seq"'::text)::regclass),
	"fecha_registro" date,
	"monto_pago" decimal(20,2),
	"observacion" text,
	"estado" smallint,
	"orden_trabajo_id" bigint NOT NULL,
	"sucursal_id" bigint,
	"usuario_id" bigint
)
;

CREATE TABLE "orden_trabajo"
(
	"id" bigint NOT NULL DEFAULT nextval(('"orden_trabajo_id_seq"'::text)::regclass),
	"codigo_trabajo" varchar(50)	,
	"observacion" text,
	"monto_subtotal" decimal(20,2),
	"descuento" decimal(20,2),
	"monto_total" decimal(20,2),
	"monto_pagado" decimal(20,2),
	"monto_deuda" decimal(20,2),
	"monto_saldo" decimal(20,2),
	"fecha_registro" date,
	"fecha_modificacion" date,
	"estado_deuda" smallint,
	"estado_trabajo" smallint,
	"estado" smallint,
	"recepcion_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "modelo"
(
	"id" bigint NOT NULL DEFAULT nextval(('"modelo_id_seq"'::text)::regclass),
	"codigo" text,
	"nombre" text,
	"estado" smallint,
	"marca_id" bigint NOT NULL
)
;

CREATE TABLE "menu"
(
	"id" bigint NOT NULL DEFAULT nextval(('"menu_id_seq"'::text)::regclass),
	"parent" integer,
	"name" text,
	"icon" text,
	"slug" text,
	"number" integer
)
;

CREATE TABLE "marca"
(
	"id" bigint NOT NULL DEFAULT nextval(('"marca_id_seq"'::text)::regclass),
	"nombre" text,
	"codigo" text,
	"estado" smallint
)
;

CREATE TABLE "inventario"
(
	"id" bigint NOT NULL DEFAULT nextval(('"inventario_id_seq"'::text)::regclass),
	"codigo" varchar(50)	,
	"cantidad" decimal(20,2),
	"precio_compra" decimal(20,2),
	"precio_venta" decimal(20,2),
	"fecha_ingreso" date,
	"fecha_modificacion" date,
	"estado" smallint,
	"almacen_id" bigint NOT NULL,
	"ingreso_inventario_id" bigint NOT NULL
)
;

CREATE TABLE "ingreso_venta"
(
	"id" bigint NOT NULL DEFAULT nextval(('"ingreso_venta_id_seq"'::text)::regclass),
	"ingreso_id" bigint NOT NULL,
	"venta_id" bigint
)
;

CREATE TABLE "ingreso_inventario"
(
	"id" bigint NOT NULL DEFAULT nextval(('"ingreso_inventario_id_seq"'::text)::regclass),
	"nombre" text,
	"fecha_ingreso" date,
	"fecha_registro" date,
	"fecha_modificacion" bigint NOT NULL,
	"estado" smallint,
	"tipo_ingreso_inventario_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "ingreso_compra_producto"
(
	"id" bigint NOT NULL DEFAULT nextval(('"ingreso_compra_producto_id_seq"'::text)::regclass),
	"cantidad" decimal(20,2),
	"fecha_ingreso" date,
	"estado" smallint,
	"compra_id" bigint NOT NULL,
	"ingreso_inventario_id" bigint NOT NULL,
	"producto_id" bigint NOT NULL
)
;

CREATE TABLE "ingreso"
(
	"id" bigint NOT NULL DEFAULT nextval(('"ingreso_id_seq"'::text)::regclass),
	"nro_ingreso" varchar(50)	,
	"descripcion" text,
	"fecha_ingreso" date,
	"fecha_registro" date,
	"fecha_modificacion" date,
	"tipo_cambio" decimal(20,2),
	"monto" decimal(20,2),
	"sincronizado" smallint,
	"estado" smallint,
	"tipo_ingreso_egreso_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "impresora"
(
	"id" bigint NOT NULL DEFAULT nextval(('"impresora_id_seq"'::text)::regclass),
	"marca" text,
	"serial" text,
	"estado" smallint,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "imagen_recepcion"
(
	"id" bigint NOT NULL DEFAULT nextval(('"imagen_recepcion_id_seq"'::text)::regclass),
	"nombre" text,
	"url" text,
	"fecha_registro" date,
	"estado" smallint,
	"recepcion_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "historial_recepcion"
(
	"id" bigint NOT NULL DEFAULT nextval(('"historial_recepcion_id_seq"'::text)::regclass),
	"tipo_historial" smallint,
	"glosa" text,
	"fecha_recepcionada" date,
	"fecha_registrada" date,
	"fecha_modificacion" date,
	"estado" smallint,
	"recepcion_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "historial_orden_trabajo"
(
	"id" bigint NOT NULL DEFAULT nextval(('"historial_orden_trabajo_id_seq"'::text)::regclass),
	"tipo_historial" smallint NOT NULL,
	"descipcion" text,
	"fecha_registro" date,
	"estado" smallint,
	"orden_trabajo_id" bigint NOT NULL,
	"sucursal_id" bigint,
	"usuario_id" bigint
)
;

CREATE TABLE "historial_compra"
(
	"id" bigint NOT NULL DEFAULT nextval(('"historial_compra_id_seq"'::text)::regclass),
	"tipo_historial" smallint,
	"observacion" text,
	"fecha_registro" date,
	"estado" smallint,
	"compra_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "grupo"
(
	"id" bigint NOT NULL,
	"nombre" text,
	"descripcion" text,
	"estado" smallint
)
;

CREATE TABLE "falla_recepcion"
(
	"id" bigint NOT NULL DEFAULT nextval(('"falla_recepcion_id_seq"'::text)::regclass),
	"estado" smallint,
	"recepcion_id" bigint NOT NULL,
	"falla_id" bigint NOT NULL
)
;

CREATE TABLE "falla_orden_trabajo"
(
	"id" bigint NOT NULL DEFAULT nextval(('"falla_orden_trabajo_id_seq"'::text)::regclass),
	"estado" smallint,
	"falla_id" bigint NOT NULL,
	"orden_trabajo_id" bigint NOT NULL
)
;

CREATE TABLE "falla"
(
	"id" bigint NOT NULL DEFAULT nextval(('"falla_id_seq"'::text)::regclass),
	"nombre" text,
	"descripcion" text,
	"estado" smallint,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "factura_compra"
(
	"id" bigint NOT NULL DEFAULT nextval(('"factura_compra_id_seq"'::text)::regclass),
	"nit_proveedor" text,
	"nombre_proveedor" text,
	"nro_factura" integer,
	"nro_dui" text,
	"nro_autorizacion" text,
	"fecha_factura" date,
	"importe_total" decimal(20,2),
	"importe_no_sujeto_iva" decimal(20,2),
	"sub_total" decimal(20,2),
	"descuento" decimal(20,2),
	"importe_base_iva" decimal(20,2),
	"iva" decimal(20,2),
	"codigo_control" varchar(25)	,
	"tipo_compra" smallint,
	"proveedor_id" bigint
)
;

CREATE TABLE "factura"
(
	"id" bigint NOT NULL DEFAULT nextval(('"factura_id_seq"'::text)::regclass),
	"nro_factura" integer,
	"fecha" date,
	"nro_autorizacion" text,
	"nit_cliente" text,
	"nombre_cliente" text,
	"importe_total_venta" decimal(20,2),
	"importe_no_sujeto_iva" decimal(20,2),
	"operacion_excenta" decimal(20,2),
	"venta_tasa_cero" decimal(20,2),
	"subtotal" decimal(20,2),
	"descuento" decimal(20,2),
	"importe_base_iva" decimal(20,2),
	"iva" decimal(20,2),
	"codigo_control" varchar(25)	,
	"sincronizado" smallint,
	"estado" char(1)	,
	"dosificacion_id" bigint NOT NULL,
	"venta_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL
)
;

CREATE TABLE "equipo_cliente"
(
	"id" bigint NOT NULL DEFAULT nextval(('"equipo_cliente_id_seq"'::text)::regclass),
	"numero_telefono" text,
	"imei" text,
	"fecha_compra" date,
	"fecha_registro" date,
	"fecha_modificacion" date,
	"cliente_id" bigint NOT NULL,
	"producto_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario" bigint NOT NULL
)
;

CREATE TABLE "empresa"
(
	"id" bigint NOT NULL DEFAULT nextval(('"empresa_id_seq"'::text)::regclass),
	"nombre" text,
	"nit" text,
	"sitio_web" text,
	"estado" smallint
)
;

CREATE TABLE "egreso"
(
	"id" bigint NOT NULL DEFAULT nextval(('"egreso_id_seq"'::text)::regclass),
	"nro_egreso" varchar(50)	,
	"descripcion" text,
	"fecha_egreso" date,
	"fecha_registro" date,
	"fecha_modificada" date,
	"tipo_cambio" decimal(20,2),
	"monto" decimal(20,2),
	"sincronizado" smallint,
	"estado" smallint,
	"tipo_ingreso_egreso_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "dosificacion"
(
	"id" bigint NOT NULL DEFAULT nextval(('"dosificacion_id_seq"'::text)::regclass),
	"autorizacion" text,
	"nro_inicio" integer,
	"llave" text,
	"fecha_registro" date,
	"fecha_limite" date,
	"leyenda" text,
	"estado" smallint,
	"actividad_id" bigint NOT NULL,
	"impresora_id" bigint NOT NULL,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "detalle_venta"
(
	"id" bigint NOT NULL DEFAULT nextval(('"detalle_venta_id_seq"'::text)::regclass),
	"descripcion" text,
	"cantidad" decimal(20,2),
	"precio_costo" decimal(20,2),
	"precio_venta" decimal(20,2),
	"estado" smallint,
	"venta_id" bigint NOT NULL,
	"producto_id" bigint NOT NULL
)
;

CREATE TABLE "detalle_recepcion_servicio"
(
	"id" bigint NOT NULL DEFAULT nextval(('"detalle_recepcion_servicio_id_seq"'::text)::regclass),
	"precio_costo" decimal(20,2),
	"precio_venta" decimal(20,2),
	"estado" smallint,
	"recepcion_id" bigint NOT NULL,
	"servicio_id" bigint NOT NULL
)
;

CREATE TABLE "detalle_producto_trabajo"
(
	"id" bigint NOT NULL DEFAULT nextval(('"detalle_producto_trabajo_id_seq"'::text)::regclass),
	"estado" smallint,
	"orden_trabajo_id" bigint NOT NULL,
	"producto_id" bigint NOT NULL
)
;

CREATE TABLE "detalle_orden_trabajo_servicio"
(
	"id" bigint NOT NULL DEFAULT nextval(('"detalle_orden_trabajo_servicio_id_seq"'::text)::regclass),
	"precio_servicio" decimal(20,2),
	"observacion" text,
	"estado" smallint,
	"servicio_id" bigint NOT NULL,
	"orden_trabajo_id" bigint NOT NULL
)
;

CREATE TABLE "detalle_compra"
(
	"id" bigint NOT NULL DEFAULT nextval(('"detalle_compra_id_seq"'::text)::regclass),
	"precio_unitario" decimal(20,2),
	"costo_adicional" decimal(20,2),
	"costo_almacen" decimal(20,2),
	"precio_costo" decimal(20,2),
	"precio_venta" decimal(20,2),
	"cantidad" decimal(20,2),
	"cantidad_correcta" decimal(20,2),
	"cantidad_observada" decimal(20),
	"observacion" text,
	"fecha_control" date,
	"fecha_registro" date,
	"fecha_modificacion" date,
	"estado" smallint,
	"producto_id" bigint NOT NULL,
	"compra_id" bigint NOT NULL
)
;

CREATE TABLE "compra"
(
	"id" bigint NOT NULL DEFAULT nextval(('"compra_id_seq"'::text)::regclass),
	"tipo_compra" smallint,
	"nro_compra" varchar(50)	,
	"fecha_registro" date,
	"fecha_modificacion" date,
	"observacion" text,
	"monto_subtotal" decimal(20,2),
	"descuento_uno" decimal(20,2),
	"descuento_dos" decimal(20,2),
	"descuento_tres" decimal(20,2),
	"monto_total" decimal(20,2),
	"estado" smallint,
	"sucursal_id" bigint,
	"usuario_id" bigint
)
;

CREATE TABLE "cliente"
(
	"id" bigint NOT NULL DEFAULT nextval(('"cliente_id_seq"'::text)::regclass),
	"ci" text,
	"nit" text,
	"nombre" text,
	"telefono" text,
	"direccion" text,
	"email" text,
	"fecha_nacimiento" date,
	"fecha_registro" date,
	"fecha_modificacion" date,
	"sincronizado" smallint,
	"estado" smallint,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "categoria"
(
	"id" bigint NOT NULL DEFAULT nextval(('"categoria_id_seq"'::text)::regclass),
	"nombre" text,
	"descripcion" text,
	"estado" smallint
)
;

CREATE TABLE "almacen"
(
	"id" bigint NOT NULL DEFAULT nextval(('"almacen_id_seq"'::text)::regclass),
	"nombre" text,
	"descripcion" text,
	"direccion" text,
	"estado" smallint,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "actividad"
(
	"id" bigint NOT NULL DEFAULT nextval(('"actividad_id_seq"'::text)::regclass),
	"nombre" text,
	"telefono" text,
	"email" text,
	"tipo_impresion" smallint,
	"estado" smallint,
	"sucursal_id" bigint NOT NULL,
	"usuario_id" bigint NOT NULL
)
;

CREATE TABLE "acceso"
(
	"id" bigint NOT NULL DEFAULT nextval(('"acceso_id_seq"'::text)::regclass),
	"usuario_id" bigint NOT NULL,
	"menu_id" bigint NOT NULL
)
;

CREATE SEQUENCE "asignacion_grupo_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "venta_recepcion_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "venta_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "usuario_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "unidad_medida_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "tipo_servicio_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "tipo_salida_inventario_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "tipo_ingreso_inventario_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "tipo_ingreso_egreso_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "sucursal_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "solucion_recepcion_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "solucion_orden_trabajo_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "solucion_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "servicio_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "salida_inventario_venta_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "salida_inventario_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "detalle_salida_inventario_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "recepcion_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "proveedor_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "producto_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "precio_servicio_categoria_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "pago_trabajo_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "orden_trabajo_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "modelo_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "menu_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "marca_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "inventario_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "ingreso_venta_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "ingreso_inventario_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "ingreso_compra_producto_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "ingreso_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "impresora_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "imagen_recepcion_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "historial_recepcion_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "historial_orden_trabajo_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "historial_compra_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "falla_recepcion_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "falla_orden_trabajo_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "falla_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "factura_compra_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "factura_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "equipo_cliente_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "empresa_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "egreso_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "dosificacion_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "detalle_venta_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "detalle_recepcion_servicio_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "detalle_producto_trabajo_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "detalle_orden_trabajo_servicio_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "detalle_compra_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "compra_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "cliente_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "categoria_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "almacen_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "actividad_id_seq" INCREMENT 1 START 1
;

CREATE SEQUENCE "acceso_id_seq" INCREMENT 1 START 1
;

CREATE INDEX "IXFK_asignacion_grupo_grupo" ON "asignacion_grupo" ("grupo_padre_id" ASC)
;

CREATE INDEX "IXFK_asignacion_grupo_grupo_02" ON "asignacion_grupo" ("grupo_hijo_id" ASC)
;

ALTER TABLE "asignacion_grupo" ADD CONSTRAINT "PK_asignacion_grupo"
	PRIMARY KEY ("id")
;

ALTER TABLE "venta_recepcion" ADD CONSTRAINT "PK_venta_recepcion"
	PRIMARY KEY ("id")
;

ALTER TABLE "venta" ADD CONSTRAINT "PK_venta"
	PRIMARY KEY ("id")
;

ALTER TABLE "usuario" ADD CONSTRAINT "PK_usuario"
	PRIMARY KEY ("id")
;

ALTER TABLE "unidad_medida" ADD CONSTRAINT "PK_unidad_medida"
	PRIMARY KEY ("id")
;

ALTER TABLE "tipo_servicio" ADD CONSTRAINT "PK_tipo_servicio"
	PRIMARY KEY ("id")
;

ALTER TABLE "tipo_salida_inventario" ADD CONSTRAINT "PK_tipo_salida_inventario"
	PRIMARY KEY ("id")
;

ALTER TABLE "tipo_ingreso_inventario" ADD CONSTRAINT "PK_tipo_ingreso_inventario"
	PRIMARY KEY ("id")
;

ALTER TABLE "tipo_ingreso_egreso" ADD CONSTRAINT "PK_tipo_ingreso_egreso"
	PRIMARY KEY ("id")
;

ALTER TABLE "sucursal" ADD CONSTRAINT "PK_sucursal"
	PRIMARY KEY ("id")
;

ALTER TABLE "solucion_recepcion" ADD CONSTRAINT "PK_solucion_recepcion"
	PRIMARY KEY ("id")
;

ALTER TABLE "solucion_orden_trabajo" ADD CONSTRAINT "PK_solucion_orden_trabajo"
	PRIMARY KEY ("id")
;

ALTER TABLE "solucion" ADD CONSTRAINT "PK_solucion"
	PRIMARY KEY ("id")
;

ALTER TABLE "servicio" ADD CONSTRAINT "PK_servicio"
	PRIMARY KEY ("id")
;

ALTER TABLE "salida_inventario_venta" ADD CONSTRAINT "PK_salida_inventario_venta"
	PRIMARY KEY ("id")
;

ALTER TABLE "salida_inventario" ADD CONSTRAINT "PK_salida_inventario"
	PRIMARY KEY ("id")
;

ALTER TABLE "detalle_salida_inventario" ADD CONSTRAINT "PK_detalle_salida_inventario"
	PRIMARY KEY ("id")
;

ALTER TABLE "recepcion" ADD CONSTRAINT "PK_recepcion"
	PRIMARY KEY ("id")
;

ALTER TABLE "proveedor" ADD CONSTRAINT "PK_proveedor"
	PRIMARY KEY ("id")
;

ALTER TABLE "producto" ADD CONSTRAINT "PK_producto"
	PRIMARY KEY ("id")
;

ALTER TABLE "precio_servicio_categoria" ADD CONSTRAINT "PK_precio_servicio_categoria"
	PRIMARY KEY ("id")
;

ALTER TABLE "pago_trabajo" ADD CONSTRAINT "PK_pago_trabajo"
	PRIMARY KEY ("id")
;

ALTER TABLE "orden_trabajo" ADD CONSTRAINT "PK_orden_trabajo"
	PRIMARY KEY ("id")
;

ALTER TABLE "modelo" ADD CONSTRAINT "PK_modelo"
	PRIMARY KEY ("id")
;

ALTER TABLE "menu" ADD CONSTRAINT "PK_menu"
	PRIMARY KEY ("id")
;

ALTER TABLE "marca" ADD CONSTRAINT "PK_marca"
	PRIMARY KEY ("id")
;

ALTER TABLE "inventario" ADD CONSTRAINT "PK_inventario"
	PRIMARY KEY ("id")
;

ALTER TABLE "ingreso_venta" ADD CONSTRAINT "PK_ingreso_venta"
	PRIMARY KEY ("id")
;

ALTER TABLE "ingreso_inventario" ADD CONSTRAINT "PK_ingreso_inventario"
	PRIMARY KEY ("id")
;

ALTER TABLE "ingreso_compra_producto" ADD CONSTRAINT "PK_ingreso_compra_producto"
	PRIMARY KEY ("id")
;

ALTER TABLE "ingreso" ADD CONSTRAINT "PK_ingreso"
	PRIMARY KEY ("id")
;

ALTER TABLE "impresora" ADD CONSTRAINT "PK_impresora"
	PRIMARY KEY ("id")
;

ALTER TABLE "imagen_recepcion" ADD CONSTRAINT "PK_imagen_recepcion"
	PRIMARY KEY ("id")
;

ALTER TABLE "historial_recepcion" ADD CONSTRAINT "PK_historial_recepcion"
	PRIMARY KEY ("id")
;

ALTER TABLE "historial_orden_trabajo" ADD CONSTRAINT "PK_historial_orden_trabajo"
	PRIMARY KEY ("id")
;

ALTER TABLE "historial_compra" ADD CONSTRAINT "PK_historial_compra"
	PRIMARY KEY ("id")
;

ALTER TABLE "grupo" ADD CONSTRAINT "PK_grupo"
	PRIMARY KEY ("id")
;

ALTER TABLE "falla_recepcion" ADD CONSTRAINT "PK_falla_recepcion"
	PRIMARY KEY ("id")
;

ALTER TABLE "falla_orden_trabajo" ADD CONSTRAINT "PK_falla_orden_trabajo"
	PRIMARY KEY ("id")
;

ALTER TABLE "falla" ADD CONSTRAINT "PK_falla"
	PRIMARY KEY ("id")
;

ALTER TABLE "factura_compra" ADD CONSTRAINT "PK_factura_compra"
	PRIMARY KEY ("id")
;

ALTER TABLE "factura" ADD CONSTRAINT "PK_factura"
	PRIMARY KEY ("id")
;

ALTER TABLE "equipo_cliente" ADD CONSTRAINT "PK_equipo_cliente"
	PRIMARY KEY ("id")
;

ALTER TABLE "empresa" ADD CONSTRAINT "PK_empresa"
	PRIMARY KEY ("id")
;

ALTER TABLE "egreso" ADD CONSTRAINT "PK_egreso"
	PRIMARY KEY ("id")
;

ALTER TABLE "dosificacion" ADD CONSTRAINT "PK_dosificacion"
	PRIMARY KEY ("id")
;

ALTER TABLE "detalle_venta" ADD CONSTRAINT "PK_detalle_venta"
	PRIMARY KEY ("id")
;

ALTER TABLE "detalle_recepcion_servicio" ADD CONSTRAINT "PK_detalle_recepcion_servicio"
	PRIMARY KEY ("id")
;

ALTER TABLE "detalle_producto_trabajo" ADD CONSTRAINT "PK_detalle_producto_trabajo"
	PRIMARY KEY ("id")
;

ALTER TABLE "detalle_orden_trabajo_servicio" ADD CONSTRAINT "PK_detalle_orden_trabajo_servicio"
	PRIMARY KEY ("id")
;

ALTER TABLE "detalle_compra" ADD CONSTRAINT "PK_detalle_compra"
	PRIMARY KEY ("id")
;

ALTER TABLE "compra" ADD CONSTRAINT "PK_compra"
	PRIMARY KEY ("id")
;

ALTER TABLE "cliente" ADD CONSTRAINT "PK_cliente"
	PRIMARY KEY ("id")
;

ALTER TABLE "categoria" ADD CONSTRAINT "PK_categoria"
	PRIMARY KEY ("id")
;

ALTER TABLE "almacen" ADD CONSTRAINT "PK_almacen"
	PRIMARY KEY ("id")
;

ALTER TABLE "actividad" ADD CONSTRAINT "PK_actividad"
	PRIMARY KEY ("id")
;

ALTER TABLE "acceso" ADD CONSTRAINT "PK_acceso"
	PRIMARY KEY ("id")
;

ALTER TABLE "asignacion_grupo" ADD CONSTRAINT "FK_asignacion_grupo_grupo"
	FOREIGN KEY ("grupo_padre_id") REFERENCES "grupo" ("id") ON DELETE No Action ON UPDATE No Action
;

ALTER TABLE "asignacion_grupo" ADD CONSTRAINT "FK_asignacion_grupo_grupo_02"
	FOREIGN KEY ("grupo_hijo_id") REFERENCES "grupo" ("id") ON DELETE No Action ON UPDATE No Action
;
