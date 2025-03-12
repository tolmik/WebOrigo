<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterDeviceRequest;
use App\Models\ActivationCodes;
use App\Models\Device;
use App\Models\RegistrationRequest;
use Illuminate\Http\JsonResponse;
use function PHPUnit\Framework\isEmpty;

class DeviceController extends Controller
{
    public function registerDevice(RegisterDeviceRequest $registerDeviceRequest): JsonResponse
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
        $isRequestSuccessful = false;
        if ($registerDeviceRequest->validated()) {
            /**
             * We validated the deviceID, at the form validation step, so we know that it is present at the DB at this point.
             * So we gather the data for the additional checks. This step should be moved to a DeviceService class, and not directly written here.
             */
            //TODO: Move the logical checks for successful registration into a DeviceService class in the Services folder.
            $deviceId = $registerDeviceRequest->input('deviceId');
            $activationCode = $registerDeviceRequest->input('activationCode');
            $device = Device::where('deviceId', $deviceId)->first();

            if (isEmpty($registerDeviceRequest->input('activationCode'))) {
                /**
                 * We do not have an activation code, so we need to check if condition b - c is met for a successful registration
                 */
                if ($device->deviceType == 'free' && !isEmpty($device->activationCode) || RegistrationRequest::where('deviceId', $device->deviceId)->count() == 0) {
                    $isRequestSuccessful = true; // Case B
                } else if (isEmpty($device->activationCode) && RegistrationRequest::where('deviceId', $device->deviceId)->where('activationCode', null)->count() == 0) {
                    $isRequestSuccessful = true; // Case C
                }
            } else {
                /**
                 * We have an activation code, so we check if it is legit. Cases D and E.
                 */
                if (isEmpty($device->activationCode) && ActivationCodes::where('deviceId', null)->where('activationCode', $activationCode)->count() == 1
                    && (RegistrationRequest::where('deviceId', $device->deviceId)->count() == 0 || $device-> deviceType == 'free')) {
                    $isRequestSuccessful = true;
                }
            }


        }


        if ($isRequestSuccessful) {
            /**
             * The request was successful, so we generate the response data for it.
             */
            $data = [
                'deviceId' => $deviceId,
                'deviceAPIKey' => '',
                'deviceType' => 'leasing',
                'timestamp' => date('Y-m-d H:i:s'),
            ];
            $status = 201;
        } else {
            /**
             * This is a bad request, as it did not pass the validation.
             */
            $status = 400;
            $data = ['errorMessage' => 'A valid Device ID is required for this request'];
        }

        /**
         * We log the registration request, as we need it for future Request validations and multiple security reasons.
         */
        RegistrationRequest::factory()->create([
            'deviceId' => $deviceId,
            'activationCode' => $registerDeviceRequest->input('activationCode'),
            'ipAddress' => $registerDeviceRequest->ip()
            ]);
        return $this->sendJsonResponse($data, $status);
    }

    private function sendJsonResponse(array $data, int $status): \Illuminate\Http\JsonResponse
    {
        return response()->json($data, $status);
    }
}
