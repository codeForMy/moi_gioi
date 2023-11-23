<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Throwable;
use App\Models\Post;
use App\Models\Company;
use App\Models\Language;
use App\Imports\PostImport;
use Illuminate\Http\Request;
use App\Models\ObjectLanguage;
use App\Enums\PostRemotableEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TitleTrait;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Enums\ObjectLanguageTypeEnum;
use App\Enums\PostCurrencySalaryEnum;
use App\Enums\PostLevelEnum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResponseTrait;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Controllers\SystemConfigController;

class PostController extends Controller
{
    use TitleTrait;
    use ResponseTrait;
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Post::query();
        // $this->table = (new Post())->getTable();

        $routerName = Route::currentRouteName();


        View::share('title', ucfirst($this->getTitleRoute($routerName)));
        // View::share('table', $this->table);
    }

    public function index()
    {
        $levels = PostLevelEnum::asArray();

        return view('admin.posts.index', [
            'levels' => $levels,
        ]);
    }

    public function create()
    {
        $configs = SystemConfigController::getAndCache();

        $remotables = PostRemotableEnum::getArrWithoutAll();


        return view('admin.posts.create', [
            'currencies' => $configs['currencies'],
            'countries' => $configs['countries'],
            'remotables' => $remotables,
        ]);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $arr = $request->validated();

            $companyName = $request->get('company');

            if (!empty($companyName)) {
                $arr['company_id'] = Company::firstOrCreate(['name' => $companyName])->id;
            }
            if ($request->has('remotable')) {
                $arr['remotable'] = $request->get('remotable');
            }
            if ($request->has('can_parttime')) {
                $arr['can_parttime'] = 1;
            }

            $languages = $request->get('languages');

            $post = Post::create($arr);

            foreach ($languages as $language) {
                $language = Language::firstOrCreate(['id' => $language]);

                ObjectLanguage::create([
                    'object_id'   => $post->id,
                    'language_id' => $language->id,
                    'object_type' => Post::class,
                    // 'object_type' => ObjectLanguageTypeEnum::POST,
                ]);
            }

            DB::commit();
            return $this->successResponse();
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function importCSV(Request $request): JsonResponse
    {
        try {
            $levels = $request->input('levels');
            $file   = $request->file('file');

            Excel::import(new PostImport($levels), $file);

            return $this->successResponse();
        } catch (Throwable $e) {
            return $this->errorResponse();
        }
    }
}
