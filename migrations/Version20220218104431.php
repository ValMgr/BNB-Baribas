<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220218104431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE virement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE virement (id INT NOT NULL, destinataire_id INT NOT NULL, montant INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D4DCFA6A4F84F6E ON virement (destinataire_id)');
        $this->addSql('COMMENT ON COLUMN virement.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE virement ADD CONSTRAINT FK_2D4DCFA6A4F84F6E FOREIGN KEY (destinataire_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE virement_id_seq CASCADE');
        $this->addSql('DROP TABLE virement');
    }
}
