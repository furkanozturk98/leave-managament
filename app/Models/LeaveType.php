<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LeaveType
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeaveType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeaveType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeaveType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeaveType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeaveType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeaveType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeaveType whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeaveType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LeaveType extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'description'
    ];
}
