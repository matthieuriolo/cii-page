<?php

namespace app\modules\page\migrations;

use cii\db\Migration;

class Index extends Migration {
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /* table Page_SiteContent */
        $this->createTable('{{%Page_SiteContent}}', [
            'id' => $this->primaryKey()->unsigned(),
            'content_id' => $this->integer()->notNull()->unsigned(),
            'content' => $this->text(),
            'language_id' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreContent_CoreLanguage1',
            'Page_SiteContent',
            'language_id',
            'Cii_Language',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Page_Site_Core_Content1',
            'Page_SiteContent',
            'content_id',
            'Cii_Content',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_CoreContent_CoreLanguage1_idx',
            'Page_SiteContent',
            'language_id'
        );

        $this->createIndex(
            'fk_Page_Site_Core_Content1_idx',
            'Page_SiteContent',
            'content_id'
        );

        }

    public function down() {
        $this->dropTable('{{%Page_SiteContent}}');
    }
}