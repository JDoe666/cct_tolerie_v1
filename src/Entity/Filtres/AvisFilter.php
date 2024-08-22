<?php 

namespace App\Entity\Filtres;

class AvisFilter {
    
    private ?string $query = '';

    private ?int $page = 1;

    private ?int $limit = 10;

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
}