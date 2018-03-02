<?php 

use \Firebase\JWT\JWT;

class Controller_Lists extends Controller_Rest
{
    private $key = "jnf4lcf4hg3ghg53vgvkx24vxg";
   
                                    //Crear listas
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
                    'message' => 'Solo los usuario pueden crear listas modificables',
                    'data' => []
                ));
                return $json;

            }
            else
            {    

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
                
                    $lists = new Model_Lists();
                    $lists->editable= 1;
                    $lists->id_user = $dataJwtUser->id;
                    $lists->title= $input['title'];
               
                    if ($lists->id_user == "" || $lists->title == ""   )
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Se necesita introducir todos los parametros',
                            'data' => []
                        ));
                    }
                    else
                    {


                        $lists->save();
                        
                        $json = $this->response(array(
                            'code' => 200,
                            'message' => 'Cancion creada correctamente',
                            'data' => $lists
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

    public function get_lists()
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
        $tens = $input['tens_list']-1;
        if($input['tens_list'] == '')
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'Introduce una decena',
                'data' => []
            ));
            return $json; 
        }
        if($input['tens_list'] <= 0)
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'La decena minima es 1',
                'data' => []
            ));
            return $json; 
        }



       $lists = Model_Lists::query()->where('id_user', $dataJwtUser->id)->offset( $tens * 10)->limit(10)->get();

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Conjunto de listas',
            'data' => $lists
        ));

        return $json;

    }
    public function post_modifyList()
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
        if (  ! isset($_POST['title']) || ! isset($_POST['id'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

        $input = $_POST;



        $lists = Model_Lists::find('all', array(
                        'where' => array(
                            array('id_user', $dataJwtUser->id),
                            array('id', $input['id']),
                            
                   
                        )
                     ));
      
        foreach ($lists as $key => $list) 
        {
            $list->title = $input['title'];
            $list->save();
        }

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Conjunto de listas',
            'data' => $lists
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
        if (  ! isset($_POST['id'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

        $input = $_POST;

        $lists = Model_Lists::find('all', array(
                        'where' => array(
                            array('id_user', $dataJwtUser->id),
                            array('id', $input['id']),
                            
                   
                        )
                     ));
        if (! empty($lists))
        {
            foreach ($lists as $key => $list) {
            $borrar = $list;
            }

            $list->delete();
          

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
                'message' => 'Id no encontrado',
                'data' => []
            ));

            return $json;

        } 
    }
}    