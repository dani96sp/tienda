CREATE DATABASE u136110db1;

USE u136110db1;

CREATE TABLE usuarios (
username CHAR(30) PRIMARY KEY,
password CHAR(255) NOT NULL,
nombre CHAR(50) NOT NULL,
telefono CHAR(20) NOT NULL,
email CHAR(40) NOT NULL,
tipo ENUM('Cliente', 'Empleado', 'SuperUsuario') NOT NULL
);

INSERT INTO usuarios (username, password, nombre, telefono, email, tipo) VALUES ('juanjoseml', '$2y$10$MOn0gwmFcgAbI4ls8bIbGei5Jmk4NZHqlHxLWW/Uham47EUzn62m.', 'Juan Jose Martinez Lopez', '654567876', 'juanjo@hotmail.com', 'empleado'),('cliente', '$2y$10$elG.6L1K6USIGe37lqWL4.O7xU.39TszIuPOMe86v85nuVOerWVz2', 'Cliente Normal', '666444333', 'cliente@cliente.com', 'Cliente'),('admin', '$2y$10$1VXEImxtKKF.kQeA2l4w6u5.Gc0S1r8HGkMUUQV.NRDJMWFBDPSwu', 'Admin Adm', '666444333', 'admin@dandocabezazos.es', 'SuperUsuario');

CREATE TABLE articulos (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre CHAR(50) NOT NULL,
descripcion TEXT NOT NULL,
precio DECIMAL(10,2) NOT NULL,
oferta ENUM('no', 'si') NOT NULL,
stock ENUM('si', 'no') NOT NULL
);
INSERT INTO `articulos` (`nombre`, `descripcion`, `precio`, `oferta`, `stock`) VALUES
('Rueda', 'Rueda exclusiva de última generación.', '12.34', 'no', 'si'),
('Pepinillo', 'Pepinillo fresco.', '3.99', 'no', 'si'),
('Sombrero', 'Sombrero de la mejor calidad.', '19.99', 'no', 'si'),
('Gafas de sol', 'Gafas de sol edición limitada.', '79.99', 'no', 'si'),
('Plátano', 'Plátanos recien cogidos, los más frescos.', '2.99', 'no', 'si'),
('Yogur', 'Yogur de la marca Danone.', '4.99', 'no', 'si'),
('Monitor 24 pulgadas', 'Monitor 24 pulgadas LED con resolución 4k', '239.99', 'no', 'si'),
('Teclado y Ratón', 'Pack teclado y ratón gaming.', '49.99', 'no', 'si'),
('Auriculares gaming', 'Auriculares con micrófono gaming.', '79.99', 'no', 'si'),
('Móvil chino', 'Móvil chino traido de china hecho en china.', '19.99', 'si', 'si'),
('Alfombrilla gaming.', 'Alfombrilla gaming edición limitada.', '5.99', 'no', 'si'),
('Papel higiénico', 'Papel higiénico del mejor.', '7.99', 'no', 'si'),
('Cargador usb universal.', 'Cargador usb universal para todos los dispositivos.', '29.99', 'no', 'si');


CREATE TABLE pedidos (
numpedido INT AUTO_INCREMENT PRIMARY KEY,
cliente CHAR(30) REFERENCES usuarios(username),
fecha TIMESTAMP NOT NULL
);

INSERT INTO pedidos (cliente, fecha) VALUES ('juanjoseml','2017-03-25 15:53:20'),('cliente','2017-04-20 20:06:50'),('cliente','2017-05-10 12:33:00');

CREATE TABLE lineapedido (
numpedido INT REFERENCES pedidos(numpedido),
numorden INT NOT NULL,
codarticulo INT REFERENCES articulos(id),
cantidad INT NOT NULL,
precio DECIMAL(10,2) NOT NULL,
PRIMARY KEY (numpedido, numorden)
);

INSERT INTO lineapedido (numpedido, numorden, codarticulo, cantidad, precio) VALUES (1,1,1,1,12.34),(1,2,2,2,7.98),(1,3,5,6,17.94),(1,4,4,4,319.96),(2,1,7,2,479.98),(2,2,3,6,199.94),(3,1,5,7,20.93);

CREATE TABLE categorias (
id INT AUTO_INCREMENT,
articulo INT REFERENCES articulos(id),
nombre CHAR(30) NOT NULL,
padre CHAR(30) REFERENCES categorias(nombre),
PRIMARY KEY(id)
);

INSERT INTO categorias (nombre) VALUES ('Videojuegos'),('Telefonia'),('Electrodomesticos'),('Hogar'),('Mascotas'),('Escolar'),('Higiene'),('Muebles'),('Peliculas'),('Libros');
INSERT INTO categorias (nombre, articulo) VALUES ('Videojuegos', 8),('Videojuegos', 9),('Videojuegos', 11),('Telefonia', 10),('Telefonia', 13),('Electrodomesticos', 1),('Electrodomesticos', 7),('Hogar', 12),('Mascotas', 2),('Escolar', 5),('Higiene', 12),('Muebles', 4),('Peliculas', 6),('Libros', 3),('Videojuegos', 1),('Videojuegos', 2),('Videojuegos', 3),('Telefonia', 11),('Electrodomesticos', 2),('Electrodomesticos', 8),('Hogar', 9),('Mascotas', 3),('Escolar', 10),('Higiene', 10),('Muebles', 2),('Peliculas', 6),('Libros', 1);


