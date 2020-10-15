<?php
namespace App\Data;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Component\Validator\Constraints as Assert;

class SearchData{

    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Campus[]
     */
    public $campus = [];

    /**
     * @Assert\DateTime
     * @var string A "d-m-Y H:i" formatted value
     */
    public $dateMax;

    /**
     * @Assert\DateTime
     * @var string A "d-m-Y H:i" formatted value
     */
    public $dateMin;

    /**
     * @var boolean
     */
    public $organisateur = true;

    /**
     * @var boolean
     */
    public $inscrit = true;

    /**
     * @var boolean
     */
    public $nonInscrit = true;

    /**
     * @var boolean
     */
    public $fini = true;


}
