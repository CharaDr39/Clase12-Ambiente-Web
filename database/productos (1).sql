
DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
CREATE TABLE IF NOT EXISTS `productos` (
  `id_producto` INT AUTO_INCREMENT PRIMARY KEY,
  `codigo` VARCHAR(50) NOT NULL UNIQUE,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `precio_compra` DECIMAL(10,2) NOT NULL,
  `precio_venta` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL,
  `estado` TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar al menos 5 productos de ejemplo
INSERT INTO `productos` (codigo, nombre, descripcion, precio_compra, precio_venta, stock, estado) VALUES
('P001', 'Laptop Básica', 'Computadora portátil de gama de entrada', 350.00, 400.00, 25, 1),
('P002', 'Smartphone Económico', 'Teléfono inteligente con funciones básicas', 150.00, 170.00, 50, 1),
('P003', 'Mouse Inalámbrico', 'Mouse ergonómico inalámbrico', 10.00, 12.00, 100, 1),
('P004', 'Teclado Mecánico', 'Teclado mecánico con retroiluminación', 45.00, 55.00, 40, 1),
('P005', 'Monitor 24 pulgadas', 'Monitor LED de 24" Full HD', 80.00, 95.00, 30, 1);