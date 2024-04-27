<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result , $message): \Illuminate\Http\JsonResponse
    {
     $response = [
         'success' => true,
         'data' => $result,
         'message' => $message,
     ];
       return response()->json($response ,200);
    }

    public function sendError($error , $errorMessage=[], $code=404): \Illuminate\Http\JsonResponse
    {
     $response = [
         'success' => false,
         'data' => $error,
     ];
       return response()->json($response ,$code);
    }
}
