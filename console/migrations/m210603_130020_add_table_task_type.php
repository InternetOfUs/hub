<?php

use yii\db\Migration;

/**
 * Class m210603_130020_add_table_task_type
 */
class m210603_130020_add_table_task_type extends Migration{
    /**
     * {@inheritdoc}
     */
    public function safeUp(){
        $this->execute("CREATE TABLE `task_type` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `public` tinyint(1) NOT NULL DEFAULT '0',
          `creator_id` int(11) NOT NULL,
          `task_manager_id` varchar(256) NOT NULL DEFAULT '',
          `created_at` int(11) NOT NULL,
          `updated_at` int(11) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `creator_id` (`creator_id`),
          CONSTRAINT `task_type_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(){
        echo "m210603_130020_add_table_task_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210603_130020_add_table_task_type cannot be reverted.\n";

        return false;
    }
    */
}
