<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use libphonenumber\PhoneNumber;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 *
 * @Vich\Uploadable
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank(
     *     message="user.firstname.not_blank",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @Assert\Length(
     *     min=2,
     *     max=50,
     *     minMessage="user.firstname.min_length",
     *     maxMessage="user.firstname.max_length",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="user.firstname.regex",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @var string
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank(
     *     message="user.lastname.not_blank",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @Assert\Length(
     *     min=2,
     *     max=50,
     *     minMessage="user.lastname.min_length",
     *     maxMessage="user.lastname.max_length",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="user.lastname.regex",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @var string
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Length(
     *     min=5,
     *     max=255,
     *     minMessage="user.address.min_length",
     *     maxMessage="user.address.max_length",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @var string
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     *
     * @Assert\Regex(
     *     pattern="/^[0-9]{5}$/",
     *     message="user.postcode.regex",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @var string
     */
    protected $postcode;

    /**
     * @ORM\Column(type="string",length=100, nullable=true)
     *
     * @Assert\Length(
     *     min=5,
     *     max=100,
     *     minMessage="user.city.min_length",
     *     maxMessage="user.city.max_length",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @var string
     */
    protected $city;

    /**
     * @ORM\Column(type="date", nullable=false)
     *
     * @Assert\NotNull(
     *     message="user.birthdate.not_null",
     *     groups={"Registration", "Profile"}
     * )
     * @Assert\Date(
     *     message="user.birthdate.date",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @var \DateTime
     */
    protected $birthdate;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    protected $description;

    /**
     * @Vich\UploadableField(mapping="profile_picture", fileNameProperty="imageName")
     *
     * @Assert\Image(
     *     minWidth=300,
     *     minHeight=300,
     *     maxWidth=800,
     *     maxHeight=800,
     *     minWidthMessage="user.image_file.min_width",
     *     minHeightMessage="user.image_file.min_height",
     *     maxWidthMessage="user.image_file.max_width",
     *     maxHeightMessage="user.image_file.max_height",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @var File
     */
    protected $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $imageName;


    /**
     * @ORM\Column(type="phone_number", nullable=true)
     *
     * @AssertPhoneNumber(defaultRegion="FR", type="mobile")
     *
     * @var PhoneNumber
     */
    protected $mobilePhoneNumber;

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File|UploadedFile $image
     *
     * @return User
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->lastLogin = new \DateTime();
        }

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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     *
     * @return User
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return User
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return User
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get mobilePhoneNumber
     *
     * @return PhoneNumber
     */
    public function getMobilePhoneNumber()
    {
        return $this->mobilePhoneNumber;
    }

    /**
     * Set mobilePhoneNumber
     *
     * @param PhoneNumber $mobilePhoneNumber
     *
     * @return User
     */
    public function setMobilePhoneNumber($mobilePhoneNumber)
    {
        $this->mobilePhoneNumber = $mobilePhoneNumber;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = parent::getRoles();

        if (count($roles) > 1) {
            array_pop($roles);
        }

        return $roles;
    }
}
