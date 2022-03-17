<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220316111718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE record (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE record_user (id INT AUTO_INCREMENT NOT NULL, record_id INT NOT NULL, user_id INT NOT NULL, is_owner TINYINT(1) NOT NULL, INDEX IDX_6824479D4DFD750C (record_id), INDEX IDX_6824479DA76ED395 (user_id), UNIQUE INDEX record_user (record_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE record_user ADD CONSTRAINT FK_6824479D4DFD750C FOREIGN KEY (record_id) REFERENCES record (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE record_user ADD CONSTRAINT FK_6824479DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE record_user DROP FOREIGN KEY FK_6824479D4DFD750C');
        $this->addSql('ALTER TABLE record_user DROP FOREIGN KEY FK_6824479DA76ED395');
        $this->addSql('DROP TABLE record');
        $this->addSql('DROP TABLE record_user');
        $this->addSql('DROP TABLE user');
    }
}
