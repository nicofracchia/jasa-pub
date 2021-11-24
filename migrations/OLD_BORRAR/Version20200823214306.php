<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200823214306 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clientes CHANGE telefono telefono VARCHAR(255) DEFAULT NULL, CHANGE direccion direccion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE clientes_almacenes CHANGE id_cliente_id id_cliente_id INT DEFAULT NULL, CHANGE id_almacen_id id_almacen_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compras CHANGE proveedor_id proveedor_id INT DEFAULT NULL, CHANGE almacen_id almacen_id INT DEFAULT NULL, CHANGE precio precio DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE materiales CHANGE codigo_barras codigo_barras VARCHAR(255) DEFAULT NULL, CHANGE costo costo DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE productos CHANGE codigo_barras codigo_barras VARCHAR(255) DEFAULT NULL, CHANGE iamgen iamgen VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE proveedores CHANGE cuit cuit VARCHAR(255) DEFAULT NULL, CHANGE telefono telefono VARCHAR(255) DEFAULT NULL, CHANGE mail mail VARCHAR(255) DEFAULT NULL, CHANGE direccion direccion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE proveedores_productos CHANGE costo costo DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE servicios_reparaciones CHANGE tiempo_estimado tiempo_estimado INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuarios CHANGE direccion direccion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ventas_pagos CHANGE comprobante comprobante VARCHAR(255) DEFAULT NULL, CHANGE url_comprobante url_comprobante VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clientes CHANGE telefono telefono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE clientes_almacenes CHANGE id_cliente_id id_cliente_id INT DEFAULT NULL, CHANGE id_almacen_id id_almacen_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compras CHANGE proveedor_id proveedor_id INT DEFAULT NULL, CHANGE almacen_id almacen_id INT DEFAULT NULL, CHANGE precio precio DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE materiales CHANGE codigo_barras codigo_barras VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE costo costo DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE productos CHANGE codigo_barras codigo_barras VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE iamgen iamgen VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE proveedores CHANGE cuit cuit VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono telefono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mail mail VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE proveedores_productos CHANGE costo costo DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE servicios_reparaciones CHANGE tiempo_estimado tiempo_estimado INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuarios CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ventas_pagos CHANGE comprobante comprobante VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE url_comprobante url_comprobante VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
