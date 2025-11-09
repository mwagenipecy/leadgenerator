<?php

namespace App\Services;

use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogService
{
    /**
     * Log a critical user action
     */
    public static function log(
        string $action,
        string $description = null,
        string $severity = 'medium',
        $model = null,
        array $oldValues = null,
        array $newValues = null,
        array $metadata = null
    ): SystemLog {
        try {
            $user = Auth::user();
            
            return SystemLog::create([
                'user_id' => $user?->id,
                'action' => $action,
                'model_type' => $model ? get_class($model) : null,
                'model_id' => $model?->id ?? null,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'severity' => $severity,
                'description' => $description ?? self::getDefaultDescription($action, $model),
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'metadata' => $metadata,
                'request_method' => Request::method(),
                'request_url' => Request::fullUrl(),
            ]);
        } catch (\Exception $e) {
            // Log to Laravel's log if system log fails (to prevent infinite loops)
            \Log::error('Failed to create system log', [
                'action' => $action,
                'error' => $e->getMessage()
            ]);
            // Return a dummy log or throw based on your preference
            throw $e;
        }
    }

    /**
     * Log user login
     */
    public static function logLogin($user): SystemLog
    {
        return self::log(
            'user_login',
            "User {$user->email} logged in",
            'medium',
            $user
        );
    }

    /**
     * Log user logout
     */
    public static function logLogout($user): SystemLog
    {
        return self::log(
            'user_logout',
            "User {$user->email} logged out",
            'low',
            $user
        );
    }

    /**
     * Log user creation
     */
    public static function logUserCreated($user, array $metadata = null): SystemLog
    {
        return self::log(
            'user_created',
            "User {$user->email} was created",
            'high',
            $user,
            null,
            $user->toArray(),
            $metadata
        );
    }

    /**
     * Log user update
     */
    public static function logUserUpdated($user, array $oldValues, array $newValues): SystemLog
    {
        return self::log(
            'user_updated',
            "User {$user->email} was updated",
            'medium',
            $user,
            $oldValues,
            $newValues
        );
    }

    /**
     * Log user deletion
     */
    public static function logUserDeleted($user): SystemLog
    {
        return self::log(
            'user_deleted',
            "User {$user->email} was deleted",
            'critical',
            $user,
            $user->toArray(),
            null
        );
    }

    /**
     * Log user status change
     */
    public static function logUserStatusChanged($user, bool $isActive): SystemLog
    {
        return self::log(
            'user_status_changed',
            "User {$user->email} status changed to " . ($isActive ? 'active' : 'inactive'),
            'high',
            $user,
            ['is_active' => !$isActive],
            ['is_active' => $isActive]
        );
    }

    /**
     * Log role assignment
     */
    public static function logRoleAssigned($user, $role, $assignedBy = null): SystemLog
    {
        return self::log(
            'role_assigned',
            "Role {$role} was assigned to user {$user->email}",
            'high',
            $user,
            null,
            ['role' => $role],
            ['assigned_by' => $assignedBy?->id]
        );
    }

    /**
     * Log permission change
     */
    public static function logPermissionChanged($user, string $permission, bool $granted): SystemLog
    {
        return self::log(
            'permission_changed',
            "Permission {$permission} was " . ($granted ? 'granted' : 'revoked') . " for user {$user->email}",
            'high',
            $user,
            null,
            ['permission' => $permission, 'granted' => $granted]
        );
    }

    /**
     * Log application creation
     */
    public static function logApplicationCreated($application): SystemLog
    {
        return self::log(
            'application_created',
            "Application #{$application->id} was created by user {$application->user->email}",
            'medium',
            $application
        );
    }

    /**
     * Log application status change
     */
    public static function logApplicationStatusChanged($application, string $oldStatus, string $newStatus): SystemLog
    {
        return self::log(
            'application_status_changed',
            "Application #{$application->id} status changed from {$oldStatus} to {$newStatus}",
            'high',
            $application,
            ['status' => $oldStatus],
            ['status' => $newStatus]
        );
    }

    /**
     * Log lender approval
     */
    public static function logLenderApproved($lender): SystemLog
    {
        return self::log(
            'lender_approved',
            "Lender {$lender->company_name} was approved",
            'high',
            $lender,
            ['status' => 'pending'],
            ['status' => 'approved']
        );
    }

    /**
     * Log lender rejection
     */
    public static function logLenderRejected($lender, string $reason = null): SystemLog
    {
        return self::log(
            'lender_rejected',
            "Lender {$lender->company_name} was rejected" . ($reason ? ": {$reason}" : ''),
            'high',
            $lender,
            ['status' => 'pending'],
            ['status' => 'rejected'],
            ['reason' => $reason]
        );
    }

    /**
     * Get default description based on action
     */
    private static function getDefaultDescription(string $action, $model = null): string
    {
        $descriptions = [
            'user_login' => 'User logged in',
            'user_logout' => 'User logged out',
            'user_created' => 'User was created',
            'user_updated' => 'User was updated',
            'user_deleted' => 'User was deleted',
            'user_status_changed' => 'User status was changed',
            'role_assigned' => 'Role was assigned',
            'permission_changed' => 'Permission was changed',
            'application_created' => 'Application was created',
            'application_status_changed' => 'Application status was changed',
            'lender_approved' => 'Lender was approved',
            'lender_rejected' => 'Lender was rejected',
        ];

        return $descriptions[$action] ?? ucfirst(str_replace('_', ' ', $action));
    }
}

