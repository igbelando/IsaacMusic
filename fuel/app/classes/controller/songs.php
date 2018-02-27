<?php 

use \Firebase\JWT\JWT;

class Controller_Song extends Controller_Rest
{
    private $key = "jnf4lcf4hg3ghg53vgvkx24vxg";
   
                                    //Crear usuario
    public function post_create()
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
            
            if ($rol != 1)
            {

                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Acceso denegado',
                    'data' => []
                ));
                return $json;

            }
            else
            {    

                if ( ! isset($_POST['artist']) || ! isset($_POST['url']) || ! isset($_POST['title'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

                $input = $_POST;
                
                $songs = new Model_Songs();
                $songs->artist= $input['artist'];
                $songs->url= $input['url'];
                $songs->title= $input['title'];
                $songs->reproducciones= 0;
               
                if ($songs->artist == "" || $songs->title == "" || $songs->url == ""  )
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Se necesita introducir todos los parametros',
                        'data' => []
                    ));
                }
                else
                {

                    $songs->save();
                    
                    $json = $this->response(array(
                        'code' => 200,
                        'message' => 'Cancion creada correctamente',
                        'data' => $songs
                    ));

                    return $json;
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

    
    public function get_reproduceSong()
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
        foreach ($users as $key => $user)
        {
            $rol = $user->id_rol;
        }
            
        if ($rol != 1)
        {

                $input = $_GET;
                $songs = Model_Song::find('all', array(
                            'where' => array(
                                array('id', $input['id']),

                            )
                         ));
                foreach ($songs as $key => $song) {
                    $song->reproducciones += 1;
                    $song->save();
                    # code...
                }

                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Cancion escuchada',
                    'data' => $songs
                ));

                return $json;
        }
        else
        {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Solo pueden escuchar los usuarios',
                    'data' => []
                ));
        }
    }
}    