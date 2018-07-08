<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180708185423 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE page (
                        page_id INT AUTO_INCREMENT NOT NULL,
                        creator_user_id INT NOT NULL,
                        password VARCHAR(255) NOT NULL,
                        page_name VARCHAR(255) NOT NULL,
                        page_type VARCHAR(255) NOT NULL,
                        date_of_creation DATETIME NOT NULL,
                        since_date DATETIME DEFAULT NULL,
                        contact_email VARCHAR(255) NOT NULL,
                        contact_address VARCHAR(255) DEFAULT NULL,
                        contact_postal_code VARCHAR(255) DEFAULT NULL,
                        contact_phone VARCHAR(255) DEFAULT NULL,
                        page_pic VARCHAR(255) DEFAULT \'default_business.jpg\',
                        page_cover_pic VARCHAR(255) DEFAULT NULL,
                        about LONGTEXT DEFAULT NULL,
                        PRIMARY KEY(page_id, creator_user_id)
                       ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE page');
    }
}
