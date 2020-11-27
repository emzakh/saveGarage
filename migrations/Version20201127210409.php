<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127210409 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, voiture_id INT NOT NULL, url VARCHAR(255) NOT NULL, caption VARCHAR(255) NOT NULL, INDEX IDX_C53D045F181A8BA (voiture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, introduction VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voiture (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, img_cover VARCHAR(255) NOT NULL, km INT NOT NULL, prix INT NOT NULL, nb_proprio INT NOT NULL, cylindre INT NOT NULL, puissance INT NOT NULL, carburant VARCHAR(255) NOT NULL, date_circu DATE NOT NULL, transmission VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, sup_option LONGTEXT DEFAULT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_E9E2810FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810FF675F31B');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F181A8BA');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE voiture');
    }
}
