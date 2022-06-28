<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220617175819 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE users
            DROP CONSTRAINT users_pkey CASCADE
        ');
        $this->addSql('ALTER TABLE users ADD id SERIAL NOT NULL');
        $this->addSql('ALTER TABLE users ADD PRIMARY KEY (id)');
        $this->addSql('CREATE TYPE gender_enum AS ENUM (\'male\', \'female\')');
        $this->addSql('
            CREATE TABLE profiles (
                id         SERIAL NOT NULL,
                user_id    INT DEFAULT NULL,
                first_name VARCHAR(64) NOT NULL,
                last_name  VARCHAR(64) NOT NULL,
                gender     gender_enum NOT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8B308530A76ED395 ON profiles (user_id)');
        $this->addSql('
            ALTER TABLE profiles ADD CONSTRAINT FK_8B308530A76ED395 FOREIGN KEY (user_id)
            REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('ALTER TABLE users ADD profile_id INT DEFAULT NULL');
        $this->addSql('
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9CCFA12B8
            FOREIGN KEY (profile_id) REFERENCES profiles (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9CCFA12B8 ON users (profile_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9CCFA12B8');
        $this->addSql('DROP TABLE profiles');
        $this->addSql('DROP TYPE gender_enum');
        $this->addSql('DROP INDEX UNIQ_1483A5E9E7927C74');
        $this->addSql('ALTER TABLE users DROP id');
        $this->addSql('ALTER TABLE users ADD PRIMARY KEY (email)');
        $this->addSql('DROP INDEX UNIQ_1483A5E9CCFA12B8');
        $this->addSql('ALTER TABLE users DROP profile_id');
    }
}
