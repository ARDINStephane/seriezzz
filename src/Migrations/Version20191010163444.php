<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191010163444 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE favorite (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, serie VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_68C58ED98D93D649 (user), INDEX IDX_68C58ED9AA3A9334 (serie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode (id VARCHAR(255) NOT NULL, season VARCHAR(255) DEFAULT NULL, serie VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, number INT NOT NULL, images LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', year VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', note VARCHAR(255) DEFAULT NULL, episode_show VARCHAR(255) DEFAULT NULL, seen TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_DDAA1CDA2B36786B (title), UNIQUE INDEX UNIQ_DDAA1CDA989D9B62 (slug), UNIQUE INDEX UNIQ_DDAA1CDA96901F54 (number), INDEX IDX_DDAA1CDAF0E45BA9 (season), INDEX IDX_DDAA1CDAAA3A9334 (serie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE serie (id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, alias LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', images LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', year VARCHAR(255) DEFAULT NULL, origin VARCHAR(255) DEFAULT NULL, genre LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', number_of_seasons VARCHAR(255) DEFAULT NULL, seasons_details TINYTEXT NOT NULL COMMENT \'(DC2Type:array)\', number_of_episodes VARCHAR(255) DEFAULT NULL, last_episode VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', note VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, serie_show VARCHAR(255) DEFAULT NULL, seen TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_AA3A93342B36786B (title), UNIQUE INDEX UNIQ_AA3A9334989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id VARCHAR(255) NOT NULL, serie VARCHAR(255) DEFAULT NULL, number INT NOT NULL, images LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', year VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', note VARCHAR(255) DEFAULT NULL, season_show VARCHAR(255) DEFAULT NULL, seen TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, lastEpisodeSeen VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F0E45BA996901F54 (number), UNIQUE INDEX UNIQ_F0E45BA9F89C4980 (lastEpisodeSeen), INDEX IDX_F0E45BA9AA3A9334 (serie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(100) DEFAULT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED98D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9AA3A9334 FOREIGN KEY (serie) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDAF0E45BA9 FOREIGN KEY (season) REFERENCES season (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDAAA3A9334 FOREIGN KEY (serie) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA9F89C4980 FOREIGN KEY (lastEpisodeSeen) REFERENCES episode (id)');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA9AA3A9334 FOREIGN KEY (serie) REFERENCES serie (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA9F89C4980');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9AA3A9334');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDAAA3A9334');
        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA9AA3A9334');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDAF0E45BA9');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED98D93D649');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE serie');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE user');
    }
}
