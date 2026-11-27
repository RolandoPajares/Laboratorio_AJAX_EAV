USE laboratorio_ajax_eav;
START TRANSACTION;

INSERT INTO categorias (id, nombre) VALUES
(1,'Ropa de Hombre'),(2,'Teléfonos'),(3,'Computadoras'),(4,'Calzado'),(5,'Televisores'),(6,'Electrodomésticos'),(7,'Accesorios');

INSERT INTO atributos (id, nombre) VALUES
(1,'Marca'),(2,'Color'),(3,'Talla'),(4,'Año'),(5,'Sist. Operativo'),(6,'Almacenamiento'),
(7,'Material'),(8,'Memoria RAM'),(9,'Procesador'),(10,'Resolución'),(11,'Tamaño de pantalla'),
(12,'Capacidad'),(13,'Potencia'),(14,'Conectividad'),(15,'Tipo de calzado');

INSERT INTO productos (id, nombre, id_categoria) VALUES
(1,'Polo Casual',1),(2,'Smartphone X1',2),(3,'Camisa Oxford',1),(4,'Chaqueta Urbana',1),
(5,'Laptop Pro 14',3),(6,'Notebook Andina',3),(7,'PC Gamer Cóndor',3),
(8,'Zapatilla Runner',4),(9,'Botín Montaña',4),(10,'Sandalia Verano',4),
(11,'Smart TV 55 Ultra',5),(12,'Televisor Andino 43',5),(13,'OLED Cinema 65',5),
(14,'Refrigeradora Frío Plus',6),(15,'Licuadora TurboMix',6),(16,'Lavadora Eco 10K',6),
(17,'Auriculares Nova',7),(18,'Mouse Óptico Pro',7),(19,'Teclado Mecánico RGB',7),
(20,'Polo Deportivo',1),(21,'Teléfono Xiaomi Note',2),(22,'Ultrabook Amazonas',3),
(23,'Zapatilla Urbana',4),(24,'Monitor Smart 27',5);

INSERT INTO valores_productos (producto_id, atributo_id, valor) VALUES
(1,1,'Lacoste'),(1,2,'Rojo'),(1,3,'L'),
(2,1,'Samsung'),(2,4,'2025'),(2,5,'Android 15'),(2,6,'256GB'),(2,2,'Azul'),
(3,1,'Lacoste'),(3,2,'Blanco'),(3,3,'M'),(3,7,'Algodón'),
(4,1,'North Face'),(4,2,'Negro'),(4,3,'L'),(4,7,'Poliéster'),
(5,1,'Lenovo'),(5,2,'Gris'),(5,8,'16GB'),(5,9,'Intel Core i7'),(5,6,'512GB'),(5,5,'Windows 11'),
(6,1,'HP'),(6,2,'Azul'),(6,8,'8GB'),(6,9,'Intel Core i5'),(6,6,'256GB'),(6,5,'Windows 11'),
(7,1,'Asus'),(7,2,'Negro'),(7,8,'32GB'),(7,9,'AMD Ryzen 7'),(7,6,'1TB'),(7,5,'Windows 11'),
(8,1,'Nike'),(8,2,'Negro'),(8,3,'42'),(8,15,'Deportivo'),(8,7,'Malla'),
(9,1,'North Face'),(9,2,'Marrón'),(9,3,'43'),(9,15,'Botín'),(9,7,'Cuero'),
(10,1,'Adidas'),(10,2,'Blanco'),(10,3,'38'),(10,15,'Sandalia'),(10,7,'Sintético'),
(11,1,'Samsung'),(11,2,'Negro'),(11,4,'2024'),(11,10,'4K'),(11,11,'55 pulgadas'),(11,14,'Wi-Fi'),
(12,1,'LG'),(12,2,'Gris'),(12,4,'2023'),(12,10,'4K'),(12,11,'43 pulgadas'),(12,14,'Wi-Fi'),
(13,1,'Sony'),(13,2,'Negro'),(13,4,'2025'),(13,10,'4K'),(13,11,'65 pulgadas'),(13,14,'Bluetooth'),
(14,1,'LG'),(14,2,'Plata'),(14,12,'450L'),(14,13,'500W'),(14,14,'Wi-Fi'),
(15,1,'Oster'),(15,2,'Rojo'),(15,12,'2L'),(15,13,'700W'),(15,14,'Bluetooth'),
(16,1,'Samsung'),(16,2,'Blanco'),(16,12,'10kg'),(16,13,'600W'),(16,14,'Wi-Fi'),
(17,1,'Sony'),(17,2,'Negro'),(17,13,'30W'),(17,14,'Bluetooth'),(17,7,'Plástico'),
(18,1,'Logitech'),(18,2,'Negro'),(18,14,'USB'),(18,7,'Plástico'),
(19,1,'Logitech'),(19,2,'Negro'),(19,14,'USB'),(19,7,'Aluminio'),
(20,1,'Adidas'),(20,2,'Azul'),(20,3,'M'),(20,7,'Algodón'),
(21,1,'Xiaomi'),(21,2,'Negro'),(21,4,'2025'),(21,5,'Android 14'),(21,6,'128GB'),(21,14,'Bluetooth'),
(22,1,'Lenovo'),(22,2,'Plata'),(22,8,'16GB'),(22,9,'AMD Ryzen 5'),(22,6,'512GB'),(22,5,'Windows 11'),
(23,1,'Adidas'),(23,2,'Blanco'),(23,3,'40'),(23,15,'Deportivo'),(23,7,'Malla'),
(24,1,'LG'),(24,2,'Negro'),(24,10,'QHD'),(24,11,'27 pulgadas'),(24,14,'HDMI');

COMMIT;
