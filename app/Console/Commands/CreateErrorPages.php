<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateErrorPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:error-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate default error pages (404, 500, 403)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // List of error pages to create
        $errorPages = [
            404 => 'Page Not Found',
            500 => 'Server Error',
            403 => 'Forbidden',
            419 => 'Page Expired',
            429 => 'Too Many Requests'
        ];

        // Path to store the error pages
        $errorPath = resource_path('views/errors');

        // Create the errors directory if it doesn't exist
        if (!File::exists($errorPath)) {
            File::makeDirectory($errorPath, 0755, true);
        }

        // Create each error page
        foreach ($errorPages as $code => $message) {
            $filePath = $errorPath . "/$code.blade.php";
            if (!File::exists($filePath)) {
                $content = $this->generateErrorPageContent($code, $message);
                File::put($filePath, $content);
                $this->info("Created error page: $filePath");
            } else {
                $this->info("Error page $code already exists.");
            }
        }

        return 0;
    }

    /**
     * Generate content for the error page.
     *
     * @param int $code
     * @param string $message
     * @return string
     */
    protected function generateErrorPageContent($code, $message)
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>$code - $message</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    text-align: center;
                    padding: 100px;
                    background-color: #f8fafc;
                }
                h1 {
                    font-size: 60px;
                    color: #636b6f;
                }
                p {
                    font-size: 24px;
                    color: #4a5568;
                }
                a {
                    text-decoration: none;
                    color: #3490dc;
                    font-size: 18px;
                }
            </style>
        </head>
        <body>
            <h1>$code</h1>
            <p>$message</p>
            <a href="{{ url('/home') }}">Go back to Home</a>
        </body>
        </html>
        HTML;
    }
}
