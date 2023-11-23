<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CheckSlugRequest;
use Throwable;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Post\GenerateSlugRequest;
use Cviebrock\EloquentSluggable\Services\SlugService;

class PostController extends Controller
{
    use ResponseTrait;
    private object $model;

    public function __construct()
    {
        $this->model = Post::query();
    }

    public function index(): JsonResponse
    {
        $data =  $this->model
            ->paginate();
        foreach ($data as $each) {
            $each->currency_salary = $each->currency_salary_code;
            $each->status = $each->status_name;
        }


        // cách làm không sử dụng trait
        // return response()->json([
        //     'success' => true,
        //     'data' => $data->getCollection(),
        //     'pagination' => $data->linkCollection(),
        // ]);

        // cách làm sử dụng trait
        $arr['data'] = $data->getCollection();
        $arr['pagination'] = $data->linkCollection();

        return $this->successResponse($arr);
    }

    public function generateSlug(GenerateSlugRequest $request) : JsonResponse
    {
        try{
            $title = $request->get('title');
            $slug = SlugService::createSlug(Post::class, 'slug', $title);

            return $this->successResponse($slug);
        }
        catch(Throwable $e){
            return $this->errorResponse();
        }
    }

    public function checkSlug(CheckSlugRequest $request) : JsonResponse
    {
        return $this->successResponse();
    }
}
