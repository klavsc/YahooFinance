<?php

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Dotenv\Dotenv;
use Scheb\YahooFinanceApi\ApiClient;
use Scheb\YahooFinanceApi\ApiClientFactory;

require_once __DIR__ . '/vendor/autoload.php';

// Database Connection
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function database(): Connection
{
    $connectionParams = [
        'dbname' => $_ENV['DB_DATABASE'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'host' => $_ENV['DB_HOST'],
        'driver' => 'pdo_mysql',
    ];

    $connection = DriverManager::getConnection($connectionParams);
    $connection->connect();

    return $connection;
}

function query(): QueryBuilder
{
    return database()->createQueryBuilder();
}

//Router

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $namespace = '\App\Controllers\\';

    $r->addRoute('GET', '/', $namespace . 'StockController@search');
    $r->addRoute('GET', '/show', $namespace . 'StockController@show');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 PAGE NOT FOUND';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo 'METHOD NOT ALLOWED';
        break;
    case FastRoute\Dispatcher::FOUND:
        [$controller, $method] = explode('@', $routeInfo[1]);
        $vars = $routeInfo[2];

        (new $controller)->$method($vars);

        break;
}

// Create a new client from the factory
$client = ApiClientFactory::createApiClient();

// Returns an array of Scheb\YahooFinanceApi\Results\SearchResult
$searchResult = $client->search("Apple");

// Returns an array of Scheb\YahooFinanceApi\Results\HistoricalData
$historicalData = $client->getHistoricalData("AAPL", ApiClient::INTERVAL_1_DAY, new \DateTime("-14 days"), new \DateTime("today"));

// Returns Scheb\YahooFinanceApi\Results\Quote
$exchangeRate = $client->getExchangeRate("USD", "EUR");

// Returns an array of Scheb\YahooFinanceApi\Results\Quote
$exchangeRates = $client->getExchangeRates([
    ["USD", "EUR"],
    ["EUR", "USD"],
]);

// Returns Scheb\YahooFinanceApi\Results\Quote
$quote = $client->getQuote("AAPL");

// Returns an array of Scheb\YahooFinanceApi\Results\Quote
$quotes = $client->getQuotes(["AAPL", "GOOG"]);