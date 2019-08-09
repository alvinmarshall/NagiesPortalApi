<?php


namespace App\notification;


use App\common\AppConstant;
use App\common\utils\Validator;

class FirebaseMessagingService implements INotification
{
    protected $header;
    protected $curl;
    private $output;

    public function __construct()
    {
        $this->header = [
            'Content-Type: application/json',
            "Authorization: key=" . getenv('FCM_KEY')
        ];

        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, AppConstant::FIREBASE_MESSAGING_URL);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    function sendTargetDevice(array $data)
    {
        if ($data == null) return;
        $field = ['deviceId', 'message', 'title','type'];
        $input = [$data['deviceId'], $data['message'], $data['title'],$data['type']];
        if (!Validator::validateInput($field, $input)) return;
        $fcmBody = [
            "to" => $data['deviceId'],
            "data" => [
                "message" => $data['message'],
                "title" => $data['title'],
                "type" => $data['type'],
                "vibrate" => 1
            ]
        ];
        $fcmBody = json_encode($fcmBody);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fcmBody);
        $results = curl_exec($this->curl);
        curl_close($this->curl);
        $this->output = json_decode($results,false);
    }

    function sendGroupMessaging(array $data)
    {
        if ($data == null) return;
        if (!Validator::isTypeAnArray($data['groupId'], 'groupId')) return;
        if (!Validator::validateArrayInput($data['groupId'], 'groupId')) return;
        $field = ['message', 'title','type'];
        $input = [$data['message']??'', $data['title']??'',$data['type']??''];
        if (!Validator::validateInput($field, $input)) return;
        $fcmBody = [
            "registration_ids" => $data['groupId'],
            "data" => [
                "message" => $data['message'],
                "title" => $data['title'],
                'type' => $data['type']
            ]
        ];
        $fcmBody = json_encode($fcmBody);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fcmBody);
        $results = curl_exec($this->curl);
        curl_close($this->curl);
        $this->output = json_decode($results,false);
    }

    function sendTopicMessaging(array $data)
    {
        if ($data == null) return;
        $field = ['topic', 'message', 'title','type'];
        $input = [$data['topic']??'', $data['message']??'', $data['title']??'',$data['type']??''];
        if (!Validator::validateInput($field, $input)) return;
        $fcmBody = [
            "to" => '/topics/' . $data['topic'],
            "data" => [
                "message" => $data['message'],
                "title" => $data['title'],
                "type" => $data['type'],
                "vibrate" => 1
            ]
        ];
        $fcmBody = json_encode($fcmBody);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fcmBody);
        $results = curl_exec($this->curl);
        curl_close($this->curl);
        $this->output = json_decode($results,false);
    }

    function sendConditionTopicMessaging(array $data)
    {
        if ($data == null) return;
        $field = ['condition', 'message', 'title','type'];
        $input = [$data['condition']??'', $data['message']??'', $data['title'],$data['type']??''];
        if (!Validator::validateInput($field, $input)) return;
        $fcmBody = [
            "condition" => $data['condition'],
            "data" => [
                "message" => $data['message'],
                "title" => $data['title'],
                "type" => $data['type']
            ]
        ];
        $fcmBody = json_encode($fcmBody);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fcmBody);
        $results = curl_exec($this->curl);
        curl_close($this->curl);
        $this->output = json_decode($results,false);
    }

    /**
     * @return mixed
     */
    function getOutput(){
        return $this->output;
    }
}