<?php

// src/EventListener/SwaggerUiListener.php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class SwaggerUiListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        if ($response->getStatusCode() !== 200 || $event->getRequest()->getPathInfo() !== '/api/docs') {
            return;
        }

        $content = $response->getContent();
        $content = str_replace(
            '</head>',
            '<script>
                window.onload = function() {
                    const ui = SwaggerUIBundle({
                        url: "swagger.json",
                        dom_id: "#swagger-ui",
                        presets: [
                            SwaggerUIBundle.presets.apis,
                            SwaggerUIStandalonePreset
                        ],
                        layout: "StandaloneLayout"
                    });
                    ui.initOAuth({
                        clientId: "your-client-id",
                        clientSecret: "your-client-secret",
                        realm: "your-realms",
                        appName: "your-app-name",
                        scopeSeparator: " ",
                        additionalQueryStringParams: {}
                    });
                    ui.preauthorizeApiKey("bearerAuth", "your-token");
                };
            </script></head>',
            $content
        );

        $response->setContent($content);
    }
}
