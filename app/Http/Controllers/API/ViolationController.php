<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Violation;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateViolationRequest;
use App\Http\Requests\UpdateViolationRequest;

class ViolationController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);
        $with_responsibilities = $request->input('with_responsibilities', false);

        $violationQuery = Violation::query();

        // Get single data
        if ($id) {
            $violation = $violationQuery->with('responsibilities')->find($id);

            if ($violation) {
                return ResponseFormatter::success($violation, 'Violation found');
            }

            return ResponseFormatter::error('Violation not found', 404);
        }

        // Get multiple data
        $violations = $violationQuery->where('company_id', $request->company_id);

        if ($name) {
            $violations->where('name', 'like', '%' . $name . '%');
        }

        if ($with_responsibilities) {
            $violations->with('responsibilities');
        }

        return ResponseFormatter::success(
            $violations->paginate($limit),
            'Violations found'
        );
    }

    public function create(CreateViolationRequest $request)
    {
        try {
            // Create violation
            $violation = Violation::create([
                'name' => $request->name,
                'company_id' => $request->company_id,
            ]);

            if (!$violation) {
                throw new Exception('Violation not created');
            }

            return ResponseFormatter::success($violation, 'Violation created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(UpdateViolationRequest $request, $id)
    {

        try {
            // Get violation
            $violation = Violation::find($id);

            // Check if violation exists
            if (!$violation) {
                throw new Exception('Violation not found');
            }

            // Update violation
            $violation->update([
                'name' => $request->name,
                'company_id' => $request->company_id,
            ]);

            return ResponseFormatter::success($violation, 'Violation updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get violation
            $violation = Violation::find($id);

            // TODO: Check if violation is owned by user

            // Check if violation exists
            if (!$violation) {
                throw new Exception('Violation not found');
            }

            // Delete violation
            $violation->delete();

            return ResponseFormatter::success('Violation deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
