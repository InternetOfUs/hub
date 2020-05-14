<?php

use yii\db\Migration;

/**
 * Class m200414_155206_addAppTable
 */
class m200414_155206_addAppTable extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("CREATE TABLE `app` (
          `id` varchar(128) NOT NULL DEFAULT '',
          `status` int(11) NOT NULL,
          `name` varchar(512) NOT NULL DEFAULT '',
          `description` text,
          `token` varchar(512) NOT NULL DEFAULT '',
          `message_callback_url` text,
          `metadata` text NOT NULL,
          `created_at` int(11) NOT NULL,
          `updated_at` int(11) NOT NULL,
          `owner_id` int(11) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `owner_id` (`owner_id`),
          CONSTRAINT `app_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
    );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200414_155206_addAppTable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200414_155206_addAppTable cannot be reverted.\n";

        return false;
    }
    */
}
