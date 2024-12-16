<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102092415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "discussions" (
                id UUID NOT NULL,
                name VARCHAR(255) NOT NULL,
                PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "discussions".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "members" (
                email VARCHAR(255) NOT NULL,
                userIdentifier VARCHAR(255) DEFAULT NULL,
                username VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY(email))');
        $this->addSql('CREATE TABLE "messages" (
                id UUID NOT NULL,
                discussion_id UUID NOT NULL,
                message VARCHAR(2047) NOT NULL,
                member_email VARCHAR(2047) NOT NULL,
                PRIMARY KEY(id),
                CONSTRAINT fk_discussion FOREIGN KEY(discussion_id) REFERENCES discussions(id),
                CONSTRAINT fk_member FOREIGN KEY(member_email) REFERENCES members(email))');
        $this->addSql('COMMENT ON COLUMN "messages".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "discussion_members" (
                discussion_id UUID NOT NULL,
                member_email VARCHAR(2047) NOT NULL,
                seen BOOLEAN DEFAULT false,
                PRIMARY KEY(discussion_id, member_email),
                CONSTRAINT fk_discussion FOREIGN KEY(discussion_id) REFERENCES discussions(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE "messages"');
        $this->addSql('DROP TABLE "discussion_members"');
        $this->addSql('DROP TABLE "discussions"');
        $this->addSql('DROP TABLE "members"');
    }
}
