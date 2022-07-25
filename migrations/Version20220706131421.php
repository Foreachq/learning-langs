<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220706131421 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE words_progress (
                id                    SERIAL NOT NULL,
                profile_id            INT NOT NULL,
                word_id               INT NOT NULL,
                active                BOOLEAN NOT NULL,
                attempts_count        INT NOT NULL,
                correct_answers_count INT NOT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_A85008EBCCFA12B8 ON words_progress (profile_id)');
        $this->addSql('CREATE INDEX IDX_A85008EBE357438D ON words_progress (word_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A85008EBCCFA12B8E357438D ON words_progress (profile_id, word_id)');
        $this->addSql('
            ALTER TABLE words_progress ADD CONSTRAINT FK_A85008EBCCFA12B8 FOREIGN KEY (profile_id)
            REFERENCES profiles (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('
            ALTER TABLE words_progress ADD CONSTRAINT FK_A85008EBE357438D FOREIGN KEY (word_id)
            REFERENCES words (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('ALTER TABLE users ALTER roles DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE words_progress');
        $this->addSql('ALTER TABLE users ALTER roles SET DEFAULT \'[]\'');
    }
}
