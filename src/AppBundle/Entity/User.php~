<?php

// src/AppBundle/Entity/User.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User implements UserInterface, \Serializable
{
       
	 /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

     /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     */
    protected $username;

     /**
     * @ORM\Column(type="string", length=100)
     */
    protected $password;

     /**
     * @ORM\Column(name="FirstName", type="string", length=50)
     * @Assert\NotBlank()
     */
    protected $firstName;

     /**
     * @ORM\Column(name="LastName", type="string", length=50)
     * @Assert\NotBlank()
     */
    protected $lastName;
    
    /**
     * @ORM\Column(name="EnglishName", unique=true)
     * @Assert\NotBlank()
     */
    protected $englishName;    
    
    /**
     * @ORM\Column(name="HomePhone", type="string", length=45)
     */
    protected $homePhone; 
    
    /**
     * @ORM\Column(name="MobilePhone", type="string", length=45)
     */
    protected $mobilePhone;
    

    /**
     * @ORM\Column(name="DOB", type="date", length=50)
     */
    protected $dob;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Email
     */
    protected $email;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="User_Role",
     *      joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="roleId", referencedColumnName="id")}
     *      )
     **/
    protected $roles;
    
    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="User_Parent",
     *      joinColumns={@ORM\JoinColumn(name="UserId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="ParentId", referencedColumnName="id")}
     *      )
     **/
    protected $parents;  
    
    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="User_Parent",
     *      joinColumns={@ORM\JoinColumn(name="ParentId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="UserId", referencedColumnName="id")}
     *      )
     **/
    protected $students;    

    /**
     * @ORM\ManyToMany(targetEntity="ClassOfStudents")
     * @ORM\JoinTable(name="Class_Student",
     *      joinColumns={@ORM\JoinColumn(name="StudentId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="ClassId", referencedColumnName="id")}
     *      )
     **/
    protected $classes;

    /**
     * @ORM\Column(name="Active", type="boolean")
     */
    protected $active;     

     /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;
    
    /** 
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="teacher")
     **/
    protected $lessons;
    

    public function __toString() {
        return $this->lastName.' '.$this->firstName;
    }



    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized);
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     * @return User
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime 
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add roles
     *
     * @param \AppBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\AppBundle\Entity\Role $role)
    {
        $this->roles->add($role);

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \AppBundle\Entity\Role $roles
     */
    public function removeRole(\AppBundle\Entity\Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        #print $this->roles->toArray();
        return $this->roles->toArray();
    }

    /**
     * Add classes
     *
     * @param \AppBundle\Entity\ClassOfStudents $classes
     * @return User
     */
    public function addClass(\AppBundle\Entity\ClassOfStudents $classes)
    {
        $this->classes[] = $classes;

        return $this;
    }

    /**
     * Remove classes
     *
     * @param \AppBundle\Entity\ClassOfStudents $classes
     */
    public function removeClass(\AppBundle\Entity\ClassOfStudents $classes)
    {
        $this->classes->removeElement($classes);
    }

    /**
     * Get classes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set homePhone
     *
     * @param string $homePhone
     * @return User
     */
    public function setHomePhone($homePhone)
    {
        $this->homePhone = $homePhone;

        return $this;
    }

    /**
     * Get homePhone
     *
     * @return string 
     */
    public function getHomePhone()
    {
        return $this->homePhone;
    }

    /**
     * Set mobilePhone
     *
     * @param string $mobilePhone
     * @return User
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    /**
     * Get mobilePhone
     *
     * @return string 
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * Add lessons
     *
     * @param \AppBundle\Entity\Lesson $lessons
     * @return User
     */
    public function addLesson(\AppBundle\Entity\Lesson $lessons)
    {
        $this->lessons[] = $lessons;

        return $this;
    }

    /**
     * Remove lessons
     *
     * @param \AppBundle\Entity\Lesson $lessons
     */
    public function removeLesson(\AppBundle\Entity\Lesson $lessons)
    {
        $this->lessons->removeElement($lessons);
    }

    /**
     * Get lessons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLessons()
    {
        return $this->lessons;
    }

    /**
     * Add parents
     *
     * @param \AppBundle\Entity\User $parents
     * @return User
     */
    public function addParent(\AppBundle\Entity\User $parents)
    {
        $this->parents[] = $parents;

        return $this;
    }

    /**
     * Remove parents
     *
     * @param \AppBundle\Entity\User $parents
     */
    public function removeParent(\AppBundle\Entity\User $parents)
    {
        $this->parents->removeElement($parents);
    }

    /**
     * Get parents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Add students
     *
     * @param \AppBundle\Entity\User $students
     * @return User
     */
    public function addStudent(\AppBundle\Entity\User $students)
    {
        $this->students[] = $students;

        return $this;
    }

    /**
     * Remove students
     *
     * @param \AppBundle\Entity\User $students
     */
    public function removeStudent(\AppBundle\Entity\User $students)
    {
        $this->students->removeElement($students);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStudents()
    {
        return $this->students;
    }


    /**
     * Set englishName
     *
     * @param string $englishName
     * @return User
     */
    public function setEnglishName($englishName)
    {
        $this->englishName = $englishName;

        return $this;
    }

    /**
     * Get englishName
     *
     * @return string 
     */
    public function getEnglishName()
    {
        return $this->englishName;
    }
}
