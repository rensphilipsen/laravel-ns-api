<?php

namespace RensPhilipsen\NSApi\Actions;

use InvalidArgumentException;

trait ManagesDisruptions
{
    /**
     * Get disruptions, calamities and maintenance items.
     *
     * @param array $query
     * @param string|null $acceptLanguage
     * @return mixed
     */
    public function getDisruptions(array $query = [], ?string $acceptLanguage = null)
    {
        return $this->get(
            $this->disruptionsUri('/v3'),
            $query,
            $this->languageHeader($acceptLanguage)
        );
    }

    /**
     * Get a single disruption by type and id.
     *
     * @param string $type
     * @param string $id
     * @param string|null $acceptLanguage
     * @return mixed
     */
    public function getDisruption(string $type, string $id, ?string $acceptLanguage = null)
    {
        $this->guardDisruptionType($type);

        return $this->get(
            $this->disruptionsUri(sprintf('/v3/%s/%s', $type, rawurlencode($id))),
            [],
            $this->languageHeader($acceptLanguage)
        );
    }

    /**
     * Get disruptions for a station code.
     *
     * @param string $stationCode
     * @param string|null $acceptLanguage
     * @return mixed
     */
    public function getDisruptionsByStationCode(string $stationCode, ?string $acceptLanguage = null)
    {
        return $this->get(
            $this->disruptionsUri(sprintf('/v3/station/%s', rawurlencode($stationCode))),
            [],
            $this->languageHeader($acceptLanguage)
        );
    }

    private function disruptionsUri(string $path): string
    {
        return rtrim($this->disruptionsApiUrl, '/') . $path;
    }

    private function languageHeader(?string $acceptLanguage): array
    {
        if ($acceptLanguage === null || $acceptLanguage === '') {
            return [];
        }

        return ['Accept-Language' => $acceptLanguage];
    }

    private function guardDisruptionType(string $type): void
    {
        $allowedTypes = ['disruption', 'maintenance', 'calamity'];

        if (!in_array($type, $allowedTypes, true)) {
            throw new InvalidArgumentException(sprintf(
                'Disruption type must be one of: %s.',
                implode(', ', $allowedTypes)
            ));
        }
    }
}