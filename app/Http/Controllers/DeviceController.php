<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterDeviceRequest;

class DeviceController extends Controller
{
    public function registerDevice(RegisterDeviceRequest $registerDeviceRequest): \Illuminate\Http\JsonResponse
    {
        /**
         * The registration is successful if:
         * a. “deviceID” is in the DB
         * b. device already has “activationCode“ assigned (via Manager Portal) and is sending request without “activationCode” and it is already registered as “free” device or it is the first time it attempts to register.
         * c. device does not have “activationCode“ assigned and it tries to register for the first time without “activationCode”
         * d. device does not have “activationCode“ assigned and it tries to register for the first time with valid (existing and not already associated to another device) “activationCode”
         * e. device does not have “activationCode“ assigned and it is already registered as “free” and it tries to register with valid (existing and not already associated to another device) “activationCode”
         * In all other cases the registration process will fail and the API will respond with error message.
         */
       // Expected response format { "deviceId": "NW-H-20-0017", "deviceAPIKey": "489d5e8e1a4081a99da486b526a694f6", "deviceType": "leasing", "timestamp": "2021-07-01 00:00:00" }

        if ($registerDeviceRequest->validated()) {
            $data = [
                'deviceId' => $registerDeviceRequest->input('deviceId'),
                'deviceAPIKey' => '',
                'deviceType' => 'leasing',
                'timestamp' => date('Y-m-d H:i:s'),
            ];
            $status = 200;
        } else {

        }
        return $this->sendJsonResponse($data, $status);
    }

    private function sendJsonResponse(array $data, int $status): \Illuminate\Http\JsonResponse
    {
        return response()->json($data, $status);
    }
}
