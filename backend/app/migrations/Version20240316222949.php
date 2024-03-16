<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240316222949 extends AbstractMigration {

    public function getDescription(): string {
        return '';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE subtitles_file ADD created_ts INT NOT NULL');
    }

    public function down(Schema $schema): void {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subtitles_file DROP created_ts');
    }
}
