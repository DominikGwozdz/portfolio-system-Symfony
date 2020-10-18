<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201018163457 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE visit (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, date DATE NOT NULL, count INT NOT NULL, INDEX IDX_437EE9394E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE9394E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('DROP INDEX slug ON gallery');
        $this->addSql('ALTER TABLE gallery CHANGE slug slug VARCHAR(500) NOT NULL');
        $this->addSql('DROP INDEX slug ON gallery_category');
        $this->addSql('ALTER TABLE gallery_category CHANGE slug slug VARCHAR(500) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE visit');
        $this->addSql('ALTER TABLE gallery CHANGE slug slug VARCHAR(500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX slug ON gallery (slug)');
        $this->addSql('ALTER TABLE gallery_category CHANGE slug slug VARCHAR(500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX slug ON gallery_category (slug)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
