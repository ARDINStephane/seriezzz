<?php

namespace App\Api\BetaseriesApi\Provider;


/**
 * Class SerieByApiProvider
 * @package App\Api\BetaseriesApi\Provider
 */
class SerieByApiProvider extends ApiProvider
{
    /**
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function provideMostPopularSeries(): array
    {
        $url = 'https://api.betaseries.com/shows/list?&order=popularity&limit=120';
        $series = $this->curlRequestResults(self::GetMethod, $url);

        return $series['shows'];
    }

    /**
     * @param string $id
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function provideSerieByApi(string $id): array
    {
        $url = 'https://api.betaseries.com/shows/display?id=' .$id;
        $serie = $this->curlRequestResults(self::GetMethod, $url);

        return $serie['show'];
    }

    /**
     * @param string $name
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function searchSerie(string $name): array
    {
        $url = 'https://api.betaseries.com/search/all?query=' . $name . '&limit=24';
        $series = $this->curlRequestResults(self::GetMethod, $url);

        return $series['shows'];
    }
}