<?php

namespace Webkul\Admin\Http\Controllers\Initiative;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Initiative\Repositories\InitiativeRepository;
use Webkul\Quote\Repositories\QuoteRepository;

class QuoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected InitiativeRepository $initiativeRepository,
        protected QuoteRepository $quoteRepository
    ) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        Event::dispatch('initiatives.quote.create.before');

        $initiative = $this->initiativeRepository->find($id);

        if (! $initiative->quotes->contains(request('id'))) {
            $initiative->quotes()->attach(request('id'));
        }

        Event::dispatch('initiatives.quote.create.after', $initiative);

        return response()->json([
            'message' => trans('admin::app.initiatives.quote-create-success'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $initiativeId
     * @param  int  $tagId
     * @return \Illuminate\Http\Response
     */
    public function delete($initiativeId)
    {
        Event::dispatch('initiatives.quote.delete.before', $initiativeId);

        $initiative = $this->initiativeRepository->find($initiativeId);

        $initiative->quotes()->detach(request('quote_id'));

        Event::dispatch('initiatives.quote.delete.after', $initiative);

        return response()->json([
            'message' => trans('admin::app.initiatives.view.quotes.destroy-success'),
        ], 200);
    }
}
