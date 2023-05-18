<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use App\Models\Audit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;

class UniversityController extends Controller
{
    private $storeURL;

    /**
     * Set $storeURL for store APIs URL.
     */
    public function __construct()
    {
        $this->storeURL = "";
    }

    /**
     * get country details by specific given IP address using third party API.
     *
     * @param  Request  $request
     * @return Response
     */

    public function getCountryByIp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ip_address' => 'required|ip',
        ]);

        if ($validator->fails()):
            // IP address is not valid
            return response()->json([
                'message' => 'Invalid IP address',
                'errors' => $validator->errors(),
            ], 422);
        endif;

        try{
            $this->storeURL = "https://ipinfo.io/";
            $client = new Client();
            $response = $client->request('GET', $this->storeURL. $request->ip_address.'/geo');
            $fetchAuditData = json_decode($response->getBody(), true);
        
            try {

                if(isset($fetchAuditData['bogon']) && $fetchAuditData['bogon'] == 1):
                    return response()->json([
                        'message' => 'Country Data Not Found',
                        'data' => [],
                    ], 422);

                else:

                    $auditData = [
                        'country' => $fetchAuditData['country'],
                        'city' => $fetchAuditData['city'],
                        'loc' => $fetchAuditData['loc']
                    ];

                    /**
                     * Create or update audit record in database.
                     */
                    Audit::updateOrCreate(['ip_address' => $request->ip_address], $auditData);

                    return response()->json([
                        'message' => 'Audit records created/updated successfully',
                        'data'    => $fetchAuditData
                    ], 200);
                endif;

            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'message' => 'Error creating/updating audit records',
                    'error' => $e->getMessage()
                ], 500);
            }
        } catch (RequestException $e) {
            // Handle request exceptions (network, timeout, etc.)
            $statusCode = $e->getCode();
            $errorMessage = $e->getMessage();

            // Log the exception or return an error response
            return response()->json(['message' => 'Something want wrong.', 'errors' => $errorMessage], $statusCode);
        }
    }

    /**
     * get unversities by country using third party API.
     *
     * @param  Request  $request
     * @return Response
     */
    public function getUniversitiesByCountry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required'
        ]);

        if ($validator->fails()):
            // Country required validation
            return response()->json([
                'message' => 'Invalid Country',
                'errors' => $validator->errors(),
            ], 422);
        endif;

        try {
            $this->storeURL = "http://universities.hipolabs.com/";
            $client = new Client();
            $response = $client->request('GET', $this->storeURL . "search?country=$request->country");
            $fetchUniversityData = json_decode($response->getBody(), true);

            if (!empty($fetchUniversityData)) :
                return response()->json([
                    'message' => 'Get university details successfully',
                    'data' => $fetchUniversityData
                ], 200);
            else :
                return response()->json([
                    'message' => 'University details Not Found',
                    'data' => [],
                ], 422);
            endif;

        } catch (RequestException $e) {
            // Handle request exceptions (network, timeout, etc.)
            $statusCode = $e->getCode();
            $errorMessage = $e->getMessage();

            // Log the exception or return an error response
            return response()->json(['message' => 'Something want wrong.', 'errors' => $errorMessage], $statusCode);
        }
        
    }
}
