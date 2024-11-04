<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102231646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, name, price, image_url, product_url FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, product_url VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO product (id, name, price, image_url, product_url) SELECT id, name, price, image_url, product_url FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD5E237E06 ON product (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, name, price, image_url, product_url FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, image_url VARCHAR(255) NOT NULL, product_url VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO product (id, name, price, image_url, product_url) SELECT id, name, price, image_url, product_url FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
    }
}
