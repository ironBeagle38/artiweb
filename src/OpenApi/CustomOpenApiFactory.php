<?php
// src/OpenApi/CustomOpenApiFactory.php
namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Info;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model\SecurityScheme;

class CustomOpenApiFactory implements OpenApiFactoryInterface
{
    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $info = new Info('Hello API Platform', '1.0.0', 'API description');
        $openApi = $openApi->withInfo($info);

        $securityScheme = new SecurityScheme('http');
        $securityScheme = $securityScheme->withScheme('bearer')
            ->withBearerFormat('JWT');

        $components = $openApi->getComponents()->withSecuritySchemes(['bearerAuth' => $securityScheme]);
        $openApi = $openApi->withComponents($components);

        $securityRequirement = ['bearerAuth' => []];
        $openApi = $openApi->withSecurity([$securityRequirement]);

        return $openApi;
    }
}