
CREATE DATABASE IF NOT EXISTS Faction;
DROP DATABASE IF EXISTS Faction;
USE Faction;

CREATE TABLE `usuarios`(
    `id`     int          NOT NULL AUTO_INCREMENT,
    `nombre` varchar(100) NOT NULL,
    `email`  varchar(100) NOT NULL,
    `password`  varchar(255) NOT NULL,
    `rol`  enum('admin', 'user') NOT NULL DEFAULT 'user',
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
);  

-- 2. Facciones 
CREATE TABLE facciones (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    lider_id INT NOT NULL, 
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

-- 3. Miembros 
CREATE TABLE miembros (
    id INT NOT NULL AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    faccion_id INT NOT NULL,
    rango ENUM('lider', 'oficial', 'miembro') DEFAULT 'miembro',
    fecha_ingreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (faccion_id) REFERENCES facciones(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- 4. Claims
CREATE TABLE claims (
    id INT NOT NULL AUTO_INCREMENT,
    faccion_id INT NOT NULL,
    coordenada_inicio VARCHAR(50) NOT NULL,
    coordenada_fin VARCHAR(50) NOT NULL,
    mundo ENUM('overworld', 'nether', 'end') NOT NULL DEFAULT 'overworld',
    PRIMARY KEY (id),
    FOREIGN KEY (faccion_id) REFERENCES facciones(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- 5. Invitaciones
CREATE TABLE invitaciones (
    id INT NOT NULL AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    faccion_id INT NOT NULL,
    estado ENUM('pendiente', 'aceptada', 'rechazada') DEFAULT 'pendiente',
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (faccion_id) REFERENCES facciones(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- 6. Eventos
CREATE TABLE eventos (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('koth', 'captura', 'raid') DEFAULT 'koth',
    descripcion TEXT,
    fecha_inicio DATETIME,
    fecha_fin DATETIME,
    faccion_ganadora_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (faccion_ganadora_id) REFERENCES facciones(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);


INSERT INTO facciones (nombre, descripcion, lider_id) VALUES
('ObsidianClan', 'Faccion full defensa y territorio', 1),
('EndSlayers', 'Los dueños del end y koths', 2);


-- -----------------------------
-- INSERTS: Miembros (3)
-- -----------------------------
INSERT INTO miembros (usuario_id, faccion_id, rango) VALUES
(1, 1, 'lider'),
(3, 1, 'miembro'),
(2, 2, 'lider');

-- -----------------------------
-- INSERTS: Claims (2)
-- -----------------------------
INSERT INTO claims (faccion_id, coordenada_inicio, coordenada_fin, mundo) VALUES
(1, '100,64,100', '150,64,150', 'overworld'),
(2, '0,64,0', '50,64,50', 'end');

-- -----------------------------
-- INSERTS: Invitaciones (2)
-- -----------------------------
INSERT INTO invitaciones (usuario_id, faccion_id, estado) VALUES
(4, 1, 'pendiente'),
(5, 2, 'aceptada');

-- -----------------------------
-- INSERTS: Eventos (1)
-- -----------------------------
INSERT INTO eventos (nombre, tipo, descripcion, fecha_inicio, fecha_fin, faccion_ganadora_id) VALUES
('KOTH Central', 'koth', 'Evento mensual por control del centro', '2025-08-01 18:00:00', '2025-08-01 18:30:00', 2);