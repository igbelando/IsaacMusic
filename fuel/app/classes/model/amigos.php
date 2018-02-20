<?php 

class Model_Amigos extends Orm\Model
{
    protected static $_table_name = 'amigos';

    protected static $_primary_key = array('id_usuario_seguidor','id_usuario_seguido');
    protected static $_properties = array(
        'id_usuario_seguidor',
        'id_usuario_seguido' // both validation & typing observers will ignore the PK
       
        
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