<?php

declare(strict_types=1);

namespace PickIt\Entities;

class TrackingStatus
{
    private string $title;
    private string $description;
    private \DateTime $date;
    private ?int $pointId;
    private string $marker;
    private string $color;

    public function __construct(
        string $title,
        string $description,
        \DateTime $date,
        ?int $pointId,
        string $marker,
        string $color
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->pointId = $pointId;
        $this->marker = $marker;
        $this->color = $color;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getPointId(): ?int
    {
        return $this->pointId;
    }

    public function getMarker(): string
    {
        return $this->marker;
    }

    public function getColor(): string
    {
        return $this->color;
    }
}
