<?php

namespace App\Entity\Filtres;

class UserFilter
{
    private ?string $query = '';

    private ?string $roles = '';

    private ?bool $isVerified = null;

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
     * Get the value of roles
     *
     * @return ?string
     */
    public function getRoles(): ?string
    {
        return $this->roles;
    }

    /**
     * Set the value of roles
     *
     * @param ?string $roles
     *
     * @return self
     */
    public function setRoles(?string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of isVerified
     *
     * @return ?bool
     */
    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    /**
     * Set the value of isVerified
     *
     * @param ?bool $isVerified
     *
     * @return self
     */
    public function setIsVerified(?bool $isVerified): self
    {
        $this->isVerified = $isVerified;

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