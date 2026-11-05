<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncApidogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apidog:sync
                            {--project-id= : Override the Apidog Project ID (defaults to APIDOG_PROJECT_ID)}
                            {--token= : Override the Apidog Access Token (defaults to APIDOG_ACCESS_TOKEN)}
                            {--behavior=OVERWRITE_EXISTING : Overwrite behavior (OVERWRITE_EXISTING, AUTO_MERGE, KEEP_EXISTING, CREATE_NEW)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize local swagger.json / public/swagger.json and push the OpenAPI 3.0.3 spec to Apidog';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $rootSpecPath = base_path('swagger.json');
        $publicSpecPath = public_path('swagger.json');

        $sourcePath = $this->synchronizeLocalSpecFiles($rootSpecPath, $publicSpecPath);
        if (! $sourcePath) {
            $this->error('Could not find swagger.json or public/swagger.json.');

            return self::FAILURE;
        }

        $rawContent = (string) file_get_contents($sourcePath);
        $decoded = json_decode($rawContent, true);

        if (! is_array($decoded) || empty($decoded['openapi'])) {
            $this->error("Invalid OpenAPI JSON in [{$sourcePath}].");

            return self::FAILURE;
        }

        $projectId = (string) ($this->option('project-id') ?: config('services.apidog.project_id'));
        $accessToken = (string) ($this->option('token') ?: config('services.apidog.access_token'));
        $behavior = (string) ($this->option('behavior') ?: config('services.apidog.overwrite_behavior', 'OVERWRITE_EXISTING'));

        if ($projectId === '' || $accessToken === '') {
            $this->error('Missing Apidog credentials. Please set APIDOG_PROJECT_ID and APIDOG_ACCESS_TOKEN in your .env file.');

            return self::FAILURE;
        }

        $this->info("Syncing OpenAPI 3.0.3 specification to Apidog Project [{$projectId}]...");

        $url = "https://api.apidog.com/v1/projects/{$projectId}/import-openapi?locale=en-US";

        $response = Http::withHeaders([
            'X-Apidog-Api-Version' => '2024-03-28',
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->timeout(60)->post($url, [
            'input' => $rawContent,
            'options' => [
                'targetEndpointFolderId' => 0,
                'targetSchemaFolderId' => 0,
                'endpointOverwriteBehavior' => $behavior,
                'schemaOverwriteBehavior' => $behavior,
                'updateFolderOfChangedEndpoint' => true,
                'prependBasePath' => false,
            ],
        ]);

        if (! $response->successful()) {
            $this->error("Apidog sync failed (HTTP {$response->status()}): ".$response->body());

            return self::FAILURE;
        }

        $counters = $response->json('data.counters') ?? [];
        if (is_array($counters) && ! empty($counters)) {
            $this->table(
                ['Metric', 'Count'],
                collect($counters)->map(fn ($value, $key) => [$key, (string) $value])->values()->all()
            );
        }

        $this->info('OpenAPI 3.0.3 specification successfully synced to Apidog!');

        return self::SUCCESS;
    }

    /**
     * Keep swagger.json and public/swagger.json identical based on whichever was modified most recently.
     */
    protected function synchronizeLocalSpecFiles(string $rootSpecPath, string $publicSpecPath): ?string
    {
        $rootExists = file_exists($rootSpecPath);
        $publicExists = file_exists($publicSpecPath);

        if (! $rootExists && ! $publicExists) {
            return null;
        }

        if ($rootExists && $publicExists) {
            $rootMtime = (int) filemtime($rootSpecPath);
            $publicMtime = (int) filemtime($publicSpecPath);

            if ($rootMtime > $publicMtime) {
                copy($rootSpecPath, $publicSpecPath);
                $this->line('Synchronized [swagger.json] -> [public/swagger.json].');

                return $rootSpecPath;
            }

            if ($publicMtime > $rootMtime) {
                copy($publicSpecPath, $rootSpecPath);
                $this->line('Synchronized [public/swagger.json] -> [swagger.json].');

                return $publicSpecPath;
            }

            return $publicSpecPath;
        }

        if ($rootExists) {
            copy($rootSpecPath, $publicSpecPath);

            return $rootSpecPath;
        }

        copy($publicSpecPath, $rootSpecPath);

        return $publicSpecPath;
    }
}

