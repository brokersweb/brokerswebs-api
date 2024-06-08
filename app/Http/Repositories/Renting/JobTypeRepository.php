<?php

namespace App\Http\Repositories\Renting;

use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class JobTypeRepository extends Repository
{

    use ApiResponse;


    public function jobTypeValidation($request, $prefix)
    {
        switch ($request->{$prefix . 'working_type'}) {
            case 'employee':
                return [
                    $prefix . 'ename' => 'required|max:100',
                    $prefix . 'ephone' => 'required|max:25',
                    $prefix . 'eaddress' => 'required|max:100',
                    $prefix . 'ecountry' => 'required|max:50',
                    $prefix . 'edepartment' => 'required|max:50',
                    $prefix . 'ecity' => 'required|max:50',
                    $prefix . 'emarket' => 'required|max:100',
                    $prefix . 'esalary' => 'required',
                    $prefix . 'eexpense' => 'required',
                    $prefix . 'eentry_date' => 'required|date',
                    $prefix . 'eworking_letter' => 'required',
                    $prefix . 'epayment_stubs' => 'required',
                ];
                break;
            case 'independent':
                return [
                    $prefix . 'iname' => 'required|max:100',
                    $prefix . 'iphone' => 'required|max:25',
                    $prefix . 'iaddress' => 'required|max:50',
                    $prefix . 'icountry' => 'required|max:50',
                    $prefix . 'idepartment' => 'required|max:50',
                    $prefix . 'icity' => 'required|max:50',
                    $prefix . 'idescription' => 'required|max:1000',
                    $prefix . 'iincome' => 'required',
                    $prefix . 'iexpense' => 'required',
                    $prefix . 'ichamber_commerce' => 'required',
                    $prefix . 'irut_file' => 'required',
                    $prefix . 'ibank_statement' => 'required',
                    $prefix . 'iincome_statement' => 'required',
                ];
                break;
            case 'freelancerp':
                return [
                    $prefix . 'fname' => 'required|max:100',
                    $prefix . 'fphone' => 'required|max:25',
                    $prefix . 'faddress' => 'required|max:50',
                    $prefix . 'fcountry' => 'required|max:50',
                    $prefix . 'fdepartment' => 'required|max:50',
                    $prefix . 'fcity' => 'required|max:50',
                    $prefix . 'fdescription' => 'required|max:1000',
                    $prefix . 'fincome' => 'required',
                    $prefix . 'fexpense' => 'required',
                    $prefix . 'fcaccount_public' => 'required',
                    $prefix . 'frut_file' => 'required',
                    $prefix . 'fbank_statement' => 'required',
                    $prefix . 'fincome_statement' => 'required',
                ];
                break;
            case 'pensioner':
                return [
                    $prefix . 'pcertificate' => 'required',
                    $prefix . 'ppayment_stubs' => 'required'
                ];
                break;
            case 'capitalrentier':
                return [
                    $prefix . 'ccertificate' => 'required',
                ];
                break;
        }
    }



    public function storeEmployeeType($request, $prefix, $model)
    {

        $payments = json_encode($request->{$prefix . 'epayment_stubs'});

        $model->employee()->create([
            'name' => $request->{$prefix . 'ename'},
            'phone' => $request->{$prefix . 'ephone'},
            'address' => $request->{$prefix . 'eaddress'},
            'country' => $request->{$prefix . 'ecountry'},
            'department' => $request->{$prefix . 'edepartment'},
            'city' => $request->{$prefix . 'ecity'},
            'market' => $request->{$prefix . 'emarket'},
            'salary' => $request->{$prefix . 'esalary'},
            'expense' => $request->{$prefix . 'eexpense'},
            'entry_date' => $request->{$prefix . 'eentry_date'},
            'working_letter' => $request->{$prefix . 'eworking_letter'},
            'payment_stubs' => $payments,
            'entity_type' => $model::class,
            'entity_id' => $model->id,
        ]);
    }


    public function storeIndependentType($request, $prefix, $model)
    {
        $model->independent()->create([
            'name' => $request->{$prefix . 'iname'},
            'phone' => $request->{$prefix . 'iphone'},
            'address' => $request->{$prefix . 'iaddress'},
            'country' => $request->{$prefix . 'icountry'},
            'department' => $request->{$prefix . 'idepartment'},
            'city' => $request->{$prefix . 'icity'},
            'description' => $request->{$prefix . 'idescription'},
            'income' => $request->{$prefix . 'iincome'},
            'expense' => $request->{$prefix . 'iexpense'},
            'chamber_commerce' => $request->{$prefix . 'ichamber_commerce'},
            'rut_file' => $request->{$prefix . 'irut_file'},
            'bank_statement' => $request->{$prefix . 'ibank_statement'},
            'income_statement' => $request->{$prefix . 'iincome_statement'},
            'entity_type' => $model::class,
            'entity_id' => $model->id,
        ]);
    }

    public function storeFreelancerProfessionalType($request, $prefix, $model)
    {
        $model->freelancerProfessional()->create([
            'name' => $request->{$prefix . 'fname'},
            'phone' => $request->{$prefix . 'fphone'},
            'address' => $request->{$prefix . 'faddress'},
            'country' => $request->{$prefix . 'fcountry'},
            'department' => $request->{$prefix . 'fdepartment'},
            'city' => $request->{$prefix . 'fcity'},
            'description' => $request->{$prefix . 'fdescription'},
            'income' => $request->{$prefix . 'fincome'},
            'expense' => $request->{$prefix . 'fexpense'},
            'caccount_public' => $request->{$prefix . 'fcaccount_public'},
            'rut_file' => $request->{$prefix . 'frut_file'},
            'bank_statement' => $request->{$prefix . 'fbank_statement'},
            'income_statement' => $request->{$prefix . 'fincome_statement'},
            'entity_type' => $model::class,
            'entity_id' => $model->id,
        ]);
    }

    public function storePensionerType($request, $prefix, $model)
    {
        $payments = json_encode($request->{$prefix . 'ppayment_stubs'});

        $model->pensioner()->create([
            'certificate' => $request->{$prefix . 'pcertificate'},
            'payment_stubs' => $payments,
            'entity_type' => $model::class,
            'entity_id' => $model->id
        ]);
    }

    public function storeCapitalRentierType($request, $prefix, $model)
    {
        $model->capitalRentier()->create([
            'certificate' => $request->{$prefix . 'ccertificate'},
            'entity_type' => $model::class,
            'entity_id' => $model->id
        ]);
    }
}
