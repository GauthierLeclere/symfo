<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190915064618 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE client_adresse (client_id INT NOT NULL, adresse_id INT NOT NULL, INDEX IDX_91624C6B19EB6921 (client_id), INDEX IDX_91624C6B4DE7DC5C (adresse_id), PRIMARY KEY(client_id, adresse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_adresse ADD CONSTRAINT FK_91624C6B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_adresse ADD CONSTRAINT FK_91624C6B4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adresse ADD adresse_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F08166B52330C FOREIGN KEY (adresse_type_id) REFERENCES adresse_type (id)');
        $this->addSql('CREATE INDEX IDX_C35F08166B52330C ON adresse (adresse_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE client_adresse');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F08166B52330C');
        $this->addSql('DROP INDEX IDX_C35F08166B52330C ON adresse');
        $this->addSql('ALTER TABLE adresse DROP adresse_type_id');
    }
}
