<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240316175035 extends AbstractMigration {
    public function getDescription(): string {
        return '';
    }

    public function up(Schema $schema): void {
        $this->addSql('CREATE SEQUENCE subtitles_file_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE word_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE subtitles_file (id INT NOT NULL, name VARCHAR(255) NOT NULL, storage_path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE word (id INT NOT NULL, subtitles_file_id INT NOT NULL, value VARCHAR(255) NOT NULL, created_ts INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C3F17511CA20DBF7 ON word (subtitles_file_id)');
        $this->addSql('CREATE INDEX value_created_ts ON word (value, created_ts)');
        $this->addSql('ALTER TABLE word ADD CONSTRAINT FK_C3F17511CA20DBF7 FOREIGN KEY (subtitles_file_id) REFERENCES subtitles_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE subtitles_file_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE word_id_seq CASCADE');
        $this->addSql('ALTER TABLE word DROP CONSTRAINT FK_C3F17511CA20DBF7');
        $this->addSql('DROP TABLE subtitles_file');
        $this->addSql('DROP TABLE word');
    }
}
