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
final class Version20190825093930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE ads_thread (id UUID NOT NULL, ad_id UUID NOT NULL, user_creator_id UUID NOT NULL, is_deleted BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AB80359D4F34D596 ON ads_thread (ad_id)');
        $this->addSql('CREATE INDEX IDX_AB80359DC645C84A ON ads_thread (user_creator_id)');
        $this->addSql('CREATE TABLE ads_users_threads (thread_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(thread_id, user_id))');
        $this->addSql('CREATE INDEX IDX_26986CDBE2904019 ON ads_users_threads (thread_id)');
        $this->addSql('CREATE INDEX IDX_26986CDBA76ED395 ON ads_users_threads (user_id)');
        $this->addSql('CREATE TABLE ads_messages_threads (id UUID NOT NULL, thread_id UUID NOT NULL, user_sender_id UUID NOT NULL, body TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F2B7632DE2904019 ON ads_messages_threads (thread_id)');
        $this->addSql('CREATE INDEX IDX_F2B7632DF6C43E79 ON ads_messages_threads (user_sender_id)');
        $this->addSql('ALTER TABLE ads_thread ADD CONSTRAINT FK_AB80359D4F34D596 FOREIGN KEY (ad_id) REFERENCES ads (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ads_thread ADD CONSTRAINT FK_AB80359DC645C84A FOREIGN KEY (user_creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ads_users_threads ADD CONSTRAINT FK_26986CDBE2904019 FOREIGN KEY (thread_id) REFERENCES ads_thread (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ads_users_threads ADD CONSTRAINT FK_26986CDBA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ads_messages_threads ADD CONSTRAINT FK_F2B7632DE2904019 FOREIGN KEY (thread_id) REFERENCES ads_thread (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ads_messages_threads ADD CONSTRAINT FK_F2B7632DF6C43E79 FOREIGN KEY (user_sender_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ads_users_threads DROP CONSTRAINT FK_26986CDBE2904019');
        $this->addSql('ALTER TABLE ads_messages_threads DROP CONSTRAINT FK_F2B7632DE2904019');
        $this->addSql('DROP TABLE ads_thread');
        $this->addSql('DROP TABLE ads_users_threads');
        $this->addSql('DROP TABLE ads_messages_threads');
    }
}
