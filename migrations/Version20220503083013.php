<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220503083013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message_prive (id INT AUTO_INCREMENT NOT NULL, expediteur_id INT DEFAULT NULL, destinataire_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_2DB3B2610335F61 (expediteur_id), INDEX IDX_2DB3B26A4F84F6E (destinataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message_prive ADD CONSTRAINT FK_2DB3B2610335F61 FOREIGN KEY (expediteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message_prive ADD CONSTRAINT FK_2DB3B26A4F84F6E FOREIGN KEY (destinataire_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE message_prive');
    }
}
