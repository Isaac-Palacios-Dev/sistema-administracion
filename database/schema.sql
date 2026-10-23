-- Esquema del Sistema de Administración Gerencial
-- Importar con: mysql -u root -p < database/schema.sql
-- (o desde phpMyAdmin / HeidiSQL de Laragon)

CREATE DATABASE IF NOT EXISTS sistema_administracion
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE sistema_administracion;

-- ---------------------------------------------------------------------------
-- Usuarios del sistema (quienes inician sesión)
-- ---------------------------------------------------------------------------
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Departamentos de la empresa
-- ---------------------------------------------------------------------------
CREATE TABLE departamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT '',
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Empleados, cada uno asignado a un departamento
-- ---------------------------------------------------------------------------
CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    apellido VARCHAR(80) NOT NULL,
    documento VARCHAR(20) NOT NULL UNIQUE,
    correo VARCHAR(150) NOT NULL UNIQUE,
    telefono VARCHAR(20) DEFAULT '',
    puesto VARCHAR(100) NOT NULL,
    departamento_id INT NOT NULL,
    salario DECIMAL(10, 2) NOT NULL DEFAULT 0,
    fecha_contratacion DATE NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_empleado_departamento FOREIGN KEY (departamento_id) REFERENCES departamentos(id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Proyectos, cada uno a cargo de un departamento
-- ---------------------------------------------------------------------------
CREATE TABLE proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    departamento_id INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin_estimada DATE DEFAULT NULL,
    estado ENUM('planificacion', 'en_progreso', 'completado', 'cancelado') NOT NULL DEFAULT 'planificacion',
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_proyecto_departamento FOREIGN KEY (departamento_id) REFERENCES departamentos(id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Tareas de cada proyecto, asignadas opcionalmente a un empleado
-- ---------------------------------------------------------------------------
CREATE TABLE tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    empleado_id INT DEFAULT NULL,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT,
    estado ENUM('pendiente', 'en_progreso', 'completada') NOT NULL DEFAULT 'pendiente',
    prioridad ENUM('baja', 'media', 'alta') NOT NULL DEFAULT 'media',
    fecha_limite DATE DEFAULT NULL,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tarea_proyecto FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    CONSTRAINT fk_tarea_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE INDEX idx_empleados_departamento ON empleados(departamento_id);
CREATE INDEX idx_proyectos_departamento ON proyectos(departamento_id);
CREATE INDEX idx_tareas_proyecto ON tareas(proyecto_id);
CREATE INDEX idx_tareas_empleado ON tareas(empleado_id);

-- ---------------------------------------------------------------------------
-- Datos iniciales
-- ---------------------------------------------------------------------------

-- Usuario admin: correo admin@gerencia.local / clave: Admin123*
INSERT INTO usuarios (nombre, correo, clave) VALUES
    ('Administrador', 'admin@gerencia.local', '$2y$10$kQBmFL0YF0ddcupiNl0yy.kLs4lMIDWk6DXH0E7kcOA/8conW1t86');

INSERT INTO departamentos (nombre, descripcion) VALUES
    ('Gerencia General', 'Dirección y planificación estratégica'),
    ('Recursos Humanos', 'Contratación, nómina y bienestar del personal'),
    ('Ventas', 'Comercialización y atención a clientes'),
    ('Tecnología', 'Desarrollo y soporte de sistemas'),
    ('Finanzas', 'Contabilidad, tesorería y presupuestos');

INSERT INTO empleados (nombre, apellido, documento, correo, telefono, puesto, departamento_id, salario, fecha_contratacion) VALUES
    ('Carlos', 'Méndez', '01010101-1', 'carlos.mendez@empresa.local', '7000-0001', 'Gerente General', 1, 2200.00, '2022-01-10'),
    ('Ana', 'Ramírez', '02020202-2', 'ana.ramirez@empresa.local', '7000-0002', 'Jefa de Recursos Humanos', 2, 1400.00, '2022-03-15'),
    ('Luis', 'Fernández', '03030303-3', 'luis.fernandez@empresa.local', '7000-0003', 'Ejecutivo de Ventas', 3, 900.00, '2023-02-01'),
    ('María', 'González', '04040404-4', 'maria.gonzalez@empresa.local', '7000-0004', 'Desarrolladora de Software', 4, 1300.00, '2023-05-20'),
    ('Jorge', 'Castillo', '05050505-5', 'jorge.castillo@empresa.local', '7000-0005', 'Analista Financiero', 5, 1100.00, '2023-08-11'),
    ('Pamela', 'Ortiz', '06060606-6', 'pamela.ortiz@empresa.local', '7000-0006', 'Soporte Técnico', 4, 850.00, '2024-01-09');

INSERT INTO proyectos (nombre, descripcion, departamento_id, fecha_inicio, fecha_fin_estimada, estado) VALUES
    ('Plan estratégico 2026', 'Definición de objetivos y metas anuales de la empresa.', 1, '2026-01-05', '2026-03-31', 'en_progreso'),
    ('Rediseño del portal de ventas', 'Nueva plataforma web para el equipo comercial.', 4, '2026-02-01', '2026-06-30', 'en_progreso'),
    ('Programa de capacitación 2026', 'Capacitaciones trimestrales para todo el personal.', 2, '2026-01-15', '2026-12-15', 'planificacion'),
    ('Auditoría financiera anual', 'Revisión de estados financieros del año anterior.', 5, '2025-11-01', '2026-01-31', 'completado');

INSERT INTO tareas (proyecto_id, empleado_id, titulo, descripcion, estado, prioridad, fecha_limite) VALUES
    (1, 1, 'Definir objetivos por departamento', 'Reunión con cada jefatura para fijar metas.', 'en_progreso', 'alta', '2026-02-15'),
    (1, 2, 'Levantar presupuesto de RRHH', 'Estimar costos de contratación y capacitación.', 'pendiente', 'media', '2026-02-20'),
    (2, 4, 'Diseñar wireframes del portal', 'Bocetos de las pantallas principales.', 'completada', 'alta', '2026-02-10'),
    (2, 4, 'Implementar catálogo de productos', 'Listado y filtros de productos en el portal.', 'en_progreso', 'alta', '2026-04-01'),
    (2, 6, 'Pruebas de soporte técnico', 'Validar que el portal funcione en distintos navegadores.', 'pendiente', 'media', '2026-05-15'),
    (3, 2, 'Elegir proveedor de capacitaciones', 'Cotizar al menos 3 proveedores.', 'pendiente', 'baja', '2026-03-01'),
    (4, 5, 'Conciliar cuentas bancarias', 'Cruce de movimientos del último trimestre.', 'completada', 'alta', '2026-01-20'),
    (4, 5, 'Entregar informe final', 'Informe de auditoría para la gerencia.', 'completada', 'media', '2026-01-31');
