<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230612224102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE section_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF727ACA70');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF727ACA70 FOREIGN KEY (parent_id) REFERENCES section_group (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF727ACA70');
        $this->addSql('DROP TABLE section_group');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF727ACA70');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF727ACA70 FOREIGN KEY (parent_id) REFERENCES section (id)');
    }
}
