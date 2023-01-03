<?php

namespace Viettqt\JetResponse;
class Response implements ResponseInterface
{
    /**
     * HTTP message version (1.0, 1.1 or 2.0).
     */
    protected string $httpVersion = '1.1';

    /**
     * This is the list of currently registered HTTP status codes.
     *
     * @var string[]
     */
    public static array $statusCodes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status', // RFC 4918
        208 => 'Already Reported', // RFC 5842
        226 => 'IM Used', // RFC 3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot', // RFC 2324
        421 => 'Misdirected Request', // RFC7540 (HTTP/2)
        422 => 'Unprocessable Entity', // RFC 4918
        423 => 'Locked', // RFC 4918
        424 => 'Failed Dependency', // RFC 4918
        426 => 'Upgrade Required',
        428 => 'Precondition Required', // RFC 6585
        429 => 'Too Many Requests', // RFC 6585
        431 => 'Request Header Fields Too Large', // RFC 6585
        451 => 'Unavailable For Legal Reasons', // draft-tbray-http-legally-restricted-status
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage', // RFC 4918
        508 => 'Loop Detected', // RFC 5842
        509 => 'Bandwidth Limit Exceeded', // non-standard
        510 => 'Not extended',
        511 => 'Network Authentication Required', // RFC 6585
    ];

    /**
     * Headers
     */
    protected array $headers;
    /**
     * HTTP status code.
     *
     * @var int<100, 999>
     */
    protected int $status;

    /**
     * HTTP status text.
     */
    protected string $statusText;

    /**
     * Body content
     */
    protected array|string|null $body;

    /**
     * Creates the response object.
     *
     * @param int|string $status
     * @param array<string, mixed>|null $headers
     * @param callable|string|null $body
     */
    public function __construct(int|string $status = 500, ?array $headers = null, callable|string $body = null)
    {
        if (null !== $status) {
            $this->setStatus($status);
        }
        if (null !== $headers) {
            $this->setHeaders($headers);
        }
        if (null !== $body) {
            $this->setBody($body);
        }
    }

    /**
     * Returns the current HTTP status code.
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Returns the human-readable status string.
     *
     * In the case of a 200, this may for example be 'OK'.
     */
    public function getStatusText(): string
    {
        return $this->statusText;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

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
    public function setStatus(int|string $status): void
    {
        if (is_int($status) || ctype_digit($status)) {
            $statusCode = $status;
            $statusText = self::$statusCodes[$status] ?? 'Unknown';
        } else {
            list(
                $statusCode,
                $statusText
                ) = explode(' ', $status, 2);
        }
        $statusCode = (int)$statusCode;
        if ($statusCode < 100 || $statusCode > 999) {
            throw new \InvalidArgumentException('The HTTP status code must be exactly 3 digits');
        }

        $this->status = $statusCode;
        $this->statusText = $statusText;
    }

    /**
     * Set content to body response
     * @param array|string|null $content
     * @return void
     */
    public function setBody(array|string|null $content): void
    {
        $this->body = $content;
    }

    /**
     * Send response
     * @return void
     */
    public function send(): void
    {
        header('HTTP/' . $this->httpVersion . ' ' . $this->getStatus() . ' ' . $this->getStatusText());
        http_response_code($this->getStatus());

        foreach ($this->headers as $key => $value) {
            foreach ($value as $k => $v) {
                if (0 === $k) {
                    header($key . ': ' . $v);
                } else {
                    header($key . ': ' . $v, false);
                }
            }
        }

        echo $this->body;
    }

    /**
     * Send response json
     */

    public function sendWithJson(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($this->getStatus());

        echo json_encode($this->body);
    }
}
