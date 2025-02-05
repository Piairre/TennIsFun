<?php

namespace App\Dto;

use Symfony\Component\Intl\Countries;

class CountryDto
{
    public ?string $country;
    public ?string $countryCode;
    public ?string $flag = null;

    public function __construct(?string $country, ?string $countryCode)
    {
        $this->country = $country;
        $this->countryCode = $countryCode;

        $this->completeInfo();
    }

    private function completeInfo(): void
    {
        if (is_null($this->getCountry()) && is_null($this->getCountryCode())) {
            // We can't complete the info
            return;
        }

        if (!is_null($this->getCountryCode())) {
            // Complete the country name
            if (Countries::alpha3CodeExists($this->getCountryCode())) {
                $this->setCountry(Countries::getAlpha3Name($this->getCountryCode()));
                $this->setCountryCode(Countries::getAlpha2Code($this->getCountryCode()));
            }
        }

        if (!is_null($this->getCountry())) {
            // Complete the country code
            foreach (Countries::getAlpha3Names() as $iso3 => $country) {
                if (strtolower($country) === strtolower($this->getCountry())) {
                    $countryCode = Countries::getAlpha2Code($iso3);
                    $this->setCountryCode($countryCode);
                    break;
                }
            }
        }

        $this->setFlag('https://flagcdn.com/24x18/' . strtolower($this->getCountryCode()) . '.png');
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(string $flag): void
    {
        $this->flag = $flag;
    }
}
