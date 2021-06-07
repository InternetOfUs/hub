<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use frontend\models\TaskTypeDetails;

class TaskManagerConnector extends PlatformConnector {

    public $baseUrl;

    public function listTaskTypes() {
        $url = $this->baseUrl . '/taskTypes';
        try {
            $result = $this->get($url, $this->authHeaders());
            return \array_map(
                function($t) {
                    return TaskTypeDetails::fromRepr($t);
                },
                $result['taskTypes']
            );
        } catch (\Exception $e) {
            $log = "Something went wrong while getting list of task types: $e";
            Yii::error($log, 'wenet.connector.taskManager');
            throw $e;
        }
    }

    public function getTaskType($id) {
        $url = $this->baseUrl . '/taskTypes/' . $id;
        try {
            $result = $this->get($url, $this->authHeaders());
            return TaskTypeDetails::fromRepr($result);
        } catch (\Exception $e) {
            $log = "Something went wrong while getting task type [$id]: $e";
            Yii::error($log, 'wenet.connector.taskManager');
            throw $e;
        }
    }

    public function createTaskType(TaskTypeDetails $taskType) {
        $url = $this->baseUrl . '/taskTypes';
        // print_r(JSON::encode($taskType->toRepr()));
        // exit();
        try {
            $result = $this->post($url, $this->authHeaders(), $taskType->toRepr());
            $response = Json::decode($result);
            return $response['id'];
        } catch (\Exception $e) {
            $log = "Something went wrong while creating new task type: $e";
            Yii::error($log, 'wenet.connector.taskManager');
            throw $e;
        }
    }

    public function updateTaskType(TaskTypeDetails $taskType) {
        $url = $this->baseUrl . '/taskTypes/' . $taskType->id;
        // print_r(JSON::encode($taskType->toRepr()));
        // exit();
        try {
            $result = $this->put($url, $this->authHeaders(), $taskType->toRepr());
        } catch (\Exception $e) {
            $log = "Something went wrong while updating task type [$taskType->id]: $e";
            Yii::error($log, 'wenet.connector.taskManager');
            throw $e;
        }
    }

}
