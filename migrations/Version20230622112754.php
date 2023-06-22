<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230622112754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart ADD pay_by_id INT DEFAULT NULL, ADD delivery_by_id INT DEFAULT NULL, ADD status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7E2068F9D FOREIGN KEY (pay_by_id) REFERENCES pay_system (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7BAECC696 FOREIGN KEY (delivery_by_id) REFERENCES delivery_price (id)');
        $this->addSql('CREATE INDEX IDX_BA388B7E2068F9D ON cart (pay_by_id)');
        $this->addSql('CREATE INDEX IDX_BA388B7BAECC696 ON cart (delivery_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7E2068F9D');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7BAECC696');
        $this->addSql('DROP INDEX IDX_BA388B7E2068F9D ON cart');
        $this->addSql('DROP INDEX IDX_BA388B7BAECC696 ON cart');
        $this->addSql('ALTER TABLE cart DROP pay_by_id, DROP delivery_by_id, DROP status');
    }
}
