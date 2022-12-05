<?php
namespace App\Logic;

use Illuminate\Http\Exceptions\HttpResponseException;
use Ramsey\Uuid\Type\Integer;

class Error
{
    public static function throw($messages, int $code = 422)
    {
        throw new HttpResponseException(response()->json([
            "errors" => $messages,
        ], $code));
    }
}
