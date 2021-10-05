<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductPolicy
{

    /**
     * Determine whether the user can view the product.
     *
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function view(User $user, Product $product)
    {
        return $user->getAttribute('admin') == 1;
    }

    public function buy(Product $product){

        return $product->can_buy;
    }
 
    /**
     * Determine whether the user can list products
     *
     * @param  User $user
     * @return bool
     */
    public function list(User $user)
    {
        return $user->hasPermission('equipment.list');
    }

    /**
     * Determine whether the user can create product.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->getAttribute('admin') == 1;
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param  User $user
     * @param  Product $product
     * @return bool
     */
    public function update(User $user, Product $product)
    {
        return $user->getAttribute('admin') == 1;
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param  User $user
     * @param  Product $product
     * @return bool
     */
    public function delete(User $user, Product $product)
    {
        return false;
    }


}
