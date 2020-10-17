<?php

namespace App\Controller\Api;

use App\Entity\Lieu;
use App\Form\LieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $result = [
            'status' => $lieu ? 'OK' : 'KO',
            'message' => $lieu ? 'Lieu trouvé' : 'Lieu inconnu',
            'data' => $lieu,
        ];
        $code = $lieu ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
        $json = $serializer->serialize($result, 'json', [AbstractNormalizer::GROUPS  => ['lieu']]);
        $response = new JsonResponse();
        $response->setContent($json)->setStatusCode($code);
        return $response;
    }

    /**
     * @Route("/nouveau", name="nouveau", methods="POST", options={"expose"=true})
     */
    public function nouveauLieu(SerializerInterface $serializer, Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $data = json_decode($request->getContent(), true);
        $lieuForm->submit($data);

        if ($lieuForm->isValid())
        {
            $em->persist($lieu);
            $em->flush();
            $status = 'OK';
            $message = 'Lieu créé';
            $code = Response::HTTP_CREATED;
        }
        else
        {
            $status = 'KO';
            $message = [];
            foreach ($lieuForm->getErrors(true, false) as $error)
            {
                $message[] = [
                    'attr' => $error,
                    'msg' => $error,
                ];
            }
            $code = Response::HTTP_CREATED;
        }
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => [
                'lieu' => $lieu,
                'form' => $this->render('lieu/nouveau.html.twig', ['lieuForm' => $lieuForm->createView()])->getContent()
            ],
        ];

        $json = $serializer->serialize($result, 'json', [AbstractNormalizer::ATTRIBUTES => ['id', 'nom']]);
        $response = new JsonResponse();
        $response->setContent($json)->setStatusCode($code);

        return $response;
    }
}
