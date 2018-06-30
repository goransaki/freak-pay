<?php

use yii\db\Migration;

/**
 * Class m180630_060134_add_transaction_transaction_items_articles_and_store_tables
 */
class m180630_060134_add_transaction_transaction_items_articles_and_store_tables extends Migration
{

    public function up()
    {
        $this->createTable('store', [
            'id' => $this->primaryKey(),
            'identifier' => $this->string()->notNull(),
            'name' => $this->string()->notNull()
        ]);

        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull()
        ]);

        $this->createTable('transaction', [
            'id' => $this->primaryKey(),
            'reference' => $this->string()->notNull(),
            'store_id' => $this->integer(),
            'user_id' => $this->integer(),
            'total_price' => $this->float(),
            'used_points' => $this->integer()->notNull()->defaultValue(0),
            'got_points' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey('tr_user_id_fk', 'transaction', 'user_id', 'user', 'id');
        $this->addForeignKey('tr_store_id_fk', 'transaction', 'store_id', 'store', 'id');

        $this->createTable('transaction_item', [
            'id' => $this->primaryKey(),
            'transaction_id' => $this->integer(),
            'store_id' => $this->integer(),
            'user_id' => $this->integer(),
            'article_id' => $this->integer(),
            'article_price' => $this->float(),
            'created_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey('tri_user_id_fk', 'transaction_item', 'user_id', 'user', 'id');
        $this->addForeignKey('tri_store_id_fk', 'transaction_item', 'store_id', 'store', 'id');
        $this->addForeignKey('tri_transaction_id_fk', 'transaction_item', 'transaction_id', 'transaction', 'id');
        $this->addForeignKey('tri_article_id_fk', 'transaction_item', 'article_id', 'article', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('tri_user_id_fk', 'transaction_item');
        $this->dropForeignKey('tri_store_id_fk', 'transaction_item');
        $this->addForeignKey('tri_transaction_id_fk', 'transaction_item', 'transaction_id', 'transaction', 'id');
        $this->addForeignKey('tri_article_id_fk', 'transaction_item', 'article_id', 'article', 'id');

        $this->createTable('store', [
            'id' => $this->primaryKey(),
            'identifier' => $this->string()->notNull(),
            'name' => $this->string()->notNull()
        ]);

        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull()
        ]);

        $this->createTable('transaction', [
            'id' => $this->primaryKey(),
            'reference' => $this->string()->notNull(),
            'store_id' => $this->integer(),
            'user_id' => $this->integer(),
            'total_price' => $this->float(),
            'used_points' => $this->integer()->notNull()->defaultValue(0),
            'got_points' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey('tr_user_id_fk', 'transaction', 'user_id', 'user', 'id');
        $this->addForeignKey('tr_store_id_fk', 'transaction', 'store_id', 'store', 'id');

        $this->createTable('transaction_item', [
            'id' => $this->primaryKey(),
            'transaction_id' => $this->integer(),
            'store_id' => $this->integer(),
            'user_id' => $this->integer(),
            'article_id' => $this->integer(),
            'article_price' => $this->float(),
            'created_at' => $this->dateTime()->notNull(),
        ]);
    }

}
