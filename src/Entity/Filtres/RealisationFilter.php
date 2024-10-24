<?php 

namespace App\Entity\Filtres;

use App\Entity\Categorie;

class RealisationFilter {
    private ?string $query = '';

    private ?Categorie $categorie = null;

    private ?int $page = 1;

    private ?int $limit = 10;

    /**
     * Get the value of query
     *
     * @return ?string
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * Set the value of query
     *
     * @param ?string $query
     *
     * @return self
     */
    public function setQuery(?string $query): self
    {
        $this->query = $query;

        return $this;
    }
    
    /**
     * Get the value of page
     *
     * @return ?int
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * Set the value of page
     *
     * @param ?int $page
     *
     * @return self
     */
    public function setPage(?int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the value of limit
     *
     * @return ?int
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * Set the value of limit
     *
     * @param ?int $limit
     *
     * @return self
     */
    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }


    /**
     * Get the value of categorie
     *
     * @return ?Categorie
     */
    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    /**
     * Set the value of categorie
     *
     * @param ?Categorie $categorie
     *
     * @return self
     */
    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}