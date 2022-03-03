<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220303115226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE virement DROP CONSTRAINT FK_2D4DCFA6A4F84F6E');
        $this->addSql('ALTER TABLE virement DROP CONSTRAINT FK_2D4DCFA687998E');
        $this->addSql('ALTER TABLE virement ADD CONSTRAINT FK_2D4DCFA6A4F84F6E FOREIGN KEY (destinataire_id) REFERENCES account (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE virement ADD CONSTRAINT FK_2D4DCFA687998E FOREIGN KEY (origine_id) REFERENCES account (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE virement DROP CONSTRAINT fk_2d4dcfa687998e');
        $this->addSql('ALTER TABLE virement DROP CONSTRAINT fk_2d4dcfa6a4f84f6e');
        $this->addSql('ALTER TABLE virement ADD CONSTRAINT fk_2d4dcfa687998e FOREIGN KEY (origine_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE virement ADD CONSTRAINT fk_2d4dcfa6a4f84f6e FOREIGN KEY (destinataire_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
