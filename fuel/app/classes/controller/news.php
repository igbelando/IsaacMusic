<?php 

use \Firebase\JWT\JWT;

class Controller_Noticias extends Controller_Rest
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

                if (  ! isset($_POST['title']) || ! isset($_POST['description'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

                $input = $_POST;
                
                $news = new Model_News();
                $news->description = $input['description'];
                $news->id_user = $dataJwtUser->id;
                $news->title= $input['title'];
           
                if ($news->description == "" || $news->title == ""   )
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Se necesita introducir todos los parametros',
                        'data' => []
                    ));
                }
                else
                {

                    $news->save();
                    
                    $json = $this->response(array(
                        'code' => 200,
                        'message' => 'Noticia creada correctamente',
                        'data' => $news
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

    public function get_myNews()
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
        $tens = $input['tens_news']-1;
        if($input['tens_news'] == '')
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'Introduce una decena',
                'data' => []
            ));
            return $json; 
        }
        if($input['tens_news'] <= 0)
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'La decena minima es 1',
                'data' => []
            ));
            return $json; 
        }

       $news = Model_News::query()->where('id_user', $dataJwtUser->id)->offset( $tens * 10)->limit(10)->get();


        $json = $this->response(array(
            'code' => 200,
            'message' => 'Conjunto de noticias',
            'data' => $news
        ));

        return $json;

        //return $this->response(Arr::reindex($users));

    }
    public function get_news()
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
        $tens = $input['tens_news']-1;
        if($input['tens_news'] == '')
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'Introduce una decena',
                'data' => []
            ));
            return $json; 
        }
        if($input['tens_news'] <= 0)
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'La decena minima es 1',
                'data' => []
            ));
            return $json; 
        }

        $news = Model_News::query()->offset( $tens * 10)->limit(10)->get();

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Conjunto de noticias',
            'data' => $news
        ));

        return $json;

    }
    public function post_modify()
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
        if (  ! isset($_POST['last_title']) || ! isset($_POST['title']) || ! isset($_POST['description'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

        $input = $_POST;

        $news = Model_News::find('all', array(
                        'where' => array(
                            array('id_usuario', $dataJwtUser->id),
                            array('title', $input['last_title']),
                            
                        )
                     ));

        if(empty($news))
        {
            $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Noticia no encontrada',
                        'data' => []
                    ));

                    return $json;
        }

        if($input['title'] == '' && $input['description'] == '')
        {
            $json = $this->response(array(
                        'code' => 400,
                        'message' => 'No puedes dejar los dos campos vacios',
                        'data' => []
                    ));

                    return $json;

        }
        elseif ($input['title'] == '')
        {
            foreach ($news as $key => $new) 
            {
               
                $new->description= $input['description'];
                $new->save();
            }

        }
        elseif ( $input['description'] == '')
        {
            foreach ($news as $key => $new) 
            {
                $new->title= $input['title'];
               
                $new->save();
            }

        }
        else{
            foreach ($noticias as $key => $new) 
            {
                $new->title = $input['title'];
                $new->description = $input['description'];
                $new->save();
            }

        }
      
        $json = $this->response(array(
            'code' => 200,
            'message' => 'Noticia modificada',
            'data' => $news
        ));

        return $json;

    }

    public function post_delete()
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
        if (  ! isset($_POST['title'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

        $input = $_POST;

        $news = Model_News::find('all', array(
                        'where' => array(
                            array('id_user', $dataJwtUser->id),
                            array('title', $input['title']),
                            
                   
                        )
                     ));
        if (! empty($news))
        {
            foreach ($news as $key => $new) {
            $borrar = $new;
            }

            $new->delete();

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

     public function get_closeNews()
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

         $tens = $input['tens_news']-1;
        if($input['tens_news'] == '')
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'Introduce una decena',
                'data' => []
            ));
            return $json; 
        }
        if($input['tens_news'] <= 0)
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'La decena minima es 1',
                'data' => []
            ));
            return $json; 
        }

        $news = Model_News::query()->get();

        foreach ($news as $key => $new) {

                $users = Model_Users::query()->where('id',$new->id_user)->get();
           foreach ($users as $key => $user) 
           {

               # code...
                if(abs($dataJwtUser->coordinate_X - $user->coordinate_X)<= 30.0 && abs($dataJwtUser->coordinate_Y - $user->coordinate_Y)<= 30.0 && $dataJwtUser->id != $user->id)      

                {

                    $cercanos[] = $new;
                }
           }
            # code...
        }
        if(empty($cercanos))
        {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'No hay noticias encontradas',
                'data' => []
            ));
            return $json; 

        }

        $salida = array_slice($cercanos, $tens*10,($tens+1)*10);

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Esta es la lista de noticias de usuarios cercanos',
            'data' => $salida

        ));

        return $json;

    }
}    