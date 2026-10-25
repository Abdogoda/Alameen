<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class UnifiedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new unified notification instance.
     *
     * @param  string|array<string, string>  $title
     * @param  string|array<string, string>  $body
     * @param  array<int, string>  $channels
     * @param  string  $type  ('info'|'success'|'warning'|'error'|'security'|'system')
     * @param  string|array<string, string>|null  $actionText
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        public string|array $title,
        public string|array $body,
        public array $channels = ['database'],
        public string $type = 'info',
        public ?string $actionUrl = null,
        public string|array|null $actionText = null,
        public ?string $icon = null,
        public array $data = [],
        public ?int $senderId = null,
    ) {
        $this->channels = empty($this->channels) ? ['database'] : array_values(array_unique($this->channels));
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $allowed = ['database', 'mail'];
        $resolved = array_values(array_intersect($this->channels, $allowed));

        return empty($resolved) ? ['database'] : $resolved;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $locale = $this->resolveLocale($notifiable);
        $title = $this->resolveLocalized($this->title, $locale);
        $body = $this->resolveLocalized($this->body, $locale);
        $defaultAction = __('notifications.view_details', [], $locale);
        $actionText = $this->actionText !== null
            ? $this->resolveLocalized($this->actionText, $locale)
            : $defaultAction;

        $mail = (new MailMessage)
            ->subject($title)
            ->greeting($title)
            ->line($body);

        if (! empty($this->actionUrl)) {
            $mail->action($actionText, $this->actionUrl);
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $locale = $this->resolveLocale($notifiable);
        $title = $this->resolveLocalized($this->title, $locale);
        $body = $this->resolveLocalized($this->body, $locale);
        $actionText = $this->actionText !== null
            ? $this->resolveLocalized($this->actionText, $locale)
            : null;

        return [
            'title' => $title,
            'body' => $body,
            'type' => $this->type,
            'channels' => $this->channels,
            'action_url' => $this->actionUrl,
            'action_text' => $actionText,
            'icon' => $this->icon ?? $this->defaultIconForType($this->type),
            'data' => $this->data,
            'sender_id' => $this->senderId,
            'locale' => $locale,
            'sent_at' => Carbon::now()->toIso8601String(),
        ];
    }

    /**
     * Resolve the recipient's preferred locale.
     */
    protected function resolveLocale(object $notifiable): string
    {
        if ($notifiable instanceof User && ! empty($notifiable->locale)) {
            return (string) $notifiable->locale;
        }

        if (isset($notifiable->locale) && is_string($notifiable->locale) && ! empty($notifiable->locale)) {
            return $notifiable->locale;
        }

        return (string) config('localization.default', 'en');
    }

    /**
     * Resolve localized text from a string, translation key, or language dictionary array.
     *
     * @param  string|array<string, string>|null  $content
     */
    public function resolveLocalized(string|array|null $content, string $locale): string
    {
        if ($content === null) {
            return '';
        }

        if (is_array($content)) {
            if (isset($content[$locale])) {
                return $content[$locale];
            }

            if (isset($content['en'])) {
                return $content['en'];
            }

            $first = reset($content);

            return $first !== false ? $first : '';
        }

        // Try Laravel translation with data replacements
        $replacements = array_merge([
            'app' => (string) config('app.name', 'Laravel'),
        ], $this->data);

        /** @var array<string, string> $safeReplacements */
        $safeReplacements = [];
        foreach ($replacements as $key => $val) {
            if (is_scalar($val)) {
                $safeReplacements[(string) $key] = (string) $val;
            }
        }

        $translated = __($content, $safeReplacements, $locale);

        return is_string($translated) ? $translated : $content;
    }

    /**
     * Provide a sensible default icon name for the given notification type.
     */
    protected function defaultIconForType(string $type): string
    {
        return match ($type) {
            'success' => 'check-circle',
            'warning' => 'exclamation-triangle',
            'error' => 'x-circle',
            'security' => 'shield-check',
            'system' => 'cog',
            default => 'bell',
        };
    }
}
