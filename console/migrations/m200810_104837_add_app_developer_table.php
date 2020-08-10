<?php

use yii\db\Migration;

/**
 * Class m200810_104837_add_app_developer_table
 */
class m200810_104837_add_app_developer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("CREATE TABLE `app_developer` (
          `app_id` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
          `user_id` int(11) NOT NULL,
          `created_at` int(11) NOT NULL,
          PRIMARY KEY (`app_id`,`user_id`),
          KEY `user_id` (`user_id`),
          CONSTRAINT `app_developer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
          CONSTRAINT `app_developer_ibfk_2` FOREIGN KEY (`app_id`) REFERENCES `app` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200810_104837_add_app_developer_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200810_104837_add_app_developer_table cannot be reverted.\n";

        return false;
    }
    */
}
