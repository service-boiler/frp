<?php

namespace ServiceBoiler\Prf\Site\Support;


use ServiceBoiler\Prf\Site\Contracts\Messagable;
use ServiceBoiler\Prf\Site\Contracts\CommentBoxInterface;

class CommentBox
{

	/**
	 * @var CommentBoxInterface
	 */
	private $messagable;
	private $comments;

	public function __construct(Messagable $messagable)
	{
		$this->messagable = $messagable;
		if(!empty($messagable->messages->first()) && in_array($messagable->messages->first()->messagable_type, ['tenders','tickets','authorizations','repairs'])){
            $this->comments = $messagable
                ->messages()->where([
                    'personal' => 1,
                    ])
                ->get();
        } else {
             $this->comments = $messagable
                ->messages()
                ->where([
                    'personal' => 1,
                    //'user_id' => auth()->user()->getAuthIdentifier(),
                    'receiver_id' => $messagable->id,
                ])->get(); 
        }
	}

	public function messagable()
	{
		return $this->messagable;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function comments()
	{
		return $this->comments;
	}
}