<?php

declare(strict_types=1);

namespace PickIt\Responses;

use PickIt\Entities\MapPoint;

class GetMapPointResponse extends RawResponse
{
    /**
     * @var MapPoint[]
     */
    private const WEEK_DAYS = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo'
    ];
    private array $points = [];

    public function __construct(RawResponse $rawResponse)
    {
        parent::__construct($rawResponse->getRawResponse(), $rawResponse->getHeaders());

        $response = $rawResponse->getResponse();
        foreach ($response['result'] as $point) {
            if(!$this->isPointValid($point)) {
                continue;
            }
            $this->points[] = new MapPoint(
                $point['pickitPoint']['id'],
                $point['name'],
                $point['latitud'],
                $point['longitud'],
                $point['direccion'],
                $point['codigoPostal'],
                isset($point['dropoff']),
                isset($point['pickitPoint']['estado']) && 1 === $point['pickitPoint']['estado'],
                $this->weeklyScheduleArrayToString($point['pointBaseOpeningHours'])
            );
        }
    }

    private function isPointValid(array $point): bool
    {
        $requiredFields = [
            'pickitPoint',
            'name',
            'latitud',
            'longitud',
            'direccion',
            'codigoPostal',
            'pointBaseOpeningHours'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($point[$field])) {
                return false;
            }
        }

        if (!isset($point['pickitPoint']['id'])) {
            return false;
        }

        return true;
    }

    public function getPoints(): array
    {
        return $this->points;
    }

    private function weeklyScheduleArrayToString(array $weeklyScheduleArray): string
    {
        $weeklyScheduleString = '';
        $scheduleSlots = [];
        $chain = false;
        $lastEntry = null;
        $chainStartingEntry = null;

        if(empty($weeklyScheduleArray)) {
            return '';
        }

        foreach ($weeklyScheduleArray as $key => $entry) {
            //first entry is just saved
            if(!$lastEntry) {
                $lastEntry = $entry;
                continue;
            }

            //check if there's continuity: one day of difference and same open and close times
            if(
                $entry['day'] == 1+$lastEntry['day'] && 
                $entry['open'] == $lastEntry['open'] && 
                $entry['close'] == $lastEntry['close']
            ) {
                if(!$chain) { //we start a chain and save the first day of it
                    $chainStartingEntry = $lastEntry;
                    $chain = true;
                }
                $lastEntry = $entry;
                if($key !== array_key_last($weeklyScheduleArray)) { 
                    continue; //if it's the last entry we must print what we have, otherwise we keep going
                }
            }

            if($chain){ //create a chained slot
                $scheduleSlots[] = 
                    self::WEEK_DAYS[$chainStartingEntry['day']] . 
                    ' a ' . self::WEEK_DAYS[$lastEntry['day']] . ': ' . 
                    $lastEntry['open'] . ' - ' . $lastEntry['close'];
                $lastEntry = $entry;
                $chain = false;
                continue;
            }

            //create a single slot
            $scheduleSlots[] = self::WEEK_DAYS[$lastEntry['day']] . ': ' . $lastEntry['open'] . ' - ' . $lastEntry['close'];
            $lastEntry = $entry;
        }

        return implode(' | ', $scheduleSlots);

    }
}
