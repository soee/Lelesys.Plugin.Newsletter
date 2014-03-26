<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Migrations for Lelesys.Plugin.Newsletter package
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140322105857 extends AbstractMigration {

	/**
	 * Up Migrations
	 *
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		// this up() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE lelesys_plugin_newsletter_domain_model_category (persistence_object_identifier VARCHAR(40) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE lelesys_plugin_newsletter_domain_model_emaillog (persistence_object_identifier VARCHAR(40) NOT NULL, newsletter VARCHAR(40) DEFAULT NULL, timecreated DATETIME NOT NULL, timesent DATETIME DEFAULT NULL, recipienttype VARCHAR(255) NOT NULL, recipient VARCHAR(255) NOT NULL, issent TINYINT(1) NOT NULL, isfailed TINYINT(1) NOT NULL, INDEX IDX_F61B63FA7E8585C8 (newsletter), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE lelesys_plugin_newsletter_domain_model_newsletter (persistence_object_identifier VARCHAR(40) NOT NULL, attachments VARCHAR(40) DEFAULT NULL, fromname VARCHAR(255) DEFAULT NULL, fromemail VARCHAR(255) NOT NULL, replytoname VARCHAR(255) DEFAULT NULL, replytoemail VARCHAR(255) DEFAULT NULL, organisation VARCHAR(255) DEFAULT NULL, subject VARCHAR(255) DEFAULT NULL, encoding VARCHAR(255) DEFAULT NULL, characterset VARCHAR(255) DEFAULT NULL, htmlbody LONGTEXT DEFAULT NULL, plaintextbody LONGTEXT DEFAULT NULL, priority INT DEFAULT NULL, issent TINYINT(1) DEFAULT NULL, contentnode VARCHAR(255) DEFAULT NULL, INDEX IDX_739A5FC647C4FAD6 (attachments), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE lelesys_plugin_newsletter_domain_model_ne_11124_categories_join (newsletter_newsletter VARCHAR(40) NOT NULL, newsletter_category VARCHAR(40) NOT NULL, INDEX IDX_B49207ED9390BC62 (newsletter_newsletter), INDEX IDX_B49207EDDB4EDFAB (newsletter_category), PRIMARY KEY(newsletter_newsletter, newsletter_category)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE lelesys_plugin_newsletter_domain_mod_11124_recipientgroups_join (newsletter_newsletter VARCHAR(40) NOT NULL, newsletter_recipient_abstractgroup VARCHAR(40) NOT NULL, INDEX IDX_28D983869390BC62 (newsletter_newsletter), INDEX IDX_28D983862A8E4F6B (newsletter_recipient_abstractgroup), PRIMARY KEY(newsletter_newsletter, newsletter_recipient_abstractgroup)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE lelesys_plugin_newsletter_domain_model_ne_11124_recipients_join (newsletter_newsletter VARCHAR(40) NOT NULL, newsletter_recipient_person VARCHAR(40) NOT NULL, INDEX IDX_15C731869390BC62 (newsletter_newsletter), INDEX IDX_15C73186BA709A88 (newsletter_recipient_person), PRIMARY KEY(newsletter_newsletter, newsletter_recipient_person)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE lelesys_plugin_newsletter_domain_model_recipient_abstractgroup (persistence_object_identifier VARCHAR(40) NOT NULL, title VARCHAR(255) NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE lelesys_plugin_newsletter_domain_model_recipient_group_party (persistence_object_identifier VARCHAR(40) NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE lelesys_plugin_newsletter_domain_model_recipient_group_st_59daa (persistence_object_identifier VARCHAR(40) NOT NULL, recipientlist VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE lelesys_plugin_newsletter_domain_model_recipient_person (persistence_object_identifier VARCHAR(40) NOT NULL, gender TINYINT(1) NOT NULL, acceptshtml TINYINT(1) DEFAULT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE lelesys_plugin_newsletter_domain_model_recipi_70879_groups_join (newsletter_recipient_person VARCHAR(40) NOT NULL, plugin_group_party VARCHAR(40) NOT NULL, INDEX IDX_C39CD389BA709A88 (newsletter_recipient_person), INDEX IDX_C39CD3891EC1F73D (plugin_group_party), PRIMARY KEY(newsletter_recipient_person, plugin_group_party)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE lelesys_plugin_newsletter_domain_model_re_70879_categories_join (newsletter_recipient_person VARCHAR(40) NOT NULL, newsletter_category VARCHAR(40) NOT NULL, INDEX IDX_CBAFBB99BA709A88 (newsletter_recipient_person), INDEX IDX_CBAFBB99DB4EDFAB (newsletter_category), PRIMARY KEY(newsletter_recipient_person, newsletter_category)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_emaillog ADD CONSTRAINT FK_F61B63FA7E8585C8 FOREIGN KEY (newsletter) REFERENCES lelesys_plugin_newsletter_domain_model_newsletter (persistence_object_identifier)");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_newsletter ADD CONSTRAINT FK_739A5FC647C4FAD6 FOREIGN KEY (attachments) REFERENCES typo3_flow_resource_resource (persistence_object_identifier)");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_ne_11124_categories_join ADD CONSTRAINT FK_B49207ED9390BC62 FOREIGN KEY (newsletter_newsletter) REFERENCES lelesys_plugin_newsletter_domain_model_newsletter (persistence_object_identifier)");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_ne_11124_categories_join ADD CONSTRAINT FK_B49207EDDB4EDFAB FOREIGN KEY (newsletter_category) REFERENCES lelesys_plugin_newsletter_domain_model_category (persistence_object_identifier)");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_mod_11124_recipientgroups_join ADD CONSTRAINT FK_28D983869390BC62 FOREIGN KEY (newsletter_newsletter) REFERENCES lelesys_plugin_newsletter_domain_model_newsletter (persistence_object_identifier)");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_mod_11124_recipientgroups_join ADD CONSTRAINT FK_28D983862A8E4F6B FOREIGN KEY (newsletter_recipient_abstractgroup) REFERENCES lelesys_plugin_newsletter_domain_model_recipient_abstractgroup (persistence_object_identifier)");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_ne_11124_recipients_join ADD CONSTRAINT FK_15C731869390BC62 FOREIGN KEY (newsletter_newsletter) REFERENCES lelesys_plugin_newsletter_domain_model_newsletter (persistence_object_identifier)");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_ne_11124_recipients_join ADD CONSTRAINT FK_15C73186BA709A88 FOREIGN KEY (newsletter_recipient_person) REFERENCES lelesys_plugin_newsletter_domain_model_recipient_person (persistence_object_identifier)");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_recipient_group_party ADD CONSTRAINT FK_9C45950147A46B0A FOREIGN KEY (persistence_object_identifier) REFERENCES lelesys_plugin_newsletter_domain_model_recipient_abstractgroup (persistence_object_identifier) ON DELETE CASCADE");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_recipient_group_st_59daa ADD CONSTRAINT FK_3529B8B47A46B0A FOREIGN KEY (persistence_object_identifier) REFERENCES lelesys_plugin_newsletter_domain_model_recipient_abstractgroup (persistence_object_identifier) ON DELETE CASCADE");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_recipient_person ADD CONSTRAINT FK_91D644BB47A46B0A FOREIGN KEY (persistence_object_identifier) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier) ON DELETE CASCADE");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_recipi_70879_groups_join ADD CONSTRAINT FK_C39CD389BA709A88 FOREIGN KEY (newsletter_recipient_person) REFERENCES lelesys_plugin_newsletter_domain_model_recipient_person (persistence_object_identifier)");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_recipi_70879_groups_join ADD CONSTRAINT FK_C39CD3891EC1F73D FOREIGN KEY (plugin_group_party) REFERENCES lelesys_plugin_newsletter_domain_model_recipient_group_party (persistence_object_identifier)");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_re_70879_categories_join ADD CONSTRAINT FK_CBAFBB99BA709A88 FOREIGN KEY (newsletter_recipient_person) REFERENCES lelesys_plugin_newsletter_domain_model_recipient_person (persistence_object_identifier)");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_re_70879_categories_join ADD CONSTRAINT FK_CBAFBB99DB4EDFAB FOREIGN KEY (newsletter_category) REFERENCES lelesys_plugin_newsletter_domain_model_category (persistence_object_identifier)");
	}

	/**
	 * Down Migrations
	 *
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		// this down() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_ne_11124_categories_join DROP FOREIGN KEY FK_B49207EDDB4EDFAB");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_re_70879_categories_join DROP FOREIGN KEY FK_CBAFBB99DB4EDFAB");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_emaillog DROP FOREIGN KEY FK_F61B63FA7E8585C8");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_ne_11124_categories_join DROP FOREIGN KEY FK_B49207ED9390BC62");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_mod_11124_recipientgroups_join DROP FOREIGN KEY FK_28D983869390BC62");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_ne_11124_recipients_join DROP FOREIGN KEY FK_15C731869390BC62");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_mod_11124_recipientgroups_join DROP FOREIGN KEY FK_28D983862A8E4F6B");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_recipient_group_party DROP FOREIGN KEY FK_9C45950147A46B0A");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_recipient_group_st_59daa DROP FOREIGN KEY FK_3529B8B47A46B0A");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_recipi_70879_groups_join DROP FOREIGN KEY FK_C39CD3891EC1F73D");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_ne_11124_recipients_join DROP FOREIGN KEY FK_15C73186BA709A88");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_recipi_70879_groups_join DROP FOREIGN KEY FK_C39CD389BA709A88");
		$this->addSql("ALTER TABLE lelesys_plugin_newsletter_domain_model_re_70879_categories_join DROP FOREIGN KEY FK_CBAFBB99BA709A88");
		$this->addSql("DROP TABLE lelesys_plugin_newsletter_domain_model_category");
		$this->addSql("DROP TABLE lelesys_plugin_newsletter_domain_model_emaillog");
		$this->addSql("DROP TABLE lelesys_plugin_newsletter_domain_model_newsletter");
		$this->addSql("DROP TABLE lelesys_plugin_newsletter_domain_model_ne_11124_categories_join");
		$this->addSql("DROP TABLE lelesys_plugin_newsletter_domain_mod_11124_recipientgroups_join");
		$this->addSql("DROP TABLE lelesys_plugin_newsletter_domain_model_ne_11124_recipients_join");
		$this->addSql("DROP TABLE lelesys_plugin_newsletter_domain_model_recipient_abstractgroup");
		$this->addSql("DROP TABLE lelesys_plugin_newsletter_domain_model_recipient_group_party");
		$this->addSql("DROP TABLE lelesys_plugin_newsletter_domain_model_recipient_group_st_59daa");
		$this->addSql("DROP TABLE lelesys_plugin_newsletter_domain_model_recipient_person");
		$this->addSql("DROP TABLE lelesys_plugin_newsletter_domain_model_recipi_70879_groups_join");
		$this->addSql("DROP TABLE lelesys_plugin_newsletter_domain_model_re_70879_categories_join");
	}

}
?>