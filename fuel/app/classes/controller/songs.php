<?php 

use \Firebase\JWT\JWT;

class Controller_Songs extends Controller_Rest
{
    private $key = "jnf4lcf4hg3ghg53vgvkx24vxg";
   
                                    //Crear cancion
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
                        'message' => 'Se necesita introducir todos los parametros',
                        'data' => []
                    ));

                    return $json;
                }

                $input = $_POST;
                
                $songs = new Model_Songs();
                $songs->artist= $input['artist'];
                $songs->url= $input['url'];
                $songs->title= $input['title'];
                $songs->views= 0;
               
                if ($this->URLCreated($input['url']))
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'No puede haber dos URL identicas, esa cancion ya existe',
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

    public function URLCreated($url)
    
    {
        $url = Model_Songs::find('all', array(
            'where' => array(
                array('url', $url)
            )
        ));

        if(count($url) < 1) {
            return false;
        }
        else
        {
            return true;
        }
    }                              
}    