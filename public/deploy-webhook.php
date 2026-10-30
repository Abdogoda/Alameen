<?php

declare(strict_types=1);

/**
 * Hostinger GitHub Deployment Webhook Script
 *
 * If you prefer using a direct Webhook URL in GitHub (Settings -> Webhooks)
 * instead of GitHub Actions SSH, this script handles incoming POST requests.
 *
 * Usage:
 * 1. Define DEPLOY_SECRET below or in your .env file as DEPLOY_WEBHOOK_SECRET.
 * 2. In GitHub -> Repo Settings -> Webhooks:
 *    - Payload URL: https://yourdomain.com/deploy-webhook.php
 *    - Content type: application/json
 *    - Secret: (Same value as DEPLOY_SECRET)
 *    - Events: Push event
 */

// --- CONFIGURATION ---
// Change this to a secure random string or configure DEPLOY_WEBHOOK_SECRET in your .env
$secret = getenv('DEPLOY_WEBHOOK_SECRET') ?: 'CHANGE_THIS_TO_YOUR_SECURE_SECRET_TOKEN';
$branch = 'main';
$rootDir = dirname(__DIR__);

// --- VERIFICATION ---
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Only POST accepted.']);
    exit;
}

$payload = file_get_contents('php://input');
$signatureHeader = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

if (empty($signatureHeader) || ! str_starts_with($signatureHeader, 'sha256=')) {
    http_response_code(403);
    echo json_encode(['error' => 'Missing or invalid signature header.']);
    exit;
}

$expectedSignature = 'sha256='.hash_hmac('sha256', $payload, $secret);
if (! hash_equals($expectedSignature, $signatureHeader)) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid webhook signature.']);
    exit;
}

$data = json_decode($payload, true);
if (! is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON payload.']);
    exit;
}

// Check if push event is for target branch
$ref = $data['ref'] ?? '';
if ($ref !== "refs/heads/{$branch}") {
    echo json_encode(['message' => "Push event ignored for ref {$ref}. Only watching {$branch}."]);
    exit;
}

// --- EXECUTE DEPLOYMENT ---
if (! function_exists('shell_exec') || ! function_exists('exec')) {
    http_response_code(500);
    echo json_encode(['error' => 'shell_exec/exec functions are disabled on this server. Please use GitHub Actions SSH deployment instead.']);
    exit;
}

$scriptPath = $rootDir.DIRECTORY_SEPARATOR.'scripts'.DIRECTORY_SEPARATOR.'deploy.sh';

if (! file_exists($scriptPath)) {
    http_response_code(500);
    echo json_encode(['error' => "Deployment script not found at {$scriptPath}"]);
    exit;
}

// Execute deployment
$command = "cd {$rootDir} && bash {$scriptPath} 2>&1";
$output = shell_exec($command);

echo json_encode([
    'status' => 'success',
    'message' => 'Deployment script executed.',
    'output' => $output,
]);
