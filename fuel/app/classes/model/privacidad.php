<?php 

class Model_Privacidad extends Orm\Model
{
    protected static $_table_name = 'privacidad';

    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', // both validation & typing observers will ignore the PK
        'perfil' => array(
            'data_type' => 'int'   
        ),
        'amigos' => array(
            'data_type' => 'int'   
        ),
        'listas' => array(
            'data_type' => 'int'   
        ),
        'notificaciones' => array(
            'data_type' => 'int'   
        ),
        'ubicacion' => array(
            'data_type' => 'int'
        ),
        'id_usuario' => array(
            'data_type' => 'int'   
        )
    );
}