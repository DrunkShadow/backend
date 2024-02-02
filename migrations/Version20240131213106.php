<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131213106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materielle MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON materielle');
        $this->addSql('ALTER TABLE materielle ADD reference INT NOT NULL, DROP id, CHANGE matrielle_titre titre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE materielle ADD PRIMARY KEY (reference)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materielle ADD id INT AUTO_INCREMENT NOT NULL, DROP reference, CHANGE titre matrielle_titre VARCHAR(255) NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
