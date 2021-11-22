<p align="center">AloTech Call Example</p>

## Requirement
- Guzzle is a PHP HTTP client library

## AloTech Class
- [Official Docs](https://alotech.atlassian.net/wiki/spaces/PA1/overview).
If you want, you can match your custom functions with aloetech.

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
## How to implements click 2 call
More Detail [Click2Call](https://alotech.atlassian.net/wiki/spaces/PA1/pages/30703621/How+to+implement+click+2+call)

## Click 2 Call Example
    $alotech = new \AloTech();
    $auth = json_decode(json_encode($alotech->callApi('login', ['email' => 'ali.arslan@xxxx.com'])));
    
    $alotech->callApi('call', ['function' => 'click2call', 'phonenumber' => '05412XXXXXX', 'session' => $auth->original->data->session, 'hangup_url' => 'Hangup URL']);

