<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\Base\Gallery;
use App\Models\Immovable;
use App\Models\Renting\Application;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class UtilsController extends Controller
{
    use ApiResponse;

    /**
     *  Módulo - Rentar Inmueble
     */
    public function generateRootNumber(): string
    {
        $alphab_num = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $root_number = 'CAS-' . rand(10000, 99999) . '-' . substr(str_shuffle($alphab_num), 0, 6);
        $exist = Application::where('root_number', $root_number)->first();
        if ($root_number == $exist) {
            $this->generateRootNumber();
        }
        return $root_number;
    }

    public function storeReferences($request, $modelApplicant)
    {
        $root = $this->generateRootNumber();

        try {
            // Application si es desde el home
            if ($request->requestLocation == 'home') {
                $modelApplicant->applications()->create([
                    'root_number' => $root,
                    'immovable_id' => $request->immovable_id,
                    'applicant_id' => $modelApplicant->id,
                    'comment' => $request->comment,
                ]);
            }

            // References
            $modelApplicant->references()->createMany([
                [
                    'name' => $request->rname,
                    'lastname' => $request->rlastname,
                    'birthdate' => $request->rbirthdate,
                    'residence_address' => $request->residence_address,
                    'residence_country' => $request->residence_country,
                    'residence_department' => $request->residence_department,
                    'residence_city' => $request->residence_city,
                    'kinship' => $request->rkinship,
                    'type' => 'family',
                    'phone' => $request->rphone,
                ],
                [
                    'name' => $request->rrname,
                    'lastname' => $request->rrlastname,
                    'birthdate' => $request->rrbirthdate,
                    'residence_address' => $request->rresidence_address,
                    'residence_country' => $request->rresidence_country,
                    'residence_department' => $request->rresidence_department,
                    'residence_city' => $request->rresidence_city,
                    'kinship' => $request->rrkinship,
                    'type' => 'personal',
                    'phone' => $request->rrphone,
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al agregar las referencias.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    // Fin Módulo - Rentar Inmueble
    public function converMonthInSpanish($monthp)
    {
        $month = '';
        switch ($monthp) {
            case 'January':
                $month = 'Enero';
                break;
            case 'February':
                $month = 'Febrero';
                break;
            case 'March':
                $month = 'Marzo';
                break;
            case 'April':
                $month = 'Abril';
                break;
            case 'May':
                $month = 'Mayo';
                break;
            case 'June':
                $month = 'Junio';
                break;
            case 'July':
                $month = 'Julio';
                break;
            case 'August':
                $month = 'Agosto';
                break;
            case 'September':
                $month = 'Septiembre';
                break;
            case 'October':
                $month = 'Octubre';
                break;
            case 'November':
                $month = 'Noviembre';
                break;
            case 'December':
                $month = 'Diciembre';
                break;
        }

        return $month;
    }

    // Notificaciones
    public function registerInmoNotify($inmo)
    {
        $data = array(
            'note' => '¡Buenas! ' . "\n\n" . 'Se ha agregado un nuevo inmueble con código ' . $inmo->code . ' a la plataforma, por favor revisar su contenido multimedia para das paso a la solicitud de documentación par su administración.' . "\n\n",
            'message' => "\n\n" . '¡Gracias por confiar en nosotros!',
        );
        $data = implode("\n", $data);
        $to = 'dbrokerssoluciones@gmail.com';
        $subject = 'Nuevo inmueble agregado';
        Mail::raw($data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
            $message->from('soporte@brokerssoluciones.com');
        });
    }

    public function conditionInmoNotify($inmo)
    {

        $to = $inmo->owner->email;
        $subject = 'Condiciones multimedia';

        if ($inmo->image_status == 'accepted' && $inmo->video_status == 'accepted') {
            $data = array(
                'note' => '¡Buenas! ' . "\n\n" . 'Han sido aceptada las condiciones para publicar tu inmueble con código ' . $inmo->code . ' en la plataforma, por favor sigue los estos pasos para subir los documentos de adminitración.' . "\n\n" . "1.Ingresa a la plataforma desde el siguiente enlace: https://brokerssoluciones.com/login"
                    . "\n" . "2.Inicia sesión con tu usuario y contraseña." . "\n" . "3.Una vez que hayas iniciado sesión, ve al panel de administración. Esto lo puedes hacer haciendo click en la esquina superior derecha y seleccionando Panel de Administración." . "\n" . "4.En el panel de administración, busca y haz click en la sección Publicar documentos." . "\n" . "5.En la sección de Publicar Documentos, selecciona el inmueble para el cual quieres subir información." . "\n" . "6.Una vez que hayas seleccionado el inmueble correcto, podrás subir los documentos e información relevante sobre el inmueble."
                    . "\n" . "7.Cuando hayas terminado de subir la información, haz click en 'Guardar' para que los documentos e información se asocien a ese inmueble en el sistema."
                    . "\n\n" . "Por favor házme saber si necesitas alguna aclaración o si quieres que amplíe algún paso en particular.",
                'message' => "\n\n" . '¡Gracias por confiar en nosotros!',
            );
            $data = implode("\n", $data);
            Mail::raw($data, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
                $message->from('soporte@brokerssoluciones.com');
            });
        } else if ($inmo->image_status == 'rejected' || $inmo->video_status == 'rejected') {
            $data = array(
                'note' => '¡Buenas! ' . "\n\n" . 'Han sido rechazadas las condiciones multimedia, es decir fotos y enlace al video para publicar tu inmueble con código ' . $inmo->code . ' en la plataforma, por favor sigue los estos pasos para cambiar esta información.' . "\n\n" . "1.Ingresa a la plataforma desde el siguiente enlace: https://brokerssoluciones.com/login"
                    . "\n" . "2.Inicia sesión con tu usuario y contraseña." . "\n" . "3.Una vez que hayas iniciado sesión, ve al panel de administración. Esto lo puedes hacer haciendo click en la esquina superior derecha y seleccionando Panel de Administración." . "\n" . "4.En el panel de administración, busca y haz click en la sección Editar inmueble." . "\n" . "5.En la sección de  Editar inmueble, selecciona el inmueble para el cual quieres editar la información." . "\n" . "6.Una vez que hayas seleccionado el inmueble correcto, podrás cambiar la información relevante sobre el inmueble."
                    . "\n" . "7.Las imágenes se editarán desde la sección 'Multimedia' del mismo formulario."
                    . "\n\n" . "Por favor házme saber si necesitas alguna aclaración o si quieres que amplíe algún paso en particular.",
                'message' => "\n\n" . '¡Gracias por confiar en nosotros!',
            );
            $data = implode("\n", $data);
            Mail::raw($data, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
                $message->from('soporte@brokerssoluciones.com');
            });
        }
    }
}
