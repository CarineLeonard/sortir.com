<?php

namespace App\Service;


use App\Entity\Csv;
use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;

class CSVReader
{
    public function ReadCSV( $csvfileName, EntityManagerInterface $em) {
        //dump($csvfileName);

        $dataArray = [];

        if (($h = fopen("{$csvfileName}", "r")) !== FALSE)
        {
            // Each line in the file is converted into an individual array that we call $data
            // The items of the array are comma separated
            while (($data = fgetcsv($h, 1000, ",")) !== FALSE)
            {
                // Each individual array is being pushed into the nested array
                $dataArray[] = $data;
            }
            // Close the file
            fclose($h);
        }

        dump($dataArray);

        // $data2 = $this->csv_to_array($csvfileName, ',');
        //dump($data2);

        foreach ($data2 as $ligne) {
            $participant = new Participant();
            $participant->setNom($ligne['nom']);
            $participant->setPrenom($ligne['prenom']);
            $participant->setTelephone(['telephone']);
            $participant->setMotPasse(['motPasse']);
            $participant->setAdministrateur(['administrateur']);
            $participant->setActif(['actif']);
            $participant->setCampus(['campus']);
            $participant->setPseudo(['pseudo']);

            $em->persist($participant);
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
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }

}
