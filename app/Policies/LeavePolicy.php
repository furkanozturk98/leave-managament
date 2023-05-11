<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use phpDocumentor\Reflection\Types\True_;

class LeavePolicy
{
    use HandlesAuthorization;


    /**
     * @param User $user
     * @param Leave $leave
     * @return bool
     */
    public function view(User $user, Leave $leave)
    {
        return $leave->user_id == $user->id;
    }


    /**
     * @param User $user
     * @param Leave $leave
     * @return bool
     */
    public function update(User $user, Leave $leave)
    {
        return $leave->user_id == $user->id && $leave->status == 0;
    }


    /**
     * @param User $user
     * @param Leave $leave
     * @return bool
     */
    public function send(User $user, Leave $leave)
    {
        return $leave->user_id == $user->id && $leave->status == 0;
    }


    /**
     * @param User $user
     * @param Leave $leave
     * @return bool
     */
    public function approve(User $user, Leave $leave)
    {
        return $user->id == $leave->user->manager_id && $leave->status == 1;
    }


    /**
     * @param User $user
     * @param Leave $leave
     * @return bool
     */
    public function reject(User $user, Leave $leave)
    {
        return $user->id == $leave->user->manager_id && $leave->status == 1;
    }


    /**
     * @param User $user
     * @param Leave $leave
     * @return bool
     */
    public function delete(User $user, Leave $leave)
    {
        return $leave->user_id == $user->id && $leave->status == 0;
    }


    /**
     * @param User $user
     * @return mixed
     */
    public function getLeaveRequests(User $user)
    {
        return $user->is_manager();
    }
}
