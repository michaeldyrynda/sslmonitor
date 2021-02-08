<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Iodev\Whois\Factory as WhoisFactory;
use Iodev\Whois\Modules\Tld\TldInfo;
use Iodev\Whois\Modules\Tld\TldServer;
use Iodev\Whois\Whois;
use Spatie\SslCertificate\SslCertificate;
use Throwable;

class SiteHealth
{
    public string $domain;

    public ?SslCertificate $certificate;

    public ?TldInfo $whois;

    private function __construct(string $domain)
    {
        $this->domain = $domain;

        $this->lookupCertificate();
        $this->lookupDomain();
    }

    public static function make(string $domain): static
    {
        return new static($domain);
    }

    protected function lookupCertificate(): void
    {
        $this->certificate = SslCertificate::createForHostName($this->domain);
    }

    protected function lookupDomain(): void
    {
        try {
            $this->whois = cache()->remember(
                "whois:{$this->domain}",
                Date::now()->addHours(6),
                fn () => $this->getWhois()->loadDomainInfo($this->domain)
            );
        } catch (Throwable $e) {
            // no-op
            $this->whois = null;
        }
    }

    public function isDomainValid(): ?bool
    {
        if (! $this->whois) {
            return true;
        }

        return collect($this->whois->states ?? [])->contains(fn ($value) => in_array($value, [
            'ok',
            'serverrenewprohibited',
        ]));
    }

    public function domainStatus(): ?string
    {
        if (! $this->whois) {
            return null;
        }

        return collect(Arr::wrap(data_get($this->whois->getExtra(), 'groups.*.Status'), []))
            ->flatten()
            ->map(fn ($status) => Str::of($status)->before(' '))
            ->implode(', ');
    }

    protected function getWhois(): Whois
    {
        return tap(WhoisFactory::get()->createWhois(), function (Whois $whois) {
            $whois->getTldModule()->setServers(
                TldServer::fromDataList([
                    ['zone' => '.au', 'host' => 'whois.auda.tld'],
                    ['zone' => '.au', 'host' => 'whois.auda.org.au'],
                ])
            );
        });
    }
}
