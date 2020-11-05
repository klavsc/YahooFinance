<?php

namespace App\Models;

class Stock
{
    private string $symbol;
    private string $name;
    private float $open;
    private float $close;
    private float $bid;
    private int $volume;
    private ?string $createdAt;

    public function __construct
    (
        string $symbol,
        string $name,
        float $open,
        float $close,
        float $bid,
        int $volume,
        string $createdAt = null
    )
    {
        $this->symbol = $symbol;
        $this->name = $name;
        $this->open = $open;
        $this->close = $close;
        $this->volume = $volume;
        $this->createdAt = $createdAt;
        $this->bid = $bid;
    }

    public function symbol(): string
    {
        return $this->symbol;
    }

    public function name(): string
    {
        return $this->name;
    }
    public function bid(): float
    {
        return $this->bid;
    }


    public function open(): float
    {
        return $this->open;
    }

    public function close(): float
    {
        return $this->close;
    }

    public function volume(): int
    {
        return $this->volume;
    }

    public function createdAt(): string
    {
        return $this->createdAt;
    }



}