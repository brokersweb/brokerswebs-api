<?php
namespace App\Http\Controllers\Renting;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Renting\ApplicantRepository;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    protected ApplicantRepository $repository;

    public function __construct(ApplicantRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * @OA\Get(
     *     path="/api/applicants",
     *     tags={"Applicant"},
     *     summary="Obtener todas los aplicantes o solicitantes",
     *     operationId="applicants",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        return $this->repository->applicants();
    }

    public function store(Request $request)
    {
        return $this->repository->create($request);
    }
    /**
     * @OA\Get(
     *     path="/api/applicants/{id}",
     *     tags={"Applicant"},
     *     summary="Obtener un aplicante o solicitante",
     *     operationId="applicant",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del aplicante o solicitante",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function show($id)
    {
        return $this->repository->applicant($id);
    }
}
