<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731081905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8B27C52BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis_image (id INT AUTO_INCREMENT NOT NULL, devis_id INT NOT NULL, image_name VARCHAR(255) NOT NULL, INDEX IDX_D31E197B41DEFADA (devis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE devis_image ADD CONSTRAINT FK_D31E197B41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BA76ED395');
        $this->addSql('ALTER TABLE devis_image DROP FOREIGN KEY FK_D31E197B41DEFADA');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE devis_image');
    }
}
