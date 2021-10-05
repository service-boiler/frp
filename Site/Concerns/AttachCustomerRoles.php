<?php

namespace ServiceBoiler\Prf\Site\Concerns;

trait AttachCustomerRoles
{
    /**
     * Attach multiple customerRole to a user
     *
     * @param mixed $customerRole
     */
    public function attachCustomerRoles(array $customerRoles)
    {      
        foreach ($customerRoles as $customerRole) {
            $this->attachCustomerRole($customerRole);
        }
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $customerRole
     */
    public function attachCustomerRole($customerRole)
    {
        if (is_object($customerRole)) {
            $customerRole = $customerRole->getKey();
        }
        if (is_array($customerRole)) {
            $customerRole = $customerRole['id'];
        }
       
        $this->customerRoles()->attach($customerRole);
    }

    /**
     * Detach multiple customerRole from a user
     *
     * @param mixed $customerRole
     */
    public function detachCustomerRoles(array $customerRoles)
    {
        if (!$customerRoles) {
            $customerRoles = $this->customerRoles()->get();
        }
        foreach ($customerRoles as $customerRole) {
            $this->detachCustomerRole($customerRole);
        }
    }

    /**
     * @param $customerRole
     */
    public function syncCustomerRoles($customerRoles)
    {
        if (!is_array($customerRoles)) {
            $customerRoles = [$customerRoles];
        }
        
        $this->customerRoles()->sync($customerRoles, true);
    }


    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param mixed $customerRole
     */
    public function detachCustomerRole($customerRole)
    {
        if (is_object($customerRole)) {
            $customerRole = $customerRole->getKey();
        }
        if (is_array($customerRole)) {
            $customerRole = $customerRole['id'];
        }
        $this->customerRoles()->detach($customerRole);
    }
}