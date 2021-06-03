<?php

use yii\db\Migration;

/**
 * Class m210603_130437_add_table_task_type_dev
 */
class m210603_130437_add_table_task_type_dev extends Migration{
    /**
     * {@inheritdoc}
     */
    public function safeUp(){
        $this->execute("CREATE TABLE `task_type_developer` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NOT NULL,
          `task_type_id` int(11) NOT NULL,
          `created_at` int(11) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `user_id` (`user_id`),
          KEY `task_type_id` (`task_type_id`),
          CONSTRAINT `task_type_developer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
          CONSTRAINT `task_type_developer_ibfk_2` FOREIGN KEY (`task_type_id`) REFERENCES `task_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(){
        echo "m210603_130437_add_table_task_type_dev cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210603_130437_add_table_task_type_dev cannot be reverted.\n";

        return false;
    }
    */
}
