<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170629155156 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE newbie ADD employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE newbie ADD CONSTRAINT FK_E6B2F5E68C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6B2F5E68C03F15C ON newbie (employee_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE newbie DROP FOREIGN KEY FK_E6B2F5E68C03F15C');
        $this->addSql('DROP INDEX UNIQ_E6B2F5E68C03F15C ON newbie');
        $this->addSql('ALTER TABLE newbie DROP employee_id');
    }
}
