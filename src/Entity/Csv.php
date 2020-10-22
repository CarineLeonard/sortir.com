<?php

namespace App\Entity;


class Csv
{
    private $id;

    private $csvFileName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCsvFileName(): ?string
    {
        return $this->csvFileName;
    }

    public function setCsvFileName(?string $csvFileName): self
    {
        $this->csvFileName = $csvFileName;

        return $this;
    }
}
