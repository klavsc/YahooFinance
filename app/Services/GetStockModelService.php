<?php


namespace App\Services;

use App\Repository\StockRepository;
use App\Models\Stock;
use Scheb\YahooFinanceApi\ApiClient;
use Scheb\YahooFinanceApi\ApiClientFactory;
use Carbon\Carbon;

class GetStockModelService
{
    protected StockRepository $stockRepository;

    public function __construct()
    {
        $this->stockRepository = new StockRepository();
    }

    public function getModel(string $symbol): Stock
    {

        $dbData = $this->stockRepository->getBySymbol($symbol);
        $dt = (Carbon::create($dbData['created_at']));


        if (empty($dbData) || ($dt->diffInMinutes(Carbon::now()->subMinutes(10)->format('Y-m-d H:i:s')))) {
            $client = ApiClientFactory::createApiClient();

            $searchResult = $client->search($symbol)[0];
            $quote = $client->getQuote($symbol);

            $stock = new Stock(
                $searchResult->getSymbol(),
                $searchResult->getName(),
                $quote->getRegularMarketOpen(),
                $quote->getRegularMarketPreviousClose(),
                $quote->getBid(),
                $quote->getRegularMarketVolume(),
                Carbon::now()->format('Y-m-d H:i:s')
            );

            (new StoreStockService)->store($stock);
        } else {
            $stock = new Stock(
                $dbData['symbol'],
                $dbData['name'],
                (float)$dbData['open'],
                (float)$dbData['close'],
                (float)$dbData['bid'],
                (int)$dbData['volume'],
                $dt
            );
        }

        return $stock;
    }
}