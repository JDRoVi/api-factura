<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use App\Models\User;
use PhpParser\Node\Stmt\TryCatch;

class JwtAuth{
    private $key;
    function __construct()
    {
        $this->key='1w8dawdawds86ef4fsefs8efes84';
    }
    public function signin($userName,$password){//se genera el token
        $user = User::where([
            'nombreUsuario' => $userName,
            'password' => hash('sha256',$password)
        ])->first(); 
        if(is_object($user)){
            $token = array(
                'sub' =>$user->id,
                'nombreUsuario'=> $user->nombreUsuario,
                'nivelUsuario'=>$user->nivelUsuario,
                'iat'=>time(),
                'exp'=>time()+600
            );
            $data=JWT::encode($token,$this->key,'HS256');
        }else{
            $data=array(
                'status' => 'error',
                'code' => 401,
                'message'=>'datos de autenticacion erroneos'
            );
        }
        return $data;
    }

    public function verify($token,$getIdentity=false)
    {
        $auth=false;
        try{
            $decoded=JWT::decode($token,$this->key,['HS256']);
            
        }catch(\UnexpectedValueException $ex){
            $auth=false;
        
        }catch(\DomainException $ex){
            $auth=false;
        }
        if(!empty($decoded)&&is_object($decoded)&&isset($decoded->sub)){
            $auth =true;
        }
        if($getIdentity){
            
            return $decoded;
        }
        return $auth;        
    }
}