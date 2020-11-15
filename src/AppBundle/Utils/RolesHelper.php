<?php
namespace AppBundle\Utils;

class RolesHelper
{
    /**
     * @var array
     */
    private $rolesHierarchy;

    public function __construct($roleHierarchy)
    {
        $this->rolesHierarchy = $roleHierarchy;
    }

    public function getRoles()
    {
        $roles = array();
        array_walk_recursive($this->rolesHierarchy, function ($val) use (&$roles) {
            $roles[$val] = $val;
        });

        $keys = array_keys($this->rolesHierarchy);
        $keys = array_combine($keys, $keys);

        $roles += $keys;

        return array_unique($roles);
    }
}