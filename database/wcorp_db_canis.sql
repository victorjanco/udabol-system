--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.3
-- Dumped by pg_dump version 9.6.5

-- Started on 2017-10-24 15:15:26

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 1 (class 3079 OID 12387)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2873 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 185 (class 1259 OID 29319)
-- Name: acceso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE acceso (
    id bigint DEFAULT nextval(('"acceso_id_seq"'::text)::regclass) NOT NULL,
    usuario_id bigint NOT NULL,
    menu_id bigint NOT NULL
);


ALTER TABLE acceso OWNER TO postgres;

--
-- TOC entry 248 (class 1259 OID 29690)
-- Name: acceso_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE acceso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE acceso_id_seq OWNER TO postgres;

--
-- TOC entry 186 (class 1259 OID 29323)
-- Name: actividad; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE actividad (
    id bigint DEFAULT nextval(('"actividad_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    estado smallint,
    usuario_id bigint NOT NULL
);


ALTER TABLE actividad OWNER TO postgres;

--
-- TOC entry 249 (class 1259 OID 29692)
-- Name: actividad_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE actividad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE actividad_id_seq OWNER TO postgres;

--
-- TOC entry 187 (class 1259 OID 29330)
-- Name: agrupa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE agrupa (
    id bigint DEFAULT nextval(('"agrupa_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    descripcion text,
    estado smallint
);


ALTER TABLE agrupa OWNER TO postgres;

--
-- TOC entry 250 (class 1259 OID 29694)
-- Name: agrupa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE agrupa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE agrupa_id_seq OWNER TO postgres;

--
-- TOC entry 188 (class 1259 OID 29337)
-- Name: almacen; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE almacen (
    id bigint DEFAULT nextval(('"almacen_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    descripcion text,
    direccion text,
    estado smallint,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    tipo_almacen_id bigint NOT NULL
);


ALTER TABLE almacen OWNER TO postgres;

--
-- TOC entry 251 (class 1259 OID 29696)
-- Name: almacen_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE almacen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE almacen_id_seq OWNER TO postgres;

--
-- TOC entry 189 (class 1259 OID 29344)
-- Name: asignacion_grupo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE asignacion_grupo (
    id bigint DEFAULT nextval(('"asignacion_grupo_id_seq"'::text)::regclass) NOT NULL,
    grupo_padre_id bigint NOT NULL,
    grupo_hijo_id bigint NOT NULL
);


ALTER TABLE asignacion_grupo OWNER TO postgres;

--
-- TOC entry 252 (class 1259 OID 29698)
-- Name: asignacion_grupo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE asignacion_grupo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE asignacion_grupo_id_seq OWNER TO postgres;

--
-- TOC entry 190 (class 1259 OID 29348)
-- Name: cargo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cargo (
    id bigint NOT NULL,
    descripcion character varying(30),
    estado smallint
);


ALTER TABLE cargo OWNER TO postgres;

--
-- TOC entry 191 (class 1259 OID 29351)
-- Name: categoria; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE categoria (
    id bigint DEFAULT nextval(('"categoria_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    descripcion text,
    estado smallint
);


ALTER TABLE categoria OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 29700)
-- Name: categoria_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE categoria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE categoria_id_seq OWNER TO postgres;

--
-- TOC entry 192 (class 1259 OID 29358)
-- Name: cliente; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cliente (
    id bigint DEFAULT nextval(('"cliente_id_seq"'::text)::regclass) NOT NULL,
    codigo_cliente character varying(20),
    nit text,
    nombre_factura text,
    ci text,
    nombre text,
    telefono1 text,
    telefono2 text,
    direccion text,
    email text,
    fecha_nacimiento date,
    fecha_registro date,
    fecha_modificacion date,
    ciudad text,
    sincronizado smallint,
    estado smallint,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE cliente OWNER TO postgres;

--
-- TOC entry 254 (class 1259 OID 29702)
-- Name: cliente_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cliente_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE cliente_id_seq OWNER TO postgres;

--
-- TOC entry 193 (class 1259 OID 29365)
-- Name: compra; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE compra (
    id bigint DEFAULT nextval(('"compra_id_seq"'::text)::regclass) NOT NULL,
    tipo_compra smallint,
    nro_compra character varying(50),
    fecha_registro date,
    fecha_modificacion date,
    observacion text,
    monto_subtotal numeric(20,2),
    descuento_uno numeric(20,2),
    descuento_dos numeric(20,2),
    descuento_tres numeric(20,2),
    monto_total numeric(20,2),
    estado smallint,
    sucursal_id bigint,
    usuario_id bigint,
    proveedor_id bigint
);


ALTER TABLE compra OWNER TO postgres;

--
-- TOC entry 255 (class 1259 OID 29704)
-- Name: compra_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE compra_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE compra_id_seq OWNER TO postgres;

--
-- TOC entry 194 (class 1259 OID 29372)
-- Name: detalle_compra; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE detalle_compra (
    id bigint DEFAULT nextval(('"detalle_compra_id_seq"'::text)::regclass) NOT NULL,
    precio_unitario numeric(20,2),
    cantidad numeric(20,2),
    monto_total numeric(20,2),
    costo_adicional numeric(20,2),
    costo_almacen numeric(20,2),
    precio_costo numeric(20,2),
    precio_venta numeric(20,2),
    precio_venta_mayor numeric(20,2),
    cantidad_correcta numeric(20,2),
    cantidad_observada numeric(20,0),
    observacion text,
    fecha_control date,
    fecha_registro date,
    fecha_modificacion date,
    estado smallint,
    producto_id bigint NOT NULL,
    compra_id bigint NOT NULL,
    cantidad_ingresada_inventario numeric DEFAULT 0
);


ALTER TABLE detalle_compra OWNER TO postgres;

--
-- TOC entry 256 (class 1259 OID 29706)
-- Name: detalle_compra_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_compra_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_compra_id_seq OWNER TO postgres;

--
-- TOC entry 195 (class 1259 OID 29379)
-- Name: detalle_orden_trabajo_servicio; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE detalle_orden_trabajo_servicio (
    id bigint DEFAULT nextval(('"detalle_orden_trabajo_servicio_id_seq"'::text)::regclass) NOT NULL,
    precio_servicio numeric(20,2),
    observacion text,
    estado smallint,
    servicio_id bigint NOT NULL,
    orden_trabajo_id bigint NOT NULL
);


ALTER TABLE detalle_orden_trabajo_servicio OWNER TO postgres;

--
-- TOC entry 257 (class 1259 OID 29708)
-- Name: detalle_orden_trabajo_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_orden_trabajo_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_orden_trabajo_servicio_id_seq OWNER TO postgres;

--
-- TOC entry 196 (class 1259 OID 29386)
-- Name: detalle_producto_trabajo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE detalle_producto_trabajo (
    id bigint DEFAULT nextval(('"detalle_producto_trabajo_id_seq"'::text)::regclass) NOT NULL,
    estado smallint,
    orden_trabajo_id bigint NOT NULL,
    producto_id bigint NOT NULL,
    cantidad numeric(20,6),
    precio_venta numeric(20,6)
);


ALTER TABLE detalle_producto_trabajo OWNER TO postgres;

--
-- TOC entry 258 (class 1259 OID 29710)
-- Name: detalle_producto_trabajo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_producto_trabajo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_producto_trabajo_id_seq OWNER TO postgres;

--
-- TOC entry 197 (class 1259 OID 29390)
-- Name: detalle_recepcion_servicio; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE detalle_recepcion_servicio (
    id bigint DEFAULT nextval(('"detalle_recepcion_servicio_id_seq"'::text)::regclass) NOT NULL,
    precio_costo numeric(20,2),
    precio_venta numeric(20,2),
    estado smallint,
    recepcion_id bigint NOT NULL,
    servicio_id bigint NOT NULL
);


ALTER TABLE detalle_recepcion_servicio OWNER TO postgres;

--
-- TOC entry 259 (class 1259 OID 29712)
-- Name: detalle_recepcion_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_recepcion_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_recepcion_servicio_id_seq OWNER TO postgres;

--
-- TOC entry 198 (class 1259 OID 29394)
-- Name: detalle_salida_inventario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE detalle_salida_inventario (
    id bigint DEFAULT nextval(('"detalle_salida_inventario_id_seq"'::text)::regclass) NOT NULL,
    cantidad numeric(20,2),
    precio_costo numeric(20,2),
    precio_venta numeric(20,2),
    salida_inventario_id bigint NOT NULL
);


ALTER TABLE detalle_salida_inventario OWNER TO postgres;

--
-- TOC entry 260 (class 1259 OID 29714)
-- Name: detalle_salida_inventario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_salida_inventario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_salida_inventario_id_seq OWNER TO postgres;

--
-- TOC entry 199 (class 1259 OID 29398)
-- Name: detalle_venta; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE detalle_venta (
    id bigint DEFAULT nextval(('"detalle_venta_id_seq"'::text)::regclass) NOT NULL,
    descripcion text,
    cantidad numeric(20,2),
    precio_costo numeric(20,2),
    precio_venta numeric(20,2),
    estado smallint,
    venta_id bigint NOT NULL,
    producto_id bigint NOT NULL
);


ALTER TABLE detalle_venta OWNER TO postgres;

--
-- TOC entry 261 (class 1259 OID 29716)
-- Name: detalle_venta_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_venta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_venta_id_seq OWNER TO postgres;

--
-- TOC entry 310 (class 1259 OID 30219)
-- Name: detalle_virtual; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE detalle_virtual (
    id bigint DEFAULT nextval(('"detalle_virtual_id_seq"'::text)::regclass) NOT NULL,
    cantidad numeric(20,2) NOT NULL,
    sucursal_id bigint NOT NULL,
    almacen_id bigint NOT NULL,
    producto_id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    sesion_id bigint NOT NULL
);


ALTER TABLE detalle_virtual OWNER TO postgres;

--
-- TOC entry 311 (class 1259 OID 30223)
-- Name: detalle_virtual_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_virtual_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_virtual_id_seq OWNER TO postgres;

--
-- TOC entry 312 (class 1259 OID 30227)
-- Name: detalle_virtual_stock_total; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW detalle_virtual_stock_total AS
 SELECT detalle_virtual.sucursal_id,
    detalle_virtual.almacen_id,
    detalle_virtual.producto_id,
    sum(detalle_virtual.cantidad) AS stock_total
   FROM detalle_virtual
  GROUP BY detalle_virtual.sucursal_id, detalle_virtual.almacen_id, detalle_virtual.producto_id;


ALTER TABLE detalle_virtual_stock_total OWNER TO postgres;

--
-- TOC entry 200 (class 1259 OID 29405)
-- Name: dosificacion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE dosificacion (
    id bigint DEFAULT nextval(('"dosificacion_id_seq"'::text)::regclass) NOT NULL,
    autorizacion text,
    nro_inicio integer,
    llave text,
    fecha_registro date,
    fecha_limite date,
    leyenda text,
    estado smallint,
    actividad_id bigint NOT NULL,
    impresora_id bigint NOT NULL,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE dosificacion OWNER TO postgres;

--
-- TOC entry 262 (class 1259 OID 29718)
-- Name: dosificacion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE dosificacion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE dosificacion_id_seq OWNER TO postgres;

--
-- TOC entry 201 (class 1259 OID 29412)
-- Name: egreso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE egreso (
    id bigint DEFAULT nextval(('"egreso_id_seq"'::text)::regclass) NOT NULL,
    nro_egreso character varying(50),
    descripcion text,
    fecha_egreso date,
    fecha_registro date,
    fecha_modificada date,
    tipo_cambio numeric(20,2),
    monto numeric(20,2),
    sincronizado smallint,
    estado smallint,
    tipo_ingreso_egreso_id bigint NOT NULL,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE egreso OWNER TO postgres;

--
-- TOC entry 263 (class 1259 OID 29720)
-- Name: egreso_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE egreso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE egreso_id_seq OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 29419)
-- Name: equipo_cliente; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE equipo_cliente (
    id bigint DEFAULT nextval(('"equipo_cliente_id_seq"'::text)::regclass) NOT NULL,
    numero_telefono text,
    imei text,
    fecha_compra date,
    fecha_registro date,
    fecha_modificacion date,
    cliente_id bigint NOT NULL,
    producto_id bigint NOT NULL,
    sucursal_id bigint NOT NULL,
    usuario bigint NOT NULL
);


ALTER TABLE equipo_cliente OWNER TO postgres;

--
-- TOC entry 264 (class 1259 OID 29722)
-- Name: equipo_cliente_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE equipo_cliente_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE equipo_cliente_id_seq OWNER TO postgres;

--
-- TOC entry 203 (class 1259 OID 29426)
-- Name: factura; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE factura (
    id bigint DEFAULT nextval(('"factura_id_seq"'::text)::regclass) NOT NULL,
    nro_factura integer,
    fecha date,
    nro_autorizacion text,
    nit_cliente text,
    nombre_cliente text,
    importe_total_venta numeric(20,2),
    importe_no_sujeto_iva numeric(20,2),
    operacion_excenta numeric(20,2),
    venta_tasa_cero numeric(20,2),
    subtotal numeric(20,2),
    descuento numeric(20,2),
    importe_base_iva numeric(20,2),
    iva numeric(20,2),
    codigo_control character varying(25),
    sincronizado smallint,
    estado character(1),
    dosificacion_id bigint NOT NULL,
    venta_id bigint NOT NULL,
    sucursal_id bigint NOT NULL
);


ALTER TABLE factura OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 29433)
-- Name: factura_compra; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE factura_compra (
    id bigint DEFAULT nextval(('"factura_compra_id_seq"'::text)::regclass) NOT NULL,
    nit_proveedor text,
    nombre_proveedor text,
    nro_factura integer,
    nro_dui text,
    nro_autorizacion text,
    fecha_factura date,
    importe_total numeric(20,2),
    importe_no_sujeto_iva numeric(20,2),
    sub_total numeric(20,2),
    descuento numeric(20,2),
    importe_base_iva numeric(20,2),
    iva numeric(20,2),
    codigo_control character varying(25),
    tipo_compra smallint,
    proveedor_id bigint
);


ALTER TABLE factura_compra OWNER TO postgres;

--
-- TOC entry 266 (class 1259 OID 29726)
-- Name: factura_compra_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE factura_compra_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE factura_compra_id_seq OWNER TO postgres;

--
-- TOC entry 265 (class 1259 OID 29724)
-- Name: factura_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE factura_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE factura_id_seq OWNER TO postgres;

--
-- TOC entry 205 (class 1259 OID 29440)
-- Name: falla; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE falla (
    id bigint DEFAULT nextval(('"falla_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    descripcion text,
    estado smallint,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    agrupa_id bigint NOT NULL
);


ALTER TABLE falla OWNER TO postgres;

--
-- TOC entry 267 (class 1259 OID 29728)
-- Name: falla_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE falla_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE falla_id_seq OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 29447)
-- Name: falla_orden_trabajo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE falla_orden_trabajo (
    id bigint DEFAULT nextval(('"falla_orden_trabajo_id_seq"'::text)::regclass) NOT NULL,
    estado smallint,
    falla_id bigint NOT NULL,
    orden_trabajo_id bigint NOT NULL
);


ALTER TABLE falla_orden_trabajo OWNER TO postgres;

--
-- TOC entry 268 (class 1259 OID 29730)
-- Name: falla_orden_trabajo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE falla_orden_trabajo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE falla_orden_trabajo_id_seq OWNER TO postgres;

--
-- TOC entry 207 (class 1259 OID 29451)
-- Name: falla_recepcion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE falla_recepcion (
    id bigint DEFAULT nextval(('"falla_recepcion_id_seq"'::text)::regclass) NOT NULL,
    estado smallint,
    recepcion_id bigint NOT NULL,
    falla_id bigint NOT NULL
);


ALTER TABLE falla_recepcion OWNER TO postgres;

--
-- TOC entry 269 (class 1259 OID 29732)
-- Name: falla_recepcion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE falla_recepcion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE falla_recepcion_id_seq OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 29455)
-- Name: grupo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE grupo (
    id bigint DEFAULT nextval(('"grupo_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    descripcion text,
    estado smallint
);


ALTER TABLE grupo OWNER TO postgres;

--
-- TOC entry 270 (class 1259 OID 29734)
-- Name: grupo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE grupo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE grupo_id_seq OWNER TO postgres;

--
-- TOC entry 209 (class 1259 OID 29462)
-- Name: historial_compra; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE historial_compra (
    id bigint DEFAULT nextval(('"historial_compra_id_seq"'::text)::regclass) NOT NULL,
    tipo_historial smallint,
    observacion text,
    fecha_registro date,
    estado smallint,
    compra_id bigint NOT NULL,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE historial_compra OWNER TO postgres;

--
-- TOC entry 271 (class 1259 OID 29736)
-- Name: historial_compra_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE historial_compra_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE historial_compra_id_seq OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 29469)
-- Name: historial_orden_trabajo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE historial_orden_trabajo (
    id bigint DEFAULT nextval(('"historial_orden_trabajo_id_seq"'::text)::regclass) NOT NULL,
    tipo_historial smallint NOT NULL,
    descripcion text,
    fecha_registro date,
    estado smallint,
    orden_trabajo_id bigint NOT NULL,
    sucursal_id bigint,
    usuario_id bigint
);


ALTER TABLE historial_orden_trabajo OWNER TO postgres;

--
-- TOC entry 272 (class 1259 OID 29738)
-- Name: historial_orden_trabajo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE historial_orden_trabajo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE historial_orden_trabajo_id_seq OWNER TO postgres;

--
-- TOC entry 211 (class 1259 OID 29476)
-- Name: historial_recepcion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE historial_recepcion (
    id bigint DEFAULT nextval(('"historial_recepcion_id_seq"'::text)::regclass) NOT NULL,
    tipo_historial smallint,
    glosa text,
    fecha_recepcionada date,
    fecha_registro date,
    fecha_modificacion date,
    estado smallint,
    recepcion_id bigint NOT NULL,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE historial_recepcion OWNER TO postgres;

--
-- TOC entry 273 (class 1259 OID 29740)
-- Name: historial_recepcion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE historial_recepcion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE historial_recepcion_id_seq OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 29483)
-- Name: imagen_recepcion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE imagen_recepcion (
    id bigint DEFAULT nextval(('"imagen_recepcion_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    url text,
    fecha_registro date,
    estado smallint,
    recepcion_id bigint NOT NULL,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE imagen_recepcion OWNER TO postgres;

--
-- TOC entry 274 (class 1259 OID 29742)
-- Name: imagen_recepcion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE imagen_recepcion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE imagen_recepcion_id_seq OWNER TO postgres;

--
-- TOC entry 213 (class 1259 OID 29490)
-- Name: impresora; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE impresora (
    id bigint DEFAULT nextval(('"impresora_id_seq"'::text)::regclass) NOT NULL,
    marca text,
    serial text,
    estado smallint,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE impresora OWNER TO postgres;

--
-- TOC entry 275 (class 1259 OID 29744)
-- Name: impresora_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE impresora_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE impresora_id_seq OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 29497)
-- Name: ingreso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ingreso (
    id bigint DEFAULT nextval(('"ingreso_id_seq"'::text)::regclass) NOT NULL,
    nro_ingreso character varying(50),
    descripcion text,
    fecha_ingreso date,
    fecha_registro date,
    fecha_modificacion date,
    tipo_cambio numeric(20,2),
    monto numeric(20,2),
    sincronizado smallint,
    estado smallint,
    tipo_ingreso_egreso_id bigint NOT NULL,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE ingreso OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 29504)
-- Name: ingreso_compra_producto; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ingreso_compra_producto (
    id bigint DEFAULT nextval(('"ingreso_compra_producto_id_seq"'::text)::regclass) NOT NULL,
    cantidad numeric(20,2),
    fecha_ingreso date,
    estado smallint,
    compra_id bigint NOT NULL,
    ingreso_inventario_id bigint NOT NULL,
    producto_id bigint NOT NULL
);


ALTER TABLE ingreso_compra_producto OWNER TO postgres;

--
-- TOC entry 277 (class 1259 OID 29748)
-- Name: ingreso_compra_producto_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ingreso_compra_producto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ingreso_compra_producto_id_seq OWNER TO postgres;

--
-- TOC entry 276 (class 1259 OID 29746)
-- Name: ingreso_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ingreso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ingreso_id_seq OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 29508)
-- Name: ingreso_inventario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ingreso_inventario (
    id bigint DEFAULT nextval(('"ingreso_inventario_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    fecha_ingreso date,
    fecha_registro date,
    fecha_modificacion date NOT NULL,
    estado smallint,
    tipo_ingreso_inventario_id bigint NOT NULL,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE ingreso_inventario OWNER TO postgres;

--
-- TOC entry 278 (class 1259 OID 29750)
-- Name: ingreso_inventario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ingreso_inventario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ingreso_inventario_id_seq OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 29515)
-- Name: ingreso_venta; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ingreso_venta (
    id bigint DEFAULT nextval(('"ingreso_venta_id_seq"'::text)::regclass) NOT NULL,
    ingreso_id bigint NOT NULL,
    venta_id bigint
);


ALTER TABLE ingreso_venta OWNER TO postgres;

--
-- TOC entry 279 (class 1259 OID 29752)
-- Name: ingreso_venta_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ingreso_venta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ingreso_venta_id_seq OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 29519)
-- Name: inventario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE inventario (
    id bigint DEFAULT nextval(('"inventario_id_seq"'::text)::regclass) NOT NULL,
    codigo character varying(50),
    cantidad numeric(20,2),
    precio_compra numeric(20,2),
    precio_venta numeric(20,2),
    fecha_ingreso date,
    fecha_modificacion date,
    estado smallint,
    almacen_id bigint NOT NULL,
    ingreso_inventario_id bigint NOT NULL,
    producto_id bigint NOT NULL
);


ALTER TABLE inventario OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 29523)
-- Name: marca; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE marca (
    id bigint DEFAULT nextval(('"marca_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    codigo text,
    estado smallint
);


ALTER TABLE marca OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 29537)
-- Name: modelo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE modelo (
    id bigint DEFAULT nextval(('"modelo_id_seq"'::text)::regclass) NOT NULL,
    codigo text,
    nombre text,
    estado smallint,
    marca_id bigint NOT NULL
);


ALTER TABLE modelo OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 29562)
-- Name: producto; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE producto (
    id bigint DEFAULT nextval(('"producto_id_seq"'::text)::regclass) NOT NULL,
    codigo text,
    nombre_comercial text,
    nombre_generico text,
    dimension text,
    precio_compra numeric(20,2),
    precio_venta numeric(20,2),
    precio_venta_mayor numeric(20,2),
    fecha_registro date,
    fecha_modificacion date,
    estado smallint,
    categoria_id bigint NOT NULL,
    grupo_id bigint NOT NULL,
    modelo_id bigint NOT NULL,
    unidad_medida_id bigint NOT NULL,
    usuario_id bigint,
    tipo_producto_id bigint
);


ALTER TABLE producto OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 29619)
-- Name: sucursal; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE sucursal (
    id bigint DEFAULT nextval(('"sucursal_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    nombre_comercial text,
    direccion text,
    ciudad text,
    telefono character varying(70) NOT NULL,
    correo text NOT NULL,
    estado smallint
);


ALTER TABLE sucursal OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 29647)
-- Name: tipo_producto; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE tipo_producto (
    id bigint DEFAULT nextval(('"tipo_producto_id_seq"'::text)::regclass) NOT NULL,
    nombre character varying(128),
    descripcion character varying(255),
    estado smallint
);


ALTER TABLE tipo_producto OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 29665)
-- Name: unidad_medida; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE unidad_medida (
    id bigint DEFAULT nextval(('"unidad_medida_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    abreviatura character varying(20),
    estado smallint
);


ALTER TABLE unidad_medida OWNER TO postgres;

--
-- TOC entry 308 (class 1259 OID 30204)
-- Name: inventario_general; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW inventario_general AS
 SELECT s.id AS sucursal_id,
    s.nombre_comercial AS nombre_sucursal,
    a.id AS almacen_id,
    a.nombre AS nombre_almacen,
    m.id AS modelo_id,
    m.nombre AS nombre_modelo,
    ma.id AS marca_id,
    ma.nombre AS nombre_marca,
    g.id AS grupo_id,
    g.nombre AS nombre_grupo,
    t.id AS tipo_producto_id,
    t.nombre AS nombre_tipo_producto,
    u.id AS unidad_medida_id,
    u.nombre AS nombre_unidad_medida,
    p.id AS producto_id,
    p.codigo AS codigo_producto,
    p.codigo AS codigo_barra,
    p.nombre_comercial,
    p.nombre_generico,
    i.precio_compra,
    p.precio_venta,
    p.precio_venta_mayor,
    p.estado AS estado_producto,
    i.id AS inventario_id,
    i.codigo AS lote,
    i.cantidad AS stock,
    i.estado AS estado_inventario
   FROM inventario i,
    producto p,
    almacen a,
    sucursal s,
    modelo m,
    marca ma,
    grupo g,
    tipo_producto t,
    unidad_medida u
  WHERE ((i.producto_id = p.id) AND (i.almacen_id = a.id) AND (a.sucursal_id = s.id) AND (p.modelo_id = m.id) AND (m.marca_id = ma.id) AND (p.grupo_id = g.id) AND (p.tipo_producto_id = t.id) AND (p.unidad_medida_id = u.id));


ALTER TABLE inventario_general OWNER TO postgres;

--
-- TOC entry 280 (class 1259 OID 29754)
-- Name: inventario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE inventario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE inventario_id_seq OWNER TO postgres;

--
-- TOC entry 309 (class 1259 OID 30214)
-- Name: inventario_stock_general; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE inventario_stock_general (
    sucursal_id bigint,
    nombre_sucursal text,
    almacen_id bigint,
    nombre_almacen text,
    modelo_id bigint,
    nombre_modelo text,
    marca_id bigint,
    nombre_marca text,
    grupo_id bigint,
    nombre_grupo text,
    tipo_producto_id bigint,
    nombre_tipo_producto character varying(128),
    unidad_medida_id bigint,
    nombre_unidad_medida text,
    producto_id bigint,
    codigo_producto text,
    codigo_barra text,
    nombre_comercial text,
    nombre_generico text,
    precio_venta numeric(20,2),
    precio_venta_mayor numeric(20,2),
    estado_producto smallint,
    stock numeric
);

ALTER TABLE ONLY inventario_stock_general REPLICA IDENTITY NOTHING;


ALTER TABLE inventario_stock_general OWNER TO postgres;

--
-- TOC entry 281 (class 1259 OID 29756)
-- Name: marca_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE marca_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE marca_id_seq OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 29530)
-- Name: menu; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE menu (
    id bigint DEFAULT nextval(('"menu_id_seq"'::text)::regclass) NOT NULL,
    parent integer,
    name text,
    icon text,
    slug text,
    number integer
);


ALTER TABLE menu OWNER TO postgres;

--
-- TOC entry 282 (class 1259 OID 29758)
-- Name: menu_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE menu_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE menu_id_seq OWNER TO postgres;

--
-- TOC entry 283 (class 1259 OID 29760)
-- Name: modelo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE modelo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE modelo_id_seq OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 29544)
-- Name: orden_trabajo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE orden_trabajo (
    id bigint DEFAULT nextval(('"orden_trabajo_id_seq"'::text)::regclass) NOT NULL,
    codigo_trabajo character varying(50),
    observacion text,
    monto_subtotal numeric(20,2),
    descuento numeric(20,2),
    monto_total numeric(20,2),
    monto_pagado numeric(20,2),
    monto_deuda numeric(20,2),
    monto_saldo numeric(20,2),
    fecha_registro date,
    fecha_modificacion date,
    estado_deuda smallint,
    estado_trabajo smallint,
    progreso integer,
    estado smallint,
    recepcion_id bigint NOT NULL,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE orden_trabajo OWNER TO postgres;

--
-- TOC entry 284 (class 1259 OID 29762)
-- Name: orden_trabajo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE orden_trabajo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE orden_trabajo_id_seq OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 29551)
-- Name: pago_trabajo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE pago_trabajo (
    id bigint DEFAULT nextval(('"pago_trabajo_id_seq"'::text)::regclass) NOT NULL,
    fecha_registro date,
    monto_pago numeric(20,2),
    observacion text,
    estado smallint,
    orden_trabajo_id bigint NOT NULL,
    sucursal_id bigint,
    usuario_id bigint
);


ALTER TABLE pago_trabajo OWNER TO postgres;

--
-- TOC entry 285 (class 1259 OID 29764)
-- Name: pago_trabajo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE pago_trabajo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE pago_trabajo_id_seq OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 29558)
-- Name: precio_servicio_categoria; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE precio_servicio_categoria (
    id bigint DEFAULT nextval(('"precio_servicio_categoria_id_seq"'::text)::regclass) NOT NULL,
    precio_servicio numeric(20,2),
    estado smallint,
    categoria_id bigint NOT NULL,
    servicio_id bigint NOT NULL
);


ALTER TABLE precio_servicio_categoria OWNER TO postgres;

--
-- TOC entry 286 (class 1259 OID 29766)
-- Name: precio_servicio_categoria_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE precio_servicio_categoria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE precio_servicio_categoria_id_seq OWNER TO postgres;

--
-- TOC entry 287 (class 1259 OID 29768)
-- Name: producto_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE producto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE producto_id_seq OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 29569)
-- Name: producto_proveedor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE producto_proveedor (
    id bigint NOT NULL,
    producto_id bigint,
    proveedor_id bigint
);


ALTER TABLE producto_proveedor OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 29572)
-- Name: proveedor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE proveedor (
    id bigint DEFAULT nextval(('"proveedor_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    nit text,
    direccion text,
    telefono text,
    contacto text,
    estado smallint
);


ALTER TABLE proveedor OWNER TO postgres;

--
-- TOC entry 288 (class 1259 OID 29770)
-- Name: proveedor_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE proveedor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proveedor_id_seq OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 29579)
-- Name: recepcion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE recepcion (
    id bigint DEFAULT nextval(('"recepcion_id_seq"'::text)::regclass) NOT NULL,
    codigo_recepcion character varying(50),
    codigo_seguridad character varying(50),
    garantia smallint,
    accesorio_dispositivo text,
    observacion_recepcion text,
    prioridad smallint,
    numero_ticket character varying(50),
    fecha_registro date,
    fecha_modificacion date,
    monto_total numeric(20,2),
    galeria smallint,
    estado smallint,
    equipo_cliente_id bigint NOT NULL,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE recepcion OWNER TO postgres;

--
-- TOC entry 289 (class 1259 OID 29772)
-- Name: recepcion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE recepcion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE recepcion_id_seq OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 29586)
-- Name: salida_inventario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE salida_inventario (
    id bigint DEFAULT nextval(('"salida_inventario_id_seq"'::text)::regclass) NOT NULL,
    fecha_registro date,
    fecha_modificacion date,
    sincronizado smallint,
    estado smallint,
    tipo_salida_inventario_id bigint NOT NULL
);


ALTER TABLE salida_inventario OWNER TO postgres;

--
-- TOC entry 290 (class 1259 OID 29774)
-- Name: salida_inventario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE salida_inventario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE salida_inventario_id_seq OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 29590)
-- Name: salida_inventario_venta; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE salida_inventario_venta (
    id bigint DEFAULT nextval(('"salida_inventario_venta_id_seq"'::text)::regclass) NOT NULL,
    venta_id bigint NOT NULL,
    salida_inventario_id bigint NOT NULL
);


ALTER TABLE salida_inventario_venta OWNER TO postgres;

--
-- TOC entry 291 (class 1259 OID 29776)
-- Name: salida_inventario_venta_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE salida_inventario_venta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE salida_inventario_venta_id_seq OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 29594)
-- Name: servicio; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE servicio (
    id bigint DEFAULT nextval(('"servicio_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    descripcion text,
    precio numeric(20,2),
    fecha_registro date,
    fecha_modificacion date,
    estado smallint,
    tipo_servicio_id bigint NOT NULL,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE servicio OWNER TO postgres;

--
-- TOC entry 292 (class 1259 OID 29778)
-- Name: servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE servicio_id_seq OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 29601)
-- Name: solicitud_traspaso_inventario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE solicitud_traspaso_inventario (
    "solicitud_traspaso_inventarioID" integer NOT NULL
);


ALTER TABLE solicitud_traspaso_inventario OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 29604)
-- Name: solucion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE solucion (
    id bigint DEFAULT nextval(('"solucion_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    descripcion text,
    estado smallint,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    agrupa_id bigint NOT NULL
);


ALTER TABLE solucion OWNER TO postgres;

--
-- TOC entry 293 (class 1259 OID 29780)
-- Name: solucion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solucion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE solucion_id_seq OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 29611)
-- Name: solucion_orden_trabajo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE solucion_orden_trabajo (
    id bigint DEFAULT nextval(('"solucion_orden_trabajo_id_seq"'::text)::regclass) NOT NULL,
    estado smallint,
    orden_trabajo_id bigint NOT NULL,
    solucion_id bigint NOT NULL
);


ALTER TABLE solucion_orden_trabajo OWNER TO postgres;

--
-- TOC entry 294 (class 1259 OID 29782)
-- Name: solucion_orden_trabajo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solucion_orden_trabajo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE solucion_orden_trabajo_id_seq OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 29615)
-- Name: solucion_recepcion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE solucion_recepcion (
    id bigint DEFAULT nextval(('"solucion_recepcion_id_seq"'::text)::regclass) NOT NULL,
    estado smallint,
    recepcion_id bigint NOT NULL,
    solucion_id bigint NOT NULL
);


ALTER TABLE solucion_recepcion OWNER TO postgres;

--
-- TOC entry 295 (class 1259 OID 29784)
-- Name: solucion_recepcion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solucion_recepcion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE solucion_recepcion_id_seq OWNER TO postgres;

--
-- TOC entry 296 (class 1259 OID 29786)
-- Name: sucursal_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE sucursal_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE sucursal_id_seq OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 29626)
-- Name: tipo_almacen; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE tipo_almacen (
    id bigint DEFAULT nextval(('"tipo_almacen_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    descripcion text,
    estado smallint NOT NULL
);


ALTER TABLE tipo_almacen OWNER TO postgres;

--
-- TOC entry 297 (class 1259 OID 29788)
-- Name: tipo_almacen_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipo_almacen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tipo_almacen_id_seq OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 29633)
-- Name: tipo_ingreso_egreso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE tipo_ingreso_egreso (
    id bigint DEFAULT nextval(('"tipo_ingreso_egreso_id_seq"'::text)::regclass) NOT NULL,
    descripcion text,
    tipo smallint,
    estado smallint
);


ALTER TABLE tipo_ingreso_egreso OWNER TO postgres;

--
-- TOC entry 298 (class 1259 OID 29790)
-- Name: tipo_ingreso_egreso_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipo_ingreso_egreso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tipo_ingreso_egreso_id_seq OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 29640)
-- Name: tipo_ingreso_inventario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE tipo_ingreso_inventario (
    id bigint DEFAULT nextval(('"tipo_ingreso_inventario_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    decripcion text,
    fecha_registro date,
    url character varying(64),
    estado smallint
);


ALTER TABLE tipo_ingreso_inventario OWNER TO postgres;

--
-- TOC entry 299 (class 1259 OID 29792)
-- Name: tipo_ingreso_inventario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipo_ingreso_inventario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tipo_ingreso_inventario_id_seq OWNER TO postgres;

--
-- TOC entry 300 (class 1259 OID 29794)
-- Name: tipo_producto_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipo_producto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tipo_producto_id_seq OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 29651)
-- Name: tipo_salida_inventario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE tipo_salida_inventario (
    id bigint DEFAULT nextval(('"tipo_salida_inventario_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    descripcion text,
    fecha_registro date,
    estado smallint
);


ALTER TABLE tipo_salida_inventario OWNER TO postgres;

--
-- TOC entry 301 (class 1259 OID 29796)
-- Name: tipo_salida_inventario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipo_salida_inventario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tipo_salida_inventario_id_seq OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 29658)
-- Name: tipo_servicio; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE tipo_servicio (
    id bigint DEFAULT nextval(('"tipo_servicio_id_seq"'::text)::regclass) NOT NULL,
    nombre text,
    descripcion text,
    estado smallint
);


ALTER TABLE tipo_servicio OWNER TO postgres;

--
-- TOC entry 302 (class 1259 OID 29798)
-- Name: tipo_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipo_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tipo_servicio_id_seq OWNER TO postgres;

--
-- TOC entry 303 (class 1259 OID 29800)
-- Name: unidad_medida_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE unidad_medida_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE unidad_medida_id_seq OWNER TO postgres;

--
-- TOC entry 244 (class 1259 OID 29672)
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE usuario (
    id bigint DEFAULT nextval(('"usuario_id_seq"'::text)::regclass) NOT NULL,
    ci text,
    nombre text,
    telefono text,
    usuario text,
    clave text,
    estado smallint,
    cargo_id bigint NOT NULL
);


ALTER TABLE usuario OWNER TO postgres;

--
-- TOC entry 304 (class 1259 OID 29802)
-- Name: usuario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE usuario_id_seq OWNER TO postgres;

--
-- TOC entry 245 (class 1259 OID 29679)
-- Name: usuario_sucursal; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE usuario_sucursal (
    usuario_id bigint,
    sucursal_id bigint
);


ALTER TABLE usuario_sucursal OWNER TO postgres;

--
-- TOC entry 246 (class 1259 OID 29682)
-- Name: venta; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE venta (
    id bigint DEFAULT nextval(('"venta_id_seq"'::text)::regclass) NOT NULL,
    tipo_venta character varying(50),
    fecha_registro date,
    subtotal numeric(20,2),
    descuento numeric(20,2),
    total numeric(20,2),
    sincronizado smallint,
    cliente_id bigint NOT NULL,
    sucursal_id bigint NOT NULL,
    usuario_id bigint NOT NULL
);


ALTER TABLE venta OWNER TO postgres;

--
-- TOC entry 305 (class 1259 OID 29804)
-- Name: venta_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE venta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE venta_id_seq OWNER TO postgres;

--
-- TOC entry 247 (class 1259 OID 29686)
-- Name: venta_recepcion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE venta_recepcion (
    id bigint DEFAULT nextval(('"venta_recepcion_id_seq"'::text)::regclass) NOT NULL,
    recepcion_id bigint NOT NULL,
    venta_id bigint NOT NULL
);


ALTER TABLE venta_recepcion OWNER TO postgres;

--
-- TOC entry 306 (class 1259 OID 29806)
-- Name: venta_recepcion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE venta_recepcion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE venta_recepcion_id_seq OWNER TO postgres;

--
-- TOC entry 307 (class 1259 OID 30194)
-- Name: vista_orden_trabajo; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW vista_orden_trabajo AS
 SELECT r.id,
    ordn.codigo_trabajo,
    ordn.fecha_registro,
    sucu.nombre AS nombre_sucursal,
    c.nombre AS nombre_cliente,
    prod.nombre_comercial,
    eqc.imei,
    usr.usuario,
    ordn.estado_trabajo
   FROM recepcion r,
    cliente c,
    equipo_cliente eqc,
    orden_trabajo ordn,
    sucursal sucu,
    producto prod,
    usuario usr
  WHERE ((r.equipo_cliente_id = eqc.id) AND (eqc.cliente_id = c.id) AND (r.estado = 1) AND (r.id = ordn.recepcion_id) AND (sucu.id = ordn.sucursal_id) AND (prod.id = eqc.producto_id) AND (ordn.usuario_id = usr.id));


ALTER TABLE vista_orden_trabajo OWNER TO postgres;

--
-- TOC entry 2743 (class 0 OID 29319)
-- Dependencies: 185
-- Data for Name: acceso; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2874 (class 0 OID 0)
-- Dependencies: 248
-- Name: acceso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('acceso_id_seq', 1, false);


--
-- TOC entry 2744 (class 0 OID 29323)
-- Dependencies: 186
-- Data for Name: actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2875 (class 0 OID 0)
-- Dependencies: 249
-- Name: actividad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('actividad_id_seq', 1, false);


--
-- TOC entry 2745 (class 0 OID 29330)
-- Dependencies: 187
-- Data for Name: agrupa; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO agrupa (id, nombre, descripcion, estado) VALUES (2, 'Antenas', 'Todo lo relacionado con recepciones de señal te los dispositivos', 1);
INSERT INTO agrupa (id, nombre, descripcion, estado) VALUES (1, 'Pantalla', 'Todo lo relacionado con pantallas tactiles', 1);
INSERT INTO agrupa (id, nombre, descripcion, estado) VALUES (3, 'Configuracion de Internet', 'Configurar el dispositivo para que tenga internet', 1);


--
-- TOC entry 2876 (class 0 OID 0)
-- Dependencies: 250
-- Name: agrupa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('agrupa_id_seq', 3, true);


--
-- TOC entry 2746 (class 0 OID 29337)
-- Dependencies: 188
-- Data for Name: almacen; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO almacen (id, nombre, descripcion, direccion, estado, sucursal_id, usuario_id, tipo_almacen_id) VALUES (4, 'Almacen D', 'Ninguna', 'S/D', 0, 1, 1, 1);
INSERT INTO almacen (id, nombre, descripcion, direccion, estado, sucursal_id, usuario_id, tipo_almacen_id) VALUES (6, 'Almacen Trinidad Sansung', 'Almacen Trinidad Sansung', 'trinidad', 1, 2, 1, 2);
INSERT INTO almacen (id, nombre, descripcion, direccion, estado, sucursal_id, usuario_id, tipo_almacen_id) VALUES (1, 'Almacen Apple', 'Almacen Apple', 'S D', 1, 1, 1, 1);
INSERT INTO almacen (id, nombre, descripcion, direccion, estado, sucursal_id, usuario_id, tipo_almacen_id) VALUES (2, 'Almacen Sansung', 'Almacen Sansung', 'Almacen Sansung', 1, 1, 1, 2);
INSERT INTO almacen (id, nombre, descripcion, direccion, estado, sucursal_id, usuario_id, tipo_almacen_id) VALUES (3, 'Almacen Huawei', 'Almacen Huawei', 'Almacen Huawei', 1, 1, 1, 3);


--
-- TOC entry 2877 (class 0 OID 0)
-- Dependencies: 251
-- Name: almacen_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('almacen_id_seq', 6, true);


--
-- TOC entry 2747 (class 0 OID 29344)
-- Dependencies: 189
-- Data for Name: asignacion_grupo; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2878 (class 0 OID 0)
-- Dependencies: 252
-- Name: asignacion_grupo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('asignacion_grupo_id_seq', 1, false);


--
-- TOC entry 2748 (class 0 OID 29348)
-- Dependencies: 190
-- Data for Name: cargo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cargo (id, descripcion, estado) VALUES (1, 'Recepcionista', 1);


--
-- TOC entry 2749 (class 0 OID 29351)
-- Dependencies: 191
-- Data for Name: categoria; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO categoria (id, nombre, descripcion, estado) VALUES (1, 'Categoria A1', '', 1);
INSERT INTO categoria (id, nombre, descripcion, estado) VALUES (2, 'Categoria B1', '', 1);
INSERT INTO categoria (id, nombre, descripcion, estado) VALUES (3, 'Categoria C1', '', 1);
INSERT INTO categoria (id, nombre, descripcion, estado) VALUES (4, 'Categoria D1', '', 1);


--
-- TOC entry 2879 (class 0 OID 0)
-- Dependencies: 253
-- Name: categoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('categoria_id_seq', 1, true);


--
-- TOC entry 2750 (class 0 OID 29358)
-- Dependencies: 192
-- Data for Name: cliente; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO cliente (id, codigo_cliente, nit, nombre_factura, ci, nombre, telefono1, telefono2, direccion, email, fecha_nacimiento, fecha_registro, fecha_modificacion, ciudad, sincronizado, estado, sucursal_id, usuario_id) VALUES (1, '1', '0', NULL, '0', 'S/N', '0', '', '', '', '2017-08-30', '2017-08-30', '2017-08-30', NULL, 0, 1, 1, 1);
INSERT INTO cliente (id, codigo_cliente, nit, nombre_factura, ci, nombre, telefono1, telefono2, direccion, email, fecha_nacimiento, fecha_registro, fecha_modificacion, ciudad, sincronizado, estado, sucursal_id, usuario_id) VALUES (2, '2', '0', NULL, '0', 'ANULADO', '0', '', '', '', '2017-08-30', '2017-08-30', '2017-08-30', NULL, 0, 1, 1, 1);
INSERT INTO cliente (id, codigo_cliente, nit, nombre_factura, ci, nombre, telefono1, telefono2, direccion, email, fecha_nacimiento, fecha_registro, fecha_modificacion, ciudad, sincronizado, estado, sucursal_id, usuario_id) VALUES (3, '3', '6094043', 'Ariel Alejandro Gomez Chavez', '6565652', 'Manuel Becerra', '695522121', '', 'calle florida', 'maria@hotmail.com', '2017-08-30', '2017-08-30', '2017-10-06', 'Potosi', 0, 1, 1, 1);
INSERT INTO cliente (id, codigo_cliente, nit, nombre_factura, ci, nombre, telefono1, telefono2, direccion, email, fecha_nacimiento, fecha_registro, fecha_modificacion, ciudad, sincronizado, estado, sucursal_id, usuario_id) VALUES (4, '4', '6094043', 'Ariel Alejandro Gomez Chavez', '609400404', 'Ariel Alejandro Chavez', '731454451', '721223656', 'asdasdas', 'ariel@gmail.com', '2017-09-01', '2017-09-01', '2017-10-06', 'Santa Cruz', 0, 1, 1, 1);
INSERT INTO cliente (id, codigo_cliente, nit, nombre_factura, ci, nombre, telefono1, telefono2, direccion, email, fecha_nacimiento, fecha_registro, fecha_modificacion, ciudad, sincronizado, estado, sucursal_id, usuario_id) VALUES (6, NULL, '6094044', 'Ariel Gomez Ch.', '654654', 'asds', '54', '4654', 'asdasdas', 'ariel@gmail.com', '2017-10-06', '2017-10-06', '2017-10-06', 'Santa Cruz', 0, 1, 1, 1);
INSERT INTO cliente (id, codigo_cliente, nit, nombre_factura, ci, nombre, telefono1, telefono2, direccion, email, fecha_nacimiento, fecha_registro, fecha_modificacion, ciudad, sincronizado, estado, sucursal_id, usuario_id) VALUES (8, NULL, '159159159', 'Brenda Nazario', '989898', 'Brenda Nazario', '8798788', '7987858', 'La catedral #5', 'brenda@gmail.com', '2017-10-12', '2017-10-12', '2017-10-12', 'Santa Cruz', 0, 1, 1, 1);
INSERT INTO cliente (id, codigo_cliente, nit, nombre_factura, ci, nombre, telefono1, telefono2, direccion, email, fecha_nacimiento, fecha_registro, fecha_modificacion, ciudad, sincronizado, estado, sucursal_id, usuario_id) VALUES (10, NULL, '', '', '11', '1', '21', '4', '215345', 'ariel@gmail.com', '2017-10-13', '2017-10-13', '2017-10-13', 'Santa Cruz', 0, 1, 1, 1);
INSERT INTO cliente (id, codigo_cliente, nit, nombre_factura, ci, nombre, telefono1, telefono2, direccion, email, fecha_nacimiento, fecha_registro, fecha_modificacion, ciudad, sincronizado, estado, sucursal_id, usuario_id) VALUES (11, NULL, '6094043', 'Ariel Alejandro Gomez Chavez', '6094043', 'Ariel', '7545645', '754545', 'Barrio Militar, Av. Ejercito a 20 mts del 2do Anillo', 'ariel@gmail.com', '2017-10-20', '2017-10-20', '2017-10-20', 'Santa Cruz', 0, 1, 1, 1);


--
-- TOC entry 2880 (class 0 OID 0)
-- Dependencies: 254
-- Name: cliente_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cliente_id_seq', 11, true);


--
-- TOC entry 2751 (class 0 OID 29365)
-- Dependencies: 193
-- Data for Name: compra; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO compra (id, tipo_compra, nro_compra, fecha_registro, fecha_modificacion, observacion, monto_subtotal, descuento_uno, descuento_dos, descuento_tres, monto_total, estado, sucursal_id, usuario_id, proveedor_id) VALUES (1, 10, '32123123', '2017-09-01', '2017-09-01', '1312312safs', 168.00, 0.00, 0.00, 0.00, 168.00, 2, 1, 1, 1);
INSERT INTO compra (id, tipo_compra, nro_compra, fecha_registro, fecha_modificacion, observacion, monto_subtotal, descuento_uno, descuento_dos, descuento_tres, monto_total, estado, sucursal_id, usuario_id, proveedor_id) VALUES (2, 10, '1231231', '2017-09-04', '2017-09-04', 'aaaaaaaaaaaaaaaa', 0.00, 0.00, 0.00, 0.00, 0.00, 2, 1, 1, 1);
INSERT INTO compra (id, tipo_compra, nro_compra, fecha_registro, fecha_modificacion, observacion, monto_subtotal, descuento_uno, descuento_dos, descuento_tres, monto_total, estado, sucursal_id, usuario_id, proveedor_id) VALUES (3, 10, '3444', '2017-09-28', '2017-09-28', 'probando eliminar', 1400000.00, 0.00, 0.00, 0.00, 1400000.00, 1, 1, 1, NULL);


--
-- TOC entry 2881 (class 0 OID 0)
-- Dependencies: 255
-- Name: compra_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('compra_id_seq', 3, true);


--
-- TOC entry 2752 (class 0 OID 29372)
-- Dependencies: 194
-- Data for Name: detalle_compra; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO detalle_compra (id, precio_unitario, cantidad, monto_total, costo_adicional, costo_almacen, precio_costo, precio_venta, precio_venta_mayor, cantidad_correcta, cantidad_observada, observacion, fecha_control, fecha_registro, fecha_modificacion, estado, producto_id, compra_id, cantidad_ingresada_inventario) VALUES (4, 14.00, 500.00, NULL, 12.00, 111.00, 24.25, 1000.00, NULL, 400.00, 100, NULL, '2017-09-01', '2017-09-01', '2017-09-01', 2, 1, 1, 400);
INSERT INTO detalle_compra (id, precio_unitario, cantidad, monto_total, costo_adicional, costo_almacen, precio_costo, precio_venta, precio_venta_mayor, cantidad_correcta, cantidad_observada, observacion, fecha_control, fecha_registro, fecha_modificacion, estado, producto_id, compra_id, cantidad_ingresada_inventario) VALUES (5, 0.00, 700.00, NULL, 25.00, 25.00, 25.00, 5500.00, NULL, 700.00, 0, NULL, '2017-09-01', '2017-09-01', '2017-09-01', 2, 3, 1, 700);
INSERT INTO detalle_compra (id, precio_unitario, cantidad, monto_total, costo_adicional, costo_almacen, precio_costo, precio_venta, precio_venta_mayor, cantidad_correcta, cantidad_observada, observacion, fecha_control, fecha_registro, fecha_modificacion, estado, producto_id, compra_id, cantidad_ingresada_inventario) VALUES (6, 0.00, 450.00, NULL, 0.00, 0.00, 0.00, 5500.00, NULL, 450.00, 0, NULL, '2017-09-04', '2017-09-04', '2017-09-04', 2, 3, 2, 450);
INSERT INTO detalle_compra (id, precio_unitario, cantidad, monto_total, costo_adicional, costo_almacen, precio_costo, precio_venta, precio_venta_mayor, cantidad_correcta, cantidad_observada, observacion, fecha_control, fecha_registro, fecha_modificacion, estado, producto_id, compra_id, cantidad_ingresada_inventario) VALUES (7, 500.00, 1500.00, NULL, 0.00, 0.00, 500.00, 1000.00, NULL, NULL, NULL, NULL, '2017-09-28', '2017-09-28', '2017-09-28', 1, 1, 3, 0);
INSERT INTO detalle_compra (id, precio_unitario, cantidad, monto_total, costo_adicional, costo_almacen, precio_costo, precio_venta, precio_venta_mayor, cantidad_correcta, cantidad_observada, observacion, fecha_control, fecha_registro, fecha_modificacion, estado, producto_id, compra_id, cantidad_ingresada_inventario) VALUES (8, 2500.00, 100.00, NULL, 0.00, 0.00, 2500.00, 3550.00, NULL, NULL, NULL, NULL, '2017-09-28', '2017-09-28', '2017-09-28', 1, 6, 3, 0);
INSERT INTO detalle_compra (id, precio_unitario, cantidad, monto_total, costo_adicional, costo_almacen, precio_costo, precio_venta, precio_venta_mayor, cantidad_correcta, cantidad_observada, observacion, fecha_control, fecha_registro, fecha_modificacion, estado, producto_id, compra_id, cantidad_ingresada_inventario) VALUES (9, 800.00, 500.00, NULL, 0.00, 0.00, 800.00, 5500.00, NULL, NULL, NULL, NULL, '2017-09-28', '2017-09-28', '2017-09-28', 1, 3, 3, 0);


--
-- TOC entry 2882 (class 0 OID 0)
-- Dependencies: 256
-- Name: detalle_compra_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_compra_id_seq', 9, true);


--
-- TOC entry 2753 (class 0 OID 29379)
-- Dependencies: 195
-- Data for Name: detalle_orden_trabajo_servicio; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO detalle_orden_trabajo_servicio (id, precio_servicio, observacion, estado, servicio_id, orden_trabajo_id) VALUES (3, 700.00, '700 yenes', 1, 2, 11);


--
-- TOC entry 2883 (class 0 OID 0)
-- Dependencies: 257
-- Name: detalle_orden_trabajo_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_orden_trabajo_servicio_id_seq', 3, true);


--
-- TOC entry 2754 (class 0 OID 29386)
-- Dependencies: 196
-- Data for Name: detalle_producto_trabajo; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2884 (class 0 OID 0)
-- Dependencies: 258
-- Name: detalle_producto_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_producto_trabajo_id_seq', 1, false);


--
-- TOC entry 2755 (class 0 OID 29390)
-- Dependencies: 197
-- Data for Name: detalle_recepcion_servicio; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (1, 500.00, 500.00, 0, 2, 2);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (2, 50.00, 50.00, 0, 2, 1);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (3, 500.00, 500.00, 0, 3, 2);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (4, 50.00, 50.00, 0, 3, 1);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (5, 50.00, 50.00, 0, 6, 1);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (6, 500.00, 500.00, 0, 6, 2);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (7, 50.00, 50.00, 0, 7, 1);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (8, 50.00, 50.00, 0, 7, 1);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (9, 500.00, 500.00, 0, 8, 2);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (10, 500.00, 500.00, 0, 8, 2);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (11, 500.00, 500.00, 0, 9, 2);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (12, 50.00, 50.00, 0, 9, 1);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (18, 50.00, 50.00, 0, 12, 1);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (19, 500.00, 500.00, 0, 12, 2);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (20, 50.00, 70.00, 0, 12, 1);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (63, 500.00, 500.00, 0, 13, 2);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (64, 50.00, 708.00, 0, 11, 1);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (65, 50.00, 750.00, 0, 16, 1);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (66, 50.00, 363.00, 0, 17, 1);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (67, 500.00, 1500.00, 0, 17, 2);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (68, 50.00, 693.00, 0, 18, 1);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (69, 500.00, 500.00, 0, 18, 2);
INSERT INTO detalle_recepcion_servicio (id, precio_costo, precio_venta, estado, recepcion_id, servicio_id) VALUES (70, 500.00, 748.00, 0, 19, 2);


--
-- TOC entry 2885 (class 0 OID 0)
-- Dependencies: 259
-- Name: detalle_recepcion_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_recepcion_servicio_id_seq', 70, true);


--
-- TOC entry 2756 (class 0 OID 29394)
-- Dependencies: 198
-- Data for Name: detalle_salida_inventario; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2886 (class 0 OID 0)
-- Dependencies: 260
-- Name: detalle_salida_inventario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_salida_inventario_id_seq', 1, false);


--
-- TOC entry 2757 (class 0 OID 29398)
-- Dependencies: 199
-- Data for Name: detalle_venta; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2887 (class 0 OID 0)
-- Dependencies: 261
-- Name: detalle_venta_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_venta_id_seq', 1, false);


--
-- TOC entry 2865 (class 0 OID 30219)
-- Dependencies: 310
-- Data for Name: detalle_virtual; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2888 (class 0 OID 0)
-- Dependencies: 311
-- Name: detalle_virtual_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_virtual_id_seq', 1, false);


--
-- TOC entry 2758 (class 0 OID 29405)
-- Dependencies: 200
-- Data for Name: dosificacion; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2889 (class 0 OID 0)
-- Dependencies: 262
-- Name: dosificacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('dosificacion_id_seq', 1, false);


--
-- TOC entry 2759 (class 0 OID 29412)
-- Dependencies: 201
-- Data for Name: egreso; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2890 (class 0 OID 0)
-- Dependencies: 263
-- Name: egreso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('egreso_id_seq', 1, false);


--
-- TOC entry 2760 (class 0 OID 29419)
-- Dependencies: 202
-- Data for Name: equipo_cliente; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO equipo_cliente (id, numero_telefono, imei, fecha_compra, fecha_registro, fecha_modificacion, cliente_id, producto_id, sucursal_id, usuario) VALUES (1, '721321321', '121465454', '2017-01-01', '2017-09-08', '2017-09-08', 3, 3, 1, 1);
INSERT INTO equipo_cliente (id, numero_telefono, imei, fecha_compra, fecha_registro, fecha_modificacion, cliente_id, producto_id, sucursal_id, usuario) VALUES (2, '78744', '45464515', '2018-01-01', '2017-09-08', '2017-09-08', 4, 3, 1, 1);
INSERT INTO equipo_cliente (id, numero_telefono, imei, fecha_compra, fecha_registro, fecha_modificacion, cliente_id, producto_id, sucursal_id, usuario) VALUES (3, '698556465', '123121054545', '2017-09-28', '2017-09-28', '2017-09-28', 4, 4, 1, 1);
INSERT INTO equipo_cliente (id, numero_telefono, imei, fecha_compra, fecha_registro, fecha_modificacion, cliente_id, producto_id, sucursal_id, usuario) VALUES (4, '1232131231231', '21312312111231231', '2017-10-10', '2017-10-10', '2017-10-10', 6, 4, 1, 1);
INSERT INTO equipo_cliente (id, numero_telefono, imei, fecha_compra, fecha_registro, fecha_modificacion, cliente_id, producto_id, sucursal_id, usuario) VALUES (5, '77777777', '77777777', '2017-10-12', '2017-10-12', '2017-10-12', 8, 3, 1, 1);


--
-- TOC entry 2891 (class 0 OID 0)
-- Dependencies: 264
-- Name: equipo_cliente_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('equipo_cliente_id_seq', 5, true);


--
-- TOC entry 2761 (class 0 OID 29426)
-- Dependencies: 203
-- Data for Name: factura; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2762 (class 0 OID 29433)
-- Dependencies: 204
-- Data for Name: factura_compra; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2892 (class 0 OID 0)
-- Dependencies: 266
-- Name: factura_compra_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('factura_compra_id_seq', 1, false);


--
-- TOC entry 2893 (class 0 OID 0)
-- Dependencies: 265
-- Name: factura_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('factura_id_seq', 1, false);


--
-- TOC entry 2763 (class 0 OID 29440)
-- Dependencies: 205
-- Data for Name: falla; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO falla (id, nombre, descripcion, estado, sucursal_id, usuario_id, agrupa_id) VALUES (3, 'No tiene internet', 'El dispositivo no cuenta con internet', 1, 1, 1, 3);
INSERT INTO falla (id, nombre, descripcion, estado, sucursal_id, usuario_id, agrupa_id) VALUES (4, 'Pantalla Negra', 'No prende la pantalla', 1, 1, 1, 1);
INSERT INTO falla (id, nombre, descripcion, estado, sucursal_id, usuario_id, agrupa_id) VALUES (5, 'Pantalla Quebrada', 'La pantalla del dispositivo', 1, 1, 1, 1);
INSERT INTO falla (id, nombre, descripcion, estado, sucursal_id, usuario_id, agrupa_id) VALUES (6, 'Cambien de Bateria', 'Cambio de Bateria', 1, 1, 1, 3);


--
-- TOC entry 2894 (class 0 OID 0)
-- Dependencies: 267
-- Name: falla_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('falla_id_seq', 6, true);


--
-- TOC entry 2764 (class 0 OID 29447)
-- Dependencies: 206
-- Data for Name: falla_orden_trabajo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (4, 1, 3, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (5, 1, 5, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (6, 1, 3, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (7, 1, 4, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (8, 1, 5, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (9, 1, 3, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (10, 1, 4, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (11, 1, 5, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (12, 1, 3, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (13, 1, 4, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (14, 1, 5, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (15, 1, 3, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (16, 1, 4, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (17, 1, 5, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (18, 1, 3, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (19, 1, 4, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (20, 1, 5, 11);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (21, 1, 4, 15);
INSERT INTO falla_orden_trabajo (id, estado, falla_id, orden_trabajo_id) VALUES (22, 1, 5, 15);


--
-- TOC entry 2895 (class 0 OID 0)
-- Dependencies: 268
-- Name: falla_orden_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('falla_orden_trabajo_id_seq', 22, true);


--
-- TOC entry 2765 (class 0 OID 29451)
-- Dependencies: 207
-- Data for Name: falla_recepcion; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (1, 0, 2, 4);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (2, 0, 2, 5);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (3, 0, 3, 4);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (4, 0, 3, 5);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (5, 0, 6, 4);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (6, 0, 7, 4);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (7, 0, 8, 4);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (8, 0, 9, 4);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (9, 0, 9, 5);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (14, 0, 12, 4);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (15, 0, 12, 5);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (66, 0, 13, 3);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (67, 0, 13, 4);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (68, 0, 13, 5);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (69, 0, 11, 4);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (70, 0, 16, 5);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (71, 0, 16, 6);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (72, 0, 17, 4);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (73, 0, 17, 5);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (74, 0, 18, 4);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (75, 0, 19, 4);
INSERT INTO falla_recepcion (id, estado, recepcion_id, falla_id) VALUES (76, 0, 19, 5);


--
-- TOC entry 2896 (class 0 OID 0)
-- Dependencies: 269
-- Name: falla_recepcion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('falla_recepcion_id_seq', 76, true);


--
-- TOC entry 2766 (class 0 OID 29455)
-- Dependencies: 208
-- Data for Name: grupo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO grupo (id, nombre, descripcion, estado) VALUES (1, 'Grupo 1', '', 1);
INSERT INTO grupo (id, nombre, descripcion, estado) VALUES (2, 'Grupo 2', '', 1);
INSERT INTO grupo (id, nombre, descripcion, estado) VALUES (3, 'Grupo 3', '', 1);
INSERT INTO grupo (id, nombre, descripcion, estado) VALUES (4, 'Grupo 4', '', 1);


--
-- TOC entry 2897 (class 0 OID 0)
-- Dependencies: 270
-- Name: grupo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('grupo_id_seq', 1, false);


--
-- TOC entry 2767 (class 0 OID 29462)
-- Dependencies: 209
-- Data for Name: historial_compra; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2898 (class 0 OID 0)
-- Dependencies: 271
-- Name: historial_compra_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('historial_compra_id_seq', 1, false);


--
-- TOC entry 2768 (class 0 OID 29469)
-- Dependencies: 210
-- Data for Name: historial_orden_trabajo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (1, 0, 'Ingreso del equipo para la revision.', '2017-09-08', 1, 2, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (2, 0, 'Ingreso del equipo para la revision.', '2017-09-08', 1, 3, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (3, 0, 'Ingreso del equipo para la revision.', '2017-09-20', 1, 4, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (4, 0, 'Ingreso del equipo para la revision.', '2017-09-20', 1, 5, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (5, 0, 'Ingreso del equipo para la revision.', '2017-09-20', 1, 6, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (6, 0, 'Ingreso del equipo para la revision.', '2017-09-20', 1, 7, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (7, 0, 'Ingreso del equipo para la revision.', '2017-09-21', 1, 8, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (8, 0, 'Ingreso del equipo para la revision.', '2017-09-22', 1, 9, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (9, 0, 'Ingreso del equipo para la revision.', '2017-09-22', 1, 10, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (10, 0, 'Ingreso del equipo para la revision.', '2017-09-28', 1, 11, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (11, 0, 'Ingreso del equipo para la revision.', '2017-10-10', 1, 12, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (12, 0, 'Ingreso del equipo para la revision.', '2017-10-10', 1, 13, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (13, 0, 'Ingreso del equipo para la revision.', '2017-10-10', 1, 14, 1, 1);
INSERT INTO historial_orden_trabajo (id, tipo_historial, descripcion, fecha_registro, estado, orden_trabajo_id, sucursal_id, usuario_id) VALUES (14, 0, 'Ingreso del equipo para la revision.', '2017-10-12', 1, 15, 1, 1);


--
-- TOC entry 2899 (class 0 OID 0)
-- Dependencies: 272
-- Name: historial_orden_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('historial_orden_trabajo_id_seq', 14, true);


--
-- TOC entry 2769 (class 0 OID 29476)
-- Dependencies: 211
-- Data for Name: historial_recepcion; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2900 (class 0 OID 0)
-- Dependencies: 273
-- Name: historial_recepcion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('historial_recepcion_id_seq', 1, false);


--
-- TOC entry 2770 (class 0 OID 29483)
-- Dependencies: 212
-- Data for Name: imagen_recepcion; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2901 (class 0 OID 0)
-- Dependencies: 274
-- Name: imagen_recepcion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('imagen_recepcion_id_seq', 1, false);


--
-- TOC entry 2771 (class 0 OID 29490)
-- Dependencies: 213
-- Data for Name: impresora; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2902 (class 0 OID 0)
-- Dependencies: 275
-- Name: impresora_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('impresora_id_seq', 1, false);


--
-- TOC entry 2772 (class 0 OID 29497)
-- Dependencies: 214
-- Data for Name: ingreso; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2773 (class 0 OID 29504)
-- Dependencies: 215
-- Data for Name: ingreso_compra_producto; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ingreso_compra_producto (id, cantidad, fecha_ingreso, estado, compra_id, ingreso_inventario_id, producto_id) VALUES (1, 400.00, '2017-09-12', 1, 1, 12, 1);
INSERT INTO ingreso_compra_producto (id, cantidad, fecha_ingreso, estado, compra_id, ingreso_inventario_id, producto_id) VALUES (2, 150.00, '2017-09-12', 1, 1, 13, 3);
INSERT INTO ingreso_compra_producto (id, cantidad, fecha_ingreso, estado, compra_id, ingreso_inventario_id, producto_id) VALUES (3, 700.00, '2017-09-12', 1, 1, 14, 3);
INSERT INTO ingreso_compra_producto (id, cantidad, fecha_ingreso, estado, compra_id, ingreso_inventario_id, producto_id) VALUES (4, 400.00, '2017-09-13', 1, 2, 15, 3);
INSERT INTO ingreso_compra_producto (id, cantidad, fecha_ingreso, estado, compra_id, ingreso_inventario_id, producto_id) VALUES (5, 25.00, '2017-09-13', 1, 2, 16, 3);
INSERT INTO ingreso_compra_producto (id, cantidad, fecha_ingreso, estado, compra_id, ingreso_inventario_id, producto_id) VALUES (6, 5.00, '2017-09-13', 1, 2, 17, 3);
INSERT INTO ingreso_compra_producto (id, cantidad, fecha_ingreso, estado, compra_id, ingreso_inventario_id, producto_id) VALUES (7, 20.00, '2017-09-13', 1, 2, 18, 3);


--
-- TOC entry 2903 (class 0 OID 0)
-- Dependencies: 277
-- Name: ingreso_compra_producto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ingreso_compra_producto_id_seq', 7, true);


--
-- TOC entry 2904 (class 0 OID 0)
-- Dependencies: 276
-- Name: ingreso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ingreso_id_seq', 1, false);


--
-- TOC entry 2774 (class 0 OID 29508)
-- Dependencies: 216
-- Data for Name: ingreso_inventario; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (2, 'Primera compra', '2017-08-10', '2017-08-29', '2017-08-29', 1, 1, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (3, 'PRB', '2017-09-04', '2017-09-04', '2017-09-04', 1, 1, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (4, 'PRB', '2017-09-04', '2017-09-04', '2017-09-04', 1, 1, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (5, 'PRB', '2017-09-04', '2017-09-04', '2017-09-04', 1, 1, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (6, 'aaaaaaaaaaaaaaaaaaa', '2017-09-21', '2017-09-04', '2017-09-04', 1, 1, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (7, 'PRuebaMM', '2017-09-13', '2017-09-04', '2017-09-04', 1, 1, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (8, 'ingreso mr', '2017-09-05', '2017-09-05', '2017-09-05', 1, 1, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (9, 'Compra 002', '2017-09-16', '2017-09-07', '2017-09-07', 1, 1, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (10, 'primer ingreso por compra3', '2017-09-12', '2017-09-12', '2017-09-12', 1, 1, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (11, 'Reposicion L3', '2017-09-13', '2017-09-12', '2017-09-12', 1, 1, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (12, 'Prueba de XD', '2017-09-14', '2017-09-13', '2017-09-13', 1, 2, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (13, 'XDDDD', '2017-09-15', '2017-09-13', '2017-09-13', 1, 2, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (14, 'ZZZZZZZ', '2017-09-13', '2017-09-13', '2017-09-13', 1, 2, 1, 1);
INSERT INTO ingreso_inventario (id, nombre, fecha_ingreso, fecha_registro, fecha_modificacion, estado, tipo_ingreso_inventario_id, sucursal_id, usuario_id) VALUES (15, 'ULTIMIO', '2017-09-23', '2017-09-13', '2017-09-13', 1, 2, 1, 1);


--
-- TOC entry 2905 (class 0 OID 0)
-- Dependencies: 278
-- Name: ingreso_inventario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ingreso_inventario_id_seq', 15, true);


--
-- TOC entry 2775 (class 0 OID 29515)
-- Dependencies: 217
-- Data for Name: ingreso_venta; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2906 (class 0 OID 0)
-- Dependencies: 279
-- Name: ingreso_venta_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ingreso_venta_id_seq', 1, false);


--
-- TOC entry 2776 (class 0 OID 29519)
-- Dependencies: 218
-- Data for Name: inventario; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (1, '3', 2.00, 950.00, 1250.00, '2017-08-10', '2017-08-29', 1, 1, 2, 2);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (2, '4a', 12.00, 280.00, 700.00, '2017-08-10', '2017-08-29', 1, 1, 2, 4);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (3, 'K25', 15.00, 950.00, 1250.00, '2017-09-04', '2017-09-04', 1, 1, 3, 2);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (4, 'K25', 15.00, 950.00, 1250.00, '2017-09-04', '2017-09-04', 1, 1, 3, 2);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (5, 'K25', 15.00, 950.00, 1250.00, '2017-09-04', '2017-09-04', 1, 1, 3, 2);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (6, '32', 12.00, 950.00, 1250.00, '2017-09-21', '2017-09-04', 1, 1, 6, 2);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (7, '23', 12.00, 250.00, 1000.00, '2017-09-13', '2017-09-04', 1, 1, 7, 1);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (8, '33', 12.00, 950.00, 1250.00, '2017-09-13', '2017-09-04', 1, 1, 7, 2);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (9, 'fr4', 45.00, 950.00, 1250.00, '2017-09-05', '2017-09-05', 1, 1, 8, 2);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (10, 'aa', 12.00, 24.25, 1000.00, '2017-09-16', '2017-09-07', 1, 1, 9, 1);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (11, 'bb', 45.00, 25.00, 5500.00, '2017-09-16', '2017-09-07', 1, 2, 9, 3);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (12, 'L1', 400.00, 24.25, 1000.00, '2017-09-12', '2017-09-12', 1, 1, 10, 1);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (13, 'L2', 150.00, 25.00, 5500.00, '2017-09-12', '2017-09-12', 1, 4, 10, 3);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (14, 'L3', 1000.00, 25.00, 5500.00, '2017-09-13', '2017-09-12', 1, 3, 11, 3);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (15, 'LX3000', 400.00, 0.00, 5500.00, '2017-09-14', '2017-09-13', 1, 3, 12, 3);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (16, 'LOMN', 25.00, 0.00, 5500.00, '2017-09-15', '2017-09-13', 1, 3, 13, 3);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (17, 'LOO', 5.00, 0.00, 5500.00, '2017-09-13', '2017-09-13', 1, 1, 14, 3);
INSERT INTO inventario (id, codigo, cantidad, precio_compra, precio_venta, fecha_ingreso, fecha_modificacion, estado, almacen_id, ingreso_inventario_id, producto_id) VALUES (18, 'ULKT', 20.00, 0.00, 5500.00, '2017-09-23', '2017-09-13', 1, 2, 15, 3);


--
-- TOC entry 2907 (class 0 OID 0)
-- Dependencies: 280
-- Name: inventario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('inventario_id_seq', 18, true);


--
-- TOC entry 2777 (class 0 OID 29523)
-- Dependencies: 219
-- Data for Name: marca; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO marca (id, nombre, codigo, estado) VALUES (1, 'Apple', 'Iph', 1);
INSERT INTO marca (id, nombre, codigo, estado) VALUES (2, 'Samsung', 'Sg', 1);
INSERT INTO marca (id, nombre, codigo, estado) VALUES (3, 'Sony', 'Sn', 1);
INSERT INTO marca (id, nombre, codigo, estado) VALUES (4, 'Lg', 'Lg', 1);


--
-- TOC entry 2908 (class 0 OID 0)
-- Dependencies: 281
-- Name: marca_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('marca_id_seq', 1, false);


--
-- TOC entry 2778 (class 0 OID 29530)
-- Dependencies: 220
-- Data for Name: menu; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2909 (class 0 OID 0)
-- Dependencies: 282
-- Name: menu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('menu_id_seq', 1, false);


--
-- TOC entry 2779 (class 0 OID 29537)
-- Dependencies: 221
-- Data for Name: modelo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO modelo (id, codigo, nombre, estado, marca_id) VALUES (1, '4S', 'Iphone 4S', 1, 1);
INSERT INTO modelo (id, codigo, nombre, estado, marca_id) VALUES (2, '5S', 'Iphone 5S', 1, 1);
INSERT INTO modelo (id, codigo, nombre, estado, marca_id) VALUES (3, '6S', 'Iphone 6S', 1, 1);
INSERT INTO modelo (id, codigo, nombre, estado, marca_id) VALUES (4, 'Z3', 'Soni Z3', 1, 3);


--
-- TOC entry 2910 (class 0 OID 0)
-- Dependencies: 283
-- Name: modelo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('modelo_id_seq', 1, false);


--
-- TOC entry 2780 (class 0 OID 29544)
-- Dependencies: 222
-- Data for Name: orden_trabajo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (2, 'O.T.2', NULL, 50.00, NULL, 50.00, NULL, 50.00, NULL, '2017-09-08', '2017-09-08', 1, 0, 0, 1, 2, 1, 1);
INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (3, 'O.T.3', NULL, 50.00, NULL, 50.00, NULL, 50.00, NULL, '2017-09-08', '2017-09-08', 1, 0, 0, 1, 3, 1, 1);
INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (4, 'O.T.6', NULL, 500.00, 0.00, 500.00, 0.00, 500.00, NULL, '2017-09-20', '2017-09-20', 1, 0, 0, 1, 6, 1, 1);
INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (5, 'O.T.7', NULL, 50.00, 0.00, 50.00, 0.00, 50.00, NULL, '2017-09-20', '2017-09-20', 1, 0, 0, 1, 7, 1, 1);
INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (6, 'O.T.8', NULL, 500.00, 0.00, 500.00, 0.00, 500.00, NULL, '2017-09-20', '2017-09-20', 1, 0, 0, 1, 8, 1, 1);
INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (7, 'O.T.9', NULL, 50.00, 0.00, 50.00, 0.00, 50.00, NULL, '2017-09-20', '2017-09-20', 1, 0, 0, 1, 9, 1, 1);
INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (8, 'O.T.11', NULL, 708.00, 0.00, 708.00, 0.00, 708.00, 708.00, '2017-09-21', '2017-09-29', 1, 0, 0, 1, 11, 1, 1);
INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (12, 'O.T.16', NULL, 750.00, 0.00, 750.00, 0.00, 750.00, NULL, '2017-10-10', '2017-10-10', 1, 0, 0, 1, 16, 1, 1);
INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (13, 'O.T.17', NULL, 1863.00, 0.00, 1863.00, 0.00, 1863.00, NULL, '2017-10-10', '2017-10-10', 1, 0, 0, 1, 17, 1, 1);
INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (14, 'O.T.18', NULL, 1193.00, 0.00, 1193.00, 0.00, 1193.00, NULL, '2017-10-10', '2017-10-10', 1, 0, 0, 1, 18, 1, 1);
INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (10, 'O.T.12', 'DEDEDE', 250.00, 0.00, 250.00, 0.00, 250.00, 250.00, '2017-09-22', '2017-10-13', 1, 1, 0, 1, 12, 1, 1);
INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (11, 'O.T.13', 'desde siempre', 700.00, 0.00, 700.00, 0.00, 700.00, 700.00, '2017-09-28', '2017-10-17', 1, 1, 0, 1, 13, 1, 1);
INSERT INTO orden_trabajo (id, codigo_trabajo, observacion, monto_subtotal, descuento, monto_total, monto_pagado, monto_deuda, monto_saldo, fecha_registro, fecha_modificacion, estado_deuda, estado_trabajo, progreso, estado, recepcion_id, sucursal_id, usuario_id) VALUES (15, 'O.T.19', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2017-10-12', '2017-10-18', 1, 1, 0, 1, 19, 1, 1);


--
-- TOC entry 2911 (class 0 OID 0)
-- Dependencies: 284
-- Name: orden_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('orden_trabajo_id_seq', 15, true);


--
-- TOC entry 2781 (class 0 OID 29551)
-- Dependencies: 223
-- Data for Name: pago_trabajo; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2912 (class 0 OID 0)
-- Dependencies: 285
-- Name: pago_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('pago_trabajo_id_seq', 1, false);


--
-- TOC entry 2782 (class 0 OID 29558)
-- Dependencies: 224
-- Data for Name: precio_servicio_categoria; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO precio_servicio_categoria (id, precio_servicio, estado, categoria_id, servicio_id) VALUES (1, 200.00, 1, 1, 1);
INSERT INTO precio_servicio_categoria (id, precio_servicio, estado, categoria_id, servicio_id) VALUES (2, 250.00, 1, 2, 1);
INSERT INTO precio_servicio_categoria (id, precio_servicio, estado, categoria_id, servicio_id) VALUES (3, 300.00, 1, 3, 1);
INSERT INTO precio_servicio_categoria (id, precio_servicio, estado, categoria_id, servicio_id) VALUES (4, 500.00, 1, 2, 2);
INSERT INTO precio_servicio_categoria (id, precio_servicio, estado, categoria_id, servicio_id) VALUES (5, 700.00, 1, 3, 2);
INSERT INTO precio_servicio_categoria (id, precio_servicio, estado, categoria_id, servicio_id) VALUES (6, 950.00, 1, 4, 2);


--
-- TOC entry 2913 (class 0 OID 0)
-- Dependencies: 286
-- Name: precio_servicio_categoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('precio_servicio_categoria_id_seq', 6, true);


--
-- TOC entry 2783 (class 0 OID 29562)
-- Dependencies: 225
-- Data for Name: producto; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO producto (id, codigo, nombre_comercial, nombre_generico, dimension, precio_compra, precio_venta, precio_venta_mayor, fecha_registro, fecha_modificacion, estado, categoria_id, grupo_id, modelo_id, unidad_medida_id, usuario_id, tipo_producto_id) VALUES (1, 'NOD32', 'Antivirus Nod32', 'Antivirus Nod32', '3*5', 250.00, 1000.00, NULL, '2017-08-29', '2017-08-29', 1, 1, 1, 4, 1, 1, 1);
INSERT INTO producto (id, codigo, nombre_comercial, nombre_generico, dimension, precio_compra, precio_venta, precio_venta_mayor, fecha_registro, fecha_modificacion, estado, categoria_id, grupo_id, modelo_id, unidad_medida_id, usuario_id, tipo_producto_id) VALUES (2, 'SJ1', 'Samsung J1', 'Antivirus Nod32', '5*9', 950.00, 1250.00, NULL, '2017-08-29', '2017-08-29', 1, 2, 2, 4, 1, 1, 1);
INSERT INTO producto (id, codigo, nombre_comercial, nombre_generico, dimension, precio_compra, precio_venta, precio_venta_mayor, fecha_registro, fecha_modificacion, estado, categoria_id, grupo_id, modelo_id, unidad_medida_id, usuario_id, tipo_producto_id) VALUES (3, 'SZ1', 'Sony Z1', 'Sony Z1', '4*9', 4100.00, 5500.00, NULL, '2017-08-29', '2017-08-29', 1, 4, 4, 4, 2, 1, 1);
INSERT INTO producto (id, codigo, nombre_comercial, nombre_generico, dimension, precio_compra, precio_venta, precio_venta_mayor, fecha_registro, fecha_modificacion, estado, categoria_id, grupo_id, modelo_id, unidad_medida_id, usuario_id, tipo_producto_id) VALUES (4, 'i5s', 'Iphone 5S', 'Iphone 5S', '3*7', 280.00, 700.00, NULL, '2017-08-29', '2017-08-29', 1, 4, 2, 2, 1, 1, 1);
INSERT INTO producto (id, codigo, nombre_comercial, nombre_generico, dimension, precio_compra, precio_venta, precio_venta_mayor, fecha_registro, fecha_modificacion, estado, categoria_id, grupo_id, modelo_id, unidad_medida_id, usuario_id, tipo_producto_id) VALUES (5, '545454', 'Note one', 'Samsung Note one', '18''''', 3000.00, 3550.00, NULL, '2017-08-30', '2017-08-30', 1, 2, 2, 4, 1, 1, NULL);
INSERT INTO producto (id, codigo, nombre_comercial, nombre_generico, dimension, precio_compra, precio_venta, precio_venta_mayor, fecha_registro, fecha_modificacion, estado, categoria_id, grupo_id, modelo_id, unidad_medida_id, usuario_id, tipo_producto_id) VALUES (6, '5454542', 'Note one', 'Samsung Note one', '18''''', 3000.00, 3550.00, NULL, '2017-08-30', '2017-08-30', 1, 2, 2, 4, 1, 1, NULL);


--
-- TOC entry 2914 (class 0 OID 0)
-- Dependencies: 287
-- Name: producto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('producto_id_seq', 6, true);


--
-- TOC entry 2784 (class 0 OID 29569)
-- Dependencies: 226
-- Data for Name: producto_proveedor; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO producto_proveedor (id, producto_id, proveedor_id) VALUES (1, 1, 1);
INSERT INTO producto_proveedor (id, producto_id, proveedor_id) VALUES (2, 2, 2);
INSERT INTO producto_proveedor (id, producto_id, proveedor_id) VALUES (3, 1, 2);
INSERT INTO producto_proveedor (id, producto_id, proveedor_id) VALUES (4, 1, 3);
INSERT INTO producto_proveedor (id, producto_id, proveedor_id) VALUES (5, 3, 3);
INSERT INTO producto_proveedor (id, producto_id, proveedor_id) VALUES (6, 4, 2);


--
-- TOC entry 2785 (class 0 OID 29572)
-- Dependencies: 227
-- Data for Name: proveedor; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO proveedor (id, nombre, nit, direccion, telefono, contacto, estado) VALUES (1, 'Proveedor ByB', '78945121', 'S/D', '4564545', 'S/C', 1);
INSERT INTO proveedor (id, nombre, nit, direccion, telefono, contacto, estado) VALUES (2, 'Maxiking A&B', '5345345', 'S/D', '4564545', 'S/C', 1);
INSERT INTO proveedor (id, nombre, nit, direccion, telefono, contacto, estado) VALUES (3, 'NetWorkin', '3454353', 'S/D', '34534534', 'S/C', 1);
INSERT INTO proveedor (id, nombre, nit, direccion, telefono, contacto, estado) VALUES (4, 'Proveedor Alpfa', '78945121', 'S/D', '4564545', 'S/C', 1);


--
-- TOC entry 2915 (class 0 OID 0)
-- Dependencies: 288
-- Name: proveedor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('proveedor_id_seq', 1, false);


--
-- TOC entry 2786 (class 0 OID 29579)
-- Dependencies: 228
-- Data for Name: recepcion; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (13, 'xxxx', '65465888', 0, 'editando accesorio', 'Probando con aagc23', 1, '3650000', '2017-09-28', '2017-09-29', 500.00, 0, 1, 3, 1, 1);
INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (12, 'xxxx', '93', 0, '', '2 fallas - 3 reparaciones', 1, '15', '2017-09-22', '2017-09-28', 70.00, 0, 0, 1, 1, 1);
INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (11, 'xxxx', '455', 1, '', 'probando celulaco', 1, '66880', '2017-09-21', '2017-09-29', 708.00, 0, 1, 1, 1, 1);
INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (16, 'xxxx', '453453433', 1, 'kikfghfghfg', '2342dsadasdasd', 1, '8855', '2017-10-10', '2017-10-10', 750.00, 0, 1, 1, 1, 1);
INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (17, 'xxxx', '276767', 1, 'sadsasdasd', 'dsadasdasdasdas', 2, '1456', '2017-10-10', '2017-10-10', 1863.00, 0, 1, 1, 1, 1);
INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (18, 'xxxx', '8558', 1, '', '213213123', 1, '1231231', '2017-10-10', '2017-10-10', 1193.00, 0, 1, 4, 1, 1);
INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (19, 'xxxx', 'MN25', 1, 'Probando con brenda', '', 2, '366', '2017-10-12', '2017-10-12', 748.00, 0, 1, 5, 1, 1);
INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (2, 'xxxx', '455', 1, '', 'probando celulaco', 1, '66880', '2017-09-08', '2017-09-22', 500.00, 0, 1, 1, 1, 1);
INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (3, 'xxxx', '455', 1, '', 'probando celulaco', 1, '66880', '2017-09-08', '2017-09-22', 500.00, 0, 1, 1, 1, 1);
INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (6, 'xxxx', '455', 1, '', 'probando celulaco', 1, '66880', '2017-09-20', '2017-09-22', 500.00, 0, 1, 1, 1, 1);
INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (7, 'xxxx', '455', 1, '', 'probando celulaco', 1, '66880', '2017-09-20', '2017-09-22', 500.00, 0, 1, 1, 1, 1);
INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (8, 'xxxx', '455', 1, '', 'probando celulaco', 1, '66880', '2017-09-20', '2017-09-22', 500.00, 0, 1, 1, 1, 1);
INSERT INTO recepcion (id, codigo_recepcion, codigo_seguridad, garantia, accesorio_dispositivo, observacion_recepcion, prioridad, numero_ticket, fecha_registro, fecha_modificacion, monto_total, galeria, estado, equipo_cliente_id, sucursal_id, usuario_id) VALUES (9, 'xxxx', '455', 1, '', 'probando celulaco', 1, '66880', '2017-09-20', '2017-09-22', 500.00, 0, 1, 1, 1, 1);


--
-- TOC entry 2916 (class 0 OID 0)
-- Dependencies: 289
-- Name: recepcion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('recepcion_id_seq', 19, true);


--
-- TOC entry 2787 (class 0 OID 29586)
-- Dependencies: 229
-- Data for Name: salida_inventario; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2917 (class 0 OID 0)
-- Dependencies: 290
-- Name: salida_inventario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('salida_inventario_id_seq', 1, false);


--
-- TOC entry 2788 (class 0 OID 29590)
-- Dependencies: 230
-- Data for Name: salida_inventario_venta; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2918 (class 0 OID 0)
-- Dependencies: 291
-- Name: salida_inventario_venta_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('salida_inventario_venta_id_seq', 1, false);


--
-- TOC entry 2789 (class 0 OID 29594)
-- Dependencies: 231
-- Data for Name: servicio; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO servicio (id, nombre, descripcion, precio, fecha_registro, fecha_modificacion, estado, tipo_servicio_id, sucursal_id, usuario_id) VALUES (1, 'Configuracion de internet', 'Configuracion', 50.00, NULL, NULL, 1, 1, 1, 1);
INSERT INTO servicio (id, nombre, descripcion, precio, fecha_registro, fecha_modificacion, estado, tipo_servicio_id, sucursal_id, usuario_id) VALUES (2, 'Cambio de Pantalla', 'Cambio de pantalla', 500.00, NULL, NULL, 1, 2, 1, 1);


--
-- TOC entry 2919 (class 0 OID 0)
-- Dependencies: 292
-- Name: servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('servicio_id_seq', 2, true);


--
-- TOC entry 2790 (class 0 OID 29601)
-- Dependencies: 232
-- Data for Name: solicitud_traspaso_inventario; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2791 (class 0 OID 29604)
-- Dependencies: 233
-- Data for Name: solucion; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO solucion (id, nombre, descripcion, estado, sucursal_id, usuario_id, agrupa_id) VALUES (2, 'Cambio de Pantalla', 'La solucion se cambia de pantalla', 1, 1, 1, 1);
INSERT INTO solucion (id, nombre, descripcion, estado, sucursal_id, usuario_id, agrupa_id) VALUES (3, 'Cambio de Antena', 'Se cambia la pantalla', 1, 1, 1, 1);
INSERT INTO solucion (id, nombre, descripcion, estado, sucursal_id, usuario_id, agrupa_id) VALUES (4, 'Configurar para internet', 'Configuracion de internet para el dispositivo', 1, 1, 1, 3);


--
-- TOC entry 2920 (class 0 OID 0)
-- Dependencies: 293
-- Name: solucion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solucion_id_seq', 4, true);


--
-- TOC entry 2792 (class 0 OID 29611)
-- Dependencies: 234
-- Data for Name: solucion_orden_trabajo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (5, 1, 11, 2);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (6, 1, 11, 3);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (7, 1, 11, 4);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (8, 1, 11, 2);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (9, 1, 11, 3);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (10, 1, 11, 4);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (11, 1, 11, 2);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (12, 1, 11, 3);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (13, 1, 11, 4);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (14, 1, 11, 2);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (15, 1, 11, 3);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (16, 1, 11, 4);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (17, 1, 11, 2);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (18, 1, 11, 3);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (19, 1, 11, 4);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (20, 1, 11, 2);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (21, 1, 11, 3);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (22, 1, 11, 4);
INSERT INTO solucion_orden_trabajo (id, estado, orden_trabajo_id, solucion_id) VALUES (23, 1, 15, 3);


--
-- TOC entry 2921 (class 0 OID 0)
-- Dependencies: 294
-- Name: solucion_orden_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solucion_orden_trabajo_id_seq', 23, true);


--
-- TOC entry 2793 (class 0 OID 29615)
-- Dependencies: 235
-- Data for Name: solucion_recepcion; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (1, 0, 2, 2);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (2, 0, 3, 2);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (3, 0, 6, 3);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (4, 0, 7, 3);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (5, 0, 7, 4);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (6, 0, 8, 3);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (7, 0, 9, 3);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (8, 0, 9, 4);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (14, 0, 12, 3);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (50, 0, 13, 2);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (51, 0, 13, 3);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (52, 0, 13, 4);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (53, 0, 11, 3);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (54, 0, 11, 4);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (55, 0, 16, 2);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (56, 0, 17, 3);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (57, 0, 17, 4);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (58, 0, 18, 2);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (59, 0, 18, 3);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (60, 0, 18, 4);
INSERT INTO solucion_recepcion (id, estado, recepcion_id, solucion_id) VALUES (61, 0, 19, 3);


--
-- TOC entry 2922 (class 0 OID 0)
-- Dependencies: 295
-- Name: solucion_recepcion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solucion_recepcion_id_seq', 61, true);


--
-- TOC entry 2794 (class 0 OID 29619)
-- Dependencies: 236
-- Data for Name: sucursal; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO sucursal (id, nombre, nombre_comercial, direccion, ciudad, telefono, correo, estado) VALUES (1, 'Santa Cruz', 'Sucursal  SCZ', 'Santa Cruz - Bolivia', 'Ciudad de Scz', '545454', 'S/E', 1);
INSERT INTO sucursal (id, nombre, nombre_comercial, direccion, ciudad, telefono, correo, estado) VALUES (2, 'Trinidad', 'Trinidad Sucur', 'Trinidad - Bolivia', 'Ciudad de Trinidad', '3123123', 'S/E', 1);


--
-- TOC entry 2923 (class 0 OID 0)
-- Dependencies: 296
-- Name: sucursal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('sucursal_id_seq', 1, false);


--
-- TOC entry 2795 (class 0 OID 29626)
-- Dependencies: 237
-- Data for Name: tipo_almacen; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO tipo_almacen (id, nombre, descripcion, estado) VALUES (1, 'Apple', 'Tipo de almacen para la marca Apple', 1);
INSERT INTO tipo_almacen (id, nombre, descripcion, estado) VALUES (2, 'Sansung', 'Tipo de almacen para la marca Sansung', 1);
INSERT INTO tipo_almacen (id, nombre, descripcion, estado) VALUES (3, 'Huawei', 'Tipo de almacen para la marca Huawei', 1);
INSERT INTO tipo_almacen (id, nombre, descripcion, estado) VALUES (4, 'Sony', 'Tipo de almacen para la marca Sony', 1);


--
-- TOC entry 2924 (class 0 OID 0)
-- Dependencies: 297
-- Name: tipo_almacen_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipo_almacen_id_seq', 1, false);


--
-- TOC entry 2796 (class 0 OID 29633)
-- Dependencies: 238
-- Data for Name: tipo_ingreso_egreso; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2925 (class 0 OID 0)
-- Dependencies: 298
-- Name: tipo_ingreso_egreso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipo_ingreso_egreso_id_seq', 1, false);


--
-- TOC entry 2797 (class 0 OID 29640)
-- Dependencies: 239
-- Data for Name: tipo_ingreso_inventario; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO tipo_ingreso_inventario (id, nombre, decripcion, fecha_registro, url, estado) VALUES (2, 'Ingreso Pedido', 'Ingreso de inventario mediante pedido', '2017-08-17', 'inventory/common', 1);
INSERT INTO tipo_ingreso_inventario (id, nombre, decripcion, fecha_registro, url, estado) VALUES (3, 'Ingreso Regalo', 'Ingreso de inventario obsequiado a la empresa', '2017-08-17', 'inventory/common', 1);
INSERT INTO tipo_ingreso_inventario (id, nombre, decripcion, fecha_registro, url, estado) VALUES (1, 'Ingreso habitual', 'Ingreso de inventario habitual de manera sencilla', '2017-08-17', '''inventory/common''', 1);
INSERT INTO tipo_ingreso_inventario (id, nombre, decripcion, fecha_registro, url, estado) VALUES (4, 'Ingreso Compra', 'Ingreso de inventario mediante compras', '2017-08-17', '''inventory/purchase_entry''', 1);


--
-- TOC entry 2926 (class 0 OID 0)
-- Dependencies: 299
-- Name: tipo_ingreso_inventario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipo_ingreso_inventario_id_seq', 1, false);


--
-- TOC entry 2798 (class 0 OID 29647)
-- Dependencies: 240
-- Data for Name: tipo_producto; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO tipo_producto (id, nombre, descripcion, estado) VALUES (1, 'Originales', 'Telefonos movil originales', 1);
INSERT INTO tipo_producto (id, nombre, descripcion, estado) VALUES (2, 'Replicas', 'Telefonos movil Replicas', 1);
INSERT INTO tipo_producto (id, nombre, descripcion, estado) VALUES (3, 'Antivirus', 'Antivirus con licencia original', 1);


--
-- TOC entry 2927 (class 0 OID 0)
-- Dependencies: 300
-- Name: tipo_producto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipo_producto_id_seq', 1, false);


--
-- TOC entry 2799 (class 0 OID 29651)
-- Dependencies: 241
-- Data for Name: tipo_salida_inventario; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2928 (class 0 OID 0)
-- Dependencies: 301
-- Name: tipo_salida_inventario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipo_salida_inventario_id_seq', 1, false);


--
-- TOC entry 2800 (class 0 OID 29658)
-- Dependencies: 242
-- Data for Name: tipo_servicio; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO tipo_servicio (id, nombre, descripcion, estado) VALUES (1, 'Recepcion', 'Pagando recepcion', 1);
INSERT INTO tipo_servicio (id, nombre, descripcion, estado) VALUES (2, 'Orden de trabajo', 'Orden de trabajo para realizar el servicio tecnico', 1);


--
-- TOC entry 2929 (class 0 OID 0)
-- Dependencies: 302
-- Name: tipo_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipo_servicio_id_seq', 2, true);


--
-- TOC entry 2801 (class 0 OID 29665)
-- Dependencies: 243
-- Data for Name: unidad_medida; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO unidad_medida (id, nombre, abreviatura, estado) VALUES (1, 'Unidad', 'Un.', 1);
INSERT INTO unidad_medida (id, nombre, abreviatura, estado) VALUES (2, 'Paquete', 'Pqg', 1);
INSERT INTO unidad_medida (id, nombre, abreviatura, estado) VALUES (3, 'Docena', 'doc.', 1);
INSERT INTO unidad_medida (id, nombre, abreviatura, estado) VALUES (4, 'Lg', 'Lg', 1);


--
-- TOC entry 2930 (class 0 OID 0)
-- Dependencies: 303
-- Name: unidad_medida_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('unidad_medida_id_seq', 1, false);


--
-- TOC entry 2802 (class 0 OID 29672)
-- Dependencies: 244
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO usuario (id, ci, nombre, telefono, usuario, clave, estado, cargo_id) VALUES (1, '46545', 'Yobani campos', '13213123', 'ybani123', '23123123', 1, 1);


--
-- TOC entry 2931 (class 0 OID 0)
-- Dependencies: 304
-- Name: usuario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuario_id_seq', 3, true);


--
-- TOC entry 2803 (class 0 OID 29679)
-- Dependencies: 245
-- Data for Name: usuario_sucursal; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2804 (class 0 OID 29682)
-- Dependencies: 246
-- Data for Name: venta; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2932 (class 0 OID 0)
-- Dependencies: 305
-- Name: venta_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('venta_id_seq', 1, false);


--
-- TOC entry 2805 (class 0 OID 29686)
-- Dependencies: 247
-- Data for Name: venta_recepcion; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2933 (class 0 OID 0)
-- Dependencies: 306
-- Name: venta_recepcion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('venta_recepcion_id_seq', 1, false);


--
-- TOC entry 2490 (class 2606 OID 29809)
-- Name: acceso PK_acceso; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY acceso
    ADD CONSTRAINT "PK_acceso" PRIMARY KEY (id);


--
-- TOC entry 2492 (class 2606 OID 29811)
-- Name: actividad PK_actividad; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY actividad
    ADD CONSTRAINT "PK_actividad" PRIMARY KEY (id);


--
-- TOC entry 2494 (class 2606 OID 29813)
-- Name: agrupa PK_agrupa; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY agrupa
    ADD CONSTRAINT "PK_agrupa" PRIMARY KEY (id);


--
-- TOC entry 2496 (class 2606 OID 29815)
-- Name: almacen PK_almacen; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY almacen
    ADD CONSTRAINT "PK_almacen" PRIMARY KEY (id);


--
-- TOC entry 2498 (class 2606 OID 29817)
-- Name: asignacion_grupo PK_asignacion_grupo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY asignacion_grupo
    ADD CONSTRAINT "PK_asignacion_grupo" PRIMARY KEY (id);


--
-- TOC entry 2500 (class 2606 OID 29819)
-- Name: cargo PK_cargo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cargo
    ADD CONSTRAINT "PK_cargo" PRIMARY KEY (id);


--
-- TOC entry 2502 (class 2606 OID 29821)
-- Name: categoria PK_categoria; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY categoria
    ADD CONSTRAINT "PK_categoria" PRIMARY KEY (id);


--
-- TOC entry 2504 (class 2606 OID 29823)
-- Name: cliente PK_cliente; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cliente
    ADD CONSTRAINT "PK_cliente" PRIMARY KEY (id);


--
-- TOC entry 2506 (class 2606 OID 29825)
-- Name: compra PK_compra; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra
    ADD CONSTRAINT "PK_compra" PRIMARY KEY (id);


--
-- TOC entry 2508 (class 2606 OID 29827)
-- Name: detalle_compra PK_detalle_compra; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_compra
    ADD CONSTRAINT "PK_detalle_compra" PRIMARY KEY (id);


--
-- TOC entry 2510 (class 2606 OID 29829)
-- Name: detalle_orden_trabajo_servicio PK_detalle_orden_trabajo_servicio; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_orden_trabajo_servicio
    ADD CONSTRAINT "PK_detalle_orden_trabajo_servicio" PRIMARY KEY (id);


--
-- TOC entry 2512 (class 2606 OID 29831)
-- Name: detalle_producto_trabajo PK_detalle_producto_trabajo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_producto_trabajo
    ADD CONSTRAINT "PK_detalle_producto_trabajo" PRIMARY KEY (id);


--
-- TOC entry 2514 (class 2606 OID 29833)
-- Name: detalle_recepcion_servicio PK_detalle_recepcion_servicio; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_recepcion_servicio
    ADD CONSTRAINT "PK_detalle_recepcion_servicio" PRIMARY KEY (id);


--
-- TOC entry 2516 (class 2606 OID 29835)
-- Name: detalle_salida_inventario PK_detalle_salida_inventario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_salida_inventario
    ADD CONSTRAINT "PK_detalle_salida_inventario" PRIMARY KEY (id);


--
-- TOC entry 2518 (class 2606 OID 29837)
-- Name: detalle_venta PK_detalle_venta; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_venta
    ADD CONSTRAINT "PK_detalle_venta" PRIMARY KEY (id);


--
-- TOC entry 2612 (class 2606 OID 30226)
-- Name: detalle_virtual PK_detalle_virtual; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_virtual
    ADD CONSTRAINT "PK_detalle_virtual" PRIMARY KEY (id);


--
-- TOC entry 2520 (class 2606 OID 29839)
-- Name: dosificacion PK_dosificacion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dosificacion
    ADD CONSTRAINT "PK_dosificacion" PRIMARY KEY (id);


--
-- TOC entry 2522 (class 2606 OID 29841)
-- Name: egreso PK_egreso; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY egreso
    ADD CONSTRAINT "PK_egreso" PRIMARY KEY (id);


--
-- TOC entry 2524 (class 2606 OID 29843)
-- Name: equipo_cliente PK_equipo_cliente; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY equipo_cliente
    ADD CONSTRAINT "PK_equipo_cliente" PRIMARY KEY (id);


--
-- TOC entry 2526 (class 2606 OID 29845)
-- Name: factura PK_factura; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY factura
    ADD CONSTRAINT "PK_factura" PRIMARY KEY (id);


--
-- TOC entry 2528 (class 2606 OID 29847)
-- Name: factura_compra PK_factura_compra; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY factura_compra
    ADD CONSTRAINT "PK_factura_compra" PRIMARY KEY (id);


--
-- TOC entry 2530 (class 2606 OID 29849)
-- Name: falla PK_falla; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY falla
    ADD CONSTRAINT "PK_falla" PRIMARY KEY (id);


--
-- TOC entry 2532 (class 2606 OID 29851)
-- Name: falla_orden_trabajo PK_falla_orden_trabajo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY falla_orden_trabajo
    ADD CONSTRAINT "PK_falla_orden_trabajo" PRIMARY KEY (id);


--
-- TOC entry 2534 (class 2606 OID 29853)
-- Name: falla_recepcion PK_falla_recepcion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY falla_recepcion
    ADD CONSTRAINT "PK_falla_recepcion" PRIMARY KEY (id);


--
-- TOC entry 2536 (class 2606 OID 29855)
-- Name: grupo PK_grupo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY grupo
    ADD CONSTRAINT "PK_grupo" PRIMARY KEY (id);


--
-- TOC entry 2538 (class 2606 OID 29857)
-- Name: historial_compra PK_historial_compra; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY historial_compra
    ADD CONSTRAINT "PK_historial_compra" PRIMARY KEY (id);


--
-- TOC entry 2540 (class 2606 OID 29859)
-- Name: historial_orden_trabajo PK_historial_orden_trabajo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY historial_orden_trabajo
    ADD CONSTRAINT "PK_historial_orden_trabajo" PRIMARY KEY (id);


--
-- TOC entry 2542 (class 2606 OID 29861)
-- Name: historial_recepcion PK_historial_recepcion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY historial_recepcion
    ADD CONSTRAINT "PK_historial_recepcion" PRIMARY KEY (id);


--
-- TOC entry 2544 (class 2606 OID 29863)
-- Name: imagen_recepcion PK_imagen_recepcion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY imagen_recepcion
    ADD CONSTRAINT "PK_imagen_recepcion" PRIMARY KEY (id);


--
-- TOC entry 2546 (class 2606 OID 29865)
-- Name: impresora PK_impresora; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY impresora
    ADD CONSTRAINT "PK_impresora" PRIMARY KEY (id);


--
-- TOC entry 2548 (class 2606 OID 29867)
-- Name: ingreso PK_ingreso; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ingreso
    ADD CONSTRAINT "PK_ingreso" PRIMARY KEY (id);


--
-- TOC entry 2550 (class 2606 OID 29869)
-- Name: ingreso_compra_producto PK_ingreso_compra_producto; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ingreso_compra_producto
    ADD CONSTRAINT "PK_ingreso_compra_producto" PRIMARY KEY (id);


--
-- TOC entry 2552 (class 2606 OID 29871)
-- Name: ingreso_inventario PK_ingreso_inventario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ingreso_inventario
    ADD CONSTRAINT "PK_ingreso_inventario" PRIMARY KEY (id);


--
-- TOC entry 2554 (class 2606 OID 29873)
-- Name: ingreso_venta PK_ingreso_venta; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ingreso_venta
    ADD CONSTRAINT "PK_ingreso_venta" PRIMARY KEY (id);


--
-- TOC entry 2556 (class 2606 OID 29875)
-- Name: inventario PK_inventario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY inventario
    ADD CONSTRAINT "PK_inventario" PRIMARY KEY (id);


--
-- TOC entry 2558 (class 2606 OID 29877)
-- Name: marca PK_marca; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY marca
    ADD CONSTRAINT "PK_marca" PRIMARY KEY (id);


--
-- TOC entry 2560 (class 2606 OID 29879)
-- Name: menu PK_menu; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT "PK_menu" PRIMARY KEY (id);


--
-- TOC entry 2562 (class 2606 OID 29881)
-- Name: modelo PK_modelo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY modelo
    ADD CONSTRAINT "PK_modelo" PRIMARY KEY (id);


--
-- TOC entry 2564 (class 2606 OID 29883)
-- Name: orden_trabajo PK_orden_trabajo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY orden_trabajo
    ADD CONSTRAINT "PK_orden_trabajo" PRIMARY KEY (id);


--
-- TOC entry 2566 (class 2606 OID 29885)
-- Name: pago_trabajo PK_pago_trabajo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pago_trabajo
    ADD CONSTRAINT "PK_pago_trabajo" PRIMARY KEY (id);


--
-- TOC entry 2568 (class 2606 OID 29887)
-- Name: precio_servicio_categoria PK_precio_servicio_categoria; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY precio_servicio_categoria
    ADD CONSTRAINT "PK_precio_servicio_categoria" PRIMARY KEY (id);


--
-- TOC entry 2570 (class 2606 OID 29889)
-- Name: producto PK_producto; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY producto
    ADD CONSTRAINT "PK_producto" PRIMARY KEY (id);


--
-- TOC entry 2572 (class 2606 OID 29891)
-- Name: producto_proveedor PK_producto_proveedor; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY producto_proveedor
    ADD CONSTRAINT "PK_producto_proveedor" PRIMARY KEY (id);


--
-- TOC entry 2574 (class 2606 OID 29893)
-- Name: proveedor PK_proveedor; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY proveedor
    ADD CONSTRAINT "PK_proveedor" PRIMARY KEY (id);


--
-- TOC entry 2576 (class 2606 OID 29895)
-- Name: recepcion PK_recepcion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY recepcion
    ADD CONSTRAINT "PK_recepcion" PRIMARY KEY (id);


--
-- TOC entry 2578 (class 2606 OID 29897)
-- Name: salida_inventario PK_salida_inventario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY salida_inventario
    ADD CONSTRAINT "PK_salida_inventario" PRIMARY KEY (id);


--
-- TOC entry 2580 (class 2606 OID 29899)
-- Name: salida_inventario_venta PK_salida_inventario_venta; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY salida_inventario_venta
    ADD CONSTRAINT "PK_salida_inventario_venta" PRIMARY KEY (id);


--
-- TOC entry 2582 (class 2606 OID 29901)
-- Name: servicio PK_servicio; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servicio
    ADD CONSTRAINT "PK_servicio" PRIMARY KEY (id);


--
-- TOC entry 2584 (class 2606 OID 29903)
-- Name: solucion PK_solucion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solucion
    ADD CONSTRAINT "PK_solucion" PRIMARY KEY (id);


--
-- TOC entry 2586 (class 2606 OID 29905)
-- Name: solucion_orden_trabajo PK_solucion_orden_trabajo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solucion_orden_trabajo
    ADD CONSTRAINT "PK_solucion_orden_trabajo" PRIMARY KEY (id);


--
-- TOC entry 2588 (class 2606 OID 29907)
-- Name: solucion_recepcion PK_solucion_recepcion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solucion_recepcion
    ADD CONSTRAINT "PK_solucion_recepcion" PRIMARY KEY (id);


--
-- TOC entry 2590 (class 2606 OID 29909)
-- Name: sucursal PK_sucursal; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY sucursal
    ADD CONSTRAINT "PK_sucursal" PRIMARY KEY (id);


--
-- TOC entry 2592 (class 2606 OID 29911)
-- Name: tipo_almacen PK_tipo_almacen; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipo_almacen
    ADD CONSTRAINT "PK_tipo_almacen" PRIMARY KEY (id);


--
-- TOC entry 2594 (class 2606 OID 29913)
-- Name: tipo_ingreso_egreso PK_tipo_ingreso_egreso; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipo_ingreso_egreso
    ADD CONSTRAINT "PK_tipo_ingreso_egreso" PRIMARY KEY (id);


--
-- TOC entry 2596 (class 2606 OID 29915)
-- Name: tipo_ingreso_inventario PK_tipo_ingreso_inventario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipo_ingreso_inventario
    ADD CONSTRAINT "PK_tipo_ingreso_inventario" PRIMARY KEY (id);


--
-- TOC entry 2598 (class 2606 OID 29917)
-- Name: tipo_producto PK_tipo_producto; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipo_producto
    ADD CONSTRAINT "PK_tipo_producto" PRIMARY KEY (id);


--
-- TOC entry 2600 (class 2606 OID 29919)
-- Name: tipo_salida_inventario PK_tipo_salida_inventario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipo_salida_inventario
    ADD CONSTRAINT "PK_tipo_salida_inventario" PRIMARY KEY (id);


--
-- TOC entry 2602 (class 2606 OID 29921)
-- Name: tipo_servicio PK_tipo_servicio; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipo_servicio
    ADD CONSTRAINT "PK_tipo_servicio" PRIMARY KEY (id);


--
-- TOC entry 2604 (class 2606 OID 29923)
-- Name: unidad_medida PK_unidad_medida; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY unidad_medida
    ADD CONSTRAINT "PK_unidad_medida" PRIMARY KEY (id);


--
-- TOC entry 2606 (class 2606 OID 29925)
-- Name: usuario PK_usuario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT "PK_usuario" PRIMARY KEY (id);


--
-- TOC entry 2608 (class 2606 OID 29927)
-- Name: venta PK_venta; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venta
    ADD CONSTRAINT "PK_venta" PRIMARY KEY (id);


--
-- TOC entry 2610 (class 2606 OID 29929)
-- Name: venta_recepcion PK_venta_recepcion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venta_recepcion
    ADD CONSTRAINT "PK_venta_recepcion" PRIMARY KEY (id);


--
-- TOC entry 2741 (class 2618 OID 30217)
-- Name: inventario_stock_general _RETURN; Type: RULE; Schema: public; Owner: postgres
--

CREATE RULE "_RETURN" AS
    ON SELECT TO inventario_stock_general DO INSTEAD  SELECT s.id AS sucursal_id,
    s.nombre_comercial AS nombre_sucursal,
    a.id AS almacen_id,
    a.nombre AS nombre_almacen,
    m.id AS modelo_id,
    m.nombre AS nombre_modelo,
    ma.id AS marca_id,
    ma.nombre AS nombre_marca,
    g.id AS grupo_id,
    g.nombre AS nombre_grupo,
    t.id AS tipo_producto_id,
    t.nombre AS nombre_tipo_producto,
    u.id AS unidad_medida_id,
    u.nombre AS nombre_unidad_medida,
    p.id AS producto_id,
    p.codigo AS codigo_producto,
    p.codigo AS codigo_barra,
    p.nombre_comercial,
    p.nombre_generico,
    p.precio_venta,
    p.precio_venta_mayor,
    p.estado AS estado_producto,
    sum(i.cantidad) AS stock
   FROM inventario i,
    producto p,
    almacen a,
    sucursal s,
    modelo m,
    marca ma,
    grupo g,
    tipo_producto t,
    unidad_medida u
  WHERE ((i.producto_id = p.id) AND (i.almacen_id = a.id) AND (a.sucursal_id = s.id) AND (p.modelo_id = m.id) AND (m.marca_id = ma.id) AND (p.grupo_id = g.id) AND (p.tipo_producto_id = t.id) AND (p.unidad_medida_id = u.id))
  GROUP BY s.id, a.id, p.id, m.id, ma.id, g.id, t.id, u.id, p.precio_venta;


--
-- TOC entry 2613 (class 2606 OID 29930)
-- Name: almacen FK_almacen_tipo_almacen; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY almacen
    ADD CONSTRAINT "FK_almacen_tipo_almacen" FOREIGN KEY (tipo_almacen_id) REFERENCES tipo_almacen(id);


--
-- TOC entry 2614 (class 2606 OID 29935)
-- Name: asignacion_grupo FK_asignacion_grupo_grupo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY asignacion_grupo
    ADD CONSTRAINT "FK_asignacion_grupo_grupo" FOREIGN KEY (grupo_padre_id) REFERENCES grupo(id);


--
-- TOC entry 2615 (class 2606 OID 29940)
-- Name: asignacion_grupo FK_asignacion_grupo_grupo_02; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY asignacion_grupo
    ADD CONSTRAINT "FK_asignacion_grupo_grupo_02" FOREIGN KEY (grupo_hijo_id) REFERENCES grupo(id);


--
-- TOC entry 2617 (class 2606 OID 29950)
-- Name: producto_proveedor FK_producto_proveedor_producto; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY producto_proveedor
    ADD CONSTRAINT "FK_producto_proveedor_producto" FOREIGN KEY (producto_id) REFERENCES producto(id) ON UPDATE CASCADE;


--
-- TOC entry 2618 (class 2606 OID 29955)
-- Name: producto_proveedor FK_producto_proveedor_proveedor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY producto_proveedor
    ADD CONSTRAINT "FK_producto_proveedor_proveedor" FOREIGN KEY (proveedor_id) REFERENCES proveedor(id) ON UPDATE CASCADE;


--
-- TOC entry 2616 (class 2606 OID 29945)
-- Name: producto FK_producto_tipo_producto; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY producto
    ADD CONSTRAINT "FK_producto_tipo_producto" FOREIGN KEY (tipo_producto_id) REFERENCES tipo_producto(id) ON UPDATE CASCADE;


--
-- TOC entry 2619 (class 2606 OID 29960)
-- Name: usuario FK_usuario_cargo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT "FK_usuario_cargo" FOREIGN KEY (cargo_id) REFERENCES cargo(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2620 (class 2606 OID 29965)
-- Name: usuario_sucursal FK_usuario_sucursal_sucursal; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario_sucursal
    ADD CONSTRAINT "FK_usuario_sucursal_sucursal" FOREIGN KEY (sucursal_id) REFERENCES sucursal(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2621 (class 2606 OID 29970)
-- Name: usuario_sucursal FK_usuario_sucursal_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario_sucursal
    ADD CONSTRAINT "FK_usuario_sucursal_usuario" FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE CASCADE;


-- Completed on 2017-10-24 15:15:31

--
-- PostgreSQL database dump complete
--

