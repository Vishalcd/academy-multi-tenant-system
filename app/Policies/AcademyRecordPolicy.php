<?php

namespace App\Policies;

use App\Models\User;

class AcademyRecordPolicy
{
    /**
     * Determine if the user can view the record.
     */
    public function view(User $user, $record): bool
    {
        return $this->isAuthorized($user, $record);
    }

    /**
     * Determine if the user can update the record.
     */
    public function update(User $user, $record): bool
    {
        return $this->isAuthorized($user, $record);
    }

    /**
     * Determine if the user can delete the record.
     */
    public function delete(User $user, $record): bool
    {
        return $this->isAuthorized($user, $record);
    }

    /**
     * Shared logic for access check.
     */
    private function isAuthorized(User $user, $record): bool
    {
        // Admin has access to all
        if ($user->role === 'admin') {
            return true;
        }

        // Manager can access only their own academy's data
        if ($user->role === 'manager') {

            // Record has user relationship (e.g., Student, Employee)
            if (method_exists($record, 'user') && optional($record->user)->academy_id === $user->academy_id) {
                return true;
            }

            // Record directly has academy_id (e.g., Expense, Sport)
            if (isset($record->academy_id) && $record->academy_id === $user->academy_id) {
                return true;
            }
        }

        return false;
    }
}
