<?php

namespace App\Application\Series\Controller;


use App\Api\BetaseriesApi\Provider\SeriesProvider;
use App\Application\Common\Controller\BaseController;
use App\Application\Common\Repository\FavoriteRepository;
use App\Application\Common\Repository\SerieRepository;
use App\Application\Helpers\Paginator;
use App\Application\Search\Entity\Search;
use App\Application\Series\DTO\SerieDTOByApiBuilder;
use App\Application\Series\Factory\SerieFactory;
use App\Application\Series\Manager\serieManager;
use App\Application\Search\Form\SearchType;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SeriesController
 * @package App\Application\Series\Controller
 */
class SeriesController extends BaseController
{
    const Home = "home";
    /**
     * @var SeriesProvider
     */
    private $seriesProvider;
    /**
     * @var SerieDTOByApiBuilder
     */
    private $serieDTOByApiBuilder;
    /**
     * @var SerieFactory
     */
    private $serieFactory;
    /**
     * @var serieManager
     */
    private $serieManager;
    /**
     * @var FavoriteRepository
     */
    private $favoriteRepository;
    /**
     * @var SerieRepository
     */
    private $serieRepository;
    /**
     * @var PaginationInterface
     */
    private $paginator;

    public function __construct(
        SeriesProvider $seriesProvider,
        SerieDTOByApiBuilder $serieDTOByApiBuilder,
        SerieFactory $serieFactory,
        serieManager $serieManager,
        FavoriteRepository $favoriteRepository,
        SerieRepository $serieRepository,
        Paginator $paginator
    ) {
        $this->seriesProvider = $seriesProvider;
        $this->serieDTOByApiBuilder = $serieDTOByApiBuilder;
        $this->serieFactory = $serieFactory;
        $this->serieManager = $serieManager;
        $this->favoriteRepository = $favoriteRepository;
        $this->serieRepository = $serieRepository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="home.index")
     * @param Request $request
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function index(Request $request): Response
    {
        $form = $this->handleForm($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData();

            return $this->redirectToRoute('serie.search', [
                'search' => $search->getName()
            ]);
        }
        $betaseries = $this->seriesProvider->provideMostPopularSeries();

        $series = $this->paginator->paginateSeries($betaseries, $request, SerieDTOByApiBuilder::Index);

        return $this->render('pages/home.html.twig', [
            'series' => $series,
            'current_menu' => self::Home,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/serie/show/{id}", name="serie.show")
     * @param string $id
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function show(string $id): Response
    {
        $serie = $this->seriesProvider->provideSerieBy($id);
        $serie = $this->serieDTOByApiBuilder->switchAndBuildBetaserieInfo($serie, SerieDTOByApiBuilder::Index);

        return $this->render('serie/_serie.html.twig', [
            'serie' => $serie
        ]);
    }

    /**
     * @Route("/serie/add/{id}", name="serie.add")
     * @param string $id
     * @return Void
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function add(string $id): Void
    {
        $serieInfo = $this->seriesProvider->provideSerieBy($id);
        $serie = $this->serieFactory->buildByApi($serieInfo);

        $this->serieManager->saveSerie($serie);
    }

    /**
     * @Route("/serie/delete/{id}", name="serie.delete")
     * @param string $id
     * @return Void
     */
    public function delete(string $id): Void
    {
        $this->serieManager->deleteSerie($id);
    }

    /**
     * @Route("/serie/search/{search}", name="serie.search")
     * @param string $search
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function search(string $search, Request $request): Response
    {
        $form = $this->handleForm($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData();

            return $this->redirectToRoute('serie.search', [
                'search' => $search->getName()
            ]);
        }

        $betaseries = $this->seriesProvider->searchSerie($search);
        $series = $this->paginator->paginateSeries($betaseries, $request, SerieDTOByApiBuilder::Search);

        return $this->render('pages/home.html.twig', [
            'series' => $series,
            'search' => $search,
            'current_menu' => self::Home,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function handleForm(Request $request)
    {
        $newSearch = new Search();
        $form = $this->createForm(SearchType::class, $newSearch);
        $form->handleRequest($request);

        return $form;
    }
}