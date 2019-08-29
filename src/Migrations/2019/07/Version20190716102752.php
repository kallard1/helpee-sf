<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190716102752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE community_user (community_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(community_id, user_id))');
        $this->addSql('CREATE INDEX IDX_4CC23C83FDA7B0BF ON community_user (community_id)');
        $this->addSql('CREATE INDEX IDX_4CC23C83A76ED395 ON community_user (user_id)');
        $this->addSql('ALTER TABLE community_user ADD CONSTRAINT FK_4CC23C83FDA7B0BF FOREIGN KEY (community_id) REFERENCES communities (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE community_user ADD CONSTRAINT FK_4CC23C83A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE users_communities');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE users_communities (user_id UUID NOT NULL, community_id UUID NOT NULL, PRIMARY KEY(user_id, community_id))');
        $this->addSql('CREATE INDEX idx_f97b0dd9a76ed395 ON users_communities (user_id)');
        $this->addSql('CREATE INDEX idx_f97b0dd9fda7b0bf ON users_communities (community_id)');
        $this->addSql('ALTER TABLE users_communities ADD CONSTRAINT fk_f97b0dd9a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_communities ADD CONSTRAINT fk_f97b0dd9fda7b0bf FOREIGN KEY (community_id) REFERENCES communities (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE community_user');
    }
}
