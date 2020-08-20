<?php

use yii\db\Migration;

/**
 * Class m200820_135509_delete_platform_app_platform_telegram
 */
class m200820_135509_delete_app_platform_telegram extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("DROP TABLE `app_platform_telegram`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m200820_135509_delete_app_platform_telegram cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200820_135509_delete_platform_app_platform_telegram cannot be reverted.\n";

        return false;
    }
    */
}
