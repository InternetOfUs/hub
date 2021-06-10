<?php

use yii\db\Migration;

/**
 * Class m210608_083000_add_task_type_id_field_to_app_table
 */
class m210608_083000_add_task_type_id_field_to_app_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `app` ADD `task_type_id` INT(11)  NULL  DEFAULT NULL  AFTER `image_url`;");
        $this->execute("ALTER TABLE `app` ADD FOREIGN KEY (`task_type_id`) REFERENCES `task_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");
        $this->execute("update app set status = 0 where task_type_id is NULL and status = 1;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210608_083000_add_task_type_id_field_to_app_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210608_083000_add_task_type_id_field_to_app_table cannot be reverted.\n";

        return false;
    }
    */
}
