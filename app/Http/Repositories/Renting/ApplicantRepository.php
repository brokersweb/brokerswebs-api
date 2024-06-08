<?php

namespace App\Http\Repositories\Renting;

use App\Http\Controllers\Utils\UtilsController as UtilsUtilsController;
use App\Models\ImmovableTenant;
use App\Models\Renting\Applicant as RentingApplicant;
use App\Models\Tenant;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApplicantRepository extends Repository
{
    private $model, $modelTenant;
    use ApiResponse;
    private $utilsController;
    private CosignerRepository $cosignerRepository;
    private JobTypeRepository $jobTypeRepository;


    public function __construct(RentingApplicant $applicant, Tenant $tenant, CosignerRepository $cosignerRepository)
    {
        $this->model = $applicant;
        $this->modelTenant = $tenant;
        $this->utilsController = new UtilsUtilsController();
        $this->cosignerRepository = $cosignerRepository;
        $this->jobTypeRepository = new JobTypeRepository();
    }

    public function create(Request $request)
    {

        $rulesData = [
            'aname' => 'required|max:255',
            'alastname' => 'required|max:255',
            'aphone' => 'required|max:25',
            'adocument_type' => 'required|in:CC,CE,TI,PPN,NIT,RC,RUT',
            'adni' => 'required|max:25',
            'adni_file' => 'required',
            'aexpedition_place' => 'required|max:100',
            'aexpedition_date' => 'required|date',
            'abirthdate' => 'required|date',
            'agender' => 'required|in:Masculino,Femenino,Otro',
            'acivil_status' => 'required|in:Soltero,Casado,Unión libre,Viudo,Divorciado',
            'adependent_people' => 'required|integer',
            'aprofession' => 'required',
            'aemail' => 'required|email',
            'aaddress' => 'required|max:100',
            'aworking_type' => 'required|in:employee,independent,freelancerp,pensioner,capitalrentier',

            'cosigner_type' => 'required|in:regular,root_property',
            'immovable_id' => 'required|string|exists:immovables,id',
            'comment' => 'nullable|max:500',
            'requestLocation' => 'required|in:home,admin',
            // Root Property -> Codeudor con Propiedad Raíz
            'rpdocument_type' => 'required_if:cosigner_type,root_property|in:CC,CE,TI,PPN,NIT,RC,RUT',
            'rpdni' => 'required_if:cosigner_type,root_property|max:20',
            'rpbirthdate' => 'required_if:cosigner_type,root_property|date',
            'rpexpedition_country' => 'required_if:cosigner_type,root_property|max:50',
            'rpexpedition_department' => 'required_if:cosigner_type,root_property|max:50',
            'rpexpedition_city' => 'required_if:cosigner_type,root_property|max:50',
            'rpname' => 'required_if:cosigner_type,root_property|max:100',
            'rplastname' => 'required_if:cosigner_type,root_property|max:100',
            'rpphone' => 'required_if:cosigner_type,root_property|max:25',
            'rpexpedition_date' => 'required_if:cosigner_type,root_property|date',
            'rpdni_file' => 'required_if:cosigner_type,root_property',
            'rpfreedom_tradition' => 'required_if:cosigner_type,root_property',
            'rplease_contract' => 'nullable',
            // Codeudor I
            'cdocument_type' => 'required_if:cosigner_type,regular|in:CC,CE,TI,PPN,NIT,RC,RUT',
            'cdni' => 'required_if:cosigner_type,regular|max:20',
            'cbirthdate' => 'required_if:cosigner_type,regular|date',
            'cexpedition_country' => 'required_if:cosigner_type,regular|max:50',
            'cexpedition_department' => 'required_if:cosigner_type,regular|max:50',
            'cexpedition_city' => 'required_if:cosigner_type,regular|max:50',
            'cname' => 'required_if:cosigner_type,regular|max:100',
            'clastname' => 'required_if:cosigner_type,regular|max:100',
            'cphone' => 'required_if:cosigner_type,regular|max:25',
            'cexpedition_date' => 'required_if:cosigner_type,regular|date',
            'cworking_type' => 'required_if:cosigner_type,regular|in:employee,independent,freelancerp,pensioner,capitalrentier',
            'cdni_file' => 'required_if:cosigner_type,regular',
            // Codeudor II
            'ccdocument_type' => 'required_if:cosigner_type,regular|in:CC,CE,TI,PPN,NIT,RC,RUT',
            'ccdni' => 'required_if:cosigner_type,regular|max:20',
            'ccbirthdate' => 'required_if:cosigner_type,regular|date',
            'ccexpedition_country' => 'required_if:cosigner_type,regular|max:50',
            'ccexpedition_department' => 'required_if:cosigner_type,regular|max:50',
            'ccexpedition_city' => 'required_if:cosigner_type,regular|max:50',
            'ccname' => 'required_if:cosigner_type,regular|max:100',
            'cclastname' => 'required_if:cosigner_type,regular|max:100',
            'ccphone' => 'required_if:cosigner_type,regular|max:25',
            'ccexpedition_date' => 'required_if:cosigner_type,regular|date',
            'ccworking_type' => 'required_if:cosigner_type,regular|in:employee,independent,freelancerp,pensioner,capitalrentier',
            'ccdni_file' => 'required_if:cosigner_type,regular',
            // Referencias
            'rname' => 'required|max:100',
            'rlastname' => 'required|max:100',
            'rphone' => 'required|max:25',
            'rbirthdate' => 'required|date',
            'residence_address' => 'required|max:50',
            'residence_country' => 'required|max:50',
            'residence_department' => 'required|max:50',
            'residence_city' => 'required|max:50',
            'rkinship' => 'required',

            'rrname' => 'required|max:100',
            'rrlastname' => 'required|max:100',
            'rrphone' => 'required|max:25',
            'rrbirthdate' => 'required|date',
            'rresidence_address' => 'required|max:50',
            'rresidence_country' => 'required|max:50',
            'rresidence_department' => 'required|max:50',
            'rresidence_city' => 'required|max:50',
            'rrkinship' => 'required',
        ];

        // Validaciones para el tipo de trabajo
        $applicantRule = array_merge($rulesData, $this->jobTypeRepository->jobTypeValidation($request, 'a'));

        if ($request->cosigner_type === 'root_property') {
            $allRules = $applicantRule;
        } else {

            $cosigneri = array_merge($rulesData, $this->jobTypeRepository->jobTypeValidation($request, 'c'));
            $cosignerii = array_merge($rulesData, $this->jobTypeRepository->jobTypeValidation($request, 'cc'));

            $allRules = array_merge($applicantRule, $cosigneri, $cosignerii);
        }

        $validated = Validator::make($request->all(), $allRules);

        if ($validated->fails()) {
            return $this->errorResponse($validated->errors(), Response::HTTP_BAD_REQUEST);
        }


        DB::beginTransaction();

        try {

            switch ($request->requestLocation) {
                case 'home': {
                        $applicant = $this->model->create([
                            'name' => $request->aname,
                            'lastname' => $request->alastname,
                            'document_type' => $request->adocument_type,
                            'dni' => $request->adni,
                            'expedition_place' => $request->aexpedition_place,
                            'expedition_date' => $request->aexpedition_date,
                            'phone' => $request->aphone,
                            'birthdate' => $request->abirthdate,
                            'gender' => $request->agender,
                            'civil_status' => $request->acivil_status,
                            'dependent_people' => $request->adependent_people,
                            'profession' => $request->aprofession,
                            'email' => $request->aemail,
                            'address' => $request->aaddress,
                            'dni_file' => $request->adni_file,
                            'working_type' => $request->aworking_type,
                        ]);
                        // Referencias , Tipo de trabajo: Applicante o Solicitante
                        $this->utilsController->storeReferences($request, $applicant);
                        $this->saveJobTypeApplicant($request, $applicant);

                        // Codeudores
                        $this->cosignerRepository->storeCosigners($request, $applicant);

                        DB::commit();
                        return $this->successResponseWithMessage('Solicitud creada correctamente', Response::HTTP_CREATED);
                    }
                    break;
                case 'admin': {
                        $tenant = $this->modelTenant->create([
                            'name' => $request->aname,
                            'lastname' => $request->alastname,
                            'document_type' => $request->adocument_type,
                            'dni' => $request->adni,
                            'expedition_place' => $request->aexpedition_place,
                            'expedition_date' => $request->aexpedition_date,
                            'cellphone' => $request->aphone,
                            'birthdate' => $request->abirthdate,
                            'gender' => $request->agender,
                            'civil_status' => $request->acivil_status,
                            'dependent_people' => $request->adependent_people,
                            'profession' => $request->aprofession,
                            'email' => $request->aemail,
                            'address' => $request->aaddress,
                            'dni_file' => $request->adni_file,
                            'working_type' => $request->aworking_type,
                            'type' => 'holder'
                        ]);
                        // Relación con el inmueble
                        ImmovableTenant::create([
                            'immovable_id' => $request->immovable_id,
                            'tenant_id' => $tenant->id,
                        ]);
                        // Referencias , Tipo de trabajo: Inquilino
                        $this->utilsController->storeReferences($request, $tenant);
                        $this->saveJobTypeApplicant($request, $tenant);

                        // Codeudores
                        $this->cosignerRepository->storeCosigners($request, $tenant);

                        DB::commit();
                        return $this->successResponseWithMessage('Inquilino agregado correctamente', Response::HTTP_CREATED);
                    }
                    break;
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }



    public function saveJobTypeApplicant($request, $model)
    {
        switch ($request->aworking_type) {
            case 'employee': {
                    $this->jobTypeRepository->storeEmployeeType($request, 'a', $model);
                }
                break;
            case 'independent': {
                    $this->jobTypeRepository->storeIndependentType($request, 'a', $model);
                }
                break;
            case 'freelancerp': {
                    $this->jobTypeRepository->storeFreelancerProfessionalType($request, 'a', $model);
                }
                break;
            case 'pensioner': {
                    $this->jobTypeRepository->storePensionerType($request, 'a', $model);
                }
                break;
            case 'capitalrentier': {
                    $this->jobTypeRepository->storeCapitalRentierType($request, 'a', $model);
                }
                break;
        }
    }



    public function applicants()
    {
        return $this->successResponse($this->model->all(), Response::HTTP_OK);
    }

    public function applicant($id): JsonResponse
    {
        $applicant = $this->model->find($id)->load('applications', 'references', 'cosigners');
        if ($applicant) {
            return $this->successResponse($applicant, Response::HTTP_OK);
        }
        return $this->errorResponse('Applicant not found', Response::HTTP_NOT_FOUND);
    }
}
