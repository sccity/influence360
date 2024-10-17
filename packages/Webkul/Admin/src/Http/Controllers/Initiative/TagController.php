<?php

namespace Webkul\Admin\Http\Controllers\Initiative;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Initiative\Repositories\InitiativeRepository;

class TagController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected InitiativeRepository $initiativeRepository) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function attach($id)
    {
        Event::dispatch('initiatives.tag.create.before', $id);

        $initiative = $this->initiativeRepository->find($id);

        if (! $initiative->tags->contains(request()->input('tag_id'))) {
            $initiative->tags()->attach(request()->input('tag_id'));
        }

        Event::dispatch('initiatives.tag.create.after', $initiative);

        return response()->json([
            'message' => trans('admin::app.initiatives.view.tags.create-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $initiativeId
     * @return \Illuminate\Http\Response
     */
    public function detach($initiativeId)
    {
        Event::dispatch('initiatives.tag.delete.before', $initiativeId);

        $initiative = $this->initiativeRepository->find($initiativeId);

        $initiative->tags()->detach(request()->input('tag_id'));

        Event::dispatch('initiatives.tag.delete.after', $initiative);

        return response()->json([
            'message' => trans('admin::app.initiatives.view.tags.destroy-success'),
        ]);
    }
}
