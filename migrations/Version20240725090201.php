<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240725090201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_address (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, zip_code VARCHAR(5) NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_address_user (user_address_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_70137AA052D06999 (user_address_id), INDEX IDX_70137AA0A76ED395 (user_id), PRIMARY KEY(user_address_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_address_user ADD CONSTRAINT FK_70137AA052D06999 FOREIGN KEY (user_address_id) REFERENCES user_address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_address_user ADD CONSTRAINT FK_70137AA0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_address_user DROP FOREIGN KEY FK_70137AA052D06999');
        $this->addSql('ALTER TABLE user_address_user DROP FOREIGN KEY FK_70137AA0A76ED395');
        $this->addSql('DROP TABLE user_address');
        $this->addSql('DROP TABLE user_address_user');
    }
}
