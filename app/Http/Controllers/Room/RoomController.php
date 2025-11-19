<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Resources\Room\RoomResource;
use App\Http\Responses\ApiResponse;
use App\Models\Patient;
use App\Models\Room;
use Illuminate\Support\Str;
use Hidehalo\Nanoid\Client;

class RoomController extends Controller
{
    public function store(StoreRoomRequest $request)
    {
        $user   = $request->user();
        $doctor = $user->doctor;

        if (!$doctor) {
            return ApiResponse::error('Only doctors can create rooms', null, 403);
        }

        $patientId = (int) $request->route('patient');
        $patient = Patient::findOrFail($patientId);

        $existing = Room::where('patient_id', $patient->id)->first();

        if ($existing) {
            return ApiResponse::success(
                'An active room already exists for this patient.',
                new RoomResource($existing), 200);
        }

        $client = new Client();
        $token = $client->generateId(20);

        $title = trim($request->input('title', ''));

        $room = Room::create([
            'patient_id' => $patient->id,
            'doctor_id'  => $doctor->id,
            'title'      => $title !== '' ? $title : "Case {$patient->id}",
            'room_code'       => $token,
        ]);
        return ApiResponse::success('room created successfully.', new RoomResource($room), 201);
    }
}
