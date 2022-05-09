<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email', message:'Cet adresse email existe déjà !')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\Column(type: 'string', length: 255)]
    private $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Message::class, orphanRemoval: true)]
    private $messages;


    #[ORM\OneToMany(mappedBy: 'expediteur', targetEntity: MessagePrive::class)]
    private $sent;

    #[ORM\OneToMany(mappedBy: 'destinataire', targetEntity: MessagePrive::class)]
    private $received;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $session_update;

   


    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->sent = new ArrayCollection();
        $this->received = new ArrayCollection();
        
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {
        
    }

    public function getSalt()
    {
        
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

   

    /**
     * @return Collection<int, MessagePrive>
     */
    public function getSent(): Collection
    {
        return $this->sent;
    }

    public function addSent(MessagePrive $sent): self
    {
        if (!$this->sent->contains($sent)) {
            $this->sent[] = $sent;
            $sent->setExpediteur($this);
        }

        return $this;
    }

    public function removeSent(MessagePrive $sent): self
    {
        if ($this->sent->removeElement($sent)) {
            // set the owning side to null (unless already changed)
            if ($sent->getExpediteur() === $this) {
                $sent->setExpediteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MessagePrive>
     */
    public function getReceived(): Collection
    {
        return $this->received;
    }

    public function addReceived(MessagePrive $received): self
    {
        if (!$this->received->contains($received)) {
            $this->received[] = $received;
            $received->setDestinataire($this);
        }

        return $this;
    }

    public function removeReceived(MessagePrive $received): self
    {
        if ($this->received->removeElement($received)) {
            // set the owning side to null (unless already changed)
            if ($received->getDestinataire() === $this) {
                $received->setDestinataire(null);
            }
        }

        return $this;
    }



    public function getSessionUpdate(): ?\DateTimeInterface
    {
        return $this->session_update;
    }

    public function setSessionUpdate(?\DateTimeInterface $session_update): self
    {
        $this->session_update = $session_update;

        return $this;
    }


    public function isActive()
    {
        // Délai pendant lequel l'utilisateur est considéré comme actif
        $delai = new \DateTime('5 minutes ago');

        return ($this->getSessionUpdate() > $delai);
    }

   

  

   
}
