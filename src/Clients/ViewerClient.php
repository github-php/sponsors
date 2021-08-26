<?php

namespace GitHub\Sponsors\Clients;

use GitHub\Sponsors\Contracts\Client;
use GitHub\Sponsors\GraphqlClient;

final class ViewerClient implements Client
{
    private GraphqlClient $client;

    public function __construct(GraphqlClient $client)
    {
        $this->client = $client;
    }

    public function isSponsoredBy(string $sponsor): bool
    {
        $query = <<<'QUERY'
        query (
            $sponsor: String!
        ) {
            user(login: $sponsor) {
                isSponsoringViewer
            }
            organization(login: $sponsor) {
                isSponsoringViewer
            }
        }
        QUERY;

        $result = $this->client->send($query, compact('sponsor'));

        return ($result['user']['isSponsoringViewer'] ?? false) ||
            ($result['organization']['isSponsoringViewer'] ?? false);
    }

    public function isSponsoring(string $account): bool
    {
        $query = <<<'QUERY'
            query (
                $account: String!
            ) {
                user(login: $account) {
                    viewerIsSponsoring
                }
                organization(login: $account) {
                    viewerIsSponsoring
                }
            }
        QUERY;

        $result = $this->client->send($query, compact('account'));

        return ($result['user']['viewerIsSponsoring'] ?? false) ||
            ($result['organization']['viewerIsSponsoring'] ?? false);
    }
}
