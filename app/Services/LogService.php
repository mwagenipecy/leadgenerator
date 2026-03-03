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
     * Log lender disabled
     */
    public static function logLenderDisabled($lender, array $metadata = null, string $oldStatus = null): SystemLog
    {
        return self::log(
            'lender_disabled',
            "Lender {$lender->company_name} was disabled. All loan products and users were disabled.",
            'critical',
            $lender,
            ['status' => $oldStatus ?? 'approved'],
            ['status' => 'suspended', 'is_active' => false],
            $metadata
        );
    }

    /**
     * Log lender enabled
     */
    public static function logLenderEnabled($lender, array $metadata = null, string $oldStatus = null): SystemLog
    {
        return self::log(
            'lender_enabled',
            "Lender {$lender->company_name} was enabled. All loan products and users were enabled.",
            'critical',
            $lender,
            ['status' => $oldStatus ?? 'suspended'],
            ['status' => 'approved', 'is_active' => true],
            $metadata
        );
    }

    /**
     * Log loan product creation
     */
    public static function logLoanProductCreated($product): SystemLog
    {
        return self::log(
            'loan_product_created',
            "Loan product '{$product->name}' was created",
            'high',
            $product,
            null,
            $product->toArray()
        );
    }

    /**
     * Log loan product update
     */
    public static function logLoanProductUpdated($product, array $oldValues, array $newValues): SystemLog
    {
        return self::log(
            'loan_product_updated',
            "Loan product '{$product->name}' was updated",
            'medium',
            $product,
            $oldValues,
            $newValues
        );
    }

    /**
     * Log loan product deletion
     */
    public static function logLoanProductDeleted($product): SystemLog
    {
        return self::log(
            'loan_product_deleted',
            "Loan product '{$product->name}' was deleted",
            'critical',
            $product,
            $product->toArray(),
            ['status' => 'deleted', 'is_active' => false]
        );
    }

    /**
     * Log loan product status change
     */
    public static function logLoanProductStatusChanged($product, bool $isActive): SystemLog
    {
        return self::log(
            'loan_product_status_changed',
            "Loan product '{$product->name}' status changed to " . ($isActive ? 'active' : 'inactive'),
            'high',
            $product,
            ['is_active' => !$isActive, 'status' => !$isActive ? 'active' : 'inactive'],
            ['is_active' => $isActive, 'status' => $isActive ? 'active' : 'inactive']
        );
    }

    /**
     * Log role creation
     */
    public static function logRoleCreated($role): SystemLog
    {
        return self::log(
            'role_created',
            "Role '{$role->display_name}' was created",
            'high',
            $role,
            null,
            $role->toArray()
        );
    }

    /**
     * Log role update
     */
    public static function logRoleUpdated($role, array $oldValues, array $newValues): SystemLog
    {
        return self::log(
            'role_updated',
            "Role '{$role->display_name}' was updated",
            'high',
            $role,
            $oldValues,
            $newValues
        );
    }

    /**
     * Log role deletion
     */
    public static function logRoleDeleted($role): SystemLog
    {
        return self::log(
            'role_deleted',
            "Role '{$role->display_name}' was deleted",
            'critical',
            $role,
            $role->toArray(),
            null
        );
    }

    /**
     * Log role status change
     */
    public static function logRoleStatusChanged($role, bool $isActive): SystemLog
    {
        return self::log(
            'role_status_changed',
            "Role '{$role->display_name}' status changed to " . ($isActive ? 'active' : 'inactive'),
            'high',
            $role,
            ['is_active' => !$isActive],
            ['is_active' => $isActive]
        );
    }

    /**
     * Log company verification
     */
    public static function logCompanyVerified($user, string $notes = null): SystemLog
    {
        return self::log(
            'company_verified',
            "Company '{$user->company_name}' was verified",
            'high',
            $user,
            ['company_verification_status' => 'pending'],
            ['company_verification_status' => 'verified', 'company_verified_at' => now()],
            ['notes' => $notes]
        );
    }

    /**
     * Log company verification rejection
     */
    public static function logCompanyRejected($user, string $reason = null): SystemLog
    {
        return self::log(
            'company_rejected',
            "Company '{$user->company_name}' verification was rejected",
            'high',
            $user,
            ['company_verification_status' => 'pending'],
            ['company_verification_status' => 'rejected'],
            ['rejection_reason' => $reason]
        );
    }

    /**
     * Log company verification document viewed
     */
    public static function logCompanyDocumentsViewed($user): SystemLog
    {
        return self::log(
            'company_documents_viewed',
            "Company verification documents for '{$user->company_name}' were viewed",
            'medium',
            $user
        );
    }

    /**
     * Log company verification document downloaded
     */
    public static function logCompanyDocumentDownloaded($user, $document): SystemLog
    {
        return self::log(
            'company_document_downloaded',
            "Company verification document '{$document->document_name}' was downloaded for '{$user->company_name}'",
            'medium',
            $user,
            null,
            null,
            ['document_id' => $document->id, 'document_name' => $document->document_name, 'document_type' => $document->document_type]
        );
    }

    /**
     * Log loan category creation
     */
    public static function logLoanCategoryCreated($category): SystemLog
    {
        return self::log(
            'loan_category_created',
            "Loan category '{$category->name}' was created",
            'high',
            $category,
            null,
            $category->toArray()
        );
    }

    /**
     * Log loan category update
     */
    public static function logLoanCategoryUpdated($category, array $oldValues, array $newValues): SystemLog
    {
        return self::log(
            'loan_category_updated',
            "Loan category '{$category->name}' was updated",
            'high',
            $category,
            $oldValues,
            $newValues
        );
    }

    /**
     * Log loan category disable
     */
    public static function logLoanCategoryDisabled($category): SystemLog
    {
        return self::log(
            'loan_category_disabled',
            "Loan category '{$category->name}' was disabled",
            'high',
            $category,
            ['is_active' => true],
            ['is_active' => false]
        );
    }

    /**
     * Log blog post creation
     */
    public static function logBlogPostCreated($post): SystemLog
    {
        return self::log(
            'blog_post_created',
            "Blog post '{$post->title}' was created",
            'medium',
            $post,
            null,
            $post->toArray()
        );
    }

    /**
     * Log blog post update
     */
    public static function logBlogPostUpdated($post, array $oldValues, array $newValues): SystemLog
    {
        return self::log(
            'blog_post_updated',
            "Blog post '{$post->title}' was updated",
            'medium',
            $post,
            $oldValues,
            $newValues
        );
    }

    /**
     * Log CreditInfo alert service unsubscribe (audit trail).
     */
    public static function logCreditInfoServiceUnsubscribed($user, string $serviceName, $service = null, array $metadata = null): SystemLog
    {
        return self::log(
            'creditinfo_service_unsubscribed',
            "User {$user->email} unsubscribed from CreditInfo service: {$serviceName}",
            'medium',
            $service,
            ['subscribed' => true],
            ['subscribed' => false],
            $metadata ? array_merge($metadata, ['service_name' => $serviceName]) : ['service_name' => $serviceName]
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
            'role_created' => 'Role was created',
            'role_updated' => 'Role was updated',
            'role_deleted' => 'Role was deleted',
            'role_status_changed' => 'Role status was changed',
            'permission_changed' => 'Permission was changed',
            'application_created' => 'Application was created',
            'application_status_changed' => 'Application status was changed',
            'lender_approved' => 'Lender was approved',
            'lender_rejected' => 'Lender was rejected',
            'lender_disabled' => 'Lender was disabled',
            'lender_enabled' => 'Lender was enabled',
            'loan_product_created' => 'Loan product was created',
            'loan_product_updated' => 'Loan product was updated',
            'loan_product_deleted' => 'Loan product was deleted',
            'loan_product_status_changed' => 'Loan product status was changed',
            'company_verified' => 'Company was verified',
            'company_rejected' => 'Company verification was rejected',
            'company_documents_viewed' => 'Company verification documents were viewed',
            'company_document_downloaded' => 'Company verification document was downloaded',
            'loan_category_created' => 'Loan category was created',
            'loan_category_updated' => 'Loan category was updated',
            'loan_category_disabled' => 'Loan category was disabled',
            'blog_post_created' => 'Blog post was created',
            'blog_post_updated' => 'Blog post was updated',
            'creditinfo_service_unsubscribed' => 'CreditInfo alert service was unsubscribed',
        ];

        return $descriptions[$action] ?? ucfirst(str_replace('_', ' ', $action));
    }
}

