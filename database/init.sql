-- Crear base de datos si no existe
SELECT 'CREATE DATABASE nombre_de_la_base_de_datos'
    WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'Bd_MiyagiDo');

-- Borrar tablas y secuencias si existen
DROP TABLE IF EXISTS "productos";
DROP SEQUENCE IF EXISTS productos_id_seq;
DROP TABLE IF EXISTS "user_roles";
DROP TABLE IF EXISTS "usuarios";
DROP SEQUENCE IF EXISTS usuarios_id_seq;
DROP TABLE IF EXISTS "categorias";

-- Crear secuencias
CREATE SEQUENCE productos_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 6 CACHE 1;
CREATE SEQUENCE usuarios_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 5 CACHE 1;

-- Crear tabla categorias
CREATE TABLE "public"."categorias"
(
    "is_deleted" boolean NOT NULL DEFAULT false,
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "id"         serial                               NOT NULL,
    "nombre"     character varying(255)              NOT NULL,
    CONSTRAINT "categorias_nombre_key" UNIQUE ("nombre"),
    CONSTRAINT "categorias_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

-- Insertar categorías
INSERT INTO "categorias" ("id", "is_deleted", "created_at", "updated_at", "nombre")
VALUES (1, false, '2023-11-02 11:43:24.717712', '2023-11-02 11:43:24.717712', 'Karateguis'),
       (2, false, '2023-11-02 11:43:24.717712', '2023-11-02 11:43:24.717712', 'Protecciones'),
       (3, false, '2023-11-02 11:43:24.717712', '2023-11-02 11:43:24.717712', 'Tatamis'),
       (4, false, '2023-11-02 11:43:24.717712', '2023-11-02 11:43:24.717712', 'Complementos'),
       (5, false, '2023-11-02 11:43:24.717712', '2023-11-02 11:43:24.717712', 'Competicion');

-- Crear tabla usuarios
CREATE TABLE "public"."usuarios"
(
    "is_deleted" boolean NOT NULL DEFAULT false,
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP          NOT NULL,
    "id"         bigint    DEFAULT nextval('usuarios_id_seq') NOT NULL,
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP          NOT NULL,
    "apellidos"  character varying(255)                       NOT NULL,
    "email"      character varying(255)                       NOT NULL,
    "nombre"     character varying(255)                       NOT NULL,
    "password"   character varying(255)                       NOT NULL,
    "username"   character varying(255)                       NOT NULL,
    CONSTRAINT "usuarios_email_key" UNIQUE ("email"),
    CONSTRAINT "usuarios_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "usuarios_username_key" UNIQUE ("username")
) WITH (oids = false);

-- Insertar usuarios
INSERT INTO "usuarios" ("id", "is_deleted", "created_at", "updated_at", "apellidos", "email", "nombre", "password", "username")
VALUES (1, false, '2023-11-02 11:43:24.724871', '2023-11-02 11:43:24.724871', 'Moya Peñuela', 'miyagui@gmail.es', 'Noah',
        '$2y$10$O055OrzHiinLB.YrA5wrl.Bcavy9d5MNCXTR5WA4XDq0BEtYiIzRa', 'moya'),
       (2, false, '2023-11-02 11:43:24.730431', '2023-11-02 11:43:24.730431', 'Garcia Peris', 'garcia@gmail.es', 'Manolo',
        '$2y$10$Sv7SyViHnI0DrYYMhfBVL.r37Pt466LGkDardBd5Z6cj/Hmr9QEZm', 'garci'),
       (3, false, '2023-11-02 11:43:24.733552', '2023-11-02 11:43:24.733552', 'Jimenez Mena', 'jimenez@gmail.com', 'Sandra',
        '$2y$10$/tPwvBE5e28vqjkdySDB/ec/mbhGKBDzAz0FqGdHh6Bsm2KHxxQSu', 'jim');
       --contraseñas
       --moya admin contraseña: admin	
       --garci user contraseña: user1	
       --jim user contraseña: user2

-- Crear tabla productos
CREATE TABLE "public"."productos"
(
    "is_deleted"   boolean          NOT NULL DEFAULT false,
    "precio"       double precision DEFAULT '0.0',
    "stock"        integer          DEFAULT '0',
    "created_at"   timestamp        DEFAULT CURRENT_TIMESTAMP           NOT NULL,
    "id"           bigint           DEFAULT nextval('productos_id_seq') NOT NULL,
    "updated_at"   timestamp        DEFAULT CURRENT_TIMESTAMP           NOT NULL,
    "categoria_id" integer,
    "descripcion"  character varying(255),
    "imagen"       text             DEFAULT 'https://via.placeholder.com/150',
    "marca"        character varying(255),
    "modelo"       character varying(255),
    "color"        character varying(50),
    "talla"        character varying(20),
    CONSTRAINT "productos_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

-- Insertar productos
INSERT INTO "productos" ("id", "is_deleted", "precio", "stock", "created_at", "updated_at", "categoria_id",
                         "descripcion", "imagen", "marca", "modelo", "color", "talla")
VALUES (1, false, 72, 10, '2023-11-02 11:43:24.722473', '2023-11-02 11:43:24.722473', 1, 'Karategui principiante',
        'https://via.placeholder.com/150', 'Tokaido', 'Komite Master Junior', 'blanco', 'XS'),
       (2, false, 30, 14, '2023-11-02 11:43:24.722473', '2023-11-02 11:43:24.722473', 2, 'Karategui Basico 100% algodon',
        'https://via.placeholder.com/150', 'NI', 'Basico', 'blanco', '130'),
       (3, false, 70, 18, '2023-11-02 11:43:24.722473', '2023-11-02 11:43:24.722473', 1, 'Casco proteccion infantil Homologado',
        'https://via.placeholder.com/150', 'Daedo', 'WFK', 'blanco', 'S'),
       (4, false, 55, 12, '2023-11-02 11:43:24.722473', '2023-11-02 11:43:24.722473', 2, 'Espinilleras Karate',
        'https://via.placeholder.com/150', 'Tokaido', 'Kanji', 'rojo', 'XS'),
       (5, false, 40, 3, '2023-11-02 11:43:24.722473', '2023-11-02 11:43:24.722473', 2, 'Peto Cadete Homologado',
        'https://via.placeholder.com/150', 'Shureido', 'WFK', 'blanco', 'XS');

-- Crear tabla user_roles
CREATE TABLE "public"."user_roles"
(
    "user_id" bigint NOT NULL,
    "roles"   character varying(255)
) WITH (oids = false);

-- Insertar roles de usuarios
INSERT INTO "user_roles" ("user_id", "roles")
VALUES (1, 'USER'),
       (1, 'ADMIN'),
       (2, 'USER'),
       (3, 'USER');

-- Claves foráneas
ALTER TABLE ONLY "public"."productos"
    ADD CONSTRAINT "fk_categoria_productos" FOREIGN KEY (categoria_id) REFERENCES categorias (id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."user_roles"
    ADD CONSTRAINT "fk_user_roles" FOREIGN KEY (user_id) REFERENCES usuarios (id) NOT DEFERRABLE;
