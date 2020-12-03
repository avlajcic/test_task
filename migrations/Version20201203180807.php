<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\GameState;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203180807 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_history (id SERIAL NOT NULL, value VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE game_state (id SERIAL NOT NULL, key VARCHAR(255) NOT NULL, state BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_91A0AB748A90ABA9 ON game_state (key)');

        $this->addSql('INSERT INTO game_state (key, state) VALUES (\'' . GameState::GAME_STATE_KEY .'\', false)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE game_history');
        $this->addSql('DROP TABLE game_state');
    }
}
