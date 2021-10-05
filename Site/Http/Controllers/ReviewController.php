<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\PathUrlFilter;
use ServiceBoiler\Prf\Site\Events\ReviewCreateEvent;
use ServiceBoiler\Prf\Site\Http\Requests\ReviewRequest;
use ServiceBoiler\Prf\Site\Models\Review;
use ServiceBoiler\Prf\Site\Models\ReviewStatus;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;
use ServiceBoiler\Prf\Site\Repositories\ReviewRepository;
use ServiceBoiler\Prf\Site\Repositories\HeadBannerBlockRepository;


class ReviewController extends Controller
{
    private $users;

    private $headBannerBlocks;
    /**
     * Create a new controller instance.
     *
     * @param UserRepository $users
     */
    public function __construct(
        ReviewRepository $reviews, 
        UserRepository $users,
        HeadBannerBlockRepository $headBannerBlocks)
    {
        $this->reviews = $reviews;
		$this->users = $users;
        $this->headBannerBlocks = $headBannerBlocks;
    }
 

    public function store(ReviewRequest $request) 
    {
        $review = $this->reviews
                ->create(
                array_merge($request->input('review'), ['ip' => request()->ip()])
                );

        
        event(new ReviewCreateEvent($review));
                
                
                switch ($request->input('review.reviewable_type')) {
					case 'products':
                        return redirect()->route('products.show',$request->input('review.reviewable_id'))->with('success', trans('Отзыв успешно принят. Мы его обязательно прочитаем.'));
						break;
					case 'equipments':
                        return redirect()->route('equipments.show',$request->input('review.reviewable_id'))->with('success', trans('Отзыв успешно принят. Мы его обязательно прочитаем.'));
                        break;
				}

        
    }

}
