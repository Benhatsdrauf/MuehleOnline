<?php
namespace App\Logic;

use Illuminate\Http\Exceptions\HttpResponseException;

class Error
{
    public static function throw($messages, int $code = 400)
    {
        throw new HttpResponseException(response()->json([
            "errors" => $messages,
        ], $code));
    }
}
