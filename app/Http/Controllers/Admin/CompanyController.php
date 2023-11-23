<?php

namespace App\Http\Controllers\admin;

use Throwable;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TitleTrait;
use App\Http\Controllers\ResponseTrait;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Company\StoreRequest;


class CompanyController extends Controller
{
    use TitleTrait;
    use ResponseTrait;
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Company::query();
        // $this->table = (new Post())->getTable();
    }

    public function store(StoreRequest $request): JsonResponse
    {
        try {
            $arr = $request->validated();

            if($request->hasFile('logo')){
                $destination_path = 'public/images/company_logo/' . $request->name;
                $image = $request->file('logo');
                $image_name = $image->getClientOriginalName();
                $path = $request->file('logo')->storeAs($destination_path, $image_name);

                $arr['logo'] = $request->name . '/' . $image_name;

            }
            // $arr['logo'] = optional($request->file('logo'))->store('company_logo/' . $request->name);

            // create a new company with validated
            Company::create($arr);

            return $this->successResponse();
        } catch (Throwable $e) {
            $message = '';
            if ($e->getCode() === '23000') {
                $message = 'Duplicate company name';
            }

            return $this->errorResponse($e);
        }
    }
}
