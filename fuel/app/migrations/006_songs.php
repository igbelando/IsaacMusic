<?php 

namespace Fuel\Migrations;

class songs
{

    function up()
    {
        \DBUtil::create_table('songs', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'title' => array('type' => 'varchar', 'constraint' => 100),
            'url' => array('type' => 'varchar', 'constraint' => 200),
            'artist' => array('type' => 'varchar', 'constraint' => 100),
            'views' => array('type' => 'int', 'constraint' => 100),
        ), array('id'));
    }

    function down()
    {
       \DBUtil::drop_table('songs');
    }
}