<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220701154015 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE profiles ADD is_verified BOOLEAN NOT NULL DEFAULT FALSE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE profiles DROP is_verified');
    }
}
