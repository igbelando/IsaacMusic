<?php 

use \Firebase\JWT\JWT;

class Controller_Add extends Controller_Rest
{
    private $key = "jnf4lcf4hg3ghg53vgvkx24vxg";
   
                                       //Añadir canciones a listas.
    public function post_add()
    {
        
        try {

            try
            {
                $headers = apache_request_headers();
                $token = $headers['Authorization'];
                $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));

                $users = Model_Users::find('all', array(
                    'where' => array(
                        array('id', $dataJwtUser->id),
                        array('username', $dataJwtUser->username),
                        array('password', $dataJwtUser->password)
               
                    )
                 ));

            }    
            catch (Exception $e)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                ));
                return $json;
               
            }
            foreach ($users as $key => $user)
            {
                $rol = $user->id_rol;
            }
            
            if ($rol == 1)
            {

                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Solo los usuarios pueden añadir canciones a listas',
                    'data' => []
                ));
                return $json;

            }
            else
            {   

                if (  ! isset($_POST['id_list'])|| ! isset($_POST['id_song'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

                $input = $_POST;
                
                $listsSongs = new Model_Add();
                $listsSongs->id_list = $input['id_list'];
                $listsSongs->id_song = $input['id_song'];

                if ($listsSongs->id_list == "" || $listsSongs->id_song == ""   )
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Se necesita introducir todos los parametros',
                        'data' => []
                    ));
                }

                else
                {
                    $lists = Model_Lists::find('all', array(
                        'where' => array(
                            array('id', $input['id_list']),
                           
                        )
                     ));

                    if(empty($lists))
                    {
                         $json = $this->response(array(
                            'code' => 400,
                            'message' => 'lista no encontrada',
                            'data' => []
                        ));

                    }
                    foreach ($lists as $key => $list) {
                       
                        # code...
                    }

                    if ($list->editable == 2 || $list->id_usero != $dataJwtUser->id)
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'lista no editable',
                            'data' => []
                        ));

                    }
                    else
                    {

                        $listsSongs->save();

                        $json = $this->response(array(
                            'code' => 200,
                            'message' => 'Cancion añadida a '. $list->titulo. ' correctamente',
                            'data' => $listsSongs
                        ));

                        return $json;
                    }
                }
            }            
        } 
        catch (Exception $e) 
        {
           
                $json = $this->response(array(
                    'code' => 500,
               // 'message' => $e->getCode()
                    'message' => $e->getMessage(),
                    'data' => []
                ));

                return $json;

        }        
    }

    public function get_songs()
    {
        try
            {
                $headers = apache_request_headers();
                $token = $headers['Authorization'];
                $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));

                $users = Model_Users::find('all', array(
                    'where' => array(
                        array('id', $dataJwtUser->id),
                        array('username', $dataJwtUser->username),
                        array('password', $dataJwtUser->password)
               
                    )
                 ));

            }    
        catch (Exception $e)
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ));
            return $json;
               
        }
        $input = $_GET;

        $añadir = Model_Add::find('all', array(
                        'where' => array(
                            array('id_list', $input['id_list']),
                        )
                     ));

        if (empty($añadir))
        {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'lista no encontada',
                'data' => []
            ));
            return $json;

        }
        else
        {
            $lists = Model_Lists::find('all', array(
                        'where' => array(
                            array('id', $input['id_list']),
                            array('id_user', $dataJwtUser->id),
                   
                        )
                     ));

            if (empty($lists))
            {
                    $json = $this->response(array(
                    'code' => 500,
                    'message' => 'la lista no pertenece al usuario',
                    'data' => []
                ));
                return $json;

            }
            else
            {

                foreach ($añadir as $key => $añadido) {

                    $songs=Model_Songs::query()->where('id',$añadido->id_song)->get();
                    foreach($songs as $ket => $song) {

                    }

                    $showTitle = $song->title;
                    
                    # code...
                }

                $exit = array_slice($showTitle, $tens*10,($tens+1)*10);

                $lists = Model_Lists::query()->where('id',$añadido-id_list)->get();
                    foreach ($lists as $key => list){

                    }

                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Conjunto de canciones de la lista ' .$list->title,
                    'data' => $añadido
                ));

                return $json;
            }
        }
    }
   

    public function post_delete()
    {
        try
            {
                $headers = apache_request_headers();
                $token = $headers['Authorization'];
                $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));

                $users = Model_Usuarios::find('all', array(
                    'where' => array(
                        array('id', $dataJwtUser->id),
                        array('username', $dataJwtUser->username),
                        array('password', $dataJwtUser->password)
               
                    )
                 ));

            }    
        catch (Exception $e)
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ));
            return $json;
               
        }
        $input = $_POST;

        if (  ! isset($_POST['id_song'])|| ! isset($_POST['id_list']) ) 
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Error en las credenciales, prueba otra vez',
                            'data' => []
                        ));

                        return $json;
                    }

        $lists = Model_Lists::find('all', array(
                        'where' => array(
                            array('id', $input['id_list']),

                        )
                     ));
        if (empty($lists))
        {
            $json = $this->response(array(
                        'code' => 400,
                        'message' => 'lista no encontrada ',
                        'data' => []
                    ));
        } 
        else
        {

            foreach ($lists as $key => $list) {
                # code...
            }
            if ($list->editable == 2 || $list->id_user != $dataJwtUser->id)
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'lista no editable',
                        'data' => []
                    ));

                }
            else
            {
                 
                $añadir = Model_Add::find('all', array(
                                'where' => array(
                                    array('id_list', $list->id),
                                    array('id_song', $input['id_song']),
                                    
                                )
                             ));
                if(! empty($añadir))
                {

                    foreach ($añadir as $key => $añade) {
                        
                        # code...
                    }

                    Model_Add::find($añade);
                    try
                    {
                        $songs = Model_Song::find('all', array(
                                'where' => array(
                                    array('id', $input['id_song']),
                                    
                                )
                             ));
                        foreach ($songs as $key => $song) 
                        {
                            # code...
                            $dataSong = $song->title;
                        }

                        $dataList = $list->title;

                        $añade->delete(); 
                    }
                    catch (Exception $e)
                    {

                    }

                    $json = $this->response(array(
                        'code' => 200,
                        'message' => $dataSong. ' borrada de '. $dataList,
                        'data' => []
                    ));

                    return $json;
                }
                else
                {
                    $json = $this->response(array(
                                'code' => 400,
                                'message' => 'Cancion no encontrada',
                                'data' => []
                            ));

                            return $json;
                }
            } 
        }   
    }

    public function get_mostViewed()
    {
         $songs = Model_Canciones::query()->order_by('views', 'desc')->limit(10)->get();
         
         foreach ($songs as $key => $song) {
            $songFormated[] = $song;
             # code...
         }

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Esta es la lista de canciones mas escuchadas',
            'data' => $songFormated
        ));

        return $json;

    } 

    public function get_songNoListened()
    {
        try
            {
                $headers = apache_request_headers();
                $token = $headers['Authorization'];
                $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));

                $users = Model_Users::find('all', array(
                    'where' => array(
                        array('id', $dataJwtUser->id),
                        array('username', $dataJwtUser->username),
                        array('password', $dataJwtUser->password)
               
                    )
                 ));

            }    
        catch (Exception $e)
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ));
            return $json;
               
        }
        $input = $_GET;

        $añadir = Model_Add::find('all', array(
                        'where' => array(
                            array('id_list', $input['id_list']),

                        )
                     ));
        if (empty($añadir))
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'lista no encontada',
                'data' => []
            ));
            return $json;

        }
        else
        {
            $lists = Model_Lists::find('all', array(
                        'where' => array(
                            array('id', $input['id_list']),
                            array('id_user', $dataJwtUser->id),

                        )
                     ));

            if (empty($lists))
            {
                $json = $this->response(array(
                'code' => 500,
                'message' => 'la lista no pertenece al usuario',
                'data' => []
            ));
            return $json;

            }
            else
            {

                foreach ($añadir as $key => $añade) {
                    $añadido[] = $añade;
                    # code...
                }

                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Conjunto de canciones',
                    'data' => $añadido
                ));

                return $json;
            }
        }
    }
}