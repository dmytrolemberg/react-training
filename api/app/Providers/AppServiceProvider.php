<?php

declare(strict_types = 1);

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        if (! $this->app->environment('production') && class_exists(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class)) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->booting(function (): void {
            $this->configureApiDocumentation();
        });
    }

    private function configureApiDocumentation(): void
    {
        if (! class_exists(Scramble::class)) {
            return;
        }

        Scramble::ignoreDefaultRoutes();

        Scramble::registerApi('client', [
            'api_path' => 'api/v1',
            'export_path' => 'docs/client.json',
            'info' => [
                'version' => $this->apiVersion(),
                'description' => 'North Shop client API for catalog browsing, authenticated cart, checkout, orders, profile, wishlist, and reviews.',
            ],
            'ui' => $this->scrambleUiConfig('North Shop Client API'),
        ])->expose(ui: 'docs/client', document: 'docs/client.json');

        Scramble::registerApi('admin', [
            'api_path' => 'admin/api/v1',
            'export_path' => 'docs/admin.json',
            'info' => [
                'version' => $this->apiVersion(),
                'description' => 'North Shop admin API for back-office authentication and administration endpoints.',
            ],
            'ui' => $this->scrambleUiConfig('North Shop Admin API'),
        ])->expose(ui: 'docs/admin', document: 'docs/admin.json');
    }

    private function apiVersion(): string
    {
        $version = config('scramble.info.version');

        return is_string($version) ? $version : '1.0.0';
    }

    /**
     * @return array<string, bool|string>
     */
    private function scrambleUiConfig(string $title): array
    {
        return [
            'title' => $title,
            'theme' => 'light',
            'hide_try_it' => false,
            'hide_schemas' => false,
            'logo' => '',
            'try_it_credentials_policy' => 'include',
            'layout' => 'responsive',
        ];
    }
}
