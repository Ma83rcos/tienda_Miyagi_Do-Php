# Tienda Miyagi-Do

![PHP](https://img.shields.io/badge/PHP-8.2-blue) 
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-12-blue) 
![Docker](https://img.shields.io/badge/Docker-20.10-blue) 
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.2-purple)

## 📋 Descripción

Aplicación web de gestión de productos para una tienda de Karate. Permite realizar operaciones CRUD completas sobre productos, gestionar categorías y controlar el acceso mediante un sistema de autenticación con roles (administrador y usuario).

- Los **administradores** pueden crear, editar, cambiar imágenes y eliminar productos. 
- Los **usuarios normales** pueden consultar el catálogo, ver detalles de cada producto y buscar por marca o color.

## 🛠️ Tecnologías utilizadas

### Backend
- PHP 8.2 
- PostgreSQL 12 
- Apache 2.4 

### Frontend
- Bootstrap 5.3.2 
- HTML5 y CSS3 

### Librerías PHP
- `vlucas/phpdotenv` (v5.6) – Gestión de variables de entorno 
- `phpunit/phpunit` (v9) – Testing unitario 

### Infraestructura
- Docker y Docker Compose 
- Adminer – Gestor de base de datos 

## 📁 Estructura del proyecto

```plaintext
proyecto-final/
├── src/                    # Código fuente
│   ├── config/            # Configuración y conexión BD
│   ├── models/            # Modelos (Producto, Categoria, Usuario)
│   ├── services/          # Lógica de negocio
│   ├── uploads/           # Imágenes de productos
│   ├── index.php          # Página principal
│   ├── create.php         # Crear productos
│   ├── update.php         # Actualizar productos
│   ├── login.php          # Login
│   ├── logout.php         # Logout
│   ├── header.php         # Plantilla header
│   └── footer.php         # Plantilla footer
├── database/
│   └── init.sql           # Script de inicialización BD
├── vendor/                # Dependencias Composer
│   └── autoload.php       # Carga automática de librerías
├── .env                   # Variables de entorno
├── docker-compose.yml     # Configuración Docker
├── Dockerfile             # Imagen PHP-Apache
└── composer.json          # Dependencias PHP 
```

## ✅ Requisitos previos

- Docker (versión 20.10 o superior)  
- Docker Compose (versión 1.29 o superior)  
- Git  

> **Nota:** No es necesario tener PHP, Composer ni PostgreSQL instalados localmente, ya que todo se ejecuta dentro de contenedores Docker.

## 🚀 Instrucciones de instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/tienda-miyagi-do.git
cd tienda-miyagi-do
```

### 2. Configurar variables de entorno

El archivo `.env` ya está configurado con valores por defecto. Puedes modificar las credenciales si es necesario:

```env
APP_PORT=8080
POSTGRES_DB=Bd_MiyagiDo
POSTGRES_USER=root
POSTGRES_PASSWORD=123456
```

### 3. Levantar los contenedores

```bash
docker-compose up --build -d
```

Esto construirá las imágenes y levantará tres servicios:
- Aplicación PHP-Apache (puerto 8080)
- Base de datos PostgreSQL (puerto 5432)
- Adminer para gestión de BD (puerto 8081)

### 4. Instalar dependencias PHP

```bash
docker exec -it tienda_Miyagi-Do composer install
```

### 5. Verificar la instalación

Accede a [http://localhost:8080](http://localhost:8080)

## 💻 Uso básico

### Acceso a la aplicación

**Aplicación web:** [http://localhost:8080](http://localhost:8080)

#### Credenciales de prueba

**Administrador:**
- Usuario: `moya`
- Contraseña: `admin`

**Usuarios normales:**
- Usuario: `garci` – Contraseña: `user1`
- Usuario: `jim` – Contraseña: `user2`

### Acceso a Adminer

**URL:** [http://localhost:8081](http://localhost:8081)

- Sistema: PostgreSQL
- Servidor: `postgres-db`
- Usuario: `root`
- Contraseña: `123456`
- Base de datos: `Bd_MiyagiDo`

## ⚡ Funcionalidades

### Como usuario invitado:
- Ver catálogo de productos
- Buscar productos por marca o color
- Ver detalles de cada producto

### Como administrador (después de hacer login):
- Todas las funcionalidades anteriores
- Crear nuevos productos con imagen
- Editar información de productos existentes
- Actualizar imágenes de productos
- Eliminar productos del catálogo

## 🧭 Navegación

- **Página principal:** Muestra el listado completo de productos con buscador
- **Detalles:** Para ver la información completa de un producto
- **Crear/Editar/Imagen/Eliminar:** Solo visible para administradores
- **Login/Logout:** Disponible en la barra de navegación superior

## 🗄️ Base de datos

La base de datos PostgreSQL incluye cuatro tablas principales:

- **productos:** marca, modelo, descripción, precio, stock, color, talla, imagen y relación con categoría
- **categorias:** nombre de cada categoría de productos
- **usuarios:** credenciales de acceso y roles
- **user_roles:** relación de usuarios con roles (USER, ADMIN)

El script `database/init.sql` crea la estructura completa y datos de ejemplo al iniciar el contenedor.

## 👨‍💻 Autor

**Marcos Aaron Moya Maldonado**  
Estudiante de 2º DAW Semipresencial, IES Juan de Garay  
GitHub: [@Ma83rcos](https://github.com/Ma83rcos)

## 📄 Licencia

Licencia Creative Commons Reconocimiento-CompartirIgual 4.0 Internacional (CC BY-SA 4.0)

---

⭐ Si te ha gustado este proyecto, ¡dale una estrella en GitHub!