<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $balance = null;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Expense::class)]
    private Collection $expenses;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Revenue::class)]
    private Collection $revenues;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeAccount $accountType = null;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
        $this->revenues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(?float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): self
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setAccount($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): self
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getAccount() === $this) {
                $expense->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Revenue>
     */
    public function getRevenues(): Collection
    {
        return $this->revenues;
    }

    public function addRevenue(Revenue $revenue): self
    {
        if (!$this->revenues->contains($revenue)) {
            $this->revenues->add($revenue);
            $revenue->setAccount($this);
        }

        return $this;
    }

    public function removeRevenue(Revenue $revenue): self
    {
        if ($this->revenues->removeElement($revenue)) {
            // set the owning side to null (unless already changed)
            if ($revenue->getAccount() === $this) {
                $revenue->setAccount(null);
            }
        }

        return $this;
    }

    public function getAccountType(): ?TypeAccount
    {
        return $this->accountType;
    }

    public function setAccountType(?TypeAccount $accountType): self
    {
        $this->accountType = $accountType;

        return $this;
    }
}
