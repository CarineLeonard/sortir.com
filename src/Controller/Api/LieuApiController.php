<?php

namespace App\Controller\Api;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\VilleType;
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

        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);

        $data = json_decode($request->getContent(), true);
        $lieuResult = $data;
        $villeResult['nom'] = $lieuResult['ville_nom'];
        $villeResult['codePostal'] = $lieuResult['ville_codePostal'];
        $villeResult['_token'] = $lieuResult['ville__token'];
        unset($lieuResult['ville_nom']);
        unset($lieuResult['ville_codePostal']);
        unset($lieuResult['ville__token']);

        dump($lieuResult);
        dump($villeResult);

        if (!$lieuResult['ville'])
        {
            $villeForm->submit($villeResult);

            if ($villeForm->isValid())
            {
                $em->persist($ville);
                $em->flush();
                $status = 'OK';
                $message = 'Ville créé';
                $code = Response::HTTP_CREATED;

                $lieuResult['ville'] = $ville->getIdVille();
            }
            else
            {
                $status = 'KO';
                $message = [];
                foreach ($villeForm->getErrors(true, false) as $error)
                {
                    $message[] = [
                        'attr' => $error,
                        'msg' => $error,
                    ];
                }
                $code = Response::HTTP_CREATED;
            }
        }

        $lieuForm->submit($lieuResult);

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
                'form' => $this->render('lieu/nouveau.html.twig', [
                    'lieuForm' => $lieuForm->createView(),
                    'villeForm' => $villeForm->createView(),
                ])->getContent(),
                'data' => $data
            ],
        ];

        $json = $serializer->serialize($result, 'json', [AbstractNormalizer::ATTRIBUTES => ['id', 'nom']]);
        $response = new JsonResponse();
        $response->setContent($json)->setStatusCode($code);

        return $response;
    }
}
