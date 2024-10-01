<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {{modelName}} extends Model
{
    use HasFactory;

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE   = 1;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Scope a query to only include
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, int|array $status)
    {
        if (is_array($status)) {
            return $query->whereIn('status', $status);
        }
        return $query->where('status', $status);
    }

    /**
     * Returns the list of statuses used for {{modelName}}
     *
     * @return array
     */
    public static function getStatuses() : array
    {
        return [
            self::getStatusActive(),
            self::getStatusInactive(),
        ];
    }

     /**
     * Returns true if the {{modelName}} is active, false otherwise
     *
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->status == self::getStatusActive() ? true : false;
    }

    /**
     * Returns true if the {{modelName}} is archived, false otherwise
     *
     * @return bool
     */
    public function isDeactivated() : bool
    {
        return $this->status == self::getStatusInactive() ? true : false;
    }

     /**
     * Returns the value for {{modelName}} status Active status
     *
     * @return string
     */
    public static function getStatusActive() : string
    {
        return self::STATUS_ACTIVE;
    }
    
    /**
     * Returns the value for {{modelName}} status Archived status
     *
     *
     * @return string
     */
    public static function getStatusInactive() : string
    {
        return self::STATUS_INACTIVE;
    }
}
