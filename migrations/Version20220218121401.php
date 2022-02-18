<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220218121401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE virement ADD compte_id INT NOT NULL');
        $this->addSql('ALTER TABLE virement ADD CONSTRAINT FK_2D4DCFA6F2C56620 FOREIGN KEY (compte_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D4DCFA6F2C56620 ON virement (compte_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE virement DROP CONSTRAINT FK_2D4DCFA6F2C56620');
        $this->addSql('DROP INDEX UNIQ_2D4DCFA6F2C56620');
        $this->addSql('ALTER TABLE virement DROP compte_id');
    }
}
