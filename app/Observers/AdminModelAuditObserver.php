<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Admin;
use App\Models\AdminActivityLog;
use App\Services\Audit\AdminActivityLogger;
use Illuminate\Database\Eloquent\Model;

class AdminModelAuditObserver
{
    /**
     * Handle model created event.
     */
    public function created(Model $model): void
    {
        $this->recordModelDiff('created', $model, [], $model->getAttributes());
    }

    /**
     * Handle model updated event.
     */
    public function updated(Model $model): void
    {
        $changes = $model->getChanges();
        $original = [];

        foreach (array_keys($changes) as $key) {
            $original[$key] = $model->getOriginal($key);
        }

        // Exclude updated_at from noise if it's the only changed attribute
        if (count($changes) === 1 && isset($changes['updated_at'])) {
            return;
        }

        $this->recordModelDiff('updated', $model, $original, $changes);
    }

    /**
     * Handle model deleted event.
     */
    public function deleted(Model $model): void
    {
        $this->recordModelDiff('deleted', $model, $model->getOriginal(), []);
    }

    /**
     * Safely store model diff in request attributes for inclusion in the request audit log.
     *
     * @param  array<string, mixed>  $old
     * @param  array<string, mixed>  $new
     */
    protected function recordModelDiff(string $type, Model $model, array $old, array $new): void
    {
        // Only track when acting under an admin context
        if (! auth('admin')->check() && ! request()->user('admin')) {
            return;
        }

        // Avoid logging activity log table itself to prevent infinite loop
        if ($model instanceof AdminActivityLog) {
            return;
        }

        $request = request();

        $sanitizedOld = AdminActivityLogger::sanitizePayload($old);
        $sanitizedNew = AdminActivityLogger::sanitizePayload($new);

        $changes = (array) $request->attributes->get('_admin_model_audit_changes', []);

        $modelAttributes = $model->getAttributes();
        $modelName = '#'.$model->getKey();

        if (isset($modelAttributes['name']) && is_scalar($modelAttributes['name'])) {
            $modelName = (string) $modelAttributes['name'];
        } elseif (isset($modelAttributes['title_ar']) && is_scalar($modelAttributes['title_ar'])) {
            $modelName = (string) $modelAttributes['title_ar'];
        } elseif (isset($modelAttributes['title']) && is_scalar($modelAttributes['title'])) {
            $modelName = (string) $modelAttributes['title'];
        } elseif (isset($modelAttributes['subject']) && is_scalar($modelAttributes['subject'])) {
            $modelName = (string) $modelAttributes['subject'];
        }

        $changes[] = [
            'type' => $type,
            'model' => class_basename($model),
            'id' => $model->getKey(),
            'name' => $modelName,
            'old' => $sanitizedOld,
            'new' => $sanitizedNew,
        ];

        $request->attributes->set('_admin_model_audit_changes', $changes);
    }
}
