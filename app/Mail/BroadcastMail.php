<?php

namespace App\Mail;

use App\Models\TemplateEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $emailNama;
    public string $emailSubject;
    public string $emailBody;
    public ?string $headerHtml = null;
    public ?string $footerHtml = null;
    public ?string $headerCss  = null;
    public ?string $footerCss  = null;

    public function __construct(string $nama, string $subject, string $body, ?int $templateId = null)
    {
        $this->emailNama    = $nama;
        $this->emailSubject = $subject;

        $bodyHtml = $body;
        if (is_string($body) && str_starts_with(trim($body), '{"')) {
            $decoded = json_decode($body, true);
            if (is_array($decoded) && isset($decoded['body'])) {
                $bodyHtml = $decoded['body'];
            }
        }

        $this->emailBody = $bodyHtml;

        if ($templateId) {
            $template = TemplateEmail::find($templateId);
            if ($template) {
                $this->headerHtml = $template->header_html;
                $this->footerHtml = $template->footer_html;
                $this->headerCss  = $template->header_css;
                $this->footerCss  = $template->footer_css;
            }
        }
    }

    // FUNGSI BARU: Embed Gambar untuk Body, Header, dan Footer
    public function embedHtml(?string $html, \Illuminate\Mail\Message $message): string
    {
        if (!$html) return '';
        $embedded = [];

        return preg_replace_callback(
            '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i',
            function ($m) use ($message, &$embedded) {
                $src = $m[1];
                if (str_starts_with($src, 'data:') || str_starts_with($src, 'cid:')) {
                    return $m[0];
                }

                $path = parse_url($src, PHP_URL_PATH) ?? '';

                if (!str_contains($path, 'uploads/email-images/') && !str_contains($path, '/images/')) {
                    return $m[0];
                }

                $local = public_path(ltrim($path, '/'));
                $real  = realpath($local);
                
                if (!$real || !str_starts_with($real, realpath(public_path()))) {
                    return $m[0];
                }

                $embedded[$real] ??= $message->embed($real);
                return str_replace($src, $embedded[$real], $m[0]);
            },
            $html
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->emailSubject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.broadcast',
            with: [
                'embedBody'   => fn ($message) => $this->embedHtml($this->emailBody, $message),
                'embedHeader' => fn ($message) => $this->embedHtml($this->headerHtml, $message),
                'embedFooter' => fn ($message) => $this->embedHtml($this->footerHtml, $message),
                'headerHtml'  => $this->headerHtml,
                'footerHtml'  => $this->footerHtml,
                'headerCss'   => $this->headerCss,
                'footerCss'   => $this->footerCss,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}