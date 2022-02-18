<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220218134929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE virement DROP CONSTRAINT fk_2d4dcfa6f2c56620');
        $this->addSql('DROP INDEX uniq_2d4dcfa6f2c56620');
        $this->addSql('ALTER TABLE virement RENAME COLUMN compte_id TO origine_id');
        $this->addSql('ALTER TABLE virement ADD CONSTRAINT FK_2D4DCFA687998E FOREIGN KEY (origine_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2D4DCFA687998E ON virement (origine_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE virement DROP CONSTRAINT FK_2D4DCFA687998E');
        $this->addSql('DROP INDEX IDX_2D4DCFA687998E');
        $this->addSql('ALTER TABLE virement RENAME COLUMN origine_id TO compte_id');
        $this->addSql('ALTER TABLE virement ADD CONSTRAINT fk_2d4dcfa6f2c56620 FOREIGN KEY (compte_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_2d4dcfa6f2c56620 ON virement (compte_id)');
    }
}
