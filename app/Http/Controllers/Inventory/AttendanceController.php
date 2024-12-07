<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AttendanceResource;
use App\Models\Attendance;
use Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::all();
        $attendances = AttendanceResource::collection($attendances);

        return $this->successResponse($attendances);
    }

    public function store(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'notes' => 'nullable',
            'date' => 'required',
            'staffAttendance' => 'required|array'
        ]);


        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $exist = Attendance::where('attdate', $request->date)->first();
        if ($exist) {
            return $this->errorResponse('Esta Asistencia ya ha sido registrada para la fecha seleccionada.', Response::HTTP_BAD_REQUEST);
        }

        try {

            $attendance = Attendance::create([
                'notes' => $request->notes,
                'attdate' => $request->date,
                'user_id' => Auth::id(),
            ]);

            foreach ($request->staffAttendance as $item) {

                $attendance->items()->create([
                    'check_in' => $item['check_in'],
                    'check_out' => $item['check_out'],
                    'staff_id' => $item['staff_id'],
                    'position' => $item['position'],
                    'worksite' => $item['worksite'],
                    'payment' => $item['payment'],
                    'status' => $item['is_active'],
                ]);
            }

            return $this->successResponseWithMessage('Asistencia registrada correctamente.', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al registrar la asistencia', Response::HTTP_BAD_REQUEST);
        }
    }


    public function updateStatus(Request $request, $id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return $this->errorResponse('Asistencia no encontrada', Response::HTTP_NOT_FOUND);
        }


        $valid = Validator::make($request->all(), [
            'status' => 'required',
            'confirmed_id' => 'required_if:status,confirmed',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {
            if ($request->status == 'confirmed') {
                $attendance->update([
                    'status' => $request->status,
                    'confirmed_id' => $request->confirmed_id,
                ]);
            } else {
                $attendance->update(['status' => $request->status, 'confirmed_id' => null]);
            }
            return $this->successResponseWithMessage('Estado de la asistencia actualizado correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al actualizar el estado de la asistencia', Response::HTTP_BAD_REQUEST);
        }
    }


    public function show($id)
    {
        $attendance = Attendance::find($id)->load('items');

        try {
            if (!$attendance) {
                return $this->errorResponse('Asistencia no encontrada', Response::HTTP_NOT_FOUND);
            }
            return $this->successResponse($attendance);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener la asistencia', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
