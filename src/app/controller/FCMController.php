<?php


namespace App\controller;


use App\common\AppConstant;
use App\ServiceContainer;
use Exception;

class FCMController
{
    private $fcmService;

    /**
     * FCMController constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->fcmService = ServiceContainer::inject()->get(AppConstant::IOC_FCM_SERVICE);
    }

    function single(array $data)
    {
        $this->fcmService->sendTargetDevice($data);
        if ($this->fcmService->getOutput() == null) return;
        echo json_encode($this->fcmService->getOutput());
    }

    function group(array $data)
    {
        $this->fcmService->sendGroupMessaging($data);
        if ($this->fcmService->getOutput() == null) return;
        echo json_encode($this->fcmService->getOutput());
    }

    function topic(array $data, bool $condition = false)
    {
        if ($condition) {
            $this->fcmService->sendConditionTopicMessaging($data);
            return;
        }
        $this->fcmService->sendTopicMessaging($data);
        if ($this->fcmService->getOutput() == null) return;
        echo json_encode($this->fcmService->getOutput());
    }
}