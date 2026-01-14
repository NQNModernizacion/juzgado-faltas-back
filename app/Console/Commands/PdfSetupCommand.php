<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class PdfSetupCommand extends Command
{
    protected $signature = 'pdf:setup';
    protected $description = 'Setup completo spatie/laravel-pdf + puppeteer: crea .puppeteerrc, npm install si falta, baja navegador y ajusta permisos (Linux).';

    public function handle(): int
    {
        $root = base_path();
        // dd(PHP_OS_FAMILY);
        // dd($root);
        $rcFile = $root . DIRECTORY_SEPARATOR . '.puppeteerrc.cjs';
        $cacheDir = $root . DIRECTORY_SEPARATOR . '.cache' . DIRECTORY_SEPARATOR . 'puppeteer';

        $this->info("== PDF SETUP ==");
        $this->line("Root: {$root}");

        // 1) .puppeteerrc.cjs
        if (!File::exists($rcFile)) {
            File::put($rcFile, <<<'CJS'
const { join } = require("path");

/**
 * @type {import("puppeteer").Configuration}
 */
module.exports = {
    cacheDirectory: join(__dirname, ".cache", "puppeteer"),
};
CJS);
            $this->info("OK: creado .puppeteerrc.cjs");
        } else {
            $this->line("OK: .puppeteerrc.cjs ya existe");
        }

        // 2) cache dir
        File::ensureDirectoryExists($cacheDir);
        $this->line("OK: cache listo: {$cacheDir}");

        // 3) npm install si falta node_modules
        if (!File::exists($root . DIRECTORY_SEPARATOR . 'node_modules')) {
            $this->info("node_modules no existe -> ejecutando npm install...");
            $this->runCmd("npm install");
        } else {
            $this->line("OK: node_modules ya existe");
        }

        // 4) descargar navegador
        $installJs = $root . DIRECTORY_SEPARATOR . 'node_modules' . DIRECTORY_SEPARATOR . 'puppeteer' . DIRECTORY_SEPARATOR . 'install.js';

        if (File::exists($installJs)) {
            $this->info("Descargando navegador via Puppeteer install.js ...");
            $cmd = $this->isWindows()
                ? 'node node_modules\puppeteer\install.js'
                : 'node node_modules/puppeteer/install.js';
            $this->runCmd($cmd);
        } else {
            $this->warn("No encontré node_modules/puppeteer/install.js. Intento método moderno: npx puppeteer browsers install chrome ...");
            // $this->runCmd("npx puppeteer browsers install chrome");
        }

        // 5) permisos Linux (sin romper ejecutables)
        if (!$this->isWindows()) {
            $this->info("Ajustando permisos del cache (Linux)...");
            $this->runCmd("chmod -R a+rX " . escapeshellarg($root . '/.cache/puppeteer') . " || true");
            $this->runCmd("chown -R www-data:www-data " . escapeshellarg($root . '/.cache/puppeteer') . " || true");
        }

        if (env('APP_ENV') == 'local') {
            $this->appendEnvIfMissing([
                "NODE_PATH" => "",
                "NPM_PATH" => ""
            ]);
        }
        if (env('APP_ENV') == 'replica' || env('APP_ENV') == 'production') {
            $this->appendEnvIfMissing([
                "NODE_PATH" => "/usr/bin/node",
                "NPM_PATH" => "/usr/bin/npm"
            ]);
        }

        $this->info("== FIN pdf:setup ==");
        return self::SUCCESS;
    }

    private function runCmd(string $cmd): void
    {
        $this->line(">> {$cmd}");

        $p = Process::fromShellCommandline($cmd, base_path());
        $p->setTimeout(null);

        $p->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        if (!$p->isSuccessful()) {
            $err = trim($p->getErrorOutput());
            throw new \RuntimeException($err !== '' ? $err : "Error ejecutando: {$cmd}");
        }
    }

    private function isWindows(): bool
    {
        return PHP_OS_FAMILY === 'Windows';
    }


    private function appendEnvIfMissing(array $vars): void
    {
        $envPath = base_path('.env');

        if (! File::exists($envPath)) {
            throw new \RuntimeException("No existe el archivo .env en: {$envPath}");
        }

        $content = File::get($envPath);

        $toAppend = '';
        foreach ($vars as $key => $value) {
            // ya existe la key? (KEY=...)
            if (preg_match('/^\s*' . preg_quote($key, '/') . '\s*=/m', $content)) {
                continue;
            }

            // si el valor tiene espacios o #, lo comillamos
            $valueStr = (string) $value;
            if (preg_match('/\s|#|=/', $valueStr)) {
                $valueStr = '"' . str_replace('"', '\"', $valueStr) . '"';
            }

            $toAppend .= "{$key}={$valueStr}\n";
        }

        if ($toAppend !== '') {
            // Asegura que el archivo termine con \n antes de append
            if ($content !== '' && !str_ends_with($content, "\n")) {
                File::append($envPath, "\n");
            }

            File::append($envPath, "\n# --- Auto agregado por pdf:setup ---\n" . $toAppend);
        }
    }
}
