<?php


namespace App\Http;
use Illuminate\Support\Facades\Response;

class PrettyResponse
{
    public function __construct()
    {

    }

    public static function raw($res){
        return Response::json($res, 200, [], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }

    public static function formerror($res){
        return Response::json([
            'success' => false,
            'is_valid' => 0,
            'data' => [],
            'errors' => $res,
        ], 403, [], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }

    public static function formsuccess($res){
        return Response::json([
            'success' => true,
            'is_valid' => 1,
            'data' => $res,
            'errors' => [],
        ], 200, [], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }

    public static function unauthorized()
    {
        return Response::json(['message' => 'Unauthorized'], 401);
    }

}
