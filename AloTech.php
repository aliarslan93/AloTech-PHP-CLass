<?php

use GuzzleHttp\Client;

class AloTech
{
    /**
     * App Token of Alo-Tech Api
     * @var string
     */
    private $appToken;
    /**
     * Tenant Name of Alo-Tech Api
     * @var string
     */
    private $tenantName;
    /**
     * Endpoint of Alo-Tech Api
     * @var string
     */
    private $apiEndPoint;
    /**
     * Functions of Alo-Tech Api
     * @var array
     */
    private $functions = [
        //function Map List Local => AloTech
        'ping' => 'ping',
        'call' => 'call',// login like a user // requires phone
        'hang' => 'hang',
        'login' => 'login', // login like a user // requires email field
        'user-list' => 'userlist',// get all users
        'user' => 'getuser', // get individual user // requires userkey field as userid
        'add-contact' => 'addcontacttocampaign',
        'campaigns' => 'getcampaignlist', // get campaigns
        'agent-list' => 'getAgentList',
        'ip-phone-list' => 'ipphonelist',
        'location-list' => 'location-list',
        'incoming-call-list' => 'incomingcalllist',
        'queue-list' => 'queuelist',
    ];
    /**
     * Parameters for Alo-Tech Api
     * @var array
     */
    private $params = [];
    /**
     * Alo-Tech api call method
     * @var string
     */
    private $apiCallMethod = 'GET';
    /**
     * Response status. success/error
     * @var string
     */
    private $status = 'success';
    /**
     * Message from returning server
     * @var string
     */
    private $message = '';
    /**
     * Api result without status and message
     * @var null|array
     */
    private $data = NULL;

    /**
     * AloTechRepository constructor.
     */
    public function __construct()
    {
        $this->appToken = "ALOTECH_APP_TOKEN";
        $this->params['app_token'] = $this->appToken;
        $this->tenantName = "ALOTECH_TENANT_NAME";
        $this->apiEndPoint = "http://{$this->tenantName}.alo-tech.com/api/";
    }

    /**
     * Alo-Tech function and data decider
     * @param $function
     * @param $data
     * @return JsonResponse
     */
    private function callFunction($function, $data = false)
    {
        if (!isset($this->functions[$function])) {
            return json_encode([
                'status' => 'error',
                'code' => 200,
                'message' => 'Function unknown.'
            ]);
        }

        $this->params['function'] = $this->functions[$function];

        if ($data) {
            foreach ($data as $key => $datum) {
                $this->params[$key] = $datum;
            }
        }
        return $this;
    }

    /**
     * Alo-Tech master Api call
     * @param $function
     * @param bool $data
     * @return JsonResponse
     *
     */
    public function callApi($function, $data = false)
    {
        $this->callFunction($function, $data);
        $apiFullUrl = $this->apiEndPoint . "?" . http_build_query($this->params);

        try {
            $client = new Client();
            $request = $client->request($this->apiCallMethod, $apiFullUrl);
        } catch (\Exception $e) {
            return json_encode([
                'status' => 'error',
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);
        }

        if ($request->getStatusCode() != 200) {
            $this->status == 'error';
            $this->message == 'Server error.';
            return $this->response();
        }

        $result = json_decode($request->getBody(), true);

        if (isset($result->success)) {
            if ($result->success == true) {
                $this->status = 'success';
            } else {
                $this->status = 'error';
            }
        }

        if (isset($result->message)) {
            $this->message = $result->message;
        }
        unset($result->success);
        unset($result->error);
        unset($result->message);
        if ($result) {
            $this->data = $result;
        }
        return $this->response();
    }

    /**
     * Make response.
     * @return object
     */
    private function response()
    {
        return json_encode(
            [
                'data' => $this->data,
                'status' => $this->status,
                'message' => $this->message,
            ]
        );
    }
}