<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201006194947 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ajustes_stock (id INT AUTO_INCREMENT NOT NULL, motivo_id INT NOT NULL, usuario_id INT NOT NULL, producto_id INT NOT NULL, almacen_id INT NOT NULL, fecha DATETIME NOT NULL, cantidad INT NOT NULL, observaciones VARCHAR(255) NOT NULL, stock_anterior INT NOT NULL, INDEX IDX_1477ECF5F9E584F8 (motivo_id), INDEX IDX_1477ECF5DB38439E (usuario_id), INDEX IDX_1477ECF57645698E (producto_id), INDEX IDX_1477ECF59C9C9E68 (almacen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE almacenes (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, direccion VARCHAR(255) NOT NULL, eliminado TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorias (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, id_padre INT NOT NULL, final INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clientes (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, apellido VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, dni VARCHAR(255) NOT NULL, telefono VARCHAR(255) DEFAULT NULL, direccion VARCHAR(255) DEFAULT NULL, habilitado TINYINT(1) NOT NULL, eliminado TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clientes_almacenes (id INT AUTO_INCREMENT NOT NULL, id_cliente_id INT DEFAULT NULL, id_almacen_id INT DEFAULT NULL, INDEX IDX_1F3D21AE7BF9CE86 (id_cliente_id), INDEX IDX_1F3D21AE39161EBF (id_almacen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE combos (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, porcentaje_costo DOUBLE PRECISION NOT NULL, precio_final DOUBLE PRECISION NOT NULL, descripcion LONGTEXT DEFAULT NULL, habilitado TINYINT(1) NOT NULL, eliminado TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE combos_galeria (id INT AUTO_INCREMENT NOT NULL, id_combo_id INT NOT NULL, url VARCHAR(255) NOT NULL, habilitado SMALLINT NOT NULL, INDEX IDX_D779D131BBBE6B76 (id_combo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE combos_productos (id INT AUTO_INCREMENT NOT NULL, id_producto_id INT NOT NULL, id_combo_id INT NOT NULL, cantidad INT NOT NULL, INDEX IDX_5A68FA3F6E57A479 (id_producto_id), INDEX IDX_5A68FA3FBBBE6B76 (id_combo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compras (id INT AUTO_INCREMENT NOT NULL, proveedor_id INT DEFAULT NULL, almacen_id INT DEFAULT NULL, fecha DATETIME NOT NULL, precio DOUBLE PRECISION DEFAULT NULL, estado VARCHAR(255) NOT NULL, INDEX IDX_3692E1B7CB305D73 (proveedor_id), INDEX IDX_3692E1B79C9C9E68 (almacen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compras_productos (id INT AUTO_INCREMENT NOT NULL, compra_id INT NOT NULL, producto_id INT NOT NULL, unidad_medida_id INT NOT NULL, cantidad INT NOT NULL, INDEX IDX_DC9917F7F2E704D7 (compra_id), INDEX IDX_DC9917F77645698E (producto_id), INDEX IDX_DC9917F72E003F4 (unidad_medida_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cotizaciones (id INT AUTO_INCREMENT NOT NULL, id_cliente_id INT NOT NULL, creador_id INT DEFAULT NULL, fecha DATETIME NOT NULL, estado VARCHAR(255) NOT NULL, hasta DATE DEFAULT NULL, descuento DOUBLE PRECISION DEFAULT NULL, INDEX IDX_12CE14AE7BF9CE86 (id_cliente_id), INDEX IDX_12CE14AE62F40C3D (creador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cotizaciones_productos (id INT AUTO_INCREMENT NOT NULL, id_cotizacion_id INT NOT NULL, id_producto_id INT NOT NULL, almacen_id INT DEFAULT NULL, cantidad INT NOT NULL, precio_actual DOUBLE PRECISION NOT NULL, costo_actual DOUBLE PRECISION NOT NULL, reserva_mercaderia TINYINT(1) NOT NULL, INDEX IDX_E238C0068E5841CF (id_cotizacion_id), INDEX IDX_E238C0066E57A479 (id_producto_id), INDEX IDX_E238C0069C9C9E68 (almacen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE materiales (id INT AUTO_INCREMENT NOT NULL, id_unidad_medida_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, codigo_barras VARCHAR(255) DEFAULT NULL, costo DOUBLE PRECISION DEFAULT NULL, stock_actual INT NOT NULL, INDEX IDX_BC062AC9E16A5625 (id_unidad_medida_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medios_pago (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, habilitado SMALLINT NOT NULL, eliminado SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE motivos_ajustes_stock (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE productos (id INT AUTO_INCREMENT NOT NULL, categoria_id INT NOT NULL, id_unidad_medida_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, codigo_barras VARCHAR(255) DEFAULT NULL, costo DOUBLE PRECISION NOT NULL, porcentaje_costo DOUBLE PRECISION NOT NULL, precio_final DOUBLE PRECISION NOT NULL, descripcion LONGTEXT DEFAULT NULL, habilitado TINYINT(1) NOT NULL, eliminado TINYINT(1) NOT NULL, imagen VARCHAR(255) DEFAULT NULL, stock_actual INT NOT NULL, stock_minimo INT NOT NULL, diametro DOUBLE PRECISION DEFAULT NULL, largo DOUBLE PRECISION DEFAULT NULL, ancho DOUBLE PRECISION DEFAULT NULL, color VARCHAR(255) DEFAULT NULL, material VARCHAR(255) DEFAULT NULL, utilidad LONGTEXT DEFAULT NULL, presentacion LONGTEXT DEFAULT NULL, INDEX IDX_767490E63397707A (categoria_id), INDEX IDX_767490E6E16A5625 (id_unidad_medida_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE productos_almacenes (id INT AUTO_INCREMENT NOT NULL, id_producto_id INT NOT NULL, id_almacen_id INT NOT NULL, stock INT NOT NULL, INDEX IDX_586E72EB6E57A479 (id_producto_id), INDEX IDX_586E72EB39161EBF (id_almacen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE productos_galeria (id INT AUTO_INCREMENT NOT NULL, id_producto_id INT NOT NULL, url VARCHAR(255) NOT NULL, habilitado SMALLINT NOT NULL, INDEX IDX_A10E30966E57A479 (id_producto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proveedores (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, cuit VARCHAR(255) DEFAULT NULL, telefono VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, direccion VARCHAR(255) DEFAULT NULL, notas LONGTEXT DEFAULT NULL, habilitado TINYINT(1) NOT NULL, eliminado TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proveedores_almacenes (id INT AUTO_INCREMENT NOT NULL, id_proveedor_id INT NOT NULL, id_almacen_id INT NOT NULL, INDEX IDX_B42CFF42E8F12801 (id_proveedor_id), INDEX IDX_B42CFF4239161EBF (id_almacen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proveedores_productos (id INT AUTO_INCREMENT NOT NULL, id_proveedor_id INT NOT NULL, id_producto_id INT NOT NULL, costo DOUBLE PRECISION DEFAULT NULL, INDEX IDX_8061F921E8F12801 (id_proveedor_id), INDEX IDX_8061F9216E57A479 (id_producto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servicios (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT DEFAULT NULL, habilitado TINYINT(1) NOT NULL, eliminado TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servicios_galeria (id INT AUTO_INCREMENT NOT NULL, id_servicio_id INT NOT NULL, url VARCHAR(255) NOT NULL, habilitado SMALLINT NOT NULL, INDEX IDX_9D252E9669D86E10 (id_servicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servicios_productos (id INT AUTO_INCREMENT NOT NULL, id_servicio_id INT NOT NULL, id_producto_id INT NOT NULL, INDEX IDX_962C75C069D86E10 (id_servicio_id), INDEX IDX_962C75C06E57A479 (id_producto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servicios_reparaciones (id INT AUTO_INCREMENT NOT NULL, id_servicio_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, precio DOUBLE PRECISION NOT NULL, tiempo_estimado INT DEFAULT NULL, descripcion LONGTEXT DEFAULT NULL, habilitado SMALLINT NOT NULL, eliminado SMALLINT NOT NULL, INDEX IDX_7C7310B369D86E10 (id_servicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servicios_reparaciones_materiales (id INT AUTO_INCREMENT NOT NULL, reparacion_id INT NOT NULL, material_id INT NOT NULL, cantidad INT NOT NULL, INDEX IDX_C5B3814AB0096FA (reparacion_id), INDEX IDX_C5B3814E308AC6F (material_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unidades_medida (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, nombre_padre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuarios (id INT AUTO_INCREMENT NOT NULL, rol_id INT NOT NULL, almacen_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(255) NOT NULL, apellido VARCHAR(255) NOT NULL, direccion VARCHAR(255) DEFAULT NULL, habilitado TINYINT(1) NOT NULL, eliminado TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_EF687F2E7927C74 (email), INDEX IDX_EF687F24BAB96C (rol_id), INDEX IDX_EF687F29C9C9E68 (almacen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ventas (id INT AUTO_INCREMENT NOT NULL, fecha DATETIME NOT NULL, estado VARCHAR(255) NOT NULL, precio_final DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ventas_pagos (id INT AUTO_INCREMENT NOT NULL, medio_pago_id INT NOT NULL, venta_id INT NOT NULL, fecha DATETIME NOT NULL, monto DOUBLE PRECISION NOT NULL, comprobante VARCHAR(255) DEFAULT NULL, url_comprobante VARCHAR(255) DEFAULT NULL, INDEX IDX_D3E05448CAEEDE5B (medio_pago_id), INDEX IDX_D3E05448F2A5805D (venta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ventas_productos (id INT AUTO_INCREMENT NOT NULL, venta_id INT NOT NULL, producto_id INT NOT NULL, unidad_medida_id INT NOT NULL, cantidad INT NOT NULL, costo DOUBLE PRECISION NOT NULL, INDEX IDX_20ABEB63F2A5805D (venta_id), INDEX IDX_20ABEB637645698E (producto_id), INDEX IDX_20ABEB632E003F4 (unidad_medida_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ajustes_stock ADD CONSTRAINT FK_1477ECF5F9E584F8 FOREIGN KEY (motivo_id) REFERENCES motivos_ajustes_stock (id)');
        $this->addSql('ALTER TABLE ajustes_stock ADD CONSTRAINT FK_1477ECF5DB38439E FOREIGN KEY (usuario_id) REFERENCES usuarios (id)');
        $this->addSql('ALTER TABLE ajustes_stock ADD CONSTRAINT FK_1477ECF57645698E FOREIGN KEY (producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE ajustes_stock ADD CONSTRAINT FK_1477ECF59C9C9E68 FOREIGN KEY (almacen_id) REFERENCES almacenes (id)');
        $this->addSql('ALTER TABLE clientes_almacenes ADD CONSTRAINT FK_1F3D21AE7BF9CE86 FOREIGN KEY (id_cliente_id) REFERENCES clientes (id)');
        $this->addSql('ALTER TABLE clientes_almacenes ADD CONSTRAINT FK_1F3D21AE39161EBF FOREIGN KEY (id_almacen_id) REFERENCES almacenes (id)');
        $this->addSql('ALTER TABLE combos_galeria ADD CONSTRAINT FK_D779D131BBBE6B76 FOREIGN KEY (id_combo_id) REFERENCES combos (id)');
        $this->addSql('ALTER TABLE combos_productos ADD CONSTRAINT FK_5A68FA3F6E57A479 FOREIGN KEY (id_producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE combos_productos ADD CONSTRAINT FK_5A68FA3FBBBE6B76 FOREIGN KEY (id_combo_id) REFERENCES combos (id)');
        $this->addSql('ALTER TABLE compras ADD CONSTRAINT FK_3692E1B7CB305D73 FOREIGN KEY (proveedor_id) REFERENCES proveedores (id)');
        $this->addSql('ALTER TABLE compras ADD CONSTRAINT FK_3692E1B79C9C9E68 FOREIGN KEY (almacen_id) REFERENCES almacenes (id)');
        $this->addSql('ALTER TABLE compras_productos ADD CONSTRAINT FK_DC9917F7F2E704D7 FOREIGN KEY (compra_id) REFERENCES compras (id)');
        $this->addSql('ALTER TABLE compras_productos ADD CONSTRAINT FK_DC9917F77645698E FOREIGN KEY (producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE compras_productos ADD CONSTRAINT FK_DC9917F72E003F4 FOREIGN KEY (unidad_medida_id) REFERENCES unidades_medida (id)');
        $this->addSql('ALTER TABLE cotizaciones ADD CONSTRAINT FK_12CE14AE7BF9CE86 FOREIGN KEY (id_cliente_id) REFERENCES clientes (id)');
        $this->addSql('ALTER TABLE cotizaciones ADD CONSTRAINT FK_12CE14AE62F40C3D FOREIGN KEY (creador_id) REFERENCES usuarios (id)');
        $this->addSql('ALTER TABLE cotizaciones_productos ADD CONSTRAINT FK_E238C0068E5841CF FOREIGN KEY (id_cotizacion_id) REFERENCES cotizaciones (id)');
        $this->addSql('ALTER TABLE cotizaciones_productos ADD CONSTRAINT FK_E238C0066E57A479 FOREIGN KEY (id_producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE cotizaciones_productos ADD CONSTRAINT FK_E238C0069C9C9E68 FOREIGN KEY (almacen_id) REFERENCES almacenes (id)');
        $this->addSql('ALTER TABLE materiales ADD CONSTRAINT FK_BC062AC9E16A5625 FOREIGN KEY (id_unidad_medida_id) REFERENCES unidades_medida (id)');
        $this->addSql('ALTER TABLE productos ADD CONSTRAINT FK_767490E63397707A FOREIGN KEY (categoria_id) REFERENCES categorias (id)');
        $this->addSql('ALTER TABLE productos ADD CONSTRAINT FK_767490E6E16A5625 FOREIGN KEY (id_unidad_medida_id) REFERENCES unidades_medida (id)');
        $this->addSql('ALTER TABLE productos_almacenes ADD CONSTRAINT FK_586E72EB6E57A479 FOREIGN KEY (id_producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE productos_almacenes ADD CONSTRAINT FK_586E72EB39161EBF FOREIGN KEY (id_almacen_id) REFERENCES almacenes (id)');
        $this->addSql('ALTER TABLE productos_galeria ADD CONSTRAINT FK_A10E30966E57A479 FOREIGN KEY (id_producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE proveedores_almacenes ADD CONSTRAINT FK_B42CFF42E8F12801 FOREIGN KEY (id_proveedor_id) REFERENCES proveedores (id)');
        $this->addSql('ALTER TABLE proveedores_almacenes ADD CONSTRAINT FK_B42CFF4239161EBF FOREIGN KEY (id_almacen_id) REFERENCES almacenes (id)');
        $this->addSql('ALTER TABLE proveedores_productos ADD CONSTRAINT FK_8061F921E8F12801 FOREIGN KEY (id_proveedor_id) REFERENCES proveedores (id)');
        $this->addSql('ALTER TABLE proveedores_productos ADD CONSTRAINT FK_8061F9216E57A479 FOREIGN KEY (id_producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE servicios_galeria ADD CONSTRAINT FK_9D252E9669D86E10 FOREIGN KEY (id_servicio_id) REFERENCES servicios (id)');
        $this->addSql('ALTER TABLE servicios_productos ADD CONSTRAINT FK_962C75C069D86E10 FOREIGN KEY (id_servicio_id) REFERENCES servicios (id)');
        $this->addSql('ALTER TABLE servicios_productos ADD CONSTRAINT FK_962C75C06E57A479 FOREIGN KEY (id_producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE servicios_reparaciones ADD CONSTRAINT FK_7C7310B369D86E10 FOREIGN KEY (id_servicio_id) REFERENCES servicios (id)');
        $this->addSql('ALTER TABLE servicios_reparaciones_materiales ADD CONSTRAINT FK_C5B3814AB0096FA FOREIGN KEY (reparacion_id) REFERENCES servicios_reparaciones (id)');
        $this->addSql('ALTER TABLE servicios_reparaciones_materiales ADD CONSTRAINT FK_C5B3814E308AC6F FOREIGN KEY (material_id) REFERENCES materiales (id)');
        $this->addSql('ALTER TABLE usuarios ADD CONSTRAINT FK_EF687F24BAB96C FOREIGN KEY (rol_id) REFERENCES roles (id)');
        $this->addSql('ALTER TABLE usuarios ADD CONSTRAINT FK_EF687F29C9C9E68 FOREIGN KEY (almacen_id) REFERENCES almacenes (id)');
        $this->addSql('ALTER TABLE ventas_pagos ADD CONSTRAINT FK_D3E05448CAEEDE5B FOREIGN KEY (medio_pago_id) REFERENCES medios_pago (id)');
        $this->addSql('ALTER TABLE ventas_pagos ADD CONSTRAINT FK_D3E05448F2A5805D FOREIGN KEY (venta_id) REFERENCES ventas (id)');
        $this->addSql('ALTER TABLE ventas_productos ADD CONSTRAINT FK_20ABEB63F2A5805D FOREIGN KEY (venta_id) REFERENCES ventas (id)');
        $this->addSql('ALTER TABLE ventas_productos ADD CONSTRAINT FK_20ABEB637645698E FOREIGN KEY (producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE ventas_productos ADD CONSTRAINT FK_20ABEB632E003F4 FOREIGN KEY (unidad_medida_id) REFERENCES unidades_medida (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ajustes_stock DROP FOREIGN KEY FK_1477ECF59C9C9E68');
        $this->addSql('ALTER TABLE clientes_almacenes DROP FOREIGN KEY FK_1F3D21AE39161EBF');
        $this->addSql('ALTER TABLE compras DROP FOREIGN KEY FK_3692E1B79C9C9E68');
        $this->addSql('ALTER TABLE cotizaciones_productos DROP FOREIGN KEY FK_E238C0069C9C9E68');
        $this->addSql('ALTER TABLE productos_almacenes DROP FOREIGN KEY FK_586E72EB39161EBF');
        $this->addSql('ALTER TABLE proveedores_almacenes DROP FOREIGN KEY FK_B42CFF4239161EBF');
        $this->addSql('ALTER TABLE usuarios DROP FOREIGN KEY FK_EF687F29C9C9E68');
        $this->addSql('ALTER TABLE productos DROP FOREIGN KEY FK_767490E63397707A');
        $this->addSql('ALTER TABLE clientes_almacenes DROP FOREIGN KEY FK_1F3D21AE7BF9CE86');
        $this->addSql('ALTER TABLE cotizaciones DROP FOREIGN KEY FK_12CE14AE7BF9CE86');
        $this->addSql('ALTER TABLE combos_galeria DROP FOREIGN KEY FK_D779D131BBBE6B76');
        $this->addSql('ALTER TABLE combos_productos DROP FOREIGN KEY FK_5A68FA3FBBBE6B76');
        $this->addSql('ALTER TABLE compras_productos DROP FOREIGN KEY FK_DC9917F7F2E704D7');
        $this->addSql('ALTER TABLE cotizaciones_productos DROP FOREIGN KEY FK_E238C0068E5841CF');
        $this->addSql('ALTER TABLE servicios_reparaciones_materiales DROP FOREIGN KEY FK_C5B3814E308AC6F');
        $this->addSql('ALTER TABLE ventas_pagos DROP FOREIGN KEY FK_D3E05448CAEEDE5B');
        $this->addSql('ALTER TABLE ajustes_stock DROP FOREIGN KEY FK_1477ECF5F9E584F8');
        $this->addSql('ALTER TABLE ajustes_stock DROP FOREIGN KEY FK_1477ECF57645698E');
        $this->addSql('ALTER TABLE combos_productos DROP FOREIGN KEY FK_5A68FA3F6E57A479');
        $this->addSql('ALTER TABLE compras_productos DROP FOREIGN KEY FK_DC9917F77645698E');
        $this->addSql('ALTER TABLE cotizaciones_productos DROP FOREIGN KEY FK_E238C0066E57A479');
        $this->addSql('ALTER TABLE productos_almacenes DROP FOREIGN KEY FK_586E72EB6E57A479');
        $this->addSql('ALTER TABLE productos_galeria DROP FOREIGN KEY FK_A10E30966E57A479');
        $this->addSql('ALTER TABLE proveedores_productos DROP FOREIGN KEY FK_8061F9216E57A479');
        $this->addSql('ALTER TABLE servicios_productos DROP FOREIGN KEY FK_962C75C06E57A479');
        $this->addSql('ALTER TABLE ventas_productos DROP FOREIGN KEY FK_20ABEB637645698E');
        $this->addSql('ALTER TABLE compras DROP FOREIGN KEY FK_3692E1B7CB305D73');
        $this->addSql('ALTER TABLE proveedores_almacenes DROP FOREIGN KEY FK_B42CFF42E8F12801');
        $this->addSql('ALTER TABLE proveedores_productos DROP FOREIGN KEY FK_8061F921E8F12801');
        $this->addSql('ALTER TABLE usuarios DROP FOREIGN KEY FK_EF687F24BAB96C');
        $this->addSql('ALTER TABLE servicios_galeria DROP FOREIGN KEY FK_9D252E9669D86E10');
        $this->addSql('ALTER TABLE servicios_productos DROP FOREIGN KEY FK_962C75C069D86E10');
        $this->addSql('ALTER TABLE servicios_reparaciones DROP FOREIGN KEY FK_7C7310B369D86E10');
        $this->addSql('ALTER TABLE servicios_reparaciones_materiales DROP FOREIGN KEY FK_C5B3814AB0096FA');
        $this->addSql('ALTER TABLE compras_productos DROP FOREIGN KEY FK_DC9917F72E003F4');
        $this->addSql('ALTER TABLE materiales DROP FOREIGN KEY FK_BC062AC9E16A5625');
        $this->addSql('ALTER TABLE productos DROP FOREIGN KEY FK_767490E6E16A5625');
        $this->addSql('ALTER TABLE ventas_productos DROP FOREIGN KEY FK_20ABEB632E003F4');
        $this->addSql('ALTER TABLE ajustes_stock DROP FOREIGN KEY FK_1477ECF5DB38439E');
        $this->addSql('ALTER TABLE cotizaciones DROP FOREIGN KEY FK_12CE14AE62F40C3D');
        $this->addSql('ALTER TABLE ventas_pagos DROP FOREIGN KEY FK_D3E05448F2A5805D');
        $this->addSql('ALTER TABLE ventas_productos DROP FOREIGN KEY FK_20ABEB63F2A5805D');
        $this->addSql('DROP TABLE ajustes_stock');
        $this->addSql('DROP TABLE almacenes');
        $this->addSql('DROP TABLE categorias');
        $this->addSql('DROP TABLE clientes');
        $this->addSql('DROP TABLE clientes_almacenes');
        $this->addSql('DROP TABLE combos');
        $this->addSql('DROP TABLE combos_galeria');
        $this->addSql('DROP TABLE combos_productos');
        $this->addSql('DROP TABLE compras');
        $this->addSql('DROP TABLE compras_productos');
        $this->addSql('DROP TABLE cotizaciones');
        $this->addSql('DROP TABLE cotizaciones_productos');
        $this->addSql('DROP TABLE materiales');
        $this->addSql('DROP TABLE medios_pago');
        $this->addSql('DROP TABLE motivos_ajustes_stock');
        $this->addSql('DROP TABLE productos');
        $this->addSql('DROP TABLE productos_almacenes');
        $this->addSql('DROP TABLE productos_galeria');
        $this->addSql('DROP TABLE proveedores');
        $this->addSql('DROP TABLE proveedores_almacenes');
        $this->addSql('DROP TABLE proveedores_productos');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE servicios');
        $this->addSql('DROP TABLE servicios_galeria');
        $this->addSql('DROP TABLE servicios_productos');
        $this->addSql('DROP TABLE servicios_reparaciones');
        $this->addSql('DROP TABLE servicios_reparaciones_materiales');
        $this->addSql('DROP TABLE unidades_medida');
        $this->addSql('DROP TABLE usuarios');
        $this->addSql('DROP TABLE ventas');
        $this->addSql('DROP TABLE ventas_pagos');
        $this->addSql('DROP TABLE ventas_productos');
    }
}
