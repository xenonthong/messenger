<?php

declare(strict_types=1);

namespace Kerox\Messenger\Model\Message\Attachment\Template\Airline;

use Kerox\Messenger\Exception\InvalidKeyException;

class ExtendedFlightInfo extends AbstractFlightInfo implements TravelClassInterface
{
    /**
     * @var string
     */
    protected $connectionId;

    /**
     * @var string
     */
    protected $segmentId;

    /**
     * @var string
     */
    protected $aircraftType;

    /**
     * @var string
     */
    protected $travelClass;

    /**
     * ExtendedFlightInfo constructor.
     *
     * @param string                                                                    $connectionId
     * @param string                                                                    $segmentId
     * @param string                                                                    $flightNumber
     * @param \Kerox\Messenger\Model\Message\Attachment\Template\Airline\Airport        $departureAirport
     * @param \Kerox\Messenger\Model\Message\Attachment\Template\Airline\Airport        $arrivalAirport
     * @param \Kerox\Messenger\Model\Message\Attachment\Template\Airline\FlightSchedule $flightSchedule
     * @param string                                                                    $travelClass
     *
     * @throws \Kerox\Messenger\Exception\MessengerException
     */
    public function __construct(
        string $connectionId,
        string $segmentId,
        string $flightNumber,
        Airport $departureAirport,
        Airport $arrivalAirport,
        FlightSchedule $flightSchedule,
        string $travelClass
    ) {
        parent::__construct($flightNumber, $departureAirport, $arrivalAirport, $flightSchedule);

        $this->isValidTravelClass($travelClass);

        $this->connectionId = $connectionId;
        $this->segmentId = $segmentId;
        $this->travelClass = $travelClass;
    }

    /**
     * @param string                                                                    $connectionId
     * @param string                                                                    $segmentId
     * @param string                                                                    $flightNumber
     * @param \Kerox\Messenger\Model\Message\Attachment\Template\Airline\Airport        $departureAirport
     * @param \Kerox\Messenger\Model\Message\Attachment\Template\Airline\Airport        $arrivalAirport
     * @param \Kerox\Messenger\Model\Message\Attachment\Template\Airline\FlightSchedule $flightSchedule
     * @param string                                                                    $travelClass
     *
     * @throws \Kerox\Messenger\Exception\MessengerException
     *
     * @return \Kerox\Messenger\Model\Message\Attachment\Template\Airline\ExtendedFlightInfo
     */
    public static function create(
        string $connectionId,
        string $segmentId,
        string $flightNumber,
        Airport $departureAirport,
        Airport $arrivalAirport,
        FlightSchedule $flightSchedule,
        string $travelClass
    ): self {
        return new self(
            $connectionId,
            $segmentId,
            $flightNumber,
            $departureAirport,
            $arrivalAirport,
            $flightSchedule,
            $travelClass
        );
    }

    /**
     * @param string $aircraftType
     *
     * @return ExtendedFlightInfo
     */
    public function setAircraftType(string $aircraftType): self
    {
        $this->aircraftType = $aircraftType;

        return $this;
    }

    /**
     * @param string $travelClass
     *
     * @throws \Kerox\Messenger\Exception\MessengerException
     */
    public function isValidTravelClass(string $travelClass): void
    {
        $allowedTravelClass = $this->getAllowedTravelClass();
        if (!\in_array($travelClass, $allowedTravelClass, true)) {
            throw new InvalidKeyException(sprintf(
                'travelClass must be either "%s".',
                implode(', ', $allowedTravelClass)
            ));
        }
    }

    /**
     * @return array
     */
    public function getAllowedTravelClass(): array
    {
        return [
            self::ECONOMY,
            self::BUSINESS,
            self::FIRST_CLASS,
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = [
            'connection_id' => $this->connectionId,
            'segment_id' => $this->segmentId,
            'flight_number' => $this->flightNumber,
            'aircraft_type' => $this->aircraftType,
            'travel_class' => $this->travelClass,
            'departure_airport' => $this->departureAirport,
            'arrival_airport' => $this->arrivalAirport,
            'flight_schedule' => $this->flightSchedule,
        ];

        return array_filter($array);
    }
}
