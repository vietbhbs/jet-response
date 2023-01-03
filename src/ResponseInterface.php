<?php

namespace Viettqt\JetResponse;
interface ResponseInterface
{
    /**
     * Sets a new set of HTTP headers.
     *
     * The headers array should contain headernames for keys, and their value
     * should be specified as either a string or an array.
     *
     * Any header that already existed will be overwritten.
     *
     * @param array<string, mixed> $headers
     */
    public function setHeaders(array $headers): void;

    /**
     * Returns the current HTTP status code.
     */
    public function getStatus(): int;

    /**
     * Returns the human-readable status string.
     *
     * In the case of a 200, this may for example be 'OK'.
     */
    public function getStatusText(): string;

    /**
     * Sets the HTTP status code.
     *
     * This can be either the full HTTP status code with human-readable string,
     * for example: "403 I can't let you do that, Dave".
     *
     * Or just the code, in which case the appropriate default message will be
     * added.
     *
     * @param int|string $status
     *
     * @throws \InvalidArgumentException
     */
    public function setStatus(int|string $status): void;

    /**
     * Set content to body
     */

    public function setBody(array|string|null $content): void;
}