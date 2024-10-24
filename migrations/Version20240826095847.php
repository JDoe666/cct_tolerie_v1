<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240826095847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devis_logs (id INT AUTO_INCREMENT NOT NULL, devis_id INT NOT NULL, administration_id INT NOT NULL, action VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', details JSON NOT NULL, INDEX IDX_BF5DA78341DEFADA (devis_id), INDEX IDX_BF5DA78339B8E743 (administration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis_logs ADD CONSTRAINT FK_BF5DA78341DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE devis_logs ADD CONSTRAINT FK_BF5DA78339B8E743 FOREIGN KEY (administration_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis_logs DROP FOREIGN KEY FK_BF5DA78341DEFADA');
        $this->addSql('ALTER TABLE devis_logs DROP FOREIGN KEY FK_BF5DA78339B8E743');
        $this->addSql('DROP TABLE devis_logs');
    }
}
