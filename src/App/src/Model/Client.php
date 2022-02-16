<?php

namespace App\Model;

/**
 * Description of Model
 * @property int $id ID of item
 * @property mixed $firstname First Name
 * @property mixed $lastname Last Name
 * @property mixed $email Email
 * @property mixed $phone Phone
 * @property mixed $photo Photo url
 * @property mixed $created_at Created date Y-M-d
 * @property mixed $updated_at Created date Y-M-d
 * @property mixed $deleted The record is deleted

 *
 * @OA\Schema()
 * @OA\Property(
 *  property="id",
 *  type="integer",
 *  description=""
 * )
 * @OA\Property(
 *  property="firstname",
 *  type="string",
 *  description=""
 * )
 * @OA\Property(
 *  property="lastname",
 *  type="string",
 *  description=""
 * )
 * @OA\Property(
 *  property="email",
 *  type="string",
 *  description=""
 * )
 *  @OA\Property(
 *  property="phone",
 *  type="string",
 *  description=""
 * )
 * @OA\Property(
 *  property="photo",
 *  type="string",
 *  description=""
 * )
 * @OA\Property(
 *  property="created_at",
 *  type="",
 *  description=""
 * )
 * @OA\Property(
 *  property="updated_at",
 *  type="",
 *  description=""
 * )
 * @OA\Property(
 *  property="deleted",
 *  type="integer",
 *  description=""
 * )

 *
 * @author cpantuso
 */
class Client extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'client';

    /**
    * Filter deleted records
    * @return void
    */
    protected static function boot(): void
    {
        parent::boot();
        
        static::addGlobalScope('exclude', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->where('client.deleted', 0);
        });
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();;
    }
}
