<?php

namespace App\Http\Repositories\Renting;

use App\Models\Renting\Cosigner;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CosignerRepository extends Repository
{
    private $model;
    use ApiResponse;

    private JobTypeRepository $jobTypeRepository;

    public function __construct(Cosigner $cosigner)
    {
        $this->model = $cosigner;
        $this->jobTypeRepository = new JobTypeRepository();
    }

    public function cosigners()
    {
        $cosigners = $this->model->all();
        return $this->successResponse($cosigners);
    }


    // Store the cosigners
    public function storeCosigners(Request $request, $applicant)
    {

        switch ($request->cosigner_type) {

            case 'root_property':

                DB::beginTransaction();

                try {
                    $cosigner = Cosigner::create([
                        'name' => $request->rpname,
                        'lastname' => $request->rplastname,
                        'dni' => $request->rpdni,
                        'birthdate' => $request->rpbirthdate,
                        'dni_file' => $request->rpdni_file,
                        'document_type' => $request->rpdocument_type,
                        'expedition_country' => $request->rpexpedition_country,
                        'expedition_department' => $request->rpexpedition_department,
                        'expedition_city' => $request->rpexpedition_city,
                        'expedition_date' => $request->rpexpedition_date,
                        'phone' => $request->rpphone,
                        'freedom_tradition' => $request->rpfreedom_tradition,
                        'lease_contract' => $request->rplease_contract,
                        'cosigner_type' => 'root_property',
                        'cosignerable_id' => $applicant->id,
                        'cosignerable_type' => get_class($applicant),
                    ]);

                    DB::commit();

                    return $this->successResponseWithMessage('Codeudor con propiedad raíz guardado correctamente', Response::HTTP_OK);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return $this->errorResponse('Ocurrió un error al guardar el codeudor con propiedad raíz', Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                break;

            case 'regular':

                $cosigneri = Cosigner::create([
                    'name' => $request->cname,
                    'lastname' => $request->clastname,
                    'dni' => $request->cdni,
                    'birthdate' => $request->cbirthdate,
                    'dni_file' => $request->cdni_file,
                    'document_type' => $request->cdocument_type,
                    'expedition_country' => $request->cexpedition_country,
                    'expedition_department' => $request->cexpedition_department,
                    'expedition_city' => $request->cexpedition_city,
                    'expedition_date' => $request->cexpedition_date,
                    'phone' => $request->cphone,
                    'freedom_tradition' => $request->cfreedom_tradition,
                    'working_type' => $request->cworking_type,
                    'cosigner_type' => 'regular',
                    'cosignerable_id' => $applicant->id,
                    'cosignerable_type' => get_class($applicant),
                ]);

                $cosignerii = Cosigner::create([
                    'name' => $request->ccname,
                    'lastname' => $request->cclastname,
                    'dni' => $request->ccdni,
                    'birthdate' => $request->ccbirthdate,
                    'dni_file' => $request->ccdni_file,
                    'document_type' => $request->ccdocument_type,
                    'expedition_country' => $request->ccexpedition_country,
                    'expedition_department' => $request->ccexpedition_department,
                    'expedition_city' => $request->ccexpedition_city,
                    'expedition_date' => $request->ccexpedition_date,
                    'phone' => $request->ccphone,
                    'freedom_tradition' => $request->ccfreedom_tradition,
                    'working_type' => $request->ccworking_type,
                    'cosigner_type' => 'regular',
                    'cosignerable_id' => $applicant->id,
                    'cosignerable_type' => get_class($applicant),
                ]);

                $this->jobTypeCosigneri($request, $cosigneri);
                $this->jobTypeCosignerii($request, $cosignerii);

                return $this->successResponseWithMessage('Codeudores guardados correctamente', Response::HTTP_OK);

                break;
        }
    }

    // Save Job Type

    public function jobTypeCosigneri($request, $model)
    {
        switch ($request->cworking_type) {
            case 'employee': {
                    $this->jobTypeRepository->storeEmployeeType($request, 'c', $model);
                }
                break;
            case 'independent': {
                    $this->jobTypeRepository->storeIndependentType($request, 'c', $model);
                }
                break;
            case 'freelancerp': {
                    $this->jobTypeRepository->storeFreelancerProfessionalType($request, 'c', $model);
                }
                break;
            case 'pensioner': {
                    $this->jobTypeRepository->storePensionerType($request, 'c', $model);
                }
                break;
            case 'capitalrentier': {
                    $this->jobTypeRepository->storeCapitalRentierType($request, 'c', $model);
                }
                break;
        }
    }

    public function jobTypeCosignerii($request, $model)
    {
        switch ($request->ccworking_type) {
            case 'employee': {
                    $this->jobTypeRepository->storeEmployeeType($request, 'cc', $model);
                }
                break;
            case 'independent': {
                    $this->jobTypeRepository->storeIndependentType($request, 'cc', $model);
                }
                break;
            case 'freelancerp': {
                    $this->jobTypeRepository->storeFreelancerProfessionalType($request, 'cc', $model);
                }
                break;
            case 'pensioner': {
                    $this->jobTypeRepository->storePensionerType($request, 'cc', $model);
                }
                break;
            case 'capitalrentier': {
                    $this->jobTypeRepository->storeCapitalRentierType($request, 'cc', $model);
                }
                break;
        }
    }

    // Fin store cosigner
    public function cosigner($id)
    {
        try {
            $cosigner = Cosigner::findOrFail($id)->load('applicant');
            // Cargar el tipo de trabajo
            if ($cosigner->employee()->exists()) {
                $cosigner->employee;
            } elseif ($cosigner->independent()->exists()) {
                $cosigner->independent;
            } elseif ($cosigner->other()->exists()) {
                $cosigner->other;
            }
            return $this->successResponse($cosigner, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('No se encontro el codeudor', Response::HTTP_NOT_FOUND);
        }
    }

    public function delete($id)
    {
        try {
            $cosigner = Cosigner::findOrFail($id);
            // TODO:  check si pertenece a un aplicante
            if ($cosigner->applicant()->exists()) {
                return $this->errorResponse('No se puede eliminar el codeudor, pertenece a un aplicante', Response::HTTP_BAD_REQUEST);
            }
            $cosigner->delete();
            return $this->successResponse('Se elimino el codeudor', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('No se encontro el codeudor', Response::HTTP_NOT_FOUND);
        }
    }
}
