CREATE DATABASE IF NOT EXISTS laboratorio_ajax_eav CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE laboratorio_ajax_eav;

CREATE TABLE IF NOT EXISTS categorias (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(150) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_categorias_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS productos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(200) NOT NULL,
  id_categoria INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  KEY idx_productos_categoria_id (id_categoria, id),
  CONSTRAINT fk_productos_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS atributos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(150) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_atributos_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS valores_productos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  producto_id INT UNSIGNED NOT NULL,
  atributo_id INT UNSIGNED NOT NULL,
  valor VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_valor_producto_atributo (producto_id, atributo_id),
  KEY idx_valores_atributo_valor_producto (atributo_id, valor, producto_id),
  CONSTRAINT fk_valores_producto FOREIGN KEY (producto_id) REFERENCES productos(id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_valores_atributo FOREIGN KEY (atributo_id) REFERENCES atributos(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
