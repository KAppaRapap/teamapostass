<?php

namespace App\Services;

use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FootballApiService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.football_data.base_uri'),
            'headers' => [
                'X-Auth-Token' => config('services.football_data.token'),
                'Accept' => 'application/json',
            ],
            'timeout' => 5,
            'verify' => false, // Ignora SSL para ambiente local
        ]);
    }

    /**
     * Get upcoming Totobola matches (scheduled next Sunday).
     *
     * @return array
     */
    public function getUpcomingSundayMatches(): array
    {
        $today = Carbon::now()->toDateString();
        $sunday = Carbon::now()->next(Carbon::SUNDAY)->toDateString();

        try {
            $response = $this->client->get('matches', [
                'query' => [
                    'dateFrom' => $today,
                    'dateTo'   => $sunday,
                    'status'   => 'SCHEDULED',
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            \Log::info('FootballApiService matches response', ['response' => $data]);
            return $data['matches'] ?? $data['data'] ?? [];
        } catch (\Exception $e) {
            Log::error('FootballApiService error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get available leagues (competitions)
     *
     * @return array
     */
    public function getLeagues(): array
    {
        try {
            $response = $this->client->get('competitions', [
                'query' => [
                    'plan' => 'TIER_ONE',
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            $leagues = $data['competitions'] ?? $data['data'] ?? [];

            // Liga principal de cada país
            $mainLeagues = [
                'PL',    // Premier League
                'PD',    // La Liga
                'SA',    // Serie A
                'BL1',   // Bundesliga
                'FL1',   // Ligue 1
            ];
            // Ligas europeias principais
            $europeanLeagues = [
                'CL',    // Champions League
                'EL',    // Europa League
                'EC',    // Conference League
                'WC',    // World Cup
                'EURO',  // Euro
            ];

            // Tradução dos países para português
            $countryPT = [
                'England' => 'Inglaterra',
                'Spain' => 'Espanha',
                'Italy' => 'Itália',
                'Germany' => 'Alemanha',
                'France' => 'França',
                'Portugal' => 'Portugal',
                'Netherlands' => 'Países Baixos',
                'Europe' => 'Europa',
                'World' => 'Mundo',
                'Russia' => 'Rússia',
                'Belgium' => 'Bélgica',
                'Turkey' => 'Turquia',
                'Greece' => 'Grécia',
                'Brazil' => 'Brasil',
                'Argentina' => 'Argentina',
                'Ukraine' => 'Ucrânia',
                // Adicione mais conforme necessário
            ];
            // Ordem dos países principais
            $mainCountries = [
                'Inglaterra', 'Espanha', 'Itália', 'Alemanha', 'França'
            ];
            // Só mostrar Primeira Liga de Portugal como principal
            $mainPortugalCode = 'PPL';
            // Agrupar por país
            $byCountry = [];
            $european = [];
            $mainLeaguesGroup = [];
            foreach ($leagues as &$league) {
                $country = $league['area']['name'] ?? ($league['country'] ?? null);
                $code = $league['code'] ?? $league['id'];
                $countryPTName = $countryPT[$country] ?? $country;
                $league['display_name'] = $league['name'] . ($countryPTName ? " ($countryPTName)" : '');
                // Só Primeira Liga (PPL) como principal de Portugal
                if ($countryPTName === 'Portugal' && $code === $mainPortugalCode) {
                    $mainLeaguesGroup[$countryPTName][] = $league;
                } elseif (in_array($countryPTName, $mainCountries) && $countryPTName !== 'Portugal') {
                    $mainLeaguesGroup[$countryPTName][] = $league;
                } elseif ($countryPTName === 'Europa' || $countryPTName === 'Mundo') {
                    $european[] = $league;
                } else {
                    $byCountry[$countryPTName][] = $league;
                }
            }
            // Ordenar ligas dentro de cada grupo
            foreach ($mainLeaguesGroup as &$list) {
                usort($list, function($a, $b) use ($mainLeagues) {
                    $aIdx = array_search($a['code'] ?? $a['id'], $mainLeagues);
                    $bIdx = array_search($b['code'] ?? $b['id'], $mainLeagues);
                    if ($aIdx === false) $aIdx = 99;
                    if ($bIdx === false) $bIdx = 99;
                    return $aIdx <=> $bIdx ?: strcmp($a['name'], $b['name']);
                });
            }
            foreach ($byCountry as &$list) {
                usort($list, function($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
            }
            usort($european, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            // Juntar: 5 principais, outros países, depois europeias
            $final = [];
            foreach ($mainCountries as $c) {
                if (isset($mainLeaguesGroup[$c])) {
                    $final = array_merge($final, $mainLeaguesGroup[$c]);
                }
            }
            foreach ($byCountry as $list) {
                $final = array_merge($final, $list);
            }
            $final = array_merge($final, $european);
            return $final;
        } catch (\Exception $e) {
            Log::error('FootballApiService error (leagues): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get matches for a specific league (competition code)
     *
     * @param string $leagueCode
     * @return array
     */
    public function getMatchesForLeague(string $leagueCode): array
    {
        try {
            $response = $this->client->get("competitions/{$leagueCode}/matches", [
                'query' => [
                    'status'   => 'SCHEDULED',
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            return $data['matches'] ?? $data['data'] ?? [];
        } catch (\Exception $e) {
            Log::error('FootballApiService error (matches): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get last 5 matches for a team
     * @param int $teamId
     * @return array
     */
    public function getLastMatchesForTeam($teamId): array
    {
        try {
            $response = $this->client->get("teams/{$teamId}/matches", [
                'query' => [
                    'limit' => 5,
                    'status' => 'FINISHED',
                    'order' => 'desc',
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            \Log::info('getLastMatchesForTeam', ['teamId' => $teamId, 'response' => $data]);
            return $data['matches'] ?? $data['data'] ?? [];
        } catch (\Exception $e) {
            Log::error('FootballApiService error (team matches): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get extended info for a match (venue, date, teams, last5, favorite)
     * @param array $match
     * @return array
     */
    public function getMatchExtendedInfo(array $match): array
    {
        $homeId = $match['homeTeam']['id'] ?? null;
        $awayId = $match['awayTeam']['id'] ?? null;
        $venue  = $match['venue'] ?? ($match['stadium']['name'] ?? ($match['stadium'] ?? null));
        $utcDate = $match['utcDate'] ?? null;
        $info = [
            'date' => $utcDate,
            'venue' => $venue,
            'homeTeam' => $match['homeTeam']['name'] ?? '?',
            'awayTeam' => $match['awayTeam']['name'] ?? '?',
            'homeId' => $homeId,
            'awayId' => $awayId,
            'favorite' => null,
            'last5_home' => [],
            'last5_away' => [],
        ];
        if ($homeId && $awayId) {
            $last5_home = $this->getLastMatchesForTeam($homeId);
            $last5_away = $this->getLastMatchesForTeam($awayId);
            $info['last5_home'] = $this->extractResults($last5_home, $homeId);
            $info['last5_away'] = $this->extractResults($last5_away, $awayId);

            // Melhor lógica para favorito
            if (empty($info['last5_home']) && !empty($info['last5_away'])) {
                $info['favorite'] = $info['awayTeam'];
            } elseif (empty($info['last5_away']) && !empty($info['last5_home'])) {
                $info['favorite'] = $info['homeTeam'];
            } elseif (empty($info['last5_home']) && empty($info['last5_away'])) {
                $info['favorite'] = 'Sem dados';
            } else {
                $homeWins = count(array_filter($info['last5_home'], fn($r) => $r === 'V'));
                $awayWins = count(array_filter($info['last5_away'], fn($r) => $r === 'V'));
                if ($homeWins > $awayWins) {
                    $info['favorite'] = $info['homeTeam'];
                } elseif ($awayWins > $homeWins) {
                    $info['favorite'] = $info['awayTeam'];
                } else {
                    $info['favorite'] = 'Equilíbrio';
                }
            }
        }
        return $info;
    }

    /**
     * Helper: Extract V/E/D results for a team
     * @param array $matches
     * @param int $teamId
     * @return array
     */
    private function extractResults(array $matches, $teamId): array
    {
        $results = [];
        foreach ($matches as $m) {
            if (!isset($m['score']['winner'])) {
                $results[] = 'E';
                continue;
            }
            if ($m['score']['winner'] === 'DRAW') {
                $results[] = 'E';
            } elseif (
                ($m['homeTeam']['id'] == $teamId && $m['score']['winner'] === 'HOME_TEAM') ||
                ($m['awayTeam']['id'] == $teamId && $m['score']['winner'] === 'AWAY_TEAM')
            ) {
                $results[] = 'V';
            } else {
                $results[] = 'D';
            }
        }
        return $results;
    }
}
