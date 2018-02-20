<?php 

use \Firebase\JWT\JWT;

class Controller_Noticias extends Controller_Rest
{
    private $key = "juf3dhu3hufdchv3xui3ucxj";
   

                                    //Crear usuario
    public function post_create()
    {
        try {

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
            foreach ($users as $key => $user)
            {
                $rol = $user->id_rol;
            }
            
            if ($rol == 1)
            {

                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Solo los usuario pueden crear noticias',
                    'data' => []
                ));
                return $json;

            }
            else
            {    


                if (  ! isset($_POST['titulo']) || ! isset($_POST['descripcion'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

                /*if (strlen($_POST['password']) < 6 || strlen($_POST['password']) >12){
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Contraseña: entre 6 y 12 caracteres',
                        'data' => []
                    ));

                    return $json;

                }*/


              

                $input = $_POST;
                
                    $noticias = new Model_Noticias();
                    $noticias->descripcion = $input['descripcion'];
                    $noticias->id_usuario = $dataJwtUser->id;
                    $noticias->titulo= $input['titulo'];
                    
                   


                    
                
                
               
                    if ($noticias->descripcion == "" || $noticias->titulo == ""   )
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Se necesita introducir todos los parametros',
                            'data' => []
                        ));
                    }
                    else
                    {


                        $noticias->save();
                        
                        

                        $json = $this->response(array(
                            'code' => 200,
                            'message' => 'Noticia creada correctamente',
                            'data' => $noticias
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

    public function get_misNoticias()
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



        $noticias = Model_Noticias::find('all', array(
                        'where' => array(
                            array('id_usuario', $dataJwtUser->id),
                            
                   
                        )
                     ));

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Conjunto de noticias',
            'data' => $noticias
        ));

        return $json;

        //return $this->response(Arr::reindex($users));

    }
    public function get_noticias()
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



        $noticias = Model_Noticias::find('all');

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Conjunto de noticias',
            'data' => $noticias
        ));

        return $json;

        //return $this->response(Arr::reindex($users));

    }
    public function post_modify()
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
        if (  ! isset($_POST['titulo_antiguo']) || ! isset($_POST['titulo']) || ! isset($_POST['descripcion'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

        $input = $_POST;



        $noticias = Model_Noticias::find('all', array(
                        'where' => array(
                            array('id_usuario', $dataJwtUser->id),
                            array('titulo', $input['titulo_antiguo']),
                            
                   
                        )
                     ));

        if(empty($noticias))
        {
            $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Noticia no encontrada',
                        'data' => []
                    ));

                    return $json;
        }

        if($input['titulo'] == '' && $input['descripcion'] == '')
        {
            $json = $this->response(array(
                        'code' => 400,
                        'message' => 'No puedes dejar los dos campos vacios',
                        'data' => []
                    ));

                    return $json;


        }
        elseif ($input['titulo'] == '')
        {
            foreach ($noticias as $key => $noticia) 
            {
               
                $noticia->descripcion= $input['descripcion'];
                $noticia->save();
            }

        }
        elseif ( $input['descripcion'] == '')
        {
            foreach ($noticias as $key => $noticia) 
            {
                $noticia->titulo= $input['titulo'];
               
                $noticia->save();
            }

        }
        else{
            foreach ($noticias as $key => $noticia) 
            {
                $noticia->titulo = $input['titulo'];
                $noticia->descripcion = $input['descripcion'];
                $noticia->save();
            }

        }
      
        

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Noticia modificada',
            'data' => $noticias
        ));

        return $json;

        //return $this->response(Arr::reindex($users));

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
        if (  ! isset($_POST['titulo'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

        $input = $_POST;




        $noticias = Model_Noticias::find('all', array(
                        'where' => array(
                            array('id_usuario', $dataJwtUser->id),
                            array('titulo', $input['titulo']),
                            
                   
                        )
                     ));
        if (! empty($noticias))
        {
            foreach ($noticias as $key => $noticia) {
            $borrar = $noticia;
            }

            $noticia->delete();
          

            $json = $this->response(array(
                'code' => 200,
                'message' => 'Lista borrada',
                'data' => []
            ));

            return $json;


        }
        else
        {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'Titulo no encontrado',
                'data' => []
            ));

            return $json;


        }
        
    }

    public function get_noticiasCercania()
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


        $noticias = Model_Noticias::find('all');
        foreach ($noticias as $key => $noticia) {
            # code...
        }
        

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Conjunto de noticias',
            'data' => $noticias
        ));

        return $json;

        //return $this->response(Arr::reindex($users));

    }
    

}    
