<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LangController extends Controller
{

    public function __invoke()
    {
        $lang = 'en';
        if (auth()->user()->lang == 'en') {
            $lang = 'ar';
        }
        auth()->user()->make([
            'lang' => $lang
        ]);
        return $this->success(null);
    }
}
