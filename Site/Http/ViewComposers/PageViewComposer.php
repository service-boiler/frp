<?php

namespace ServiceBoiler\Prf\Site\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use ServiceBoiler\Prf\Site\Models\Page;

class PageViewComposer
{
    public function compose(View $view)
    {

        $page_title = null;
        $page_description = null;
        $page_h1 = null;
        if (!is_null(request()->route()) && ($page = Page::query()->where('route', request()->route()->getName()))->exists()) {
            $page_title = $page->first()->title;
            $page_description = $page->first()->description;
            $page_h1 = $page->first()->h1;
        }
        $view->with('page_title', $page_title);
        $view->with('page_description', $page_description);
        $view->with('page_h1', $page_h1);
    }
}