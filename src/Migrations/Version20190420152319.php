<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190420152319 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE agenda (id INT AUTO_INCREMENT NOT NULL, prof_id INT NOT NULL, user_id INT NOT NULL, jour INT NOT NULL, debut TIME NOT NULL, fin TIME NOT NULL, raison LONGTEXT DEFAULT NULL, datep DATE NOT NULL, INDEX IDX_2CEDC877ABC1F7FE (prof_id), INDEX IDX_2CEDC877A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, matiere VARCHAR(255) NOT NULL, niveau INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence_professeur (competence_id INT NOT NULL, professeur_id INT NOT NULL, INDEX IDX_3925EA6E15761DAB (competence_id), INDEX IDX_3925EA6EBAB22EE9 (professeur_id), PRIMARY KEY(competence_id, professeur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, jour INT NOT NULL, debut TIME NOT NULL, fin TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disponibilite_professeur (disponibilite_id INT NOT NULL, professeur_id INT NOT NULL, INDEX IDX_746FDAF82B9D6493 (disponibilite_id), INDEX IDX_746FDAF8BAB22EE9 (professeur_id), PRIMARY KEY(disponibilite_id, professeur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professeur (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, adresse VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_17A55299A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, confidental_id INT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, sexe TINYINT(1) DEFAULT NULL, phone VARCHAR(10) DEFAULT NULL, birthday DATE DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649C2681CF5 (confidental_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usersecurity (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E9DE2AB6E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC877ABC1F7FE FOREIGN KEY (prof_id) REFERENCES professeur (id)');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC877A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE competence_professeur ADD CONSTRAINT FK_3925EA6E15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competence_professeur ADD CONSTRAINT FK_3925EA6EBAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE disponibilite_professeur ADD CONSTRAINT FK_746FDAF82B9D6493 FOREIGN KEY (disponibilite_id) REFERENCES disponibilite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE disponibilite_professeur ADD CONSTRAINT FK_746FDAF8BAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE professeur ADD CONSTRAINT FK_17A55299A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C2681CF5 FOREIGN KEY (confidental_id) REFERENCES usersecurity (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE competence_professeur DROP FOREIGN KEY FK_3925EA6E15761DAB');
        $this->addSql('ALTER TABLE disponibilite_professeur DROP FOREIGN KEY FK_746FDAF82B9D6493');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC877ABC1F7FE');
        $this->addSql('ALTER TABLE competence_professeur DROP FOREIGN KEY FK_3925EA6EBAB22EE9');
        $this->addSql('ALTER TABLE disponibilite_professeur DROP FOREIGN KEY FK_746FDAF8BAB22EE9');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC877A76ED395');
        $this->addSql('ALTER TABLE professeur DROP FOREIGN KEY FK_17A55299A76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C2681CF5');
        $this->addSql('DROP TABLE agenda');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE competence_professeur');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE disponibilite_professeur');
        $this->addSql('DROP TABLE professeur');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE usersecurity');
    }
}
