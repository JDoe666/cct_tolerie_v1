<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240830125454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP image_name');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_497DD634A76ED395 ON categorie (user_id)');
        $this->addSql('ALTER TABLE realisation ADD CONSTRAINT FK_EAA5610EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EAA5610EA76ED395 ON realisation (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis ADD image_name VARCHAR(255) NOT NULL, DROP updated_at');
        $this->addSql('ALTER TABLE realisation DROP FOREIGN KEY FK_EAA5610EA76ED395');
        $this->addSql('DROP INDEX IDX_EAA5610EA76ED395 ON realisation');
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634A76ED395');
        $this->addSql('DROP INDEX IDX_497DD634A76ED395 ON categorie');
    }
}
