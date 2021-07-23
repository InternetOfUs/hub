<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use frontend\models\Badge;
use frontend\models\BadgeDescriptor;


class IncentiveServerConnector extends PlatformConnector {

    public $baseUrl;

    /**
     * Get the list of all Badges that a user won in the context of an application.
     *
     * @param  string $appId  The application id
     * @param  string $userId The user id
     * @return array[Badge]   The Badge list
     */
    public function getBadgesForUser($appId, $userId) {
        $url = $this->baseUrl . "/incentive/apps/$appId/users/$userId";
        try {
            $response = $this->get($url, $this->authHeaders());
            return array_map(
                function($b){ return Badge::fromRepr($b); },
                $response['incentives']['badges']
            );
        } catch (\Exception $e) {
            $log = "Something went wrong while getting badge list for app [$appId] and user [$userId]: $e";
            Yii::error($log, 'wenent.connector.incentive_server');
            return null;
        }
    }

    /**
     * Get the list of all Badges associated to an app.
     *
     * @param  string $appId  The application id
     * @return array[Badge]   The Badge list
     */
    public function getBadgesForApp($appId) {
        $url = $this->baseUrl . "/badges/apps/$appId";
        try {
            $response = $this->get($url, $this->authHeaders());
            return array_map(
                function($b){ return Badge::fromRepr($b); },
                $response['badges']
            );
        } catch (\Exception $e) {
            $log = "Something went wrong while getting badge list for app [$appId]: $e";
            Yii::error($log, 'wenent.connector.incentive_server');
            return null;
        }
    }

    /**
     * Define a new badge.
     *
     * @param  BadgeDescriptor $descriptor The badge descriptor
     * @return BadgeDescriptor             The update badge descriptor associated to an id
     */
    public function createBadgeDescriptor(BadgeDescriptor $descriptor) {
        try {
            if ($descriptor->isTaskBadge()) {
                return $this->createTaskBadgeDescriptor($descriptor);
            } else {
                return $this->createTransactionBadgeDescriptor($descriptor);
            }
        } catch (\Exception $e) {
            $log = "Something went wrong while creating badge descriptor with id [$descriptor->id]: $e";
            Yii::error($log, 'wenent.connector.incentive_server');
            return null;
        }
    }

    private function createTaskBadgeDescriptor(BadgeDescriptor $descriptor) {
        $url = $this->baseUrl . "/badges/BadgeClasses/TaskType";
        $response = $this->post($url, $this->authHeaders(), $descriptor->toRepr());
        $descriptor->id = $response['entityId'];
        return $descriptor;
    }

    private function createTransactionBadgeDescriptor(BadgeDescriptor $descriptor) {
        $url = $this->baseUrl . "/badges/BadgeClasses/TaskTransaction";
        $response = $this->post($url, $this->authHeaders(), $descriptor->toRepr());
        $descriptor->id = $response['entityId'];
        return $descriptor;
    }

    public function getBadgeDescriptor($id) {
        $url = $this->baseUrl . "/badges/BadgeClasses/$id";
        try {
            $response = $this->get($url, $this->authHeaders());
            return BadgeDescriptor::fromRepr($response['badge']);
        } catch (\Exception $e) {
            $log = "Something went wrong while getting badge description for badge [$id]: $e";
            Yii::error($log, 'wenent.connector.incentive_server');
            return null;
        }
    }

    /**
     * Update the definition of an existing badge definition.
     *
     * @param  BadgeDescriptor $descriptor The Badge descriptor
     */
    public function updateBadgeDescriptor(BadgeDescriptor $descriptor) {
        //  TODO manca un return? qualcosa non funziona!
        try {
            if ($descriptor->isTaskBadge()) {
                $this->updateTaskBadgeDescriptor($descriptor);
            } else {
                $this->updateTransactionBadgeDescriptor($descriptor);
            }
        } catch (\Exception $e) {
            $log = "Something went wrong while updating badge descriptor with id [$descriptor->id]: $e";
            Yii::error($log, 'wenent.connector.incentive_server');
        }
    }

    private function updateTaskBadgeDescriptor(BadgeDescriptor $descriptor) {
        $url = $this->baseUrl . "/badges/BadgeClasses/TaskType/$id";
        $response = $this->put($url, $this->authHeaders(), $descriptor->toUpdateRepr());
    }

    private function updateTransactionBadgeDescriptor(BadgeDescriptor $descriptor) {
        $url = $this->baseUrl . "/badges/BadgeClasses/TaskTransaction/$id";
        $response = $this->put($url, $this->authHeaders(), $descriptor->toUpdateRepr());
    }

    public function deleteBadgeDescriptor($id) {
        $url = $this->baseUrl . "/badges/BadgeClasses/$id";
        try {
            $response = $this->delete($url, $this->authHeaders());
        } catch (\Exception $e) {
            $log = "Something went wrong while deleting badge description for badge [$id]: $e";
            Yii::error($log, 'wenent.connector.incentive_server');
            return null;
        }
    }

}
