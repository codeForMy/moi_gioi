<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Post;
use App\Models\User;
use NumberFormatter;
use App\Models\Company;
use App\Models\Language;
use App\Enums\FileTypeEnum;
use App\Enums\PostCurrencySalaryEnum;
use Illuminate\Http\Request;
use App\Enums\PostStatusEnum;
use App\Enums\PostRemotableEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = User::query();
        $this->table = (new User())->getTable();

        View::share('title', ucfirst($this->table));
        View::share('table', $this->table);
    }

    public function test()
    {
        $arr = PostCurrencySalaryEnum::asArray();
        foreach ($arr as $val) {
            $key = PostCurrencySalaryEnum::getKey($val);
            $locale = PostCurrencySalaryEnum::getLocaleByVal($val);
            $format = new NumberFormatter($locale, NumberFormatter::CURRENCY);
            $string = $format->formatCurrency(1000, $key);
            echo formatCurrencySalary($string, $key) . '</br>';
            echo $string . '</br>';
        }
    }
}
