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
        $this->addSql('alter table public.messages alter column message add not null;');
        $this->addSql('alter table public.messages drop column updated_at;');
        $this->addSql('alter table public.messages drop column deleted;');
    }
}
