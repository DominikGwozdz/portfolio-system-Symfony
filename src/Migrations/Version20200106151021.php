<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200106151021 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(500) NOT NULL, picture VARCHAR(500) DEFAULT NULL, directory VARCHAR(500) NOT NULL, is_visible TINYINT(1) NOT NULL, is_protected TINYINT(1) NOT NULL, password VARCHAR(500) DEFAULT NULL, INDEX IDX_472B783A12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(500) NOT NULL, picture VARCHAR(500) DEFAULT NULL, is_visible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_item (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, picture VARCHAR(500) NOT NULL, INDEX IDX_8C040D924E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783A12469DE2 FOREIGN KEY (category_id) REFERENCES gallery_category (id)');
        $this->addSql('ALTER TABLE gallery_item ADD CONSTRAINT FK_8C040D924E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE about CHANGE picture picture VARCHAR(255) DEFAULT NULL, CHANGE meta_description meta_description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gallery_item DROP FOREIGN KEY FK_8C040D924E7AF8F');
        $this->addSql('ALTER TABLE gallery DROP FOREIGN KEY FK_472B783A12469DE2');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE gallery_category');
        $this->addSql('DROP TABLE gallery_item');
        $this->addSql('ALTER TABLE about CHANGE picture picture VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE meta_description meta_description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
