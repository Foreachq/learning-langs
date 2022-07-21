<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220702182151 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD roles JSON NOT NULL DEFAULT \'[]\'');
        $this->addSql('
            CREATE TABLE words (
                id      SERIAL NOT NULL,
                english VARCHAR(64) NOT NULL,
                russian VARCHAR(64) NOT NULL,
                PRIMARY KEY(id))
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE words');
        $this->addSql('ALTER TABLE users DROP roles');
    }
}
