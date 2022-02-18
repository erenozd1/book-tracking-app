<?php

namespace App\Controller;

use App\Entity\Contributor;
use App\Repository\ContributorRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AnnotationsasRest;
use FOS\RestBundle\Controller\FOSRestController;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 *ContributorController.
 *@Route("/api",name="api_")
 */
class ContributorController extends AbstractFOSRestController
{
    /**
     * List of contributors
     * @Rest\Get("/contributor")
     * @OA\Tag(name="Contributors")
     */
    public function getContributors(ContributorRepository $contributorRepository)
    {
        return $contributorRepository->get();
    }

    /**
     * Add a new contributor
     * @Rest\Post("/contributor")
     * @OA\RequestBody(
     *     required=true,
     *     @Model(type=Contributor::class, groups={"contributorBody"})
     * )
     * @OA\Tag(name="Contributors")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function newContributor(ContributorRepository $contributorRepository,Request $request)
    {
        $params = json_decode($request->getContent());
        $result = $contributorRepository->newContributor($params);

        return $result;

    }

    /**
     * Update a  contributor
     * @Rest\Put ("/contributor")
     * @OA\RequestBody(
     *     required=true,
     *     @Model(type=Contributor::class, groups={"contributorBody","contributorId"})
     * )
     * @OA\Tag(name="Contributors")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function updateContributor(ContributorRepository $contributorRepository,Request $request)
    {
        $params = json_decode($request->getContent());
        $result = $contributorRepository->updateContributor($params);

        return $result;
    }

    /**
     * Delete a  contributor
     * @Rest\Delete ("/contributor")
     * @OA\RequestBody(
     *     required=true,
     *     @Model(type=Contributor::class, groups={"contributorId"})
     * )
     * @OA\Tag(name="Contributors")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function deleteContributor(ContributorRepository $contributorRepository,Request $request)
    {
        $params = json_decode($request->getContent());
        $result = $contributorRepository->deleteContributor($params);

        return $result;
    }
}
