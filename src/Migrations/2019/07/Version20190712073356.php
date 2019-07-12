<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190712073356 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE departments (id UUID NOT NULL, region_id UUID DEFAULT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16AEB8D4989D9B62 ON departments (slug)');
        $this->addSql('CREATE INDEX IDX_16AEB8D498260155 ON departments (region_id)');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, firstname VARCHAR(75) NOT NULL, lastname VARCHAR(75) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_verified BOOLEAN DEFAULT NULL, verification_token VARCHAR(255) DEFAULT NULL, is_banned BOOLEAN DEFAULT NULL, roles TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN users.roles IS \'(DC2Type:json)\'');
        $this->addSql('CREATE TABLE hobbies_users (user_id UUID NOT NULL, hobby_id UUID NOT NULL, PRIMARY KEY(user_id, hobby_id))');
        $this->addSql('CREATE INDEX IDX_46DAFC3CA76ED395 ON hobbies_users (user_id)');
        $this->addSql('CREATE INDEX IDX_46DAFC3C322B2123 ON hobbies_users (hobby_id)');
        $this->addSql('CREATE TABLE users_communities (user_id UUID NOT NULL, community_id UUID NOT NULL, PRIMARY KEY(user_id, community_id))');
        $this->addSql('CREATE INDEX IDX_F97B0DD9A76ED395 ON users_communities (user_id)');
        $this->addSql('CREATE INDEX IDX_F97B0DD9FDA7B0BF ON users_communities (community_id)');
        $this->addSql('CREATE TABLE ads_categories (id UUID NOT NULL, tree_root UUID DEFAULT NULL, parent_id UUID DEFAULT NULL, label VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, lft INT DEFAULT NULL, lvl INT DEFAULT NULL, rgt INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FC8F3A8989D9B62 ON ads_categories (slug)');
        $this->addSql('CREATE INDEX IDX_6FC8F3A8A977936C ON ads_categories (tree_root)');
        $this->addSql('CREATE INDEX IDX_6FC8F3A8727ACA70 ON ads_categories (parent_id)');
        $this->addSql('CREATE TABLE ads (id UUID NOT NULL, category_id UUID NOT NULL, user_id UUID NOT NULL, community_id UUID NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, description TEXT NOT NULL, enabled BOOLEAN DEFAULT NULL, uev INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7EC9F620989D9B62 ON ads (slug)');
        $this->addSql('CREATE INDEX IDX_7EC9F62012469DE2 ON ads (category_id)');
        $this->addSql('CREATE INDEX IDX_7EC9F620A76ED395 ON ads (user_id)');
        $this->addSql('CREATE INDEX IDX_7EC9F620FDA7B0BF ON ads (community_id)');
        $this->addSql('CREATE TABLE cities (id UUID NOT NULL, department_id UUID DEFAULT NULL, insee_code VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95DB16B989D9B62 ON cities (slug)');
        $this->addSql('CREATE INDEX IDX_D95DB16BAE80F5DF ON cities (department_id)');
        $this->addSql('CREATE TABLE hobbies (id UUID NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE blog_posts (id UUID NOT NULL, category_id UUID DEFAULT NULL, user_id UUID DEFAULT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, is_published BOOLEAN DEFAULT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_deleted BOOLEAN DEFAULT NULL, can_comment BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_78B2F93212469DE2 ON blog_posts (category_id)');
        $this->addSql('CREATE INDEX IDX_78B2F932A76ED395 ON blog_posts (user_id)');
        $this->addSql('CREATE TABLE blog_categories (id UUID NOT NULL, label VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE blog_comments (id UUID NOT NULL, user_id UUID DEFAULT NULL, post_id UUID DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, published BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2BC3B20DA76ED395 ON blog_comments (user_id)');
        $this->addSql('CREATE INDEX IDX_2BC3B20D4B89032C ON blog_comments (post_id)');
        $this->addSql('CREATE TABLE regions (id UUID NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A26779F3989D9B62 ON regions (slug)');
        $this->addSql('CREATE TABLE informations_users (id UUID NOT NULL, user_id UUID DEFAULT NULL, city_id UUID DEFAULT NULL, description TEXT DEFAULT NULL, uev DOUBLE PRECISION NOT NULL, address VARCHAR(255) NOT NULL, address_1 VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1016EF8DA76ED395 ON informations_users (user_id)');
        $this->addSql('CREATE INDEX IDX_1016EF8D8BAC62AF ON informations_users (city_id)');
        $this->addSql('CREATE TABLE orders (id UUID NOT NULL, user_id UUID DEFAULT NULL, transaction UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E52FFDEEA76ED395 ON orders (user_id)');
        $this->addSql('CREATE TABLE communities (id UUID NOT NULL, user_creator_id UUID DEFAULT NULL, city_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, description TEXT NOT NULL, is_enabled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ECE312B989D9B62 ON communities (slug)');
        $this->addSql('CREATE INDEX IDX_ECE312BC645C84A ON communities (user_creator_id)');
        $this->addSql('CREATE INDEX IDX_ECE312B8BAC62AF ON communities (city_id)');
        $this->addSql('ALTER TABLE departments ADD CONSTRAINT FK_16AEB8D498260155 FOREIGN KEY (region_id) REFERENCES regions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hobbies_users ADD CONSTRAINT FK_46DAFC3CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hobbies_users ADD CONSTRAINT FK_46DAFC3C322B2123 FOREIGN KEY (hobby_id) REFERENCES hobbies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_communities ADD CONSTRAINT FK_F97B0DD9A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_communities ADD CONSTRAINT FK_F97B0DD9FDA7B0BF FOREIGN KEY (community_id) REFERENCES communities (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ads_categories ADD CONSTRAINT FK_6FC8F3A8A977936C FOREIGN KEY (tree_root) REFERENCES ads_categories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ads_categories ADD CONSTRAINT FK_6FC8F3A8727ACA70 FOREIGN KEY (parent_id) REFERENCES ads_categories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F62012469DE2 FOREIGN KEY (category_id) REFERENCES ads_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F620A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F620FDA7B0BF FOREIGN KEY (community_id) REFERENCES communities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cities ADD CONSTRAINT FK_D95DB16BAE80F5DF FOREIGN KEY (department_id) REFERENCES departments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F93212469DE2 FOREIGN KEY (category_id) REFERENCES blog_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F932A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE blog_comments ADD CONSTRAINT FK_2BC3B20DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE blog_comments ADD CONSTRAINT FK_2BC3B20D4B89032C FOREIGN KEY (post_id) REFERENCES blog_posts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE informations_users ADD CONSTRAINT FK_1016EF8DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE informations_users ADD CONSTRAINT FK_1016EF8D8BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE communities ADD CONSTRAINT FK_ECE312BC645C84A FOREIGN KEY (user_creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE communities ADD CONSTRAINT FK_ECE312B8BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE cities DROP CONSTRAINT FK_D95DB16BAE80F5DF');
        $this->addSql('ALTER TABLE hobbies_users DROP CONSTRAINT FK_46DAFC3CA76ED395');
        $this->addSql('ALTER TABLE users_communities DROP CONSTRAINT FK_F97B0DD9A76ED395');
        $this->addSql('ALTER TABLE ads DROP CONSTRAINT FK_7EC9F620A76ED395');
        $this->addSql('ALTER TABLE blog_posts DROP CONSTRAINT FK_78B2F932A76ED395');
        $this->addSql('ALTER TABLE blog_comments DROP CONSTRAINT FK_2BC3B20DA76ED395');
        $this->addSql('ALTER TABLE informations_users DROP CONSTRAINT FK_1016EF8DA76ED395');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE communities DROP CONSTRAINT FK_ECE312BC645C84A');
        $this->addSql('ALTER TABLE ads_categories DROP CONSTRAINT FK_6FC8F3A8A977936C');
        $this->addSql('ALTER TABLE ads_categories DROP CONSTRAINT FK_6FC8F3A8727ACA70');
        $this->addSql('ALTER TABLE ads DROP CONSTRAINT FK_7EC9F62012469DE2');
        $this->addSql('ALTER TABLE informations_users DROP CONSTRAINT FK_1016EF8D8BAC62AF');
        $this->addSql('ALTER TABLE communities DROP CONSTRAINT FK_ECE312B8BAC62AF');
        $this->addSql('ALTER TABLE hobbies_users DROP CONSTRAINT FK_46DAFC3C322B2123');
        $this->addSql('ALTER TABLE blog_comments DROP CONSTRAINT FK_2BC3B20D4B89032C');
        $this->addSql('ALTER TABLE blog_posts DROP CONSTRAINT FK_78B2F93212469DE2');
        $this->addSql('ALTER TABLE departments DROP CONSTRAINT FK_16AEB8D498260155');
        $this->addSql('ALTER TABLE users_communities DROP CONSTRAINT FK_F97B0DD9FDA7B0BF');
        $this->addSql('ALTER TABLE ads DROP CONSTRAINT FK_7EC9F620FDA7B0BF');
        $this->addSql('DROP TABLE departments');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE hobbies_users');
        $this->addSql('DROP TABLE users_communities');
        $this->addSql('DROP TABLE ads_categories');
        $this->addSql('DROP TABLE ads');
        $this->addSql('DROP TABLE cities');
        $this->addSql('DROP TABLE hobbies');
        $this->addSql('DROP TABLE blog_posts');
        $this->addSql('DROP TABLE blog_categories');
        $this->addSql('DROP TABLE blog_comments');
        $this->addSql('DROP TABLE regions');
        $this->addSql('DROP TABLE informations_users');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE communities');
    }
}
