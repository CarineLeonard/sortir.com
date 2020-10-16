<?php

namespace App\Controller\Api;

use App\Entity\Lieu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/lieu", name="api_lieu_")
 */
class LieuApiController extends AbstractController
{
    /**
     * @Route("/get/{id}", name="get", requirements={"id"="\d+"}, methods="GET", options={"expose"=true})
     */
    public function getLieu(SerializerInterface $serializer, $id)
    {
        $lieuRepo = $this->getDoctrine()->getRepository(Lieu::class);
        $lieu = $lieuRepo->find($id);

        if($lieu)
        {
            $jsonContent = $serializer->serialize($lieu, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['id', 'lieux']]);

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
