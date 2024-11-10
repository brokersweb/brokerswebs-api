<?php

namespace App\Http\Repositories;

use App\Models\Base\Support;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SupportRepository extends Repository
{
    use ApiResponse;
    private $model;

    public function __construct()
    {
        $this->model = new Support();
    }

    public function getSupports()
    {
        return $this->successResponse($this->model->all()->load('answers'));
    }

    public function create(Request $request)
    {
        $rulesData = Validator::make($request->all(), [
            'fullname' => 'required|max:70',
            'email' => 'nullable|email',
            'cellphone' => 'required|max:30',
            'message' => 'required|max:600',
        ]);

        if ($rulesData->fails()) {
            return $this->errorResponse($rulesData->errors(), Response::HTTP_BAD_REQUEST);
        }
        $support = $this->model->create($request->all());
        $this->sendEmail($request);
        return $this->successResponse($support, Response::HTTP_CREATED);
    }

    public function getSupport($id)
    {
        $support = $this->model->find($id);
        if ($support) {
            return $this->successResponse($support);
        }
        return $this->errorResponse('Soporte no encontrado', Response::HTTP_NOT_FOUND);
    }


    public function sendEmail($request)
    {
        $data = array(
            'note' => '¡Buenas! Sres. Brokers Soluciones,' . "\n" . $request->message . 'Gracias.' . "\n",
            'name' => 'Nombre: ' . $request->fullname,
            'email' => 'Correo: ' . $request->email,
            'cellphone' => 'Celular: ' . $request->cellphone,
        );

        $data = implode("\n", $data);
        $to = 'dbrokerssoluciones@gmail.com';
        $subject = 'Área de soporte';
        Mail::raw($data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
            $message->from('soporte@brokerssoluciones.com', 'Brokers Soluciones|Soporte');
        });
        return $this->successResponse('Correo enviado', Response::HTTP_OK);
    }

    public function sendRegisterUserEmail($request)
    {
        $data = array(
            'note' => '¡Buenas! Sres. Brokers Soluciones,' . "\n" . 'Se ha registrado un nuevo usuario, por favor revisar y asignarle el rol o permisos requeridos para que pueda interactuar más en la plataforma.' . "\n" . 'Gracias.' . "\n",
            'name' => 'Nombre: ' . $request->name,
            'email' => 'Correo: ' . $request->email,
            'cellphone' => 'Celular: ' . $request->cellphone
        );
        $data = implode("\n", $data);
        $to = 'soporte@brokerssoluciones.com';
        $subject = 'Área de soporte';
        Mail::raw($data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
            $message->from('soporte@brokerssoluciones.com', 'Brokers Soluciones|Nuevo usuario');
        });
        // return $this->successResponse('Correo enviado', Response::HTTP_OK);
    }

    // Reply

    public function createReply($request, $id)
    {
        $rulesData = Validator::make($request->all(), [
            'message' => 'required',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($rulesData->fails()) {
            return $this->errorResponse($rulesData->errors(), Response::HTTP_BAD_REQUEST);
        }
        $support = $this->model->find($id);
        if ($support) {
            $support->answers()->create([
                'comment' => $request->message,
                'user_id' => $request->user_id
            ]);
            $support->update([
                'status' => 'answered'
            ]);
            $this->sendReplyEmail($request, $support);
            return $this->successResponseWithMessage('Respuesta enviada', Response::HTTP_OK);
        }
        return $this->errorResponse('Soporte no encontrado', Response::HTTP_NOT_FOUND);
    }


    public function sendReplyEmail($request, $support)
    {
        $data = array(
            'note' => '¡Buenas! ' . $support->fullname . ',' . "\n\n" . 'Ha recibido una respuesta a su mensaje, por favor revisar el siguiente mensaje y dar respuesta lo más pronto posible.' . "\n\n" . 'Gracias.' . "\n\n",
            'message' => $request->message . "\n\n" . '¡Gracias por contactarnos, Brokers Soluciones!',
        );
        $data = implode("\n", $data);
        $to = $support->email;
        $subject = 'Área de soporte';
        Mail::raw($data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
            $message->from('soporte@brokerssoluciones.com');
        });
    }
}
