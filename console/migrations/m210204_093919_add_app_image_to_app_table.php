<?php

use yii\db\Migration;

/**
 * Class m210204_093919_add_app_image_to_app_table
 */
class m210204_093919_add_app_image_to_app_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `app` ADD `image_url` TEXT  NULL  AFTER `owner_id`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210204_093919_add_app_image_to_app_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210204_093919_add_app_image_to_app_table cannot be reverted.\n";

        return false;
    }
    */
}
