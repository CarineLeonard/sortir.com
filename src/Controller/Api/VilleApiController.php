<?php

namespace App\Controller\Api;

use App\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/ville", name="api_ville_")
 */
class VilleApiController extends AbstractController
{
    /**
     * @Route("/get/lieux/{id}", name="get_lieu", requirements={"id"="\d+"}, methods="GET", options={"expose"=true})
     */
    public function getLieuxByVilleId(SerializerInterface $serializer, $id)
    {
        $villeRepo = $this->getDoctrine()->getRepository(Ville::class);
        $ville = $villeRepo->find($id);

        if($ville)
        {
            $lieux = $ville->getLieux();
            $jsonContent = $serializer->serialize($lieux, 'json', [AbstractNormalizer::ATTRIBUTES => ['id', 'nom']]);

            $response = new Response($jsonContent);
            $response->headers->set('Content-Type', 'application/json');
        }
        else{
            $response = new Response();
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $response;
    }
}
