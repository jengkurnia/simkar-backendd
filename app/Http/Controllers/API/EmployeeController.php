<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $nik = $request->input('nik');
        $name = $request->input('name');
        $email = $request->input('email');
        $gender = $request->input('gender');
        $age = $request->input('age');
        $address = $request->input('address');
        $education = $request->input('education');
        $phone = $request->input('phone');
        $date_entry = $request->input('date_entry');
        $year_service = $request->input('year_service');
        $position = $request->input('position');
        $team_id = $request->input('team_id');
        $violation_id = $request->input('violation_id');
        $company_id = $request->input('company_id');
        $limit = $request->input('limit', 10);

        $employeeQuery = Employee::query();

        // Get single data
        if ($id) {
            $employee = $employeeQuery->with(['team', 'violation'])->find($id);

            if ($employee) {
                return ResponseFormatter::success($employee, 'Employee found');
            }

            return ResponseFormatter::error('Employee not found', 404);
        }

        // Get multiple data
        $employees = $employeeQuery;

        if ($name) {
            $employees->where('name', 'like', '%' . $name . '%');
        }

        if ($nik) {
            $employees->where('nik', $nik);
        }

        if ($email) {
            $employees->where('email', $email);
        }

        if ($gender) {
            $employees->where('gender', $gender);
        }

        if ($age) {
            $employees->where('age', $age);
        }

        if ($address) {
            $employees->where('address', $address);
        }

        if ($education) {
            $employees->where('education', $education);
        }

        if ($phone) {
            $employees->where('phone', 'like', '%' . $phone . '%');
        }

        if ($date_entry) {
            $employees->where('date_entry', $date_entry);
        }

        if ($year_service) {
            $employees->where('year_service', $year_service);
        }

        if ($position) {
            $employees->where('position', $position);
        }

        if ($violation_id) {
            $employees->where('violation_id', $violation_id);
        }

        if ($team_id) {
            $employees->where('team_id', $team_id);
        }

        if ($company_id) {
            $employees->whereHas('team', function ($query) use ($company_id) { //bisa ambilin data berdasarkan company id
                $query->where('company_id', $company_id); //wherehas itu maksudnya karyawan dari team mana
            }); //intinya cek karyawan ini dari tim mana
        }

        return ResponseFormatter::success(
            $employees->paginate($limit),
            'Employees found'
        );
    }

    public function create(CreateEmployeeRequest $request)
    {
        try {
            // Upload photos
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('public/photos');
            }

            // Create employee
            $employee = Employee::create([
                'name' => $request->name,
                'nik' => $request->nik,
                'email' => $request->email,
                'gender' => $request->gender,
                'age' => $request->age,
                'address' => $request->address,
                'education' => $request->education,
                'phone' => $request->phone,
                'data_entry' => $request->data_entry,
                'year_service' => $request->year_service,
                'position' => $request->position,
                'photo' => isset($path) ? $path : '',

                'team_id' => $request->team_id,
                'violation_id' => $request->violation_id,
            ]);

            if (!$employee) {
                throw new Exception('Employee not created');
            }

            return ResponseFormatter::success($employee, 'Employee created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {

        try {
            // Get employee
            $employee = Employee::find($id);

            // Check if employee exists
            if (!$employee) {
                throw new Exception('Employee not found');
            }

            // Upload photo
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('public/photos');
            }

            // Update employee
            $employee->update([
                'name' => $request->name,
                'nik' => $request->nik,
                'email' => $request->email,
                'gender' => $request->gender,
                'age' => $request->age,
                'address' => $request->address,
                'education' => $request->education,
                'phone' => $request->phone,
                'data_entry' => $request->data_entry,
                'year_service' => $request->year_service,
                'position' => $request->position,
                'photo' => isset($path) ? $path : $employee->photo,

                'team_id' => $request->team_id,
                'violation_id' => $request->violation_id,
            ]);

            return ResponseFormatter::success($employee, 'Employee updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get employee
            $employee = Employee::find($id);

            // TODO: Check if employee is owned by user

            // Check if employee exists
            if (!$employee) {
                throw new Exception('Employee not found');
            }

            // Delete employee
            $employee->delete();

            return ResponseFormatter::success('Employee deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
