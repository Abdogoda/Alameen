<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MuxWebhookController extends Controller
{
    /**
     * Handle incoming MUX webhook notifications.
     */
    public function handle(Request $request): JsonResponse
    {
        $signatureHeader = $request->header('Mux-Signature');
        $webhookSecret = config('video.drivers.mux.webhook_secret');

        // Optional webhook signature verification
        if (! empty($webhookSecret) && ! empty($signatureHeader)) {
            if (! $this->verifySignature($request->getContent(), $signatureHeader, $webhookSecret)) {
                Log::warning('MUX Webhook signature verification failed.');

                return response()->json(['error' => 'Invalid webhook signature'], 401);
            }
        }

        $payload = $request->json()->all();
        $type = $payload['type'] ?? null;
        $data = $payload['data'] ?? [];

        Log::info('MUX Webhook received', ['type' => $type, 'id' => $data['id'] ?? null]);

        return match ($type) {
            'video.asset.ready' => $this->handleAssetReady($data),
            'video.upload.asset_created' => $this->handleUploadAssetCreated($data),
            'video.asset.errored' => $this->handleAssetErrored($data),
            default => response()->json(['status' => 'ignored', 'type' => $type], 200),
        };
    }

    /**
     * Handle video.asset.ready event.
     *
     * @param  array<string, mixed>  $data
     */
    protected function handleAssetReady(array $data): JsonResponse
    {
        $assetId = $data['id'] ?? null;
        $uploadId = $data['upload_id'] ?? null;
        $playbackId = $data['playback_ids'][0]['id'] ?? null;
        $durationSeconds = isset($data['duration']) ? (int) round((float) $data['duration']) : null;

        if (! $assetId) {
            return response()->json(['error' => 'Missing asset ID in payload'], 400);
        }

        // 1. Locate and update matching Lesson Video
        $video = Video::query()
            ->where(function ($q) use ($assetId, $uploadId, $playbackId) {
                $q->where('mux_asset_id', $assetId);
                if ($uploadId) {
                    $q->orWhere('mux_upload_id', $uploadId);
                }
                if ($playbackId) {
                    $q->orWhere('mux_playback_id', $playbackId);
                }
            })
            ->first();

        if ($video) {
            $video->video_source = 'mux';
            $video->mux_asset_id = $assetId;
            if ($playbackId) {
                $video->mux_playback_id = $playbackId;
                $video->video_url = "https://stream.mux.com/{$playbackId}.m3u8";
            }
            if ($durationSeconds && $durationSeconds > 0) {
                $video->duration_seconds = $durationSeconds;
            }
            $video->save();

            Log::info("MUX Webhook: Video #{$video->id} ({$video->title_ar}) successfully synced to ready status.");
        }

        // 2. Also check if course promo video matches
        $course = Course::query()
            ->where(function ($q) use ($assetId, $playbackId) {
                $q->where('mux_asset_id', $assetId);
                if ($playbackId) {
                    $q->orWhere('mux_playback_id', $playbackId);
                }
            })
            ->first();

        if ($course && $playbackId) {
            $course->promo_video_source = 'mux';
            $course->mux_asset_id = $assetId;
            $course->mux_playback_id = $playbackId;
            $course->promo_video_url = "https://stream.mux.com/{$playbackId}.m3u8";
            $course->save();

            Log::info("MUX Webhook: Course #{$course->id} promo video updated.");
        }

        return response()->json(['status' => 'success', 'asset_id' => $assetId], 200);
    }

    /**
     * Handle video.upload.asset_created event.
     *
     * @param  array<string, mixed>  $data
     */
    protected function handleUploadAssetCreated(array $data): JsonResponse
    {
        $uploadId = $data['id'] ?? null;
        $assetId = $data['asset_id'] ?? null;

        if ($uploadId && $assetId) {
            Video::where('mux_upload_id', $uploadId)->update(['mux_asset_id' => $assetId]);
        }

        return response()->json(['status' => 'linked', 'upload_id' => $uploadId, 'asset_id' => $assetId], 200);
    }

    /**
     * Handle video.asset.errored event.
     *
     * @param  array<string, mixed>  $data
     */
    protected function handleAssetErrored(array $data): JsonResponse
    {
        $assetId = $data['id'] ?? null;
        $errors = $data['errors'] ?? [];

        Log::error("MUX Webhook: Video asset {$assetId} encountered encoding errors:", $errors);

        return response()->json(['status' => 'logged_error'], 200);
    }

    /**
     * Verify HMAC-SHA256 MUX signature.
     * Mux-Signature header: t=1565120340,v1=25e2434114...
     */
    protected function verifySignature(string $rawBody, string $header, string $secret): bool
    {
        $parts = explode(',', $header);
        $timestamp = null;
        $signature = null;

        foreach ($parts as $part) {
            $pair = explode('=', trim($part), 2);
            if (count($pair) === 2) {
                if ($pair[0] === 't') {
                    $timestamp = $pair[1];
                } elseif ($pair[0] === 'v1') {
                    $signature = $pair[1];
                }
            }
        }

        if (! $timestamp || ! $signature) {
            return false;
        }

        // Prevent replay attacks (allow 10-minute clock drift)
        if (abs(time() - (int) $timestamp) > 600) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', "{$timestamp}.{$rawBody}", $secret);

        return hash_equals($expectedSignature, $signature);
    }
}
