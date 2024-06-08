<?php

namespace App\Http\Resources\Admin\Renting;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource  extends JsonResource
{
    public function toArray($request)
    {
        $status = '';
        switch ($this->status) {
            case 'In Progress':
                $status = 'En proceso';
                break;
            case 'Accepted':
                $status = 'Aprobada';
                break;
            case 'Rejected':
                $status = 'Rechazada';
                break;
            default:
                $status = 'Pendiente';
                break;
        }

        return [
            'id' => $this->id,
            'root_number' => $this->root_number,
            'imm_title' => $this->immovable->title,
            'appl_name' => $this->applicant->name . ' ' . $this->applicant->lastname,
            'appl_phone' => $this->applicant->phone,
            'imm_address' => $this->immovable->address->street . ', ' . $this->immovable->address->city . ', ' . $this->immovable->address->neighborhood,
            'status' => $status,
            'created_at' => $this->created_at,
            'comment' => $this->comment,
        ];
    }
}
