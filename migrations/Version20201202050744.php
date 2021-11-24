<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201202050744 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caja CHANGE usuario_apertura_id usuario_apertura_id INT DEFAULT NULL, CHANGE usuario_cierre_id usuario_cierre_id INT DEFAULT NULL, CHANGE cierre cierre DATETIME DEFAULT NULL, CHANGE saldo_estimado saldo_estimado DOUBLE PRECISION DEFAULT NULL, CHANGE saldo_final saldo_final DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE clientes CHANGE telefono telefono VARCHAR(255) DEFAULT NULL, CHANGE direccion direccion VARCHAR(255) DEFAULT NULL, CHANGE cuit cuit INT DEFAULT NULL, CHANGE razon_social razon_social VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE clientes_almacenes CHANGE id_cliente_id id_cliente_id INT DEFAULT NULL, CHANGE id_almacen_id id_almacen_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compras CHANGE proveedor_id proveedor_id INT DEFAULT NULL, CHANGE almacen_id almacen_id INT DEFAULT NULL, CHANGE precio precio DOUBLE PRECISION DEFAULT NULL, CHANGE recepcion recepcion DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE cotizaciones CHANGE id_cliente_id id_cliente_id INT DEFAULT NULL, CHANGE creador_id creador_id INT DEFAULT NULL, CHANGE almacen_id almacen_id INT DEFAULT NULL, CHANGE hasta hasta DATE DEFAULT NULL, CHANGE descuento descuento DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE movimientos_caja CHANGE caja_id caja_id INT DEFAULT NULL, CHANGE creador_id creador_id INT DEFAULT NULL, CHANGE observaciones observaciones VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE productos CHANGE codigo_barras codigo_barras VARCHAR(255) DEFAULT NULL, CHANGE imagen imagen VARCHAR(255) DEFAULT NULL, CHANGE diametro diametro DOUBLE PRECISION DEFAULT NULL, CHANGE largo largo DOUBLE PRECISION DEFAULT NULL, CHANGE ancho ancho DOUBLE PRECISION DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT NULL, CHANGE material material VARCHAR(255) DEFAULT NULL, CHANGE iva iva DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE proveedores CHANGE cuit cuit VARCHAR(255) DEFAULT NULL, CHANGE telefono telefono VARCHAR(255) DEFAULT NULL, CHANGE mail mail VARCHAR(255) DEFAULT NULL, CHANGE direccion direccion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE proveedores_productos CHANGE costo costo DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE reparaciones CHANGE almacen_id almacen_id INT DEFAULT NULL, CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE receptor_id receptor_id INT DEFAULT NULL, CHANGE tinta_c tinta_c DOUBLE PRECISION DEFAULT NULL, CHANGE tinta_m tinta_m DOUBLE PRECISION DEFAULT NULL, CHANGE tinta_y tinta_y DOUBLE PRECISION DEFAULT NULL, CHANGE tinta_cl tinta_cl DOUBLE PRECISION DEFAULT NULL, CHANGE tinta_ml tinta_ml DOUBLE PRECISION DEFAULT NULL, CHANGE tinta_bk tinta_bk DOUBLE PRECISION DEFAULT NULL, CHANGE estado estado VARCHAR(255) DEFAULT NULL, CHANGE presupuesto_inicial presupuesto_inicial DOUBLE PRECISION DEFAULT NULL, CHANGE presupuesto_final presupuesto_final DOUBLE PRECISION DEFAULT NULL, CHANGE sena sena DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE reparaciones_pagos ADD medio_pago_id INT DEFAULT NULL, ADD comprobante VARCHAR(255) DEFAULT NULL, ADD url_comprobante VARCHAR(255) DEFAULT NULL, ADD observaciones VARCHAR(255) DEFAULT NULL, ADD interes DOUBLE PRECISION DEFAULT NULL, ADD nota_venta TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE reparaciones_pagos ADD CONSTRAINT FK_B4D9E502CAEEDE5B FOREIGN KEY (medio_pago_id) REFERENCES medios_pago (id)');
        $this->addSql('CREATE INDEX IDX_B4D9E502CAEEDE5B ON reparaciones_pagos (medio_pago_id)');
        $this->addSql('ALTER TABLE unidades_medida CHANGE corto corto VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuarios CHANGE roles roles JSON NOT NULL, CHANGE direccion direccion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ventas CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ventas_pagos CHANGE comprobante comprobante VARCHAR(255) DEFAULT NULL, CHANGE url_comprobante url_comprobante VARCHAR(255) DEFAULT NULL, CHANGE observaciones observaciones VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caja CHANGE usuario_apertura_id usuario_apertura_id INT DEFAULT NULL, CHANGE usuario_cierre_id usuario_cierre_id INT DEFAULT NULL, CHANGE cierre cierre DATETIME DEFAULT \'NULL\', CHANGE saldo_estimado saldo_estimado DOUBLE PRECISION DEFAULT \'NULL\', CHANGE saldo_final saldo_final DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE clientes CHANGE telefono telefono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE cuit cuit INT DEFAULT NULL, CHANGE razon_social razon_social VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE clientes_almacenes CHANGE id_cliente_id id_cliente_id INT DEFAULT NULL, CHANGE id_almacen_id id_almacen_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compras CHANGE proveedor_id proveedor_id INT DEFAULT NULL, CHANGE almacen_id almacen_id INT DEFAULT NULL, CHANGE precio precio DOUBLE PRECISION DEFAULT \'NULL\', CHANGE recepcion recepcion DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE cotizaciones CHANGE id_cliente_id id_cliente_id INT DEFAULT NULL, CHANGE creador_id creador_id INT DEFAULT NULL, CHANGE almacen_id almacen_id INT DEFAULT NULL, CHANGE hasta hasta DATE DEFAULT \'NULL\', CHANGE descuento descuento DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE movimientos_caja CHANGE caja_id caja_id INT DEFAULT NULL, CHANGE creador_id creador_id INT DEFAULT NULL, CHANGE observaciones observaciones VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE productos CHANGE codigo_barras codigo_barras VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE imagen imagen VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE diametro diametro DOUBLE PRECISION DEFAULT \'NULL\', CHANGE largo largo DOUBLE PRECISION DEFAULT \'NULL\', CHANGE ancho ancho DOUBLE PRECISION DEFAULT \'NULL\', CHANGE color color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE material material VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE iva iva DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE proveedores CHANGE cuit cuit VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono telefono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mail mail VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE proveedores_productos CHANGE costo costo DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE reparaciones CHANGE almacen_id almacen_id INT DEFAULT NULL, CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE receptor_id receptor_id INT DEFAULT NULL, CHANGE tinta_c tinta_c DOUBLE PRECISION DEFAULT \'NULL\', CHANGE tinta_m tinta_m DOUBLE PRECISION DEFAULT \'NULL\', CHANGE tinta_y tinta_y DOUBLE PRECISION DEFAULT \'NULL\', CHANGE tinta_cl tinta_cl DOUBLE PRECISION DEFAULT \'NULL\', CHANGE tinta_ml tinta_ml DOUBLE PRECISION DEFAULT \'NULL\', CHANGE tinta_bk tinta_bk DOUBLE PRECISION DEFAULT \'NULL\', CHANGE estado estado VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE presupuesto_inicial presupuesto_inicial DOUBLE PRECISION DEFAULT \'NULL\', CHANGE presupuesto_final presupuesto_final DOUBLE PRECISION DEFAULT \'NULL\', CHANGE sena sena DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE reparaciones_pagos DROP FOREIGN KEY FK_B4D9E502CAEEDE5B');
        $this->addSql('DROP INDEX IDX_B4D9E502CAEEDE5B ON reparaciones_pagos');
        $this->addSql('ALTER TABLE reparaciones_pagos DROP medio_pago_id, DROP comprobante, DROP url_comprobante, DROP observaciones, DROP interes, DROP nota_venta');
        $this->addSql('ALTER TABLE unidades_medida CHANGE corto corto VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE usuarios CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ventas CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ventas_pagos CHANGE comprobante comprobante VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE url_comprobante url_comprobante VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE observaciones observaciones VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
