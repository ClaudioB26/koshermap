<?php

namespace App\Services\Marketing;

use Illuminate\Support\Facades\Http;

/**
 * Cliente mínimo de la API v3 de Brevo. Solo se usa desde la notebook:
 * BREVO_API_KEY NO va en el .env de producción.
 */
class BrevoClient
{
    private const BASE = 'https://api.brevo.com/v3';

    public function __construct(private readonly string $apiKey)
    {
    }

    private function request(string $method, string $path, array $data = [], array $query = []): array
    {
        for ($try = 1; $try <= 3; $try++) {
            $req = Http::withHeaders(['api-key' => $this->apiKey, 'accept' => 'application/json'])->timeout(40);
            $url = self::BASE . $path;
            $res = match (strtoupper($method)) {
                'GET'    => $req->get($url, $query),
                'POST'   => $req->post($url . ($query ? '?' . http_build_query($query) : ''), $data),
                'PUT'    => $req->put($url, $data),
                'DELETE' => $req->delete($url),
            };

            if ($res->status() === 429 && $try < 3) {
                sleep(2 * $try);
                continue;
            }

            if ($res->successful()) {
                return $res->json() ?? [];
            }

            throw new \RuntimeException("Brevo {$method} {$path} -> HTTP {$res->status()}: " . substr($res->body(), 0, 300));
        }

        return [];
    }

    public function account(): array
    {
        return $this->request('GET', '/account');
    }

    /** ID de la carpeta de contactos donde se crean las listas (la primera). */
    public function defaultFolderId(): int
    {
        $folders = $this->request('GET', '/contacts/folders', [], ['limit' => 50])['folders'] ?? [];

        return (int) ($folders[0]['id'] ?? 1);
    }

    /** Devuelve el ID de la lista con ese nombre; la crea si no existe. */
    public function ensureList(string $name): int
    {
        $offset = 0;
        do {
            $page = $this->request('GET', '/contacts/lists', [], ['limit' => 50, 'offset' => $offset]);
            foreach ($page['lists'] ?? [] as $list) {
                if (($list['name'] ?? '') === $name) {
                    return (int) $list['id'];
                }
            }
            $offset += 50;
        } while ($offset < (int) ($page['count'] ?? 0));

        $created = $this->request('POST', '/contacts/lists', ['name' => $name, 'folderId' => $this->defaultFolderId()]);

        return (int) $created['id'];
    }

    public function listInfo(int $listId): array
    {
        return $this->request('GET', "/contacts/lists/{$listId}");
    }

    public function upsertContact(string $email, ?string $firstName, array $listIds): void
    {
        $payload = ['email' => $email, 'listIds' => array_values($listIds), 'updateEnabled' => true];
        if ($firstName) {
            $payload['attributes'] = ['FIRSTNAME' => $firstName];
        }
        $this->request('POST', '/contacts', $payload);
    }

    public function blacklist(string $email): void
    {
        $this->request('PUT', '/contacts/' . rawurlencode($email), ['emailBlacklisted' => true]);
    }

    /** Emails que se dieron de baja / rebotaron / reclamaron, modificados desde $sinceIso. */
    public function blacklistedSince(?string $sinceIso): array
    {
        $emails = [];
        $offset = 0;
        do {
            $query = ['limit' => 500, 'offset' => $offset, 'sort' => 'desc'];
            if ($sinceIso) {
                $query['modifiedSince'] = $sinceIso;
            }
            $page = $this->request('GET', '/contacts', [], $query);
            foreach ($page['contacts'] ?? [] as $c) {
                if (! empty($c['emailBlacklisted']) && ! empty($c['email'])) {
                    $emails[] = mb_strtolower($c['email']);
                }
            }
            $offset += 500;
        } while ($offset < (int) ($page['count'] ?? 0));

        return array_values(array_unique($emails));
    }

    public function createCampaign(array $payload): int
    {
        return (int) $this->request('POST', '/emailCampaigns', $payload)['id'];
    }

    public function sendNow(int $campaignId): void
    {
        $this->request('POST', "/emailCampaigns/{$campaignId}/sendNow");
    }

    public function sendTest(int $campaignId, array $emails): void
    {
        $this->request('POST', "/emailCampaigns/{$campaignId}/sendTest", ['emailTo' => array_values($emails)]);
    }

    public function campaign(int $campaignId): array
    {
        return $this->request('GET', "/emailCampaigns/{$campaignId}", [], ['statistics' => 'globalStats']);
    }
}
