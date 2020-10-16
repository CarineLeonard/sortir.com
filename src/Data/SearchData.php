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
     * @var \DateTime
     */
    public $dateMax;

    /**
     * @var \DateTime
     */
    public $dateMin;

    /**
     * @var boolean
     */
    public $organisateur = false;

    /**
     * @var boolean
     */
    public $inscrit = false;

    /**
     * @var boolean
     */
    public $nonInscrit = false;

    /**
     * @var boolean
     */
    public $fini = false;


}
