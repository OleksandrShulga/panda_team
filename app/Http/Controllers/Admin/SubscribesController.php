<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subscribe\BulkDestroySubscribe;
use App\Http\Requests\Admin\Subscribe\DestroySubscribe;
use App\Http\Requests\Admin\Subscribe\IndexSubscribe;
use App\Http\Requests\Admin\Subscribe\StoreSubscribe;
use App\Http\Requests\Admin\Subscribe\UpdateSubscribe;
use App\Jobs\sendMail;
use App\Models\Subscribe;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubscribesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexSubscribe $request
     * @return array|Factory|View
     */
    public function index(IndexSubscribe $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Subscribe::class)
            ->modifyQuery(function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'url_from_user', 'email_from_user', 'url_id', 'email_id', 'user_id'],

            // set columns to searchIn
            ['id', 'url_from_user', 'email_from_user']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.subscribe.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.subscribe.create');

        return view('admin.subscribe.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSubscribe $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreSubscribe $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['user_id'] = auth()->id();
        $sanitized['url_id'] = StoreSubscribe::prepareUrlForTrack($sanitized['url_from_user']);
        $sanitized['email_id'] = StoreSubscribe::prepareEmailForTrack($sanitized['email_from_user'], $sanitized['url_id']);

        Subscribe::create($sanitized);

        dispatch(new sendMail());

        if ($request->ajax()) {
            return ['redirect' => url('admin/subscribes'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/subscribes');
    }

    /**
     * Display the specified resource.
     *
     * @param Subscribe $subscribe
     * @throws AuthorizationException
     * @return void
     */
    public function show(Subscribe $subscribe)
    {
        $this->authorize('admin.subscribe.show', $subscribe);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Subscribe $subscribe
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Subscribe $subscribe)
    {
        $this->authorize('admin.subscribe.edit', $subscribe);


        return view('admin.subscribe.edit', [
            'subscribe' => $subscribe,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSubscribe $request
     * @param Subscribe $subscribe
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateSubscribe $request, Subscribe $subscribe)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        UpdateSubscribe::updateUrl(['id' => $subscribe->url_id], ['url' => $sanitized['url_from_user']], ['subscribe_id' => $subscribe->id]);
        UpdateSubscribe::updateEmail(['id' => $subscribe->email_id], ['email' => $sanitized['email_from_user']]);

        // Update changed values Subscribe
        $subscribe->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/subscribes'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/subscribes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroySubscribe $request
     * @param Subscribe $subscribe
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroySubscribe $request, Subscribe $subscribe)
    {
        $subscribe->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroySubscribe $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroySubscribe $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Subscribe::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
