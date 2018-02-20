<?php 

class Model_Anyadir extends Orm\Model
{
    protected static $_table_name = 'listas_contienen_canciones';

    protected static $_primary_key = array('id_lista','id_cancion');
    protected static $_properties = array(
        'id_lista',
        'id_cancion' // both validation & typing observers will ignore the PK
       
        
    );

    protected static $_has_many = array(
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
    );
}