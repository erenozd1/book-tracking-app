<?php

namespace App\Controller;

use App\Repository\ContributorRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AnnotationsasRest;
use FOS\RestBundle\Controller\FOSRestController;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 *ContributorController.
 *@Route("/api",name="api_")
 */
class ContributorController extends AbstractController
{
    /**
     * List the contributors
     *
     *
     * @Rest\Get("/contributors")
     * @OA\Response(
     *     response=200,
     *     description="Returns list of contributors",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Contributor::class, groups={"full"}))
     *     )
     * )
     * @OA\Tag(name="Contributors")
     * @Security(name="Bearer")
     */
    public function getContributors(ContributorRepository $contributorRepository)
    {
        $result = $contributorRepository->get();
        return $result;
    }

    /**
     * @Rest\Post ("/contributor")
     * @OA\Response(
     *     response=200,
     *     description="Returns added of contributor",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Contributor::class, groups={"full"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="name",
     *     in="query",
     *     description="The field name of contributor",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="surname",
     *     in="query",
     *     description="The field surname of contributor",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="Contributors")
     * @Security(name="Bearer")
     * @param ContributorRepository $contributorRepository
     * @param Request $request
     */
    public function newContributor(ContributorRepository $contributorRepository,Request $request)
    {
        $params = $request->query->all();
        $result = $contributorRepository->newContributor($params);

        return $result;

    }
}
