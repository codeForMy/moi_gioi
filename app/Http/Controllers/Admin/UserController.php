<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Company;
use App\Enums\UserRoleEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = User::query();
        $this->table = (new User())->getTable(); // trả về tên bảng trong database của model đó

        View::share('title', ucfirst($this->table));
        View::share('table', $this->table);
    }

    public function index(Request $request)
    {
        $selectedRole = $request->get('role');
        $selectedCity = $request->get('city');
        $selectedCompany = $request->get('company');
        $selectNumberDisplay = $request->get('display') ?? 3;

        $query = $this->model
            ->clone()
            ->with('company:id,name')
            ->orderBy('id', 'desc');

        if (!is_null($selectedRole) && $selectedRole !== 'All') {
            $query->where('role', $selectedRole);
        }
        if (!is_null($selectedCity) && $selectedCity !== 'All') {
            $query->where('city', $selectedCity);
        }
        if (!is_null($selectedCompany) && $selectedCompany !== 'All') {
            $query->whereHas('company', function ($q) use ($selectedCompany) {
                return $q->where('id', $selectedCompany);
            });
        }

        $data = $query->paginate($selectNumberDisplay);

        // $data = $this->model
        //     // ->select([
        //     //     'id',
        //     //     'name'
        //     // ]) // select specific row from user
        //     ->where
        //     ->with('company:id,name') // select specific row from company
        //     ->paginate(15);

        // sử dụng cách này chậm hơn vì khi dùng when nó sẽ khởi tạo thằng $this một lần nữa
        // $data = $this->model
        //     ->clone()
        //     ->when($request->has('role'), function ($q) {
        //         if (request('role') !== 'All') {
        //             return $q->where('role', request('role'));
        //         }
        //     })
        //     ->when($request->has('city'), function ($q) {
        //         if (request('city') !== 'All') {
        //             return $q->where('city', request('city'));
        //         }
        //     })
        //     ->when($request->has('company'), function ($q) {
        //         if (request('company') !== 'All') {
        //             return $q->where('company_id', request('company'));
        //         }
        //     })
        //     ->with('company:id,name') // select specific row from company
        //     ->orderBy('id', 'desc')
        //     ->paginate();

        $roles = UserRoleEnum::asArray();

        $cities = $this->model
            ->clone()
            ->distinct()
            ->whereNotNull('city')
            ->limit(10)
            ->pluck('city');

        $companies = Company::query()
            ->get([
                'id',
                'name',
            ]);

        $displayNumbers = [
            3,
            5,
            10,
        ];
        return view("admin.$this->table.index", [
            'data' => $data,
            'roles' => $roles,
            'cities' => $cities,
            'companies' => $companies,
            'displayNumbers' => $displayNumbers,
            'selectedRole' =>  $selectedRole,
            'selectedCity' =>  $selectedCity,
            'selectedCompany' =>  $selectedCompany,
            'selectNumberDisplay' => $selectNumberDisplay,
        ]);
    }

    public function destroy($userId)
    {
        User::destroy($userId); // soft delete

        return redirect()->back();
    }
}
