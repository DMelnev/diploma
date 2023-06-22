<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230622122009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_product ADD seller_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAA8DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2890CCAA8DE820D9 ON cart_product (seller_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_product DROP FOREIGN KEY FK_2890CCAA8DE820D9');
        $this->addSql('DROP INDEX IDX_2890CCAA8DE820D9 ON cart_product');
        $this->addSql('ALTER TABLE cart_product DROP seller_id');
    }
}
