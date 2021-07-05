<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use frontend\models\analytics\AppAnalytic;
use frontend\models\analytics\AnalyticDescription;


class AnalyticsManager {

    /**
     * Defines the list of analytics that should be available for
     * all existing applications.
     *
     * @return array An array of tuple including the analytic dimension, metric and timespan
     */
    private static function _analyticsToCreate() {
        return [
            // ['dimension', 'metric', 'timespan']
            // User analytics
            ['user', 'u:new', '1d'],
            ['user', 'u:active', '1d'],
            ['user', 'u:engaged', '1d'],
            // Message analytics
            // Task analytics
            // Transaction analytics
            // Badge analytics
        ];
    }

    private function appAnalytic($appId, $dimension, $metric, $timespan) {
        $where = [
            'app_id' => $appId,
            'dimension' => $dimension,
            'metric' => $metric,
            'timespan' => $timespan,
        ];
        return AppAnalytic::find()->where($where)->one();
    }

    /**
     * Verify the presence of all configured analytics for the
     * specified application.
     *
     * Any non-defined analytic is automatically
     * created by this methods according to the list defined by _analyticsToCreate().
     *
     * @param  string $appId The target application id
     */
    public function createAnalyticsIfMissing($appId) {

        foreach (self::_analyticsToCreate() as $analytic) {
            $dimension = $analytic[0];
            $metric = $analytic[1];
            $timespan = $analytic[2];

            $appAnalytic = $this->appAnalytic($appId, $dimension, $metric, $timespan);
            if (!$appAnalytic) {
                Yii::info("Defining missing analytic for app [$appId]", 'wenet.component.analytic');
                $appAnalytic = new AppAnalytic();
                $appAnalytic->dimension = $dimension;
                $appAnalytic->metric = $metric;
                $appAnalytic->timespan = $timespan;
                $appAnalytic->app_id = $appId;

                $descriptor = new AnalyticDescription(
                    $appId,
                    $dimension,
                    $metric,
                    AnalyticDescription::defaultTimespan($timespan)
                );

                $id = Yii::$app->loggingComponent->createAnalytic($descriptor);
                $appAnalytic->id = $id;
                $appAnalytic->save();
            }
        }
    }

    private function get($appId, $dimension, $metric, $timespan) {
        $appAnalytic = $this->appAnalytic($appId, $dimension, $metric, $timespan);
        $analyticResult = Yii::$app->loggingComponent->getResult($appId, $appAnalytic->id);
        return $analyticResult;
    }

    private function userData($appId, $timespan) {
        return [
            'total' => null,  # TODO
            'new' => $this->get($appId, 'user', 'u:new', $timespan)->result->count,
            'active' => $this->get($appId, 'user', 'u:active', $timespan)->result->count,
            'engaged' => $this->get($appId, 'user', 'u:engaged', $timespan)->result->count,
        ];
    }

    # TODO
    private function messageData($appId, $timespan) {
        return [
            'platform' => [
                'total' => 121,
                'period' => 31,
            ],
            'app' => [
                'total' => 201,
                'period' => 51,
            ],
            'users' => [
                'total' => null,
                'period' => 61,
            ]
        ];
    }

    # TODO
    private function taskData($appId, $timespan) {
        return [
            'total' => 101,
            'new' => 21,
            'active' => 86,
            'closed' => 15,
        ];
    }

    # TODO
    private function transactionData($appId, $timespan) {
        return [
            'total' => 111,
            'new' => 81,
            'distribution' => [
                'answerTransaction' => 5,
                'notAnswerTransaction' => 10,
                'bestAnswerTransaction' => 15,
                'moreAnswerTransaction' => 20,
                'reportQuestionTransaction' => 25,
                'reportAnswerTransaction' => 6
            ]
        ];
    }

    /**
     * Prepare the data with the structure that is expected by the
     * stats view.
     *
     * @param  string $appId    The application id
     * @param  string $timespan The target timespan
     * @return array            The data ready to be visualised
     */
    public function prepareData($appId, $timespan) {
        $data = [];
        $data['users'] = $this->userData($appId, $timespan);
        $data['messages'] = $this->messageData($appId, $timespan);
        $data['tasks'] = $this->taskData($appId, $timespan);
        $data['transactions'] = $this->transactionData($appId, $timespan);
        return $data;
    }



}
