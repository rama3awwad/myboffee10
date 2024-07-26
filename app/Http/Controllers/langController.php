<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use  App\Http\Controllers\BaseController;

use Illuminate\Http\Request;

class LangController extends BaseController
{

    public function __invoke()
    {
        $user = auth()->user();

        $lang = 'en';
        if ($user->lang == 'en') {
            $lang = 'ar';
        }
        $user-> update([
            'lang' => $lang
        ]);
        return $this->sendResponse(null,'');
    }
}
