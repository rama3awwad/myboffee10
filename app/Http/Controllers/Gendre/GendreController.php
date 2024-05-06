<?php

namespace App\Http\Controllers\Gendre;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\Gendre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GendreController extends BaseController
{
    public function index()
    {
        $gendres = Gendre::all();
        return $this->sendResponse($gendres, 'Gendres retrieved successfully.');
    }

    public function store(Request $request)
    {
        $gendre = Gendre::create($request->all());
        return $this->sendResponse($gendre, 'Gendre created successfully.');
    }

    public function show($id): JsonResponse
    {
        $gendre = Gendre::find($id);

        if (is_null($gendre)) {
            return $this->sendError('Gendre not found');
        }

        // Assuming you have a method to send a response, similar to your sendResponse method
        return $this->sendResponse($gendre, 'Gendre retrieved successfully');
    }



    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $gendre = Gendre::find($id);
        if (is_null($gendre)) {
            return $this->sendError('Gendre not found');
        }
        $gendre->delete();
        return $this->sendResponse(null, 'Gendre deleted successfully');
    }
}
