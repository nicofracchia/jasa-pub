<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200823214546 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM roles');
        $this->addSql('INSERT INTO roles (id, nombre, descripcion) VALUES (1, "Admin","Administrador general del sistema, acceso completo a todas las secciones.")');
        $this->addSql('DELETE FROM almacenes');
        $this->addSql('INSERT INTO almacenes (id, nombre, direccion) VALUES (1, "Admin","Almacen admin para hacerlo generico")');
        $this->addSql('DELETE FROM usuarios');
        $this->addSql('INSERT INTO usuarios (id, rol_id, almacen_id, email, password, nombre, apellido, direccion, habilitado, eliminado) VALUES (1, 1, 1, "admin@admin.com", 123456, "Nicolás", "Fracchia", "Garibaldi 1221, Lomas de Zamora", 1, 0)');
        $this->addSql('DELETE FROM unidades_medida');
        $this->addSql('INSERT INTO unidades_medida (id, nombre, nombre_padre) VALUES (1, "Centímetros","Metros"), (2, "Mililitros","Litros"), (3, "Unidades","Unidades")');
        $this->addSql('DELETE FROM categorias');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (1, "Sublimación", 0, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (2, "Articulos Sublimables", 1, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (3, "Cartón", 2, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (4, "Ceramica", 2, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (5, "Madera", 2, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (6, "Metal", 2, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (7, "Plástico", 2, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (8, "Textil", 2, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (9, "Vidrio", 2, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (10, "Planchas Termicas", 1, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (11, "Estampadora Plana 38x38", 10, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (12, "Estampadora plana 40x60", 10, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (13, "Estampadora Multifuncion 5 en 1", 10, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (14, "Estampadora de Tazas", 10, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (15, "Estampadora de Gorras", 10, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (16, "Horno 3D", 10, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (17, "Accesorios Sublimacion", 1, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (18, "Simball", 17, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (19, "Cinta Térmica Adhesiva", 17, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (20, "Conformador Platos Plásticos", 17, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (21, "Conformador Tazas Plásticas", 17, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (22, "Polímero Textil en Polvo", 17, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (23, "Tela Termoadhesiva", 17, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (24, "Tela Teflonada", 17, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (25, "Goma Térmica 38x38", 17, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (26, "Goma Térmica 40x60", 17, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (27, "Impresoras", 0, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (28, "Impresoras", 27, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (29, "Impresoras Fotográficas", 28, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (30, "Impresoras Sublimación", 28, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (31, "sistemas Continuos Epson", 27, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (32, "Sistema Continuo Epson 5 Tintas", 31, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (33, "Sistema Continuo Epson 6 Tintas", 31, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (34, "Sistema Continuo Epson 2101", 31, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (35, "Sistema Continuo Epson TX/CX", 31, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (36, "Sistema Continuo Epson XP", 31, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (37, "Sistema Continuo Epson XP231 / XP241", 31, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (38, "Tinta", 27, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (39, "Tinta Sublimación Epson", 38, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (40, "Tinta Fotográfica Epson Alternativa", 38, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (41, "Tinta Fotográfica Epson Original", 38, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (42, "Papel", 0, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (43, "Papel Sublimación", 42, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (44, "Resmas Papel Sublimación", 43, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (45, "Papel Sublimación A4", 44, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (46, "Papel Sublimación A3", 44, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (47, "Papel Sublimación A3+", 44, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (48, "Rollos Papel Sublimación", 43, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (49, "Papel Fotográfico", 42, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (50, "Papel Fotográfico Glossy A4", 49, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (51, "Papel Autoadhesivo Glossy A4", 49, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (52, "Dasa", 0, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (53, "Cutter Rotativo", 52, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (54, "Cuchillas Troquelado", 52, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (55, "Porta Block", 52, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (56, "Cabezal Corte Recto", 52, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (57, "Tabla de Corte 30x22cm.", 52, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (58, "Vinilo", 0, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (59, "Autoadhesivos", 58, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (60, "Vinilo Serie 6000 (Brillante)", 59, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (61, "Vinilo Serie 3000 (Transparente)", 59, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (62, "Vinilo Serie 2000 (Mate)", 59, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (63, "Termoadhesivos", 58, 0)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (64, "Vinilo Termoadhesivo (Textil)", 63, 1)');
        $this->addSql('INSERT INTO categorias (id, nombre, id_padre, final) VALUES (65, "Vinilo Sublimable", 58, 1)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM roles');
        $this->addSql('DELETE FROM almacenes');
        $this->addSql('DELETE FROM usuarios');
        $this->addSql('DELETE FROM categorias');
        $this->addSql('DELETE FROM unidades_medida');
    }
}
