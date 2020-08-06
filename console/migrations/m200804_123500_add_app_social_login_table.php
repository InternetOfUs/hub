<?php

use yii\db\Migration;

/**
 * Class m200804_123500_add_app_social_login_table
 */
class m200804_123500_add_app_social_login_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `app_social_login` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `callback_url` text NOT NULL,
          `scope` text NOT NULL,
          `app_id` varchar(128) NOT NULL DEFAULT '',
          `created_at` int(11) NOT NULL,
          `updated_at` int(11) NOT NULL,
          `status` int(11) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `app_id` (`app_id`),
          CONSTRAINT `app_social_login_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `app` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200804_123500_add_app_social_login_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200804_123500_add_app_social_login_table cannot be reverted.\n";

        return false;
    }
    */
}
