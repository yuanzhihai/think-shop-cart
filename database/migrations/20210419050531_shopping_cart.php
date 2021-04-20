<?php

use think\migration\Migrator;

class ShoppingCart extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $table = $this->table('shopping_cart', ['engine' => 'InnoDB', 'id' => false,'primary_key' => ['key', '__raw_id']]);
        $table->addColumn('key', 'string');
        $table->addColumn('__raw_id', 'string');
        $table->addColumn('guard', 'string', ['null' => true]);
        $table->addColumn('user_id', 'integer', ['null' => true]);
        $table->addColumn('id', 'integer');
        $table->addColumn('name', 'string');
        $table->addColumn('qty', 'integer');
        $table->addColumn('price', 'decimal', ['precision' => 8, 'scale' => 2]);
        $table->addColumn('total', 'decimal', ['precision' => 8, 'scale' => 2]);
        $table->addColumn('__model', 'string', ['null' => true]);
        $table->addColumn('type', 'string', ['null' => true]);
        $table->addColumn('status', 'string', ['null' => true]);
        $table->addColumn('attributes', 'text', ['null' => true]);
        $table->addTimestamps();
        $table->create();
    }


    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('shopping_cart');
    }
}
