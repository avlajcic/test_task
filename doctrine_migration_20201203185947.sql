-- Doctrine Migration File Generated on 2020-12-03 18:59:47

-- Version DoctrineMigrations\Version20201203174741
CREATE SEQUENCE game_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE game_state_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE game_history (id INT NOT NULL, value VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id));
CREATE TABLE game_state (id INT NOT NULL, key VARCHAR(255) NOT NULL, state BOOLEAN NOT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_91A0AB748A90ABA9 ON game_state (key);