<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309123451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_blog ADD blogs_id INT NOT NULL, CHANGE blog_id blog_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images_blog ADD CONSTRAINT FK_FA1B60BC89C05BBC FOREIGN KEY (blogs_id) REFERENCES blog (id)');
        $this->addSql('CREATE INDEX IDX_FA1B60BC89C05BBC ON images_blog (blogs_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_blog DROP FOREIGN KEY FK_FA1B60BC89C05BBC');
        $this->addSql('DROP INDEX IDX_FA1B60BC89C05BBC ON images_blog');
        $this->addSql('ALTER TABLE images_blog DROP blogs_id, CHANGE blog_id blog_id INT NOT NULL');
    }
}
