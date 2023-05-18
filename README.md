<h3>University Service API</h3>
This is a backend service that allows users to retrieve university and country details based on the provided IP address. The service utilizes two REST endpoints to fulfill user requests.

<h4>Requirements</h4>
To run this project, you need to have the following software installed:
<ul>
<li>PHP (version 8.1.x)</li>
<li>Composer (version 2.0.x)</li>
<li>MySQL (version 8.1.x)</li>
<li>Git (version 2.13.x)</li>
</ul>

<h4>Technology Stack</h4>
The backend service is built using the following technologies:
<ul>
<li>Language: PHP</li>
<li>Framework: Laravel</li>
<li>Database: MySql</li>
</ul>

<h4>Additional Package Required</h4>
<b>We Are Used `guzzlehttp/guzzle` Compose package for call HTTP Request.</b><br/>
<blockquote>
composer require guzzlehttp/guzzle
</blockquote>

<h4>Throttling</h4>
<p>Throttling is a mechanism implemented in this project to control the rate of requests made to certain routes or APIs. It helps prevent abuse, excessive resource consumption, and ensures fair usage of the application.</p>

<h5>How Throttling Works</h5>
Throttling is based on the client's IP address or any other identifier you choose.<br/>
When a client exceeds the defined rate limit, further requests from that client are temporarily blocked.<br/>
The duration of the blockage depends on the specific rate limit rules configured in the application.<br/>
Throttling can be applied to specific routes, controllers, or API endpoints to protect critical functionality or prevent overload.<br/>
By default, this project uses the Laravel built-in throttle middleware to handle throttling.<br/>

<h5>Rate Limit Configuration</h5>
<p>The rate limits and throttling settings can be customized based on your project's requirements. To configure the rate limits, you can modify the App\Http\Kernel class and define the throttle middleware options. The configuration can be found in the app/Http/Kernel.php file.</p>

<h5>Example Usage</h5>
To apply throttling to a route or controller, you can simply add the throttle middleware to the route definition or the controller constructor. Here's an example:
<br/>
/**<br/>
  * Set Throttling group for APIs execute 10 times in evry 1 minute.<br/>
 */<br/>
<blockquote>
Route::middleware('throttle:10,1')->group(function () {<br/>
    //Set route for calling<br/>
    Route::post('/country', [UniversityController::class, 'getCountryByIp']);<br/>
    Route::post('/universities', [UniversityController::class, 'getUniversitiesByCountry']);<br/>
});
</blockquote>

<p>In the above example, the throttle middleware is applied to the /api/country & /api/universities route with a rate limit of 10 requests per minute and a limit of 1 request per second for each client.</p>

<h4>Project Structure</h4>
The project follows a typical structure for a Laravel-based application:

<ul>
<li>app/: Contains the core application code, including controllers, models, and services.</li>
<li>config/: Contains configuration files for the application.</li>
<li>database/: Contains database-related files, such as migrations and seeders.</li>
<li>public/: Contains the entry point of the application and publicly accessible files.</li>
<li>routes/: Defines the application routes and maps them to appropriate controller actions.</li>
</ul>
  
<h4>Endpoints</h4>
The API has two endpoints:<br/>

1. `/api/country` - Accepts an IP address and returns details about the country it is assigned to.<br/>

HTTP Method: POST<br/>

Body FormData Parameters:<br/>

`ip_address` (required): The IP address to look up.<br/>

<h5>Example:</h5>
<blockquote>
POST /api/country HTTP/1.1
  </br>
Host: example.com
</blockquote>

<h5>Response:</h5>
<blockquote>
  HTTP/1.1 200 OK</br>
Content-Type: application/json</br>
{</br>
    "message": "Audit records created/updated successfully",</br>
    "data": {</br>
        "ip": "80.233.249.21",</br>
        "city": "Riga",</br>
        "region": "Riga",</br>
        "country": "LV",</br>
        "loc": "56.9460,24.1059",</br>
        "org": "AS43956 Latvijas Finieris A/S",</br>
        "postal": "LV-1001",</br>
        "timezone": "Europe/Riga",</br>
        "readme": "https://ipinfo.io/missingauth"</br>
    }</br>
}</br>
</blockquote>
![image](https://github.com/RkDevlops24/university-service/assets/106295693/4386dbaf-78e4-45ee-bc2d-85224232af2d)
<br/>
<br/>

2. `/api/universities` - Accepts a country name and returns a list of universities in that country.<br/>

HTTP Method: POST<br/>

Body FormData Parameters:<br/>

`country` (required): The name of the country to look up.<br/>

<h5>Example:</h5>
<blockquote>
POST /api/universities HTTP/1.1
  <br/>
Host: example.com
</blockquote>

<h5>Response:</h5>
<blockquote>
  HTTP/1.1 200 OK</br>
Content-Type: application/json</br>
{<br/>
    "message": "Get university details successfully",<br/>
    "data": [<br/>
        {<br/>
            "domains": [<br/>
                "aml.lv"<br/>
            ],<br/>
            "country": "Latvia",<br/>
            "alpha_two_code": "LV",<br/>
            "state-province": null,<br/>
            "web_pages": [<br/>
                "http://www.aml.lv/"<br/>
            ],<br/>
            "name": "Medical Academy of Latvia"<br/>
        },<br/>
        {<br/>
            "domains": [<br/>
                "ba.lv"<br/>
            ],<br/>
            "country": "Latvia",<br/>
            "alpha_two_code": "LV",<br/>
            "state-province": null,<br/>
            "web_pages": [<br/>
                "http://www.ba.lv/"<br/>
            ],<br/>
            "name": "School of Business and Finance"<br/>
        }<br/>
    ]<br/>
}<br/>
</blockquote>
![image](https://github.com/RkDevlops24/university-service/assets/106295693/c5493817-6ae0-496a-acaa-4490bc1ff83c)
<br/>
<br/>

<h4>Audit Record</h4>

After retrieving country details by IP address, the API also creates a basic audit record in the database with the following fields:
<ul>
<li>ip: The IP address looked up.</li>
<li>city: The city name associated with the IP address.</li>
<li>region: The region or state associated with the IP address.</li>
<li>country: The two-letter country code associated with the IP address.</li>
<li>loc: The geographic coordinates (latitude and longitude) associated with the IP address.</li>
</ul>

<h4>Running the Project</h4>
To run the project, follow these steps:<br/>
1. Download Laravel Project:<br/>
    a. Clone the repository:
    <blockquote>
    git clone https://github.com/RkDevlops24/RkDevlops24
    </blockquote>
    b. Download attached university-service.zip file:
    <blockquote>
    Extract your xampp path inside `htdocs` folder.
    </blockquote>

2.Install the dependencies:
<blockquote>
cd university-service<br/>
composer install<br/>
</blockquote>

3.Create a `.env` file:
Set Database connection Configure detils Like (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
<blockquote>
cp .env.example .env<br/>
===========================<br/>
DB_CONNECTION=mysql<br/>
DB_HOST=127.0.0.1<br/>
DB_PORT=3306<br/>
DB_DATABASE=universities-service<br/>
DB_USERNAME=root<br/>
DB_PASSWORD=<br/>
</blockquote>

4.Generate an application key:
<blockquote>
php artisan key:generate
</blockquote>

5.Set up the database:
<blockquote>
php artisan migrate<br/>
php artisan cache:clear
</blockquote>

6.Start the server:
<blockquote>
php artisan serve
</blockquote>

7.Visit <http://127.0.0.1:8000/> in your web browser to access the API.

License
This project is licensed under the MIT License.
