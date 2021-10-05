<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Filters\Review\SortFilter;
use ServiceBoiler\Prf\Site\Filters\Review\ReviewStatusFilter;
use ServiceBoiler\Prf\Site\Filters\Review\ReviewUserFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\ReviewRequest;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Review;
use ServiceBoiler\Prf\Site\Models\ReviewStatus;
use ServiceBoiler\Prf\Site\Repositories\ReviewRepository;

class ReviewController extends Controller
{

    use StoreMessages;

    /**
     * @var ReviewRepository
     */
    private $reviews;

    /**
     * ReviewController constructor.
     * @param ReviewRepository $reviews
     */
    public function __construct(ReviewRepository $reviews)
    {

        $this->reviews = $reviews;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->reviews->trackFilter();

        $this->reviews->pushTrackFilter(SortFilter::class);
        $this->reviews->pushTrackFilter(ReviewStatusFilter::class);
        //$this->reviews->pushTrackFilter(ReviewUserFilter::class);
        $reviews = $this->reviews->paginate($request->input('filter.per_page', config('site.per_page.review', 100)), ['reviews.*']);
        $repository = $this->reviews;

        return view('site::admin.review.index', compact('reviews', 'repository'));
    }

    /**
     * @param Review $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        $review_statuses = ReviewStatus::query()->get();
        return view('site::admin.review.show', compact(
            'review', 'review_statuses'
        ));
    }


    /**
     * @param Review $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        $review_statuses = ReviewStatus::query()->get();

        return view('site::admin.review.edit', compact('review', 'review_statuses'));
    }

    /**
     * @param  ReviewRequest $request
     * @param  Review $review
     * @return \Illuminate\Http\Response
     */
    public function update(ReviewRequest $request, Review $review)
    {
        $review->update($request->input('review'));

        return redirect()->route('admin.reviews.show', $review)->with('success', trans('site::admin.review.updated'));
    }


}