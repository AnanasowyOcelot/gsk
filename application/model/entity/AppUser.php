<?php

/**
 * @Entity @Table(name="app_users")
 * @HasLifecycleCallbacks
 **/
class Model_Entity_AppUser
{
    /** @Id @Column(type="integer") @GeneratedValue */
    protected $id;

    /** @Column(type="string") */
    protected $name = '';

    /**
     * @ManyToOne(targetEntity="Model_Entity_AppUser")
     * @JoinColumn(name="supervisor_id", referencedColumnName="id")
     */
    public $supervisor;

    /** @Column(type="string") */
    protected $active = 0;

    /** @var \DateTime
     * @Column(type="datetime", name="data_utworzenia", nullable=true) */
    protected $dataUtworzenia;

    /** @var \DateTime
     * @Column(type="datetime", name="data_aktualizacji", nullable=true) */
    protected $dataAktualizacji;

    /**
     * @PrePersist
     * @PreUpdate
     */
    public function updateTimestamps()
    {
        if ($this->getDataUtworzenia() == null) {
            $this->dataUtworzenia = new \DateTime('now');
        }
        $this->dataAktualizacji = new \DateTime('now');
    }

    public function getId()
    {
        return $this->id;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function getDataAktualizacji()
    {
        if($this->dataAktualizacji) {
            return $this->dataAktualizacji->format('Y-m-d G:i:s');
        }
        return '';
    }

    public function getDataUtworzenia()
    {
        if($this->dataUtworzenia) {
            return $this->dataUtworzenia->format('Y-m-d G:i:s');
        }
        return '';
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSupervisor($supervisor)
    {
        $this->supervisor = $supervisor;
    }

    public function getSupervisor()
    {
        return $this->supervisor;
    }
}
