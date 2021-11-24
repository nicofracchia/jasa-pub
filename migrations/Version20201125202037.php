<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125202037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE servicios_reparaciones_materiales DROP FOREIGN KEY FK_C5B3814E308AC6F');
        $this->addSql('ALTER TABLE servicios_galeria DROP FOREIGN KEY FK_9D252E9669D86E10');
        $this->addSql('ALTER TABLE servicios_productos DROP FOREIGN KEY FK_962C75C069D86E10');
        $this->addSql('ALTER TABLE servicios_reparaciones DROP FOREIGN KEY FK_7C7310B369D86E10');
        $this->addSql('ALTER TABLE servicios_reparaciones_materiales DROP FOREIGN KEY FK_C5B3814AB0096FA');
        $this->addSql('CREATE TABLE reparaciones (id INT AUTO_INCREMENT NOT NULL, almacen_id INT DEFAULT NULL, cliente_id INT DEFAULT NULL, receptor_id INT DEFAULT NULL, recepcion DATETIME NOT NULL, articulo VARCHAR(255) NOT NULL, marca VARCHAR(255) NOT NULL, modelo VARCHAR(255) NOT NULL, serial VARCHAR(255) NOT NULL, tarea VARCHAR(255) NOT NULL, reporte LONGTEXT NOT NULL, tinta_c DOUBLE PRECISION DEFAULT NULL, tinta_m DOUBLE PRECISION DEFAULT NULL, tinta_y DOUBLE PRECISION DEFAULT NULL, tinta_cl DOUBLE PRECISION DEFAULT NULL, tinta_ml DOUBLE PRECISION DEFAULT NULL, tinta_bk DOUBLE PRECISION DEFAULT NULL, estado VARCHAR(255) DEFAULT NULL, diagnostico LONGTEXT DEFAULT NULL, presupuesto_inicial DOUBLE PRECISION DEFAULT NULL, presupuesto_final DOUBLE PRECISION DEFAULT NULL, observaciones LONGTEXT DEFAULT NULL, INDEX IDX_60AF46E9C9C9E68 (almacen_id), INDEX IDX_60AF46EDE734E51 (cliente_id), INDEX IDX_60AF46E386D8D01 (receptor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reparaciones_estados (id INT AUTO_INCREMENT NOT NULL, reparacion_id INT NOT NULL, fecha DATETIME NOT NULL, estado VARCHAR(255) NOT NULL, INDEX IDX_2DE8BAD0AB0096FA (reparacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reparaciones_pagos (id INT AUTO_INCREMENT NOT NULL, reparacion_id INT NOT NULL, fecha DATETIME NOT NULL, monto DOUBLE PRECISION NOT NULL, INDEX IDX_B4D9E502AB0096FA (reparacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reparaciones_pagos_detalle (id INT AUTO_INCREMENT NOT NULL, pago_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, valor VARCHAR(255) NOT NULL, INDEX IDX_37BAF0B363FB8380 (pago_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reparaciones ADD CONSTRAINT FK_60AF46E9C9C9E68 FOREIGN KEY (almacen_id) REFERENCES almacenes (id)');
        $this->addSql('ALTER TABLE reparaciones ADD CONSTRAINT FK_60AF46EDE734E51 FOREIGN KEY (cliente_id) REFERENCES clientes (id)');
        $this->addSql('ALTER TABLE reparaciones ADD CONSTRAINT FK_60AF46E386D8D01 FOREIGN KEY (receptor_id) REFERENCES usuarios (id)');
        $this->addSql('ALTER TABLE reparaciones_estados ADD CONSTRAINT FK_2DE8BAD0AB0096FA FOREIGN KEY (reparacion_id) REFERENCES reparaciones (id)');
        $this->addSql('ALTER TABLE reparaciones_pagos ADD CONSTRAINT FK_B4D9E502AB0096FA FOREIGN KEY (reparacion_id) REFERENCES reparaciones (id)');
        $this->addSql('ALTER TABLE reparaciones_pagos_detalle ADD CONSTRAINT FK_37BAF0B363FB8380 FOREIGN KEY (pago_id) REFERENCES reparaciones_pagos (id)');
        $this->addSql('DROP TABLE combos_galeria');
        $this->addSql('DROP TABLE materiales');
        $this->addSql('DROP TABLE productos_galeria');
        $this->addSql('DROP TABLE servicios');
        $this->addSql('DROP TABLE servicios_galeria');
        $this->addSql('DROP TABLE servicios_productos');
        $this->addSql('DROP TABLE servicios_reparaciones');
        $this->addSql('DROP TABLE servicios_reparaciones_materiales');
        $this->addSql('ALTER TABLE caja CHANGE usuario_apertura_id usuario_apertura_id INT DEFAULT NULL, CHANGE usuario_cierre_id usuario_cierre_id INT DEFAULT NULL, CHANGE cierre cierre DATETIME DEFAULT NULL, CHANGE saldo_estimado saldo_estimado DOUBLE PRECISION DEFAULT NULL, CHANGE saldo_final saldo_final DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE clientes CHANGE telefono telefono VARCHAR(255) DEFAULT NULL, CHANGE direccion direccion VARCHAR(255) DEFAULT NULL, CHANGE cuit cuit INT DEFAULT NULL, CHANGE razon_social razon_social VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE clientes_almacenes CHANGE id_cliente_id id_cliente_id INT DEFAULT NULL, CHANGE id_almacen_id id_almacen_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compras CHANGE proveedor_id proveedor_id INT DEFAULT NULL, CHANGE almacen_id almacen_id INT DEFAULT NULL, CHANGE precio precio DOUBLE PRECISION DEFAULT NULL, CHANGE recepcion recepcion DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE cotizaciones CHANGE id_cliente_id id_cliente_id INT DEFAULT NULL, CHANGE creador_id creador_id INT DEFAULT NULL, CHANGE almacen_id almacen_id INT DEFAULT NULL, CHANGE hasta hasta DATE DEFAULT NULL, CHANGE descuento descuento DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE cotizaciones_productos DROP cantidad, DROP precio_actual, DROP costo_actual, DROP reserva_mercaderia');
        $this->addSql('ALTER TABLE movimientos_caja CHANGE caja_id caja_id INT DEFAULT NULL, CHANGE creador_id creador_id INT DEFAULT NULL, CHANGE observaciones observaciones VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE productos CHANGE codigo_barras codigo_barras VARCHAR(255) DEFAULT NULL, CHANGE imagen imagen VARCHAR(255) DEFAULT NULL, CHANGE diametro diametro DOUBLE PRECISION DEFAULT NULL, CHANGE largo largo DOUBLE PRECISION DEFAULT NULL, CHANGE ancho ancho DOUBLE PRECISION DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT NULL, CHANGE material material VARCHAR(255) DEFAULT NULL, CHANGE iva iva DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE proveedores CHANGE cuit cuit VARCHAR(255) DEFAULT NULL, CHANGE telefono telefono VARCHAR(255) DEFAULT NULL, CHANGE mail mail VARCHAR(255) DEFAULT NULL, CHANGE direccion direccion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE proveedores_productos CHANGE costo costo DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE unidades_medida CHANGE corto corto VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuarios CHANGE roles roles JSON NOT NULL, CHANGE direccion direccion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ventas CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ventas_pagos CHANGE comprobante comprobante VARCHAR(255) DEFAULT NULL, CHANGE url_comprobante url_comprobante VARCHAR(255) DEFAULT NULL, CHANGE observaciones observaciones VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reparaciones_estados DROP FOREIGN KEY FK_2DE8BAD0AB0096FA');
        $this->addSql('ALTER TABLE reparaciones_pagos DROP FOREIGN KEY FK_B4D9E502AB0096FA');
        $this->addSql('ALTER TABLE reparaciones_pagos_detalle DROP FOREIGN KEY FK_37BAF0B363FB8380');
        $this->addSql('CREATE TABLE combos_galeria (id INT AUTO_INCREMENT NOT NULL, id_combo_id INT NOT NULL, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, habilitado SMALLINT NOT NULL, INDEX IDX_D779D131BBBE6B76 (id_combo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE materiales (id INT AUTO_INCREMENT NOT NULL, id_unidad_medida_id INT NOT NULL, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, codigo_barras VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, costo DOUBLE PRECISION DEFAULT \'NULL\', stock_actual INT NOT NULL, INDEX IDX_BC062AC9E16A5625 (id_unidad_medida_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE productos_galeria (id INT AUTO_INCREMENT NOT NULL, id_producto_id INT NOT NULL, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, habilitado SMALLINT NOT NULL, INDEX IDX_A10E30966E57A479 (id_producto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE servicios (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, descripcion LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, habilitado TINYINT(1) NOT NULL, eliminado TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE servicios_galeria (id INT AUTO_INCREMENT NOT NULL, id_servicio_id INT NOT NULL, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, habilitado SMALLINT NOT NULL, INDEX IDX_9D252E9669D86E10 (id_servicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE servicios_productos (id INT AUTO_INCREMENT NOT NULL, id_servicio_id INT NOT NULL, id_producto_id INT NOT NULL, INDEX IDX_962C75C069D86E10 (id_servicio_id), INDEX IDX_962C75C06E57A479 (id_producto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE servicios_reparaciones (id INT AUTO_INCREMENT NOT NULL, id_servicio_id INT NOT NULL, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, precio DOUBLE PRECISION NOT NULL, tiempo_estimado INT DEFAULT NULL, descripcion LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, habilitado SMALLINT NOT NULL, eliminado SMALLINT NOT NULL, INDEX IDX_7C7310B369D86E10 (id_servicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE servicios_reparaciones_materiales (id INT AUTO_INCREMENT NOT NULL, reparacion_id INT NOT NULL, material_id INT NOT NULL, cantidad INT NOT NULL, INDEX IDX_C5B3814AB0096FA (reparacion_id), INDEX IDX_C5B3814E308AC6F (material_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE combos_galeria ADD CONSTRAINT FK_D779D131BBBE6B76 FOREIGN KEY (id_combo_id) REFERENCES combos (id)');
        $this->addSql('ALTER TABLE materiales ADD CONSTRAINT FK_BC062AC9E16A5625 FOREIGN KEY (id_unidad_medida_id) REFERENCES unidades_medida (id)');
        $this->addSql('ALTER TABLE productos_galeria ADD CONSTRAINT FK_A10E30966E57A479 FOREIGN KEY (id_producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE servicios_galeria ADD CONSTRAINT FK_9D252E9669D86E10 FOREIGN KEY (id_servicio_id) REFERENCES servicios (id)');
        $this->addSql('ALTER TABLE servicios_productos ADD CONSTRAINT FK_962C75C069D86E10 FOREIGN KEY (id_servicio_id) REFERENCES servicios (id)');
        $this->addSql('ALTER TABLE servicios_productos ADD CONSTRAINT FK_962C75C06E57A479 FOREIGN KEY (id_producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE servicios_reparaciones ADD CONSTRAINT FK_7C7310B369D86E10 FOREIGN KEY (id_servicio_id) REFERENCES servicios (id)');
        $this->addSql('ALTER TABLE servicios_reparaciones_materiales ADD CONSTRAINT FK_C5B3814AB0096FA FOREIGN KEY (reparacion_id) REFERENCES servicios_reparaciones (id)');
        $this->addSql('ALTER TABLE servicios_reparaciones_materiales ADD CONSTRAINT FK_C5B3814E308AC6F FOREIGN KEY (material_id) REFERENCES materiales (id)');
        $this->addSql('DROP TABLE reparaciones');
        $this->addSql('DROP TABLE reparaciones_estados');
        $this->addSql('DROP TABLE reparaciones_pagos');
        $this->addSql('DROP TABLE reparaciones_pagos_detalle');
        $this->addSql('ALTER TABLE caja CHANGE usuario_apertura_id usuario_apertura_id INT DEFAULT NULL, CHANGE usuario_cierre_id usuario_cierre_id INT DEFAULT NULL, CHANGE cierre cierre DATETIME DEFAULT \'NULL\', CHANGE saldo_estimado saldo_estimado DOUBLE PRECISION DEFAULT \'NULL\', CHANGE saldo_final saldo_final DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE clientes CHANGE telefono telefono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE cuit cuit INT DEFAULT NULL, CHANGE razon_social razon_social VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE clientes_almacenes CHANGE id_cliente_id id_cliente_id INT DEFAULT NULL, CHANGE id_almacen_id id_almacen_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compras CHANGE proveedor_id proveedor_id INT DEFAULT NULL, CHANGE almacen_id almacen_id INT DEFAULT NULL, CHANGE precio precio DOUBLE PRECISION DEFAULT \'NULL\', CHANGE recepcion recepcion DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE cotizaciones CHANGE id_cliente_id id_cliente_id INT DEFAULT NULL, CHANGE creador_id creador_id INT DEFAULT NULL, CHANGE almacen_id almacen_id INT DEFAULT NULL, CHANGE hasta hasta DATE DEFAULT \'NULL\', CHANGE descuento descuento DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE cotizaciones_productos ADD cantidad INT NOT NULL, ADD precio_actual DOUBLE PRECISION NOT NULL, ADD costo_actual DOUBLE PRECISION NOT NULL, ADD reserva_mercaderia TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE movimientos_caja CHANGE caja_id caja_id INT DEFAULT NULL, CHANGE creador_id creador_id INT DEFAULT NULL, CHANGE observaciones observaciones VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE productos CHANGE codigo_barras codigo_barras VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE imagen imagen VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE diametro diametro DOUBLE PRECISION DEFAULT \'NULL\', CHANGE largo largo DOUBLE PRECISION DEFAULT \'NULL\', CHANGE ancho ancho DOUBLE PRECISION DEFAULT \'NULL\', CHANGE color color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE material material VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE iva iva DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE proveedores CHANGE cuit cuit VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono telefono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mail mail VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE proveedores_productos CHANGE costo costo DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE unidades_medida CHANGE corto corto VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE usuarios CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ventas CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ventas_pagos CHANGE comprobante comprobante VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE url_comprobante url_comprobante VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE observaciones observaciones VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
