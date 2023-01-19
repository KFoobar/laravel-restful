<?php

namespace App\Models\Sanctum;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasFactory;

    /**
     * Determines if token is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        if (!$this->hasExpirationDate()) {
            return false;
        }

        return ($this->expires_at->isPast())
            ? true
            : false;
    }

    /**
     * Determines if token has expiration date.
     *
     * @return bool
     */
    public function hasExpirationDate()
    {
        if (empty($this->expires_at)) {
            return null;
        }

        return ($this->expires_at instanceof Carbon)
            ? true
            : false;
    }

    /**
     * Gets the expiration date.
     *
     * @return string|null
     */
    public function getExpirationDate()
    {
        if (empty($this->expires_at)) {
            return null;
        }

        return !empty($this->expires_at)
            ? $this->expires_at->toDateString()
            : null;
    }

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        if (config('restful.token.ignore_empty_updates')) {
            $updates = $this->getDirty();

            if (array_key_exists('last_used_at', $updates) && count($updates) === 1) {
                return false;
            }
        }

        return parent::save();
    }
}
