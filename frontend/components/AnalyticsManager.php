<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use frontend\models\AppUser;
use frontend\models\analytics\AppAnalytic;
use frontend\models\analytics\AnalyticDescription;


class AnalyticsManager {

    const TIMESPANS = [
        '1d',
        '7d',
        '30d',
    ];

    const COUNT_ANALITYCS = [
        // ['dimension', 'metric']

        // User analytics
        ['user', 'new'],
        ['user', 'active'],
        ['user', 'engaged'],
        // ['user', 'age'],
        // ['user', 'gender'],
        // Message analytics
        // ['message', 'm:from_users'],
        // ['message', 'm:responses'],
        // ['message', 'm:notifications'],
        // Task analytics
        // Transaction analytics
        // Badge analytics
    ];

    const SEGMENTATION_ANALITYCS = [
        // ['dimension', 'metric']

        // User analytics
        ['user', 'age'],
        ['user', 'gender'],
        // Message analytics
        // Task analytics
        // Transaction analytics
        // Badge analytics
    ];

    /**
     * Defines the list of analytics that should be available for
     * all existing applications.
     *
     * @param array $analyticsMap Either SEGMENTATION_ANALITYCS or COUNT_ANALITYCS
     *
     * @return array An array of tuple including the analytic dimension, metric and timespan
     */
    private static function _analyticsToCreate($analyticsMap) {
        $analyticsToCreate = [];

        foreach (AnalyticsManager::TIMESPANS as $timespan) {
            foreach ($analyticsMap as $analytic) {
                $analyticsToCreate[] = [$analytic[0], $analytic[1], $timespan];
            }
        }

        return $analyticsToCreate;
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

        # TODO it should be possible to remove analytics that are no more useful

        foreach ([AnalyticDescription::TYPE_COUNT => self::COUNT_ANALITYCS, AnalyticDescription::TYPE_SEGMENTATION => SELF::SEGMENTATION_ANALITYCS] as $type => $analyticsMap) {

            foreach (self::_analyticsToCreate($analyticsMap) as $analytic) {
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

                    if ($type == AnalyticDescription::TYPE_COUNT) {

                        $descriptor = AnalyticDescription::count(
                            $appId,
                            $dimension,
                            $metric,
                            AnalyticDescription::movingTimespan($timespan)
                        );
                    } else if ($type == AnalyticDescription::TYPE_SEGMENTATION) {
                        $descriptor = AnalyticDescription::segmentation(
                            $appId,
                            $dimension,
                            $metric,
                            AnalyticDescription::movingTimespan($timespan)
                        );
                    } else {
                        throw new Exception("Could not build descriptor for [$type]");
                    }

                    $id = Yii::$app->loggingComponent->createAnalytic($descriptor);
                    $appAnalytic->id = $id;
                    $appAnalytic->save();
                }
            }
        }
    }

    private function get($appId, $dimension, $metric, $timespan) {
        $appAnalytic = $this->appAnalytic($appId, $dimension, $metric, $timespan);
        if (!$appAnalytic) {
            # TODO include warning
            return null;
        }

        $analyticResult = Yii::$app->loggingComponent->getResult($appId, $appAnalytic->id);
        return $analyticResult;
    }

    private function userData($appId, $timespan) {

        $genderData = $this->get($appId, 'user', 'gender', $timespan)->content();
        $ageData = $this->get($appId, 'user', 'age', $timespan)->content();

        return [
            'total' => AppUser::find()->where(['app_id' => $appId])->count(),
            'new' => $this->get($appId, 'user', 'new', $timespan)->content(),
            'active' => $this->get($appId, 'user', 'active', $timespan)->content(),
            'engaged' => $this->get($appId, 'user', 'engaged', $timespan)->content(),
            'gender' => $genderData ? $genderData : [],
            'age' => $ageData ? $ageData : [],
        ];
    }

    # TODO
    private function messageData($appId, $timespan) {
        return [
            'platform' => [
                'total' => null, # TODO
                'period' => $this->get($appId, 'message', 'notifications', $timespan)->result->count,
            ],
            'app' => [
                'total' => null, # TODO
                'period' => $this->get($appId, 'message', 'responses', $timespan)->result->count,
            ],
            'users' => [
                'total' => null, # TODO
                'period' => $this->get($appId, 'message', 'from_users', $timespan)->result->count,
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
        // $data['messages'] = $this->messageData($appId, $timespan);
        // $data['tasks'] = $this->taskData($appId, $timespan);
        // $data['transactions'] = $this->transactionData($appId, $timespan);
        return $data;
    }



}
