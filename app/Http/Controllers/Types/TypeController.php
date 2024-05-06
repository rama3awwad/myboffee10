<?php

namespace App\Http\Controllers\Types;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends BaseController
{
    //show all types
    public function index()
    {
        $types = Type::all();
        return $this->sendResponse($types, 'Types retrieved successfully.');
    }



    //show type by id
    public function show($id)
    {
        $type = Type::find($id);
        if (is_null($type)) {
            return $this->sendError('Type not found.');
        }
        return $this->sendResponse($type, 'Type retrieved successfully.');
    }

    //update type
    public function update(Request $request, $id)
    {
        $type = Type::find($id);
        if (is_null($type)) {
            return $this->sendError('Type not found.');
        }
        $type->update($request->all());
        return $this->sendResponse($type, 'Type updated successfully.');
    }

    //delete type
    public function destroy($id)
    {
        $type = Type::find($id);
        if (is_null($type)) {
            return $this->sendError('Type not found.');
        }
        $type->delete();
        return $this->sendResponse($id, 'Type deleted successfully.');
    }
}
