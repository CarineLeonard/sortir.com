<?php

namespace App\Service;


use App\Entity\Campus;
use App\Entity\Csv;
use App\Entity\Participant;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;

class CSVReader
{
    public function ReadCSV( Csv  $csv, EntityManagerInterface $em) {
        $csvfileName = $csv->getCsvFileName();
        dump($csvfileName);

        $data2 = $this->csv_to_array($csvfileName, ',');
        dump($data2);

        $campus = $em->getRepository(Campus::class)->findAll();
        $newCampus = [];
        foreach ($campus as $camp) {
            $newCampus[$camp->getNom()]=$camp;
        }

            foreach ($data2 as $ligne) {
                $participant = new Participant();
                $participant->setNom($ligne['nom']);
                $participant->setPrenom($ligne['prenom']);
                $participant->setTelephone($ligne['telephone']);
                $participant->setMail($ligne['mail']);
                $participant->setMotPasse($ligne['motPasse']);
                $participant->setAdministrateur($ligne['administrateur']);
                $participant->setActif($ligne['actif']);
                $participant->setCampus($newCampus[$ligne['campus']]);
                $participant->setPseudo($ligne['pseudo']);

                $em->persist($participant);
                dump($participant);

            }

        $em->flush();

    }

    function csv_to_array($filename='', $delimiter=',')
    {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                if(!$header)                                                            // pb si pas avec header !
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }

}
