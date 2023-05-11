<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Leave
 *
 * @property int $id
 * @property int $user_id
 * @property int $leave_type_id
 * @property string $description
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property int $status
 * @property int|null $approved_by
 * @property string|null $approved_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave whereLeaveTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Leave whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\LeaveType $type
 */
class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'description',
        'start_date',
        'end_date',
        'status',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
