<?php


namespace App;

use App\Models\Leave;
use App\Notifications\LeaveApproveMail;
use App\Notifications\LeaveRejectMail;
use App\Notifications\LeaveSendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LeaveRepository
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedData(Request $request)
    {
        $columns = ['leave_type_id', 'start_date', 'end_date', 'status','approved_by','approved_at'];

        $sortValue = $request->input('sort') ?? '';

        $sortColumn    = in_array($sortValue, $columns) ? $sortValue : 'status';

        $sortDirection = $request->input('dir', 'DESC') === 'DESC' ? 'DESC' : 'ASC';

        $per_page = $request->input('per_page') ?? 10;

        return Leave::query()
            ->when($request->input('user_id'), fn ($query, $value) => $query->where('user_id', $value))

            ->when($request->input('leave_type_id'), fn ($query, $value) => $query->where('leave_type_id', $value))

            ->when($request->input('status'), fn ($query, $value) => $query->where('status', $value))

            ->when(!($request->filled('user_id') || $request->filled('leave_type_id') || $request->input('status')), fn ($query, $value) => $query->where('user_id', '=', $request->user()->id))

            ->orderBy($sortColumn, $sortDirection)

            ->paginate($per_page);
    }

    /**
     * @param array $attributes
     * @return Leave|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes)
    {
        $attributes['user_id'] = auth()->id();

        return Leave::query()->create($attributes);
    }

    /**
     * @param Leave $leave
     * @return Leave
     */
    public function send(Leave $leave)
    {
        $leave->update(['status' => LeaveStatus::WAITING]);

        $user = $leave->user;

        $user->manager->notify(new LeaveSendMail($user));

        return $leave;
    }

    /**
     * @param Leave $leave
     * @return Leave
     */
    public function approve(Leave $leave)
    {
        $leave->update([
            'status'      => LeaveStatus::APPROVED,
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now()->toDateTimeString(),
        ]);

        $user = $leave->user;

        $user->notify(new LeaveApproveMail($user));

        $user->humanResource->notify(new LeaveApproveMail($user));

        return $leave;
    }

    /**
     * @param Leave $leave
     * @return Leave
     */
    public function reject(Leave $leave)
    {
        $leave->update([
            'status' => LeaveStatus::REJECTED,
        ]);

        $user = $leave->user;

        $user->notify(new LeaveRejectMail($user));

        return $leave;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getLeaveRequests(Request $request)
    {
        $columns = ['leave_type_id', 'start_date', 'end_date', 'status','approved_by','approved_at'];

        $sortValue = $request->input('sort');

        $sortColumn    = in_array($sortValue, $columns) ? $sortValue : 'status';

        $sortDirection = $request->input('dir', 'DESC') === 'DESC' ? 'DESC' : 'ASC';

        return Leave::query()->where('leaves.status', '>=', 1)

            ->orderBy($sortColumn, $sortDirection)

            ->orderBy('created_at')

            ->join('users', 'users.id', '=', 'leaves.user_id')

            ->where('users.manager_id', auth()->id())

            ->select('leaves.*')

            ->paginate($request->input('per_page'));
    }
}
