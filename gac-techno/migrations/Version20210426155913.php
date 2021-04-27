<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210426155913 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create entity ticket call';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE ticket_call (id INT AUTO_INCREMENT NOT NULL, invoiced_account INT NOT NULL, invoice_number INT NOT NULL, user_number INT NOT NULL, date_call DATETIME NOT NULL, hour_call VARCHAR(255) NOT NULL, real_duration INT DEFAULT NULL, real_volume INT DEFAULT NULL, invoiced_duration INT DEFAULT NULL, invoice_volume INT DEFAULT NULL, type_call VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE ticket_call');
    }
}
