<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\RetailSaleReport;
use ServiceBoiler\Prf\Site\Models\User;

class RetailSaleReportPolicy
{

	/**
	 * Determine whether the user can create posts.
	 *
	 * @param  User $user
	 *
	 * @return bool
	 */
	public function create(User $user)
	{
		return $user->id > 0 && 
          !empty($user->acceptedParents->first()) && 
          ($user->parents->first()->hasRole('dealer') || $user->parents->first()->hasRole('distr') || $user->parents->first()->hasRole('gendistr'))
          && $user->hasRole('sale_fl') ;
	}


	/**
	 * Determine whether the user can view the post.
	 *
	 * @param User $user
	 * @param RetailSaleReport $retail_sale_report
	 *
	 * @return bool
	 */
	public function view(User $user, RetailSaleReport $retail_sale_report)
	{
		// return $user->admin == 1 || $user->id == $retail_sale_report->getAttribute('user_id');
        return (in_array($retail_sale_report->user->region_id,$user->notifiRegions->pluck('id')->toArray()) && $user->hasRole('ferroli_user')) ||  $user->id == $retail_sale_report->getAttribute('user_id') || $user->admin == 1 || $user->hasRole('supervisor');
	}

	/**
	 * Determine whether the user can update the retail_sale_report.
	 *
	 * @param  User $user
	 * @param  RetailSaleReport $retail_sale_report
	 *
	 * @return bool
	 */
	public function update(User $user, RetailSaleReport $retail_sale_report)
	{
		return $user->id == $retail_sale_report->user_id;
	}

	/**
	 * Determine whether the user can create Digift Bonus.
	 *
	 * @param  User $user
	 * @param  RetailSaleReport $retail_sale_report
	 *
	 * @return bool
	 */
	public function digiftBonus(User $user, RetailSaleReport $retail_sale_report)
	{
		return $retail_sale_report->digiftBonus()->doesntExist()
			&& $retail_sale_report->getAttribute('status_id') == 2;
	}

	/**
	 * Determine whether the user can delete the retail_sale_report.
	 *
	 * @param  User $user
	 * @param  RetailSaleReport $retail_sale_report
	 *
	 * @return bool
	 */
	public function delete(User $user, RetailSaleReport $retail_sale_report)
	{
		return false;
	}


}
