<?php

namespace App\Service;

use Pusher\PushNotifications\PushNotifications;

class PusherBeamService
{
    public function send(string $user_id, string $title, string $body, string $deep_link, bool $is_user = false)
    {
        $beamsClient = new PushNotifications(array(
            "instanceId" => config('services.beams.instance_id'),
            "secretKey" => config('services.beams.secret_key'),
        ));

        if ($is_user) {
            $publishResponse = $beamsClient->publishToUsers(
                array("user-" . $user_id),
                    array("web" => array("notification" => array(
                        "title" => $title,
                        "body" => $body,
                        "deep_link" => $deep_link,
                    )),
                ));
        }else {
            $publishResponse = $beamsClient->publishToInterests(
                array("Pesan Broadcast"),
                    array("web" => array("notification" => array(
                        "title" => $title,
                        "body" => $body,
                        "deep_link" => $deep_link,
                    ))
                ));
        }

        return $publishResponse;
    }
}
