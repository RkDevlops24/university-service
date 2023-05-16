<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use App\Models\Audit;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * @param  int  $ip
     * @return Response
     */

    public function getCountryByIp($ip)
    {
        $this->storeURL = "https://ipinfo.io/";
        $client = new Client();
        $response = $client->request('GET', $this->storeURL. $ip.'/geo');
        $fetchAuditData = json_decode($response->getBody(), true);

        try {

            $auditData = [
                'country' => $fetchAuditData['country'],
                'city' => $fetchAuditData['city'],
                'loc' => $fetchAuditData['loc']
            ];

            /**
             * Create or update audit record in database.
             */
            Audit::updateOrCreate(['ip_address' => $ip], $auditData);

            return response()->json([
                'message' => 'Audit records created/updated successfully',
                'data'    => $fetchAuditData
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Error creating/updating audit records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * get unversities by country using third party API.
     *
     * @param  string  $country
     * @return Response
     */
    public function getUniversitiesByCountry($country)
    {
        $this->storeURL = "http://universities.hipolabs.com/";
        $client = new Client();
        $response = $client->request('GET', $this->storeURL . "search?country=$country");
        $fetchUniversityData = json_decode($response->getBody(), true);

        return response()->json([
            'message' => 'Get university details successfully',
            'data' => $fetchUniversityData
        ], 200);

    }
}
