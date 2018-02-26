<?php 

class Model_Add extends Orm\Model
{
    protected static $_table_name = 'lists_contain_songs';

    protected static $_primary_key = array('id_list','id_song');
    protected static $_properties = array(
        'id_list',
        'id_song' // both validation & typing observers will ignore the PK
       
        
    );

    /*protected static $_has_many = array(
        'canciones' => array(
            'key_from' => 'id_cancion',
            'model_to' => 'Model_Canciones',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => false,
        ),
        'users' => array(
            'key_from' => 'id_lista',
            'model_to' => 'Model_Listas',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => false,
        )
    );*/
}