<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicationRequest;
use App\Http\Resources\MedicationResource;

class MedicationController extends Controller
{
    public function store(StoreMedicationRequest $request)
    {
        $currentDoctor = auth()->user()->doctor;
        $medication = $currentDoctor->medications()->create([
            'name' => $request->name,
            'dosage' => $request->dosage,
            'frequency' => $request->frequency,
            'description' => $request->description ?? null,
            'patient_id' => $request->patient_id,
        ]);

        $nextVisit = $currentDoctor->appointments()->create([
            'appointment_date' => $request->appointment_date,
            'patient_id' => $request->patient_id,
        ]);

        $medication['next_visit'] = $nextVisit->appointment_date;

        return response()->json([
            'success' => true,
            'message' => 'Medication created successfully',
            'data' => new MedicationResource($medication),
            'next' => $request->get('action') == 'save_and_create_another' ? 'create' : 'index',
        ]);
    }
}
