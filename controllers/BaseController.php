<?php

declare(strict_types=1);

/**
 * Base controller: HTTP orchestration only; no SQL.
 * Views live under views/frontoffice/ or views/backoffice/.
 */
class BaseController
{
    protected function flash(string $type, string $message): void
    {
        if (!$this->ensureSession()) {
            return;
        }

        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    /**
     * @return array{type: string, message: string}|null
     */
    protected function consumeFlash(): ?array
    {
        if (!$this->ensureSession()) {
            return null;
        }

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        return is_array($flash) ? $flash : null;
    }

    private function ensureSession(): bool
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return true;
        }

        return @session_start();
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function renderFrontOffice(string $view, array $data = []): void
    {
        $this->renderArea('frontoffice', $view, $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function renderBackOffice(string $view, array $data = []): void
    {
        $this->renderArea('backoffice', $view, $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function renderArea(string $area, string $view, array $data): void
    {
        $view = ltrim(str_replace(['..', '\\'], ['', '/'], $view), '/');
        $viewFile = __DIR__ . '/../views/' . $area . '/' . $view . '.php';
        if (!is_file($viewFile)) {
            http_response_code(500);
            header('Content-Type: text/plain; charset=UTF-8');
            echo 'View not found.';
            return;
        }

        extract($data, EXTR_SKIP);
        require $viewFile;
    }
}
