<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240909111852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE review (
            id INTEGER PRIMARY KEY AUTOINCREMENT, 
            video_game_id INTEGER NOT NULL, 
            user_id INTEGER NOT NULL, 
            rating INTEGER NOT NULL, 
            comment TEXT DEFAULT NULL,
            FOREIGN KEY (video_game_id) REFERENCES video_game (id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES user (id)
        )');
        $this->addSql('CREATE INDEX IDX_794381C616230A8 ON review (video_game_id)');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
    
        $this->addSql('CREATE TABLE tag (
            id INTEGER PRIMARY KEY AUTOINCREMENT, 
            code VARCHAR(255) NOT NULL, 
            name VARCHAR(30) NOT NULL
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B78377153098 ON tag (code)');
    
        $this->addSql('CREATE TABLE user (
            id INTEGER PRIMARY KEY AUTOINCREMENT, 
            username VARCHAR(30) NOT NULL, 
            email VARCHAR(255) NOT NULL, 
            password VARCHAR(60) NOT NULL
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    
        $this->addSql('CREATE TABLE video_game (
            id INTEGER PRIMARY KEY AUTOINCREMENT, 
            title VARCHAR(100) NOT NULL, 
            image_name VARCHAR(255) DEFAULT NULL, 
            image_size INTEGER DEFAULT NULL, 
            slug VARCHAR(255) NOT NULL, 
            description TEXT NOT NULL, 
            release_date DATE NOT NULL, 
            updated_at DATETIME DEFAULT NULL, 
            test TEXT DEFAULT NULL, 
            rating INTEGER DEFAULT NULL, 
            average_rating INTEGER DEFAULT NULL, 
            number_of_ratings_per_value_number_of_one INTEGER NOT NULL, 
            number_of_ratings_per_value_number_of_two INTEGER NOT NULL, 
            number_of_ratings_per_value_number_of_three INTEGER NOT NULL, 
            number_of_ratings_per_value_number_of_four INTEGER NOT NULL, 
            number_of_ratings_per_value_number_of_five INTEGER NOT NULL
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24BC6C50989D9B62 ON video_game (slug)');
    
        $this->addSql('CREATE TABLE video_game_tags (
            video_game_id INTEGER NOT NULL, 
            tag_id INTEGER NOT NULL, 
            PRIMARY KEY(video_game_id, tag_id),
            FOREIGN KEY (video_game_id) REFERENCES video_game (id) ON DELETE CASCADE,
            FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE
        )');
        $this->addSql('CREATE INDEX IDX_46D6859F16230A8 ON video_game_tags (video_game_id)');
        $this->addSql('CREATE INDEX IDX_46D6859FBAD26311 ON video_game_tags (tag_id)');
    
        // Activer les clés étrangères pour SQLite
        $this->addSql('PRAGMA foreign_keys = ON;');
    }

    public function down(Schema $schema): void
    {
        // Revenir à l'état initial
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE video_game');
        $this->addSql('DROP TABLE video_game_tags');
    }
}