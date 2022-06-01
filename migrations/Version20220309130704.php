<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309130704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_blog DROP FOREIGN KEY FK_FA1B60BCDAE07E97');
        $this->addSql('DROP INDEX IDX_FA1B60BCDAE07E97 ON images_blog');
        $this->addSql('ALTER TABLE images_blog DROP blog_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_blog ADD blog_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images_blog ADD CONSTRAINT FK_FA1B60BCDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('CREATE INDEX IDX_FA1B60BCDAE07E97 ON images_blog (blog_id)');
    }
}
