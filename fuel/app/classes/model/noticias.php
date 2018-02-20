<?php 

class Model_Noticias extends Orm\Model
{
    protected static $_table_name = 'noticias';

    protected static $_primary_key = array('id');
    protected static $_properties = array(
        'id', // both validation & typing observers will ignore the PK
        'titulo' => array(
            'data_type' => 'varchar'   
        ),
        'descripcion' => array(
            'data_type' => 'varchar'   
        ),
        
        'id_usuario' => array(
            'data_type' => 'int'   
        )
        
    );
}