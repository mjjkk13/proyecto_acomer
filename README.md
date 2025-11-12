# 🍽️ A Comer

**A Comer** es un sistema web de **control de acceso a los comedores escolares** de los colegios distritales de Bogotá.  
Permite registrar, validar y gestionar el ingreso de estudiantes al comedor mediante una interfaz moderna desarrollada con **React + Vite** y un backend robusto en **PHP puro**, conectado a una base de datos **MySQL**.

---

## 🚀 Tecnologías principales

- **Frontend:** React + Vite  
- **Backend:** PHP 8+  
- **Base de datos:** MySQL  
- **Servidor:** Apache
- **Lenguajes:** JavaScript (ES6+), PHP, HTML5, CSS3  

---

## 🧩 Características principales

- 📋 Registro de estudiantes y asignación a docentes.  
- 🧾 Control de ingreso y asistencia al comedor.  
- 🔍 Validación de identidad por código QR.  
- 📊 Panel administrativo con estadísticas y reportes.  
- 🧠 Arquitectura modular separando frontend y backend.  
- 🔐 Control de roles para personal autorizado.  

---

## 📂 Estructura del proyecto

```
proyecto_acomer/
├── server/
│   ├── db/           # Base de datos
│   ├── php/          # Lógica del lado del servidor en PHP
│  │
├── frontend/
│   ├── src/              # Código fuente de React
│   ├── public/           # Archivos estáticos
│   ├── package.json      # Dependencias del frontend
│   └── vite.config.js    # Configuración de Vite
│
└── README.md
```

---

## ⚙️ Instalación y configuración

### 1️⃣ Clonar el repositorio
```bash
git clone https://github.com/mjjkk13/proyecto_acomer.git
cd proyecto_acomer
```

### 2️⃣ Configurar el backend (PHP + MySQL)
1. Copia la carpeta `/server` en el directorio raíz de tu servidor local (por ejemplo `htdocs` en XAMPP o `www` en WAMP).  
2. Edita el archivo `server/php/conexion.php` con los datos de conexión a tu base de datos:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'acomer_db');
   ```
3. Importa el archivo SQL ubicado en `server/db/acomer.sql` dentro de tu gestor MySQL:
   ```bash
   mysql -u root -p acomer_db < server/db/acomer.sql
   ```

### 3️⃣ Configurar el frontend (React + Vite)
```bash
cd frontend
npm install
npm run dev
```

Abre [http://localhost:5173](http://localhost:5173) en tu navegador para ver la aplicación en modo desarrollo.

---

## 🧠 Scripts útiles

| Comando | Descripción |
|----------|-------------|
| `npm run dev` | Ejecuta el frontend en modo desarrollo |
| `npm run build` | Genera la versión optimizada para producción |
| `npm run preview` | Previsualiza el build localmente |

---

## 🌐 Despliegue en producción

1. Genera la versión optimizada del frontend:
   ```bash
   npm run build
   ```
2. Copia los archivos generados en `/frontend/dist` dentro del servidor PHP (por ejemplo `/backend/public`).  
3. Configura tu servidor Apache o Nginx para redirigir todas las rutas a `index.html` de React.  
4. Verifica que PHP y MySQL estén correctamente configurados y en ejecución.

---

## 👨‍💻 Autores

| Nombre | Rol y responsabilidades |
|---------|--------------------------|
| **Juan Cárdenas** | Desarrollo backend en PHP (lógica de negocio y conexión con MySQL) |
| **Kevin García** | Desarrollo backend en PHP (controladores y servicios) |
| **Mariana Jiménez** | Desarrollo frontend (React + Vite), diseño UI/UX, integración con backend, configuración de base de datos MySQL y liderazgo del grupo |

📍 Proyecto desarrollado en Bogotá, Colombia.  

---

## 🪪 Licencia

Este proyecto se distribuye bajo la licencia **MIT**.  
Eres libre de usar, modificar y distribuir el código con atribución a los autores originales.

---

## 🏫 Contexto

Proyecto académico para grado del SENA orientado a la mejora del **Programa de Alimentación Escolar (PAE)** en los colegios distritales de Bogotá, proporcionando una solución tecnológica para el **control de acceso y trazabilidad del servicio de comedor escolar**.

---
