<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Str;
use App\Models\Project;
use App\Models\TaskProgress;
use App\Models\Task;


class ProjectController extends Controller
{

    public function index(Request $request)
    {

        $query = $request->get('query');

        $projects = Project::with(['task_progress']);

        if (!is_null($query) && $query !== '') {
            $projects->where('name', 'like', '%' . $query . '%')->orderBy('id', 'desc');

            return response(['data' => $projects->paginate(10)], 200);
        }

        return response(['data' => $projects->paginate(10)], 200);
    }

    public function store(Request $request)
    {

        return DB::transaction(function () use ($request) {
            $fields = $request->all();

            $errors = Validator::make($fields, [
                'name' => 'required',
                // 'status' => 'required',
                'startDate' => 'required',
                'endDate' => 'required'
            ]);

            if ($errors->fails()) {
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

            TaskProgress::create([
                'projectId' => $project->id,
                'pinned_on_dashboard' => TaskProgress::NOT_PINNED_ON_DASHBOARD,
                'progress' => TaskProgress::INITIAL_TASK_PERCENT
            ]);

            return response([
                'project' => $project,
                'message' => 'Project Created'
            ]);
        });
    }

    public function edit(Request $request, $id)
    {

        $fields = $request->all();

        $errors = Validator::make($fields, [
            'id' => 'required',
            'name' => 'required',
            'startDate' => 'required',
            'endDate' => 'required'
        ]);

        if ($errors->fails()) {
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

    public function pinnedProject(Request $request)
    {

        $fields = $request->all();

        $errors = Validator::make($fields, [
            'projectId' => 'required'
        ]);

        if ($errors->fails()) {
            return response($errors->errors()->all(), 422);
        }

        TaskProgress::where('projectId', $fields['projectId'])->update(
            [
                'pinned_on_dashboard' => TaskProgress::PINNED_ON_DASHBOARD
            ]
        );

        return response(['message' => "Project Pinned on dashboard"]);
    }

    public function getProject(Request $request, $slug)
    {
        $project = Project::with(['tasks.task_members.member'])->where('projects.slug', $slug)->first();

        return response(['data' => $project]);
    }

    public function projectCount()
    {
        $count = Project::count();

        return response(['count' => $count]);
    }

}
