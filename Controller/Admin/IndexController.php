<?php


declare(strict_types=1);

namespace BaksDev\Manufacture\Part\Application\Controller\Admin;


use BaksDev\Centrifugo\Services\Token\TokenUserGenerator;
use BaksDev\Core\Form\Search\SearchDTO;
use BaksDev\Core\Form\Search\SearchForm;
use BaksDev\Manufacture\Part\Application\Repository\ActionByMain\ActionByMainInterface;
use BaksDev\Manufacture\Part\Application\Repository\AllManufacturePartApplication\AllManufacturePartApplicationInterface;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use BaksDev\Manufacture\Part\Repository\AllProducts\AllManufactureProductsInterface;
use BaksDev\Manufacture\Part\Repository\ManufacturePartChoice\ManufacturePartChoiceInterface;
use BaksDev\Manufacture\Part\UseCase\Admin\AddProduct\ManufactureSelectionPartProductsForm;
use BaksDev\Products\Product\Forms\ProductFilter\Admin\ProductFilterDTO;
use BaksDev\Products\Product\Forms\ProductFilter\Admin\ProductFilterForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BaksDev\Core\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
#[RoleSecurity('ROLE_MANUFACTURE_PART')]
final class IndexController extends AbstractController
{
    #[Route('/admin/manufacture/application/{page<\d+>}', name: 'admin.index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
//        AllManufactureProductsInterface $allManufactureProducts,
        AllManufacturePartApplicationInterface $allManufacturePartApplication,
        TokenUserGenerator $tokenUserGenerator,
        int $page = 0,
    ): Response
    {

        // Поиск
        $search = new SearchDTO();
        $searchForm = $this->createForm(SearchForm::class, $search);
        $searchForm->handleRequest($request);

        // TODO !!!
        // ПЕРЕДЕЛАТЬ на список заявок

//        dd(1);


        // Фильтр
        // $filter = new ProductsStocksFilterDTO($request, $ROLE_ADMIN ? null : $this->getProfileUid());
        // $filterForm = $this->createForm(ProductsStocksFilterForm::class, $filter);
        // $filterForm->handleRequest($request);



        /**
         * Фильтр продукции
         */
//        $filter = new ProductFilterDTO()->hiddenMaterials();
//
//        // TODO
//         $filterForm = $this
//             ->createForm(
//                 type: ProductFilterForm::class,
//                 data: $filter,
//                 options: ['action' => $this->generateUrl('manufacture-part:admin.index')],
//             )
//             ->handleRequest($request);

        // Получаем список
        /**
         * Список продукции
         */
//        $query = $allManufactureProducts
//            ->search($search)
//            ->filter($filter)
////            ->forDeliveryType($opens ? $opens->getComplete() : false)
//            ->findPaginator();
        $query = $allManufacturePartApplication
            ->findAll();

        return $this->render(
            [
                'query' => $query, //$ManufacturePart,
                'search' => $searchForm->createView(),
//                'filter' => $filterForm->createView(),
                'current_profile' => $this->getCurrentProfileUid(),
                'token' => $tokenUserGenerator->generate($this->getUsr()),
                // todo
//                'add_selected_product_form_name' => $this->createForm(type: ManufactureSelectionPartProductsForm::class)->getName(),
            ]
        );
    }
}
