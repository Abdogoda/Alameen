<?php

declare(strict_types=1);

namespace Tests\Feature\Architecture;

use Illuminate\Foundation\Http\FormRequest;
use Tests\TestCase;

class RequestValidationArchitectureTest extends TestCase
{
    /**
     * Ensure controllers never contain inline request validation.
     */
    public function test_controllers_do_not_contain_inline_request_validation(): void
    {
        $controllersPath = app_path('Http/Controllers');
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($controllersPath));

        foreach ($files as $file) {
            /** @var \SplFileInfo $file */
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $content = file_get_contents($file->getRealPath());
            $this->assertIsString($content);

            $this->assertDoesNotMatchRegularExpression(
                '/(?:->validate\s*\(|Validator::make\s*\()/',
                $content,
                "Controller [{$file->getFilename()}] contains inline validation. Use a dedicated FormRequest instead."
            );
        }
    }

    /**
     * Ensure all created FormRequests extend FormRequest and define valid rules.
     */
    public function test_all_form_requests_have_authorization_and_rules(): void
    {
        $requestsPath = (string) realpath(app_path('Http/Requests'));
        $this->assertDirectoryExists($requestsPath);

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($requestsPath));

        $foundCount = 0;
        foreach ($files as $file) {
            /** @var \SplFileInfo $file */
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $realFilePath = (string) $file->getRealPath();
            $relativePath = str_replace([$requestsPath.DIRECTORY_SEPARATOR, '.php'], ['', ''], $realFilePath);
            $className = 'App\\Http\\Requests\\'.str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

            $this->assertTrue(class_exists($className), "Class {$className} must exist.");

            $reflection = new \ReflectionClass($className);
            $this->assertTrue(
                $reflection->isSubclassOf(FormRequest::class),
                "{$className} must extend ".FormRequest::class
            );

            /** @var FormRequest $instance */
            $instance = new $className;
            $this->assertTrue($instance->authorize(), "{$className}::authorize() should return true.");

            $rules = $instance->rules();
            $this->assertIsArray($rules, "{$className}::rules() must return an array.");
            $this->assertNotEmpty($rules, "{$className}::rules() must not be empty.");

            $foundCount++;
        }

        $this->assertGreaterThanOrEqual(20, $foundCount, 'Expected at least 20 FormRequest classes to be registered.');
    }
}
