<?php

namespace App\Services;

use App\Models\User;
use App\Models\AccessRequest;
use Illuminate\Support\Facades\DB;

class AccessService
{
    /**
     * Grant or extend access for a user
     *
     * @param User $user
     * @param string $membershipType
     * @return array ['action' => 'granted'|'extended', 'user' => User]
     */
    public function grantAccess(User $user, string $membershipType = 'monthly'): array
    {
        DB::transaction(function () use ($user, $membershipType, &$action) {
            // If user already has access, extend it. Otherwise grant new access.
            if ($user->has_full_access) {
                $user->extendMembership($membershipType);
                $action = 'extended';
            } else {
                $user->grantFullAccess($membershipType);
                $action = 'granted';
            }

            // Update any pending access requests
            AccessRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'membership_type' => $membershipType
                ]);
        });

        return [
            'action' => $action,
            'user' => $user->fresh(),
        ];
    }

    /**
     * Revoke access for a user
     *
     * @param User $user
     * @return User
     */
    public function revokeAccess(User $user): User
    {
        $user->revokeFullAccess();

        return $user->fresh();
    }

    /**
     * Approve an access request
     *
     * @param AccessRequest $request
     * @param string|null $membershipType
     * @return array
     */
    public function approveRequest(AccessRequest $request, ?string $membershipType = null): array
    {
        $membershipType = $membershipType ?? $request->membership_type ?? 'monthly';

        $result = $this->grantAccess($request->user, $membershipType);

        // Update the specific request
        $request->update([
            'status' => 'approved',
            'membership_type' => $membershipType,
        ]);

        return [
            'action' => $result['action'],
            'user' => $result['user'],
            'request' => $request->fresh(),
        ];
    }

    /**
     * Reject an access request
     *
     * @param AccessRequest $request
     * @return AccessRequest
     */
    public function rejectRequest(AccessRequest $request): AccessRequest
    {
        $request->update(['status' => 'rejected']);

        return $request->fresh();
    }

    /**
     * Get access statistics
     *
     * @return array
     */
    public function getAccessStats(): array
    {
        return [
            'total' => User::where('role', 'student')->count(),
            'premium' => User::where('role', 'student')->where('has_full_access', true)->count(),
            'trial' => User::where('role', 'student')->where('has_full_access', false)->count(),
            'pending' => AccessRequest::where('status', 'pending')->count(),
        ];
    }

    /**
     * Get request statistics
     *
     * @return array
     */
    public function getRequestStats(): array
    {
        return [
            'pending' => AccessRequest::where('status', 'pending')->count(),
            'approved' => AccessRequest::where('status', 'approved')->count(),
            'rejected' => AccessRequest::where('status', 'rejected')->count(),
        ];
    }
}
