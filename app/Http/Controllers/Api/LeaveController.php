<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveFormRequest;
use App\Http\Resources\LeaveResource;
use App\LeaveRepository;
use App\Models\Leave;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeaveController extends Controller
{
    private LeaveRepository $leaveRepository;

    /**
     * LeaveController constructor.
     *
     * @param LeaveRepository $leaveRepository
     */
    public function __construct(LeaveRepository $leaveRepository)
    {
        $this->leaveRepository = $leaveRepository;
    }


    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $data = $this->leaveRepository->getPaginatedData($request);

        return LeaveResource::collection($data);
    }


    /**
     * @param LeaveFormRequest $request
     * @return LeaveResource
     */
    public function store(LeaveFormRequest $request)
    {
        $data = $this->leaveRepository->create($request->validated());

        return new LeaveResource($data);
    }

    /**
     * Display the specified resource.
     *
     * @param Leave $leave
     *
     * @return LeaveResource
     * @throws AuthorizationException
     */
    public function show(Leave $leave)
    {
        $this->authorize('view', $leave);

        return new LeaveResource($leave);
    }


    /**
     * @param LeaveFormRequest $request
     * @param Leave $leave
     * @return LeaveResource
     * @throws AuthorizationException
     */
    public function update(LeaveFormRequest $request, Leave $leave)
    {
        $this->authorize('update', $leave);

        $leave->update($request->validated());

        return new LeaveResource($leave);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Leave $leave
     *
     * @return LeaveResource
     * @throws AuthorizationException
     */
    public function send(Leave $leave)
    {
        $this->authorize('send', $leave);

        $this->leaveRepository->send($leave);

        return new LeaveResource($leave);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Leave $leave
     *
     * @return LeaveResource
     * @throws AuthorizationException
     */
    public function approve(Leave $leave)
    {
        $this->authorize('approve', $leave);

        $this->leaveRepository->approve($leave);

        return new LeaveResource($leave);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Leave $leave
     *
     * @return LeaveResource
     * @throws AuthorizationException
     */
    public function reject(Request $request, Leave $leave)
    {
        $this->authorize('reject', $leave);

        $this->leaveRepository->reject($leave);

        return new LeaveResource($leave);
    }


    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function getLeaveRequests(Request $request)
    {
        $this->authorize('getLeaveRequests', Leave::class);

        $data = $this->leaveRepository->getLeaveRequests($request);

        return LeaveResource::collection($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Leave $leave
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Leave $leave)
    {
        $this->authorize('delete', $leave);

        $leave->delete();

        return response()->noContent();
    }
}
