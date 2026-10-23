# Sistema de Administración Gerencial

Sistema de administración/gestión gerencial en PHP puro con patrón MVC hecho a mano (sin framework), pensado para correr en local con Laragon.

## Qué incluye

- **Login** con sesiones y contraseñas cifradas (bcrypt).
- **Departamentos**: crear, editar, ver cuántos empleados activos tiene cada uno.
- **Empleados**: crear, editar, desactivar, filtrar por departamento.
- **Proyectos**: crear, cambiar de estado (planificación, en progreso, completado, cancelado), asignados a un departamento.
- **Tareas**: cada proyecto tiene su propio tablero (estilo kanban) con tres columnas — Pendiente, En progreso, Completada — hecho con Vue. Se mueven con un clic, sin recargar manualmente.
- **Panel principal**: empleados activos, departamentos, proyectos en progreso, tareas pendientes/vencidas, gráfico de barras de empleados por departamento y los proyectos más recientes con su avance.
- **Listado de tareas** global, filtrable por estado y por empleado, con aviso visual de tareas vencidas.

## Stack

- **Backend:** PHP 8.3, PDO con MySQL, MVC propio (sin Composer, sin dependencias).
- **Frontend:** Bootstrap 5 (estilos) + Vue 3 (el tablero de tareas del proyecto), ambos por CDN. No hace falta `npm install` ni compilar nada.

## Cómo correrlo en local (con Laragon)

1. **Prender MySQL** desde Laragon.
2. **Crear la base de datos** importando el esquema:
   ```
   C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe -u root < database/schema.sql
   ```
   (o abrí `database/schema.sql` desde HeidiSQL / phpMyAdmin de Laragon y ejecutalo ahí).
3. **Configurar el entorno**: copiá `.env.example` a `.env`. Los valores por defecto ya funcionan con Laragon (usuario `root`, sin contraseña).
4. **Levantar el servidor**, desde la carpeta del proyecto:
   ```
   php -S localhost:8001 -t public
   ```
5. Abrí **http://localhost:8001** en el navegador.

### Usuario de prueba

- Correo: `admin@gerencia.local`
- Contraseña: `Admin123*`

## Estructura

```
app/
  Core/         Router, Database, Controller y Model base (el "framework" propio)
  Controllers/  Un controlador por sección
  Models/       Un modelo por tabla
  Views/        Plantillas PHP, con layout.php como envoltorio común
config/         Lee el .env y arma la configuración
database/       schema.sql con las tablas y datos iniciales (departamentos, empleados, proyectos y tareas de ejemplo)
public/         Punto de entrada (index.php) y archivos públicos
```

## Nota

Proyecto hecho como pieza de portafolio individual, con fines de aprendizaje y referencia laboral.
