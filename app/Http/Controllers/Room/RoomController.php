<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Resources\Room\RoomResource;
use App\Http\Responses\ApiResponse;
use App\Models\Patient;
use App\Models\Room;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function store(StoreRoomRequest $request , Patient $Patient)
    {
        $user   = $request->user();
        $doctor = $user->doctor;

        if (!$doctor) {
            return ApiResponse::error('Only doctors can create rooms', null, 403);
        }

         $patientId = (int) $request->route('patient');
         $patient = Patient::find($patientId);

         if (!$patient) {
        return ApiResponse::error('Patient id not found', null, 404);
    }

        $existing = Room::where('patient_id', $patient->id)->first();

        if ($existing) {
            return ApiResponse::success(
                'An active room already exists for this patient.',
                new RoomResource($existing), 200);
        }


        $roomCode = bin2hex(random_bytes(10));


        $room = Room::create([
            'patient_id' => $patient->id,
            'doctor_id'  => $doctor->id,
            'title'      => $request->title,
            'room_code'       => $roomCode,
        ]);
        return ApiResponse::success('room created successfully.', new RoomResource($room), 201);
    }
}
