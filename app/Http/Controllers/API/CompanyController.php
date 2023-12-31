<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;
use BaconQrCode\Renderer\Path\Path;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{

    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);


        $companyQuery = Company::with(['users'])->whereHas('users', function ($query) {
            $query->where('user_id', Auth::id());
        });

        //simkar-backendd.com/api/company?id=1 = Get Single data
        if ($id) {
            $company = $companyQuery->find($id);

            if ($company) {
                return ResponseFormatter::success($company, 'Company found');
            }
            return ResponseFormatter::error('Company not found', 484);
        }


        //simkar-backendd.com/api/company = Get Multiple data
        $companies = $companyQuery;

        if ($name) {
            $companies->where('name', 'like', '%' . $name . '%');
        }


        //Company ::with(['users])->where('name', 'like', '%Kunde%')->paginate(10);
        return ResponseFormatter::success(
            $companies->paginate($limit),
            'Companies found'
        );
    }

    public function create(CreateCompanyRequest $request)
    {
        try {
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('public/logos');
            }
            //Create company
            $company = Company::create([
                'name' => $request->name,
                'logo' => isset($path) ? $path : ''
            ]);

            if (!$company) {
                throw new Exception('Company not created');
            }
            //Attach  company to user
            $user = User::find(Auth::id());
            $user->companies()->attach($company->id);

            //Load user at company
            $company->load('users');


            return ResponseFormatter::success($company, 'Company created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }



    public function update(UpdateCompanyRequest $request, $id)

    {

        try {
            //Get Company
            $company = Company::find($id);

            //check if company exist
            if (!$company) {
                throw new Exception('Company not found');
            }

            //Upload logo
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('public/logos');
            }
            //Update company
            $company->update([
                'name' => $request->name,
                'logo' => isset($path) ? $path : $company->logo, //isset ... itu untuk ketika update tapi semisal gambarnya tetap cuma nama saja
            ]);
            return ResponseFormatter::success($company, 'Company created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
