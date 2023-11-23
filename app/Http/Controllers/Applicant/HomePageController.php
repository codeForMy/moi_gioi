<?php

namespace App\Http\Controllers\Applicant;

use App\Models\Post;
use App\Models\Config;
use Illuminate\Http\Request;
use App\Enums\PostStatusEnum;
use App\Enums\PostRemotableEnum;
use App\Enums\SystemCacheKeyEnum;
use Illuminate\Support\HtmlString;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Http\Requests\Applicant\HomePage\IndexRequest;

class HomePageController extends Controller
{
    public function index(IndexRequest $request)
    {
        $searchCities      = $request->get('cities', []);
        $configs           = Config::getAndCache(0);
        $minSalary         = $request->get('min_salary', $configs['filter_min_salary']);
        $maxSalary         = $request->get('max_salary', $configs['filter_max_salary']);
        $remotable         = $request->get('remotable');
        $searchCanParttime = $request->boolean('can_parttime');

        $filters = [];
        if (!empty($searchCities)) {
            $filters['cities'] = $searchCities;
        }
        if ($request->has('min_salary')) {
            $filters['min_salary'] = $minSalary;
        }
        if ($request->has('max_salary')) {
            $filters['max_salary'] = $maxSalary;
        }
        if (!empty($remotable)) {
            $filters['remotable'] = $remotable;
        }
        if ($searchCanParttime) {
            $filters['can_parttime'] = $searchCanParttime;
        }

        $posts = Post::query()
            ->indexHomePage($filters)
            ->paginate();

        $arrCity = getAndCachePostCities();
        $filtersPostRemotable = PostRemotableEnum::getArrWithLowerKey();

        return view('applicant.index', [
            'posts' => $posts,
            'arrCity' => $arrCity,
            'filtersPostRemotable' => $filtersPostRemotable,
            'searchCities' => $searchCities,
            'remotable' => $remotable,
            'configs' => $configs,
            'minSalary' => $minSalary,
            'maxSalary' => $maxSalary,
            'searchCanParttime' => $searchCanParttime,
        ]);
    }

    public function show($postId)
    {
        $post = Post::query()
            ->with('file')
            ->findOrFail($postId);

        $title = $post->job_tittle;
            
        return view('applicant.show', [
            'post' => $post,
            'title' => $title,
        ]);
    }
}
