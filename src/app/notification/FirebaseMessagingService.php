<?php


namespace App\notification;


use App\common\AppConstant;
use App\common\utils\Validator;

class FirebaseMessagingService implements INotification
{
    protected $header;
    protected $curl;

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
        $field = ['deviceId', 'content', 'title'];
        $input = [$data['deviceId'], $data['content'], $data['title']];
        if (!Validator::validateInput($field, $input)) return;
        $fcmBody = [
            "to" => $data['deviceId'],
            "notification" => [
                "body" => $data['content'],
                "title" => $data['title'],
                "sound" => "default",
                "vibrate" => 1
            ]
        ];
        $fcmBody = json_encode($fcmBody);
//        echo $fcmBody;
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fcmBody);
        $results = curl_exec($this->curl);
        curl_close($this->curl);
        echo $results;
    }

    function sendGroupMessaging(array $data)
    {
        if ($data == null) return;
        if (!Validator::isTypeAnArray($data['groupId'], 'groupId')) return;
        if (!Validator::validateArrayInput($data['groupId'], 'groupId')) return;
        $field = ['content', 'title'];
        $input = [$data['content'], $data['title']];
        if (!Validator::validateInput($field, $input)) return;
        $fcmBody = [
            "registration_ids" => $data['groupId'],
            "notification" => [
                "body" => $data['content'],
                "title" => $data['title']
            ]
        ];
        $fcmBody = json_encode($fcmBody);
//        echo $fcmBody;
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fcmBody);
        $results = curl_exec($this->curl);
        curl_close($this->curl);
        echo $results;
    }

    function sendTopicMessaging(array $data)
    {
        if ($data == null) return;
        $field = ['topic', 'content', 'title'];
        $input = [$data['topic'], $data['content'], $data['title']];
        if (!Validator::validateInput($field, $input)) return;
        $fcmBody = [
            "to" => '/topics/' . $data['topic'],
            "notification" => [
                "body" => $data['content'],
                "title" => $data['title'],
                "sound" => "default",
                "vibrate" => 1
            ]
        ];
        $fcmBody = json_encode($fcmBody);
//        echo $fcmBody;
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fcmBody);
        $results = curl_exec($this->curl);
        curl_close($this->curl);
        echo $results;
    }

    function sendConditionTopicMessaging(array $data)
    {
        if ($data == null) return;
        $field = ['condition', 'content', 'title'];
        $input = [$data['condition'], $data['content'], $data['title']];
        if (!Validator::validateInput($field, $input)) return;
        $fcmBody = [
            "condition" => $data['condition'],
            "notification" => [
                "body" => $data['content'],
                "title" => $data['title'],
                "sound" => "default"
            ]
        ];
        $fcmBody = json_encode($fcmBody);
//        echo $fcmBody;
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fcmBody);
        $results = curl_exec($this->curl);
        curl_close($this->curl);
        echo $results;
    }
}