<?php

namespace App\Http\Controllers\Renting;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Renting\CosignerRepository;
use App\Models\Immovable;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use PhpParser\Node\Stmt\Return_;

class CosignerController extends Controller
{

    /** @var CosignerRepository */
    private CosignerRepository $cosignerrepository;

    public function __construct(CosignerRepository $cosignerrepository)
    {
        $this->cosignerrepository = $cosignerrepository;
    }

    /**
     * @OA\Get(
     *     path="/api/cosigners",
     *     tags={"Cosigner"},
     *     summary="Obtener todos los codeudores",
     *     operationId="cosigners",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        return $this->cosignerrepository->cosigners();
    }
    /**
     * @OA\Get(
     *     path="/api/cosigners/{id}",
     *     tags={"Cosigner"},
     *     summary="Obtener un codeudor",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del codeudor",
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
        return $this->cosignerrepository->cosigner($id);
    }
    /**
     * @OA\Delete(
     *     path="/api/cosigners/{id}",
     *     tags={"Cosigner"},
     *     summary="Eliminar un codeudor",
     *     operationId="cosigner",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del codeudor",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation"
     *     ),
     * *     @OA\Response(
     *         response="404",
     *         description="Codeudor no encontrado"
     *     ),
     * )
     */
    public function destroy($id)
    {
        return $this->cosignerrepository->delete($id);
    }



    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'immovable_id' => 'required|exists:immovables,id',
            'tenant_id' => 'required|exists:tenants,id',
            'cosigner_type' => 'required|in:regular,root_property',
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
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $tenant = Tenant::find($request->tenant_id);

       return  $this->cosignerrepository->storeCosigners($request, $tenant);
    }
}
