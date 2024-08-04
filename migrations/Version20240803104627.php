<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240803104627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, video_game_id INT NOT NULL, user_id INT NOT NULL, rating INT NOT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C616230A8 ON review (video_game_id)');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B78377153098 ON tag (code(191))');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(30) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(60) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username(30))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email(191))');
        $this->addSql('CREATE TABLE video_game (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, description TEXT NOT NULL, release_date DATE NOT NULL, updated_at DATETIME DEFAULT NULL, test TEXT DEFAULT NULL, rating INT DEFAULT NULL, average_rating INT DEFAULT NULL, number_of_ratings_per_value_number_of_one INT NOT NULL, number_of_ratings_per_value_number_of_two INT NOT NULL, number_of_ratings_per_value_number_of_three INT NOT NULL, number_of_ratings_per_value_number_of_four INT NOT NULL, number_of_ratings_per_value_number_of_five INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24BC6C50989D9B62 ON video_game (slug(191))');
        $this->addSql('CREATE TABLE video_game_tags (video_game_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(video_game_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_46D6859F16230A8 ON video_game_tags (video_game_id)');
        $this->addSql('CREATE INDEX IDX_46D6859FBAD26311 ON video_game_tags (tag_id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C616230A8 FOREIGN KEY (video_game_id) REFERENCES video_game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE video_game_tags ADD CONSTRAINT FK_46D6859F16230A8 FOREIGN KEY (video_game_id) REFERENCES video_game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_game_tags ADD CONSTRAINT FK_46D6859FBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE video_game');
        $this->addSql('DROP TABLE video_game_tags');
    }
}