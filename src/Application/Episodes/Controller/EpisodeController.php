<?php

namespace App\Application\Episodes\Controller;


use App\Api\BetaseriesApi\Provider\EpisodeByApiProvider;
use App\Api\BetaseriesApi\Provider\SerieByApiProvider;
use App\Application\Common\Controller\BaseController;
use App\Application\Common\Repository\FavoriteRepository;
use App\Application\Episodes\Helpers\EpisodeHelper;
use App\Application\Search\Controller\SearchController;
use App\Application\Episodes\DTO\EpisodeDTOBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class EpisodeController
 * @package App\Application\Episodes
 */
class EpisodeController extends BaseController
{
    /**
     * @var SearchController
     */
    private $searchController;
    /**
     * @var EpisodeByApiProvider
     */
    private $episodeByApiProvider;
    /**
     * @var EpisodeDTOBuilder
     */
    private $episodeDTOBuilder;
    /**
     * @var SerieByApiProvider
     */
    private $serieByApiProvider;
    /**
     * @var FavoriteRepository
     */
    private $favoriteRepository;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        SearchController $searchController,
        EpisodeByApiProvider $episodeByApiProvider,
        EpisodeDTOBuilder $episodeDTOBuilder,
        SerieByApiProvider $serieByApiProvider,
        FavoriteRepository $favoriteRepository,
        TranslatorInterface $translator
    )
    {
        $this->searchController = $searchController;
        $this->episodeByApiProvider = $episodeByApiProvider;
        $this->episodeDTOBuilder = $episodeDTOBuilder;
        $this->serieByApiProvider = $serieByApiProvider;
        $this->favoriteRepository = $favoriteRepository;
        $this->translator = $translator;
    }

    /**
     * @Route("/episode/{episodeNumber}/show/{serieId}/{seasonNumber}", name="episode.show")
     * @param string $episodeNumber
     * @param string $serieId
     * @param Request $request
     * @param string $seasonNumber
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function show(string $episodeNumber, string $serieId, string $seasonNumber, Request $request): Response
    {
        $form = $this->searchController->handleForm($request);

        $serie = $this->serieByApiProvider->provideSerieByApi($serieId);

        $episode = $this->episodeByApiProvider->provideEpisodeByApi($episodeNumber, $serieId, $seasonNumber);
        $episode = $this->episodeDTOBuilder->build($episode, $serie);

        $episodeSeen = $this->translator->trans('episode.toSee');

        $user = $this->getUser();

        if (!empty($user)) {
            $favorite = $this->favoriteRepository->getFavorite($user, $serieId);
            if (!empty($favorite)) {
                $episodeSeen = $favorite->isEpisodeSeen($episode->getCode())? $this->translator->trans('episode.seen') : $this->translator->trans('episode.toSee');
            }
        }

        return $this->render('pages/show_episode.html.twig', [
            'episode' => $episode,
            'form' => $form->createView(),
            'episodeSeen' => $episodeSeen
        ]);
    }
}