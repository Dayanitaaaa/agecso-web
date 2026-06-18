-- Base de datos: agecso_web
-- Para ejecutar en phpMyAdmin o terminal MySQL

CREATE DATABASE IF NOT EXISTS agecso_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agecso_web;

-- Tabla de administradores
CREATE TABLE IF NOT EXISTS usuarios_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'editor') DEFAULT 'editor',
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de noticias
CREATE TABLE IF NOT EXISTS noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    resumen TEXT,
    contenido LONGTEXT,
    imagen VARCHAR(255),
    estado ENUM('publicado', 'borrador', 'archivado') DEFAULT 'borrador',
    fecha_publicacion DATE,
    autor_id INT,
    vistas INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de eventos
CREATE TABLE IF NOT EXISTS eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    descripcion TEXT,
    contenido LONGTEXT,
    imagen VARCHAR(255),
    fecha_evento DATE,
    hora_inicio TIME,
    hora_fin TIME,
    lugar VARCHAR(255),
    estado ENUM('programado', 'realizado', 'cancelado') DEFAULT 'programado',
    tipo ENUM('evento', 'taller', 'conversatorio', 'feria', 'rueda') DEFAULT 'evento',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de cursos y webinars
CREATE TABLE IF NOT EXISTS cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    descripcion TEXT,
    contenido LONGTEXT,
    imagen VARCHAR(255),
    tipo ENUM('curso', 'webinar', 'capacitacion') DEFAULT 'curso',
    fecha_inicio DATE,
    fecha_fin DATE,
    duracion VARCHAR(100),
    instructor VARCHAR(150),
    estado ENUM('programado', 'en_curso', 'finalizado', 'cancelado') DEFAULT 'programado',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de aliados
CREATE TABLE IF NOT EXISTS aliados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    descripcion TEXT,
    logo VARCHAR(255),
    sitio_web VARCHAR(255),
    categoria VARCHAR(100),
    orden INT DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de servicios
CREATE TABLE IF NOT EXISTS servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    descripcion TEXT,
    contenido LONGTEXT,
    icono VARCHAR(50) DEFAULT 'bi-star',
    orden INT DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de mensajes de contacto
CREATE TABLE IF NOT EXISTS mensajes_contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefono VARCHAR(50),
    asunto VARCHAR(255),
    mensaje TEXT NOT NULL,
    leido TINYINT(1) DEFAULT 0,
    respondido TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar usuario admin por defecto
-- Password: admin123 (encriptado con password_hash)
INSERT IGNORE INTO usuarios_admin (nombre, email, password, rol) VALUES 
('Administrador', 'admin@agecso.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insertar servicios por defecto
INSERT IGNORE INTO servicios (titulo, slug, descripcion, icono, orden) VALUES
('Generamos Negocios', 'generamos-negocios', 'Relacionamiento con empresarios y otras agremiaciones para fomentar el desarrollo de negocios.', 'bi-briefcase', 1),
('Atracción del Talento Humano', 'atraccion-talento-humano', 'Alianzas con las Agencias Públicas de Empleo de COMPENSAR y CAFAM para atraer el mejor talento.', 'bi-people', 2),
('Representación Gremial', 'representacion-gremial', 'Representación ante entidades públicas y contribución en la construcción de políticas públicas.', 'bi-megaphone', 3),
('Educación Corporativa', 'educacion-corporativa', 'Capacitaciones mensuales para fortalecer a nuestros agremiados.', 'bi-book', 4),
('Estructuración y Presentación de Proyectos', 'estructuracion-proyectos', 'Presentamos proyectos ante entidades públicas y privadas mediante alianzas estratégicas entre asociados.', 'bi-folder', 5),
('Eventos Mensuales', 'eventos-mensuales', 'Organizamos eventos mensuales de networking para nuestros agremiados.', 'bi-calendar-event', 6),
('Publicidad y Promoción para Agremiados', 'publicidad-promocion', 'Promoción de productos y servicios en nuestra web, redes sociales y presentaciones comerciales.', 'bi-broadcast', 7),
('Tarifas Preferenciales', 'tarifas-preferenciales', 'Generamos sinergia entre agremiados para obtener tarifas preferenciales en productos y servicios.', 'bi-tag', 8),
('RSE', 'rse', 'Actividades de Responsabilidad Social Empresarial con la comunidad, emprendedores y estudiantes.', 'bi-heart', 9),
('Asesoría Corporativa', 'asesoria-corporativa', 'Asesoría en marketing, contabilidad, asuntos jurídicos y seguridad y salud en el trabajo.', 'bi-shield-check', 10),
('Alianzas Estratégicas', 'alianzas-estrategicas', 'Alianzas con otras agremiaciones, entidades gubernamentales y financieras.', 'bi-link', 11),
('Proyectos Colaborativos', 'proyectos-colaborativos', 'Soluciones a problemáticas empresariales mediante trabajo colaborativo.', 'bi-handshake', 12);
