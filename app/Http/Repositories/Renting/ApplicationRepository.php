<?php
namespace App\Http\Repositories\Renting;

use App\Http\Resources\Admin\Renting\ApplicationResource as RentingApplicationResource;
use App\Interfaces\ApplicationInterface;
use App\Models\Renting\Application as RentingApplication;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;

class ApplicationRepository extends Repository implements ApplicationInterface
{
    use ApiResponse;
    private $model;

    public function __construct()
    {
        $this->model = new RentingApplication();
    }

    public function all()
    {
        // $applications = $this->model->with('applicant')->get();
        $applications = RentingApplicationResource::collection($this->model->get());
        return $this->successResponse($applications);
    }

    public function show($id)
    {
        $application = $this->model->with('applicant')->find($id);
        if (is_null($application)) {
            return $this->errorResponse('Application not found', 404);
        }
        return $this->successResponse($application);
    }

    public function changeStatus(Request $request, $id)
    {
        $rules = [
            'status' => 'required|string|in:Accepted,Rejected,In Progress'
        ];
        $validator = validator($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }
        try {
            $application = $this->model->find($id);
            if (is_null($application)) {
                return $this->errorResponse('Application not found', 404);
            }
            $application->update($request->all());
            return $this->successResponse($application);
        } catch (\Throwable $th) {
            return $this->errorResponse('Application not updated', 400);
        }
    }
}
