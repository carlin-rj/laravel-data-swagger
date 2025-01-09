<?php

namespace Carlin\LaravelDataSwagger\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use L5Swagger\GeneratorFactory;
use Carlin\LaravelDataSwagger\Helper\Str;

class GenerateDocsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-data-swagger:generate {documentation?} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate docs';

    /**
     * Execute the console command.
     *
     * @param GeneratorFactory $generatorFactory
     * @return void
     *
	 * @throws \JsonException
     */
    public function handle(GeneratorFactory $generatorFactory): void
    {
        $all = $this->option('all');

		$inputDocumentation = $this->argument('documentation') ?? config('l5-swagger.default');
		$documentations = array_keys(config('l5-swagger.documentations', []));

		foreach ($documentations as $documentation) {
			if ($all || $documentation === $inputDocumentation) {

				//生成文档
				$this->generateDocumentation($generatorFactory, $documentation);

				//转换命名格式
				$this->transformDocs($documentation);
			}
		}
    }


    private function generateDocumentation(GeneratorFactory $generatorFactory, string $documentation): void
    {
        $this->info('Regenerating docs '.$documentation);

        $generator = $generatorFactory->make($documentation);
        $generator->generateDocs();
    }

    private function transformDocs(?string $documentation = 'default'): void
    {
        $docsFile = sprintf('%s/%s',
            config("l5-swagger.defaults.paths.docs"),
            config("l5-swagger.documentations.$documentation.paths.docs_json") ?? 'api-docs.json',
        );

        $docs = File::get($docsFile);
        $docs = json_decode($docs, true, 512, JSON_THROW_ON_ERROR);
        $paths = &$docs['paths'];
        foreach ($paths as &$pathInfo) {
            foreach ($pathInfo as &$methodInfo) {
               if (isset($methodInfo['requestBody']['content'])) {
                   foreach ($methodInfo['requestBody']['content'] as &$contentInfo) {
                       if (isset($contentInfo['schema'])) {
                           $this->transformSchema($documentation, $contentInfo['schema']);
                       }
                   }
               }

               if (isset($methodInfo['responses'])) {
                   foreach ($methodInfo['responses'] as &$responses) {
                       foreach ($responses['content'] as &$contents) {
                           if (isset($contents['schema'])) {
                               $this->transformSchema($documentation, $contents['schema']);
                           }
                       }
                   }

               }
            }
        }
        $schemas = &$docs['components']['schemas'];
        foreach ($schemas as &$schemaData) {
            $this->transformSchema($documentation, $schemaData);
        }
        File::put($docsFile, json_encode($docs, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE));
    }

    private function transformSchema(string $documentation, array &$schema): void
    {
		if(!empty($schema['required'])) {
			$schema['required'] = array_map(function($required) use ($documentation) {
				return $this->transform($documentation, $required);
			}, $schema['required']);
		}

        if (!empty($schema['properties'])) {
            foreach ($schema['properties'] as $propertyName => $property) {
                $propertyNewName = $this->transform($documentation, $propertyName);
                if ($propertyNewName !== $propertyName) {
                    $schema['properties'][$propertyNewName] = $property;
                    unset($schema['properties'][$propertyName]);
                }
            }
        }
    }

    private function transform(string $documentation, string $value): string
    {
       return config("laravel-data-swagger.documentations.{$documentation}.is_camel") ? Str::camel($value) : Str::snake($value);
    }
}
