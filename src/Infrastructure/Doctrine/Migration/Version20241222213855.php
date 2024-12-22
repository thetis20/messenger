<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241222213855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('alter table public.messages alter column message drop not null;');
        $this->addSql('alter table public.messages add updated_at timestamp;');
        $this->addSql('alter table public.messages add deleted bool not null default false;');
        $this->addSql('UPDATE public.messages SET updated_at = created_at WHERE updated_at is NULL;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE discussions (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN discussions.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE members (email VARCHAR(255) NOT NULL, useridentifier VARCHAR(255) DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, PRIMARY KEY(email))');
        $this->addSql('CREATE TABLE messages (id UUID NOT NULL, discussion_id UUID NOT NULL, member_email VARCHAR(2047) NOT NULL, message VARCHAR(2047) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DB021E961ADED311 ON messages (discussion_id)');
        $this->addSql('CREATE INDEX IDX_DB021E9685B3E987 ON messages (member_email)');
        $this->addSql('COMMENT ON COLUMN messages.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE discussion_members (discussion_id UUID NOT NULL, member_email VARCHAR(2047) NOT NULL, seen BOOLEAN DEFAULT false, PRIMARY KEY(discussion_id, member_email))');
        $this->addSql('CREATE INDEX IDX_CDFEF4421ADED311 ON discussion_members (discussion_id)');
        $this->addSql('CREATE INDEX IDX_CDFEF44285B3E987 ON discussion_members (member_email)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT fk_discussion FOREIGN KEY (discussion_id) REFERENCES discussions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT fk_member FOREIGN KEY (member_email) REFERENCES members (email) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE discussion_members ADD CONSTRAINT fk_discussion FOREIGN KEY (discussion_id) REFERENCES discussions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE discussion_members ADD CONSTRAINT fk_member FOREIGN KEY (member_email) REFERENCES members (email) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
