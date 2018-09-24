<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Models\ProcessOptions;

use professionalweb\IntegrationHub\IntegrationHubDB\Models\ProcessOptions;

class TransitProcessOptions extends ProcessOptions
{
    public function __construct($systemId)
    {
        parent::__construct([
            'subsystem_id' => $systemId,
            'mapping'      => [],
            'options'      => [],
        ]);
    }
}