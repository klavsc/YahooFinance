<?php

namespace App\Services;

use App\Models\Stock;
use App\Repository\StockRepository;

class StoreStockService
{
    private StockRepository $stockRepository;

    public function __construct()
    {
        $this->stockRepository = new StockRepository();
    }

    public function store(Stock $stock)
    {
        $stockQuery = $this->stockRepository->getBySymbol($stock->symbol());

        if(empty($stockQuery)) {
            $this->stockRepository->save($stock);
        }else {
            $this->stockRepository->update($stock);
        }
    }
}