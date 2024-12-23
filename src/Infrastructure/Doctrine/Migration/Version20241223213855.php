<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241223213855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('alter table public.discussions add author_email VARCHAR(2047);');
        $this->addSql('UPDATE discussions d SET author_email = dm.member_email FROM discussion_members dm WHERE d.id = dm.discussion_id;');
        $this->addSql('ALTER TABLE public.discussions alter column author_email set not null;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('alter table public.discussions drop column author_email;');
    }
}
