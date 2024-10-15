<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Str;
use App\Models\Project;
use App\Events\NewUserCreated;

class ProjectController extends Controller
{
    public function store(Request $request) {

        $fields = $request->all();

        $errors = Validator::make($fields, [
                'name' => 'required',
                // 'status' => 'required',
                'startDate' => 'required',
                'endDate' => 'required'
        ]);

        if($errors->fails()){
            return response($errors->errors()->all(), 422);
        }

        // $isValidEmail = filter_var($fiels['email'], FILTER_VALIDATE_EMAIL) ? 1 : 0;

       $project = Project::create([
            'name' => $fields['name'],
            'startDate' => $fields['startDate'],
            'endDate' => $fields['endDate'],
            'status' => Project::NOT_STARTED,
            'slug' => Project::createSlug($fields['name'])
        ]);

        return response([
            'project' => $project,
            'message' => 'Project Created'
        ]);
    }

    public function edit(Request $request, $id) {

        $fields = $request->all();

        $errors = Validator::make($fields, [
                'id' => 'required',
                'name' => 'required',
                'startDate' => 'required',
                'endDate' => 'required'
        ]);

        if($errors->fails()){
            return response($errors->errors()->all(), 422);
        }

        // $isValidEmail = filter_var($fiels['email'], FILTER_VALIDATE_EMAIL) ? 1 : 0;

       $project = Project::where('id', $id)->update([
        'name' => $fields['name'],
        'startDate' => $fields['startDate'],
        'endDate' => $fields['endDate'],
        'slug' => Project::createSlug($fields['name'])
    ]);

        return response([
            'project' => $project,
            'message' => 'Project updated'
        ]);
    }
}
