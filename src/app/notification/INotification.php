<?php


namespace App\notification;


interface INotification
{
    function sendTargetDevice(array $data);

    function sendGroupMessaging(array $data);

    function sendTopicMessaging(array $data);

    function sendConditionTopicMessaging(array $data);

}