<?php

namespace App\Http\Controllers\Gendre;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\Gendre;
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

    public function show(Gendre $gendre)
    {
        return $this->sendResponse($gendre, 'Gendre retrieved successfully.');
    }

    public function update(Request $request, Gendre $gendre)
    {
        $gendre->update($request->all());
        return $this->sendResponse($gendre, 'Gendre updated successfully.');
    }

    public function destroy(Gendre $gendre)
    {
        $gendre->delete();
        return $this->sendResponse(null, 'Gendre deleted successfully.');
    }

}
