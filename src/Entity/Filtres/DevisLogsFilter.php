<?php 

namespace App\Entity\Filtres;

class DevisLogsFilter {

    private ?string $actions = '';

    private ?int $page = 1;

    private ?int $limit = 10;

    /**
     * Get the value of actions
     *
     * @return ?string
     */
    public function getActions(): ?string
    {
        return $this->actions;
    }

    /**
     * Set the value of actions
     *
     * @param ?string $actions
     *
     * @return self
     */
    public function setActions(?string $actions): self
    {
        $this->actions = $actions;

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
}